<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Image;
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
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->get('observation');
        $observation = new Observation();
        $observation->setUser($this->getUser());
        $observation->setLocation($data['location']);
        $observation->setState($data['state']);
        $observation->setLatitude($data['latitude']);
        $observation->setLongitude($data['longitude']);
        $observation->setDescription($data['description']);
        $observedSpecies = $this->getDoctrine()
            ->getRepository('AppBundle:Species')
            ->find($data['species']);
        $observation->setSpecies($observedSpecies);
        $files = $request->files->all();
        foreach ($files as $file) {
            $filename = $this->get('app.image_uploader')->upload($file);
            $image = new Image();
            $image->setSpecies($observedSpecies);
            $image->setObservation($observation);
            $image->setName($filename);
            $em->persist($image);
        }
        $em->persist($observation);
        $em->flush();
        $data = array(
            'message' => 'OK'
        );
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
            $data['observations'][] = $this->serializeObservations($observation);
        }
        $response = new JsonResponse($data, 200);
        return $response;
    }

    /**
     * @Route("/api/observation/{id}")
     * @Method("PUT")
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
//        $clearMissing = $request->getMethod() != 'PATCH';
//        $form->submit($data, $clearMissing);
        $form->submit($data);
    }

    private function serializeObservation(Observation $observation)
    {
        return array(
            'longitude' => $observation->getLongitude(),
            'latitude' => $observation->getLatitude(),
            'description' => $observation->getDescription(),
            'userName' => $observation->getUser()->getLogin(),
            'dateCreate' => $observation->getCreatedAt()->format('Y-m-d'),
            'dateO' => $observation->getObservationDate()->format('Y-m-d'),
            'species' => $observation->getSpecies()->getName(),
            'state' => $observation->getState(),
            'location' => $observation->getLocation(),
            'description' => $observation->getDescription(),
        );
    }

    private function serializeObservations(Observation $observation)
    {
        return array(
            'longitude' => $observation->getLongitude(),
            'latitude' => $observation->getLatitude(),
            'species' => $observation->getSpecies()->getName(),
            'id' => $observation->getId(),
            'dateO' => $observation->getObservationDate()->format('Y-m-d'),
        );
    }
}
