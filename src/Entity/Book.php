<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

// Indique à doctrine qu'il faut surveiller cette entité
#[ORM\Entity(repositoryClass: BookRepository::class)]
/**
 * @Vich\Uploadable
 */
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    // décrire la colonne en DB
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text', length: 255)]
    private $title;

    #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: "books")]
    private $author;

    #[ORM\Column(type: 'string')]
    private $isbn;

    #[ORM\ManyToMany(targetEntity: BookKind::class, inversedBy: 'books')]
    private $kinds;

    #[ORM\Column(type: 'string')]
    private $coverImageName;

    /**
     * @Vich\UploadableField(mapping="book_cover", fileNameProperty="coverImageName")
     */
    private $coverImageFile;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->kinds = new ArrayCollection();
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * @param mixed $isbn
     */
    public function setIsbn($isbn): void
    {
        $this->isbn = $isbn;
    }

    /**
     * @return mixed
     */
    public function getKinds()
    {
        return $this->kinds;
    }

    public function setKinds($kinds)
    {
        $this->kinds = $kinds;
    }

    public function addKind(BookKind $bookKind)
    {
        // Vérifie qu'un genre ne soit pas déjà attribué avant de l'ajouter
        if (!$this->kinds->contains($bookKind)) {
            $this->kinds->add($bookKind);
        }
    }

    public function removeKind(BookKind $bookKind)
    {
        // Vérifie que le livre a le genre qu'on souhaite enlever
        if ($this->kinds->contains($bookKind)) {
            $this->kinds->remove($bookKind);
        }
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|null $coverImageFile
     */
    public function setCoverImageFile(?File $coverImageFile = null): void
    {
        $this->coverImageFile = $coverImageFile;

        if (null !== $coverImageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getCoverImageFile(): ?File
    {
        return $this->coverImageFile;
    }

    public function setCoverImageName(?string $imageName): void
    {
        $this->coverImageName = $imageName;
    }

    public function getCoverImageName(): ?string
    {
        return $this->coverImageName;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface|null $updatedAt
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
