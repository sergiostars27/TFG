<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\History;
use App\Entity\UserGame;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use App\Form\RolType;
use Doctrine\Common\Collections\ArrayCollection;

class RolesController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    #[Route('/home/game/{id}/roles', name: 'roles')]
    public function index(Game $game,HttpFoundationRequest $request): Response
    {
        $rol = $this->em->getRepository(UserGame::class)->findOneBy(['user' => $this->getUser(),'game' => $game])->isRol();
        $users = $this->em->getRepository(UserGame::class)->findBy(['game' => $game]);
        $formsv = [];
        $forms = [];

        for($i=0; $i < count($users);$i++){
            $form1 = $this->container->get('form.factory')->createNamedBuilder('ship_form_'.$i, RolType::class, $users[$i])->getForm();
            $form1v = $form1->createView(); 
            $form1->handleRequest($request);
            if($form1->isSubmitted() && $form1->isValid()){
                $historial = new History("Se ha modificado el rol de " . $users[$i]->getUser()->getUsername() . ".",$game,$this->getUser());
                $this->em->persist($users[$i]);
                $this->em->persist($historial);
                $this->em->flush();
                return $this->redirectToRoute('roles',array('id' => $game->getId()));
            }
            array_push($forms,$form1);
            array_push($formsv,$form1v);

        }



        return $this->render('roles/index.html.twig', ['controller_name' => 'RolesController','game' => $game, 'rol' => $rol,'users' => $users, 'forms' => $formsv]);
    }
}
