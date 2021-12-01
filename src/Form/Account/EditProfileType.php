<?php

namespace App\Form\Account;

use App\Entity\User;
use App\Form\AvatarType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProfileType extends AbstractType
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //$user = $this->token->getToken()->getUser();
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
//                'data' => function () {
//                    if ($this->token->getToken()->getUser()->getRequests() !== null) {
//                        if (array_key_exists('contributor', $this->token->getToken()->getUser()->getRequests())) {
//                            return true;
//                        }
//                    }
//                    return false;
//                },
            ])
//            ->get('requests')
//            ->addModelTransformer(new CallbackTransformer(
//                function ($activeAsString) {
//                    // transform the string to boolean
//                    $result = (bool)$activeAsString;
//                    return $result;
//                },
//                function ($activeAsBoolean) {
//                    // transform the boolean to string
//                    $result = (string)$activeAsBoolean;
//                    dump($result);
//                    die();
//                    return (int)$result;
//                }
//            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
//            'empty_data' => function (FormInterface $form) {
//                return new User();
//            },
        ]);
    }
}
