<?php

namespace App\Form\Badges;

use App\Entity\Badge;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class BadgeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Le nom du badge',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Mettez le nom du badge',
                    'class' => 'form-control',
                ],
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Téléchargez une image pour votre badge',
                'data_class' => null,
                'required' => false,
                'attr' => [
                    'placeholder' => 'choisir une image',
                    'class' => 'form-control',
                ],
                'mapped' => true,
                'constraints' => [
                    new Image([
                        'maxSize' => '3M',
                        'mimeTypes' => [
                            'image/jpg', 'image/jpeg', 'image/png', 'image/bmp',
                        ],
                    ]),
                ],
            ])
            ->add('actionName', ChoiceType::class, [
                'label' => 'L\'élément concerné par le badge',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Mettez le nom du badge',
                    'class' => 'form-control',
                ],
                'choices'  => [
                    "Sur les veilles" => 'post',
                    "Sur les commentaires" => 'comment',
                    "Sur les utilisateurs" => 'user',
                    "Sur les tags" => 'tag',
                ],
            ])
            ->add('actionDelimiter', ChoiceType::class, [
                'label' => 'L\'action a entreprendre',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Mettez le nom du badge',
                    'class' => 'form-control',
                ],
                'choices'  => [
                    "Sur la creation" => 'create',
                    "Sur le nombre" => 'number',
                ],
            ])
            ->add('actionQuantity', NumberType::class, [
                'label' => 'Mettre la quantité pour avoir le badge',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Mettez une quantité',
                    'class' => 'form-control',
                ],
            ])
            ->add('roleDelimiter', ChoiceType::class, [
                'label' => 'L\'utilisateur concerné.',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Choissisez le type d\'utilisateur concerné par ce badge.',
                    'class' => 'form-control',
                ],
                'choices'  => [
                    'Concernant les utilisateurs' => "user",
                    'Concernant les contributeurs' => "contributor",
                    'Concernant tous les utilisateurs' => "all",
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Badge::class,
        ]);
    }
}
