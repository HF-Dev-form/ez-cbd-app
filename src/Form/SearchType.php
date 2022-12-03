<?php

namespace App\Form;

use App\Entity\Category;
use App\Service\Search;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('string', TextType::class, [
                "label" => false,
                "required" => false,
                "attr" => [
                    'placeholder' => "Tapez votre recherche",
                    'class' => "form-control me-sm-2 "
                ]
            ])
            
            // ->add('categories', EntityType::class, [
            //     'class' => Category::class,
            //     'label' => false,
            //     'required' => false,
            //     'multiple' => true,
            //     'expanded' => true,
            //     'attr' => [
            //         'class' => "form-group"
            //     ]
            // ])

            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'crsf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}