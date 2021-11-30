<?php

namespace App\Form\Account;

use App\Entity\User;
use App\Form\AvatarType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
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
                'label_attr' => [
                    'class' => 'form-label mt-3',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'empty_data' => '',
                'constraints' => [
                    new NotBlank(),
                    new Email([], 'Cet email n\'est pas valide.'),
                ],
            ])
            ->add('avatar', AvatarType::class, [
                'label' => ' ',
                'required' => false,
                'mapped' => true,
            ])
            ->add('requests', CheckboxType::class, [
                'label' => "Devenir contributeur ?",
                'label_attr' => [
                    'class' => 'form-label mt-3',
                ],
                'required' => false,
                'mapped' => false,
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
