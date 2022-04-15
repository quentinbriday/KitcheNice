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

class MembreModifType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mail')
            ->add('types', EntityType::class,
                [
                    'class' => Type::class,
                    'label' => 'Choisissez les types de recettes auxquels vous abonner',
                    'multiple' => true,
                    'choice_label' => 'type',
                    'required' => false,
                    'attr' => ['class' => 'select_multiple',],
                ])
            ->add('captchaCode', CaptchaType::class,
                [
                    'captchaConfig' => 'TestCaptcha',
                    'label' => false,
                ])
            /*
            ->add('date_creation')
            ->add('date_derniere_modif')
            ->add('types')
            */
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