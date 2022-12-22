<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\UserGame;
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

        $userId=$this->getUser()->getId();
        $array=$this->em->getRepository(UserGame::class)->findGames($userId);
        $gamesId = call_user_func_array('array_merge', $array);
        $games = $this->em->getRepository(Game::class)->findBy(['id' => $gamesId]);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'games' => $games,
        ]);
    }

}
