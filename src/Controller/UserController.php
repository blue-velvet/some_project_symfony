<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Events\UserRegisteredEvent;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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

        $event = new UserRegisteredEvent($user);
        $this->dispatcher->dispatch($event);

        return $this->render('main/registrationCompleted.html.twig');
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($lastUsername !== '') {
            return $this->render('main/cabinet.html.twig', [
                    'user' => $this->getUser()
                ]
            );
        }

        return $this->render('main/login.html.twig', [
                'last_username' => $lastUsername,
                'error'         => $error,
            ]
        );
    }
}
