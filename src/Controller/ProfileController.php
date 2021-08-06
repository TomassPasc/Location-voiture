<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\Location;
use App\Form\ProfileType;
use App\Service\CalendarService;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    #[Route('/client/profile', name: 'profile')]
    public function afficher(LocationRepository $repoLocation, CalendarService $calendarService): Response
    {
        $locationsVoiture = $repoLocation->findBy(['user' => $this->getUser()]);
        //on parse pour envoyer les données au calendrier
        $data = $calendarService->parseData($locationsVoiture, true); //true pour afficher le nom du modèle dans le calendrier et non le mot réservé

        return $this->render('profile/profile.html.twig', [
            'user' => $this->getUser(),
            'data' => $data
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



}
