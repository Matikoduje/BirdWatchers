<?php

namespace AppBundle\Controller\Web;

use AppBundle\Entity\User;
use AppBundle\Entity\UserProfile;
use AppBundle\Form\UserProfileType;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/register",
     *     name="addUser")
     */
    public function createUserAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $userProfile = new UserProfile();
            $em->persist($userProfile);
            $post->setUserProfile($userProfile);
            $em->persist($post);
            $em->flush();

            // automatyczne logowanie po poprawnym zarejestrowaniu
            return $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get('app.security.login_form_authenticator'),
                    'main'
                );
        }
        return $this->render('AppBundle:UserController:addUser.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/updateProfile",
     *     name="updateProfile")
     * @Security("is_granted('ROLE_USER')")
     */
    public function updateUserProfileAction(Request $request)
    {
        $userProfile = $this->getUser()->getUserProfile();
        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
        }

        return $this->render('AppBundle:UserController:addUser.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
