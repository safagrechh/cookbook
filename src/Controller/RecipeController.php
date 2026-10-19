<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;
use App\Service\RecipeImageUploader;

class RecipeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        RecipeRepository $recipeRepository,
        RecipeImageUploader $imageUploader,
    ): Response {
        $form = $this->createRecipeForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $recipe->setImageFilename($imageUploader->upload($imageFile));
            }

            $em->persist($recipe);
            $em->flush();

            // Turbo is active: update only the parts of the page that changed
            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->render('recipe/created.stream.html.twig', [
                    'recipe' => $recipe,
                    'form' => $this->createRecipeForm(),
                ]);
            }

            // Fallback without JavaScript: classic redirect with flash message
            $this->addFlash('success', sprintf('Recipe "%s" was saved.', $recipe->getTitle()));

            return $this->redirectToRoute('app_home');
        }

        return $this->render('recipe/index.html.twig', [
            'form' => $form,
            'recipes' => $recipeRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    #[Route('/recipe/{id}', name: 'app_recipe_show', requirements: ['id' => '\d+'])]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    private function createRecipeForm(): FormInterface
    {
        $recipe = new Recipe();
        $recipe->addIngredient(new Ingredient()); // start with one empty row

        return $this->createForm(RecipeType::class, $recipe);
    }
}
