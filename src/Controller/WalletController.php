<?php

namespace App\Controller;

use App\Entity\Wallet;
use App\Form\WalletType;
use App\Repository\WalletRepository;
use App\Service\CoinrankingApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Contrôleur gérant les opérations liées aux portefeuilles (Wallet) des utilisateurs.
 */
#[Route('/wallet')]
class WalletController extends AbstractController
{
    /**
     * Affiche la liste des portefeuilles.
     *
     * @param WalletRepository $walletRepository Le repository des portefeuilles.
     * @return Response La réponse HTTP contenant la liste des portefeuilles.
     */
    #[Route('/', name: 'app_wallet_index', methods: ['GET'])]
    public function index(WalletRepository $walletRepository): Response
    {
        return $this->render('wallet/index.html.twig', [
            'wallets' => $walletRepository->findAll(),
        ]);
    }

    /**
     * Permet de créer un nouveau portefeuille.
     *
     * @param Request $request La requête HTTP.
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités.
     * @return Response La réponse HTTP après création du portefeuille.
     * @throws AccessDeniedHttpException Exception si l'utilisateur n'a pas le rôle d'administrateur.
     */
    #[Route('/new', name: 'app_wallet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException('Seuls les administrateurs sont autorisés à créer de nouveaux portefeuilles.');
        }

        $wallet = new Wallet();
        $form = $this->createForm(WalletType::class, $wallet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($wallet);
            $entityManager->flush();

            return $this->redirectToRoute('app_wallet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('wallet/new.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
        ]);
    }

    /**
     * Affiche les détails d'un portefeuille et les données sur les cryptomonnaies.
     *
     * @param Wallet $wallet Le portefeuille à afficher.
     * @param CoinrankingApiService $coinrankingApiService Le service d'API Coinranking.
     * @return Response La réponse HTTP contenant les détails du portefeuille et les données sur les cryptomonnaies.
     */
    #[Route('/{id}', name: 'app_wallet_show', methods: ['GET'])]
    public function show(Wallet $wallet, CoinrankingApiService $coinrankingApiService): Response
    {
        $cryptoData = $coinrankingApiService->getCryptoData();

        return $this->render('wallet/show.html.twig', [
            'wallet' => $wallet,
            'cryptoData' => $cryptoData,
        ]);
    }

    /**
     * Permet de modifier un portefeuille existant.
     *
     * @param Request $request La requête HTTP.
     * @param Wallet $wallet Le portefeuille à modifier.
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités.
     * @return Response La réponse HTTP après modification du portefeuille.
     * @throws AccessDeniedHttpException Exception si l'utilisateur n'a pas le rôle d'administrateur.
     */
    #[Route('/{id}/edit', name: 'app_wallet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Wallet $wallet, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException('Seuls les administrateurs sont autorisés à modifier les portefeuilles.');
        }

        $form = $this->createForm(WalletType::class, $wallet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_wallet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('wallet/edit.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
        ]);
    }

    /**
     * Supprime un portefeuille.
     *
     * @param Request $request La requête HTTP.
     * @param Wallet $wallet Le portefeuille à supprimer.
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités.
     * @return Response La réponse HTTP après suppression du portefeuille.
     */
    #[Route('/{id}', name: 'app_wallet_delete', methods: ['POST'])]
    public function delete(Request $request, Wallet $wallet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$wallet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($wallet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_wallet_index', [], Response::HTTP_SEE_OTHER);
    }
}
