<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Message;
use App\Entity\UserGame;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class ChatController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/home/game/{id}/chat', name: 'chat')]
    public function index(Game $game): Response
    {
        $rol = $this->em->getRepository(UserGame::class)->findOneBy(['user' => $this->getUser(),'game' => $game])->isRol();
        $messages = $this->em->getRepository(Message::class)->findBy(['game' => $game]);
        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
            'messages' => $messages,
            'game' => $game,
            'rol' => $rol,
        ]);
    }

    #[Route('/home/game/{id}/push', name: 'push')]
    public function publish(HubInterface $hub, Game $game): Response
    {
        $message = new Message();
        $message->setGame($game);
        $message->setUser($this->getUser());
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = $_POST['_content'];
            if($content != ""){
                $date = date("m/d/Y H:i:s"); 
                $message->setContent($content);
                $message->setDate(date_create($date));
                $this->em->persist($message);
                $this->em->flush();
                $update = new Update(
                    'https://example.com/books/' . trim($game->getId()),
                    json_encode(['status' => 'soy un frances gordo estupido'])
                );
        
                $hub->publish($update);
            }
        }


        return $this->redirectToRoute('chat',['id' => $game->getId()]);
    }
}

