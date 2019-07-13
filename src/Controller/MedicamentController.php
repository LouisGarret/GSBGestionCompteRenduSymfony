<?php

namespace App\Controller;

use App\Entity\Medicament;
use App\Form\MedicamentType;
use App\Repository\MedicamentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/medicament")
 */
class MedicamentController extends AbstractController
{
    /**
     * @Route("/", name="medicament_index", methods="GET")
     */
    public function index(MedicamentRepository $medicamentRepository): Response
    {
        return $this->render('medicament/index.html.twig', ['medicaments' => $medicamentRepository->findAll()]);
    }

    /**
     * @Route("/new", name="medicament_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $medicament = new Medicament();
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($medicament);
            $em->flush();

            return $this->redirectToRoute('medicament_index');
        }

        return $this->render('medicament/new.html.twig', [
            'medicament' => $medicament,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="medicament_show", methods="GET")
     */
    public function show(Medicament $medicament): Response
    {
        return $this->render('medicament/show.html.twig', ['medicament' => $medicament]);
    }

    /**
     * @Route("/{id}/edit", name="medicament_edit", methods="GET|POST")
     */
    public function edit(Request $request, Medicament $medicament): Response
    {
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('medicament_index', ['id' => $medicament->getId()]);
        }

        return $this->render('medicament/edit.html.twig', [
            'medicament' => $medicament,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="medicament_delete", methods="DELETE")
     */
    public function delete(Request $request, Medicament $medicament): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medicament->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($medicament);
            $em->flush();
        }

        return $this->redirectToRoute('medicament_index');
    }
}
