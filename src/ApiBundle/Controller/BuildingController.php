<?php

namespace ApiBundle\Controller;

use ApiBundle\Service\ResponseHandler;

use AppBundle\Entity\Bill;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentType;
use AppBundle\Entity\House;
use AppBundle\Entity\HouseUser;
use AppBundle\Entity\Malfunction;
use AppBundle\Entity\Manager;
use AppBundle\Entity\Order;
use AppBundle\Entity\Payment;
use AppBundle\Entity\PaymentMethod;
use AppBundle\Entity\Post;
use AppBundle\Entity\SocialEntity;
use AppBundle\Entity\Tenant;
use AppBundle\Entity\Unit;
use AppBundle\Entity\UnitTenant;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Twilio\Rest\Client;

class BuildingController extends Controller
{
    public function getHousesAction()
    {
        $objHouses = $this->getDoctrine()
            ->getRepository('AppBundle:House')
            ->findAll();

        if (!$objHouses) {
            return $this->container->get('response_handler')->errorHandler("houses_empty", "No house found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objHouses, []);
    }

    public function getHouseByIdAction(Request $request)
    {
        if (!$request->get("id")) {
            return $this->container->get('response_handler')->errorHandler("no_house_id_provided", "Invalid parameters", 422);
        }

        $numHouseID = $request->get("id");

        $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);

        if (!$objHouse) {
            return $this->container->get('response_handler')->errorHandler("house_not_exists", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objHouse, $request->query->all());
    }

    public function getUnitsByHouseAction(Request $request)
    {
        if (!$request->get("house_id")) {
            return $this->container->get('response_handler')->errorHandler("no_house_id_provided", "Invalid parameters", 422);
        }

        $numHouseID = $request->get("house_id");
        $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);

        if (!$objHouse) {
            return $this->container->get('response_handler')->errorHandler("house_not_exists", "Not found", 404);
        }
        $objUnit = $objHouse->getUnits();

        if (!$objUnit) {
            return $this->container->get('response_handler')->errorHandler("unit_not_exists", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objUnit, $request->query->all());
    }

    public function getUnitByIdAction(Request $request)
    {
        if (!$request->get("unit_id")) {
            return $this->container->get('response_handler')->errorHandler("no_unit_id_provided", "Invalid parameters", 422);
        }

        $numUnitID = $request->get("unit_id");
        $objUnit = $this->getDoctrine()->getRepository(Unit::class)->find($numUnitID);

        if (!$objUnit) {
            return $this->container->get('response_handler')->errorHandler("unit_not_exists", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objUnit, $request->query->all());
    }

    public function postImportHouseAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'name' => 'required',
                'region' => 'required',
                'postal_code' => 'required|numeric',
                'city' => 'required',
                'street' => 'required',
                'building' => 'required',
                'unit' => 'required',
                'lot_number' => 'required|numeric',
                'gps_latitude' => 'required|numeric',
                'gps_longitude' => 'required|numeric',
            ],
            $request
        );

        if ($validator['hasError']) {
            return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
        }

        $validator = $this->container->get('validation_handler')->importSourceValidationHandler($request);
        if (!$validator) {
            return $this->container->get('response_handler')->errorHandler('invalid_api_key', 'Invalid Api Key!', 422);
        }
        //TODO validálni a duplikáció elkerülésének érdekében

        $entityManager = $this->getDoctrine()->getManager();
        $objHouse = new House();
        $objHouse->setName($request->get('name'));
        $objHouse->setCountryCode($request->get('country_code') ?? 'HU');
        $objHouse->setRegion($request->get('region'));
        $objHouse->setPostalCode($request->get('postal_code'));
        $objHouse->setCity($request->get('city'));
        $objHouse->setStreet($request->get('street'));
        $objHouse->setBuilding($request->get('building'));
        $objHouse->setUnit($request->get('unit'));
        $objHouse->setLotNumber($request->get('lot_number'));
        $objHouse->setGpsLatitude($request->get('gps_latitude'));
        $objHouse->setGpsLongitude($request->get('gps_longitude'));
        $objHouse->setStatus(1);
        $objHouse->setCreatedAt(new \DateTime('now'));
        $objHouse->setUpdatedAt(new \DateTime('now'));

        $entityManager->persist($objHouse);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler(
            "House with id: ".$objHouse->getId()." and name: ".$objHouse->getName()." has been created!",
            $request->query->all()
        );
    }

    public function postImportUnitAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'building' => 'required',
                'floor' => 'required|numeric',
                'door' => 'required|numeric',
                'floor_area' => 'required|numeric',
                'type' => 'required|numeric',
                'balance' => 'required|numeric',
                'house_share' => 'required|numeric',
            ],
            $request
        );

        if ($validator['hasError']) {
            return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
        }

        $validator = $this->container->get('validation_handler')->importSourceValidationHandler($request);
        if (!$validator) {
            return $this->container->get('response_handler')->errorHandler('invalid_api_key', 'Invalid Api Key!', 422);
        }
        //TODO validálni a duplikáció elkerülésének érdekében
        /*if (!$request->get('house_id') || !is_numeric($request->get('house_id'))) {
            return $this->container->get('response_handler')->errorHandler("invalid_house_id", "Invalid parameters", 422);
        }
        if (!$request->get('unit_tenant_id') || !is_numeric($request->get('unit_tenant_id'))) {
            return $this->container->get('response_handler')->errorHandler("invalid_unit_floor_area", "Invalid parameters", 422);
        }*/

        $entityManager = $this->getDoctrine()->getManager();
        $objUnit = new Unit();
        if ($request->get('house_id')) {
            $numHouseID = $request->get("house_id");
            $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);
            if (!$objHouse) {
                return $this->container->get('response_handler')->errorHandler("invalid_house_id", "Invalid parameters", 422);
            }
            $objUnit->setHouse($objHouse);
        }
        $objUnit->setBuilding($request->get('building'));
        $objUnit->setFloor($request->get('floor'));
        $objUnit->setDoor($request->get('door'));
        $objUnit->setFloorArea($request->get('floor_area'));
        $objUnit->setType($request->get('type'));
        $objUnit->setBalance($request->get('balance'));
        $objUnit->setHouseShare($request->get('house_share'));
        if ($request->get('unit_tenant_id')) {
            $numUnitTenantID = $request->get("unit_tenant_id");
            $objUnitTenant = $this->getDoctrine()->getRepository(UnitTenant::class)->find($numUnitTenantID);
            if (!$objUnitTenant) {
                return $this->container->get('response_handler')->errorHandler("invalid_unit_tenant_id", "Invalid parameters", 422);
            }
            $objUnit->setUnitTenant($objUnitTenant);
        }
        $objUnit->setCreatedAt(new \DateTime('now'));
        $objUnit->setUpdatedAt(new \DateTime('now'));

        $entityManager->persist($objUnit);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler(
            "Unit with id: ".$objUnit->getId()." has been created!",
            $request->query->all()
        );
    }
}