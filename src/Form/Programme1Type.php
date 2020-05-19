<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Session;
use App\Entity\Programme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Programme1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('duree')
            ->add('module', EntityType::class, [
                'class' =>Module::class,
                'choice_label' => 'nom',
                'multiple' =>false,
                'expanded' =>true,
                "by_reference" => false
            ])
            ->add('module', EntityType::class, [
                'class' =>Session::class,
                'choice_label' => 'nom',
                'multiple' =>false,
                'expanded' =>true,
                "by_reference" => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
