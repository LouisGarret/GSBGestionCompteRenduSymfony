<?php

namespace App\Form;

use App\Entity\Region;
use App\Entity\Specialite;
use App\Entity\TypePraticien;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => 'Nom',
            ])
            ->add('firstName','Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => 'Prénom',
            ])
            ->add('region', EntityType::class, array(
                // looks for choices from this entity
                'class' => Region::class,
                'group_by'=>function($value,$key,$index){
                    if($value->getSecteur()->getLibelle()!=""){
                        return $value->getSecteur()->getLibelle();
                    }
                    else{
                        return "Autres";
                    }
                },
                // uses the User.username property as the visible option string
                'choice_label' => 'libelle',

                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('roles',ChoiceType::class,
                array(
                    'label'   => 'Rôles',
                    'choices' => array(
                        'Visiteur' => User::VISITEUR,
                        'Délégué Régional' => User::DELEGUE,
                        'Responsable Secteur' => User::RESPONSABLE,
                        'Administrateur' => User::ADMIN,
                    ),
                    'multiple' => true,
                    'expanded' => true
                )
            );
    }
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
