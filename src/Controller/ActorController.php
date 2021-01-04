<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ActorRepository;
use App\Entity\Actor;

/**
 *  @Route("/actors", name="actor_")
 */
class ActorController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(ActorRepository $actorRepository): Response
    {
        $actors = $actorRepository->findAll();

        return $this->render(
            "actor/index.html.twig",
            ["actors" => $actors]
        );
    }

    /**
     * @Route("/{idActor}", methods={"GET"}, name="show")
     * @ParamConverter("actor", class="App\Entity\Actor", options={"mapping": {"idActor": "id"}})
     * @return Response
     */
    public function show(Actor $actor): Response
    {
        return $this->render(
            "actor/show.html.twig",
            ["actor" => $actor]
        );
    }
}
