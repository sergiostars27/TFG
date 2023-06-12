<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\History;
use App\Entity\UserGame;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    #[Route('/home/game/{id}/history', name: 'history')]
    public function index(Game $game): Response
    {
        $rol = $this->em->getRepository(UserGame::class)->findOneBy(['user' => $this->getUser(),'game' => $game])->isRol();
        $historial = $this->em->getRepository(History::class)->findBy(['game' => $game]);
        return $this->render('history/index.html.twig', ['game' => $game, 'rol' => $rol, 'historial' => $historial]);
    }
}
