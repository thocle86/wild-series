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
use App\Service\Slugify;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Form\SearchProgramType;

/**
 *  @Route("/programs", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(
        Request $request,
        ProgramRepository $programRepository
    ): Response {
        $form = $this->createForm(SearchProgramType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $programs = $programRepository->findLikeName($search);
        } else {
            $programs = $programRepository->findAll();
        }

        return $this->render(
            "program/index.html.twig", [
                "programs" => $programs,
                "form" => $form->createView(),
            ]
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
    public function new(Request $request, Slugify $slugify, MailerInterface $mailer): Response
    {
        $program = new Program();
        $slugger = new Slugify();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $program->setOwner($this->getUser());
            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Nouvelle série ajoutée !')
                ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));

            $mailer->send($email);

            return $this->redirectToRoute('program_index');
        }
        return $this->render(
            'program/new.html.twig',
            ["form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slugProgram}", methods={"GET"}, name="show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"slugProgram": "slug"}})
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
     * @Route("/{slugProgram}/season/{idSeason}", methods={"GET"}, name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"slugProgram": "slug"}})
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
     * @Route("/{slugProgram}/season/{idSeason}/episode/{slugEpisode}", methods={"GET","POST"}, name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"slugProgram": "slug"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"idSeason": "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"slugEpisode": "slug"}})
     * @return Response
     */
    public function showEpisode(
        Program $program, 
        Season $season, 
        Episode $episode, 
        Request $request, 
        Comment $comment
    ): Response {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setEpisode($episode);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('program_episode_show',
                ['slugProgram' => $program->getSlug(),
                'idSeason' => $season->getId(),
                'slugEpisode' => $episode->getSlug(),]
            );
        }
        return $this->render(
            "program/showEpisode.html.twig",
            ["program" => $program,
            "season" => $season,
            "episode" => $episode,
            'comment' => $comment,
            'form' => $form->createView()]
        );
    }

    /**
     * @Route("/{slugProgram}/edit", name="edit", methods={"GET","POST"})
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"slugProgram": "slug"}})
     */
    public function edit(Request $request, Program $program): Response
    {
        // Check wether the logged in user is the owner of the program
        if (!(in_array('ROLE_ADMIN', $this->getUser()->getRoles())) &&
            !($this->getUser() == $program->getOwner())
            )
        {
            // If not the owner, throws a 403 Access Denied exception
            throw new AccessDeniedException('Only the owner can edit the program!');
        }

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
     * @Route("/{slugProgram}", name="delete", methods={"DELETE"})
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"slugProgram": "slug"}})
     */
    public function delete(Request $request, Program $program): Response
    {
        // Check wether the logged in user is the owner of the program
        if (!(in_array('ROLE_ADMIN', $this->getUser()->getRoles())) &&
            !($this->getUser() == $program->getOwner())
            )
        {
            // If not the owner, throws a 403 Access Denied exception
            throw new AccessDeniedException('Only the owner can edit the program!');
        }

        if ($this->isCsrfTokenValid('delete'.$program->getSlug(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($program);
            $entityManager->flush();
        }

        return $this->redirectToRoute('program_list');
    }
}
