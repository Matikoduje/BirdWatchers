<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Image;
use AppBundle\Entity\Observation;
use AppBundle\Form\ObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class ObservationController extends Controller
{

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
     * @Route("/api/myObservations")
     * @Method("GET")
     */
    public function myObservationsAction()
    {
        $user = $this->getUser();
        $observations = $this->getDoctrine()
            ->getRepository('AppBundle:Observation')
            ->findsAllByUser($user->getId());
        $data = array('observations' => array());
        foreach ($observations as $observation) {
            $data['observations'][] = $this->serializeObservations($observation);
        }
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

    private function serializeObservation(Observation $observation)
    {
        $images = $observation->getImages();
        $imgPath = array();
        foreach ($images as $image) {
            $imgPath[] = $image->getPath() . $image->getName();
        }
        return array(
            'longitude' => $observation->getLongitude(),
            'latitude' => $observation->getLatitude(),
            'description' => $observation->getDescription(),
            'userName' => $observation->getUser()->getLogin(),
            'dateCreate' => $observation->getCreatedAt()->format('Y-m-d'),
            'dateO' => $observation->getObservationDate()->format('Y-m-d'),
            'species' => $observation->getSpecies()->getName(),
            'state' => $observation->getState()->getName(),
            'location' => $observation->getLocation(),
            'images' => $imgPath
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
            'location' => $observation->getLocation(),
        );
    }

    /**
     * @Route("api/searchUser", name="searchUser")
     * @Method("GET")
     */
    public function searchUserAction(Request $request)
    {

        $userLogin = $request->query->get('login');
        $species = $request->query->get('species');
        $time = $request->query->get('time');
        $isUserExist = false;

        $repositoryUser = $this->getDoctrine()->getRepository('AppBundle:User');
        $repositoryObservation = $this->getDoctrine()->getRepository('AppBundle:Observation');
        $repositorySpecies = $this->getDoctrine()->getRepository('AppBundle:Species');

        if ($userLogin === 'all') {
            $isUserExist = true;
            $user = $userLogin;
        } else {
            $user = $repositoryUser->findOneByLogin($userLogin);
            if ($user) {
                $isUserExist = true;
            } else {
                $data = array(
                    'message' => 'badUser'
                );
            }
        }

        if ($species !== 'all') {
            $species = $repositorySpecies->find($species);
        }

        if ($isUserExist) {
            $observations = $repositoryObservation->findByParameters($user, $species, $time);
            $data = array('observations' => array());
            foreach ($observations as $observation) {
                $data['observations'][] = $this->serializeObservations($observation);
            }
        }
        $response = new JsonResponse($data, 200);
        return $response;

    }
}
