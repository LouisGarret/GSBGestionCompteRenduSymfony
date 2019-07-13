<?php

namespace App\Controller;

use App\Entity\Praticien;
use App\Form\PraticienType;
use App\Repository\PraticienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/praticien")
 */
class PraticienController extends AbstractController
{
    /**
     * @Route("/", name="praticien_index", methods="GET")
     */
    public function index(PraticienRepository $praticienRepository): Response
    {
        return $this->render('praticien/index.html.twig', ['praticiens' => $praticienRepository->findAll()]);
    }

    /**
     * @Route("/new", name="praticien_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $praticien = new Praticien();
        $form = $this->createForm(PraticienType::class, $praticien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($praticien);
            $em->flush();

            return $this->redirectToRoute('praticien_index');
        }

        return $this->render('praticien/new.html.twig', [
            'praticien' => $praticien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="praticien_show", methods="GET")
     */
    public function show(Praticien $praticien): Response
    {
        return $this->render('praticien/show.html.twig', ['praticien' => $praticien]);
    }

    /**
     * @Route("/{id}/edit", name="praticien_edit", methods="GET|POST")
     */
    public function edit(Request $request, Praticien $praticien): Response
    {
        $form = $this->createForm(PraticienType::class, $praticien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('praticien_index', ['id' => $praticien->getId()]);
        }

        return $this->render('praticien/edit.html.twig', [
            'praticien' => $praticien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="praticien_delete", methods="DELETE")
     */
    public function delete(Request $request, Praticien $praticien): Response
    {
        if ($this->isCsrfTokenValid('delete'.$praticien->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($praticien);
            $em->flush();
        }

        return $this->redirectToRoute('praticien_index');
    }
}
