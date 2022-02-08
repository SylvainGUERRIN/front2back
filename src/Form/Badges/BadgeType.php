<?php

namespace App\Form\Badges;

use App\Entity\Badge;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->add('actionName', TextType::class, [
                'label' => 'Le nom du badge',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Mettez le nom du badge',
                    'class' => 'form-control',
                ],
            ])
            ->add('actionDelimiter', TextType::class, [
                'label' => 'Le nom du badge',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Mettez le nom du badge',
                    'class' => 'form-control',
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
