<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Contrôleur gérant les fonctionnalités de sécurité, notamment la connexion et la déconnexion.
 */
class SecurityController extends AbstractController
{
    /**
     * Gère le processus de connexion des utilisateurs.
     *
     * @param AuthenticationUtils $authenticationUtils Utilitaire d'authentification pour récupérer les erreurs de connexion.
     * @return Response La réponse HTTP pour la page de connexion.
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Vérifie si l'utilisateur est déjà connecté, redirige le cas échéant
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // Récupère l'éventuelle erreur de connexion
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupère le dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // Affiche la page de connexion avec les informations nécessaires
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Gère le processus de déconnexion des utilisateurs.
     *
     * @throws \LogicException Cette méthode peut être vide, car elle sera interceptée par la clé de déconnexion de votre pare-feu.
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
