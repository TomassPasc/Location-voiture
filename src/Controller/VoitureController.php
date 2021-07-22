<?php

namespace App\Controller;

use App\Repository\VoitureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VoitureController extends AbstractController
{
    #[Route('/client/voitures', name: 'voitures')]
    public function index(VoitureRepository $repo, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $voitures = $paginatorInterface->paginate(
            $repo->findAllWithPagination(),
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );
        return $this->render('voiture/voitures.html.twig', [
            'voitures' => $voitures
        ]);
    }
}
