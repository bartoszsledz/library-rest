<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 24.11.2018 18:58
 */

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Session
 *
 * @ORM\Table(name="session")
 * @ORM\Entity(repositoryClass="App\Repository\SessionRepository")
 *
 * @package App\Entity
 */
class Session extends DataBaseEntity
{

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     *
     */
    private $token;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $expires;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="session")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Session constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * @return string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return Session
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getExpires(): ?DateTime
    {
        return $this->expires;
    }

    /**
     * @param DateTime $expires
     *
     * @return Session
     */
    public function setExpires(DateTime $expires): self
    {
        $this->expires = $expires;

        return $this;
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
     * @return Session
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

}