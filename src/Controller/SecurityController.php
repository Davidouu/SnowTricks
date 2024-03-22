<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\UserFormType;
use App\Entity\User;
use App\Form\ResetPasswordFormType;
use App\Service\SendMail;
use App\Service\ValidateToken;
use App\Form\EmailResetPasswordFormType;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
    }

    #[Route(path: '/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, SendMail $sendMail): Response
    {
        $user = new User();

        $form = $this->createForm(UserFormType::class, $user)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les données du formulaire
            $registrationForm = $form->getData();

            // On récupère le mot de passe en clair
            $plainPassword = $registrationForm->getPassword();

            // On hash le mot de passe
            $hashedPassword = $passwordHasher->hashPassword(
                $registrationForm,
                $plainPassword
            );

            // On remplace le mot de passe en clair par le mot de passe hashé
            $user->setPassword($hashedPassword);

            // On génère un token pour la confirmation du compte
            $user->setToken(md5(uniqid()));

            // On sauvegarde l'utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // On envoie un email de confirmation grâce au service Mailer
            $sendMail->sendMail($user);

            // Ajout du message flash
            $this->addFlash('success', 'Votre compte a bien été créé ! Vous allez recevoir un email de confirmation.');

            // On redirige l'utilisateur vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/confirmation/{id}/{token}', name: 'app_confirm_account')]
    public function confirmAccount(User $user, string $token, ValidateToken $checker): Response
    {
        try {
            $checker->handleEmailConfirmation($token, $user);
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Votre compte a bien été confirmé ! Vous pouvez maintenant vous connecter.');

        return $this->redirectToRoute('app_login');
    }

    #[Route(path: '/mot-de-passe-oublie', name: 'app_forgot_password')]
    public function forgotPassword(Request $request, EntityManagerInterface $entityManager, SendMail $sendMail): Response
    {
        $form = $this->createForm(EmailResetPasswordFormType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User;

            // On récupère les données du formulaire
            $resetForm = $form->getData();

            // On récupère l'email
            $email = $resetForm['email'];

            // On recherche l'utilisateur par son email
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            // Si l'utilisateur n'existe pas
            if (!$user) {
                $this->addFlash('danger', 'Cet email n\'existe pas.');

                return $this->redirectToRoute('app_forgot_password');
            }

            // On génère un token pour la réinitialisation du mot de passe
            $user->setResetToken(md5(uniqid()));

            // On génère une date d'expiration pour le token d'une semaine
            $user->setResetTokenExpiration(new \DateTime('+1 week'));

            // On sauvegarde l'utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // On envoie un email de réinitialisation grâce au service Mailer
            $sendMail->sendResetMail($user);

            // Ajout du message flash
            $this->addFlash('success', 'Un email de réinitialisation de mot de passe vous a été envoyé.');

            // On redirige l'utilisateur vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/forgot_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/reinitialisation-mot-de-passe/{id}/{token}', name: 'app_reset_password')]
    public function resetPassword(User $user, string $token, ValidateToken $checker, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        try {
            $checker->handlePasswordReset($token, $user);
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
            
            return $this->redirectToRoute('app_forgot_password');
        }

        $form = $this->createForm(ResetPasswordFormType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les données du formulaire
            $resetPasswordForm = $form->getData();

            // On récupère le mot de passe en clair
            $plainPassword = $resetPasswordForm['password'];

            // On récupère la confirmation du mot de passe en clair
            $confirmPassword = $resetPasswordForm['confirm_password'];

            // On vérifie que les deux mots de passe sont identiques
            if ($plainPassword !== $confirmPassword) {
                $this->addFlash('danger', 'Les mots de passe ne correspondent pas.');

                return $this->redirectToRoute('app_reset_password', ['id' => $user->getId(), 'token' => $token]);
            }

            // On hash le mot de passe
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plainPassword
            );

            // On remplace le mot de passe en clair par le mot de passe hashé
            $user->setPassword($hashedPassword);

            // On supprime le token de réinitialisation
            $user->setResetToken(null);
            $user->setResetTokenExpiration(null);

            // On sauvegarde l'utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Ajout du message flash
            $this->addFlash('success', 'Votre mot de passe a bien été réinitialisé ! Vous pouvez maintenant vous connecter.');

            // On redirige l'utilisateur vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
