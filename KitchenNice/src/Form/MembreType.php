<?php

namespace App\Form;

use App\Entity\Membre;
use App\Entity\Type;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password', PasswordType::class)
            ->add('mail')
            ->add('captchaCode', CaptchaType::class,
                [
                    'captchaConfig' => 'LoginCaptcha',
                    'label' => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
            'translation_domain' => 'membre',
        ]);
    }
}
