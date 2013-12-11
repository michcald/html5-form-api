<?php

namespace Michael\AppBundle\EventListener;

//use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Michael\AppBundle\EventDispatcher\RestRequestEvent;

class AuthorListener implements EventSubscriberInterface
{
    /**
     * Symfony\Component\Security\Core\SecurityContext
     */
    private $securityContext;

    /**
     * Michael\AppBundle\Doctrine\Manager\LogManager
     */
    private $logManager;

    public function __construct($securityContext, $logManager)
    {
        $this->securityContext = $securityContext;
        $this->logManager = $logManager;
    }

    private function getUser()
    {
        return $this->securityContext->getToken()->getUser();
    }

    private function generateLog($event, $message)
    {
        $this->logManager->generate($this->getUser(), $event->getRequest(), $message);
    }

    public static function getSubscribedEvents()
    {
        return array(
            MichaelAuthorEvents::CREATED => 'created',
        );
    }

    private function created()
    {

    }
}
