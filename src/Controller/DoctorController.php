<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Form\DoctorFormType;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoctorController extends AbstractController
{
    #[Route('/doctor', name: 'app_doctor')]
    public function index(DoctorRepository $doctorRepository): Response
    {
        $doctors = $doctorRepository->findAll();

        return $this->render('doctor/index.html.twig', [
            'doctors' => $doctors,
        ]);
    }
    #[Route('/doctor/new', name: 'app_doctor_new')]
    #[Route('/doctor/{id}/edit', name: 'app_doctor_edit')]
public function editDoctor(Doctor $doctor=null, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(DoctorFormType::class, $doctor);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $doctor = $form->getData();

            $entityManager->persist($doctor);
            $entityManager->flush();

            return $this->redirectToRoute('app_doctor');
        }
        return $this->render('doctor/new.html.twig', [
            'form_new_doctor' => $form
        ]);
    }
}
