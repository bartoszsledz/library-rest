<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 01.12.18 23:07
 */

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class NotFoundException
 *
 * @package App\Exceptions
 */
class NotFoundException extends ApiException
{

    /**
     * NotFoundException constructor.
     *
     * @param array|string $errors
     */
    public function __construct($errors = [])
    {
        parent::__construct(Response::HTTP_NOT_FOUND, $errors);
    }

}