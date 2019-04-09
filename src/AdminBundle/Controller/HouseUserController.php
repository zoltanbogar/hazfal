<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HouseUserController extends Controller
{
    public function getHouseUsersAction(Request $request)
    {
        if ($request->query->get('error')) {
            $error = $request->query->get('error');
        }
        $objHouseUsers = $this->getDoctrine()
            ->getRepository('AppBundle:HouseUser')
            ->findAll();

        return $this->render(
            'Admin\HouseUser\houseUsers.html.twig',
            [
                'objHouseUsers' => $objHouseUsers,
                'error' => $error ?? NULL,
                'success' => $request->get('success')
            ]
        );
    }

    public function getHouseUserReadAction($houseUserId)
    {
        $objHouseUser = $this->getDoctrine()
            ->getRepository('AppBundle:HouseUser')
            ->find($houseUserId);

        return $this->render(
            'Admin\HouseUser\form.html.twig',
            [
                'objHouseUser' => $objHouseUser,
                'isEditable' => FALSE,
                'isNew' => FALSE,
                'error' => NULL,
                'success' => NULL,
            ]
        );
    }

    public function getHouseUserEditAction($houseUserId)
    {
        $objHouseUser = $this->getDoctrine()
            ->getRepository('AppBundle:HouseUser')
            ->find($houseUserId);

        return $this->render(
            'Admin\HouseUser\form.html.twig',
            [
                'objHouseUser' => $objHouseUser,
                'isEditable' => TRUE,
                'isNew' => FALSE,
                'error' => NULL,
                'success' => NULL,
            ]
        );
    }

    public function postHouseUserEditAction($houseUserId, Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'inputEmail' => 'required',
                'inputPhoneNumber' => 'required',
                'inputFirstName' => 'required',
                'inputLastName' => 'required',
                'inputCompanyName' => 'required',
                'inputCompanyAddress' => 'required',
                'inputCompanyTaxNumber' => 'required',
            ],
            $request
        );

        $error = NULL;
        $success = TRUE;

        if ($validator['hasError']) {
            $error = $validator;
            $success = FALSE;
        }

        $objHouseUser = $this->getDoctrine()
            ->getRepository('AppBundle:HouseUser')
            ->find($houseUserId);

        if (!$error) {
            $entityManager = $this->getDoctrine()->getManager();

            $objHouseUser->setEmail($request->get('inputEmail'));
            $objHouseUser->setPhoneNumber($request->get('inputPhoneNumber'));
            $objHouseUser->setFirstName($request->get('inputFirstName'));
            $objHouseUser->setLastName($request->get('inputLastName'));
            $objHouseUser->setCompanyName($request->get('inputCompanyName'));
            $objHouseUser->setCompanyAddress($request->get('inputCompanyAddress'));
            $objHouseUser->setCompanyTaxNumber($request->get('inputCompanyTaxNumber'));
            $objHouseUser->setUpdatedAt(new \DateTime('now'));

            $entityManager->persist($objHouseUser);
            $entityManager->flush();
        }

        return $this->render(
            'Admin\HouseUser\form.html.twig',
            [
                'objHouseUser' => $objHouseUser,
                'isEditable' => TRUE,
                'isNew' => FALSE,
                'error' => $error,
                'success' => $success,
            ]
        );
    }

    public function getHouseUserDeleteAction($houseUserId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $objHouseUser = $this->getDoctrine()
            ->getRepository('AppBundle:HouseUser')
            ->find($houseUserId);

        $error = NULL;
        $success = TRUE;

        try {
            $entityManager->remove($objHouseUser);
            $entityManager->flush();
        } catch (\Exception $e) {
            $error = [
                'errorTitle' => 'Nem törölhető',
                'errorText' => $e->getMessage(),
            ];
            $success = FALSE;
        }

        return $this->redirectToRoute(
            'admin_get_house_users',
            [
                'error' => $error,
                'success' => $success
            ]
        );
    }
}
