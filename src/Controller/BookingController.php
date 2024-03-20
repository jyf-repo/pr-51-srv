<?php

namespace App\Controller;

use App\Entity\Rentcategories;
use App\Form\RentcategoriesFormType;
use App\Repository\RentcategoriesRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    #[Route('/booking', name: 'app_booking')]
    public function index(RentcategoriesRepository $rentcategoriesRepository): Response
    {

        $rentcategories = $rentcategoriesRepository->findAll();

        return $this->render('booking/index.html.twig', [
                'rentcategories' => $rentcategories
        ]);
    }

    #[Route('/booking/new_rent_category', name:'app_booking_new_rent_category')]
    #[Route('/booking/edit_rent_category/{id}', name:'app_booking_edit_rent_category')]
    public function new_rent_category(Rentcategories $rentcategory=null, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(RentcategoriesFormType::class, $rentcategory);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $newRentCategory = $form->getData();
            $entityManager->persist($newRentCategory);
            $entityManager->flush();

            return $this->redirectToRoute('app_booking');
        }
        return $this->render('booking/new.html.twig',[
            'form_rent_category' => $form
        ]);
    }
}
