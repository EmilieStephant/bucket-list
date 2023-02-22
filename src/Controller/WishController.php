<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
    #[Route('/list', name: 'wish_list')]
    public function list(): Response
    {
        //TODO récupérer la liste de tous les souhait
        return $this->render('wish/list.html.twig');
    }

    #[Route('/detail/{id}', name: 'wish_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id): Response
    {
        //TODO récupérer un souhait en particulier avec son id
        //renvoi de l'id dans la requête ... peut être qu'on n'en fera rien
        return $this->render('wish/detail.html.twig', ["idWish" => $id]);
    }
}
