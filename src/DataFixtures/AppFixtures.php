<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create 4 catégories
        $categories = [
            'Grabs',
            'Rotations',
            'Flips',
            'Rotations désaxées',
        ];

        foreach ($categories as $category) {
            $categorie = new Category();
            $categorie->setName($category);
            $categorie->setPublishDate(new \DateTime());
            $categorie->setEditDate(new \DateTime());
            $manager->persist($categorie);
        }

        $manager->flush();
        $categories = $manager->getRepository(Category::class)->findAll();

        // Create 20 tricks
        for ($i = 0; $i < 20; $i++) {
            $trick = new Trick();
            $trick->setName('Trick ' . $i);
            $trick->setSlug('trick-' . $i);
            $trick->setDescription('Description of trick ' . $i);
            $trick->setPublishDate(new \DateTime());
            $trick->setEditDate(new \DateTime('2021-01-01 + ' . $i . ' days'));
            $trick->addCategory($categories[array_rand($categories)]);
            $manager->persist($trick);
        }

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

        // Add between 0 & 2 images for each trick
        $images = [
            "data-fixture-image-1.jpg",
            "data-fixture-image-2.jpg",
        ];

        foreach ($tricks as $trick) {
            $nbImages = rand(0, 2);

            for ($i = 0; $i < $nbImages; $i++) {
                $image = new Image();
                $image->setName($images[array_rand($images)]);
                $image->setTricks($trick);
                $manager->persist($image);
            }
        }

        // Add between 0 & 2 videos for each trick
        $videos = [
            "https://www.youtube.com/embed/IAFgCCkD0Tw?si=HqG3F3oNSe716bXL",
            "https://www.youtube.com/embed/o6VgjIwKtlY?si=CoTg4sStr49vDpJb",
        ];

        foreach ($tricks as $trick) {
            $nbVideos = rand(0, 2);

            for ($i = 0; $i < $nbVideos; $i++) {
                $video = new Video();
                $video->setUrl($videos[array_rand($videos)]);
                $video->setTricks($trick);
                $manager->persist($video);
            }
        }

        $manager->flush();
    }
}
