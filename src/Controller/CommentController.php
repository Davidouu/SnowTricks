<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentsFormType;

class CommentController extends AbstractController
{
    #[Route(path: '/comment/new', name: 'app_comment_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentsFormType::class, $comment, ['action' => 'app_comment_new'])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentForm = $form->getData();


            $comment->setMessage($commentForm->getMessage());
            $comment->setPublishDate(new \DateTime());
            $comment->setTrick($commentForm->getTrick());
            $comment->setUser($commentForm->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Le commentaire a bien été ajouté.');

            return $this->redirectToRoute('app_trick_show', ['slug' => $commentForm->getTrick()->getSlug()]);
        }
    }

    #[Route(path: '/comment/{id}/delete', name: 'app_comment_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($comment);
        $entityManager->flush();

        $this->addFlash('success', 'Le commentaire a bien été supprimé.');

        return $this->redirectToRoute('app_trick_show', ['slug' => $comment->getTrick()->getSlug()]);
    }

    #[Route(path: '/comment/{id}/edit', name: 'app_comment_edit', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function edit(Comment $comment, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(
            CommentsFormType::class,
            $comment, ['action' => 'app_comment_edit', 'id' => ['id' => $comment->getId()]]
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentForm = $form->getData();

            $comment->setMessage($commentForm->getMessage());
            $comment->setPublishDate(new \DateTime());

            $entityManager->flush();

            $this->addFlash('success', 'Le commentaire a bien été modifié.');

            return $this->redirectToRoute('app_trick_show', ['slug' => $commentForm->getTrick()->getSlug()]);
        }
    }
}