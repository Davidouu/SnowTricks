<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('password', PasswordType::class, [
            'label' => 'Nouveau mot de passe',
            'constraints' => [
                new PasswordStrength([
                    'message' => 'Votre mot de passe est trop faible.',
                ]),
                new NotBlank([
                    'message' => 'Veuillez entrer un mot de passe',
                ]),
            ],
        ])
        ->add('confirm_password', PasswordType::class, [
            'label' => 'Confirmer le mot de passe',
            'constraints' => [
                new PasswordStrength([
                    'message' => 'Votre mot de passe est trop faible.',
                ]),
                new NotBlank([
                    'message' => 'Veuillez confirmer votre mot de passe',
                ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
