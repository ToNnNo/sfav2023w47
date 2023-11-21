<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RedirectSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $route = $request->getPathInfo();

        $routeToRedirect = ['/event/no-way' => '/event/new-way'];

        if( !array_key_exists($route, $routeToRedirect) ) {
            return;
        }

        $newRoute = $routeToRedirect[$route];
        $response = new RedirectResponse($newRoute, 301);
        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 256]
        ];
    }
}
