<?php

namespace AppBundle\Controller\Web;

use AppBundle\Form\ObservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class WebController extends Controller
{
    /**
     * @Route("/",
     *     name="main")
     */
    public function mainPageAction()
    {
        return $this->render('AppBundle:WebController:main.html.twig');
    }

    /**
     * @Route("/aboutProject",
     *     name="project")
     */
    public function aboutProjectAction()
    {
        return $this->render('AppBundle:WebController:about.html.twig', array(
        ));
    }

    /**
     * @Route("/map",
     *     name="map")
     */
    public function showMapAction()
    {
        $form = $this->createForm(ObservationType::class, array(
            'action' => $this->generateUrl('addObservation'),
            'method' => 'POST'
        ));
        return $this->render('AppBundle:WebController:map.html.twig', array(
            'form' => $form->createView()
        ));
    }
}