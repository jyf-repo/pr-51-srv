<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogErrorController extends AbstractController
{
    #[Route('/log/error', name: 'app_log_error')]
    public function index(LoggerInterface $logger): Response
    {
        $logger->debug('Message de debug pr-51-srv');
        $logger->info('Info Log pr-51-srv');
        $logger->emergency('Message d\'urgence');

        return $this->render('log_error/index.html.twig');
    }
}
