<?php

// namespace App\Form;

// use App\Entity\Transaction;
// use App\Entity\User;
// use Symfony\Bridge\Doctrine\Form\Type\EntityType;
// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
// use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface; 

// class TransactionType extends AbstractType
// {
//     private $tokenStorage;

//     public function __construct(TokenStorageInterface $tokenStorage)
//     {
//         $this->tokenStorage = $tokenStorage;
//     }

//     public function buildForm(FormBuilderInterface $builder, array $options): void
//     {
//         $builder
//             ->add('quantity')
//             ->add('amount')
//             ->add('crypto')
//             ->add('user', EntityType::class, [
//                 'class' => User::class,
//                 'choice_label' => 'id',
//                 'data' => $this->tokenStorage->getToken()->getUser(), // Set current user as default
//                 'disabled' => false, // Disable the field to prevent user from changing it
//             ])
//         ;
//     }

// }

namespace App\Form;

use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\Crypto;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface; 

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
            ->add('quantity')
            ->add('amount')
            ->add('cryptocurrency', EntityType::class, [ // Utilisez EntityType au lieu de ChoiceType
            'class' => Crypto::class, // Utilisez votre entité Crypto
            'choice_label' => 'name', // Utilisez la propriété appropriée de votre entité Crypto à afficher dans la liste déroulante
        ])
            // ->add('crypto') // Utilisez EntityType au lieu de ChoiceType
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                'data' => $this->tokenStorage->getToken()->getUser(), // Set current user as default
                'disabled' => false, // Disable the field to prevent user from changing it
            ])
        ;
    }

}
