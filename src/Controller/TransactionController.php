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
    $transaction->setType('Buy'); 
    $form = $this->createForm(TransactionType::class, $transaction);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user = $transaction->getUser();
        $wallet = $user->getHasWallet();
        $quantity = $transaction->getQuantity();
        $crypto = $transaction->getCryptocurrency();
        $transactionAmount = $transaction->getAmount();
        $usableBalance = $wallet->getUsableBalance();

        if ($transactionAmount <= $usableBalance) {

        $fieldName = strtolower(str_replace(' ', '', $crypto->getName()));
        $currentAmount = $wallet->{"get$fieldName"}(); 
        $newAmount = $currentAmount + $quantity; 
        $wallet->{"set$fieldName"}($newAmount); 

        $usableBalance = $wallet->getUsableBalance();
        $newUsableBalance = $usableBalance - $transactionAmount;
        $wallet->setUsableBalance($newUsableBalance);

        $cryptoBalance = $wallet->getCryptoBalance();
        $newCryptoBalance = $cryptoBalance + $transactionAmount;
        $wallet->setCryptoBalance($newCryptoBalance);

        $entityManager->persist($wallet);
        $entityManager->persist($transaction);
        $entityManager->flush();

        return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
    } else {
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
    $transaction->setType('Sell'); 
    $form = $this->createForm(TransactionType::class, $transaction);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user = $transaction->getUser();
        $wallet = $user->getHasWallet();
        $crypto = $transaction->getCryptocurrency();
        $transactionAmount = $transaction->getAmount();
        $cryptoBalance = $wallet->getCryptoBalance();
        $currentPrice = $crypto->getPrice(); 
        $fieldName = strtolower(str_replace(' ', '', $crypto->getName())); 
        $quantity = $wallet->{"get$fieldName"}(); 

        if ($transactionAmount <= $quantity * $currentPrice) { 
            $currentAmount = $wallet->{"get$fieldName"}(); 
            $newAmount = $currentAmount - $transactionAmount / $currentPrice; 
            $wallet->{"set$fieldName"}($newAmount); 

            $usableBalance = $wallet->getUsableBalance();
            $newUsableBalance = $usableBalance + $transactionAmount;
            $wallet->setUsableBalance($newUsableBalance);

            $cryptoBalance = $wallet->getCryptoBalance();
            $newCryptoBalance = $cryptoBalance - $transactionAmount;
            $wallet->setCryptoBalance($newCryptoBalance);

            $entityManager->persist($wallet);

            $entityManager->persist($transaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        } else {
            $this->addFlash('error', 'Le montant de la transaction dépasse la valeur totale de la crypto-monnaie détenue dans votre portefeuille.');
        }
    } else {
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