<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ValidateToken
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function handleEmailConfirmation(string $token, User $user): void
    {
        if (!$user || $user->getToken() !== $token) {
            throw new \Exception("Votre lien de validation n'est pas valide.");
            
        }

        $user->setToken(null);
        $user->setIsValidate(true);
        $this->entityManager->flush();
    }

    public function handlePasswordReset(string $token, User $user): void
    {
        if (!$user || $user->getResetToken() !== $token || $user->getResetTokenExpiration() < new \DateTime()){
            throw new \Exception("Votre lien de réinitialisation n'est pas valide.");
        }
    }
}