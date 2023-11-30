<?php

namespace App\Entity;

use App\Entity\Author;
use App\Entity\Category;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: BookRepository::class)]
#[UniqueEntity(fields: ['title'], message: 'le titre est déjà utilisé par un autre livre')]

class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Le titre doit faire au moins {{ limit }} caractères',
        maxMessage: 'Le titre doit faire plus de {{ limit }} caractères'
    )]
    #[Assert\NotBlank(
        message: 'Le titre ne peut pas être vide'
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    #[Assert\Length(
        min: 10,
        minMessage: 'Le resumé doit faire plus de {{ limit }} caractères',
    )]
    private ?string $Resume = null;

    #[ORM\Column]
    #[Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'book')]
    private ?Collection $category = null;

    #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: 'book')]
    private Collection $author;

    #[ORM\Column]
    #[Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updateAt = null;

    public function __construct()
    {
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->Title = $title;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->Resume;
    }

    public function setResume(string $Resume): static
    {
        $this->Resume = $Resume;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
            $category->addBook($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->category->removeElement($category)) {
            $category->removeBook($this);
        }

        return $this;
    }

    public function getAuthor(): Collection
    {
        return $this->author;
    }

    public function setAuthor(Author $author): static
    {
        if (!$this->author->contains($author)) {
            $this->author->add($author);
            $author->addBook($this);
        }
        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }
}
