<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('last_name', TextType::class, [
                'label' => "Nom",
                'required' => false,
                'attr' => [
                    'placeholder' => "Veuillez saisir votre nom"
                ],
                'constraints' => [
                        new NotBlank([
                            'message' => 'Ce champs est requis',
                        ]) ],
            ])

             ->add('first_name', TextType::class, [
                'label' => "Prénom",
                'required' => false,
                'attr' => [
                    'placeholder' => "Veuillez saisir votre prénom"
                ],
                'constraints' => [
                        new NotBlank([
                            'message' => 'Ce champs est requis',
                        ]) ],
            ])

             ->add('email', EmailType::class, [
                'label' => "Email",
                'required' => false,
                'attr' => [
                    'placeholder' => "exemple@yahoo.fr"
                ],
                'constraints' => [
                        new NotBlank([
                            'message' => 'Afin de pouvoir traiter votre demande, votre email lest obligatoire',
                        ]) ],
            ])

              ->add('subject', TextType::class, [
                'label' => "Sujet",
                'required' => false,
                'attr' => [
                    'placeholder' => ""
                ],
                'constraints' => [
                        new NotBlank([
                            'message' => 'Ce champs est requis',
                        ]) ],
            ])

             ->add('content', TextareaType::class, [
                'label' => "Message",
                'required' => false,
                'attr' => [
                    'placeholder' => "En quoi pouvez-nous vous aider?"
                ],
                 'constraints' => [
                        new NotBlank([
                            'message' => 'Ce champs est requis',
                        ]) ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
