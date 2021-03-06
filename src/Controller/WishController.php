<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    #[Route('/wish/{page}', name: 'app_wish_list', requirements: ['page'=>'\d+'])]
    public function list(WishRepository $repository, $page = 0): Response
    {
        $nbWishesByPage = 6;
        $offset = $page * $nbWishesByPage;
        $nbWishes = $repository->countWishes();
        $lastPage = intdiv($nbWishes, $nbWishesByPage);
        if($nbWishes % $nbWishesByPage === 0)
            $lastPage--;
        $wishes = $repository->findBy(['isPublished'=>true], ['dateCreated'=>'DESC'], $nbWishesByPage, $offset);
        return $this->render('wish/list.html.twig', compact('wishes', 'page', 'lastPage'));
    }
    #[Route('/wish/details/{id}', name: 'app_wish_details', requirements: ['id'=>'\d+'])]
    public function details($id, WishRepository $repository): Response
    {
        $wish = $repository->find($id);
        //$wish = $repository->findOneById($id);
        //$wish = $repository->findOneBy(['id'=>$id]);
        if(!$wish)
            throw $this->createNotFoundException();
        return $this->render('wish/details.html.twig', [
            'wish' => $wish,
        ]);
    }

    #[Route('/wish/add', name: 'app_wish_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response {
        if(!$this->isGranted('ROLE_USER')) {
            $this->addFlash('info', 'Registration required for add a wish.');
            return $this->redirectToRoute('app_connection');
        }
        $wish = new Wish();
        $wish->setAuthor($this->getUser()->getUserIdentifier());
        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $wish->setIsPublished(true);
            $wish->setDateCreated(new \DateTime());
            $entityManager->persist($wish);
            $entityManager->flush();
            $this->addFlash('ok', 'Idea successfully added!');
            return $this->redirectToRoute('app_wish_details', ['id'=>$wish->getId()]);
        }
        $formView = $form->createView();
        return $this->render('wish/add.html.twig', compact('formView'));
    }

    #[Route('/wish/DEBUG/jeuessai')]
    public function debugJeuDEssai(EntityManagerInterface $entityManager): Response {
        $wish = new Wish();
        $wish->setTitle('R??ussir une d??monstration symfony sans faire d\'erreur')
            ->setAuthor('Herv??')
            ->setDateCreated(new \DateTime())
            ->setIsPublish(true);
        $entityManager->persist($wish);
        $wish = new Wish();
        $wish->setTitle('Donner une conf??rence au Devoxx')
            ->setAuthor('Herv??')
            ->setDateCreated(new \DateTime())
            ->setIsPublish(true);
        $entityManager->persist($wish);
        $wish = new Wish();
        $wish->setTitle('Faire le tour du monde ?? la voile')
            ->setAuthor('Maxime')
            ->setDateCreated(new \DateTime())
            ->setIsPublish(true);
        $entityManager->persist($wish);
        $wish = new Wish();
        $wish->setTitle('Rencontrer le pr??sident de la r??publique')
            ->setAuthor('M??lanie')
            ->setDateCreated(new \DateTime())
            ->setIsPublish(true);
        $entityManager->persist($wish);
        $entityManager->flush();
        return $this->json('ok : Donn??es ajout??es');
    }
}
