<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 03.12.18 17:27
 */

namespace App\Security;

use App\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

/**
 * Class UserAuthenticator
 *
 * @package App\Security
 */
class UserAuthenticator implements SimplePreAuthenticatorInterface
{
    /**
     * @param Request $request
     * @param $providerKey
     *
     * @return UserToken
     * @throws UnauthorizedException
     */
    public function createToken(Request $request, $providerKey)
    {
        $token = $request->headers->get('Authorization');

        if (empty($token)) {
            throw new UnauthorizedException();
        }

        return new UserToken(
            'Anonymous User',
            $token,
            $providerKey
        );
    }

    /**
     * @param TokenInterface $token
     * @param $providerKey
     *
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UserToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * @param TokenInterface $tokenInterface
     * @param UserProviderInterface $userProvider
     * @param $providerKey
     *
     * @return UserToken
     * @throws UnauthorizedException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function authenticateToken(TokenInterface $tokenInterface, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof SessionProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of SessionProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $token = $tokenInterface->getCredentials();

        /** @var SessionProvider $userProvider */
        $session = $userProvider->loadSessionByApiKey($token);
        $user = $userProvider->loadUserBySession($session);

        $userToken = new UserToken(
            $user,
            $token,
            $providerKey,
            ['ROLE_USER'],
            $session
        );

        return $userToken;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response(strtr($exception->getMessageKey(), $exception->getMessageData()), 401);
    }
}
