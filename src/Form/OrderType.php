<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
  
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('addresses', EntityType::class, [
                'label' => false,
                'class' => Adress::class,
                'choices' => $user->getAdresses(),
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'attr' => [

                ]
            ])

             ->add('carriers', EntityType::class, [
                'label' => 'Choisissez votre transporteur',
                'class' => Carrier::class,
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'attr' => [

                ]
            ])

             ->add('submit', SubmitType::class, [
                'label' => 'Valider ma commande',
                'attr' => [
                    'class' => 'btn btn-sm btn-success btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => []
        ]);
    }
}
