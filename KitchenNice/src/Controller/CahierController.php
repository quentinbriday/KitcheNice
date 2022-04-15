<?php

namespace App\Controller;

use App\Entity\Cahier;
use App\Form\CahierType;
use App\Repository\CahierRepository;
use App\Repository\RecetteRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CahierController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 * @Route("/cahier")
 */
class CahierController extends AbstractController
{

    /**
     * @var CahierRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(CahierRepository $repository, ObjectManager $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    /**
     * @Route(name="cahier.index")
     */
    public function index()
    {
        $cahiers = $this->repository->findBy(['membre' => $this->getUser()], ['date_creation' => 'DESC']);
        return $this->render('cahier/index.html.twig', [
            'cahiers' => $cahiers,
        ]);
    }

    /**
     * @Route("/new", name="cahier.new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $cahier = new Cahier();
        $form = $this->createForm(CahierType::class, $cahier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $cahier->setMembre($this->getUser());
            $this->manager->persist($cahier);
            $this->manager->flush();
            /*
            if (!$recette->getIsPrivate())
            {
                $notif = $this->notificationManager->createNotification('Abonnement', $this->getUser()->getUsername() .' a ajouté une nouvelle recette !', '/recettes/show-' .$recette->getId());
                $this->notificationManager->addNotification($this->getUser()->getMembresAbonnes(), $notif, true);
            }
            */

            return $this->redirectToRoute('cahier.index');
        }

        return $this->render('cahier/new.html.twig',
            [
                'form' => $form->createView(),
                'cahier' => $cahier,
            ]);
    }

    /**
     * @Route("/show-{id}", name="cahier.show")
     * @param $id
     * @return Response
     */
    public function show($id): Response
    {
        $cahier = $this->repository->findOneBy(['id' => $id]);
        if(is_null($cahier))
        {
            return $this->redirectToRoute('cahier.index');
        }
        else
        {
            if ($cahier->getIsPrivate() && $cahier->getMembre() != $this->getUser())
            {
                return $this->redirectToRoute('cahier.index');
            }
            else
            {
                return $this->render('cahier/show.html.twig',
                    [
                        'cahier' => $cahier,
                    ]);
            }
        }
    }

    /**
     * @Route("/edit-{id}", name="cahier.edit")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function edit($id, Request $request)
    {
        $cahier = $this->repository->findOneBy(['id' => $id, 'membre'=> $this->getUser()]);
        $form = $this->createForm(CahierType::class, $cahier);
        $form->handleRequest($request);

        if(is_null($cahier)) {
            $this->addFlash('danger', 'Vous n\'avez pas accès à ce cahier');
            return $this->redirectToRoute('cahier.index');
        }
        if ($form->isSubmitted() && $form->isValid())
        {
            $cahier->setDateDerniereModif(new \DateTime());
            $this->manager->flush();
            $this->addFlash('success', 'Le cahier a bien été modifié.');
            return $this->redirectToRoute('cahier.show', [
                'id' => $id,
            ]);
        }
        return $this->render('cahier/edit.html.twig', [
            'form' => $form->createView(),
            'cahier' => $cahier,
        ]);
    }

    /**
     * @Route("/{id}", name="cahier.delete")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $id, $request->get('_token')))
        {
            $cahier = $this->repository->findOneBy(['id' => $id, 'membre'=> $this->getUser()]);
            if(is_null($cahier)){
                $this->addFlash('danger', 'Vous n\'avez pas accès à ce cahier');
                return $this->redirectToRoute('cahier.index');
            }
            $this->manager->remove($cahier);
            $this->manager->flush();
            $this->addFlash('success', 'Le cahier a bien été supprimé.');
            return $this->redirectToRoute('cahier.index');
        }
        return $this->redirectToRoute('cahier.index');

    }

    /**
     * @Route("/remove_recette/{cahierId}-{recetteId}", name="cahier.remove_recette")
     * @param $cahierId
     * @param $recetteId
     * @param RecetteRepository $recetteRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeRecette($cahierId, $recetteId, RecetteRepository $recetteRepository)
    {
        $cahier = $this->repository->findOneBy(['id' => $cahierId, 'membre' => $this->getUser()]);
        $recette = $recetteRepository->findOneBy(['id' => $recetteId]);
        if (is_null($cahier) || is_null($recette))
        {
            $this->addFlash('danger', 'Erreur système.');
            return $this->redirectToRoute('cahier.index');
        }
        $recette->removeCahier($cahier);
        $this->manager->flush();
        $this->addFlash('success', 'La recette est bien retirée.');
        return $this->redirectToRoute('cahier.show',
            [
                'id' => $cahierId,
            ]);
    }
}
