<?php

namespace App\Controller;

use App\Entity\CompteRendu;
use App\Entity\Famille;
use App\Entity\Medicament;
use App\Entity\User;
use App\Form\ProfileType;
use App\Form\RegistrationType;
use App\Repository\CompteRenduRepository;
use App\Repository\FamilleRepository;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\FOSRestController;
use PhpParser\Node\Expr\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Route("/praticien")
 */
class PraticienController extends FOSRestController
{

    /**
     * @Route("/", name="praticien_index")
     */
    public function index()
    {
        $view = $this->view($this,200)
            ->setTemplate("Praticien/index.html.twig");

        return $this->handleView($view);
        //return $this->render('Praticien/index.html.twig');
    }

    /**
     * @Route("/medicament", name="praticien_medicament_index", methods="GET")
     * @Rest\Get("/medicament")
     */
    public function affichageMedicament(): Response
    {
        $id = $this->getUser();
        $medicaments = $this->getDoctrine()
            ->getRepository(Medicament::class)
            ->findByPraticiens($id);

        $view = $this->view($medicaments, 200)
            ->setTemplate("Praticien/medicament/index.html.twig")
            ->setTemplateVar('medicaments');

        return $this->handleView($view);
    }

    /**
     * @Route("/medicament/{id}", name="praticien_medicament_detail", methods="GET")
     * @Rest\Get("/medicament/{id}")
     */
    public function detailMedicament(Medicament $medicament): Response
    {
        $view = $this->view($medicament, 200)
            ->setTemplate("Praticien/medicament/show.html.twig")
            ->setTemplateVar('medicament');

        return $this->handleView($view);
    }

    /**
     * @Route("/famille", name="praticien_famille_index", methods="GET")
     * @Rest\Get("/famille")
     */
    public function affichageFamille(): Response
    {
        $familles = $this->getDoctrine()
            ->getRepository(Famille::class)
            ->findAll();

        $view = $this->view($familles, 200)
            ->setTemplate("Praticien/famille/index.html.twig")
            ->setTemplateVar('familles');

        return $this->handleView($view);
    }

    /**
     * @Route("/famille/{id}", name="praticien_famille_detail", methods="GET")*
     * @Rest\Get("/famille/{id}")
     */
    public function detailFamille(Famille $famille): Response
    {
        $view = $this->view($famille, 200)
            ->setTemplate("Praticien/famille/show.html.twig")
            ->setTemplateVar('famille');

        return $this->handleView($view);
    }

    /**
     * @Route("/compte/rendu", name="praticien_compte_rendu_index", methods="GET")
     * @Rest\Get("/compte/rendu")
     */
    public function getComptesRendusSecteur(): Response
    {
        //$responsable = $this->getUser();
        $id = $this->getUser();
        $comptesRendus = $this->getDoctrine()
            ->getRepository(CompteRendu::class)
            ->findByVisiteur($id);

        $view = $this->view($comptesRendus, 200)
            ->setTemplate("Praticien/compte_rendu/index.html.twig")
            ->setTemplateVar('id')
            ->setTemplateVar('compte_rendus');

        return $this->handleView($view);
    }

    /**
     * @Route("/compte/rendu/{id}", name="praticien_compte_rendu_show", methods="GET")
     * @Rest\Get("/compte/rendu/{id}")
     */
    public function show(CompteRendu $compteRendu): Response
    {
        $view = $this->view($compteRendu, 200)
            ->setTemplate("Praticien/compte_rendu/show.html.twig")
            ->setTemplateVar('compte_rendu');

        return $this->handleView($view);
    }
}