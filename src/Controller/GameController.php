<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\UserGame;
use App\Form\GameType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class GameController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/game', name: 'game')]
    public function index(Request $request, SluggerInterface $slugger): Response
    {

        $game = new Game();
        $userGame = new UserGame();
        $userGame->setGame($game);
        $userGame->setUser($this->getUser());
        $userGame->setRol(true);
        $form = $this->createForm(GameType::class,$game);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            $file = $form->get('cover')->getData();

            if( $file ) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
            }

            try {
                $file->move(
                    $this->getParameter('files_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $game->setCover($newFilename);

            $this->em->persist($game);
            $this->em->persist($userGame);
            $game->addUser($userGame);
            $this->em->persist($game);
            $this->em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('game/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/game/{id}', name: 'gameDetails')]
    public function gameDetails(Game $game) {

        return $this->render('home/game-details.html.twig', ['game' => $game]);
    }
}
