<?php

namespace App\Controller;

use App\Entity\CompteRendu;
use App\Entity\Famille;
use App\Entity\User;
use App\Entity\Medicament;
use App\Entity\Vehicule;
use App\Form\CompteRenduType;
use App\Form\StatTicketDateType;
use App\Form\StatTicketRegionType;
use App\Form\VehiculeType;
use App\Repository\CompteRenduRepository;
use App\Repository\FamilleRepository;
use App\Repository\VehiculeRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\CalendarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\LineChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Options\PieChart\PieSlice;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * @Route("/responsable")
 */
class ResponsableController extends FOSRestController
{
	/**
	 * @Route("/", name="responsable_index")
	 */
	public function index()
	{
		return $this->render('Responsable/index.html.twig');
	}

	/**
	 * @Route("/praticiens", name="responsable_praticiens_index")
	 */
	public function affichagePraticien()
	{
		$em = $this->getDoctrine()->getManager();
		$praticiens = $em->getRepository('App:Praticien')->findAll();

		$view = $this->view($praticiens, 200)
			->setTemplate("Responsable/Praticien/praticiens.html.twig")
			->setTemplateVar('praticiens');

		return $this->handleView($view);
	}

	/**
	 * @Rest\Get("/api/praticiens")
	 */
	public function affichagePraticienAPI()
	{
		$em = $this->getDoctrine()->getManager();
		$praticiens = $em->getRepository('App:Praticien')->findAll();

		$response = array();
		foreach ($praticiens as $praticien) {
			$response[] = array(
				'id' => $praticien->getId(),
				'firstName' => $praticien->getPrenom(),
				'lastName' => $praticien->getNom(),
				'email' => $praticien->getEmail(),
				'region' => $praticien->getRegion()->getLibelle(),
				'secteur' => $praticien->getRegion()->getSecteur()->getLibelle(),
				'typePraticien' => $praticien->getTypePraticien()->getLibelle(),
				'specialite' => $praticien->getSpecialite()->getLibelle()

			);
		}

		if (count($response) == 0) {
			$retour["success"] = 0;
			$retour["message"] = "Aucun resultat";
		} else {
			$retour["success"] = 1;
			$retour["message"] = count($response) . " resultat(s)";
			$retour["users"] = $response;
		}

		return new JsonResponse($retour);
	}

	/**
	 * @Route("/medicament", name="responsable_medicament_index", methods="GET")
	 * @Rest\Get("/medicaments")
	 */
	public function affichageMedicament(): Response
	{
		$medicaments = $this->getDoctrine()
			->getRepository(Medicament::class)
			->findAll();

		$view = $this->view($medicaments, 200)
			->setTemplate("Responsable/medicament/index.html.twig")
			->setTemplateVar('medicaments');

		return $this->handleView($view);
	}

	/**
	 * @Route("/medicament/{id}", name="responsable_medicament_show", methods="GET")
	 * @Rest\Get("/medicament/{id}")
	 */
	public function detailMedicament(Medicament $medicament): Response
	{
		$view = $this->view($medicament, 200)
			->setTemplate("Responsable/medicament/show.html.twig")
			->setTemplateVar('medicament');

		return $this->handleView($view);
	}

	/**
	 * @Route("/famille", name="responsable_famille_index", methods="GET")
	 * @Rest\Get("/famille")
	 */
	public function affichageFamille(): Response
	{
		$familles = $this->getDoctrine()
			->getRepository(Famille::class)
			->findAll();

		$view = $this->view($familles, 200)
			->setTemplate("Responsable/famille/index.html.twig")
			->setTemplateVar('familles');

		return $this->handleView($view);
	}

	/**
	 * @Route("/famille/{id}", name="responsable_famille_show", methods="GET")
	 * @Rest\Get("/famille/{id}")
	 */
	public function detailFamille(Famille $famille): Response
	{
		$view = $this->view($famille, 200)
			->setTemplate("Responsable/famille/show.html.twig")
			->setTemplateVar('famille');

		return $this->handleView($view);
	}

	/**
	 * @Route("/compte/rendu", name="responsable_compte_rendu_index", methods="GET")
	 * @Rest\Get("/compte/rendu")
	 */
	public function getComptesRendusSecteur(): Response
	{
		//$responsable = $this->getUser();
		$secteur = $this->getUser()->getRegion()->getSecteur();
		$comptesRendus = $this->getDoctrine()
			->getRepository(CompteRendu::class)
			->findBySecteur($secteur);
		$view = $this->view($comptesRendus, 200)
			->setTemplate("Responsable/compte_rendu/index.html.twig")
			->setTemplateVar('responsable')
			->setTemplateVar('secteur')
			->setTemplateVar('compte_rendus');

		return $this->handleView($view);

		/*return $this->render('Responsable/compte_rendu/index.html.twig',
			[
				'compte_rendus' => $comptesRendus,
				'secteur' => $secteur,
				'responsable' => $responsable
			]);*/
	}

	/**
	 * @Route("/compte/rendu/stats_praticiens", name="responsable_compte_rendu_stats_praticiens")
	 */
	public function showStatsPraticiens(Request $request)
	{
		$secteurId = $this->getUser()->getRegion()->getSecteur();
		$form = $this->createForm(StatTicketRegionType::class, null, [
			'userRegionId' => $secteurId->getId()
		]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$pieChartSecteur = new PieChart();

			$secteur = $this->getUser()->getRegion()->getSecteur();
			$dataSecteur = $this->getDoctrine()->getRepository('App:CompteRendu')->countByVisiteurBySecteur($secteur);

			$dataSe = json_encode($secteur, true);
			$dataSes = json_decode($dataSe);
			$dataSecteur = json_encode($dataSecteur);
			$nbSecteur = json_decode($dataSecteur);

			$datasSecteur = array();
			array_push($datasSecteur, array('visiteur', 'nombre'));
			for ($i = 0; $i < count($nbSecteur); $i++) {
				array_push($datasSecteur, array($nbSecteur[$i]->username, (int)$nbSecteur[$i]->nb));
			}

			$pieChartSecteur->getData()->setArrayToDataTable($datasSecteur);
			$pieChartSecteur->getOptions()->setHeight(500);
			$pieChartSecteur->getOptions()->setBackgroundColor('#eee');

			$pieChartSecteur = new PieChart();

			$secteur = $this->getUser()->getRegion()->getSecteur();
			$dataSecteur = $this->getDoctrine()->getRepository('App:CompteRendu')->countByVisiteurBySecteur($secteur);

			$dataSe = json_encode($secteur, true);
			$dataSes = json_decode($dataSe);
			$dataSecteur = json_encode($dataSecteur);
			$nbSecteur = json_decode($dataSecteur);

			$datasSecteur = array();
			array_push($datasSecteur, array('visiteur', 'nombre'));
			for ($i = 0; $i < count($nbSecteur); $i++) {
				array_push($datasSecteur, array($nbSecteur[$i]->username, (int)$nbSecteur[$i]->nb));
			}

			$pieChartSecteur->getData()->setArrayToDataTable($datasSecteur);
			$pieChartSecteur->getOptions()->setHeight(500);
			$pieChartSecteur->getOptions()->setBackgroundColor('#eee');

			$pieChartRegion = new PieChart();

			$region = $form->get('region')->getData();
			$dataRegion = $this->getDoctrine()->getRepository('App:CompteRendu')->countByVisiteurByRegion($region);

			if ($dataRegion != null) {
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
				return $this->render('Responsable/compte_rendu/statistiques/stats_praticiens.html.twig', array(
					'piechartSecteur' => $pieChartSecteur,
					'piechartRegion' => $pieChartRegion,
					'secteur' => $secteur,
					'region' => $region,
					'form' => $form->createView()
				));
			} else {
				$region = null;
				return $this->render('Responsable/compte_rendu/statistiques/stats_praticiens.html.twig', array(
					'piechartSecteur' => $pieChartSecteur,
					'secteur' => $secteur,
					'region' => $region,
					'form' => $form->createView()
				));
			}


		}
		$pieChartSecteur = new PieChart();

		$secteur = $this->getUser()->getRegion()->getSecteur();
		$dataSecteur = $this->getDoctrine()->getRepository('App:CompteRendu')->countByVisiteurBySecteur($secteur);

		$dataSe = json_encode($secteur, true);
		$dataSes = json_decode($dataSe);
		$dataSecteur = json_encode($dataSecteur);
		$nbSecteur = json_decode($dataSecteur);

		$datasSecteur = array();
		array_push($datasSecteur, array('visiteur', 'nombre'));
		for ($i = 0; $i < count($nbSecteur); $i++) {
			array_push($datasSecteur, array($nbSecteur[$i]->username, (int)$nbSecteur[$i]->nb));
		}

		$pieChartSecteur->getData()->setArrayToDataTable($datasSecteur);
		$pieChartSecteur->getOptions()->setHeight(500);
		$pieChartSecteur->getOptions()->setBackgroundColor('#eee');

		$region = null;
		return $this->render('Responsable/compte_rendu/statistiques/stats_praticiens.html.twig', array(
			'piechartSecteur' => $pieChartSecteur,
			'secteur' => $secteur,
			'region' => $region,
			'form' => $form->createView(),
		));
	}


	/**
	 * @Route("/compte/rendu/stats_jour", name="responsable_compte_rendu_stats_jour")
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
				$nbByVisByDate = $this->getDoctrine()->getRepository('App:CompteRendu')->countByDate($value, $value2);

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

				return $this->render('Responsable/compte_rendu/statistiques/stats_praticiens_date.html.twig', array(
					'tickets' => $nbByVisByDate,
					'dateDebut' => $dateDebut,
					'dateFin' => $dateFin,
					'piechart' => $pieChart,
				));
			}
			return $this->render('Responsable/compte_rendu/statistiques/stats_praticiens_date_form.html.twig', [
				'form' => $form->createView(),
			]);
		}
	}

	/**
	 * @Rest\Get("/api/compte/rendu")
	 */
	public function affichageCompteRenduParPraticiensAPI()
	{
		$em = $this->getDoctrine()->getManager();
				if (isset($_GET['id'])) {
					$secteur = $_GET['id'];
					$compteRendus = $em->getRepository(CompteRendu::class)->findBySecteur($secteur);

					if ($secteur != null) {
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
					$response["message"] = "Vous devez saisir l'id du secteur";
				}

				return new JsonResponse($response);
			}



	/**
	 * @Route("/compte/rendu/{id}", name="responsable_compte_rendu_show", methods="GET")
	 * @Rest\Get("/compte/rendu/{id}")
	 */
	public function show(CompteRendu $compteRendu): Response
	{
		$view = $this->view($compteRendu, 200)
			->setTemplate("Responsable/compte_rendu/show.html.twig")
			->setTemplateVar('compte_rendu');

		return $this->handleView($view);
	}

	/**
	 * @Route("/vehicule", name="responsable_vehicule_index", methods="GET")
	 */
	public function indexVehicule(VehiculeRepository $vehiculeRepository): Response
	{
		return $this->render('Responsable/vehicule/index.html.twig', ['vehicules' => $vehiculeRepository->findAll()]);
	}

	/**
	 * @Route("/vehicule/new", name="vehicule_new", methods="GET|POST")
	 */
	public function newVehicule(Request $request): Response
	{
		$vehicule = new Vehicule();
		$form = $this->createForm(VehiculeType::class, $vehicule);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($vehicule);
			$em->flush();

			return $this->redirectToRoute('responsable_vehicule_index');
		}

		return $this->render('Responsable/vehicule/new.html.twig', [
			'vehicule' => $vehicule,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/vehicule/{id}", name="vehicule_show", methods="GET")
	 */
	public function showVehicule(Vehicule $vehicule): Response
	{
		return $this->render('Responsable/vehicule/show.html.twig', ['vehicule' => $vehicule]);
	}

	/**
	 * @Route("/{id}/edit", name="vehicule_edit", methods="GET|POST")
	 */
	public function edit(Request $request, Vehicule $vehicule): Response
	{
		$form = $this->createForm(VehiculeType::class, $vehicule);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDoctrine()->getManager()->flush();

			return $this->redirectToRoute('responsable_vehicule_index', ['immatriculation' => $vehicule->getId()]);
		}

		return $this->render('Responsable/vehicule/edit.html.twig', [
			'vehicule' => $vehicule,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/{id}", name="vehicule_delete", methods="DELETE")
	 */
	public function delete(Request $request, Vehicule $vehicule): Response
	{
		if ($this->isCsrfTokenValid('delete'.$vehicule->getId(), $request->request->get('_token'))) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($vehicule);
			$em->flush();
		}

		return $this->redirectToRoute('responsable_vehicule_index');
	}
}