<?php

namespace App\Form\Account;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'required' => false,
                'label' => 'Modifier votre prénom',
                'empty_data' => '',
//                'attr' => ['placeholder' => 'Veuillez mettre votre prénom'],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3]),
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'label' => 'Modifier votre email',
                'empty_data' => '',
//                'attr' => ['placeholder' => 'Veuillez mettre votre email'],
                'constraints' => [
                    new NotBlank(),
                    new Email([], 'Cet email n\'est pas valide.'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
