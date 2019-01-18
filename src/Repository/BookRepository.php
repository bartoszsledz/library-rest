<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 24.11.2018 18:35
 */

namespace App\Repository;

use App\Entity\Book;
use App\Exceptions\NotFoundException;

/**
 * Class BookRepository
 *
 * @package App\Repository
 */
class BookRepository extends EntityRepository
{
    /**
     * @param int $publicId
     *
     * @return Book
     * @throws \App\Exceptions\NotFoundException
     */
    public function getByPublicId(int $publicId): Book
    {
        $entity = $this->findOneBy(['public_id' => $publicId]);

        if ($entity instanceof Book) {
            return $entity;
        }

        throw new NotFoundException();
    }

    /**
     * @param int $id
     *
     * @return Book
     * @throws NotFoundException
     */
    public function getById(int $id)
    {
        $entity = $this->findOneBy(['id' => $id]);

        if ($entity && $entity instanceof Book) {
            return $entity;
        }

        throw new NotFoundException();
    }

}