<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Classe représentant le formulaire associé à l'entité User.
 */
class UserType extends AbstractType
{
    /**
     * Génère un mot de passe aléatoire.
     *
     * @return string Le mot de passe généré.
     */
    private function genererMotDePasseAleatoire()
    {
        $caracteresPermis = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $motDePasse = '';

        for ($i = 0; $i < 8; $i++) {
            $motDePasse .= $caracteresPermis[rand(0, strlen($caracteresPermis) - 1)];
        }

        return $motDePasse;
    }

    /**
     * Construit le formulaire avec les champs nécessaires pour l'entité User.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire.
     * @param array $options Les options du formulaire.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $randomPassword = $this->genererMotDePasseAleatoire();

        $builder
            ->add('firstName') // Champ pour le prénom de l'utilisateur.
            ->add('lastName')  // Champ pour le nom de l'utilisateur.
            ->add('email', EmailType::class); // Champ pour l'adresse e-mail de l'utilisateur.

        if (!$options['is_edit']) {
            $builder->add('password', HiddenType::class, [
                'data' => $randomPassword,
                'attr' => [
                    'readonly' => false,
                    'value' => $randomPassword
                ],
                'required' => false,
            ]);
        }

        if (!$options['is_edit']) {
            $builder->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Rôles',
                'required' => true,
            ]);
        }

        if (!$options['is_new']) {
            $builder->add('newPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ]);
        }

        if (!$options['is_edit']) {
            $builder->add('motDePasseGenere', TextType::class, [
                'attr' => [
                    'readonly' => true,
                    'value' => $randomPassword,
                ],
                'mapped' => false,
                'required' => false,
            ]);
        }
    }

    /**
     * Configure les options par défaut du formulaire.
     *
     * @param OptionsResolver $resolver Le résolveur d'options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class, // L'entité associée au formulaire.
            'is_edit' => false, // Indique si le formulaire est utilisé pour la modification.
            'is_new' => false, // Indique si le formulaire est utilisé pour la création d'un nouvel utilisateur.
        ]);
    }
}
