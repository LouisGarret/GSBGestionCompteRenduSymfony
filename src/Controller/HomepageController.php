<?php

namespace App\Controller;

use App\Repository\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{

    /**
     * @Route("/", name="redirection")
     */
    public function redirection()
    {
        $user = $this->getUser();

        if ($user == null) return $this->render('Accueil/index.html.twig');

        if($user->hasRole('ROLE_PRATICIEN')){
            return $this->redirectToRoute('praticien_index');
        }elseif($user->hasRole('ROLE_VISITEUR')){
            return $this->redirectToRoute('visiteur_index');
        }elseif($user->hasRole('ROLE_RESP_SECTEUR')){
            return $this->redirectToRoute('responsable_index');
        }elseif($user->hasRole('ROLE_DELEG_REGIONAL')) {
            return $this->redirectToRoute('delegue_index');
        }elseif($user->hasRole('ROLE_ADMIN')) {
        	return $this->redirectToRoute('easyadmin');
		}
        return $this->render('Accueil/index.html.twig');
    }
}