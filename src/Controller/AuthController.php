<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    public function __construct(public TokenStorageInterface $tokenStorage)
    {
    }
    #[Route('/test', name: 'app_main')]
    public function index(): Response
    {
        $tmp = "Test";

        return $this->render('main/index.html.twig', [
            'key' => $tmp
        ]);
    }

    #[Route('/register', name: 'register')]
    public function registerUser(): Response
    {
        if ($this->getUser() !== null) {
            $this->redirectToRoute('cabinet');
        }

        return $this->render('main/register.html.twig');
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('main/login.html.twig', [
                'last_username' => $lastUsername,
                'error'         => $error,
            ]
        );
    }

    #[Route('/logout', name: 'logout')]
    public function logout(Security $security): void
    {
//        $response = $security->logout(false);
    }

    #[Route('/cabinet', name: 'cabinet')]
    public function signInUser(): Response
    {
        return $this->render('main/cabinet.html.twig', [
                'login' => $this->getUser()?->getUserIdentifier()
            ]
        );
    }
}
