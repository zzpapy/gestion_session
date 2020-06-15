<?php

namespace App\Form;

use App\Entity\Salle;
use App\Entity\Materiel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('stock')
            ->add('Salle', EntityType::class, [
                'class' =>Salle::class,
                // 'choice_label' => function ($choice, $key, $value) {               
                //     $nom = $choice->getNumero();
                // },
                'multiple' =>true,
                'expanded' =>true,
                "by_reference" => false
            ])
            ->add('ajouter',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}
