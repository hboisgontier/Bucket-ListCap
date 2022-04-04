<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConnectionController extends AbstractController
{
    #[Route('/connection', name: 'app_connection')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $errors = $authenticationUtils->getLastAuthenticationError();
        $lastLogin = $authenticationUtils->getLastUsername();
        return $this->render('connection/index.html.twig',
            compact('errors', 'lastLogin'));
    }

    #[Route('/deconnection', name: 'app_deconnection')]
    public function deconnection() {
        throw new \Exception('PB de configuration !!!');
    }
}
