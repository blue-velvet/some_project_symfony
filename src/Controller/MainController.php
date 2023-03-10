<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/register', name: 'register')]
    public function registerUser(): Response
    {
        return $this->render('main/register.html.twig');
    }
}
