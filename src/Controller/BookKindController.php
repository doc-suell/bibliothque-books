<?php

namespace App\Controller;

// Les use, équivalents aux "require", représentent toutes les classes qui sont utilisées
// dans le fichier.
use App\Entity\BookKind;
use App\Form\BookKindType;
use App\Repository\BookKindRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookKindController extends AbstractController
{
    // La route tire partie du paramètre name.
    // Au sein de notre code, il faudra utiliser ce nom lorsqu'on voudra y faire référence
    #[Route('/bookKinds', name: 'bookKind_listing')]
    public function bookKinds(BookKindRepository $bookKindRepository)
    {
        $bookKinds = $bookKindRepository->findAll();
        return $this->render('bookKind/bookKinds.html.twig', [
            'bookKinds' => $bookKinds
        ]);
    }

    #[Route('/bookKinds/new', name: 'bookKind_new')]
    public function bookKindNew(Request $request, EntityManagerInterface $entityManager)
    {
        // Crée une nouvelle instance d'un genre, qu'on passera au formulaire
        $newBookKind = new BookKind();
        // Crée le formulaire en utilisant BookKindType, qui est le modèle de formulaire. Il contient
        // la liste des champs à générer
        $form = $this->createForm(BookKindType::class, $newBookKind);

        // Traite la requête pour vérifier si les données du formulaire sont soumises
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // Récupère les données du formulaire
            $bookKindToSave = $form->getData();
            // Le persist permet de préparer les requêtes (SQL) à exécuter en DB
            $entityManager->persist($bookKindToSave);
            // Exécute toutes les requêtes SQL préparées précédemment
            $entityManager->flush();

            // Ajoute un message éphémère pour avertir de l'état de la demande
            $this->addFlash('success', 'Votre genre à été créé avec succès.');

            return $this->redirectToRoute('bookKind_listing');
        }

        return $this->render('bookKind/bookKindNew.html.twig', [
            'bookKindForm' => $form->createView()
            ]);
    }

    #[Route('/bookKinds/{id}/edit', name: 'bookKind_edit')]
    public function bookKindEdit($id, BookKindRepository $bookKindRepository, Request $request, EntityManagerInterface $entityManager)
    {
        // Récupère un genre en DB, celui qui a l'id précisé dans l'URL
        $bookKind = $bookKindRepository->findOneBy([
            'id' => $id
        ]);

        if (!$bookKind) {
            $this->addFlash('warning', 'Aucun genre trouvé.');
            return $this->redirectToRoute('bookKind_listing');
        }

        $form = $this->createForm(BookKindType::class, $bookKind);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $bookKindToSave = $form->getData();
            $entityManager->persist($bookKindToSave);
            $entityManager->flush();

            $this->addFlash('success', 'Votre genre a été modifié avec succès.');

            return $this->redirectToRoute('bookKind_listing');
        }

        return $this->render('bookKind/bookKindEdit.html.twig', [
            'bookKindForm' => $form->createView()
        ]);
    }

    #[Route('/bookKinds/{id}/delete', name: 'bookKind_delete')]
    public function bookKindDelete($id, BookKindRepository $bookKindRepository, EntityManagerInterface $entityManager)
    {
        $bookKind = $bookKindRepository->findOneBy([
            'id' => $id
        ]);

        // Prépare la requête pour supprimer le genre de la DB
        $entityManager->remove($bookKind);
        $entityManager->flush();

        $this->addFlash('success', 'Votre genre a été supprimé.');

        return $this->redirectToRoute('bookKind_listing');
    }
}