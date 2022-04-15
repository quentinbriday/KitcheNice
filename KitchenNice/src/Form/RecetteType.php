<?php

namespace App\Form;

use App\Entity\Allergene;
use App\Entity\Recette;
use App\Entity\Type;
use App\Entity\Ustensil;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,
                [
                    'label' => false,
                ])
            ->add('imageFichier', FileType::class,
                [
                    'required' => false,
                ])
            ->add('duree_preparation', TimeType::class,
                [
                    'widget' => 'single_text',
                ])
            ->add('difficulte', ChoiceType::class,
                [
                    'choices' =>
                        [
                            'Enfantin' => 'enfantin',
                            'Facile' => 'facile',
                            'Moyennne' => 'moyenne',
                            'Difficile' => 'difficile',
                            'Grand chef' => 'grand chef',
                        ],
                ])
            ->add('duree_cuisson', TimeType::class,
                [
                    'widget' => 'single_text',
                ])
            ->add('nb_personnes', IntegerType::class,
                [
                    'label' => false,
                    'help' => 'Une fois la recette validée, les quantités s\'adapteront au nombre de personnes !'
                ])
            ->add('cout', ChoiceType::class,
                [
                    'choices' =>
                        [
                          'Economique' => 'économique',
                          'Abordable' => 'abordable',
                          'Chère' => 'chère',
                          'Très chère' => 'très chère',
                        ],
                ])
            ->add('remarque', TextareaType::class,
                [
                    'required' => false,
                ])
            ->add('is_private', CheckboxType::class,
                [
                    'label' => 'Voulez-vous rendre votre recette privée ?',
                    'required' => false,
                ])
            ->add('quantites', CollectionType::class,
                [
                    'entry_type' => QuantiteType::class,
                    'label' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ])
            ->add('etapes', CollectionType::class,
                [
                    'entry_type' => EtapeType::class,
                    'label' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ])
            ->add('ustensils', EntityType::class,
                [
                    'class' => Ustensil::class,
                    'label' => 'Choisissez vos ustensils',
                    'multiple' => true,
                    'choice_label' => 'nom',
                    'required' => false,
                    'attr' => ['class' => 'select_multiple',],
                ])
            ->add('types', EntityType::class,
                [
                    'class' => Type::class,
                    'label' => 'Choisissez les catégories de la recette',
                    'multiple' => true,
                    'choice_label' => 'type',
                    'attr' => ['class' => 'select_multiple',],
                ])
            ->add('allergenes', EntityType::class,
                [
                    'class' => Allergene::class,
                    'label' => 'Votre recette contient-elle des allergènes ?',
                    'multiple' => true,
                    'choice_label' => 'nom',
                    'required' => false,
                    'attr' => ['class' => 'select_multiple',],
                ])
            ->add('captchaCode', CaptchaType::class,
                [
                    'captchaConfig' => 'RecetteCaptcha',
                    'label' => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
