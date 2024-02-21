<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Trick;
use App\Form\TricksFormType;

class TrickController extends AbstractController
{
    #[Route(path: '/nouveau-trick', name: 'app_tricks_new')]
    public function new(): Response
    {
        $tricks = new Trick();

        $form = $this->createForm(TricksFormType::class, $tricks);

        if ($form->isSubmitted() && $form->isValid()) {
            // ... save the tricks
        }

        return $this->render('tricks/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}