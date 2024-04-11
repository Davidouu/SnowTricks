<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorysFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create 4 catégories
        $categories = [
            'Butter',
            'Grabs',
            'Flips',
            'Spins',
            'Débutant',
        ];

        foreach ($categories as $category) {
            $categorie = new Category();
            $categorie->setName($category);
            $categorie->setPublishDate(new \DateTime());
            $categorie->setEditDate(new \DateTime());
            $manager->persist($categorie);
        }

        $manager->flush();
    }
}
