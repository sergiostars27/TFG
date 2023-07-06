<?php

namespace App\Controller;

use App\Entity\Ficha;
use App\Entity\Game;
use App\Entity\UserGame;
use App\Entity\Attributes;
use Attribute;
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
            return $this->redirectToRoute('ficha',array('id' => $game->getId()));      
        }
        return $this->render('ficha/' . $game->getGameSystem() . '-creacion.html.twig', [
            'controller_name' => 'FichaController',
            'game' => $game,
            'rol' => $rol,
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/home/game/{game}/ficha/{ficha}', name: 'fichaDetalles')]
    public function details(Game $game, Ficha $ficha): Response
    {
        $rol = $this->em->getRepository(UserGame::class)->findOneBy(['user' => $this->getUser(),'game' => $game])->isRol();
        $listaAtributos = [];
        for($i = 0; $i<count($ficha->getAtributos());$i++){
            $listaAtributos[$ficha->getAtributos()[$i]->getName()] = $ficha->getAtributos()[$i]->getAttribute();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {     
            $ficha->setCharacterName($_POST['_name']);
            $ficha->setAge($_POST['_age']);
            $ficha->setSexo($_POST['_gender']);
            $atributos = ($_POST['_atributos']);
            $nombres = ($_POST['_test']);
            for($i = 0; $i<count($atributos);$i++){
                $this->em->getRepository(Attributes::class)->findOneBy(['ficha' => $ficha,'name' => $nombres[$i]])->setAttribute($atributos[$i]);

            }
            $this->em->flush();
            return $this->redirectToRoute('ficha',array('id' => $game->getId())); 
        }

        return $this->render('ficha/' . $game->getGameSystem() . '-detalles.html.twig', [
            'controller_name' => 'FichaController',
            'game' => $game,
            'rol' => $rol,
            'ficha' => $ficha,
            'user' => $this->getUser(),
            'atributos' => $listaAtributos,
        ]);
    }


    #[Route('/home/game/{game}/ficha/delete/{ficha}', name: 'fichaDelete')]
    public function fichaDelete(Game $game, Ficha $ficha) {

        $this->em->remove($ficha);
        $this->em->flush();

        return $this->redirectToRoute('ficha',array('id' => $game->getId())); 
    }
}
