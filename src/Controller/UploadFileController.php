<?php

namespace App\Controller;

use App\api\FileUploadApiModel;
use App\Entity\Prescription;
use App\Form\PrescriptionFormType;
use App\Repository\PrescriptionRepository;
use App\Repository\UserRepository;
use App\Service\EmailService;
use App\Service\FileUploader;
use App\Service\FileUploaderApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\File\File as FileObject;

class UploadFileController extends AbstractController
{
    #[Route('/upload/show/prescriptions', name: 'app_show_prescriptions')]
    public function showPrescriptions(PrescriptionRepository $prescriptionRepository, UserRepository $userRepository)
    {
        $prescriptions = $prescriptionRepository->findAll();
        $users = $userRepository->findAll();
        return $this->render('/upload_file/all_prescriptions.html.twig', [
            'prescriptions' => $prescriptions,
            'users' => $users
        ]);
    }


    #[Route('/upload/filePrescription', name: 'app_upload_filePrescription')]
    #[Route('/upload/{id}/edit/filePrescription', name: 'app_upload_edit_filePrescription')]
    public function index(Prescription $prescription=null, Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(PrescriptionFormType::class, $prescription);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $prescriptionFile = $form->get('prescriptionFileName')->getData();
            //dd($prescriptionFile);
            $prescriptionFileName = $fileUploader->upload($prescriptionFile);
            //dd($prescriptionFileName);
            $prescription->setPrescriptionFileName($prescriptionFileName);
            $prescriptionPath = '/uploads/prescriptions/'.$prescriptionFileName;
            $urlPrescription = $request->getUriForPath($prescriptionPath);
            $prescription->setPrescriptionPath($urlPrescription);

            $entityManager->persist($prescription);
            $entityManager->flush();

            return $this->redirectToRoute('app_show_prescriptions');
        }

        return $this->render('upload_file/index.html.twig', [
            'prescriptionForm' => $form->createView(),
        ]);
    }


    #[Route('/show/filePrescription/{userId}', name: 'app_show_filePrescription')]
    public function showUserPrescriptions($userId, PrescriptionRepository $prescriptionRepository): Response
    {
        $prescriptions = $prescriptionRepository->findBy(['userId'=>$userId]);

        return $this->render('upload_file/show.html.twig', [
            'prescriptions' => $prescriptions,
        ]);
    }

    #[Route('/upload/api/prescription/{userId}', name: 'upload_api_prescription')]
    public function client_prescription_upload($userId, SerializerInterface $serializer, UserRepository $userRepository, ValidatorInterface $validator, Request $request, FileUploaderApi $fileUploader, EntityManagerInterface $entityManager, EmailService $emailService): Response
    {
        $prescription = new Prescription();
        $user_identify = $userRepository->findOneBy(['id'=>$userId]);
        $prescription->setUserId($user_identify);

        // client json file must contain terms filename and data
        if ($request->headers->get('Content-Type') === 'application/json') {
            /** @var FileUploadApiModel $uploadApiModel */
            $uploadApiModel = $serializer->deserialize(
                $request->getContent(),
                FileUploadApiModel::class,
                'json'
            );
            $violations = $validator->validate($uploadApiModel);
            if ($violations->count() > 0) {
                return $this->json($violations, 400);
                //return $this->json($uploadApiModel);
            }
                //return $this->json($uploadApiModel);
            $tmpPath = sys_get_temp_dir().'/pg_upload'.uniqid();
             //return new JsonResponse($tmpPath);
            file_put_contents($tmpPath, $uploadApiModel->getDecodedData());
            //return new JsonResponse($uploadApiModel->getDecodedData());
            $uploadedFile = new FileObject($tmpPath);
            $originalFilename = $uploadApiModel->filename;
            //return new JsonResponse($uploadedFile);

        } else {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $request->files->get('prescriptionFileName');

        }
        $violations = $validator->validate(
            $uploadedFile,
            [
                new NotBlank([
                    'message' => 'Merci de sélectionner un fichier à transmettre'
                ]),
                new File([
                    'maxSize' => '1M',
                    'mimeTypes' => [
                        'image/*',
                        'application/pdf',
                        'application/x-pdf',
                        //'application/msword',
                        //'application/vnd.ms-excel',
                        //'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        //'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        //'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        //'text/plain'
                    ]
                ])
            ]
        );
        if ($violations->count() > 0) {
            return $this->json($violations, 400);
            //return $this->json($request->headers->get('Content-Type'));
        }
        $prescriptionFileName = $fileUploader->uploadFile($uploadedFile);

        $prescription->setPrescriptionFileName($prescriptionFileName);
        $prescriptionPath = '/uploads/prescriptions/'.$prescriptionFileName;
        $urlPrescription = $request->getUriForPath($prescriptionPath);
        $prescription->setPrescriptionPath($urlPrescription);

        $entityManager->persist($prescription);
        $entityManager->flush();

        $emailService->send_email($user_identify->getEmail(), 'Nouvelle ordonnance', 'Une ordonnance a été deposée dans l\'espace client sur https://pr-51-srv.medicontis.com');
        return new JsonResponse('ordonnance envoyée!!');
    }

    #[Route('/api/get/prescriptions/{userId}', name: 'api_get_prescription_user')]
    public function apiGetPrescriptions($userId, PrescriptionRepository $prescriptionRepository, SerializerInterface $serializer)
    {
        $prescriptions = $prescriptionRepository->findBy(['userId' => $userId]);
        //dd($prescriptions);
        $prescriptionsJson = $serializer->serialize($prescriptions, 'json', ['groups'=>"USER_PRESCRIPTION"]);
        //dd($prescriptionsJson);
        return $this->json($prescriptionsJson);
    }

    #[Route('/prescription/delete/{idPrescription}', name:'app_delete_prescription')]
    public function delete_prescription($idPrescription, PrescriptionRepository $prescriptionRepository, EntityManagerInterface $entityManager)
    {
        // remove prescription from the folder "/uploads/prescriptions/":
        $prescription = $prescriptionRepository->findOneBy(['id' => $idPrescription]); // get file
        //dd($prescription);
        $filename = $prescription->getPrescriptionFileName(); //get file name
        //dd($filename);
        $fileSystem = new Filesystem();// filesystem instance
        $directory = $this->getParameter('prescriptions_directory');//path to prescriptions
        //dd($directory.'/'.$filename);
        $fileSystem->remove($directory.'/'.$filename); //removing prescription

        //remove from the data base
        $entityManager->remove($prescription);
        $entityManager->flush();

        return $this->redirectToRoute('app_show_prescriptions');
    }
}
