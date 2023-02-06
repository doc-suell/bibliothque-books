<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\ORM\Mapping as ORM;

// Indique à doctrine qu'il faut surveiller cette entité
#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    // décrire la colonne en DB
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text', length: 255)]
    private $firstName;

    #[ORM\Column(type: 'text', length: 255)]
    private $lastName;

    #[ORM\Column(type: 'date')]
    private $dateOfBirth;

    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: "author")]
    private $books;

    public function __toString() {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param mixed $dateOfBirth
     */
    public function setDateOfBirth($dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return mixed
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * @param mixed $books
     */
    public function setBooks($books): void
    {
        $this->books = $books;
    }
}