<?php

namespace App\Controller;

use App\Entity\Pillbox;
use App\Form\PillboxFormType;
use App\Repository\PillboxRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\SerializerInterface;

class PillboxController extends AbstractController
{
    #[Route('/pillbox', name: 'app_pillbox')]
    public function index(PillboxRepository $pillboxRepository, UserRepository $userRepository): Response
    {
        $allPillbox = $pillboxRepository->findAll();
        //dd($userRepository->find(['id'=> 7]));
        $app_users = $userRepository->findByRoleUser('ROLE_CLIENT');
        //dd($app_users);
        return $this->render('pillbox/index.html.twig', [
            'pillboxes' => $allPillbox,
            'app_users' => $app_users
        ]);
    }
    #[Route('/api/pillbox/{userId}', name: 'api_pillbox')]
    public function allPillboxesUser($userId, PillboxRepository $pillboxRepository, UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        $allPillbox = $pillboxRepository->findBy(['userId'=> $userId]);
        //dd($allPillbox);
        //dd($serializer->serialize($allPillbox, 'json', ['groups'=>"toto l'asticot"]));
        $allPillboxJson = $serializer->serialize($allPillbox, 'json', ['groups'=>"toto l'asticot"]);
        return $this->json($allPillboxJson);
    }
    #[Route('/pillbox/new', name: 'app_new_pillbox')]
    public function new_pillbox(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pillbox = new Pillbox();
        $form = $this->createForm(PillboxFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $pillbox = $form->getData();
            $entityManager->persist($pillbox);
            $entityManager->flush();

            return $this->redirectToRoute('app_pillbox');
        }
        return $this->render('pillbox/new.html.twig', [
            'pillbox_form' => $form
        ]);
    }

    #[Route('/pillbox/{idUser}', name: 'app_user_pillbox')]
    public function user_pillbox(): Response
    {

        return $this->render('pillbox/user.html.twig', [

        ]);
    }
}
