<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 04.12.18 20:44
 */

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class GeneralException
 *
 * @package App\Exceptions
 */
class GeneralException extends ApiException
{

    /**
     * NotFoundException constructor.
     *
     * @param array|string $errors
     */
    public function __construct($errors = 'Error Occurred')
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, $errors);
    }
    
}