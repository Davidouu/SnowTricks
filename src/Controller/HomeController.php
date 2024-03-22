<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route(path: '/', name: 'app_home')]
    public function index(TrickRepository $trickRepository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 6;
        $tricks = $trickRepository->paginateTricks($page, $limit);
        $maxPages = ceil($tricks->count() / $limit);

        return $this->render('index.html.twig', [
            'tricks' => $tricks,
            'maxPages' => $maxPages,
            'page' => $page
        ]);
    }
}