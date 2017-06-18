<?php

namespace AppBundle\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class WebController extends Controller
{
    /**
     * @Route("/", name="main")
     */
    public function mainPageAction()
    {
        return $this->render('AppBundle:WebController:main.html.twig');
    }

    /**
     * @Route("/aboutProject", name="project")
     */
    public function aboutProjectAction()
    {
        return $this->render('AppBundle:WebController:about.html.twig', array(
        ));
    }

}