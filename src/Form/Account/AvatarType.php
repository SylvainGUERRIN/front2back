<?php

namespace App\Form;

use App\Entity\Avatar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('imageFile', VichImageType::class, [
//                'label' => 'Sélectionnez une image pour mettre dans votre profil :',
//                'required' => false,
//                'data_class' => null,
//                'mapped' => true,
//                'attr' => ['placeholder' => 'Choisir son avatar'],
//                'constraints' => [
//                    new Image([
//                        'maxSize' => '6M',
//                        'mimeTypes' => [
//                            'image/jpg', 'image/jpeg', 'image/png', 'image/bmp'
//                        ]
//                    ])
//                ]
//            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Sélectionnez une image pour mettre dans votre profil :',
                'label_attr' => [
                    'class' => 'form-label mt-3',
                ],
                'required' => true,
                'mapped' => true,
                'attr' => [
                    'placeholder' => 'Choisir sa photo de profil',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new Image([
                        'maxSize' => '6M',
                        'mimeTypes' => [
                            'image/jpg', 'image/jpeg', 'image/png', 'image/bmp',
                        ],
                    ]),
                ],
                'empty_data' => '',
            ])
            ->add('validatedAt', HiddenType::class, [
                'required' => false,
                'mapped' => true,
                'data' => 0,
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avatar::class,
        ]);
    }
}
