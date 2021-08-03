<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    #[Route('/admin/user', name: 'admin_user')]
    public function index(UserRepository $repo): Response
    {

        return $this->render('admin/admin_user/index.html.twig', [
            'users' => $repo->findAll(),
        ]);
    }

    #[Route('/admin/user/{id}/edit', name: 'admin_user_edit')]
    public function edit(User $user, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                "L'utilisateur {$user->getId()} a bien été modifié !"
            );
            return $this->redirectToRoute('admin_user');
        }
        return $this->render('admin/admin_user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/user/{id}/supp', name: 'admin_user_supp')]
    public function supprimer(User $user, Request $request, EntityManagerInterface $em)
    {
        if (count($user->getLocations()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'utilisateur {$user->getPseudo()} car il possède déjà des réservations !"
            );
        } else {
            if ($this->isCsrfTokenValid("SUP" . $user->getId(), $request->get("_token"))) {
                $em->remove($user);
                $em->flush();
                $this->addFlash('success', "La suppression a été effectué");
            }
        }

        return $this->redirectToRoute("admin_user");
    }

}
