<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(name: 'app_admin_')]
class AdminSecurityController extends AbstractController
{
    #[Route(path: '/admin-login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@EasyAdmin/page/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
            'translation_domain' => 'admin',
            'favicon_path' => '/favicon-admin.svg',
            'page_title' => 'Easy-Quiz',
            'csrf_token_intention' => 'authenticate',
            'target_path' => $this->generateUrl('app_admin_dashboard'),
            'username_label' => 'Your Email',
            'password_label' => 'Your password',
            'sign_in_label' => 'Log in',
            'forgot_password_enabled' => false,
            'remember_me_enabled' => true,
            'remember_me_checked' => false,
            'remember_me_label' => 'Remember me',
            'username_parameter' => 'email',
            'password_parameter' => 'password',
        ]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {

    }
}
