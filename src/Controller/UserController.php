<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;

class UserController extends AbstractController
{
    /**
     * @Route("/my-profil", name="my_profil")
     * @return Response
     */
    /*public function myProfil(UserRepository $userRepository, User $user): Response
    {
        dd($user);
        $yourEmail = $this->getRoles('');
        $user = $userRepository->findBy(['email' => ]);

        return $this->render(
            "user/myProfil.html.twig",
            ["actors" => $actors]
        );
    }*/
    public function index()
    {
        return $this->render('user/myProfil.html.twig');
    }
}
