<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class SecurityController extends AbstractController
{
    public function __construct(private Environment $twig) {}

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        return new Response($this->twig->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' =>  $error
            ])
        );
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('login');
    }
}
