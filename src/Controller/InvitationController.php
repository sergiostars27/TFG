<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Invitation;
use App\Entity\User;
use App\Entity\UserGame;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvitationController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/home/game/{id}/invitation', name: 'app_invitation')]
    public function index(Game $game): Response
    {

        $invitation = new Invitation();
        $invitation->setGame($game);
        $invitation->setSender($this->getUser());
        $rol = $this->em->getRepository(UserGame::class)->findOneBy(['user' => $this->getUser(),'game' => $game])->isRol();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['_username'];
            $reciver = $this->em->getRepository(User::class)->findBy(['username' => $username]);
            if($reciver != null){
                $invitation->setReciver($reciver[0]);
                $this->em->persist($invitation);
                $this->em->flush();
            }
        }


        return $this->render('invitation/index.html.twig', ['game' => $game, 'rol' => $rol]);
    }






    #[Route('/home/invitationList', name: 'invitation_list')]
    public function list(): Response
    {
        $userId=$this->getUser()->getId();
        $invitations = $this->em->getRepository(Invitation::class)->findBy(['reciver' => $userId]);
        return $this->render('invitation/invitationList.html.twig', ['invitations' => $invitations]);
    }

    #[Route('/invitation/{id}', name: 'invitationDetails')]
    public function invitationDetails(Invitation $invitation) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userGame = new UserGame();
            $userGame->setGame($invitation->getGame());
            $userGame->setUser($this->getUser());
            $userGame->setRol(0);
            $this->em->persist($userGame);
            $this->em->flush();
        }

        return $this->render('invitation/invitation-details.html.twig', ['invitation' => $invitation]);
    }
}
