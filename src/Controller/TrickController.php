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
use App\Entity\Comment;
use App\Form\TricksFormType;
use App\Form\CommentsFormType;
use App\Repository\CommentRepository;
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
                $embedUrl = $video->getUrl();

                if (preg_match('/<iframe/', $embedUrl)) {
                    $url = $videosService->getEmbedLink($embedUrl);
                    $video->setUrl($url);
                }

                $video->setTricks($tricks);
            }

            $tricks->setPublishDate(new \DateTime('now'));
            $tricks->setEditDate(new \DateTime('now'));
            $tricks->setSlug($slugger->slug($tricks->getName(), '-'));

            $entityManager->persist($tricks);
            $entityManager->flush();

            $this->addFlash('success', 'Le trick a bien été ajouté !');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('tricks/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/trick/{slug}/modifier', name: 'app_tricks_edit', requirements: ['slug' => '[A-Za-z0-9-]+'])]
    public function edit(Trick $trick, Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager, SluggerInterface $slugger, VideosService $videosService): Response
    {
        $trickImages = $trick->getImages()->toArray();
        
        $form = $this->createForm(TricksFormType::class, $trick)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère les données du formulaire
            $tricksForm = $form->getData();

            // On récupère les images
            $formImages = $tricksForm->getImages();

            // On boucle sur les images du tricks pour la suppression
            foreach ($trickImages as $image) {

                // Supression d'une image
                if (!in_array($image, $trick->getImages()->toArray())) {
                    $trick->removeImage($image);
                    $fileUploader->remove($image->getName());
                    $entityManager->remove($image);
                    continue;
                }
            }

            // On boucle sur les images du form pour l'ajout
            foreach ($formImages as $image) {
                $imageObject = new Image();

                // Si l'image est déjà enregistrée & qu'elle n'a pas été modifiée
                if (
                    !isset($image['file']) &&
                    isset($image['id']) &&
                    $image['id'] === $entityManager->getRepository(Image::class)->find($image['id'])->getId()
                ) {
                    continue;
                }

                // On récupère le fichier
                $file = $image['file'];

                // On upload le fichier
                $fileName = $fileUploader->upload($file);

                $imageObject->setName($fileName);

                $trick->addImage($imageObject);

                $trick->getImages()->removeElement($image);
            }

            // On récupère les vidéos
            $videos = $tricksForm->getVideos();
            
            // On récupère l'url de la vidéo
            foreach ($videos as $video) {
                $embedUrl = $video->getUrl();

                // Cas de suppression
                if (empty($embedUrl)) {
                    $trick->removeVideo($video);
                    $entityManager->remove($video);
                    continue;
                }

                if (preg_match('/<iframe/', $embedUrl)) {
                    $url = $videosService->getEmbedLink($embedUrl);
                    $video->setUrl($url);
                }

                $video->setTricks($trick);
            }

            $trick->setEditDate(new \DateTime('now'));

            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'Le trick a bien été modifié !');

            return $this->redirectToRoute('app_trick_show', ['slug' => $trick->getSlug()]);
        }

        return $this->render('tricks/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/trick/{slug}/supprimer', name: 'app_tricks_delete', requirements: ['slug' => '[A-Za-z0-9-]+'])]
    public function delete(Trick $trick, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        // On supprime les images lié
        $images = $trick->getImages();
        foreach ($images as $image) {
            $fileUploader->remove($image->getName());
            $entityManager->remove($image);
        }

        // On supprime les vidéos lié
        $videos = $trick->getVideos();
        foreach ($videos as $video) {
            $entityManager->remove($video);
        }

        $entityManager->remove($trick);
        $entityManager->flush();

        $this->addFlash('success', 'Le trick a bien été supprimé !');

        return $this->redirectToRoute('app_home');
    }

    #[Route(path: '/trick/{slug}', name: 'app_trick_show', requirements: ['slug' => '[A-Za-z0-9-]+'])]
    public function show(Trick $trick, Request $request, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();

        $commentForm = $this->createForm(CommentsFormType::class, $comment, [
            'action' => 'app_comment_new',
            'trickId' => $trick->getId(),
        ])
            ->handleRequest($request);

        // Get all comments by trick id
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $comments = $commentRepository->paginateComments($page, $limit, $trick->getId());
        $maxPages = ceil($comments->count() / $limit);

        $commentsModificationForms = [];
        foreach ($comments as $comment) {
            $commentModificationForm = $this->createForm(CommentsFormType::class, $comment, [
                'action' => 'app_comment_edit',
                'id' => ['id' => $comment->getId()],
                'trickId' => $trick->getId(),
            ]);

            $commentsModificationForms[$comment->getId()] = $commentModificationForm->createView();
        }
        
        $trickImages = $trick->getImages();
        $tricksVideos = $trick->getVideos();

        $tricksPreview = [];

        foreach ($trickImages as $image) {
            array_push($tricksPreview, $image->getName());
        }

        foreach ($tricksVideos as $video) {
            array_push($tricksPreview, $video->getUrl());
        }

        if (empty($tricksPreview)) {
            array_push($tricksPreview, 'default-image.jpg');
        }

        return $this->render('tricks/show.html.twig', [
            'trick' => $trick,
            'tricksPreview' => $tricksPreview,
            'form' => $commentForm,
            'comments' => $comments,
            'commentsModificationForms' => $commentsModificationForms,
            'maxPages' => $maxPages,
            'page' => $page
        ]);
    }
}
