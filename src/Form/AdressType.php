<?php

namespace App\Form;

use App\Entity\Adress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => "Nommer mon adresse"
            ])
            ->add('firstname', TextType::class, [
                'required' => false,
                'label' => "Prénom"
            ])
            ->add('lastname', TextType::class, [
                'required' => false,
                'label' => "Nom"
            ])
            ->add('company', TextType::class, [
                'required' => false,
                'label' => "Société (facultatif)"
            ])
            ->add('adress', TextType::class, [
                'required' => false,
                'label' => "Adresse",
                'attr' => [
                    'palceholder' => "ex: 20 avenue de la république"
                ]
            ])
            ->add('postal', TextType::class ,[
                'required' => false,
                'attr' => [
                    'placeholder' => "ex: 75000"
                ]
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'label' => "Ville"
            ])
            ->add('country', CountryType::class, [
                'required' => false,
                'label' => "Pays"
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'label' => "Numéro de téléphone"
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Enregistrer",
                'attr' => [
                    'class' => ' btn btn-block btn-dark'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
