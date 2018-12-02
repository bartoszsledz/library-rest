<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 24.11.2018 18:36
 */

namespace App\Repository;

use App\Entity\Borrow;
use App\Exceptions\NotFoundException;

/**
 * Class BorrowRepository
 *
 * @package App\Repository
 */
class BorrowRepository extends EntityRepository
{

    /**
     * @param int $publicId
     *
     * @return Borrow
     * @throws \App\Exceptions\NotFoundException
     */
    public function getByPublicId(int $publicId): Borrow
    {
        $entity = $this->findOneBy(['public_id' => $publicId]);

        if ($entity instanceof Borrow) {
            return $entity;
        }

        throw new NotFoundException();
    }

    /**
     * @param int $id
     *
     * @return Borrow
     * @throws NotFoundException
     */
    public function getById(int $id)
    {
        $entity = $this->findOneBy(['id' => $id]);

        if ($entity && $entity instanceof Borrow) {
            return $entity;
        }

        throw new NotFoundException();
    }

}