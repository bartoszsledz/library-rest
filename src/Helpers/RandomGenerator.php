<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 02.07.2018 20:06
 */

namespace App\Helpers;

/**
 * Class RandomGenerator
 *
 * @package App\Helpers
 */
final class RandomGenerator
{

    /**
     * @param int $length
     *
     * @return int
     */
    public static final function generateUniqueInteger(int $length = 11): int
    {
        $number = time() . uniqid('', true) . substr(explode(' ', microtime(false))[0], 2, 6);
        $number = preg_replace('/[^\d]+/', '', $number);
        $number = rand(1, 9) . substr($number, 1 - $length, $length - 1);

        return (int)$number;
    }

    /**
     * @return string
     */
    public static function generateAuthToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }
}
