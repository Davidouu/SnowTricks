<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Create between 3 & 14 comments for each trick
        $tricks = $manager->getRepository(Trick::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        foreach ($tricks as $trick) {
            $nbComments = rand(3, 14);

            for ($i = 0; $i < $nbComments; $i++) {
                $comment = new Comment();
                $comment->setMessage('Comment ' . $i . ' for trick ' . $trick->getName());
                $comment->setPublishDate(new \DateTime());
                $comment->setTrick($trick);
                $comment->setUser($users[array_rand($users)]);
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TricksFixtures::class,
        ];
    }
}
