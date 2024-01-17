<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
//use App\Events\UserRegisteredEvent;
use App\Message\UserRegisteredEvent;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    public function __construct(
//        private readonly EventDispatcherInterface $dispatcher,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @throws Exception
     */
    #[Route('/save-user', name: 'save_user')]
    public function saveUser(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
    ): Response
    {
        $user = new User();

        $login = $request->get('login');
        $password = $request->get('password');
        $hashedPassword = $passwordHasher->hashPassword($user, $password);

        $user->setLogin($login);
        $user->setPassword($hashedPassword);
        $userRepository->save($user);

//        $event = new UserRegisteredEvent($user);
//        $this->dispatcher->dispatch($event);
        $event = new UserRegisteredEvent($user->getId());
        $this->messageBus->dispatch($event);

        return $this->redirectToRoute('cabinet');
    }
}
