<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Quantite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuantiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantite', IntegerType::class,
                [
                    'label' => false
                ])
            ->add('unite_mesure', TextType::class,
                [
                    'label' => false,
                    'required' => false,
                ])
            ->add('ingredient', EntityType::class,
                [
                    'label' => false,
                    'class' => Ingredient::class,
                    'choice_label' => 'nom',
                ])
            /*
            ->add('recette')
            */

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quantite::class,
        ]);
    }
}
