<?php

namespace App\Form;

use App\Entity\Wallet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Classe représentant le formulaire associé à l'entité Wallet.
 */
class WalletType extends AbstractType
{
    /**
     * Construit le formulaire avec les champs nécessaires pour l'entité Wallet.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire.
     * @param array $options Les options du formulaire.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('totalBalance')  // Champ pour le solde total du portefeuille.
            ->add('cryptoBalance') // Champ pour le solde de cryptomonnaie du portefeuille.
            ->add('usableBalance') // Champ pour le solde utilisable du portefeuille.
        ;
    }

    /**
     * Configure les options par défaut du formulaire.
     *
     * @param OptionsResolver $resolver Le résolveur d'options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wallet::class, // L'entité associée au formulaire.
        ]);
    }
}
