<?php

namespace App\Controller;

use App\Entity\Famille;
use App\Form\FamilleType;
use App\Repository\FamilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/famille")
 */
class FamilleController extends AbstractController
{
    /**
     * @Route("/", name="famille_index", methods="GET")
     */
    public function index(FamilleRepository $familleRepository): Response
    {
        return $this->render('famille/index.html.twig', ['familles' => $familleRepository->findAll()]);
    }

    /**
     * @Route("/new", name="famille_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $famille = new Famille();
        $form = $this->createForm(FamilleType::class, $famille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($famille);
            $em->flush();

            return $this->redirectToRoute('famille_index');
        }

        return $this->render('famille/new.html.twig', [
            'famille' => $famille,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="famille_show", methods="GET")
     */
    public function show(Famille $famille): Response
    {
        return $this->render('famille/show.html.twig', ['famille' => $famille]);
    }

    /**
     * @Route("/{id}/edit", name="famille_edit", methods="GET|POST")
     */
    public function edit(Request $request, Famille $famille): Response
    {
        $form = $this->createForm(FamilleType::class, $famille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('famille_index', ['id' => $famille->getId()]);
        }

        return $this->render('famille/edit.html.twig', [
            'famille' => $famille,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="famille_delete", methods="DELETE")
     */
    public function delete(Request $request, Famille $famille): Response
    {
        if ($this->isCsrfTokenValid('delete'.$famille->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($famille);
            $em->flush();
        }

        return $this->redirectToRoute('famille_index');
    }
}
