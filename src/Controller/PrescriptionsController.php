<?php

namespace App\Controller;

use App\Repository\PrescriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PrescriptionsController extends AbstractController
{
    #[Route('/api/prescriptions/{userId}', name: "api_prescriptions")]
    public function get_image_products($userId, PrescriptionRepository $prescriptionRepository, Request $request)
    {
        $prescription = $prescriptionRepository->findOneBy(['id'=>$userId]);
        $file = $prescription->getPrescriptionFileName();
        $path_file = '/uploads/prescriptions/'.$file;
        $urlFile = $request->getUriForPath($path_file);
        return $this->json($urlFile);
    }
}
