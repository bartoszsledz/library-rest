<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 02.12.18 15:09
 */

namespace App\Enums;

/**
 * Class Session
 *
 * @package App\Enums
 */
class Session
{
    const MODEL = 'Session';
    const LENGTH_UNIQUE = 11;
    const STATUS_ACTIVE = 1;
    const STATUS__EXPIRES = 2;
    const SESSION_LIFE_TIME = '+15 minutes';
}