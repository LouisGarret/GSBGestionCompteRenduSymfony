<?php

namespace App\Controller\Api;

use App\Entity\CompteRendu;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Route("/auth")
 */
class ApiAuthController extends FOSRestController
{
    /**
     * @Route("/register", name="api_auth_register",  methods={"POST"})
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function register(Request $request, UserManagerInterface $userManager)
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $validator = Validation::createValidator();

        $constraint = new Assert\Collection(array(
// the keys correspond to the keys in the input array
            'username' => new Assert\Length(array('min' => 1)),
            'password' => new Assert\Length(array('min' => 1)),
            'email' => new Assert\Email(),
        ));

        $violations = $validator->validate($data, $constraint);

        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], 500);
        }

        $username = $data['username'];
        $password = $data['password'];
        $email = $data['email'];

        $user = new User();

        $user
            ->setUsername($username)
            ->setPlainPassword($password)
            ->setEmail($email)
            ->setEnabled(true)
            ->setRoles(['ROLE_USER'])
            ->setSuperAdmin(false)
        ;

        try {
            $userManager->updateUser($user, true);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }

        return new JsonResponse(["success" => $user->getUsername(). " has been registered!"], 200);
    }

    /**
     * @Route("/compte/rendu", name="compte_rendu_api", methods="GET")
     */
    public function getComptesRendusAPI(): Response
    {
        $comptesRendus = $this->getDoctrine()
            ->getRepository(CompteRendu::class)
            ->findAll();

        $view = $this->view($comptesRendus, 200)
            ->setTemplate("Responsable/compte_rendu/index.html.twig")
            ->setTemplateVar('compte_rendus');

        return $this->handleView($view);

        /*return $this->render('Responsable/compte_rendu/index.html.twig',
            [
                'compte_rendus' => $comptesRendus,
                'secteur' => $secteur,
                'responsable' => $responsable
            ]);*/
    }
}