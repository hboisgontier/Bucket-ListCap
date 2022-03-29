<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    #[Route('/wish', name: 'app_wish_list')]
    public function list(): Response
    {
        return $this->render('wish/list.html.twig', [
            'controller_name' => 'WishController',
        ]);
    }
    #[Route('/wish/details/{id}', name: 'app_wish_deatils', requirements: ['id'=>'\d+'])]
    public function details($id): Response
    {
        return $this->render('wish/details.html.twig', [
            'id' => $id,
        ]);
    }
}
