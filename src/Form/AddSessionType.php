<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddSessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('sessions', EntityType::class, [
            'class' =>Session::class,
            'label' => 'Ajouter',
            'choice_label' => function ($choice, $key, $value) {
                $nbStag = $choice->getNbplaces();
                $nb =  count($choice->getStagiaires());
                dump($nbStag,$nb);
                if($nbStag <= $nb){
                    $nom = $choice->getNom()." formation complète ";
                }
                else{
                    $reste = $nbStag - $nb;
                    $nom =  $choice->getNom()." reste : ".$reste." places";
                }             
                return $nom;
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nom', 'ASC');
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
            'data_class' => Stagiaire::class,
        ]);
    }
}
