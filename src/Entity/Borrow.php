<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 24.11.2018 18:34
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Borrow
 *
 * @ORM\Table(name="borrow")
 * @ORM\Entity(repositoryClass="App\Repository\BorrowRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @package App\Entity
 */
class Borrow extends DataBaseEntity
{

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="borrows")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var Book
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Book", inversedBy="borrows")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id")
     */
    private $book;

    /**
     * Borrow constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return Borrow
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Book
     */
    public function getBook(): ?Book
    {
        return $this->book;
    }

    /**
     * @param Book $book
     *
     * @return Borrow
     */
    public function setBook(Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    /**
     * @return int
     */
    public static function getLengthUnique(): int
    {
        return \App\Enums\Borrow::LENGTH_UNIQUE;
    }

    /**
     * @return string
     */
    public static function getEntityName(): string
    {
        return \App\Enums\Borrow::MODEL;
    }
}