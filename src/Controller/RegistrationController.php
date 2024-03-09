<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wallet;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur gérant le processus d'inscription des utilisateurs.
 */
class RegistrationController extends AbstractController
{
    /**
     * Gère le processus d'inscription des utilisateurs.
     *
     * @param Request $request La requête HTTP.
     * @param UserPasswordHasherInterface $userPasswordHasher Interface pour hasher les mots de passe des utilisateurs.
     * @param EntityManagerInterface $entityManager Interface pour interagir avec la base de données.
     * @return Response La réponse HTTP pour la page d'inscription.
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // Crée une nouvelle instance de l'entité User
        $user = new User();
        // Crée un formulaire d'inscription en utilisant le RegistrationFormType
        $form = $this->createForm(RegistrationFormType::class, $user);
        // Traite la requête pour valider le formulaire
        $form->handleRequest($request);

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode le mot de passe en utilisant l'interface UserPasswordHasher
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Crée une nouvelle instance de l'entité Wallet
            $wallet = new Wallet();
            // Associe le portefeuille à l'utilisateur
            $user->setHasWallet($wallet);
            // Persiste l'utilisateur et le portefeuille dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirige l'utilisateur vers la page d'accueil après l'inscription
            return $this->redirectToRoute('app_home');
        }

        // Affiche la page d'inscription avec le formulaire
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
