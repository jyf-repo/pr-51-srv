<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    public function __construct(private MailerInterface $mailer)
    {

    }
    public function send_email($emailContact, $emailSubject, $emailContent)
    {
        $email = (new TemplatedEmail())
            ->from($emailContact)
            ->to('contact@medicontis.com')
            ->subject($emailSubject)

            ->htmlTemplate('contact/contact_email.html.twig')

            ->context([
                'contactEmail' => $emailContact,
                'contentMail' => $emailContent
            ]);
        try{
                $this->mailer->send($email);
                return true;
        } catch (TransportExceptionInterface $transportException){
            return $transportException->getMessage();
        }
    }
}
