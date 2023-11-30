<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/category', name: 'category')]
class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $repo,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('Category/index.html.twig', [
            'category' => $this->repo->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($category);
            $this->em->flush();
            $this->addFlash('success', 'Catégorie créée avec succès');

            return $this->redirectToRoute('category.index');
        }

        return $this->render('Category/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Category $category, Request $request): Response|RedirectResponse
    {
        if (!$category instanceof Category) {
            $this->addFlash('error', 'Catégorie non trouvée');

            return $this->redirectToRoute('category.index');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($category);
            $this->em->flush();
            $this->addFlash('success', 'Catégorie modifiée avec succès');

            return $this->redirectToRoute('category.index');
        }

        return $this->render('Category/update.html.twig', [
            'form' => $form,
            'category' => $category,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Category $category, Request $request): RedirectResponse
    {
        if (!$category instanceof Category) {
            $this->addFlash('error', 'Catégorie non trouvée');

            return $this->redirectToRoute('category.index');
        }

        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('token'))) {
            $this->em->remove($category);
            $this->em->flush();
            $this->addFlash('success', 'Category supprimée avec succès');

            return $this->redirectToRoute('category.index');
        }

        $this->addFlash('error', 'Token invalide');

        return $this->redirectToRoute('category.index');
    }

    #[Route('/{id}/visibility', name: '.switch', methods: ['GET'])]
    public function switch(?Category $category): JsonResponse
    {
        if (!$category instanceof Category) {
            return new JsonResponse([
                'status' => 'Error',
                'message' => 'Catégorie non trouvée'
            ], Response::HTTP_NOT_FOUND);
        }

        $category->setEnable(!$category->isEnable());

        $this->em->persist($category);
        $this->em->flush();

        return new JsonResponse([
            'status' => 'Success',
            'message' => 'Catégorie mise à jour avec succès',
            'visibility' => $category->isEnable()
        ]);
    }
}
