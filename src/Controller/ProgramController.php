<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;

/**
 *  @Route("/programs", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render(
            "program/index.html.twig",
            ["programs" => $programs]
        );
    }

    /**
     * @Route("/list", name="list")
     * @return Response
     */
    public function list(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render(
            "program/list.html.twig",
            ["programs" => $programs]
        );
    }

    /**
     * The controller for the program add form
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();

            return $this->redirectToRoute('program_index');
        }
        return $this->render(
            'program/new.html.twig',
            ["form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idProgram}", methods={"GET"}, name="show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"idProgram": "id"}})
     * @return Response
     */
    public function show(Program $program): Response
    {
        return $this->render(
            "program/showProgram.html.twig",
            ["program" => $program]
        );
    }

    /**
     * @Route("/{idProgram}/season/{idSeason}", methods={"GET"}, name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"idProgram": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"idSeason": "id"}})
     * @return Response
     */
    public function showSeason(Program $program, Season $season): Response
    {
        return $this->render(
            "program/showSeason.html.twig",
            ["program" => $program,
            "season" => $season]
        );
    }

    /**
     * @Route("/{idProgram}/season/{idSeason}/episode/{idEpisode}", methods={"GET"}, name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"idProgram": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"idSeason": "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"idEpisode": "id"}})
     * @return Response
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render(
            "program/showEpisode.html.twig",
            ["program" => $program,
            "season" => $season,
            "episode" => $episode]
        );
    }

    /**
     * @Route("/{idProgram}/edit", name="edit", methods={"GET","POST"})
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"idProgram": "id"}})
     */
    public function edit(Request $request, Program $program): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('program_list');
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idProgram}", name="delete", methods={"DELETE"})
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"idProgram": "id"}})
     */
    public function delete(Request $request, Program $program): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($program);
            $entityManager->flush();
        }

        return $this->redirectToRoute('program_list');
    }
}
