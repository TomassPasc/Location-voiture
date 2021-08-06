<?php

namespace App\Controller;

use App\Entity\Location;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RefundController extends AbstractController
{
    #[Route('/refund/{id}/remboursement', name: 'refund')]
    public function index(Location $location): Response
    {

        return $this->render('refund/index.html.twig', [
            'controller_name' => 'RefundController',
        ]);
    }
}
