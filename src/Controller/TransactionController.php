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
use Symfony\Component\VarDumper\VarDumper;


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
public function buy(Request $request, EntityManagerInterface $entityManager): Response
{
    $transaction = new Transaction();
    $transaction->setType('Buy'); // Définir automatiquement le type comme "Buy"
    $form = $this->createForm(TransactionType::class, $transaction);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user = $transaction->getUser();
        $wallet = $user->getHasWallet();
        $quantity = $transaction->getQuantity();
        $crypto = $transaction->getCryptocurrency();
        $transactionAmount = $transaction->getAmount();
        $usableBalance = $wallet->getUsableBalance();

        // Vérifier si le montant de la transaction est inférieur ou égal au solde utilisable
        if ($transactionAmount <= $usableBalance) {

        // Mettre à jour le champ correspondant dans le wallet
        $fieldName = strtolower(str_replace(' ', '', $crypto->getName()));
        $currentAmount = $wallet->{"get$fieldName"}(); // Récupérer la valeur actuelle
        $newAmount = $currentAmount + $quantity; // Ajouter la quantité de la transaction
        $wallet->{"set$fieldName"}($newAmount); // Mettre à jour le champ dans le wallet

        // Mettre à jour le solde utilisable
        $usableBalance = $wallet->getUsableBalance();
        $newUsableBalance = $usableBalance - $transactionAmount;
        $wallet->setUsableBalance($newUsableBalance);

        // Mettre à jour le solde de crypto
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
        $crypto = $transaction->getCryptocurrency();
        $transactionAmount = $transaction->getAmount();
        $cryptoBalance = $wallet->getCryptoBalance();
        $currentPrice = $crypto->getPrice(); // Supposons que vous avez une méthode getPrice() dans votre entité Crypto pour obtenir le prix actuel
        $fieldName = strtolower(str_replace(' ', '', $crypto->getName())); // Correction de la variable $fieldName
        $quantity = $wallet->{"get$fieldName"}(); // Correction de la récupération de la quantité de crypto-monnaie dans le portefeuille

        if ($transactionAmount <= $quantity * $currentPrice) { // Correction de la variable $currentPrice
            // Mettre à jour le champ correspondant dans le wallet
            $currentAmount = $wallet->{"get$fieldName"}(); // Récupérer la valeur actuelle
            $newAmount = $currentAmount - $transactionAmount / $currentPrice; // Soustraire la quantité de la transaction (en termes de crypto-monnaie)
            $wallet->{"set$fieldName"}($newAmount); // Mettre à jour le champ dans le wallet

            // Mettre à jour le solde utilisable
            $usableBalance = $wallet->getUsableBalance();
            $newUsableBalance = $usableBalance + $transactionAmount;
            $wallet->setUsableBalance($newUsableBalance);

            // Mettre à jour le solde de crypto
            $cryptoBalance = $wallet->getCryptoBalance();
            $newCryptoBalance = $cryptoBalance - $transactionAmount;
            $wallet->setCryptoBalance($newCryptoBalance);

            // Persistez les modifications apportées au portefeuille
            $entityManager->persist($wallet);

            // Enregistrez la transaction dans la base de données
            $entityManager->persist($transaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        } else {
            // Gérer le cas où le montant de la transaction dépasse la valeur totale de la crypto-monnaie détenue dans le portefeuille
            $this->addFlash('error', 'Le montant de la transaction dépasse la valeur totale de la crypto-monnaie détenue dans votre portefeuille.');
        }
    } else {
        // Gérer le cas où la quantité à vendre est supérieure à la quantité de crypto-monnaie détenue dans le portefeuille
        $this->addFlash('error', 'Vous ne pouvez pas vendre plus de crypto-monnaie que ce que vous avez dans votre portefeuille.');
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