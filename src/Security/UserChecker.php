<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if (!$user->isValidate()) {
            throw new CustomUserMessageAccountStatusException(
                "Votre compte n'est pas confirmé, veuillez cliquez sur le lien dans le mail que vous avez reçu pour le confirmer."
            );
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}