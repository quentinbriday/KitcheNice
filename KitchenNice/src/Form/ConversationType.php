<?php

namespace App\Form;

use App\Entity\Conversation;
use App\Entity\Membre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class ConversationType extends AbstractType
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
            ->add('membre2', EntityType::class,
                [
                    'class' => Membre::class,
                    'label' => 'Avec quel membre voulez-vous converser ?',
                    'choice_label' => 'username',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('m')
                            ->setParameter('id', $this->security->getUser())
                            ->where('EXISTS(
                                        Select i.id from App\Entity\Invitation i 
                                            where i.etat = 1 
                                                and (i.membre_receveur = :id and i.membre_demandeur = m and i.etat = 1 ) 
                                                or (i.membre_demandeur = :id and i.membre_receveur = m) and i.etat = 1 ) 
                                    and NOT EXISTS (
                                        SELECT c1.id from App\Entity\Conversation c1 
                                            where (c1.membre1 = m and c1.membre2 = :id) 
                                                or (c1.membre2 = m and c1.membre1 = :id))')
                            ;
                    },
                    'required' => true,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Conversation::class,
        ]);
    }
}
