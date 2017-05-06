<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Observation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ObservationController extends Controller
{
    /**
     * @Route("/api/observation")
     * @Method("POST")
     */
    public function indexAction(Request $request)
    {
//        $data = json_decode($request->getContent(), true);
//        $observation = new Observation();
//        $observation->setState($data['state']);
//        $observation->setCoordinates($data['coordinates']);
//        $observation->setDescription($data['description']);
//        $observation->setObservationDate(date_create(date('Y-m-d')));
//        $observation->setImages($data['images']);
//        $observation->setLocation($data['location']);
//        $em = $this->getDoctrine()->getManager();
//        $user = $em->getRepository('AppBundle:User')->findOneByLogin('admin');
//        $observation->setUserId($user);
//        $em->persist($observation);
//        $em->flush();
//        return new Response('It worked. Believe me - I\'m an API');
    }
}
