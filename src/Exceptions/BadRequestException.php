<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 27.11.18 21:27
 */

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class BadRequestException
 *
 * @package App\Controller
 */
class BadRequestException extends ApiException
{

    /**
     * BadRequestException constructor.
     *
     * @param array|string $errors
     */
    public function __construct($errors = '')
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, $errors);
    }

}