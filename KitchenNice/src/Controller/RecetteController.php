<?php


namespace App\Controller;


use App\Entity\Recette;
use App\Entity\RecetteSearch;
use App\Form\AddRecetteType;
use App\Form\RecetteSearchType;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Mgilet\NotificationBundle\Manager\NotificationManager;
use phpDocumentor\Reflection\Types\Boolean;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class RecetteController
 * @package App\Controller
 * @Route("/recettes")
 */
class RecetteController extends AbstractController
{

    /**
     * @var RecetteRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var NotificationManager
     */
    private $notificationManager;

    /**
     * RecetteController constructor.
     * @param RecetteRepository $repository
     * @param ObjectManager $manager
     * @param NotificationManager $notificationManager
     */
    public function __construct(RecetteRepository $repository, ObjectManager $manager, NotificationManager $notificationManager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->notificationManager = $notificationManager;
    }

    /**
     * @Route(name="recette.index")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new RecetteSearch();
        $form= $this->createForm(RecetteSearchType::class, $search);
        $form->handleRequest($request);

        $recettes = $paginator->paginate(
            $this->repository->findAllRecentQuery($search),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('recette/index.html.twig',
            [
                'recettes' => $recettes,
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/show-{id}", name="recette.show")
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function show($id): Response
    {
        $recette = $this->repository->findOneBy(['id' => $id]);
        if(is_null($recette))
        {
            return $this->redirectToRoute('recette.index');
        }
        if ($recette->getIsPrivate() && $recette->getMembre() != $this->getUser())
        {
            return $this->redirectToRoute('recette.index');
        }
        return $this->render('recette/show.html.twig',
            [
                'recette' => $recette,
                'user' => $this->getUser(),
            ]);
    }

    /**
     * @Route("/mes_recettes", name="recette.mes_recettes")
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function show_my_recipes(): Response
    {
        if ($this->getUser()){
            $recettes = $this->repository->findBy(['membre' => $this->getUser()]);
            if(is_null($recettes))
            {
                return $this->redirectToRoute('recette.index');
            }
            return $this->render('recette/mes_recettes.html.twig',
                [
                    'recettes' => $recettes,
                    'user' => $this->getUser(),
                ]);
        }
        else
        {
            return $this->redirectToRoute('recette.index');
        }
    }

    /**
     * @Route("/new", name="recette.new")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function new(Request $request)
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $i = 1;
            foreach ($recette->getEtapes() as $etape)
            {
                $etape->setNumero($i);
                $i++;
            }
            $recette->setMembre($this->getUser());
            $this->manager->persist($recette);
            $this->manager->flush();

            if (!$recette->getIsPrivate())
            {
                $notif = $this->notificationManager->createNotification('Abonnement', $this->getUser()->getUsername() .' a ajouté une nouvelle recette !', '/recettes/show-' .$recette->getId());
                $this->notificationManager->addNotification($this->getUser()->getMembresAbonnes(), $notif, true);
            }

            return $this->redirectToRoute('recette.index');
        }

        return $this->render('recette/new.html.twig',
            [
                'form' => $form->createView(),
                'recette' => $recette,
            ]);
    }

    /**
     * @Route("edit-{id}", name="recette.edit")
     * @param $id
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function edit($id, Request $request)
    {
        $recette = $this->repository->findOneByUserAndId($this->getUser(), $id);
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if(is_null($recette)) {
            $this->addFlash('danger', 'Vous n\'avez pas accès à cette recette');
            return $this->redirectToRoute('recette.index');
        }
        if ($form->isSubmitted() && $form->isValid())
        {
            $recette->setDateDerniereModif(new \DateTime());
            $this->manager->flush();
            $this->addFlash('success', 'La recette a bien été modifiée.');
            return $this->redirectToRoute('recette.show', [
                'id' => $id,
            ]);
        }
        return $this->render('recette/edit.html.twig', [
            'form' => $form->createView(),
            'recette' => $recette,
        ]);
    }

    /**
     * @Route("/{id}", name="recette.delete")
     * @param $id
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function delete($id, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $id, $request->get('_token')))
        {
            $recette = $this->repository->findOneByUserAndId($this->getUser(), $id);
            if(is_null($recette)){
                $this->addFlash('danger', 'Vous n\'avez pas accès à cette recette');
                return $this->redirectToRoute('recette.index');
            }
            $this->manager->remove($recette);
            $this->manager->flush();
            $this->addFlash('success', 'La recette a bien été supprimée.');
            return $this->redirectToRoute('recette.index');
        }
        return $this->redirectToRoute('recette.index');

    }

    /**
     * @Route("/ajouter_cahier/{id}", name="recette.addCahier")
     * @IsGranted("ROLE_USER")
     * @param $recetteId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Exception
     */
    public function addCahier($id, Request $request)
    {
        $recette = $this->repository->findOneBy(['id' => $id]);
        $form = $this->createForm(AddRecetteType::class, $recette);
        $form->handleRequest($request);
        if(is_null($recette)) {
            $this->addFlash('danger', 'Cette recette n\'existe pas');
            return $this->redirectToRoute('recette.index');
        }
        if ($form->isSubmitted() )
        {
            $recette->setDateDerniereModif(new \DateTime());
            $this->manager->flush();
            $this->addFlash('cahier', 'La recette a bien été ajoutée !');
            return $this->redirectToRoute('recette.show', [
                'id' => $id,
            ]);
        }
        return $this->render('recette/addCahier.html.twig', [
            'form' => $form->createView(),
            'recette' => $recette,
        ]);
    }

    /**
     * @Route("/toPdf/{id}", name="recette.toPdf")
     * @IsGranted("ROLE_USER")
     * @param $id
     * @return Response
     * @throws \Spipu\Html2Pdf\Exception\Html2PdfException
     */
    public function toPDF($id, Request $request): Response
    {
        if ($this->isCsrfTokenValid('pdf' . $id, $request->get('_token')))
        {
            $recette = $this->repository->findOneBy(['id' => $id]);
            $template = $this->renderView('pdf/recette.html.twig',
            [
                'recette' => $recette,
            ]);
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->writeHTML($template);
            $html2pdf->output();
            return $this->redirectToRoute('recette.show',
                [
                    'id' => $id,
                ]);
        }
        else
        {
            $this->addFlash('danger', 'Vous n\'avez pas accès à cette recette.');
            return $this->redirectToRoute('recette.show',
                [
                    'id' => $id,
                ]);
        }
    }


}