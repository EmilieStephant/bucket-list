<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\CategoryRepository;
use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(WishRepository $wishRepository, CategoryRepository $categoryRepository): Response
    {
        $wishes = $wishRepository->findBy(["isPublished" => true], ["dateCreated" => 'DESC']);
        $categories = $categoryRepository->findAll();
        //utiliser array_unique ?

        return $this->render('wish/list.html.twig', ["wishes" => $wishes, "categories" => $categories]);
    }

    #[Route('/{id}', name: 'detail', requirements: ['id' => '\d+'])]
    public function detail(Wish $id): Response
    {
        //Récupération du souhait avec l'id envoyé dans la requête
        //grâce au ParamConverter
        //renvoi du souhait en sortie
        //Ca évite d'écrire :
        // $wish = $wishRepository->find($id);
        return $this->render('wish/detail.html.twig', ["wish" => $id]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, WishRepository $wishRepository){
        //fonction qui doit renvoyer à la vue le formulaire
        //et récupérer les infos en retour

        $wish = new Wish();

        //Je set l'auteur de Wish pour que le champ
        if($this->getUser()) $wish->setAuthor($this->getUser()->getUserIdentifier());

        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()){

            $wish->setIsPublished(true);
            $wish->setDateCreated(new \DateTime());

            $wishRepository->save($wish, true);
            $this->addFlash("success", "Idea successfully added !");

            return $this->redirectToRoute('wish_detail', [
                'id' => $wish->getId()
            ]);
        }

        return $this->render('wish/new.html.twig', [
            "wishForm" => $wishForm->createView()
        ]);
    }

    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function update(Wish $id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);

        if (!$wish){
            throw $this->createNotFoundException("Oops ! Wish not found !");
        }

        $wishForm = $this->createForm(WishType::class, $wish);

        return $this->render('wish/update.html.twig', ["wish" => $wish, "wishForm" => $wishForm->createView()]);
    }


}
