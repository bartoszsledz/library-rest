<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 01.12.18 23:59
 */

namespace App\Repository;

use App\Entity\DataBaseEntity;
use App\Exceptions\NotFoundException;

/**
 * Class EntityRepository
 *
 * @package App\Repository
 */
abstract class EntityRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * @param int $publicId
     *
     * @return DataBaseEntity
     * @throws NotFoundException
     */
    public abstract function getByPublicId(int $publicId);

    /**
     * @param int $id
     *
     * @return DataBaseEntity
     * @throws NotFoundException
     */
    public abstract function getById(int $id);

}