<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/transaction')]
class TransactionController extends AbstractController
{
    #[Route('/', name: 'app_transaction_index', methods: ['GET'])]
    public function index(TransactionRepository $transactionRepository): Response
    {
        return $this->render('transaction/index.html.twig', [
            'transactions' => $transactionRepository->findAll(),
        ]);
    }

#[Route('/buy', name: 'app_transaction_buy', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $transaction = new Transaction();
    $transaction->setType('Buy'); // Définir automatiquement le type comme "Buy"
    $form = $this->createForm(TransactionType::class, $transaction);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer l'utilisateur sélectionné
        $user = $transaction->getUser();

        // Accéder au portefeuille associé à cet utilisateur
        $wallet = $user->getHasWallet();

        // Récupérer le montant de la transaction
        $transactionAmount = $transaction->getAmount();

        // Vérifier si le montant de la transaction est inférieur ou égal au solde utilisable
        $usableBalance = $wallet->getUsableBalance();
        if ($transactionAmount <= $usableBalance) {
            // Transférer le montant de l'achat du usableBalance vers le cryptoBalance
            $newUsableBalance = $usableBalance - $transactionAmount;
            $wallet->setUsableBalance($newUsableBalance);

            $cryptoBalance = $wallet->getCryptoBalance();
            $newCryptoBalance = $cryptoBalance + $transactionAmount;
            $wallet->setCryptoBalance($newCryptoBalance);

            // Persistez les modifications apportées au portefeuille
            $entityManager->persist($wallet);

            // Enregistrez la transaction dans la base de données
            $entityManager->persist($transaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        } else {
            // Gérer le cas où le montant de la transaction est supérieur au solde utilisable
            // Par exemple, afficher un message d'erreur à l'utilisateur
            $this->addFlash('error', 'Le montant de la transaction dépasse votre solde utilisable.');
        }
    }

    return $this->render('transaction/new.html.twig', [
        'transaction' => $transaction,
        'form' => $form,
    ]);
}

#[Route('/sell', name: 'app_transaction_sell', methods: ['GET', 'POST'])]
public function sell(Request $request, EntityManagerInterface $entityManager): Response
{
    $transaction = new Transaction();
    $transaction->setType('Sell'); // Définir automatiquement le type comme "Sell"
    $form = $this->createForm(TransactionType::class, $transaction);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user = $transaction->getUser();
        $wallet = $user->getHasWallet();
        $transactionAmount = $transaction->getAmount();

        $cryptoBalance = $wallet->getCryptoBalance();
        if ($transactionAmount <= $cryptoBalance) {
            $newCryptoBalance = $cryptoBalance - $transactionAmount;
            $wallet->setCryptoBalance($newCryptoBalance);

            $usableBalance = $wallet->getUsableBalance();
            $newUsableBalance = $usableBalance + $transactionAmount;
            $wallet->setUsableBalance($newUsableBalance);

            $entityManager->persist($wallet);
            $entityManager->persist($transaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        } else {
            $this->addFlash('error', 'Le montant de la transaction dépasse votre solde en crypto.');
        }
    }

    return $this->render('transaction/new.html.twig', [
        'transaction' => $transaction,
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_transaction_show', methods: ['GET'])]
    public function show(Transaction $transaction): Response
    {
        return $this->render('transaction/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transaction/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transaction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($transaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
    }
}
