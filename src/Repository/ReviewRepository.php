<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 09.12.18 19:29
 */

namespace App\Repository;


use App\Entity\Book;
use App\Entity\DataBaseEntity;
use App\Entity\Review;
use App\Exceptions\NotFoundException;

/**
 * Class ReviewRepository
 *
 * @package App\Repository
 */
class ReviewRepository extends EntityRepository
{

    /**
     * @param int $publicId
     *
     * @return DataBaseEntity
     * @throws NotFoundException
     */
    public function getByPublicId(int $publicId)
    {
        $entity = $this->findOneBy(['public_id' => $publicId]);

        if ($entity instanceof Review) {
            return $entity;
        }

        throw new NotFoundException();
    }

    /**
     * @param int $id
     *
     * @return DataBaseEntity
     * @throws NotFoundException
     */
    public function getById(int $id)
    {
        $entity = $this->findOneBy(['id' => $id]);

        if ($entity instanceof Review) {
            return $entity;
        }

        throw new NotFoundException();
    }

    /**
     * @param Book $book
     * @return \Doctrine\ORM\Query
     */
    public function getAllReviewsForBookQuery($book)
    {
        return $this->createQueryBuilder('review')
            ->select('review, book, user')
            ->leftJoin('review.book', 'book')
            ->leftJoin('review.user', 'user')
            ->andWhere('review.book = :book')
            ->setParameter('book', $book)
            ->getQuery();
    }

}