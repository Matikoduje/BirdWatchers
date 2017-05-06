<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Observation;
use AppBundle\Form\ObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class ObservationController extends Controller
{
    /**
     * @Route("/api/observation")
     * @Method("POST")
     */
    public function indexAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $observation = new Observation();
        $form = $this->createForm(ObservationType::class, $observation);
        $form->submit($data);
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneByLogin('admiini');
        $species = $em->getRepository('AppBundle:Species')->find(1);
        $observation->setUser($user);
        $observation->setSpecies($species);
        $em->persist($observation);
        $em->flush();
        $data = $this->serializeObservation($observation);
        $response = new JsonResponse($data, 201);
        $observationUrl = $this->generateUrl(
            'apiObservationShow',
            ['id' => $observation->getId()]
        );
        $response->headers->set('Location', $observationUrl);
        return $response;
    }

    /**
     * @Route("/api/observation/{id}", name="apiObservationShow")
     * @Method("GET")
     */
    public function showObservationAction($id)
    {
        $observation = $this->getDoctrine()
            ->getRepository('AppBundle:Observation')
            ->find($id);

        if (!$observation) {
            throw $this->createNotFoundException(sprintf(
                'Obserwacja o id "%d" nie istnieje w bazie',
                $id
            ));
        }

        $data = $this->serializeObservation($observation);
        return new JsonResponse($data, 200);
    }

    /**
     * @Route("/api/observation")
     * @Method("GET")
     */
    public function listObservationsAction()
    {
        $observations = $this->getDoctrine()
            ->getRepository('AppBundle:Observation')
            ->findAll();
        $data = array('observations' => array());
        foreach ($observations as $observation) {
            $data['observations'][] = $this->serializeObservation($observation);
        }
        return new JsonResponse($data, 200);
    }

    private function serializeObservation(Observation $observation)
    {
        return array(
            'userName' => $observation->getUser()->getLogin(),
            'speciesName' => $observation->getSpecies()->getName(),
            'observationDate' => $observation->getObservationDate(),
            'description' => $observation->getDescription()
        );
    }
}
