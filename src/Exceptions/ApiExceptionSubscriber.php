<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 27.11.18 21:39
 */

namespace App\Exceptions;

use Opis\JsonSchema\ValidationError;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        if (!$e instanceof ApiException) {
            return;
        }

        $response = new JsonResponse(
            $this->prepareBody($e),
            $e->getStatusCode(),
            ['Content-Type' => 'application/problem+json'],
            true
        );

        $event->setResponse($response);
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

}