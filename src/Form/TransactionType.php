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

/**
 * Classe représentant le formulaire associé à l'entité Transaction.
 */
class TransactionType extends AbstractType
{
    /**
     * @var TokenStorageInterface Le service de stockage des jetons d'authentification.
     */
    private $tokenStorage;

    /**
     * Constructeur de la classe TransactionType.
     *
     * @param TokenStorageInterface $tokenStorage Le service de stockage des jetons d'authentification.
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Construit le formulaire avec les champs nécessaires pour l'entité Transaction.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire.
     * @param array $options Les options du formulaire.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount') // Champ pour le montant de la transaction.
            ->add('cryptocurrency', EntityType::class, [
                'class' => Crypto::class,
                'choice_label' => 'name', // Utilise le nom de la cryptomonnaie comme label dans le formulaire.
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id', // Utilise l'identifiant de l'utilisateur comme label dans le formulaire.
                'data' => $this->tokenStorage->getToken()->getUser(), // Définit l'utilisateur actuel comme valeur par défaut.
                'disabled' => false, // Permet à l'utilisateur de sélectionner un autre utilisateur s'il le souhaite.
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /** @var Transaction $transaction */
                $transaction = $event->getData();

                $amount = $transaction->getAmount();
                $cryptoPrice = $transaction->getCryptocurrency()->getPrice();

                $quantity = $amount / $cryptoPrice;

                $transaction->setQuantity($quantity); // Calcule et défini la quantité de cryptomonnaie en fonction du montant et du prix.
            });
    }

    /**
     * Configure les options par défaut du formulaire.
     *
     * @param OptionsResolver $resolver Le résolveur d'options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class, // L'entité associée au formulaire.
        ]);
    }
}
