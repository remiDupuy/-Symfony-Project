<?php
/**
 * Created by PhpStorm.
 * User: remidupuy
 * Date: 19/03/18
 * Time: 15:36
 */

namespace AppBundle\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionListener implements EventSubscriberInterface
{
    const EXCEPTION_CODE = 'The server has a big problem';

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['processExceptionForApi', 1]
        ];
    }

    public function processExceptionForApi(GetResponseForExceptionEvent $event)
    {
        $request = $event->getRequest();
        $routeName = $request->attributes->get('_route');
        $api = substr($routeName, 0, 3);

        if ($api !== 'api') {
            return;
        }

        $data = [
            'code' => self::EXCEPTION_CODE,
            'message' => $event->getException()->getMessage()
        ];

        $response = new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR);
        $event->setResponse($response);
    }
}