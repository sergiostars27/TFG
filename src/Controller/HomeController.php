<?php

namespace App\Controller;

use App\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/home', name: 'home')]
    public function index(): Response
    {

        $games = $this->em->getRepository(Game::class)->findAllGame();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'games' => $games,
        ]);
    }
}
