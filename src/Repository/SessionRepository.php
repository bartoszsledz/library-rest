<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 24.11.2018 18:59
 */

namespace App\Repository;

use App\Entity\Session;
use App\Exceptions\NotFoundException;

/**
 * Class SessionRepository
 *
 * @package App\Repository
 */
class SessionRepository extends EntityRepository
{
    /**
     * @param int $publicId
     *
     * @return Session
     * @throws \App\Exceptions\NotFoundException
     */
    public function getByPublicId(int $publicId): Session
    {
        $entity = $this->findOneBy(['public_id' => $publicId]);

        if ($entity instanceof Session) {
            return $entity;
        }

        throw new NotFoundException();
    }

    /**
     * @param int $id
     *
     * @return Session
     * @throws NotFoundException
     */
    public function getById(int $id): Session
    {
        $entity = $this->findOneBy(['id' => $id]);

        if ($entity && $entity instanceof Session) {
            return $entity;
        }

        throw new NotFoundException();
    }

    /**
     * @param string $token
     * @return Session
     * @throws NotFoundException
     */
    public function getByToken($token): Session
    {
        $entity = $this->findOneBy(['token' => $token]);

        if ($entity && $entity instanceof Session) {
            return $entity;
        }

        throw new NotFoundException();
    }

}