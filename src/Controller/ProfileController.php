<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
    
        // Vérifier que l'utilisateur est bien une instance de User
        if (!$user instanceof User) {
            throw new \LogicException('L\'utilisateur actuel n\'est pas valide.');
        }
    
        // Créer le formulaire avec l'utilisateur connecté
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);
    
        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); // Enregistrer les modifications en base de données
            $this->addFlash('success', 'Profil mis à jour avec succès.');
    
            return $this->redirectToRoute('app_profile');
        }
    
        // Afficher un message d'erreur si le formulaire est soumis mais invalide
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Une erreur est survenue. Veuillez vérifier les champs.');
        }
    
        // Rendu du template avec le formulaire
        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user, // Passer l'utilisateur à la vue
        ]);
    }
    
    
}
