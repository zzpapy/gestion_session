<?php

namespace App\Form;

use App\Entity\Salle;
use App\Entity\Session;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // dump($options["programmes"]);die;
        $builder
            ->add('nom')
            ->add('date_debut',DateType::class, [
                'widget' => 'single_text',
                // 'years' => range(date('Y'), date('Y')+10),
                // 'format' => 'dd-MM-yyyy ',
                // 'html5'  => false,
                // 'data' => $options["data"]->getDateDebut(),
                // 'placeholder' => [
                //     'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                // ]
                // "data" => new \DateTime()
            ])
            ->add('date_fin',DateType::class, [
                'widget' => 'single_text',
                // 'years' => range(date('Y'), date('Y')+10),
                // 'format' => 'dd-MM-yyyy ',
                // 'html5'  => false,
                // 'data' => $options["data"]->getDateFin(),
                // 'placeholder' => [
                //     'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                // ]
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
            ->add('salle', EntityType::class, [
                'class' =>Salle::class,
                // 'choice_label' => function ($choice, $key, $value) {               
                //     $nom = $choice->getNumero();
                // },
                'multiple' =>true,
                'expanded' =>true,
                "by_reference" => false
            ])
            ->add('programmes', CollectionType::class, [
                'entry_type' => ProgrammeType::class,
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
            ->add('submit', SubmitType::class, [
                "label" => "Ajouter",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
            "programmes" => null
        ]);
    }
}
