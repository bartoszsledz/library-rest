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

    /** @var array|string|null */
    private $errors;

    /**
     * BadRequestException constructor.
     *
     * @param int $statusCode
     * @param array|string|null $errors
     */
    public function __construct(int $statusCode, $errors = null)
    {
        parent::__construct();

        $this->setStatusCode($statusCode);
        $this->setErrors($errors);

        $this->saveLog($statusCode, $errors);
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
     * @return array|string|null
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array|string|null $errors
     */
    public function setErrors($errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @param int $statusCode
     * @param string|array $errors
     */
    private function saveLog(int $statusCode, $errors)
    {
        //todo
    }

}