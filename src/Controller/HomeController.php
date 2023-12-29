<?php

namespace App\Controller;

use App\Entity\Websites;
use App\Form\WebsitesType;
use App\Repository\WebsitesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [

        ]);
    }

    #[Route('/inside', name: 'app_inside')]
    #[IsGranted('ROLE_ADMIN', message: 'Accés interdit')]
    public function inside(): Response
    {
        return $this->render(
            'home/inside.html.twig', [

        ]);
    }


}
