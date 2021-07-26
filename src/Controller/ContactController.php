<?php

namespace App\Controller;

use App\Service\MailerService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function send(Request $request, MailerService $mailer): Response
    {

        $email = $request->request->get('email');
        $sujet = $request->request->get('sujet');
        $message = $request->request->get('message');
        $submit = $request->request->get('submit');
        if ($submit == 'envoyer') {
            $mailer->send(
                $email,
                "thoma1@free.fr",
                $sujet,
                "contact/contenu.html.twig",
                [   
                    "sujet" => $sujet,
                    "message" => $message
                    ]
            );
            $this->addFlash('success', "Votre mail a bien été envoyé");
            return $this->redirectToRoute('accueil');
        }
        return $this->render('contact/formulaireContact.html.twig');
    }
}
