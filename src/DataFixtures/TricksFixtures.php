<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TricksFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = $manager->getRepository(Category::class)->findAll();
        
        $tricks = [
            // The Butter tricks
            [
                'name' => 'Ollie',
                'description' => "Le Ollie est l'un des tricks fondamentaux du snowboard, où le rider saute en l'air en 
                                utilisant uniquement la force des jambes et du bas du corps, sans l'aide des mains 
                                ni de la pente. C'est un mouvement essentiel qui sert de base à de nombreux autres 
                                tricks plus avancés sur la neige.",
                'categorys' => [
                    $categories[0],
                    $categories[4],
                ],
                'images' => [
                    'df-ollie-1.jpg',
                    'df-ollie-2.jpg',
                ],
                'videos' => [
                    'https://www.youtube.com/embed/coBcTxy8cOY?si=5s8DQ4r99sSj_sND',
                ],
            ],
            [
                'name' => 'Nollie',
                'description' => "Le Nollie est un trick de snowboard similaire à l'Ollie, mais avec une variation 
                                importante : au lieu de sauter en utilisant la queue (partie arrière) de la planche, 
                                le Nollie implique de sauter en utilisant le nez (partie avant) de la planche",
                'categorys' => [
                    $categories[0],
                    $categories[4],
                ],
                'images' => [
                    'df-nollie-1.jpg',
                ],
                'videos' => [
                    'https://www.youtube.com/embed/xRLg8yIjEjg?si=Xf8pGcwP4KKM55ZI',
                ],
            ],
            [
                'name' => 'Tail Press',
                'description' => "Le Tail Press est un trick de snowboard où le rider maintient la pression sur le tail 
                                (partie arrière) de sa planche tout en glissant sur la neige.",
                'categorys' => [
                    $categories[0],
                    $categories[4],
                ],
                'images' => [
                    'df-tail-press-1.jpg',
                ],
                'videos' => [
                    'https://www.youtube.com/embed/Kv0Ah4Xd8d0?si=B6VB-xxF5t9CsGJD',
                ],
            ],
            [
                'name' => 'Nose-Rolle 180',
                'description' => "Le Nose-Rolle 180 est un trick avancé de snowboard qui combine une rotation de 180 
                                degrés avec un déséquilibre contrôlé sur le nose (partie avant) de la planche.",
                'categorys' => [
                    $categories[0],
                    $categories[4],
                ],
                'images' => [
                    'df-nose-rolle-180-1.jpg',
                    'df-nose-rolle-180-2.jpg',
                ],
                'videos' => [
                    'https://www.youtube.com/embed/N3ddt_yoxts?si=CgFwtOnLDk5Go-h5',
                    'https://www.youtube.com/embed/JtQERnOYeYM?si=f1OMzU-zxVjfXrTN',
                ],
            ],
            [
                'name' => 'Tripod',
                'description' => "Le Tripod est un trick de snowboard où le rider équilibre son poids sur le nose 
                                (partie avant) de sa planche tout en levant la queue (partie arrière) et en se tenant sur une seule jambe.",
                'categorys' => [
                    $categories[0],
                    $categories[4],
                ],
                'videos' => [
                    'https://www.youtube.com/embed/msD1jQL63dA?si=3DaZGPkd7vHNz8cv',
                    'https://www.youtube.com/embed/Ny3Vq2h9abQ?si=YGI0HFz2sqa3wUzj',
                ],
            ],
            [
                'name' => 'Nose Press',
                'description' => "Le Nose Press est un trick de snowboard où le rider maintient la pression sur le 
                                nose (partie avant) de sa planche tout en glissant sur la neige.",
                'categorys' => [
                    $categories[0],
                    $categories[4],
                ],
                'videos' => [
                    'https://www.youtube.com/embed/gyz_C5xmflI?si=AzHPTD6tqVZe8r9V',
                ],
            ],
            [
                'name' => 'Tail-Drag',
                'description' => "Le Tail-Drag est un trick de snowboard où le rider utilise la partie arrière (tail) 
                                de sa planche pour frotter ou \"draguer\" la neige tout en glissant, ajoutant un 
                                effet visuel stylé à sa descente.",
                'categorys' => [
                    $categories[0],
                    $categories[1],
                    $categories[4],
                ],
                'images' => [
                    'df-tail-drag-1.jpg',
                ],
                'videos' => [
                    'https://www.youtube.com/embed/hZCDsreASr4?si=5Q0LXm0ogy4Dnp0_',
                ],
            ],
            // The grabs tricks
            [
                'name' => 'Indy',
                'description' => "L'Indy est un trick de snowboard où le rider attrape la carre de sa planche du 
                                côté des orteils avec sa main arrière tout en étant en l'air, ajoutant style et 
                                amplitude à son saut.",
                'categorys' => [
                    $categories[1],
                ],
                'images' => [
                    'df-indy-1.jpg',
                ],
                'videos' => [
                    'https://www.youtube.com/embed/6yA3XqjTh_w?si=B4mLbC5dYl-9B_bY',
                    'https://www.youtube.com/embed/uJbwHBa0GyY?si=qgJ_L55lAsPPwqYY',
                ],
            ],
            [
                'name' => 'Tail Grab',
                'description' => "Le Tail Grab est un trick de snowboard où le rider attrape la partie arrière (tail) 
                                de sa planche avec sa main, généralement pendant un saut ou une rotation, ajoutant 
                                style et amplitude à son mouvement.",
                'categorys' => [
                    $categories[1],
                ],
                'images' => [
                    'df-tail-grab-1.jpg',
                ],
                'videos' => [
                    'https://www.youtube.com/embed/_Qq-YoXwNQY?si=t1XbCtk859BugG2V',
                ],
            ],
            [
                'name' => 'Melon',
                'description' => "Le Melon est un trick de snowboard où le rider attrape la carre de sa planche du 
                                côté des talons avec sa main avant pendant un saut, ajoutant style et amplitude à 
                                son mouvement aérien.",
                'categorys' => [
                    $categories[1],
                ],
                'videos' => [
                    'https://www.youtube.com/embed/OMxJRz06Ujc?si=rswfWwKCli8JDZhz',
                ],
            ],
            [
                'name' => 'Nose Grab',
                'description' => "Le Nose Grab est un trick de snowboard où le rider attrape la partie avant (nose) 
                                de sa planche avec sa main pendant un saut ou une rotation, ajoutant style et 
                                amplitude à son mouvement aérien.",
                'categorys' => [
                    $categories[1],
                ],
                'images' => [
                    'df-nose-grab-1.jpg',
                ],
                'videos' => [
                    'https://www.youtube.com/embed/M-W7Pmo-YMY?si=TZLCEL132IlY0QKL',
                ],
            ],
            // The flips tricks
            [
                'name' => 'Backflip',
                'description' => "Le Backflip est un trick de snowboard où le rider effectue une rotation 
                                complète en arrière, faisant un saut périlleux vers l'arrière tout en restant en 
                                l'air, ajoutant un élément spectaculaire à sa descente.",
                'categorys' => [
                    $categories[2],
                ],
                'images' => [
                    'df-backflip-1.jpg',
                ],
                'videos' => [
                    'https://www.youtube.com/embed/SlhGVnFPTDE?si=ClWwI_ugisOrF-wr',
                ],
            ],
            [
                'name' => 'Frontflip',
                'description' => "Le Frontflip est un trick de snowboard où le rider effectue une rotation complète 
                                en avant, faisant un saut périlleux vers l'avant tout en restant en l'air, ajoutant 
                                un élément spectaculaire à sa descente.",
                'categorys' => [
                    $categories[2],
                ],
                'images' => [
                    'df-frontflip-1.jpg',
                ],
                'videos' => [
                    'https://www.youtube.com/embed/xhvqu2XBvI0?si=m0P1YU9ixPp-AgzA',
                ],
            ],
            [
                'name' => 'Wildcat',
                'description' => "Le Wildcat est un trick de snowboard où le rider effectue une rotation 
                                arrière horizontale de 360 degrés, en partant d'une position régulière, 
                                ajoutant une dose d'audace et de style à sa descente.",
                'categorys' => [
                    $categories[2],
                ],
                'images' => [
                    'df-wildcat-1.jpg',
                ],
                'videos' => [
                    'https://www.youtube.com/embed/7KUpodSrZqI?si=6UjuXVE1UBK9oxpE',
                ],
            ],
            // The spins tricks
            [
                'name' => 'Corked Spin',
                'description' => "Le Corked Spin est un trick de snowboard où le rider effectue une rotation 
                                horizontale tout en inclinant son axe de rotation, créant une torsion ou une 
                                \"cork\", ajoutant une dimension de style et de technique à son mouvement aérien.",
                'categorys' => [
                    $categories[3],
                    $categories[2],
                ],
                'images' => [
                    'df-corked-spin-1.jpg',
                    'df-corked-spin-2.jpg',
                ],
                'videos' => [
                    'https://www.youtube.com/embed/qqNV0tI3Z4k?si=KfHmdsus0Chtfecb',
                ],
            ],
        ];
        
        foreach ($tricks as $trickData) {
            $trick = new Trick();
            $trick->setName($trickData['name']);
            $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $trickData['name']);
            $slug = strtolower(trim($slug, '-'));
            $trick->setSlug($slug);
            $trick->setDescription($trickData['description']);
            $trick->setPublishDate(new \DateTime());
            $trick->setEditDate(new \DateTime());

            foreach ($trickData['categorys'] as $category) {
                $trick->addCategory($category);
            }
            
            if (isset($trickData['images'])) {
                foreach ($trickData['images'] as $imageData) {
                    $image = new Image();
                    $image->setName($imageData);
                    $image->setTricks($trick);
                    $manager->persist($image);
                }
            }
            
            foreach ($trickData['videos'] as $videoData) {
                $video = new Video();
                $video->setUrl($videoData);
                $video->setTricks($trick);
                $manager->persist($video);
            }
            
            $manager->persist($trick);
        }

       $manager->flush();
    }
}
