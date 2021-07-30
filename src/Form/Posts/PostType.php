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

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de la veille",
                'attr' => ['placeholder' => "Mettez le titre de la veille"]
            ])
            ->add('slug', TextType::class, [
                'label' => "L'url de la veille",
                'attr' => ['placeholder' => "Ce champ n'est pas obligatoire.
                L'url se met automatiquement sauf si vous voulez la personalisée"],
                'required' => false
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Téléchargez une image pour votre article',
                'data_class' => null,
                'required' => false,
                'attr' => ['placeholder' => 'choisir une image'],
                'mapped' => true,
                'constraints' => [
                    new Image([
                        'maxSize' => '3M',
                        'mimeTypes' => [
                            'image/jpg', 'image/jpeg', 'image/png', 'image/bmp'
                        ]
                    ])
                ]
            ])
            ->add('refDescription', TextareaType::class, [
                'label' => "Contenu pour le référencement",
                'attr' => ['placeholder' => "Mettez le contenu pour le référencement"]
            ])
            ->add('excerpt', TextType::class, [
                'label' => "Contenu pour la description",
                'attr' => ['placeholder' => "Mettez le contenu pour la description"]
            ])
            ->add('content', TextareaType::class, [
                'label' => "Contenu de l'article",
                'attr' => ['placeholder' => "Mettez le contenu de l'article"]
            ])
            //->add('article_created_at') put datetime at the moment
//            ->add('tags', EntityType::class, [
//                'label' => "Choississez le tag correspondant à l'article",
//                'required' => false,
//                'class' => Tag::class,
//                'choice_label' => 'name',
//                'multiple' => true,
//                'mapped' => false
//            ])
            //->add('users') only if multiple users, just for one select admin
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
