<?php

namespace App\Form;

use App\Entity\Praticien;
use App\Entity\Region;
use App\Entity\Specialite;
use App\Entity\TypePraticien;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PraticienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
			->add('specialite', EntityType::class, array(
				// looks for choices from this entity
				'class' => Specialite::class,
				// uses the User.username property as the visible option string
				'choice_label' => 'libelle',

				// used to render a select box, check boxes or radios
				'multiple' => false,
				'expanded' => false,
				'required' => false,
			))
			->add('typePraticien', EntityType::class, array(
				// looks for choices from this entity
				'class' => TypePraticien::class,
				// uses the User.username property as the visible option string
				'choice_label' => 'libelle',

				// used to render a select box, check boxes or radios
				'multiple' => false,
				'expanded' => false,

			))
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Praticien::class,
        ]);
    }
}
