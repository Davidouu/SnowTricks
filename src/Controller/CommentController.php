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

        $form = $this->createForm(CommentsFormType::class, $comment)
            ->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $commentForm = $form->getData();

            $comment->setMessage($commentForm->getMessage());
            $comment->setPublishDate(new \DateTime());
            $comment->setTrick($commentForm->getTrick());
            $comment->setUser($commentForm->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_trick_show', ['slug' => $commentForm->getTrick()->getSlug()]);
        }
    }
}