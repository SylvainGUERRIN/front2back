<?php

namespace App\Form\Tags;

use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre du Tag',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Mettez le titre de la veille',
                    'class' => 'form-control',
                ],
            ])
            ->add('slug', TextType::class, [
                'label' => "L'url du Tag",
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => "Ce champ n'est pas obligatoire. L'url se met automatiquement sauf si vous voulez la personalisée",
                    'class' => 'form-control',
                ],
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Le texte de définition pour ce Tag',
                'attr' => [
                    'placeholder' => 'Mettez un texte de description',
                    'class' => 'form-control',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}
