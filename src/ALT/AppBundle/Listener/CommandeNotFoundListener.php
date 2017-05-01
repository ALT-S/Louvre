<?php
/**
 * Created by PhpStorm.
 * User: Anne-Laure
 * Date: 01/05/2017
 * Time: 15:31
 */

namespace ALT\AppBundle\Listener;


use ALT\AppBundle\Exception\CommandeNotFoundException;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CommandeNotFoundListener implements EventSubscriberInterface
{

    /** @var Router */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if (false === $exception instanceof CommandeNotFoundException) {
            return;
        }

        $event->setResponse(new RedirectResponse($this->router->generate('accueil')));
    }

    public static function getSubscribedEvents()
    {
        return array(
            // must be registered after the default Locale listener
            KernelEvents::EXCEPTION => array(array('onKernelException', 0)),
        );
    }

}