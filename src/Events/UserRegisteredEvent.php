<?php


namespace App\Events;


use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserRegisteredEvent extends Event
{
    const NAME = 'user.registered';

    protected User $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
