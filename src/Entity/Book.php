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
     * Book constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->histories = new ArrayCollection();
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
     * @param History $histories
     *
     * @return Book
     */
    public function setHistories(History $histories): self
    {
        $this->histories[] = $histories;

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