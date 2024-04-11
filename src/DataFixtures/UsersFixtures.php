<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create 3 user accounts with password "password"
        $users = [
            'user1' => 'password',
            'user2' => 'password',
            'user3' => 'password',
        ];

        foreach ($users as $username => $password) {
            $user = new User();
            $user->setUsername($username);
            $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
            $user->setEmail($username . '@example.com');
            $user->setIsValidate(true);
            $user->setFirstname('Firstname ' . $username);
            $user->setLastname('Lastname ' . $username);
            $user->setCreationdate(new \DateTime());
            $manager->persist($user);
        }

        $manager->flush();
    }
}
