<?php

namespace App\Form;

use App\Entity\Region;
use App\Entity\Secteur;
use App\Repository\RegionRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatTicketRegionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$id = $options['userRegionId'];

        $builder
			->add('region', EntityType::class, array(
				// looks for choices from this entity
				'class' => Region::class,
				'label' => 'Sélectionner une région',
				'query_builder' => function (RegionRepository $er) use ($id) {
					return $er->getRegionBySecteur($id);
				},

			))
        ;
    }

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setRequired(['userRegionId']);
	}
}
