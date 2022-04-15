<?php

namespace App\Form;

use App\Entity\MessagePrive;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessagePriveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenu', TextareaType::class,
                [
                    'label' => false,
                ])
            //->add('date_creation')
            //->add('is_seen')
            //->add('envoyeur')
            //->add('conversation')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MessagePrive::class,
        ]);
    }
}
