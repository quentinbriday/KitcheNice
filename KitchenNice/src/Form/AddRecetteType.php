<?php

namespace App\Form;

use App\Entity\Cahier;
use App\Entity\Recette;
use App\Repository\CahierRepository;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class AddRecetteType extends AbstractType
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cahiers', EntityType::class,
                [
                    'class' => Cahier::class,
                    'required' => true,
                    'multiple' => true,
                    'query_builder' => function (CahierRepository $cr) {
                        return $cr->createQueryBuilder('c')
                            ->andWhere('c.membre = :membre')
                            ->setParameter('membre', $this->security->getUser());
                    },
                    'expanded' => true,
                    'choice_label' => 'intitule',
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
