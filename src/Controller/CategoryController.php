<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Category;
use App\Repository\ProgramRepository;
use App\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/categories", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render(
            'category/index.html.twig',
            ["categories" => $categories]
        );
    }

    /**
     * The controller for the category add form
     *
     * @Route("/new", name="new")
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            
            return $this->redirectToRoute('category_index');
        }
        return $this->render(
            'category/new.html.twig',
            ["form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/{categoryName}", methods={"GET"}, name="show")
     * @ParamConverter("category", class="App\Entity\Category", options={"mapping": {"categoryName": "name"}})
     * @return Response
     */
    public function show(Category $category, ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findBy(
                ["category" => $category],
                ["id" => "DESC"],
                3
            );

        return $this->render(
            "category/show.html.twig",
            ["category" => $category,
            "programs" => $programs]
        );
    }
}
