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

class UserType extends AbstractType
{
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

        $builder
            ->add('firstName')            
            ->add('lastName')
            ->add('email', EmailType::class);

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
                'label' => 'Roles',
                'required' => true,
            ]);
        }

        if (!$options['is_new']) {
            $builder->add('newPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
            'is_new' => false, 
        ]);
    }
}