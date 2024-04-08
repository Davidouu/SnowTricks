<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Entity\User;
use Symfony\Component\Mime\Address;

class SendMail
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address(
                'register@snowtricks.fr',
                'SnowTricks'
            ))
            ->to($user->getEmail())
            ->subject('Bienvenue sur SnowTricks')
            ->htmlTemplate('security/mail/confirm_email.html.twig');

        $context = $email->getContext();
        $context['id'] = $user->getId();
        $context['token'] = $user->getToken();

        $email->context($context);

        $this->mailer->send($email);
    }

    public function sendResetMail(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address(
                'reset.password@snowtricks.fr',
                'SnowTricks'
            ))
            ->to($user->getEmail())
            ->subject('Réinitialisation de votre mot de passe')
            ->htmlTemplate('security/mail/reset_password.html.twig');

        $context = $email->getContext();
        $context['id'] = $user->getId();
        $context['token'] = $user->getResetToken();
        $context['date'] = $user->getResettokenexpiration();

        $email->context($context);

        $this->mailer->send($email);
    }
}
