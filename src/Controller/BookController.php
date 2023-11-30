<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/books', name: 'books')]
class BookController extends AbstractController
{
    public function __construct(
        private BookRepository $repo,
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        // $books = $this->repo->findAll();

        return $this->render('Book/index.html.twig', [
            'books' => $this->repo->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $book->setAuthor($this->getAuthor());

            $this->em->persist($book);
            $this->em->flush();

            $this->addFlash('success', 'Book créé avec succès');

            return $this->redirectToRoute('admin.books.index');
        }

        return $this->render('Book/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Book $book, Request $request): Response|RedirectResponse
    {
        if (!$book instanceof Book) {
            $this->addFlash('error', 'Livre non trouvé');

            return $this->redirectToRoute('books.index');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($book);
            $this->em->flush();
            $this->addFlash('success', 'Book mis à jour avec succès');

            return $this->redirectToRoute('admin.books.index');
        }

        return $this->render('Book/update.html.twig', [
            'form' => $form,
            'book' => $book,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Book $book, Request $request): RedirectResponse
    {
        if (!$book instanceof Book) {
            $this->addFlash('error', 'Book non trouvé');

            return $this->redirectToRoute('books.index');
        }

        if ($this->isCsrfTokenValid('delete' . $book->getId(), $request->request->get('token'))) {
            $this->em->remove($book);
            $this->em->flush();
            $this->addFlash('success', 'Book supprimé avec succès');

            return $this->redirectToRoute('books.index');
        }

        $this->addFlash('error', 'Token CSRF invalide');

        return $this->redirectToRoute('books.index');
    }
}
