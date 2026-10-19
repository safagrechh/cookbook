<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['placeholder' => 'e.g. Lemon pasta with basil'],
            ])
            ->add('preparationTime', IntegerType::class, [
                'label' => 'Preparation time (minutes)',
                'attr' => ['min' => 1, 'placeholder' => '30'],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (optional)',
                'mapped' => false,
                'required' => false,
                'attr' => ['accept' => 'image/*'],
                'constraints' => [
                    new Image(maxSize: '2M'),
                ],
            ])
            ->add('ingredients', CollectionType::class, [
                'entry_type' => IngredientType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'error_bubbling' => false,
                'label' => false,
            ])
            ->add('instructions', TextareaType::class, [
                'attr' => [
                    'rows' => 6,
                    'placeholder' => "Write the recipe steps on separate lines for a cleaner detail page.\nExample:\nCook the pasta until al dente.\nWhisk the sauce.\nToss everything together and serve.",
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
