<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Events\UserRegisteredEvent;
use App\Repository\UserRepository;
use Exception;
use http\Exception\RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private EventDispatcherInterface $dispatcher;
    private UserRepository $userRepository;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        UserRepository $userRepository
    )
    {
        $this->dispatcher = $dispatcher;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws Exception
     */
    #[Route('/save-user', name: 'save_user')]
    public function saveUser(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();

        $email = $request->get('email');
        if ($email === null) {
            throw new \RuntimeException('empty email');
        }
        if ($this->userRepository->findOneByLogin($email) !== null) {
            throw new \RuntimeException(sprintf('User %s already exists', $email));
        }

        $password = $request->get('password');
        if ($password === null) {
            throw new \RuntimeException('empty password');
        }

        $user->setEmail($email);
        $user->setPassword($password);
        $userRepository->save($user);

        $event = new UserRegisteredEvent($user);
        $this->dispatcher->dispatch($event);

        return $this->render('main/registrationCompleted.html.twig');
    }
}
