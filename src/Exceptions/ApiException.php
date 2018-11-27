<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 27.11.18 21:41
 */

namespace App\Exceptions;

/**
 * Class ApiException
 *
 * @package App\Exceptions
 */
abstract class ApiException extends \Exception
{

    /** @var int */
    private $statusCode;

    /** @var array */
    private $errors;

    /**
     * BadRequestException constructor.
     *
     * @param int $statusCode
     * @param array|null $errors
     */
    public function __construct(int $statusCode, array $errors = [])
    {
        parent::__construct();

        $this->setStatusCode($statusCode);
        $this->setErrors($errors);
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

}