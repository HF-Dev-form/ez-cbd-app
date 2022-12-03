<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => "Nom",
                'attr' => [

                ],
                'required' => false,
            ])
            ->add('firstname', TextType::class, [
                'label' => "Prénom",
                'attr' => [

                ],
                'required' => false
            ])
            ->add('email', TextType::class, [
                'label' => "Email",
                'attr' => [

                ],
                'required' => false
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Les mots de passes ne corespondent pas" ,
                'attr' => [

                ],
                'first_options' => ['label' => "Mot de passe"],
                'second_options' => ['label' => "Confirmez votre mot de passe"],
                'required' => false
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => "btn btn-primary my-3"
                ]
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
