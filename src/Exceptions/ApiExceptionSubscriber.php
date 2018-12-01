<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 27.11.18 21:39
 */

namespace App\Exceptions;

use Doctrine\DBAL\DBALException;
use Opis\JsonSchema\ValidationError;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ApiExceptionSubscriber
 *
 * @package App\Exceptions
 */
final class ApiExceptionSubscriber implements EventSubscriberInterface
{

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onApiException(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();

        switch ($e) {
            case $e instanceof BadRequestException:
                $event->setResponse($this->handleBadRequestException($e));
                break;
            case $e instanceof NotFoundException:
                $event->setResponse($this->handleNotFoundException($e));
                break;
            case $e instanceof ApiException:
                $event->setResponse($this->handleApiException($e));
                break;
            case $e instanceof DBALException:
                $event->setResponse($this->handlePDOException($e));
                break;
        }

        return;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onApiException'
        ];
    }

    /**
     * @param ApiException $e
     *
     * @return mixed|string
     */
    private function prepareBody(ApiException $e)
    {
        $errors = $e->getErrors();

        if (empty($errors)) {
            return json_encode(['errors' => Response::$statusTexts[Response::HTTP_BAD_REQUEST]]);
        }

        if (is_string($errors)) {
            return json_encode(['errors' => $e->getErrors()]);
        }

        if (is_array($errors) && $errors[0] instanceof ValidationError) {
            $body['status'] = $e->getStatusCode();

            /** @var ValidationError $error */
            foreach ($errors as $error) {
                $dataPointer = $error->dataPointer();
                if (isset($dataPointer[0])) {
                    $body[$dataPointer[0]][$error->keyword()] = $error->keywordArgs();
                } else {
                    $body[$error->keyword()] = $error->keywordArgs();
                }
            }

            return json_encode($body);
        }

        return json_encode(['errors' => 'Error Occurred']);
    }

    /**
     * @param ApiException $e
     * @return JsonResponse
     */
    private function handleBadRequestException($e)
    {
        return new JsonResponse(
            $this->prepareBody($e),
            $e->getStatusCode(),
            ['Content-Type' => 'application/problem+json'],
            true
        );
    }

    /**
     * @param NotFoundException $e
     * @return JsonResponse
     */
    private function handleNotFoundException($e)
    {
        return new JsonResponse(
            json_encode(['errors' => Response::$statusTexts[Response::HTTP_NOT_FOUND]]),
            Response::HTTP_NOT_FOUND,
            ['Content-Type' => 'application/problem+json'],
            true
        );
    }

    /**
     * @param DBALException $e
     * @return JsonResponse
     */
    private function handlePDOException($e)
    {
        return new JsonResponse(
            json_encode(['errors' => $e->getPrevious() ? $e->getPrevious()->getMessage() : $e->getMessage()]),
            Response::HTTP_CONFLICT,
            ['Content-Type' => 'application/problem+json'],
            true
        );
    }

    /**
     * @param ApiException $e
     * @return JsonResponse
     */
    private function handleApiException($e)
    {
        return new JsonResponse(
            json_encode(['errors' => 'Error Occurred']),
            Response::HTTP_BAD_REQUEST,
            ['Content-Type' => 'application/problem+json'],
            true
        );
    }

}