<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
