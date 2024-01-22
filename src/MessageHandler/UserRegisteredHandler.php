<?php

namespace App\MessageHandler;

use App\Message\UserRegisteredEvent;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\Acknowledger;

#[AsMessageHandler]
class UserRegisteredHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private LoggerInterface $logger,
    ) {}

    public function __invoke(UserRegisteredEvent $event)
    {
        $user = $this->userRepository->find($event->getUserId());

        if ($user) {
            $this->logger->notice(sprintf('New user %s was registered', $user->getLogin()));
        } else {
            $this->logger->warning(sprintf('No user with id %d was found', $event->getUserId()));
        }

        $ack = new Acknowledger($this::class);
        $ack->ack();
    }
}