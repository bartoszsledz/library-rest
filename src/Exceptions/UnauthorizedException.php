<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 02.12.18 18:28
 */

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class UnauthorizedException
 *
 * @package App\Exceptions
 */
class UnauthorizedException extends ApiException
{

    /**
     * ForbiddenException constructor.
     *
     * @param array|string $errors
     */
    public function __construct($errors = [])
    {
        parent::__construct(Response::HTTP_UNAUTHORIZED, $errors);
    }

}