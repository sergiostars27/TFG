<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\UserGame;
use App\Entity\History;
use App\Entity\User;
use App\Form\GameType;
use App\Form\InsertImagesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\String\Slugger\SluggerInterface;

class GameController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/home/game', name: 'game')]
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

    #[Route('/home/game/{id}', name: 'gameDetails')]
    public function gameDetails(Game $game,Request $request,SluggerInterface $slugger) {

        $form = $this->createForm(InsertImagesType::class ,$game);
        $form->handleRequest($request);
        $rol = $this->em->getRepository(UserGame::class)->findOneBy(['user' => $this->getUser(),'game' => $game])->isRol();

        if( $form->isSubmitted() && $form->isValid()){
            $file = $form->get('imageList')->getData();

            if( $file ) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                $file->move(
                    $this->getParameter('files_directory'),
                    $newFilename);
            }

            $historial = new History("Nueva imagen subida a la zona común por " . $this->getUser()->getUsername() . ".",$game,$this->getUser());
            $game->addImageList($newFilename);

            $this->em->persist($historial);
            $this->em->flush();
        }

        return $this->render('home/game-details.html.twig', ['game' => $game,'form' => $form->createView(), 'rol' => $rol]);
    }

    #[Route('/home/game/delete/{id}', name: 'gameDelete')]
    public function gameDelete(Game $game) {

            $this->em->remove($game);
            $this->em->flush();

        return $this->redirectToRoute('home');
    }

    #[Route('/home/game/imagedelete/{game}/{imageName}', name: 'imageDelete')]
    public function imageDelete(Game $game,String $imageName) {
        $list = $game->getImageList();
        unset($list[array_search($imageName, $list)]);
        unlink("../public/uploads/files/". $imageName);
        $game->setImageList($list);
        $this->em->flush();

        return $this->redirectToRoute('gameDetails',['id'=> $game->getId()]);
    }
}
