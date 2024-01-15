<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Service\EmailService;
use phpDocumentor\Reflection\Types\Context;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    #[IsGranted('ROLE_ADMIN', message: 'Accés interdit')]
    public function index( Request $request, MailerInterface $mailer, EmailService $emailService ): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $contentForm = $form->getData();
            $emailService->send_email($contentForm['email_contact'], $contentForm['email_subject'], $contentForm['email_content']);
            // Action: send an email:
//            $email = (new TemplatedEmail())
//                ->from($contentForm['email_contact'])
//                ->to('jeanyvesfournet@icloud.com')
//                ->subject($contentForm['email_subject'])
//
//                ->htmlTemplate('contact/contact_email.html.twig')
//
//                ->context([
//                    'contactEmail' => $contentForm['email_contact'],
//                    'contentMail' => $contentForm['email_content']
//                ]);
//
//            $mailer->send($email);

            return $this->redirectToRoute('app_inside');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/api/contact', name: 'api_contact', methods: 'POST')]
    public function contact_client(Request $request, EmailService $emailService)
    {
        $apikey = $request->headers->get('apiKeyAuth');
        $emailString = $request->getContent();
        $emailJson = json_decode($emailString);
        // dd($emailJson->email_contact);
        $emailService->send_email($emailJson->email_contact, $emailJson->email_subject, $emailJson->email_content);
        return new JsonResponse('email envoyé! '. $apikey);
    }
}
