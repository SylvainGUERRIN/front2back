<?php

namespace App\Form\Posts;

use App\Entity\Post;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use App\Entity\Tag;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la veille',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Mettez le titre de la veille',
                    'class' => 'form-control',
                ],
            ])
            ->add('slug', TextType::class, [
                'label' => "L'url de la veille",
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => "Ce champ n'est pas obligatoire. 
                    L'url se met automatiquement sauf si vous voulez la personalisée",
                    'class' => 'form-control',
                ],
                'required' => false,
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Téléchargez une image pour votre veille',
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
            ->add('refDescription', TextareaType::class, [
                'label' => 'Le texte pour référencer la veille',
                'attr' => [
                    'placeholder' => 'Mettez le texte pour le référencement',
                    'class' => 'form-control',
                ],
            ])
            ->add('excerpt', TextType::class, [
                'label' => "Contenu pour l'extrait",
                'attr' => [
                    'placeholder' => "Mettez l'extrait",
                    'class' => 'form-control', ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu de la veille',
                'attr' => [
                    'placeholder' => 'Mettez le texte de la veille',
                    'class' => 'form-control', ],
            ])
            ->add('tag', EntityType::class, [
                'class' => Tag::class,
//                'choices' => [
//                    'Choisissez un tag' => null,
//                ],
                'choice_label' => function ($tag) {
                    return $tag->getName();
                },
                'label' => 'Tag pour la veille',
                'attr' => [
                    'placeholder' => 'Choisissez les tags pour cette veille',
                    'class' => 'form-control',
                ],
                'multiple' => true,
//                'empty_data' => 'Choissisez un tag',
            ])
            //->add('author')
            //->add('article_created_at') put datetime at the moment
//            ->add('tags', EntityType::class, [
//                'label' => "Choississez le tag correspondant à l'article",
//                'required' => false,
//                'class' => Tag::class,
//                'choice_label' => 'name',
//                'multiple' => true,
//                'mapped' => false
//            ])
            //->add('users') only if multiple users, just for one select for admin ?
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
