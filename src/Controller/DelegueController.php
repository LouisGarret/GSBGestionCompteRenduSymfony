<?php

namespace App\Controller;

use App\Entity\CompteRendu;
use App\Entity\Famille;
use App\Entity\Medicament;
use App\Entity\User;
use App\Entity\Vehicule;
use App\Form\CompteRenduType;
use App\Form\StatTicketDateType;
use App\Form\StatTicketRegionType;
use App\Repository\CompteRenduRepository;
use App\Repository\FamilleRepository;
use App\Repository\VehiculeRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Route("/delegue")
 */
class DelegueController extends FOSRestController
{
    /**
     * @Route("/", name="delegue_index")
     */
    public function index()
    {
        return $this->render('Delegue/index.html.twig');
    }

    /**
     * @Route("/praticiens", name="delegue_praticiens_index")
     * @Rest\Get("/praticiens")
     */
	public function affichagePraticien()
	{
		$em = $this->getDoctrine()->getManager();
		$praticiens = $em->getRepository('App:Praticien')->findAll();

		$view = $this->view($praticiens, 200)
			->setTemplate("Delegue/Praticien/praticiens.html.twig")
			->setTemplateVar('praticiens');

		return $this->handleView($view);
	}

    /**
     * @Route("/medicament", name="delegue_medicament_index", methods="GET")
     * @Rest\Get("/medicament")
     */
    public function affichageMedicament(): Response
    {
        $medicaments = $this->getDoctrine()
            ->getRepository(Medicament::class)
            ->findAll();

        $view = $this->view($medicaments, 200)
            ->setTemplate("Delegue/medicament/index.html.twig")
            ->setTemplateVar('medicaments');

        return $this->handleView($view);
    }

    /**
     * @Route("/medicament/{id}", name="delegue_medicament_show", methods="GET")
     * @Rest\Get("/medicament/{id}")
     */
    public function detailMedicament(Medicament $medicament): Response
    {
        $view = $this->view($medicament, 200)
            ->setTemplate("Delegue/medicament/show.html.twig")
            ->setTemplateVar('medicament');

        return $this->handleView($view);
    }

    /**
     * @Route("/famille", name="delegue_famille_index", methods="GET")
     * @Rest\Get("/famille")
     */
    public function affichageFamille(): Response
    {
        $familles = $this->getDoctrine()
            ->getRepository(Famille::class)
            ->findAll();

        $view = $this->view($familles, 200)
            ->setTemplate("Delegue/famille/index.html.twig")
            ->setTemplateVar('familles');

        return $this->handleView($view);
    }

    /**
     * @Route("/famille/{id}", name="delegue_famille_show", methods="GET")
     * @Rest\Get("/famille/{id}")
     */
    public function detailFamille(Famille $famille): Response
    {
        $view = $this->view($famille, 200)
            ->setTemplate("Delegue/famille/show.html.twig")
            ->setTemplateVar('famille');

        return $this->handleView($view);
    }

	/**
	 * @Route("/compte/rendu/stats_praticiens", name="delegue_compte_rendu_stats_praticiens")
	 */
	public function showStatsPraticiens(Request $request)
	{
		$pieChartRegion = new PieChart();

		$region = $this->getUser()->getRegion();
		$dataRegion = $this->getDoctrine()->getRepository('App:CompteRendu')->countByVisiteurByRegion($region);

		$dataRe = json_encode($region, true);
		$dataRes = json_decode($dataRe);
		$dataRegion = json_encode($dataRegion);
		$nbRegion = json_decode($dataRegion);

		$datasRegion = array();
		array_push($datasRegion, array('visiteur', 'nombre'));
		for ($i = 0; $i < count($nbRegion); $i++) {
			array_push($datasRegion, array($nbRegion[$i]->username, (int)$nbRegion[$i]->nb));
		}

		$pieChartRegion->getData()->setArrayToDataTable($datasRegion);
		$pieChartRegion->getOptions()->setHeight(500);
		$pieChartRegion->getOptions()->setBackgroundColor('#eee');

		return $this->render('Delegue/compte_rendu/statistiques/stats_praticiens.html.twig', array(
			'piechartRegion' => $pieChartRegion,
			'region' => $region,
		));
	}


	/**
	 * @Route("/compte/rendu/stats_jour", name="delegue_compte_rendu_stats_jour")
	 */
	public function showStatsPraticiensJour(Request $request)
	{
		{
			$form = $this->createForm(StatTicketDateType::class);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$value = $form->get('dateDebut')->getData();
				$value2 = $form->get('dateFin')->getData();

				$dateD = json_decode(json_encode($value), true);
				$dateDebut = date("d-m-Y", strtotime($dateD['date']));

				$dateF = json_decode(json_encode($value2), true);
				$dateFin = date("d-m-Y", strtotime($dateF['date']));

				$data = $this->getDoctrine()->getRepository('App:CompteRendu')->countByVisiteurByDate($value, $value2);
				$nbByVisByDate = $this->getDoctrine()->getRepository('App:CompteRendu')->countByDate($value,$value2);

				$data = json_encode($data);
				$nb = json_decode($data);

				$pieChart = new PieChart();

				$datas = array();
				array_push($datas, array('visiteur', 'nombre'));
				for ($i = 0; $i < count($nb); $i++) {
					array_push($datas, array($nb[$i]->username, (int)$nb[$i]->nb));
				}

				$pieChart->getData()->setArrayToDataTable($datas);
				$pieChart->getOptions()->setHeight(500);
				$pieChart->getOptions()->setBackgroundColor('#eee');

				return $this->render('Delegue/compte_rendu/statistiques/stats_praticiens_date.html.twig', array(
					'tickets' => $nbByVisByDate,
					'dateDebut' => $dateDebut,
					'dateFin' => $dateFin,
					'piechart' => $pieChart,
				));
			}
			return $this->render('Delegue/compte_rendu/statistiques/stats_praticiens_date_form.html.twig', [
				'form' => $form->createView(),
			]);
		}
	}

    /**
     * @Route("/compte/rendu", name="delegue_compte_rendu_index", methods="GET")
     * @Rest\Get("/compte/rendu")
     */
    public function getComptesRendusSecteur(): Response
    {

        $delegue = $this->getUser();
        $region = $delegue->getRegion();
        $comptesRendus = $this->getDoctrine()
            ->getRepository(CompteRendu::class)
            ->findByRegion($region);

        $view = $this->view($comptesRendus, 200)
            ->setTemplate("Delegue/compte_rendu/index.html.twig")
            ->setTemplateVar('delegue')
            ->setTemplateVar('region')
            ->setTemplateVar('compte_rendus');

        return $this->handleView($view);
    }

	/**
	 * @Rest\Get("/api/compte/rendu")
	 */
	public function affichageCompteRenduParPraticiensAPI()
	{
		$em = $this->getDoctrine()->getManager();
		if (isset($_GET['id'])) {
			$region = $_GET['id'];
			$compteRendus = $em->getRepository(CompteRendu::class)->findByRegion($region);

			if ($region != null) {
				$response = array();
				foreach ($compteRendus as $compteRendu) {
					$response[] = array(
						'numero' => $compteRendu->getId(),
						'dateVisite' => $compteRendu->getDateVisite(),
						'dateSaisie' => $compteRendu->getDateSaisie(),
						'coefficient' => $compteRendu->getCoefficient(),
						'bilan' => $compteRendu->getBilan(),
						'etatCloture' => $compteRendu->getCloture(),
						'documentation' => $compteRendu->getDoc(),
						'remplacant' => $compteRendu->getRemplacant(),
						'motif' => $compteRendu->getMotif(),
						'produits' => $compteRendu->getProduits()->getValues(),
						'echantillons' => $compteRendu->getEchantillons(),
						'vehicule' => $compteRendu->getVehicule()->getId()
					);
				}
			}
			else{
				$response["success"] = 0;
				$response["message"] = "Ces informations ne correspondent a aucun compte rendu";
			}
		}else{
			$response["success"] = 0;
			$response["message"] = "Vous devez saisir l'id de la region";
		}

		return new JsonResponse($response);
	}

    /**
     * @Route("/compte/rendu/new", name="delegue_compte_rendu_new", methods="GET|POST")
     * @Rest\Get("/compte/rendu/new")
     */
    public function new(Request $request): Response
    {
        $compteRendu = new CompteRendu();
        $form = $this->createForm(CompteRenduType::class, $compteRendu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($compteRendu);
            $em->flush();

            return $this->redirectToRoute('delegue_compte_rendu_index');
        }

        return $this->render('Delegue/compte_rendu/new.html.twig', [
            'compte_rendu' => $compteRendu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/compte/rendu/{id}", name="delegue_compte_rendu_show", methods="GET")
     * @Rest\Get("/compte/rendu/{id}")
     */
    public function show(CompteRendu $compteRendu): Response
    {
        $view = $this->view($compteRendu, 200)
            ->setTemplate("Delegue/compte_rendu/show.html.twig")
            ->setTemplateVar('compte_rendu');

        return $this->handleView($view);
    }

    /**
     * @Route("/compte/rendu/{id}/edit", name="delegue_compte_rendu_edit", methods="GET|POST")
     * @Rest\Get("/compte/rendu/{id}/edit")
     */
    public function edit(Request $request, CompteRendu $compteRendu): Response
    {
        $form = $this->createForm(CompteRenduType::class, $compteRendu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('delegue_compte_rendu_index', ['id' => $compteRendu->getId()]);
        }

        return $this->render('Delegue/compte_rendu/edit.html.twig', [
            'compte_rendu' => $compteRendu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/compte/rendu/{id}", name="delegue_compte_rendu_delete", methods="DELETE")
     * @Rest\Get("/compte/rendu/{id}")
     */
    public function delete(Request $request, CompteRendu $compteRendu): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compteRendu->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($compteRendu);
            $em->flush();
        }

        return $this->redirectToRoute('delegue_compte_rendu_index');
    }

	/**
	 * @Route("/vehicule", name="delegue_vehicule_index", methods="GET")
	 */
	public function indexVehicule(VehiculeRepository $vehiculeRepository): Response
	{
		return $this->render('Delegue/vehicule/index.html.twig', ['vehicules' => $vehiculeRepository->findAll()]);
	}

	/**
	 * @Route("/vehicule/{id}", name="delegue_vehicule_show", methods="GET")
	 */
	public function showVehicule(Vehicule $vehicule): Response
	{
		return $this->render('Delegue/vehicule/show.html.twig', ['vehicule' => $vehicule]);
	}
}
