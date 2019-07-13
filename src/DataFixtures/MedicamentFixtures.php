<?php

namespace App\DataFixtures;

use App\Entity\Famille;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Medicament;

class MedicamentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $famille1 = new Famille();
        $famille2 = new Famille();
        $famille3 = new Famille();
        $famille4 = new Famille();
        $famille5 = new Famille();
        $famille6 = new Famille();
        $famille7 = new Famille();
        $famille8 = new Famille();
        $famille9 = new Famille();
        $famille10 = new Famille();
        $famille11 = new Famille();
        $famille12 = new Famille();
        $famille13 = new Famille();
        $famille14 = new Famille();
        $famille15 = new Famille();
        $famille16 = new Famille();
        $famille17 = new Famille();
        $famille18 = new Famille();
        $famille19 = new Famille();
        $famille20 = new Famille();

        $famille1 ->setCode('AA');
        $famille1 ->setLibelle('Antalgiques en association');
        $famille2 ->setCode('AAA');
        $famille2 ->setLibelle('Antalgiques antipyrétiques en association');
        $famille3 ->setCode('AAC');
        $famille3 ->setLibelle('Antidépresseur d\'action centrale');
        $famille4 ->setCode('AAH');
        $famille4 ->setLibelle('Antivertigineux antihistaminique H1');
        $famille5 ->setCode('ABA');
        $famille5 ->setLibelle('Antibiotique antituberculeux');
        $famille6 ->setCode('ABC');
        $famille6 ->setLibelle('Antibiotique antiacnéique local');
        $famille7 ->setCode('ABP');
        $famille7 ->setLibelle('Antibiotique de la famille des béta-lactamines (pénicilline A)');
        $famille8 ->setCode('AFC');
        $famille8 ->setLibelle('Antibiotique de la famille des cyclines');
        $famille9 ->setCode('AFM');
        $famille9 ->setLibelle('Antibiotique de la famille des macrolides');
        $famille10 ->setCode('AH');
        $famille10 ->setLibelle('Antihistaminique H1 local');
        $famille11 ->setCode('AIM');
        $famille11 ->setLibelle('Antidépresseur imipraminique (tricyclique)');
        $famille12 ->setCode('AIN');
        $famille12 ->setLibelle('Antidépresseur inhibiteur sélectif de la recapture de la sérotonine');
        $famille13 ->setCode('ALO');
        $famille13 ->setLibelle('Antibiotique local (ORL)');
        $famille14 ->setCode('ANS');
        $famille14 ->setLibelle('Antidépresseur IMAO non sélectif');
        $famille15 ->setCode('AO');
        $famille15 ->setLibelle('Antibiotique ophtalmique');
        $famille16 ->setCode('AP');
        $famille16 ->setLibelle('Antipsychotique normothymique');
        $famille17 ->setCode('AUM');
        $famille17 ->setLibelle('Antibiotique urinaire minute');
        $famille18 ->setCode('CRT');
        $famille18 ->setLibelle('Corticoïde, antibiotique et antifongique à  usage local');
        $famille19 ->setCode('HYP');
        $famille19 ->setLibelle('Hypnotique antihistaminique');
        $famille20 ->setCode('PSA');
        $famille20 ->setLibelle('Psychostimulant, antiasthénique');


        // $product = new Product();
        // $manager->persist($product)

        $medicament = new Medicament();
        $medicament->setDepotLegal('test');
        $medicament->setNomCommercial('test');
        $medicament->setComposition('test (acétonide) + Néomycine + Nystatine');
        $medicament->setEffets('test médicament est un corticoïde à  activité forte ou très forte associé à  un antibiotique et un antifongique, utilisé en application locale dans certaines atteintes cutanées surinfectées.');
        $medicament->setContreIndic('Ce test est contre-indiqué en cas d\'allergie à  l\'un des constituants, d\'infections de la peau ou de parasitisme non traités, d\'acné. Ne pas appliquer sur une plaie, ni sous un pansement occlusif.');
        $medicament->setPrixEchantillon('1');
        $medicament->getFamille();
        $medicament->setFamille($famille1);
        $manager->persist($medicament);
        $manager->persist($famille1);
        $manager->flush();
    }
}
