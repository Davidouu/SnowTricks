<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\TrickRepository;

class HomeController extends AbstractController
{
    #[Route(path: '/', name: 'app_home')]
    public function index(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findBy([], ['editDate' => 'DESC'], 3);

        return $this->render('index.html.twig', [
            'tricks' => $tricks
        ]);
    }
}