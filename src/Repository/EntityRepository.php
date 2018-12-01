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
    public function getByPublicId(int $publicId): DataBaseEntity
    {
        $entity = $this->findOneBy(['public_id' => $publicId]);

        if ($entity && $entity instanceof DataBaseEntity) {
            return $entity;
        }

        throw new NotFoundException();
    }

}