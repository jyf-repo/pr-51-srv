<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use http\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelper;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/admin/users', name: 'app_admin_users')]
    #[IsGranted('ROLE_SUPER_ADMIN', message: 'Accés interdit')]
    public function showUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        //dd($users);
        return $this->render('security/users.html.twig', [
            'users' => $users
        ]);
    }
    #[Route(path: '/{id}', name: 'app_users_del')]
    public function delete_user( User $user, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($user);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('app_admin_users'));
    }

    #[Route('/api/forgot/pwd', name: 'api_forgot_pwd')]
    public function forgotClientPwd(Request $request, UserRepository $userRepository, EmailService $emailService){
        $email = json_decode($request->getContent());
        if($userRepository->findOneBy(['email' => $email])){
            $emailService->send_email($email, 'Réinitialisation du mot de passe', 'Veuillez suivre ce lien');
            return $this->json('Email valable');
        } else {
            return $this->json('Adresse email invalide');
        }

    }
}
