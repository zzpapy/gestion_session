<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('date_debut',DateType::class, [
                'format' => 'dd-MM-yyyy ',
                'html5'  => false,
                // "data" => new \DateTime()
            ])
            ->add('date_fin',DateType::class, [
                'format' => 'dd-MM-yyyy ',
                'html5'  => false,
            ])
            ->add('nb_places')
            ->add('stagiaires', EntityType::class, [
                'class' =>Stagiaire::class,
                'choice_label' => function ($choice, $key, $value) {               
                    $nom = $choice->getNom();
                    return ucfirst($nom);
                },
                'multiple' =>true,
                'expanded' =>true,
                "by_reference" => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
