<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 02.12.18 19:13
 */

namespace App\Event;

use App\Entity\User;
use App\Exceptions\UnauthorizedException;
use App\Annotation\CheckRequest;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ControllerSubscriber
 *
 * @package App\Event
 */
class ControllerSubscriber implements EventSubscriberInterface
{

    /** @var Reader */
    private $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * @param FilterControllerEvent $event
     *
     * @throws UnauthorizedException
     * @throws \ReflectionException
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controllers = $event->getController())) {
            return;
        }

        $request = $event->getRequest();

        list($controller, $methodName) = $controllers;

        $reflectionClass = new \ReflectionClass($controller);
        $classAnnotation = $this->reader->getClassAnnotation($reflectionClass, CheckRequest::class);

        $reflectionObject = new \ReflectionObject($controller);
        $reflectionMethod = $reflectionObject->getMethod($methodName);
        $methodAnnotation = $this->reader->getMethodAnnotation($reflectionMethod, CheckRequest::class);

        if (!($classAnnotation || $methodAnnotation)) {
            return;
        }

        $token = $event->getRequest()->headers->get('Authorization');
        if (!empty($token)) {
            /** @var User $user */
            $user = $request->getSession()->get($token);
            if ($user !== null && $user->getToken() === $token) {
                $event->stopPropagation();
                return;
            }
        }
        throw new UnauthorizedException('Missing authorization token!');
    }
}