<?php

namespace App\Controller;

use App\Entity\User;
use App\Events\UserRegisteredEvent;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private EventDispatcherInterface $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    #[Route('/save-user', name: 'save_user')]
    public function saveUser(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $user->setEmail($request->get('email'));
        $user->setPassword($request->get('password'));
        $userRepository->save($user);

        $event = new UserRegisteredEvent($user);
        $this->dispatcher->dispatch($event);

        return $this->render('main/registrationCompleted.html.twig');
    }
}
