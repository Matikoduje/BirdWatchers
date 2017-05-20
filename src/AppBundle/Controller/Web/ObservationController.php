<?php

namespace AppBundle\Controller\Web;

use AppBundle\Entity\Image;
use AppBundle\Entity\Observation;
use AppBundle\Form\ObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
        $species = $this->getDoctrine()->getRepository('AppBundle:Species')
            ->findAll();
        return $this->render('AppBundle:WebController:map.html.twig', array(
            'species' => $species
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
    public function addMapAction(Request $request)
    {
        $observation = new Observation();
        $form = $this->createForm(ObservationType::class, $observation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $images = $request->files->get('observation');
            $images = $images['images'];
            $countImages = 0;
            foreach ($images as $image) {
                $countImages++;
            }
            if ($countImages > 3) {
                return $this->render('AppBundle:WebController:add.html.twig', array(
                    'form' => $form->createView(),
                    'message' => 'Maksymalnie za jednym razem można dodać tylko 3 zdjęcia'
                ));
            }
            foreach ($images as $image) {
                $picture = new Image();
                $picture->setSpecies($post->getSpecies());
                $imageName = $this->get('app.image_uploader')->upload($image);
                $picture->setName($imageName);
                $imagePath = $this->get('app.image_uploader')->getTargetDir();
                $picture->setPath((substr($imagePath,-11)) . '/');
                $picture->setObservation($post);
                $em->persist($picture);
                $post->addImage($picture);
            }
            $post->setUser($this->getUser());
            $em->persist($post);
            $em->flush();
            return $this->redirect($this->generateUrl('observation', array('id' => $post->getId())));
        }
        return $this->render('AppBundle:WebController:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/userObservations", name="userObservations")
     */
    public function showUserObservations()
    {
        return $this->render('@App/WebController/userObservation.html.twig', array(
        ));
    }
}