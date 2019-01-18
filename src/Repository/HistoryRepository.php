<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 24.11.2018 18:35
 */

namespace App\Repository;

use App\Entity\History;
use App\Entity\User;
use App\Exceptions\NotFoundException;

/**
 * Class HistoryRepository
 *
 * @package App\Repository
 */
class HistoryRepository extends EntityRepository
{

    /**
     * @param int $publicId
     *
     * @return History
     * @throws \App\Exceptions\NotFoundException
     */
    public function getByPublicId(int $publicId): History
    {
        $entity = $this->findOneBy(['public_id' => $publicId]);

        if ($entity instanceof History) {
            return $entity;
        }

        throw new NotFoundException();
    }

    /**
     * @param int $id
     *
     * @return History
     * @throws NotFoundException
     */
    public function getById(int $id)
    {
        $entity = $this->findOneBy(['id' => $id]);

        if ($entity && $entity instanceof History) {
            return $entity;
        }

        throw new NotFoundException();
    }

    /**
     * @param User $user
     * @return \Doctrine\ORM\Query
     */
    public function getAllHistoryForUserQuery($user)
    {
        return $this->createQueryBuilder('history')
            ->select('history, book')
            ->leftJoin('history.book', 'book')
            ->andWhere('history.user = :user')
            ->setParameter('user', $user)
            ->getQuery();
    }

}