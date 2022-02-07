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
use App\Entity\Tag;

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
//            ->add('refDescription', TextareaType::class, [
//                'label' => 'Le texte pour référencer la veille',
//                'attr' => [
//                    'placeholder' => 'Mettez le texte pour le référencement',
//                    'class' => 'form-control',
//                ],
//            ])
//            ->add('excerpt', TextType::class, [
//                'label' => "Contenu pour l'extrait",
//                'attr' => [
//                    'placeholder' => "Mettez l'extrait",
//                    'class' => 'form-control', ],
//            ])
//            ->add('content', TextareaType::class, [
//                'label' => 'Contenu de la veille',
//                'attr' => [
//                    'placeholder' => 'Mettez le texte de la veille',
//                    'class' => 'form-control', ],
//            ])
            //->add('users') only if multiple users, just for one select for admin ?
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Badge::class,
        ]);
    }
}
