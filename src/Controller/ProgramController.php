<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;

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

        if (!$programs) {
            throw $this->createNotFoundException('The program does not exist');
        }

        return $this->render(
            "program/index.html.twig",
            ["programs" => $programs]
        );
    }

    /**
     * @Route("/{id<^[0-9]+$>}", methods={"GET"}, name="show")
     * @return Response
     */
    public function show(ProgramRepository $programRepository, int $id): Response
    {
        $program = $programRepository->findOneBy(["id" => $id]);

        if (!$program) {
            throw $this->createNotFoundException('The program does not exist');
        }

        return $this->render(
            "program/show.html.twig",
            ["program" => $program]
        );
    }

    /**
     * @Route("/{idProgram<^[0-9]+$>}/season/{numberSeason<^[0-9]+$>}", methods={"GET"}, name="season_show")
     * @return Response
     */
    public function showSeason(int $idProgram, SeasonRepository $seasonRepository, int $numberSeason): Response
    {
        $season = $seasonRepository->findOneBy(
            ["program" => $idProgram,
            "number" => $numberSeason]
        );

        return $this->render(
            "program/seasonShow.html.twig",
            ["season" => $season]
        );
    }
}
