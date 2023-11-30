<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AuthorsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: AuthorsRepository::class)]
class Authors
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Le prénom doit faire plus de {{ limit }} caractères.',
        maxMessage: 'Le prénom doit faire plus de {{ limit }} caractères.'
    )]
    #[Assert\NotBlank()]
    private ?string $First_name = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Le nom doit faire plus de {{ limit }} caractères.',
        maxMessage: 'Le nom doit faire plus de {{ limit }} caractères.'
    )]
    #[Assert\NotBlank()]
    private ?string $Last_name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Books::class)]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->First_name;
    }

    public function setFirstName(string $First_name): static
    {
        $this->First_name = $First_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->Last_name;
    }

    public function setLastName(string $Last_name): static
    {
        $this->Last_name = $Last_name;

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
     * @return Collection<int, Books>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Books $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setAuthor($this);
        }

        return $this;
    }

    public function removeBook(Books $book): static
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getAuthor() === $this) {
                $book->setAuthor(null);
            }
        }

        return $this;
    }
}
