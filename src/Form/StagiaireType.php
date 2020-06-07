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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
                'format' => 'dd-MM-yyyy ',
                'years' => range(date('Y')-18, date('Y')-100),
                // 'widget' => 'single_text',
                // 'model_timezone' => 'Etc/UTC',
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                'view_timezone' => 'Europe/Paris',
                'attr' => [
                    'class' => "datePicker"
                ],
                'html5'  => false,
                "data" => $options["data"]->getBirth()
            ])
            ->add('ville',TextType::class)
            // ->add('ville',ChoiceType::class,[
            //     "choices" => $options["list_commune"]
            // ])
            ->add('email',EmailType::class)
            ->add('phone')
            // ->add('sessions', EntityType::class, [
            //     'class' => Session::class,
            //     'choice_label' => 'nom',
            //     'multiple' =>true,
            //     'expanded' =>true,
            //     "by_reference" => false
            // ])
            ->add('photo', FileType::class, [
                'label' => 'photo (JPG/PNG....)',
                'attr'  => [
                    "class" => 'photo_input'
                ],

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                // 'attr' => ['onchange' => 'readUrl(this)'],
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                // 'constraints' => [
                //     new File([
                //         'mimeTypes' => [
                //             'application/jpg',
                //             'application/png',
                //         ],
                //         'mimeTypesMessage' => 'ceci n\'est pas photo',
                //     ])
                // ],
            ])
            // ...
        
            ->add('submit', SubmitType::class, [
                "label" => "Ajouter",
                // "list_commune" => null
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
