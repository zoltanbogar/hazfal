<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\House;
use AppBundle\Entity\Unit;
use AppBundle\Entity\UnitTenant;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BuildingController extends Controller
{
    public function getHousesAction(Request $request)
    {
        if ($request->query->get('error')) {
            $error = $request->query->get('error');
        }
        $objHouses = $this->getDoctrine()
            ->getRepository('AppBundle:House')
            ->findAll();

        return $this->render(
            'Admin\House\houses.html.twig',
            [
                'objHouses' => $objHouses,
                'error' => $error ?? NULL,
            ]
        );
    }

    public function getHouseReadAction($houseId)
    {
        $objHouse = $this->getDoctrine()
            ->getRepository('AppBundle:House')
            ->find($houseId);

        return $this->render(
            'Admin\House\form.html.twig',
            [
                'objHouse' => $objHouse,
                'isEditable' => FALSE,
                'isNew' => FALSE,
                'error' => NULL,
                'success' => NULL,
            ]
        );
    }

    public function getHouseEditAction($houseId)
    {
        $objHouse = $this->getDoctrine()
            ->getRepository('AppBundle:House')
            ->find($houseId);

        return $this->render(
            'Admin\House\form.html.twig',
            [
                'objHouse' => $objHouse,
                'isEditable' => TRUE,
                'isNew' => FALSE,
                'error' => NULL,
                'success' => NULL,
            ]
        );
    }

    public function postHouseEditAction($houseId, Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'inputName' => 'required',
                'inputRegion' => 'required',
                'inputZip' => 'required|numeric',
                'inputCity' => 'required',
                'inputStreet' => 'required',
                'inputBuilding' => 'required',
                'inputUnit' => 'required',
                'inputLotNumber' => 'required|numeric',
                'inputLatitude' => 'required|numeric',
                'inputLongitude' => 'required|numeric',
                'inputCountryCode' => 'required|min:2|max:2',
            ],
            $request
        );

        $error = NULL;
        $success = TRUE;

        if ($validator['hasError']) {
            $error = $validator;
            $success = FALSE;
        }

        $objHouse = $this->getDoctrine()
            ->getRepository('AppBundle:House')
            ->find($houseId);

        if (!$error) {
            $entityManager = $this->getDoctrine()->getManager();

            $objHouse->setName($request->get('inputName'));
            $objHouse->setCountryCode($request->get('inputCountryCode'));
            $objHouse->setRegion($request->get('inputRegion'));
            $objHouse->setPostalCode($request->get('inputZip'));
            $objHouse->setCity($request->get('inputCity'));
            $objHouse->setStreet($request->get('inputStreet'));
            $objHouse->setBuilding($request->get('inputBuilding'));
            $objHouse->setUnit($request->get('inputUnit'));
            $objHouse->setLotNumber($request->get('inputLotNumber'));
            $objHouse->setGpsLatitude($request->get('inputLatitude'));
            $objHouse->setGpsLongitude($request->get('inputLongitude'));
            //$objHouse->setStatus(1);
            $objHouse->setUpdatedAt(new \DateTime('now'));

            $entityManager->persist($objHouse);
            $entityManager->flush();
        }

        return $this->render(
            'Admin\House\form.html.twig',
            [
                'objHouse' => $objHouse,
                'isEditable' => TRUE,
                'isNew' => FALSE,
                'error' => $error,
                'success' => $success,
            ]
        );
    }

    public function getHouseDeleteAction($houseId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $objHouse = $this->getDoctrine()
            ->getRepository('AppBundle:House')
            ->find($houseId);

        $error = NULL;

        try {
            $entityManager->remove($objHouse);
            $entityManager->flush();
        } catch (\Exception $e) {
            $error = [
                'errorTitle' => 'Nem törölhető',
                'errorText' => $e->getMessage(),
            ];
        }

        return $this->redirectToRoute(
            'admin_get_houses',
            [
                'error' => $error,
            ]
        );
    }

    public function getHouseAddAction(Request $request)
    {
        $objHouse = new House();
        /*if ($request->query->get('error')) {
            $error = $request->query->get('error');
        }*/
        //$objHouses = $this->getDoctrine()
        //    ->getRepository('AppBundle:House')
        //    ->findAll();

        return $this->render(
            'Admin\House\form.html.twig',
            [
                'objHouse' => $objHouse,
                'isEditable' => TRUE,
                'isNew' => TRUE,
                'error' => NULL,
                'success' => NULL,
            ]
        );
    }

    public function postHouseAddAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'inputName' => 'required',
                'inputRegion' => 'required',
                'inputZip' => 'required|numeric',
                'inputCity' => 'required',
                'inputStreet' => 'required',
                'inputBuilding' => 'required',
                'inputUnit' => 'required',
                'inputLotNumber' => 'required|numeric',
                'inputLatitude' => 'required|numeric',
                'inputLongitude' => 'required|numeric',
                'inputCountryCode' => 'required|min:2|max:2',
            ],
            $request
        );

        $error = NULL;
        $success = TRUE;

        if ($validator['hasError']) {
            $error = $validator;
            $success = FALSE;
        }

        if (!$error) {
            $entityManager = $this->getDoctrine()->getManager();

            $objHouse = new House();
            $objHouse->setName($request->get('inputName'));
            $objHouse->setCountryCode($request->get('inputCountryCode'));
            $objHouse->setRegion($request->get('inputRegion'));
            $objHouse->setPostalCode($request->get('inputZip'));
            $objHouse->setCity($request->get('inputCity'));
            $objHouse->setStreet($request->get('inputStreet'));
            $objHouse->setBuilding($request->get('inputBuilding'));
            $objHouse->setUnit($request->get('inputUnit'));
            $objHouse->setLotNumber($request->get('inputLotNumber'));
            $objHouse->setGpsLatitude($request->get('inputLatitude'));
            $objHouse->setGpsLongitude($request->get('inputLongitude'));
            $objHouse->setStatus(1);
            $objHouse->setCreatedAt(new \DateTime('now'));
            $objHouse->setUpdatedAt(new \DateTime('now'));

            $entityManager->persist($objHouse);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
            'admin_get_houses',
            [
                'error' => NULL,
            ]
        );
    }

    public function getUnitsAction(Request $request)
    {
        if ($request->query->get('error')) {
            $error = $request->query->get('error');
        }
        $objUnits = $this->getDoctrine()
            ->getRepository('AppBundle:Unit')
            ->findAll();

        return $this->render(
            'Admin\Unit\units.html.twig',
            [
                'objUnits' => $objUnits,
                'error' => $error ?? NULL,
            ]
        );
    }

    public function getUnitReadAction($unitId)
    {
        $objUnit = $this->getDoctrine()
            ->getRepository('AppBundle:Unit')
            ->find($unitId);

        return $this->render(
            'Admin\Unit\form.html.twig',
            [
                'objUnit' => $objUnit,
                'isEditable' => FALSE,
                'isNew' => FALSE,
                'error' => NULL,
                'success' => NULL,
            ]
        );
    }

    public function getUnitEditAction($unitId)
    {
        $objUnit = $this->getDoctrine()
            ->getRepository('AppBundle:Unit')
            ->find($unitId);

        $objHouses = $this->getDoctrine()
            ->getRepository('AppBundle:House')
            ->findAll();

        $objTenant = $this->getDoctrine()
            ->getRepository('AppBundle:Tenant')
            ->findAll();

        return $this->render(
            'Admin\Unit\form.html.twig',
            [
                'objUnit' => $objUnit,
                'objHouses' => $objHouses,
                'objTenant' => $objTenant,
                'isEditable' => TRUE,
                'isNew' => FALSE,
                'error' => NULL,
                'success' => NULL,
            ]
        );
    }

    public function postUnitEditAction($unitId, Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'inputBuilding' => 'required',
                'inputFloor' => 'required|numeric',
                'inputDoor' => 'required|numeric',
                'inputFloorArea' => 'required|numeric',
                'inputType' => 'required|numeric',
                'inputHouseShare' => 'required|numeric',
                'inputTenant' => 'required|numeric',
                'inputBalance' => 'required|numeric',
                'inputHouse' => 'required|numeric',
            ],
            $request
        );

        $error = NULL;
        $success = TRUE;

        if ($validator['hasError']) {
            $error = $validator;
            $success = FALSE;
        }

        $objUnit = $this->getDoctrine()
            ->getRepository('AppBundle:Unit')
            ->find($unitId);

        if (!$error) {
            $entityManager = $this->getDoctrine()->getManager();

            $objHouse = $this->getDoctrine()
                ->getRepository('AppBundle:House')
                ->find($request->get('inputHouse'));

            $objTenant = $this->getDoctrine()
                ->getRepository('AppBundle:Tenant')
                ->find($request->get('inputTenant'));

            $objUnitTenant = $objTenant->getUnitTenant();

            if (!$objUnitTenant) {
                $objUnitTenant = new UnitTenant();
                $objUnitTenant->setTenant($objTenant);
                $objUnitTenant->setCreatedAt(new \DateTime('now'));
                $objUnitTenant->setUpdatedAt(new \DateTime('now'));
                $objUnitTenant->setStatus(1);
                $objUnitTenant->setType(1);
                $objUnitTenant->setOwnershipShare(1);

                $entityManager->persist($objUnitTenant);
                $entityManager->flush();
            }
            $objUnit->setHouse($objHouse);
            $objUnit->setUnitTenant($objUnitTenant);
            //$objUnit->setBalance($request->get('inputBalance'));
            $objUnit->setHouseShare($request->get('inputHouseShare'));
            $objUnit->setType($request->get('inputType'));
            $objUnit->setFloor($request->get('inputFloor'));
            $objUnit->setDoor($request->get('inputDoor'));
            $objUnit->setBuilding($request->get('inputBuilding'));
            $objUnit->setFloorArea($request->get('inputFloorArea'));
            //$objHouse->setStatus(1);
            $objUnit->setUpdatedAt(new \DateTime('now'));

            $entityManager->persist($objUnit);
            $entityManager->flush();
        }

        $objHouses = $this->getDoctrine()
            ->getRepository('AppBundle:House')
            ->findAll();

        $objTenant = $this->getDoctrine()
            ->getRepository('AppBundle:Tenant')
            ->findAll();

        return $this->render(
            'Admin\Unit\form.html.twig',
            [
                'objUnit' => $objUnit,
                'objHouses' => $objHouses,
                'objTenant' => $objTenant,
                'isEditable' => TRUE,
                'isNew' => FALSE,
                'error' => $error,
                'success' => $success,
            ]
        );
    }

    public function getUnitDeleteAction($unitId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $objUnit = $this->getDoctrine()
            ->getRepository('AppBundle:Unit')
            ->find($unitId);

        $error = NULL;

        try {
            $entityManager->remove($objUnit);
            $entityManager->flush();
        } catch (\Exception $e) {
            $error = [
                'errorTitle' => 'Nem törölhető',
                'errorText' => $e->getMessage(),
            ];
        }

        return $this->redirectToRoute(
            'admin_get_units',
            [
                'error' => $error,
            ]
        );
    }

    public function getUnitAddAction(Request $request)
    {
        $objUnit = new Unit();

        $objHouses = $this->getDoctrine()
            ->getRepository('AppBundle:House')
            ->findAll();

        $objTenant = $this->getDoctrine()
            ->getRepository('AppBundle:Tenant')
            ->findAll();

        return $this->render(
            'Admin\Unit\form.html.twig',
            [
                'objUnit' => $objUnit,
                'objHouses' => $objHouses,
                'objTenant' => $objTenant,
                'isEditable' => TRUE,
                'isNew' => TRUE,
                'error' => NULL,
                'success' => NULL,
            ]
        );
    }

    public function postUnitAddAction(Request $request)
    {

        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'inputBuilding' => 'required',
                'inputFloor' => 'required|numeric',
                'inputDoor' => 'required|numeric',
                'inputFloorArea' => 'required|numeric',
                'inputType' => 'required|numeric',
                'inputHouseShare' => 'required|numeric',
                'inputTenant' => 'required|numeric',
                'inputBalance' => 'required|numeric',
                'inputHouse' => 'required|numeric',
            ],
            $request
        );

        $error = NULL;
        $success = TRUE;

        if ($validator['hasError']) {
            $error = $validator;
            $success = FALSE;
        }

        if (!$error) {
            $objUnit = new Unit();

            $entityManager = $this->getDoctrine()->getManager();

            $objHouse = $this->getDoctrine()
                ->getRepository('AppBundle:House')
                ->find($request->get('inputHouse'));

            $objTenant = $this->getDoctrine()
                ->getRepository('AppBundle:Tenant')
                ->find($request->get('inputTenant'));

            $objUnitTenant = $objTenant->getUnitTenant();

            if (!$objUnitTenant) {
                $objUnitTenant = new UnitTenant();
                $objUnitTenant->setTenant($objTenant);
                $objUnitTenant->setCreatedAt(new \DateTime('now'));
                $objUnitTenant->setUpdatedAt(new \DateTime('now'));
                $objUnitTenant->setStatus(1);
                $objUnitTenant->setType(1);
                $objUnitTenant->setOwnershipShare(1);

                $entityManager->persist($objUnitTenant);
                $entityManager->flush();
            }
            $objUnit->setHouse($objHouse);
            $objUnit->setUnitTenant($objUnitTenant);
            $objUnit->setBalance($request->get('inputBalance'));
            $objUnit->setHouseShare($request->get('inputHouseShare'));
            $objUnit->setType($request->get('inputType'));
            $objUnit->setFloor($request->get('inputFloor'));
            $objUnit->setDoor($request->get('inputDoor'));
            $objUnit->setBuilding($request->get('inputBuilding'));
            $objUnit->setFloorArea($request->get('inputFloorArea'));
            //$objHouse->setStatus(1);
            $objUnit->setCreatedAt(new \DateTime('now'));
            $objUnit->setUpdatedAt(new \DateTime('now'));

            $entityManager->persist($objUnit);
            $entityManager->flush();
        } else {
            $objUnit = new Unit();

            $objHouses = $this->getDoctrine()
                ->getRepository('AppBundle:House')
                ->findAll();

            $objTenant = $this->getDoctrine()
                ->getRepository('AppBundle:Tenant')
                ->findAll();

            return $this->render(
                'Admin\Unit\form.html.twig',
                [
                    'objUnit' => $objUnit,
                    'objHouses' => $objHouses,
                    'objTenant' => $objTenant,
                    'isEditable' => TRUE,
                    'isNew' => TRUE,
                    'error' => $error,
                    'success' => NULL,
                ]
            );
        }

        return $this->redirectToRoute(
            'admin_get_units',
            [
                'error' => NULL,
            ]
        );
    }
}