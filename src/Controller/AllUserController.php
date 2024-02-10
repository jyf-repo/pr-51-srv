<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllUserController extends AbstractController
{
    #[Route('/all/user/{id}', name: 'app_all_user')]
    public function index($id, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['id'=>$id]);

        return $this->render('all_user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
