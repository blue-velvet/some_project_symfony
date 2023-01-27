<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/save-user', name: 'save_user')]
    public function saveUser(Request $request, ManagerRegistry $doctrine): Response
    {
        dump($request->request);
        $em = $doctrine->getManager();

        $user = new User();
        $user->setEmail($request->request->get('firstName'));
        $user->setPassword($request->get('password'));

        $em->persist($user);
        $em->flush();

        return $this->render('main/registrationCompleted.html.twig');
    }
}
