<?php

namespace App\Form;

use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\Crypto;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface; 
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class TransactionType extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount')
            ->add('cryptocurrency', EntityType::class, [
                'class' => Crypto::class,
                'choice_label' => 'name',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                'data' => $this->tokenStorage->getToken()->getUser(),
                'disabled' => false,
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /** @var Transaction $transaction */
                $transaction = $event->getData();

                // Récupérer le montant et le prix de la crypto sélectionnée
                $amount = $transaction->getAmount();
                $cryptoPrice = $transaction->getCryptocurrency()->getPrice();

                // Calculer la quantité
                $quantity = $amount / $cryptoPrice;

                // Stocker la quantité calculée dans l'objet Transaction
                $transaction->setQuantity($quantity);
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}