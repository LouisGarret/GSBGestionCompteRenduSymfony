<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Medicament;
use App\Form\ProfileType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin")
 */
class AdministrateurController extends AbstractController
{
    /**
     * @Route("/user", name="consultation_user")
     */
    public function consulterUser()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('App:User')->findAll();

        return $this->render('Administrateur/praticiens.html.twig', [
            'users' => $users,
        ]);
    }
    /**
     * @Route("/{id}/delete", name="consultation_user_delete")
     */
    public function delete(User $user)
    {
        if($user !== null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }
        return $this->redirectToRoute('consultation_praticiens');
    }
    /**
     * @Route("/{id}/edit", name="consultation_user_edit")
     */
    public function edit(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('App:User')->find($id);

        if($user === null){
            $this->get('session')->getFlashBag()->add('danger', 'Does Not Exist');
            return $this->redirectToRoute('consultation_praticiens');
        }
        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('consultation_praticiens');
        }

        return $this->render('security/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/api/login", name="api_login")
     */
    public function connexionAction(){
        if (isset($_GET['username'])) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('App:User')->findOneBy(array('username' => $_GET['username']));

            if ($user != null) {
                $response["success"] = 1;
                $response["message"] = "Connexion Ok";
                $response["utilisateur"] = array(
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'nom' => $user->getLastName(),
                    'prenom'  => $user->getFirstName(),
					'region' => $user->getRegion()->getLibelle(),
					'secteur' => $user->getRegion()->getSecteur()->getLibelle(),
					'role' => $user->getRoles()
                );
            }else{
                $response["success"] = 0;
                $response["message"] = "Ces informations ne correspondent a aucun utilisateur";
            }
        }else{
            $response["success"] = 0;
            $response["message"] = "Vous devez saisir le nom d'utilisateur et le mot de passe";
        }

        return new JsonResponse($response);
    }
}