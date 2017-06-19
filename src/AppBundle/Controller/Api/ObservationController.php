<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Image;
use AppBundle\Entity\Observation;
use AppBundle\Form\ObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Filesystem\Filesystem;
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
            return $this->render('@App/Error/error.html.twig', array(
               'message' => sprintf(
                   'Obserwacja o id "%d" nie istnieje w bazie',
                   $id)
            ));
        }

        $data = $this->get('app.observation_serializer')->serializeObservation($observation);
        $response = new JsonResponse($data, Response::HTTP_OK);
        return $response;
    }

    /**
     * @Route("/api/observation")
     * @Method("GET")
     */
    public function listObservationsAction()
    {
        $observationRepository = $this->getDoctrine()
            ->getRepository('AppBundle:Observation');
        $observations = $observationRepository->findAll();
        $observationsCounts = $observationRepository->countAllObservations();
        $data = $this->get('app.observation_serializer')
            ->serializeObservationsMain($observations, $observationsCounts);
        $response = new JsonResponse($data, Response::HTTP_OK);
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
        $data = $this->get('app.observation_serializer')
            ->serializeObservationsMain($observations);
        $response = new JsonResponse($data, Response::HTTP_OK);
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
            return $this->render('@App/Error/error.html.twig', array(
                'message' => sprintf(
                    'Obserwacja o id "%d" nie istnieje w bazie',
                    $id)
            ));
        }

        if ($observation) {
            if ($observation->getUser() === $this->getUser()) {
                $em = $this->getDoctrine()->getManager();
                $images = $observation->getImages();
                foreach ($images as $image) {
                    $em->remove($image);
                }
                $em->remove($observation);
                $em->flush();
            }
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
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
            $observationsCounts = $repositoryObservation->countFindByParameters($user, $species, $time);
            $data = $this->get('app.observation_serializer')
                ->serializeObservationsMain($observations, $observationsCounts);
        }

        $response = new JsonResponse($data, Response::HTTP_OK);
        return $response;

    }
}
