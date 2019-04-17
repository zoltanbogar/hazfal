<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\House;
use AppBundle\Entity\HouseUser;
use AppBundle\Entity\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ManagerController extends Controller
{
    public function getManagersAction(Request $request)
    {
        if ($request->query->get('error')) {
            $error = $request->query->get('error');
        }

        $objManagers = [];

        $objHouseUsers = $this->getDoctrine()
            ->getRepository('AppBundle:HouseUser')
            ->findAll();

        foreach ($objHouseUsers as $user) {
            if($user instanceof Manager) {
                $objManagers[] = $user;
            }
        }

        return $this->render(
            'Admin\Manager\managers.html.twig',
            [
                'objManagers' => $objManagers,
                'error' => $error ?? NULL,
                'success' => $request->get('success')
            ]
        );
    }

    public function getManagerReadAction(Request $request, $managerId)
    {
        $objHouseUser = $this->getDoctrine()
            ->getRepository('AppBundle:HouseUser')
            ->find($managerId);

        $objHouses = $this->getDoctrine()
            ->getRepository('AppBundle:House')
            ->findAll();

        return $this->render(
            'Admin\Manager\form.html.twig',
            [
                'objManager' => $objHouseUser,
                'objHouses' => $objHouses,
                'isEditable' => FALSE,
                'isNew' => FALSE,
                'error' => NULL,
                'success' => $request->get('success'),
            ]
        );
    }

    public function getManagerAddAction(Request $request)
    {
        $objManager = new HouseUser();
        $objHouses = $this->getDoctrine()
            ->getRepository('AppBundle:House')
            ->findAll();

        return $this->render(
            'Admin\Manager\form.html.twig',
            [
                'objManager' => $objManager,
                'objHouses' => $objHouses,
                'isEditable' => TRUE,
                'isNew' => TRUE,
                'error' => NULL,
                'success' => NULL,
            ]
        );
    }

    public function getManagerEditAction($managerId)
    {
        $objHouseUser = $this->getDoctrine()
            ->getRepository('AppBundle:HouseUser')
            ->find($managerId);

        $objHouses = $this->getDoctrine()
            ->getRepository('AppBundle:House')
            ->findAll();

        return $this->render(
            'Admin\Manager\form.html.twig',
            [
                'objManager' => $objHouseUser,
                'objHouses' => $objHouses,
                'isEditable' => TRUE,
                'isNew' => FALSE,
                'error' => NULL,
                'success' => NULL,
            ]
        );
    }

    public function postManagerAddAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'inputEmail' => 'required',
                'inputHouseId' => 'required',
                'inputWebsite' => 'required',
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
        $objManagerID = 0;

        if ($validator['hasError']) {
            $error = $validator;
            $success = FALSE;
        }

        if (!$error) {
            $entityManager = $this->getDoctrine()->getManager();

            $objHouse = $entityManager->getRepository('AppBundle:House')->find($request->get('inputHouseId'));

            $objManager = new Manager();
            $objManager->setWebsite($request->get('inputWebsite'));
            $objManager->setLogoImage('Image');
            $objManager->setUser(NULL);
            $objManager->setEmail($request->get('inputEmail'));
            $objManager->setMailingAddress('MailingAddress');
            $objManager->setPhoneNumber($request->get('inputPhoneNumber'));
            $objManager->setFirstName($request->get('inputFirstName'));
            $objManager->setLastName($request->get('inputLastName'));
            $objManager->setCompanyName($request->get('inputCompanyName'));
            $objManager->setCompanyAddress($request->get('inputCompanyAddress'));
            $objManager->setCompanyTaxNumber($request->get('inputCompanyTaxNumber'));
            $objManager->setCreatedAt(new \DateTime('now'));
            $objManager->setHouse($objHouse);

            $entityManager->persist($objManager);
            $entityManager->flush();

            $objManagerID = $objManager->getId();
        }

        return $this->redirectToRoute(
            'admin_get_manager_read',
            [
                'managerId' => $objManagerID,
                'error' => NULL,
                'success' => $success
            ]
        );
    }

    public function postManagerEditAction($managerId, Request $request)
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
            ->find($managerId);

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
            'Admin\Manager\form.html.twig',
            [
                'objManager' => $objHouseUser,
                'isEditable' => TRUE,
                'isNew' => FALSE,
                'error' => $error,
                'success' => $success,
            ]
        );
    }

    public function getManagerDeleteAction($managerId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $objHouseUser = $this->getDoctrine()
            ->getRepository('AppBundle:HouseUser')
            ->find($managerId);

        $error = NULL;
        $success = TRUE;

        try {
            $objHouseUser->setDeletedAt(new \DateTime('now'));
            $entityManager->persist($objHouseUser);
            $entityManager->flush();
        } catch (\Exception $e) {
            $error = [
                'errorTitle' => 'Nem törölhető',
                'errorText' => $e->getMessage(),
            ];
            $success = FALSE;
        }

        return $this->redirectToRoute(
            'admin_get_managers',
            [
                'error' => $error,
                'success' => $success
            ]
        );
    }
}
