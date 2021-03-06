<?php

namespace App\Form\Account;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe actuel',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(),
                    new UserPassword(),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => false,
                'invalid_message' => 'Votre mot de passe doit être similaire à la confirmation.',
                'first_options' => [
                    'label' => 'Veuillez mettre votre nouveau mot de passe',
                    'label_attr' => [
                        'class' => 'form-label',
                    ],
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'label_attr' => [
                        'class' => 'form-label',
                    ],
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ],
                'constraints' => [
                    new Length(['min' => 8]),
                    new NotBlank(),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver); // TODO: Change the autogenerated stub
    }
}
