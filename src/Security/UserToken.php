<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 03.12.18 17:28
 */

namespace App\Security;

use App\Entity\Session;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;

/**
 * Class UserToken
 *
 * @package App\Security
 */
class UserToken extends PreAuthenticatedToken
{
    /**
     * @var Session
     */
    protected $session = null;

    /**
     * UserToken constructor.
     *
     * @param mixed $user
     * @param mixed $credentials
     * @param string $providerKey
     * @param array $roles
     * @param Session $session
     * @internal param array $roles
     */
    public function __construct($user, $credentials, $providerKey, array $roles = [], Session $session = null)
    {
        parent::__construct($user, $credentials, $providerKey, $roles);

        if ($session instanceof Session) {
            $this->setSession($session);
        }
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * @param Session $session
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
    }

}