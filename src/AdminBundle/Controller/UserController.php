<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function getUsersAction(Request $request)
    {
        if ($request->query->get('error')) {
            $error = $request->query->get('error');
        }
        $objUsers = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();

        return $this->render(
            'Admin\User\users.html.twig',
            [
                'objUsers' => $objUsers,
                'error' => $error ?? NULL,
                'success' => $request->get('success')
            ]
        );
    }

    public function getUserReadAction($userId)
    {
        $objUser = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userId);

        return $this->render(
            'Admin\User\form.html.twig',
            [
                'objUser' => $objUser,
                'isEditable' => FALSE,
                'isNew' => FALSE,
                'error' => NULL,
                'success' => NULL,
            ]
        );
    }

    public function getUserEditAction($userId)
    {
        $objUser = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userId);

        return $this->render(
            'Admin\User\form.html.twig',
            [
                'objUser' => $objUser,
                'isEditable' => TRUE,
                'isNew' => FALSE,
                'error' => NULL,
                'success' => NULL,
            ]
        );
    }

    public function postUserEditAction($userId, Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'inputEmail' => 'required',
                'inputPhoneNumber' => 'required',
                'inputOfficialAddress' => 'required',
                'inputCurrentLocation' => 'required',
                'inputBio' => 'required',
            ],
            $request
        );

        $error = NULL;
        $success = TRUE;

        if ($validator['hasError']) {
            $error = $validator;
            $success = FALSE;
        }

        $objUser = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userId);

        if (!$error) {
            $entityManager = $this->getDoctrine()->getManager();

            $objUser->setEmail($request->get('inputEmail'));
            $objUser->setLocalPhoneNumber($request->get('inputPhoneNumber'));
            $objUser->setOfficialAddress($request->get('inputOfficialAddress'));
            $objUser->setCurrentLocation($request->get('inputCurrentLocation'));
            $objUser->setBio($request->get('inputBio'));

            $entityManager->persist($objUser);
            $entityManager->flush();
        }

        return $this->render(
            'Admin\User\form.html.twig',
            [
                'objUser' => $objUser,
                'isEditable' => TRUE,
                'isNew' => FALSE,
                'error' => $error,
                'success' => $success,
            ]
        );
    }

    public function getUserDeleteAction($userId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $objUser = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userId);

        $error = NULL;
        $success = TRUE;

        try {
            $entityManager->remove($objUser);
            $entityManager->flush();
        } catch (\Exception $e) {
            $error = [
                'errorTitle' => 'Nem törölhető',
                'errorText' => $e->getMessage(),
            ];
            $success = FALSE;
        }

        return $this->redirectToRoute(
            'admin_get_users',
            [
                'error' => $error,
                'success' => $success
            ]
        );
    }
}
