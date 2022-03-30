<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    #[Route('/wish', name: 'app_wish_list')]
    public function list(WishRepository $repository): Response
    {
        $wishes = $repository->findBy(['isPublished'=>true], ['dateCreated'=>'DESC']);
        return $this->render('wish/list.html.twig', compact('wishes'));
    }
    #[Route('/wish/details/{id}', name: 'app_wish_details', requirements: ['id'=>'\d+'])]
    public function details($id, WishRepository $repository): Response
    {
        $wish = $repository->find($id);
        if(!$wish)
            throw $this->createNotFoundException();
        return $this->render('wish/details.html.twig', [
            'wish' => $wish,
        ]);
    }

    #[Route('/wish/DEBUG/jeuessai')]
    public function debugJeuDEssai(EntityManagerInterface $entityManager): Response {
        $wish = new Wish();
        $wish->setTitle('Réussir une démonstration symfony sans faire d\'erreur')
            ->setAuthor('Hervé')
            ->setDateCreated(new \DateTime())
            ->setIsPublish(true);
        $entityManager->persist($wish);
        $wish = new Wish();
        $wish->setTitle('Donner une conférence au Devoxx')
            ->setAuthor('Hervé')
            ->setDateCreated(new \DateTime())
            ->setIsPublish(true);
        $entityManager->persist($wish);
        $wish = new Wish();
        $wish->setTitle('Faire le tour du monde à la voile')
            ->setAuthor('Maxime')
            ->setDateCreated(new \DateTime())
            ->setIsPublish(true);
        $entityManager->persist($wish);
        $wish = new Wish();
        $wish->setTitle('Rencontrer le président de la république')
            ->setAuthor('Mélanie')
            ->setDateCreated(new \DateTime())
            ->setIsPublish(true);
        $entityManager->persist($wish);
        $entityManager->flush();
        return $this->json('ok : Données ajoutées');
    }
}
