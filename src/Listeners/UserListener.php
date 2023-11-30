<?php


namespace App\Listeners;


use App\Events\UserRegisteredEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserListener implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisteredEvent::NAME => 'onUserRegisteredEvent'
        ];
    }

    public function onUserRegisteredEvent(UserRegisteredEvent $event)
    {
        $user = $event->getUser();
        $this->logger->info('User registered', ['user' => $user]);
    }
}


