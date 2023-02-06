<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    // Méthode de repository custom qui prend en paramètre les données reçues depuis le formulaire de recherche.
    public function findBooksWithAuthor($searchFormValues)
    {
        $qb = $this->createQueryBuilder('book')
            // Rajoute les tables souhaitées au select
            ->select('book')
            // Rajoute les jointures souhaitées à la requete
            ->leftJoin('book.author', 'author')
            ->addSelect('author')
        ;

        // Si la clé title n'est pas empty dans les données qui viennent du formulaire de recherche, on entre dans la condition
        if (!empty($searchFormValues['title'])) {
            // passe le titre au sein d'un arguent LIKE
            $qb->andWhere('book.title LIKE :title')
                ->setParameter('title', '%' . $searchFormValues['title'] .'%');
        }

        if (!empty($searchFormValues['author'])) {
            $qb->andWhere('book.author = :author')
                ->setParameter('author', $searchFormValues['author']);
        }

        if (!empty($searchFormValues['isbn'])) {
            $qb->andWhere('book.isbn LIKE :isbn')
                ->setParameter('isbn', '%' . $searchFormValues['isbn'] . '%');
        }

        if (!empty($searchFormValues['kinds'])) {
            $qb->andWhere(':kinds MEMBER OF book.kinds')
                ->setParameter('kinds', $searchFormValues['kinds']);
        }

        // Prépare la requete
        $query = $qb->getQuery();
        // Exécute la requête
        return $query->execute();
    }

    public function findOneBookByIdWithAuthorAndBookKind($id)
    {
        $qb = $this->createQueryBuilder('book')
            ->select('book')
            ->leftJoin('book.author', 'author')
            ->addSelect('author')
            ->leftJoin('book.kinds', 'kinds')
            ->addSelect('kinds')
            ->where('book.id = :id')
            ->setParameter('id', $id)
        ;

        $query = $qb->getQuery();
        return $query->getOneOrNullResult();
    }
}
