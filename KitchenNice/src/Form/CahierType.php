<?php

namespace App\Form;

use App\Entity\Cahier;
use App\Entity\Recette;
use App\Repository\RecetteRepository;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CahierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitule')
            ->add('is_private', CheckboxType::class,
                [
                    'label' => 'Le cahier est-il privé ?',
                    'required' => false,
                ])
            ->add('description', TextareaType::class,
                [
                    'attr' => ['placeholder' => 'Ajoutez une description si vous le souhaitez...'],
                ])
            ->add('captchaCode', CaptchaType::class,
                [
                    'captchaConfig' => 'CahierCaptcha',
                    'label' => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cahier::class,
        ]);
    }
}
