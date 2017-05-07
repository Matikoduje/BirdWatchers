<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Observation;
use AppBundle\Form\ObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ObservationController extends Controller
{
    /**
     * @Route("/api/observation",
     *     name="addObservation")
     * @Method("POST")
     */
    public function indexAction(Request $request)
    {
//        $observation = new Observation();
//        $form = $this->createForm(ObservationType::class, $observation);
//        $this->processForm($request, $form);
//        $em = $this->getDoctrine()->getManager();
//        $user = $em->getRepository('AppBundle:User')->findOneByLogin('admin');
//        $observation->setUser($user);
//        $em->persist($observation);
//        $em->flush();
//        $data = $this->serializeObservation($observation);
//        $response = new JsonResponse($data, 201);
//        $observationUrl = $this->generateUrl(
//            'apiObservationShow',
//            ['id' => $observation->getId()]
//        );
//        $response->headers->set('Location', $observationUrl);
//        return $response;
        $data = array(
            'dada' => 'lalal'
        );
        $response = new JsonResponse($data, 201);
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
        $response = new JsonResponse($data, 200);
        return $response;
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
        $response = new JsonResponse($data, 200);
        return $response;
    }

    /**
     * @Route("/api/observation/{id}")
     * @Method("PUT, PATCH")
     */
    public function updateAction($id, Request $request)
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

        $form = $this->createForm(ObservationType::class, $observation);
        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($observation);
        $em->flush();

        $data = $this->serializeObservation($observation);
        $response = new JsonResponse($data, 200);
        return $response;
    }

    /**
     * @Route("/api/observation/{id}")
     * @Method("DELETE")
     */
    public function deleteAction($id)
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

        if ($observation) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($observation);
            $em->flush();
        }

        return new Response(null, 204);
    }

    private function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    private function serializeObservation(Observation $observation)
    {
        return array(
            'longitude' => $observation->getLongitude(),
            'latitude' => $observation->getLatitude(),
            'description' => $observation->getDescription()
        );
    }

    private function serializeObservations(Observation $observation)
    {
        return array(
            'longitude' => $observation->getLongitude(),
            'latitude' => $observation->getLatitude(),
            'description' => $observation->getDescription()
        );
    }
}
