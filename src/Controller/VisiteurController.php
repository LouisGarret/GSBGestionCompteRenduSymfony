<?php

namespace App\Controller;

use App\Entity\CompteRendu;
use App\Entity\Famille;
use App\Entity\Medicament;
use App\Entity\User;
use App\Entity\Vehicule;
use App\Form\CompteRenduType;
use App\Repository\CompteRenduRepository;
use App\Repository\FamilleRepository;
use App\Repository\VehiculeRepository;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Route("/visiteur")
 */
class VisiteurController extends FOSRestController
{
    /**
     * @Route("/", name="visiteur_index")
     */
    public function index()
    {
        return $this->render('Visiteur/index.html.twig');
    }

    /**
     * @Route("/praticiens", name="visiteur_praticien_index")
     * @Rest\Get("/praticiens")
     */
	public function affichagePraticien()
	{
		$em = $this->getDoctrine()->getManager();
		$praticiens = $em->getRepository('App:Praticien')->findAll();

		$view = $this->view($praticiens, 200)
			->setTemplate("Visiteur/Praticien/praticiens.html.twig")
			->setTemplateVar('praticiens');

		return $this->handleView($view);
	}

    /**
     * @Route("/medicament", name="visiteur_medicament_index", methods="GET")
     * @Rest\Get("/medicament")
     */
    public function affichageMedicament(): Response
    {
        $medicaments = $this->getDoctrine()
            ->getRepository(Medicament::class)
            ->findAllMedicaments();

        $view = $this->view($medicaments, 200)
            ->setTemplate("Visiteur/medicament/index.html.twig")
            ->setTemplateVar('medicaments');

        return $this->handleView($view);
    }


    /**
     * @Route("/api/medicaments", name="visiteur_medicament_index_API", methods="GET")
     * @Rest\Get("/api/medicaments")
     */
    public function affichageMedicamentsAPI(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $medicaments = $em->getRepository('App:Medicament')->findAllMedicaments();

        $response = array();
        foreach ($medicaments as $medicament) {
            $response[] = array(
                'depotLegal' => $medicament->getDepotLegal(),
                'nomCommercial' => $medicament->getNomCommercial(),
                'composition' => $medicament->getComposition(),
                'famille'=> $medicament->getFamille()->getLibelle(),
				'effets'=> $medicament->getEffets(),
				'contreIndic'=> $medicament->getContreIndic(),
			);
        }

        if (count($response) == 0) {
            $retour["success"] = 0;
            $retour["message"] = "Aucun resultat";
        }else{
            $retour["success"] = 1;
            $retour["message"] = count($response)." resultat(s)";
            $retour["medicaments"] = $response;
        }

        return new JsonResponse($retour);
    }


    /**
     * @Route("/medicament/{id}", name="visiteur_medicament_show", methods="GET")
     * @Rest\Get("/mediacament")
     */
    public function detailMedicament(Medicament $medicament): Response
    {
        $view = $this->view($medicament, 200)
            ->setTemplate("Visiteur/medicament/show.html.twig")
            ->setTemplateVar('medicament');

        return $this->handleView($view);
    }

    /**
     * @Route("/famille", name="visiteur_famille_index", methods="GET")
     * @Rest\Get("/famille")
     */
    public function affichageFamille(): Response
    {
        $familles = $this->getDoctrine()
            ->getRepository(Famille::class)
            ->findAll();

        $view = $this->view($familles, 200)
            ->setTemplate("Visiteur/famille/index.html.twig")
            ->setTemplateVar('familles');

        return $this->handleView($view);
    }

    /**
     * @Route("/famille/{id}", name="visiteur_famille_show", methods="GET")
     * @Rest\Get("/famille/{id}")
     */
    public function detailFamille(Famille $famille): Response
    {
        $view = $this->view($famille, 200)
            ->setTemplate("Visiteur/famille/show.html.twig")
            ->setTemplateVar('famille');

        return $this->handleView($view);
    }

    /**
     * @Route("/compte/rendu", name="visiteur_compte_rendu_index", methods="GET")
     * @Rest\Get("/compte/rendu")
     */
    public function getComptesRendus(): Response
    {
        //$responsable = $this->getUser();
        $id = $this->getUser();
        $comptesRendus = $this->getDoctrine()
            ->getRepository(CompteRendu::class)
            ->findByVisiteur($id);

        $view = $this->view($comptesRendus, 200)
            ->setTemplate("Visiteur/compte_rendu/index.html.twig")
            ->setTemplateVar('id')
            ->setTemplateVar('compte_rendus');

        return $this->handleView($view);
    }

	/**
	 * @Rest\Get("/api/compte/rendu")
	 */
	public function affichageCompteRenduAPI(): Response
	{
		$em = $this->getDoctrine()->getManager();
		$compteRendus = $em->getRepository(CompteRendu::class)->findAll();

		$response = array();
		foreach ($compteRendus as $compteRendu) {
			$response[] = array(
				'numero' => $compteRendu->getId(),
				'dateVisite' => $compteRendu->getDateVisite(),
				'dateSaisie' => $compteRendu->getDateSaisie(),
				'coefficient'=> $compteRendu->getCoefficient(),
				'bilan'=> $compteRendu->getBilan(),
				'etatCloture'=> $compteRendu->getCloture(),
				'documentation'=> $compteRendu->getDoc(),
				'remplacant'=> $compteRendu->getRemplacant(),
				'motif'=> $compteRendu->getMotif(),
				'produits'=> $compteRendu->getProduits()->getValues(),
				'echantillons'=> $compteRendu->getEchantillons()
			);
		}

		if (count($response) == 0) {
			$retour["success"] = 0;
			$retour["message"] = "Aucun resultat";
		}else{
			$retour["success"] = 1;
			$retour["message"] = count($response)." resultat(s)";
			$retour["medicaments"] = $response;
		}

		return new JsonResponse($retour);
	}

	/**
	 * @Rest\Get("/api/compte/rendu")
	 */
	public function affichageCompteRenduParPraticiensAPI(): Response
	{

		$em = $this->getDoctrine()->getManager();
		$compteRendus = $em->getRepository(CompteRendu::class)->findAll();

		$response = array();
		foreach ($compteRendus as $compteRendu) {
			$response[] = array(
				'numero' => $compteRendu->getId(),
				'dateVisite' => $compteRendu->getDateVisite(),
				'dateSaisie' => $compteRendu->getDateSaisie(),
				'coefficient'=> $compteRendu->getCoefficient(),
				'bilan'=> $compteRendu->getBilan(),
				'etatCloture'=> $compteRendu->getCloture(),
				'documentation'=> $compteRendu->getDoc(),
				'remplacant'=> $compteRendu->getRemplacant(),
				'motif'=> $compteRendu->getMotif(),
				'produits'=> $compteRendu->getProduits()->getValues(),
				'echantillons'=> $compteRendu->getEchantillons(),
				'vehicule' => $compteRendu->getVehicule()->getId()
			);
		}

		if (count($response) == 0) {
			$retour["success"] = 0;
			$retour["message"] = "Aucun resultat";
		}else{
			$retour["success"] = 1;
			$retour["message"] = count($response)." resultat(s)";
			$retour["medicaments"] = $response;
		}

		return new JsonResponse($retour);
	}

    /**
     * @Route("/compte/rendu/new", name="visiteur_compte_rendu_new", methods="GET|POST")
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

            return $this->redirectToRoute('visiteur_compte_rendu_index');
        }

        return $this->render('Visiteur/compte_rendu/new.html.twig', [
            'compte_rendu' => $compteRendu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/compte/rendu/{id}", name="visiteur_compte_rendu_show", methods="GET")
     * @Rest\Get("/compte/rendu/{id}")
     */
    public function show(CompteRendu $compteRendu): Response
    {
        $view = $this->view($compteRendu, 200)
            ->setTemplate("Visiteur/compte_rendu/show.html.twig")
            ->setTemplateVar('compte_rendu');

        return $this->handleView($view);
    }

    /**
     * @Route("/compte/rendu/{id}/edit", name="visiteur_compte_rendu_edit", methods="GET|POST")
     * @Rest\Get("/compte/rendu/{id}/edit")
     */
    public function edit(Request $request, CompteRendu $compteRendu): Response
    {
        $form = $this->createForm(CompteRenduType::class, $compteRendu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('visiteur_compte_rendu_index', ['id' => $compteRendu->getId()]);
        }

        return $this->render('Visiteur/compte_rendu/edit.html.twig', [
            'compte_rendu' => $compteRendu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/compte/rendu/{id}", name="visiteur_compte_rendu_delete", methods="DELETE")
     * @Rest\Get("/compte/rendu/{id}")
     */
    public function delete(Request $request, CompteRendu $compteRendu): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compteRendu->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($compteRendu);
            $em->flush();
        }

        return $this->redirectToRoute('visiteur_compte_rendu_index');
    }

	/**
	 * @Route("/vehicule", name="visiteur_vehicule_index", methods="GET")
	 */
	public function indexVehicule(VehiculeRepository $vehiculeRepository): Response
	{
		return $this->render('Visiteur/vehicule/index.html.twig', ['vehicules' => $vehiculeRepository->findAll()]);
	}

	/**
	 * @Route("/vehicule/{id}", name="visiteur_vehicule_show", methods="GET")
	 */
	public function showVehicule(Vehicule $vehicule): Response
	{
		return $this->render('Visiteur/vehicule/show.html.twig', ['vehicule' => $vehicule]);
	}
}