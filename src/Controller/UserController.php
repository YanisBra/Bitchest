<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wallet;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Contrôleur gérant les opérations liées aux utilisateurs.
 */
#[Route('/user')]
class UserController extends AbstractController
{
    /**
     * Affiche la liste des utilisateurs.
     *
     * @param UserRepository $userRepository Le repository des utilisateurs.
     * @return Response La réponse HTTP contenant la liste des utilisateurs.
     */
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * Permet de créer un nouvel utilisateur.
     *
     * @param Request $request La requête HTTP.
     * @param UserPasswordHasherInterface $userPasswordHasher L'interface de hachage du mot de passe utilisateur.
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités.
     * @return Response La réponse HTTP après création de l'utilisateur.
     * @throws AccessDeniedException Exception si l'utilisateur n'a pas le rôle d'administrateur.
     */
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Seuls les administrateurs sont autorisés à créer de nouveaux utilisateurs.');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['is_new' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $wallet = new Wallet();
            $user->setHasWallet($wallet);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * Affiche les détails d'un utilisateur.
     *
     * @param User $user L'utilisateur à afficher.
     * @return Response La réponse HTTP contenant les détails de l'utilisateur.
     * @throws AccessDeniedException Exception si l'utilisateur n'est pas autorisé à accéder aux profils d'autres utilisateurs.
     */
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        $currentUser = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN') || $currentUser === $user) {
            return $this->render('user/show.html.twig', [
                'user' => $user,
            ]);
        }

        throw new AccessDeniedException('Vous n\'êtes pas autorisé à accéder aux profils des autres utilisateurs.');
    }

    /**
     * Permet de modifier un utilisateur existant.
     *
     * @param Request $request La requête HTTP.
     * @param UserPasswordHasherInterface $userPasswordHasher L'interface de hachage du mot de passe utilisateur.
     * @param User $user L'utilisateur à modifier.
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités.
     * @return Response La réponse HTTP après modification de l'utilisateur.
     * @throws AccessDeniedException Exception si l'utilisateur n'a pas le rôle d'administrateur et n'est pas l'utilisateur actuel.
     */
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserPasswordHasherInterface $userPasswordHasher, User $user, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();

        if (!$this->isGranted('ROLE_ADMIN') && $currentUser !== $user) {
            throw new AccessDeniedException('Vous n\'êtes pas autorisé à modifier les profils des autres utilisateurs.');
        }

        $form = $this->createForm(UserType::class, $user, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($currentUser === $user) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('newPassword')->getData()
                    )
                );
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_user_show', ['id' => $currentUser->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'button_label' => 'Mettre à jour',
        ]);
    }

    /**
     * Supprime un utilisateur.
     *
     * @param Request $request La requête HTTP.
     * @param User $user L'utilisateur à supprimer.
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités.
     * @return Response La réponse HTTP après suppression de l'utilisateur.
     */
    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
