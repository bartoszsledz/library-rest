<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 21.11.2018 20:28
 */

namespace App\Entity;

use DateTime;

/**
 * Class DataBaseEntity
 *
 * @package App\Entity
 */
abstract class DataBaseEntity
{

    const MODEL = null;
    const LENGTH_UNIQUE = null;

    protected $virtualFields = [];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(type="bigint", unique=true, options={"unsigned"=true})
     */
    protected $public_id;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    protected $created;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    protected $modified;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * DataBaseEntity constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if ($data) {
            $this->setData($data);
        }
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedField()
    {
        $this->setCreated(new \DateTime());

        if ($this->getModified() === null) {
            $this->setModified(new \DateTime());
        }
    }

    /**
     * @ORM\PostUpdate()
     */
    public function setUpdatedField()
    {
        $this->setModified(new \DateTime());
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPublicId(): ?int
    {
        return $this->public_id;
    }

    /**
     * @param int $public_id
     *
     * @return DataBaseEntity
     */
    public function setPublicId(int $public_id): self
    {
        $this->public_id = $public_id;

        return $this;
    }

    /**
     * @return null|DateTime
     */
    public function getCreated(): ?DateTime
    {
        return $this->created;
    }

    /**
     * @return User
     */
    public function setCreated(): self
    {
        $this->created = new DateTime();

        return $this;
    }

    /**
     * @return null|DateTime
     */
    public function getModified(): ?DateTime
    {
        return $this->modified;
    }

    /**
     * @return User
     */
    public function setModified(): self
    {
        $this->modified = new \DateTime();

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $string
     * @param string $separator
     *
     * @return string
     */
    private function toCamelCase(string $string, string $separator = '_'): string
    {
        return str_replace($separator, '', ucwords($string, $separator));
    }

    /**
     * Function set values for Entity.
     *
     * @param array $data
     *
     * @return void
     */
    private function setData(array $data = []): void
    {
        //todo dorobić obsługę dla encji powiązanych z wykorzystaniem const MODEL
        foreach ($data as $key => $value) {
            $setter = 'set' . $this->toCamelCase($key);
            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }

        $this->data = $data;
    }

}
