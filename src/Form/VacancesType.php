<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Vacances;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class VacancesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_debut',DateType::class, [
                'format' => 'dd-MM-yyyy ',
                'html5'  => false,
                'years' => range(date('Y'), date('Y')+10),
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
            ])
            ->add('date_fin',DateType::class, [
                'format' => 'dd-MM-yyyy ',
                'html5'  => false,
                'years' => range(date('Y'), date('Y')+10),
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
            ])
            // ->add('session', EntityType::class, [
            //     'class' =>Session::class,
            //     'choice_label' => function ($choice, $key, $value) {               
            //         $nom = $choice->getNom();
            //         return ucfirst($nom);
            //     },
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vacances::class,
        ]);
    }
}
