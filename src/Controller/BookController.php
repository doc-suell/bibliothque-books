<?php

namespace App\Controller;

// Les use, équivalents aux "require", représentent toutes les classes qui sont utilisées
// dans le fichier.
use App\Entity\Book;
use App\Form\BookSearchFormType;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    // La route tire partie du paramètre name.
    // Au sein de notre code, il faudra utiliser ce nom lorsqu'on voudra y faire référence
    #[Route('/', name: 'book_listing')]
    public function books(BookRepository $bookRepository, Request $request)
    {
        $form = $this->createForm(BookSearchFormType::class);

        $form->handleRequest($request);

        $searchFormValues = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $searchFormValues = $form->getData();
        }

        // utilise une méthode custom crée par nos soins du repository
        $books = $bookRepository->findBooksWithAuthor($searchFormValues);
        return $this->render('book/books.html.twig', [
            'books' => $books,
            'form' => $form->createView()
        ]);
    }

    #[Route('/books/new', name: 'book_new')]
    public function bookNew(Request $request, EntityManagerInterface $entityManager)
    {
        // Crée une nouvelle instance d'un livre, qu'on passera au formulaire
        $newBook = new Book();
        // Crée le formulaire en utilisant BookType, qui est le modèle de formulaire. Il contient
        // la liste des champs à générer
        $form = $this->createForm(BookType::class, $newBook);

        // Traite la requête pour vérifier si les données du formulaire sont soumises
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // Récupère les données du formulaire
            $bookToSave = $form->getData();
            // Le persist permet de préparer les requêtes (SQL) à exécuter en DB
            $entityManager->persist($bookToSave);
            // Exécute toutes les requêtes SQL préparées précédemment
            $entityManager->flush();

            // Ajoute un message éphémère pour avertir de l'état de la demande
            $this->addFlash('success', 'Votre livre à été créé avec succès.');

            return $this->redirectToRoute('book_listing');
        }

        return $this->render('book/bookNew.html.twig', [
            'bookForm' => $form->createView()
            ]);
    }

    #[Route('/books/{id}/edit', name: 'book_edit')]
    public function bookEdit($id, BookRepository $bookRepository, Request $request, EntityManagerInterface $entityManager)
    {
        // Vérifie si l'utilisateur a le ROLE_ADMIN
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('book_listing');
        }

        // Récupère un livre en DB, celui qui a l'id précisé dans l'URL
        $book = $bookRepository->findOneBy([
            'id' => $id
        ]);

        if (!$book) {
            $this->addFlash('warning', 'Aucun livre trouvé.');
            return $this->redirectToRoute('book_listing');
        }

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $bookToSave = $form->getData();
            $entityManager->persist($bookToSave);
            $entityManager->flush();

            $this->addFlash('success', 'Votre livre à été modifié avec succès.');

            return $this->redirectToRoute('book_listing');
        }

        return $this->render('book/bookEdit.html.twig', [
            'bookForm' => $form->createView()
        ]);
    }

    #[Route('/books/{id}/delete', name: 'book_delete')]
    public function bookDelete($id, BookRepository $bookRepository, EntityManagerInterface $entityManager)
    {
        $book = $bookRepository->findOneBy([
            'id' => $id
        ]);

        // Prépare la requête pour supprimer le livre de la DB
        $entityManager->remove($book);
        $entityManager->flush();

        $this->addFlash('success', 'Votre livre a été supprimé.');

        return $this->redirectToRoute('book_listing');
    }



    // créer une route et un controller avec comme url /books/{id}
    #[Route('/books/{id}', name: 'book_detail')]
    public function bookDetail ($id, BookRepository $bookRepository){
        // Utilise une méthode custom du repositoru pour récupérer les livres avec toutes les jointures souhaitées
        $book = $bookRepository->findOneBookByIdWithAuthorAndBookKind($id);

        if (!$book) {
            $this->addFlash('warning', 'Aucun livre trouvé.');
            return $this->redirectToRoute('book_listing');
        }

        return $this->render('book/bookDetail.html.twig', [
            'book' => $book
        ]);
    }
}