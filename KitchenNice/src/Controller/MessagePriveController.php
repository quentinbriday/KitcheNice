<?php


namespace App\Controller;


use App\Entity\Conversation;
use App\Entity\MessagePrive;
use App\Form\MessagePriveType;
use App\Repository\ConversationRepository;
use App\Repository\MessagePriveRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Mgilet\NotificationBundle\Manager\NotificationManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MessagePriveController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class MessagePriveController extends AbstractController
{

    /**
     * @var MessagePriveRepository
     */
    private $repository;
    /**
     * @var ConversationRepository
     */
    private $conversationRepository;
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var NotificationManager
     */
    private $notificationManager;

    public function __construct(MessagePriveRepository $repository, ConversationRepository $conversationRepository, ObjectManager $manager)
    {

        $this->repository = $repository;
        $this->conversationRepository = $conversationRepository;
        $this->manager = $manager;
        $this->notificationManager = $notificationManager;
    }

    /**
     * @Route("/message/new", name="message.new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function new(Request $request)
    {
        $message = new MessagePrive();
        $form = $this->createForm(MessagePriveType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $message->setDateCreation(new \DateTime());
            $message->setEnvoyeur($this->getUser());
            $message->setIsSeen(0);
            $convId = $request->get('convId');
            $conversation = $this->conversationRepository->findOneById($convId);
            $conversation->addMessage($message);
            $this->manager->persist($message);
            $this->manager->flush();

            return $this->redirectToRoute('conversation.show',
                [
                    'id' => $convId,
                ]);
        }

        return $this->render('message/new.html.twig',
            [
                'form' => $form->createView(),
                'message' => $message,
            ]);
    }
}