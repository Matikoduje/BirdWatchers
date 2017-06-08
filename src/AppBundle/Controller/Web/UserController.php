<?php

namespace AppBundle\Controller\Web;

use AppBundle\Entity\User;
use AppBundle\Entity\UserProfile;
use AppBundle\Form\ChangeEmailType;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\UserProfileType;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/register/",
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
        if ($userProfile->getProfilePicture() != '') {
            $path = $userProfile->getPath().$userProfile->getProfilePicture();
        }
        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            if ($post->getUploadFile()) {
                $file = $post->getUploadFile();
                $filename = $this->get('app.image_uploader')->upload($file);
                $filePath = $this->get('app.image_uploader')->getTargetDir();
                $post->setProfilePicture($filename);
                $post->setPath((substr($filePath, -11).'/'));
            }
            $post->setUploadFile(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
        }

        return $this->render('AppBundle:UserController:addUser.html.twig', array(
            'form' => $form->createView(),
            'path' => $path
        ));
    }

    /**
     * @Route("/changeUserData",
     *     name="changeUserData")
     * @Security("is_granted('ROLE_USER')")
     */
    public function changeUserInformationAction(Request $request)
    {
        $user = $this->getUser();
        $formEmail = $this->createForm(ChangeEmailType::class, $user);
        $formPassword = $this->createForm(ChangePasswordType::class, $user);
        $formEmail->handleRequest($request);
        $formPassword->handleRequest($request);
        if ($formEmail->isSubmitted() && $formEmail->isValid()) {
            $post = $formEmail->getData();
            $em = $this->getDoctrine()->getManager();
            $em->merge($post);
            $em->flush();
        }
        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $post = $formPassword->getData();
            $em = $this->getDoctrine()->getManager();
            $em->merge($post);
            $em->flush();
        }
        return $this->render('AppBundle:UserController:changeUser.html.twig', array(
            'formEmail' => $formEmail->createView(),
            'formPassword' => $formPassword->createView()
        ));
    }

    /**
     * @Route("/showUser/{login}",
     *     name="showUser")
     * @Security("is_granted('ROLE_USER')")
     */
    public function showUserInformation($login)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')
            ->findOneByLogin($login);
        $count = $this->getDoctrine()->getRepository('AppBundle:Observation')
            ->countUserObservations($user->getId());
        return $this->render('AppBundle:UserController:infoUser.html.twig', array(
            'login' => $user->getLogin(),
            'name' => $user->getUserProfile()->getName(),
            'surname' => $user->getUserProfile()->getSurname(),
            'city' => $user->getUserProfile()->getCity(),
            'state' => $user->getUserProfile()->getState()->getName(),
            'count' => $count,
            'path' => $user->getUserProfile()->getPath() . $user->getUserProfile()->getProfilePicture()
        ));
    }
}
