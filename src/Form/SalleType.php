<?php

namespace App\Form;

use App\Entity\Salle;
use App\Entity\Session;
use App\Entity\Materiel;
use App\Form\MaterielType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero')
            ->add('places')
            ->add('sessions', EntityType::class, [
                'class' =>Session::class,
                // 'choice_label' => function ($choice, $key, $value) {               
                //     $nom = $choice->getNumero();
                // },
                'multiple' =>true,
                'expanded' =>true,
                "by_reference" => false
            ])
            // ->add('materiels', EntityType::class, [
            //     'class' =>Materiel::class,
            //     // 'choice_label' => function ($choice, $key, $value) {               
            //     //     $nom = $choice->getNumero();
            //     // },
            //     'multiple' =>true,
            //     'expanded' =>true,
            //     "by_reference" => false
            // ])
            ->add('materiels', CollectionType::class, [
                'entry_type' => MaterielType::class,
                'entry_options' => [
                    'attr' => ['class' => 'programme'],
                ],
                // 'required' => false,
                "label" => false,
                'allow_add' => true,
                'allow_delete' => true,
                // 'prototype' => true,
                'by_reference' => false
            ])
            ->add('ajouter',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
        ]);
    }
}
