<?php

namespace App\Controller;

use App\Entity\Ficha;
use App\Entity\Game;
use App\Entity\UserGame;
use App\Entity\Attributes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FichaController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/home/game/{id}/ficha', name: 'ficha')]
    public function index(Game $game): Response
    {
        $rol = $this->em->getRepository(UserGame::class)->findOneBy(['user' => $this->getUser(),'game' => $game])->isRol();
        $fichas = $this->em->getRepository(Ficha::class)->findBy(['user' => $this->getUser(),'game' => $game]);
        return $this->render('ficha/index.html.twig', [
            'controller_name' => 'FichaController',
            'fichas' => $fichas,
            'game' => $game,
            'rol' => $rol,
        ]);
    }

    #[Route('/home/game/{id}/ficha/creacion', name: 'ficha_creacion')]
    public function create(Game $game): Response
    {
        $rol = $this->em->getRepository(UserGame::class)->findOneBy(['user' => $this->getUser(),'game' => $game])->isRol();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ficha = new Ficha();
            $ficha->setCharacterName($_POST['_name']);
            $ficha->setAge($_POST['_age']);
            $ficha->setSexo($_POST['_gender']);
            $ficha->setGame($game);
            $ficha->setUser($this->getUser());
            $atributos = ($_POST['_atributos']);
            $nombres = ($_POST['_test']);
            for($i = 0; $i<count($atributos);$i++){
                $atributo = new Attributes($nombres[$i],$atributos[$i]);
                $ficha->addAtributo($atributo);
                $this->em->persist($atributo);

            }
            $this->em->persist($ficha);
            $this->em->flush();        
        }
        return $this->render('ficha/' . $game->getGameSystem() . '-creacion.html.twig', [
            'controller_name' => 'FichaController',
            'game' => $game,
            'rol' => $rol,
        ]);
    }
}
