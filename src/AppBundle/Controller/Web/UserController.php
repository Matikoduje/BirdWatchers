<?php

namespace AppBundle\Controller\Web;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
//            $userProfile = new UserProfil();
//            $em->persist($userProfile);
//            $em->flush();
//            $post->setProfilId($userProfile->getId());
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
}
