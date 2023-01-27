<?php

namespace App\Controller;

use App\Entity\Users;
use App\Events\User;
use App\Events\UserListener;
use App\Events\UserRegisteredEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(): Response
    {
        $tmp = "Test";

        return $this->render('main/index.html.twig', [
            'key' => $tmp
        ]);
    }

    /**
     * @param UserRegisteredEvent $result
     *
     * @return Response
     */
    #[Route('/register', name: 'register')]
    public function registerUser(): Response
    {
        $listener = new UserListener();

        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(
            UserRegisteredEvent::NAME,
            array($listener, 'onUserRegisteredEvent')
        );

        $user = new User();
        $user->name = "John";
        $user->age = 25;

        $event = new UserRegisteredEvent($user);

        $result = $dispatcher->dispatch($event);
        return $this->render('main/register.html.twig', [
            'name' => $result->getUser()->name,
            'age' => $result->getUser()->age
        ]);
    }
}
