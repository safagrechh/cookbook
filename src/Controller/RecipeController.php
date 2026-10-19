<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $em, RecipeRepository $recipeRepository): Response
    {
        $recipe = new Recipe();
        $recipe->addIngredient(new Ingredient()); // start with one empty row

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush();

            $this->addFlash('success', sprintf('Recipe "%s" was saved.', $recipe->getTitle()));

            return $this->redirectToRoute('app_home');
        }

        return $this->render('recipe/index.html.twig', [
            'form' => $form,
            'recipes' => $recipeRepository->findBy([], ['id' => 'DESC']),
        ]);
    }
}
