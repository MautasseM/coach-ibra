<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
// ...

class SecurityController extends AbstractController
{
    // Page de connexion
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, on le redirige vers le profil
        if ($this->getUser()) {
            return $this->redirectToRoute('app_profile');
        }

        // Récupérer l'erreur de connexion si présente
        $error = $authenticationUtils->getLastAuthenticationError();
        // Dernier nom d'utilisateur saisi
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    // Page d'inscription
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
            'email' => $user->getEmail(),
        ]);
    }

    // Fonction pour connecter un utilisateur immédiatement après l'inscription
    private function doLogin(User $user)
    {
        // Appel de la fonction de connexion de Symfony
        $this->get('security.authentication.guard_handler')
            ->authenticateUserAndHandleSuccess($user, $request, $this->get('security.authentication.manager'));
    }

    // Page de profil (accessible uniquement aux utilisateurs connectés)
    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Afficher la page de profil de l'utilisateur connecté
        return $this->render('security/profile.html.twig');
    }

    // Déconnexion (cela est géré automatiquement par Symfony)
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode est interceptée par Symfony, il n'y a pas besoin d'y mettre de code.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
