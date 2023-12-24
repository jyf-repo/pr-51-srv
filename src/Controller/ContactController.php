<?php

namespace App\Controller;

use App\Form\ContactFormType;
use phpDocumentor\Reflection\Types\Context;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
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
            $email = (new TemplatedEmail())
                ->from($contentForm['email_contact'])
                ->to('jeanyvesfournet@icloud.com')
                ->subject($contentForm['email_subject'])

                ->htmlTemplate('contact/contact_email.html.twig')

                ->context([
                    'contactEmail' => $contentForm['email_contact'],
                    'contentMail' => $contentForm['email_content']
                ]);

            $mailer->send($email);

            return $this->redirectToRoute('app_inside');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form
        ]);
    }
}
