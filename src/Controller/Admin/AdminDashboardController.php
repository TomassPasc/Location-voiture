<?php

namespace App\Controller\Admin;

use App\Repository\LocationRepository;
use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(EntityManagerInterface $manager,   StatsService $statsService)
    {

        $stats = $statsService->getStats();
        $locationsFuture = $statsService->getLocationFuturStats();
        $locationsPasse = $statsService->getLocationPasseStats();
        $locationActuelle = $statsService->getLocationPresenteStats();
    

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'locFuture' => $locationsFuture,
            'locAct' => $locationActuelle,
            'locPasse' =>$locationsPasse
        ]);
    }
}
