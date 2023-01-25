<?php


namespace App\Events;


class UserListener
{
    public function onUserRegisteredEvent(UserRegisteredEvent $event)
    {
        $user = $event->getUser();
        echo $user->name . "\r\n";
        echo $user->age . "\r\n";
    }
}


