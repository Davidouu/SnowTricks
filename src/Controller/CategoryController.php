<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Category;
use App\Form\CategoryFormType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class CategoryController extends AbstractController
{
    #[Route('/new-category', name: 'app_new_category')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryFormType::class, $category)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On génère la date de création de la catégorie
            $category->setPublishDate(new \DateTime());

            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'La catégorie a bien été ajoutée.');

            return $this->redirectToRoute('app_new_category');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit-category/{id}', name: 'app_edit_category', requirements: ['id' => '\d+'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, Category $category): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On génère la date de modification de la catégorie
            $category->setEditDate(new \DateTime());

            $entityManager->flush();

            $this->addFlash('success', 'La catégorie a bien été modifiée.');

            return $this->redirectToRoute('app_edit_category', ['id' => $category->getId()]);
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete-category/{id}', name: 'app_delete_category', requirements: ['id' => '\d+'])]
    public function delete(EntityManagerInterface $entityManager, Category $category): Response
    {
        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash('success', 'La catégorie a bien été supprimée.');

        return $this->redirectToRoute('app_new_category');
    }
}
