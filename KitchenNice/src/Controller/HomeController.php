<?php


namespace App\Controller;


use App\Repository\RecetteRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/*
 *
 */
class HomeController extends AbstractController
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
     * RecetteController constructor.
     * @param RecetteRepository $repository
     * @param ObjectManager $manager
     */
    public function __construct(RecetteRepository $repository, ObjectManager $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index():Response
    {
        if (is_null($this->getUser()))
        {
            $recettes = $this->repository->findBy(['is_private' => 0], ['date_creation' => 'DESC'], 20);
            return $this->render('pages/index.html.twig',
                [
                    'recettes' => $recettes,
                ]);
        }
        else{
            return $this->render('pages/index.html.twig',
                [
                    'abonnements' => $this->getUser()->getAbonnements(),
                    'types_abonne' => $this->getUser()->getTypes(),
                ]);
        }

    }
}