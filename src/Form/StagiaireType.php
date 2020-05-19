<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


class StagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('sexe',  ChoiceType::class, [
                'choices' => [
                    'Homme' => true,
                    'femme' => false,
                ],
                "expanded" => true,
                "multiple" => false
            ])
            ->add('birth', DateType::class, [
                'years' => range(date('Y'), date('Y')-100),
                'format' => 'dd-MM-yyyy ',
                'html5'  => false,
                "data" => new \DateTime()
            ])
            ->add('ville')
            ->add('email')
            ->add('phone')
            ->add('sessions', EntityType::class, [
                'class' => Session::class,
                'choice_label' => 'nom',
                'multiple' =>true,
                'expanded' =>true,
                "by_reference" => false
            ])
            ->add('photo', FileType::class, [
                'label' => 'photo (JPG/PNG....)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'application/jpg',
                            'application/png',
                        ],
                        'mimeTypesMessage' => 'ceci n\'est pas photo',
                    ])
                ],
            ])
            // ...
        
            ->add('submit', SubmitType::class, [
                "label" => "Ajouter"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}
