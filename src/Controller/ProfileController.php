<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    #[Route('/client/profile', name: 'profile')]
    public function afficher(): Response
    {   
        //dd($this->getUser()->getLocations());
        return $this->render('profile/profile.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    #[Route('/client/profile/nouveau', name: 'profile_new')]
    public function creation(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $profile = new Profile();
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            
            $file = $profile->getImageProfile();
            $extension = explode(".", $file->getClientOriginalName());
            $filename = md5(uniqid()) . '.' . end($extension);
            $file->move($this->getParameter('upload_directory'), $filename);
            $profile->setImageProfile($filename);


            $user->setProfile($profile);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/creationProfile.html.twig', [
          //  'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/client/profile/{id}', name: 'profile_edit')]
    public function modification(Profile $profile, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //upload image
            $file = $profile->getImageProfile();
            $extension = explode(".", $file->getClientOriginalName());
            $filename = md5(uniqid()) . '.' . end($extension);
            $file->move($this->getParameter('upload_directory'), $filename);
            $profile->setImageProfile($filename);

            $user->setProfile($profile);
           // $em->persist($user); pas besoin de persist quand modif
            $em->flush();
            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/creationProfile.html.twig', [
            //'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    


    // #[Route('/admin/creation', name: 'creationVoiture')]
    // #[Route('/admin/{id}', name: 'modifVoiture', methods: ['GET', 'POST'])]
    // public function modification(Voiture $voiture = null, Request $request, EntityManagerInterface $em)
    // {
    //     if (!$voiture) {
    //         $voiture = new Voiture();
    //     }
    //     $form = $this->createForm(VoitureType::class, $voiture);
    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $modif = $voiture->getId() !== null;
    //         $em->persist($voiture);
    //         $em->flush();
    //         $this->addFlash('success', ($modif) ? "La modification a été effectuée" : "l'ajout a été effectué");
    //         return $this->redirectToRoute('admin');
    //     }
    //     return $this->render('admin/modification.html.twig', [
    //         'voiture' => $voiture,
    //         'form' => $form->createView(),
    //     ]);
    // }
}
