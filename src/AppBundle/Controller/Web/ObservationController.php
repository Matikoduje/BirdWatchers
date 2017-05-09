<?php

namespace AppBundle\Controller\Web;

use AppBundle\Form\ObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class ObservationController extends Controller
{
    /**
     * @Route("/observation",
     *     name="map")
     */
    public function showMapAction()
    {
        return $this->render('AppBundle:WebController:map.html.twig', array(
        ));
    }

    /**
     * @Route("/observation/{id}",
     *     name="observation")
     */
    public function showObservationAction($id)
    {
        return $this->render('AppBundle:WebController:observation.html.twig', array(
            'id' => $id
        ));
    }

    /**
     * @Route("/addObservation",
     *     name="addMap")
     */
    public function addMapAction()
    {
        $form = $this->createForm(ObservationType::class);
        return $this->render('AppBundle:WebController:add.html.twig', array(
            'form' => $form->createView()
        ));
    }
}