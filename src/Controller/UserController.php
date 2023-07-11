<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/registration', name: 'userRegistration')]
    public function userRegistration(HttpFoundationRequest $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user->setRoles(['ROLE_USER']);
            $plaintextPassword = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);
            $this->em->persist($user);
            $this->em->flush();
            return $this->redirectToRoute('login');
        }

        return $this->render('user/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/home/profile', name: 'userProfile')]
    public function userProfile(UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->getUser()->setEmail($_POST['_email']);
            $hashedPassword = $passwordHasher->hashPassword(
                $this->getUser(),
                $_POST['_password']
            );
            $this->getUser()->setPassword($hashedPassword);
            $this->em->flush();
        }
        return $this->render('user/profile.html.twig', [ 'user' => $this->getUser(),
        ]);
    }
}
