<?php

namespace App\Controller\Admin;

use App\Entity\Location;
use App\Form\AdminLocationType;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminLocationController extends AbstractController
{
    #[Route('/admin/location', name: 'admin_location')]
    public function index(LocationRepository $repo): Response
    {
        return $this->render('admin/admin_location/index.html.twig', [
            'locations' => $repo->findAll(),
        ]);
    }

    #[Route('/admin/location/{id}/edit', name: 'admin_location_edit')]
    public function edit(Location $location, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(AdminLocationType::class, $location);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($location);
            $em->flush();

            $this->addFlash(
                'success',
                "La location {$location->getId()} a bien été modifié !"
            );
            return $this->redirectToRoute('admin_location');
        }
        return $this->render('admin/admin_location/edit.html.twig', [
            'location' => $location,
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/location/{id}/supp', name: 'admin_location_supp')]
    public function supprimer(Location $location, Request $request, EntityManagerInterface $em)
    {
        if ($this->isCsrfTokenValid("SUP" . $location->getId(), $request->get("_token"))) {
            $em->remove($location);
            $em->flush();
            $this->addFlash('success', "La suppression a été effectué");
        }
        

        return $this->redirectToRoute("admin_location");
    }
}
