<?php

namespace App\Controller;

// Les use, équivalents aux "require", représentent toutes les classes qui sont utilisées
// dans le fichier.
use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

// Permet d'attribuer un préfixe à l'url et aux noms de toutes les routes définies ans le controller
#[Route('/authors', name: 'author_')]
class AuthorController extends AbstractController
{
    // La route tire partie du paramètre name.
    // Au sein de notre code, il faudra utiliser ce nom lorsqu'on voudra y faire référence
    #[Route('/', name: 'listing')]
    public function authors(AuthorRepository $authorRepository)
    {
        $authors = $authorRepository->findAll();
        return $this->render('author/authors.html.twig', [
            'authors' => $authors
        ]);
    }

    #[Route('/new', name: 'new')]
    public function authorNew(Request $request, EntityManagerInterface $entityManager)
    {
        if(!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('author_listing');
        }
        // Crée une nouvelle instance d'un auteur, qu'on passera au formulaire
        $newAuthor = new Author();
        // Crée le formulaire en utilisant AuthorType, qui est le modèle de formulaire. Il contient
        // la liste des champs à générer
        $form = $this->createForm(AuthorType::class, $newAuthor);

        // Traite la requête pour vérifier si les données du formulaire sont soumises
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // Récupère les données du formulaire
            $authorToSave = $form->getData();
            // Le persist permet de préparer les requêtes (SQL) à exécuter en DB
            $entityManager->persist($authorToSave);
            // Exécute toutes les requêtes SQL préparées précédemment
            $entityManager->flush();

            // Ajoute un message éphémère pour avertir de l'état de la demande
            $this->addFlash('success', 'Votre auteur à été créé avec succès.');

            return $this->redirectToRoute('author_listing');
        }

        return $this->render('author/authorNew.html.twig', [
            'authorForm' => $form->createView()
            ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function authorEdit($id, AuthorRepository $authorRepository, Request $request, EntityManagerInterface $entityManager)
    {
        if(!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('author_listing');
        }
        // Récupère un auteur en DB, celui qui a l'id précisé dans l'URL
        $author = $authorRepository->findOneBy([
            'id' => $id
        ]);

        if (!$author) {
            $this->addFlash('warning', 'Aucun auteur trouvé.');
            return $this->redirectToRoute('author_listing');
        }

        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $authorToSave = $form->getData();
            $entityManager->persist($authorToSave);
            $entityManager->flush();

            $this->addFlash('success', 'Votre auteur a été modifié avec succès.');

            return $this->redirectToRoute('author_listing');
        }

        return $this->render('author/authorEdit.html.twig', [
            'authorForm' => $form->createView()
        ]);
    }

    #[Route('/{id}/delete', name: 'delete')]
    public function authorDelete($id, AuthorRepository $authorRepository, EntityManagerInterface $entityManager)
    {
        if(!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('author_listing');
        }
        $author = $authorRepository->findOneBy([
            'id' => $id
        ]);

        // Prépare la requête pour supprimer le auteur de la DB
        $entityManager->remove($author);
        $entityManager->flush();

        $this->addFlash('success', 'Votre auteur a été supprimé.');

        return $this->redirectToRoute('author_listing');
    }



    // créer une route et un controller avec comme url /authors/{id}
    #[Route('/{id}', name: 'detail')]
    public function authorDetail ($id, AuthorRepository $authorRepository){
        $author = $authorRepository->findOneBy([
            'id' => $id
        ]);

        if (!$author) {
            $this->addFlash('success', 'Votre auteur a été supprimé.');
            return $this->redirectToRoute('author_listing');
        }

        return $this->render('author/authorDetail.html.twig', [
            'author' => $author
        ]);
    }
}