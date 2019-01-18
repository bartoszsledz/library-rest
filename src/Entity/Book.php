<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 24.11.2018 18:32
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Book
 *
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 *
 * @package App\Entity
 */
class Book extends DataBaseEntity
{

    /**
     * @var int
     *
     * @ORM\Column(type="bigint", nullable=false, length=13, options={"unsigned"=true})
     */
    private $isbn;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="available", type="boolean")
     */
    private $available;

    /**
     * @var PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\History", mappedBy="book")
     */
    private $histories;

    /**
     * @var PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Borrow", mappedBy="book")
     */
    private $borrows;

    /**
     * @var PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="book", cascade={"remove"})
     */
    private $reviews;

    /**
     * Book constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->histories = new ArrayCollection();
        $this->borrows = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getISBN(): ?int
    {
        return $this->isbn;
    }

    /**
     * @param int $isbn
     *
     * @return Book
     */
    public function setISBN(int $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * @return PersistentCollection
     */
    public function getHistories(): ?PersistentCollection
    {
        return $this->histories;
    }

    /**
     * @param History $history
     *
     * @return Book
     */
    public function setHistories(History $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
            $history->setBook($this);
        }

        return $this;
    }

    /**
     * @return PersistentCollection
     */
    public function getReviews(): PersistentCollection
    {
        return $this->reviews;
    }

    /**
     * @param Review $reviews
     *
     * @return Book
     */
    public function setReviews(Review $reviews): self
    {
        if (!$this->reviews->contains($reviews)) {
            $this->reviews[] = $reviews;
            $reviews->setBook($this);
        }

        return $this;
    }

    /**
     * @return PersistentCollection
     */
    public function getBorrows(): ?PersistentCollection
    {
        return $this->borrows;
    }

    /**
     * @param Borrow $borrow
     *
     * @return Book
     */
    public function setBorrows(Borrow $borrow): self
    {
        if (!$this->borrows->contains($borrow)) {
            $this->borrows[] = $borrow;
            $borrow->setBook($this);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Book
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param string $author
     *
     * @return Book
     */
    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Book
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAvailable(): ?bool
    {
        return $this->available;
    }

    /**
     * @param mixed $available
     *
     * @return Book
     */
    public function setAvailable($available): self
    {
        $this->available = $available;

        return $this;
    }

    /**
     * @return int
     */
    public static function getLengthUnique(): int
    {
        return \App\Enums\Book::LENGTH_UNIQUE;
    }

    /**
     * @return string
     */
    public static function getEntityName(): string
    {
        return \App\Enums\Book::MODEL;
    }
}