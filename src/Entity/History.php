<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 24.11.2018 18:34
 */

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class History
 *
 * @ORM\Table(name="history")
 * @ORM\Entity(repositoryClass="App\Repository\HistoryRepository")
 *
 * @package App\Entity
 */
class History extends DataBaseEntity
{

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="history")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Book
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Book", inversedBy="history")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $dateBorrow;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $dateReturn;


    /**
     * Address constructor.
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
     * @return History
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
     * @return History
     */
    public function setBook(Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateBorrow(): ?DateTime
    {
        return $this->dateBorrow;
    }

    /**
     * @param DateTime $dateBorrow
     *
     * @return History
     */
    public function setDateBorrow(DateTime $dateBorrow): self
    {
        $this->dateBorrow = $dateBorrow;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateReturn(): ?DateTime
    {
        return $this->dateReturn;
    }

    /**
     * @param DateTime $dateReturn
     *
     * @return History
     */
    public function setDateReturn(DateTime $dateReturn): self
    {
        $this->dateReturn = $dateReturn;

        return $this;
    }

    /**
     * @return int
     */
    public static function getLengthUnique(): int
    {
        // TODO: Implement getLengthUnique() method.
    }

    /**
     * @return string
     */
    public static function getEntityName(): string
    {
        // TODO: Implement getEntityName() method.
    }
}