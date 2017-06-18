<?php

namespace AppBundle\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class SpeciesController extends Controller
{
    /**
     * @Route("/species/{id}", name="species")
     */
    public function speciesShowAction($id)
    {
        $species = $this->getDoctrine()->getRepository('AppBundle:Species')
            ->find($id);

        $observationRepository = $this->getDoctrine()->getRepository('AppBundle:Observation');
        $count = $observationRepository->countSpeciesObservations($id);

        return $this->render('AppBundle:Species:showSpecies.html.twig', array(
            'species' => $species,
            'countSpeciesObservation' => $count
        ));
    }
}