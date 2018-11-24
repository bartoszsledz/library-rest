<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 24.11.2018 18:36
 */

namespace App\Repository;

use App\Entity\Borrow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class BorrowRepository
 *
 * @method Borrow|null find($id, $lockMode = null, $lockVersion = null)
 * @method Borrow|null findOneBy(array $criteria, array $orderBy = null)
 * @method Borrow[]    findAll()
 * @method Borrow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @package App\Repository
 */
class BorrowRepository extends ServiceEntityRepository
{

    /**
     * BorrowRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Borrow::class);
    }

}