<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  @Route("programs", name="programs_")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/{id}", requirements={"id"="\d+"}, methods={"GET"}, name="app_show")
     */
    public function show(int $id): Response
    {
        return $this->render("program/show.html.twig", ["id" => $id]);
    }
}
