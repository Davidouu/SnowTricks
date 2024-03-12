<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileUploader;
use App\Entity\Trick;
use App\Entity\Image;
use App\Form\TricksFormType;
use App\Service\VideosService;

class TrickController extends AbstractController
{
    #[Route(path: '/nouveau-trick', name: 'app_tricks_new')]
    public function new(Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager, SluggerInterface $slugger, VideosService $videosService): Response
    {
        $tricks = new Trick();

        $form = $this->createForm(TricksFormType::class, $tricks)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les données du formulaire
            $tricksForm = $form->getData();
            
            // On récupère les images
            $images = $tricksForm->getImages();

            // On boucle sur les images
            foreach ($images as $image) {
                $imageObject = new Image();

                // On récupère le fichier
                $file = $image['file'];

                // On upload le fichier
                $fileName = $fileUploader->upload($file);

                $imageObject->setName($fileName);

                $tricks->addImage($imageObject);

                $tricks->getImages()->removeElement($image);
            }

            // On récupère les vidéos
            $videos = $tricksForm->getVideos();
            
            // On récupère l'url de la vidéo
            foreach ($videos as $video) {
                $Embedurl = $video->getUrl();

                if (preg_match('/<iframe/', $Embedurl)) {
                    $url = $videosService->getEmbedLink($Embedurl);
                }

                $video->setUrl($url);
            }

            $tricks->setPublishDate(new \DateTime('now'));
            $tricks->setSlug($slugger->slug($tricks->getName(), '-'));

            $entityManager->persist($tricks);
            $entityManager->flush();

            $this->addFlash('success', 'Le trick a bien été ajouté !');

            // return $this->redirectToRoute('app_home');
            return $this->redirectToRoute('app_register');
        }

        return $this->render('tricks/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
