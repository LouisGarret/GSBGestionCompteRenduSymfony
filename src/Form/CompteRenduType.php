<?php

namespace App\Form;

use App\Entity\CompteRendu;
use App\Entity\Medicament;
use App\Entity\Praticien;
use App\Entity\User;
use App\Entity\Vehicule;
use App\Repository\PraticienRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CompteRenduType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateVisite', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('coefficient', TextType::class, [
                'label' => 'Coefficient de confiance'
            ])
            ->add('bilan' , TextareaType::class)
            ->add('remplacant', TextType::class, [
                'required' => false,
            ])
            ->add('motif')
            ->add('visiteur', EntityType::class, array(
                'class' => User::class,
                'query_builder' => function (UserRepository $er){
                    return $er->findByMultipleRoleQuery('ROLE_VISITEUR', 'ROLE_DELEG_REGIONAL');
                },
            ))
            ->add('praticien', EntityType::class, array(
                'class' => Praticien::class,
				'query_builder' => function(PraticienRepository $er) {
					return $er->createQueryBuilder('p')
						->orderBy('p.nom', 'ASC');
				},
				'choice_label' => 'nomPrenom',
            ))
            ->add('produits', EntityType::class, array(
                // looks for choices from this entity
                'class' => Medicament::class,
                'required' => false,
                'group_by'=>function($value,$key,$index){
                    if($value->getFamille()->getLibelle()!=""){
                        return $value->getFamille()->getLibelle();
                    }
                    else{
                        return "Autres";
                    }
                },
                // uses the User.username property as the visible option string
                'choice_label' => 'NomCommercial',

                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => false,
            ))
            ->add('echantillons', EntityType::class, array(
                // looks for choices from this entity
                'class' => Medicament::class,
                'required' => false,
                'group_by'=>function($value,$key,$index){
                    if($value->getFamille()->getLibelle()!=""){
                        return $value->getFamille()->getLibelle();
                    }
                    else{
                        return "Autres";
                    }
                },
                // uses the User.username property as the visible option string
                'choice_label' => 'NomCommercial',

                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => false,
            ))
            ->add('cloture' , CheckboxType::class, [
                'label' => 'Compte rendu cloturé ?'
            ])
            ->add('doc' , CheckboxType::class, [
                'label' => 'Documentation Fournie ?',
				'required' => false,
            ])
			->add('vehicule', EntityType::class, array(
				// looks for choices from this entity
				'class' => Vehicule::class,
				'required' => false,
				// uses the User.username property as the visible option string
				'choice_label' => 'id',

				// used to render a select box, check boxes or radios
				'multiple' => false,
				'expanded' => false,
			))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CompteRendu::class,
        ]);
    }
}
