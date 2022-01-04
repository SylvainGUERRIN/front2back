<?php

namespace App\Form\Comments;

use App\Subscriber\ImageCacheSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateCommentType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Commentaire',
                'attr' => [
                    'placeholder' => 'Votre commentaire',
                    'class' => 'form-control',
                    'rows' => '5',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 150]),
                ],
            ])
        ;

        $user = $this->security->getUser();

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function ($event) use ($user) {
            if (!$user) {
                $form = $event->getForm();
                $form->add('email', TextType::class, [
                    'label' => 'Email',
                    'label_attr' => [
                        'class' => 'form-label',
                    ],
                    'attr' => [
                        'placeholder' => 'Votre email',
                        'class' => 'form-control',
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Email(),
                    ],
                ]);
            }
//            $form = $event->getForm();
//            $form->add('content', TextareaType::class, [
//                'label' => 'Commentaire',
//                'attr' => [
//                    'placeholder' => 'Votre commentaire',
//                    'rows' => '5',
//                ],
//            ]);
        });
    }
}
