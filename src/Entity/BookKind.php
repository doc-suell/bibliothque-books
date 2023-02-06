<?php

namespace App\Entity;

use App\Repository\BookKindRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookKindRepository::class)]
class BookKind
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    // décrire la colonne en DB
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text', length: 255)]
    private $label;

    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: 'kinds')]
    private $books;

    public function __toString()
    {
        return $this->getLabel();
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
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }


}