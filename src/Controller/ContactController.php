<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index( Request $request, MailerInterface $mailer ): Response
    {

        $contentForm = [
            'email_contact' => '',
            'email_subject' => '',
            'email_content' => ''
        ];

        $form = $this->createForm(ContactFormType::class, $contentForm);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $contentForm = $form->getData();

            // Action: send an email:
            $email = (new Email())
                ->from($contentForm['email_contact'])
                ->to('jeanyvesfournet@icloud.com')
                ->subject($contentForm['email_subject'])
                ->text($contentForm['email_content'])
                ->html('<h2>this is a html content</h2>');

            $mailer->send($email);

            return $this->redirectToRoute('app_inside');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form
        ]);
    }
}
