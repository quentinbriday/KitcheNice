<?php


namespace App\Controller;


use App\Entity\Conversation;
use App\Entity\MessagePrive;
use App\Form\AddMessageType;
use App\Form\ConversationType;
use App\Form\MessagePriveType;
use App\Repository\ConversationRepository;
use App\Repository\MessagePriveRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Mgilet\NotificationBundle\Manager\NotificationManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ConversationController
 * @IsGranted("ROLE_USER")
 * @package App\Controller
 */
class ConversationController extends AbstractController
{


    /**
     * @var ConversationRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var MessagePriveRepository
     */
    private $messagePriveRepository;
    /**
     * @var NotificationManager
     */
    private $notificationManager;

    /**
     * ConversationController constructor.
     * @param ConversationRepository $repository
     * @param ObjectManager $manager
     */
    public function __construct(ConversationRepository $repository, MessagePriveRepository $messagePriveRepository, ObjectManager $manager, NotificationManager $notificationManager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->messagePriveRepository = $messagePriveRepository;
        $this->notificationManager = $notificationManager;
    }

    /**
     * @Route("/conversation", name="conversation.index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $conversations = $this->repository->findByUser($this->getUser());

        return $this->render('conversation/index.html.twig',
            [
                'conversations' => $conversations,
            ]);
    }

    /**
     * @Route("/conversation/show-{id}", name="conversation.show")
     */
    public function show($id): Response
    {
        $conversation = $this->repository->findOneById($id);
        if(is_null($conversation))
        {
            return $this->redirectToRoute('conversation.index');
        }
        $messages = $this->messagePriveRepository->findAllByConvInOrder($id);
        return $this->render('conversation/show.html.twig',
            [
                'conversation' => $conversation,
                'messages' => $messages,
            ]);
    }

    /**
     * @Route("/conversation/new" , name="conversation.new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $conversation = new Conversation();
        $form = $this->createForm(ConversationType::class, $conversation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $titre = 'Conversation entre ' . $this->getUser()->getUsername() . ' et ' . $conversation->getMembre2()->getUsername();
            $conversation->setMembre1($this->getUser());
            $conversation->setTitre($titre);

            $this->manager->persist($conversation);
            $this->manager->flush();

            return $this->redirectToRoute('conversation.index');
        }

        return $this->render('conversation/new.html.twig',
            [
                'form' => $form->createView(),
                'conversation' => $conversation,
            ]);
    }

    /**
     * @Route("/ajouter_message/{id}", name="conversation.addMessage")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addMessage($id, Request $request)
    {
        $message = new MessagePrive();
        $form = $this->createForm(AddMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $message->setDateCreation(new \DateTime());
            $message->setEnvoyeur($this->getUser());
            $message->setIsSeen(0);
            $conversation = $this->repository->findOneById($id);
            $message->setConversation($conversation);
            $this->manager->persist($message);
            $this->manager->flush();

            if ($conversation->getMembre1() == $this->getUser())
            {
                $membre = $conversation->getMembre2();
            }
            else
            {
                $membre = $conversation->getMembre1();
            }

            $notif = $this->notificationManager->createNotification('Message', $this->getUser()->getUsername() .' vous a envoyé un message privé. !', '/conversation/show-' .$conversation->getId());
            $this->notificationManager->addNotification(array($membre), $notif, true);

            return $this->redirectToRoute('conversation.show',
                [
                    'id' => $id,
                ]);
        }

        return $this->render('message/new.html.twig',
            [
                'id' => $id,
                'form' => $form->createView(),
                'message' => $message,
            ]);
    }
}