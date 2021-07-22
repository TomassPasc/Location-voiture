<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Entity\RechercheVoiture;
use App\Form\RechercheVoitureType;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(VoitureRepository $repo, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $rechercheVoiture = new RechercheVoiture();
        $form = $this->createForm(RechercheVoitureType::class, $rechercheVoiture);
        $form->handleRequest($request);


        $voitures = $paginatorInterface->paginate(
            $repo->findAllWithPagination($rechercheVoiture),
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );
        return $this->render('voiture/voitures.html.twig', [
            'voitures' => $voitures,
            'form' => $form->createView(),
            'admin' => true
        ]);
    }

    #[Route('/admin/creation', name: 'creationVoiture')]
    #[Route('/admin/{id}', name: 'modifVoiture', methods: ['GET', 'POST'])]
    public function modification(Voiture $voiture = null, Request $request, EntityManagerInterface $em)    
    {
        if(!$voiture){
            $voiture = new Voiture();
        }
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $modif = $voiture->getId() !==null;
            $em->persist($voiture);
            $em->flush();
            $this->addFlash('success', ($modif) ? "La modification a été effectuée" : "l'ajout a été effectué");
            return $this->redirectToRoute('admin');
        }
        return $this->render('admin/modification.html.twig', [
            'voiture' => $voiture,
            'form' => $form->createView(),
        ]);

    }

    #[Route('/admin/supp/{id}', name: 'supVoiture')]
    public function supprimer(Voiture $voiture, Request $request, EntityManagerInterface $em)
    {
    
        if($this->isCsrfTokenValid("SUP" . $voiture->getId(), $request->get("_token"))) {
            $em->remove($voiture);
            $em->flush();
            $this->addFlash('success', "La suppression a été effectué");
            return $this->redirectToRoute("admin");
        }
    }
}
