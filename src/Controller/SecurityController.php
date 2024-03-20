<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
    // Modify User
    #[Route('/user/new', name: 'app_new_user')]
    #[Route('/user/{id}/edit', name: 'app_edit_user')]
    public function edit_user(User $user=null, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('security/edit.html.twig', [
           'edit_form' => $form
        ]);
    }
    // Delete User
    #[Route(path: '/{id}', name: 'app_users_del')]
    public function delete_user( User $user, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($user);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('app_admin_users'));
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/reset_password/api/forgot/pwd', name: 'api_forgot_pwd', methods: 'POST')] //url start by reset_password and not api because of the apiKeyAuth
    public function forgotClientPwd(Request $request, UserRepository $userRepository, TokenGeneratorInterface $tokenGenerator, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $email = json_decode($request->getContent());
        $user = $userRepository->findOneBy(['email' => $email]);
        if($user){
            $resetToken = $tokenGenerator->generateToken();
            $user->setClientToken($resetToken);
            $entityManager->persist($user);
            $entityManager->flush();

            $url = $this->generateUrl('api_reset_pwd', ['token'=> $resetToken], UrlGeneratorInterface::ABSOLUTE_URL);

            $total_email = (new TemplatedEmail())
                ->from(new Address('administration@granpharma.com', 'GRANPHARMA'))
                ->to($email)
                ->subject('Réinitialisation de mot de passe')
                ->htmlTemplate('reset_password_client/email_client.html.twig')
                ->context([
                    'user' => $user,
                    'url' => $url
                ])
            ;
            $mailer->send($total_email);
            return $this->json('Email valable');
        } else {
            return $this->json('Adresse email invalide');
        }

    }

    #[Route('/reset_password/api/reset/{token}', name: 'api_reset_pwd')] // url start by reset_password and not api because of the apiKeyAuth
    public  function resetClientPwd($token , UserRepository $userRepository, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findOneBy(['clientToken' => $token]);
        if($user){
            $form = $this->createForm(ChangePasswordFormType::class);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                // clean token
                $user->setClientToken('');
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirect($this->getParameter("url_client_app"));
            }
            return $this->render('/reset_password_client/reset_pwd.html.twig', [
               'reset_pwd_form' => $form->createView()
            ]);
        }
        return $this->redirect($this->getParameter('url_client_app'));
    }
}
