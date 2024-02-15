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
use App\Service\SendMail;
use App\Service\ValidateToken;

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
            return $this->redirectToRoute('app_register');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/confirmation/{id}/{token}', name: 'app_confirm_account')]
    public function confirmAccount(User $user, string $token, ValidateToken $verifyer): Response
    {
        try {
            $verifyer->handleEmailConfirmation($token, $user);
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Votre compte a bien été confirmé ! Vous pouvez maintenant vous connecter.');

        return $this->redirectToRoute('app_login');
    }
}
