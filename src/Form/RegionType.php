<?php

namespace App\Form;

use App\Entity\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('nom')
            ->add('secteur', EntityType::class, array(
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
                'choice_label' => 'nom',

                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Region::class,
        ]);
    }
}
