<?php

namespace AppBundle\Controller\Web;


use AppBundle\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // jeśli będzie jakiś błąd przy autoryzacji to zapisałem go do zmiennej error
        $error = $authenticationUtils->getLastAuthenticationError();

        // ostatni login wpisywany przez użytkownika
        $lastLogin = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginType::class, array(
            'login' => $lastLogin,
        ));

        return $this->render('AppBundle:Security:login.html.twig', array(
            'form' => $form->createView(),
            'error' => $error,
        ));
    }
}