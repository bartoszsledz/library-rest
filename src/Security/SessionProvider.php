<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 03.12.18 17:23
 */

namespace App\Security;

use App\Entity\Session;
use App\Exceptions\UnauthorizedException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * Class SessionProvider
 *
 * @package App\Security
 */
class SessionProvider implements UserProviderInterface
{

    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * @var Container $container
     */
    protected $container;

    /**
     * SessionProvider constructor.
     *
     * @param EntityManager $entityManager
     * @param Container $container
     */
    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    /**
     * @param string $token
     *
     * @return Session
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws UnauthorizedException
     * @throws \Exception
     */
    public function loadSessionByApiKey(string $token): Session
    {
        $sessionsRepository = $this->entityManager->getRepository(Session::class);

        /** @var Session $session */
        $session = $sessionsRepository->createQueryBuilder('s')
            ->select('s, u')
            ->leftJoin('s.user', 'u')
            ->andWhere('s.token = :token')
            ->andWhere('s.status = :status')
            ->andWhere('s.expires > :expires')
            ->setParameter('token', $token)
            ->setParameter('status', \App\Enums\Session::STATUS_ACTIVE)
            ->setParameter('expires', (new \DateTime())->format('Y-m-d H:i:s'))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!($session instanceof Session)) {
            throw new UnauthorizedException(sprintf('Session token "%s" does not exist.', $token));
        }

        return $session;
    }

    /**
     * @param Session $session
     *
     * @return \App\Entity\User
     */
    public function loadUserBySession(Session $session): \App\Entity\User
    {
        return $session->getUser();
    }

    /**
     * @param string $username
     * @return UserInterface|void
     */
    public function loadUserByUsername($username)
    {
        throw new UnsupportedUserException();
    }

    /**
     * @param UserInterface $user
     * @return UserInterface|void
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}