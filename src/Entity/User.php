<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 21.11.2018 20:27
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @package App\Entity
 */
class User extends DataBaseEntity implements UserInterface, \Serializable
{

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $roles;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $token;

    /**
     * @var PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Session", mappedBy="user")
     */
    private $session;

    /**
     * @var PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\History", mappedBy="user")
     */
    private $history;

    /**
     * @var PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Borrow", mappedBy="user")
     */
    private $borrow;

    /**
     * User constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->session = new ArrayCollection();
        $this->history = new ArrayCollection();
        $this->borrow = new ArrayCollection();
    }

    /**
     * @return PersistentCollection|null
     */
    public function getSession(): ?PersistentCollection
    {
        return $this->session;
    }

    /**
     * @param Session $session
     *
     * @return User
     */
    public function setSession(Session $session): self
    {
        $this->session[] = $session;

        return $this;
    }

    /**
     * @return PersistentCollection
     */
    public function getHistory(): ?PersistentCollection
    {
        return $this->history;
    }

    /**
     * @param PersistentCollection $history
     *
     * @return User
     */
    public function setHistory(PersistentCollection $history): self
    {
        $this->history = $history;

        return $this;
    }

    /**
     * @param array $roles
     *
     * @return User
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return PersistentCollection
     */
    public function getBorrow(): ?PersistentCollection
    {
        return $this->borrow;
    }

    /**
     * @param PersistentCollection $borrow
     *
     * @return User
     */
    public function setBorrow(PersistentCollection $borrow): self
    {
        $this->borrow = $borrow;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return User
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array (Role|string)[] The user roles
     */
    public function getRoles(): ?array
    {
        $roles = $this->roles;

        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Set the username used to authenticate the user.
     *
     * @param string $username
     * @return User The username
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
                $this->id,
                $this->username,
                $this->public_id,
                $this->email,
                $this->roles,
                $this->password,
                $this->created,
                $this->modified,
                $this->session,
                $this->history,
                $this->token
            ]
        );
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list($this->id,
            $this->username,
            $this->public_id,
            $this->email,
            $this->roles,
            $this->password,
            $this->created,
            $this->modified,
            $this->session,
            $this->history,
            $this->token) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return int
     */
    public static function getLengthUnique(): int
    {
        return \App\Enums\User::LENGTH_UNIQUE;
    }

    /**
     * @return string
     */
    public static function getEntityName(): string
    {
        return \App\Enums\User::MODEL;
    }

}
