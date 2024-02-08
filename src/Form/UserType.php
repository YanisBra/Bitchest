<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Ajout de TextType
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    // Génère un mot de passe aléatoire de 12 caractères
    private function genererMotDePasseAleatoire()
    {
        $caracteresPermis = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $motDePasse = '';

        for ($i = 0; $i < 8; $i++) {
            $motDePasse .= $caracteresPermis[rand(0, strlen($caracteresPermis) - 1)];
        }

        return $motDePasse;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $randomPassword = $this->genererMotDePasseAleatoire();

        echo '<pre>';
        var_dump($randomPassword);
        echo '</pre>';

        $builder
            ->add('firstName')            
            ->add('lastName')
            ->add('email', EmailType::class)
             ->add('password', PasswordType::class, [
                'data' => $randomPassword, // Remplit le champ avec le mot de passe aléatoire
                'attr' => [
                    'readonly' => true,
                    'value' => $randomPassword
                ],
                'required' => false,
            ])
            ->add('motDePasseGenere', TextType::class, [ // Champ de texte simple pour afficher le mot de passe
                'attr' => [
                    'readonly' => true,
                    'value' => $randomPassword,
                ],
                'mapped' => false, // Ne mappez pas cela à l'entité User
                'required' => false,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Roles',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}