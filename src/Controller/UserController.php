<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Message\UserRegisteredEvent;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
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

        $loginExists = $userRepository->findOneByLogin($login);
        if ($loginExists) {
            $this->addFlash("error", "login already exist");

            return $this->redirectToRoute('register');
        }

        $user->setLogin($login);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);
        $userRepository->save($user);

        $event = new UserRegisteredEvent($user->getId());
        $this->messageBus->dispatch($event);

        return $this->redirectToRoute('cabinet');
    }
}
