<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\RecetteSearch;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,
                [
                    'required' => false,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Titre',
                    ],

                ])
            ->add('difficulte', ChoiceType::class,
                [
                    'required' => false,
                    'label' => false,
                    'empty_data' => '',
                    'placeholder' => 'Difficulte',
                    'choices' =>
                        [
                            'Enfantin' => 'enfantin',
                            'Facile' => 'facile',
                            'Moyennne' => 'moyenne',
                            'Difficile' => 'difficile',
                            'Grand chef' => 'grand chef',
                        ],
                    'attr' => [
                        'placeholder' => 'Difficulté',
                    ],
                ])
            ->add('cout',ChoiceType::class,
                [
                    'required' => false,
                    'label' => false,
                    'empty_data' => '',
                    'placeholder' => 'Coût',
                    'choices' =>
                        [
                            'Economique' => 'économique',
                            'Abordable' => 'abordable',
                            'Chère' => 'chère',
                            'Très chère' => 'très chère',
                        ],
                ])
            ->add('ingredients', EntityType::class,
                [
                    'class' => Ingredient::class,
                    'required' => false,
                    'label' => false,
                    'empty_data' => '',
                    'placeholder' => 'Ingrédient(s)',
                    'multiple' => true,
                    'choice_label' => 'nom',
                    'attr' => ['class' => 'select_multiple',],

                ])
            ->add('types', EntityType::class,
                [
                    'class' => Type::class,
                    'required' => false,
                    'label' => false,
                    'empty_data' => '',
                    'placeholder' => 'Type(s)',
                    'multiple' => true,
                    'choice_label' => 'type',
                    'attr' => ['class' => 'select_multiple',],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecetteSearch::class,
            'method' => 'get',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
