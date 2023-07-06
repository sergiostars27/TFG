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
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/home/game/{id}/roll', name: 'roll')]
    public function roll(HubInterface $hub, Game $game): Response
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $contentJson = json_decode(file_get_contents('php://input'),true);
            $dice = $contentJson["dice"];
            $num = $contentJson["num"];
            $randomNumber= [];
            for($i=0; $i < $num;$i++){
                array_push($randomNumber,mt_rand(1,$dice));
            }
                $update = new Update(
                    'https://example.com/dice/' . trim($game->getId()),
                    json_encode(['status' => $randomNumber])
                );
        
                $hub->publish($update);
            }

        return $this->redirectToRoute('chat',['id' => $game->getId()]);
    }

    #[Route('/home/game/{id}/push', name: 'push')]
    public function publish(HubInterface $hub, Game $game): Response
    {
        $message = new Message();
        $message->setGame($game);
        $message->setUser($this->getUser());
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $contentJson = json_decode(file_get_contents('php://input'),true);
            $content = $contentJson["_content"];
            if($content != ""){
                $date = date("m/d/Y H:i:s"); 
                $message->setContent($content);
                $message->setDate(date_create($date));
                $this->em->persist($message);
                $this->em->flush();
                $update = new Update(
                    'https://example.com/books/' . trim($game->getId()),
                    json_encode(['status' => $content, 'date' => date_create($date), 'user' => $this->getUser()->getUsername()])
                );
        
                $hub->publish($update);
            }
        }


        return $this->redirectToRoute('chat',['id' => $game->getId()]);
    }


}