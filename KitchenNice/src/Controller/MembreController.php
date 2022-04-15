<?php


namespace App\Controller;


use App\Entity\Invitation;
use App\Entity\Membre;
use App\Form\MembreModifType;
use App\Form\MembreType;
use App\Notification\MailNotification;
use App\Repository\ConversationRepository;
use App\Repository\InvitationRepository;
use App\Repository\MembreRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Mgilet\NotificationBundle\Manager\NotificationManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MembreController extends AbstractController
{
    /**
     * @var MembreRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var NotificationManager
     */
    private $notificationManager;

    /**
     * MembreController constructor.
     * @param MembreRepository $repository
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @param NotificationManager $notificationManager
     */
    public function __construct(MembreRepository $repository, ObjectManager $manager, UserPasswordEncoderInterface $encoder, NotificationManager $notificationManager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->notificationManager = $notificationManager;
    }

    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('membre/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ]);
    }

    /**
     * @Route("/membre", name="membre.index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('membre/index.html.twig');
    }

    /**
     * @Route("/register", name="membre.new")
     * @param Request $request
     * @param MailNotification $notification
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function new(Request $request, MailNotification $notification)
    {
        $membre = new Membre();
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            /*
            $membre->setPassword($this->encoder->encodePassword($membre, $membre->getPassword()));
            $this->manager->persist($membre);
            $this->manager->flush();
            */

            $notification->notifyCreatedAccount($membre);

            $this->addFlash('success', 'Un lien de confirmation a été envoyé à l\'adresse mail d\'inscription. Veuillez cliquez dessus pour activer votre compte.');

            return $this->redirectToRoute('home');
        }

        return $this->render('membre/new.html.twig',
            [
                'form' => $form->createView(),
                'membre' => $membre,
            ]);
    }

    /**
     * @Route("/verify-{pseudo}-{mail}-{password}-{token}", name="member.verify")
     * @param $pseudo
     * @param $mail
     * @param $password
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function verifyAccount($pseudo, $mail, $password, $token)
    {
        if ($this->isCsrfTokenValid('verify' . $pseudo, $token))
        {
            $membre = new Membre();
            $membre->setUsername($pseudo)
                ->setMail($mail)
                ->setPassword($this->encoder->encodePassword($membre, $password));
            $this->manager->persist($membre);
            $this->manager->flush();
            $this->addFlash('success', 'Votre compte est validé ! Bienvenue sur KitcheNice !');
            return $this->redirectToRoute('home');
        }
        $this->addFlash('danger', 'Erreur');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/profil/update", name="membre.update")
     * @param Request $request
     * @param MailNotification $notification
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @IsGranted("ROLE_USER")
     */
    public function update(Request $request, MailNotification $notification, UserPasswordEncoderInterface $encoder)
    {
        $membre = $this->repository->findOneByUsername($this->getUser()->getUsername());
        $form = $this->createForm(MembreModifType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if (empty($request->get('pw')))
            {
                $membre->setDateDerniereModif(new \DateTime());
                $this->manager->flush();
                $this->addFlash('success', 'Votre compte a bien été modifié !');
                return $this->redirectToRoute('membre.update');
            }
            else
            {
                if ($request->get('pw') == $request->get('pw2') && $encoder->isPasswordValid($this->getUser(), $request->get('pw3')))
                {
                    $notification->notifyPasswordChanged($membre, $request->get('pw'));
                    $membre->setDateDerniereModif(new \DateTime());
                    $this->manager->flush();
                    $this->addFlash('success', 'Votre compte a bien été modifié. Un mail de confirmation afin de changer votre mot de passe vous a été envoyé.');
                    return $this->redirectToRoute('membre.update');
                }
                else
                {
                    $this->addFlash('danger', 'Erreur dans la modification de mot de passe');
                    return $this->redirectToRoute('membre.update');
                }

            }

        }
        return $this->render('membre/update.html.twig', [
            'form' => $form->createView(),
            'membre' => $membre,
        ]);
    }

    /**
     * @Route("/verify_password-{id}-{password}-{token}", name="membre.verify_password")
     * @param $id
     * @param $password
     * @param $token
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function verifyPassword($id, $password, $token, UserPasswordEncoderInterface $encoder)
    {
        if ($this->isCsrfTokenValid('password' . $id, $token))
        {
            $membre = $this->repository->findOneBy(['id' => $id]);
            if(!is_null($membre))
            {
                $membre->setPassword($encoder->encodePassword($membre, $password));
                $membre->setDateDerniereModif(new \DateTime());
                $this->manager->flush();
                $this->addFlash('success', 'Votre nouveau mot de passe est validé avec succès !');
                return $this->redirectToRoute('home');
            }
            $this->addFlash('danger', 'Erreur');
            return $this->redirectToRoute('home');
        }
        $this->addFlash('danger', 'Erreur.');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/lost_password", name="membre.lost_password")
     * @param Request $request
     * @param MailNotification $notification
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function lostPassword(Request $request, MailNotification $notification)
    {
        $membre = $this->repository->findOneBy(['username' => $request->get('_username')]);
        if(!is_null($membre))
        {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
            $password = substr( str_shuffle( $chars ), 0, 8 );
            $notification->notifyPasswordLost($membre, $password);
            $this->addFlash('success', 'Un mail a été envoyé à l\'adresse mail de l\'utilisateur. Veuillez cliquer sur le lien contenu dans le mail.');
            return $this->redirectToRoute('home');
        }
        $this->addFlash('danger', 'Le nom d\'utilisateur rentré est encorrecte.');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/profil/show-{id}", name="membre.show")
     * @param $id
     * @param MembreRepository $repository
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function show($id): Response
    {
        $membre = $this->repository->findOneBy(['id' => $id]);
        if(is_null($membre))
        {
            return $this->redirectToRoute('');
        }
        return $this->render('membre/show.html.twig',
            [
                'membre' => $membre,
                'user' => $this->getUser(),
            ]);
    }

    /**
     * @Route("/profil/ajouter_abonnement-{id}", name="membre.ajout_abonnement")
     * @param $id
     * @param MembreRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addAbonnement($id)
    {
        $membre = $this->repository->findOneBy(['id' => $id]);
        if ($this->getUser()->addAbonnement($membre))
        {
            $notif = $this->notificationManager->createNotification('Abonnement', $this->getUser()->getUsername() .' s\'est abonné à vos recettes !', '#');
            $this->notificationManager->addNotification(array($membre), $notif, true);
            $this->addFlash('success', 'Vous êtes abonné à ce membre !');
        }
        else{
            $this->addFlash('danger', 'Erreur système. Veuillez réesayer ultérieurement.');
        }
        return $this->redirectToRoute("membre.show",
            [
               'id' => $id,
            ]);
    }

    /**
     * @Route("/profil/supprimer_abonnement-{id}", name="membre.supprimer_abonnement")
     * @param $id
     * @param MembreRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removeAbonnement($id)
    {
        $membre = $this->repository->findOneBy(['id' => $id]);
        if ($this->getUser()->removeAbonnement($membre))
        {
            $notif = $this->notificationManager->createNotification('Abonnement', $this->getUser()->getUsername() .' s\'est désabonné de vos recettes.', '#');
            $this->notificationManager->addNotification(array($membre), $notif, true);
            $this->addFlash('success', 'Vous n\'êtes plus abonné à ce membre.');
        }
        else{
            $this->addFlash('danger', 'Erreur système. Veuillez réesayer ultérieurement.');
        }
        return $this->redirectToRoute("membre.show",
            [
                'id' => $this->getUser()->getId(),
            ]);
    }

    /**
     * @Route("/profil/ajouter_invitation-{id}", name="membre.ajouter_invitation")
     * @param $id
     * @param MembreRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addInvitation($id)
    {
        $membre = $this->repository->findOneBy(['id' => $id]);
        if(is_null($membre))
        {
            return $this->redirectToRoute('');
        }
        else{
            $invitation = new Invitation();
            $invitation->setMembreDemandeur($this->getUser());
            $invitation->setMembreReceveur($membre);
            $this->manager->persist($invitation);
            $this->manager->flush();
            $notif = $this->notificationManager->createNotification('Social', $this->getUser()->getUsername() .' vous demande en tant qu\'ami.', '/profil/show-' .$membre->getId());
            $this->notificationManager->addNotification(array($membre), $notif, true);
            $this->addFlash('success', 'Votre demande a bien été transmise !');
            return $this->redirectToRoute('membre.show',
                [
                    'id' => $membre->getId(),
                ]);
        }
    }

    /**
     * @Route("/profil/retirer_invitation-{id}", name="membre.retirer_invitation")
     * @param $id
     * @param MembreRepository $repository
     * @param InvitationRepository $invitation_repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeInvitation($id, InvitationRepository $invitation_repository)
    {
        $invitation = $invitation_repository->findOneBy(['id' => $id]);
        if (is_null($invitation))
        {
            $this->addFlash('danger', 'Erreur système. Veuillez réessayer ultérieurement.');
            return $this->redirectToRoute('membre.show',
                [
                    'id' => $this->getUser()->getId(),
                ]);
        }
        else
        {
            $this->manager->remove($invitation);
            $this->manager->flush();
            $this->addFlash('success', 'Votre demande a bien été annulée !');
            return $this->redirectToRoute('membre.show',
                [
                    'id' => $this->getUser()->getId(),
                ]);
        }
    }

    /**
     * @Route("/profil/valider_invitation-{id}", name="membre.valider_invitation")"
     * @param $id
     * @param MembreRepository $repository
     * @param InvitationRepository $invitation_repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function validInvitation($id, InvitationRepository $invitation_repository)
    {
        $invitation = $invitation_repository->findOneBy(['id' => $id]);
        if (is_null($invitation))
        {
            $this->addFlash('danger', 'Erreur système. Veuillez réessayer ultérieurement.');
            return $this->redirectToRoute('membre.show',
                [
                    'id' => $this->getUser()->getId(),
                ]);
        }
        else
        {
            $invitation->setEtat(1);
            $this->manager->flush();
            $notif = $this->notificationManager->createNotification('Social', $this->getUser()->getUsername() .' a accepté votre demande d\'amitié.', '#');
            $this->notificationManager->addNotification(array($invitation->getMembreDemandeur()), $notif, true);
            $this->addFlash('success', 'Vous êtes désormais ami avec ce membre !');
            return $this->redirectToRoute('membre.show',
                [
                    'id' => $this->getUser()->getId(),
                ]);
        }
    }

    /**
     * @Route("/profil/refuser_invitation-{id}", name="membre.refuser_invitation")
     * @param $id
     * @param InvitationRepository $invitation_repository
     * @param ConversationRepository $conversationRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function refuseInvitation($id, InvitationRepository $invitation_repository, ConversationRepository $conversationRepository)
    {
        $invitation = $invitation_repository->findOneBy(['id' => $id]);
        if (is_null($invitation))
        {
            $this->addFlash('danger', 'Erreur système. Veuillez réessayer ultérieurement.');
            return $this->redirectToRoute('membre.show',
                [
                    'id' => $this->getUser()->getId(),
                ]);
        }
        else
        {
            $etat = $invitation->getEtat();
            if ($etat == 0)
            {
                $this->addFlash('success', 'La demande a été supprimée avec succès !');
                $notif = $this->notificationManager->createNotification('Social', $this->getUser()->getUsername() .' a refusé votre demande d\'amitié.', '#');
                $this->notificationManager->addNotification(array($invitation->getMembreDemandeur()), $notif, true);
            }
            else
            {
                $this->addFlash('success', 'Vous n\'êtes plus ami avec ce membre.');
                $notif = $this->notificationManager->createNotification('Social', $this->getUser()->getUsername() .' vous a supprimé de ses amis.', '#');
                if ($invitation->getMembreDemandeur()->getUsername() === $this->getUser()->getUsername()){
                    $this->notificationManager->addNotification(array($invitation->getMembreReceveur()), $notif, true);
                    $conversation = $conversationRepository->findByUsers($this->getUser(), $invitation->getMembreReceveur());
                    dump($conversation);
                    if (!is_null($conversation))
                    {
                        $this->manager->remove($conversation);
                    }
                }
                else{
                    $this->notificationManager->addNotification(array($invitation->getMembreDemandeur()), $notif, true);
                    $conversation = $conversationRepository->findByUsers($this->getUser(), $invitation->getMembreDemandeur());
                    if (!is_null($conversation))
                    {
                        $this->manager->remove($conversation);
                    }
                }

            }
            $this->manager->remove($invitation);
            $this->manager->flush();
            return $this->redirectToRoute('membre.show',
                [
                    'id' => $this->getUser()->getId(),
                ]);
        }
    }
}