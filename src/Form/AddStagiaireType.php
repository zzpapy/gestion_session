<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddStagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('stagiaires', EntityType::class, [
            'class' =>Stagiaire::class,
            'label' => 'Ajouter ou supprimer un stagiaire de la formation',
            'choice_label' => function ($choice, $key, $value) {               
                $nom = $choice->getNom();
                return ucfirst($nom);
            },
            'multiple' =>true,
            'expanded' =>true,
            "by_reference" => false
        ])
        ->add('submit',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
