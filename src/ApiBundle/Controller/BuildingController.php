<?php

namespace ApiBundle\Controller;

use ApiBundle\Service\ResponseHandler;

use AppBundle\Entity\Bill;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentType;
use AppBundle\Entity\House;
use AppBundle\Entity\HouseUser;
use AppBundle\Entity\ImportedHouse;
use AppBundle\Entity\ImportedUnit;
use AppBundle\Entity\ImportSource;
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
                'postal_code' => 'required|numeric',
                'city' => 'required',
                'street' => 'required',
                'unit' => 'required',
                'lot_number' => 'required|numeric',
                'id' => 'required|numeric',
            ],
            $request
        );

        if ($validator['hasError']) {
            return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
        }

        $objImportSource = $this->container->get('validation_handler')->importSourceValidationHandler($request);
        if ($objImportSource === FALSE) {
            return $this->container->get('response_handler')->errorHandler('invalid_api_key', 'Invalid Api Key!', 422);
        }

        $isAlreadyAdded = $this->getDoctrine()->getRepository(ImportedHouse::class)->findBy(['externalId' => $request->get('id'), 'isAccepted' => 1]);

        if ($isAlreadyAdded) {
            return $this->container->get('response_handler')->errorHandler('duplication', 'Already imported!', 400);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $objHouse = new ImportedHouse();
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
        $objHouse->setTaxNumber($request->get('tax_number'));
        $objHouse->setFoundingDate(new \DateTime($request->get('founding_date')));
        $objHouse->setBankAccountNumber($request->get('bank_account_number'));
        $objHouse->setOwnershipSum($request->get('ownership_sum'));
        $objHouse->setImportedAt(new \DateTime('now'));
        $objHouse->setExternalId($request->get('id'));
        $objHouse->setIsAccepted(0);
        $objHouse->setImportSource($objImportSource);

        $entityManager->persist($objHouse);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler(
            "House with id: " . $objHouse->getId() . " and name: " . $objHouse->getName() . " has been imported!",
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
                'id' => 'required|numeric',
            ],
            $request
        );

        if ($validator['hasError']) {
            return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
        }

        $objImportSource = $this->container->get('validation_handler')->importSourceValidationHandler($request);
        if ($objImportSource === FALSE) {
            return $this->container->get('response_handler')->errorHandler('invalid_api_key', 'Invalid Api Key!', 422);
        }

        $isAlreadyAdded = $this->getDoctrine()->getRepository(ImportedUnit::class)->findBy(['externalId' => $request->get('id'), 'isAccepted' => 1]);

        if ($isAlreadyAdded) {
            return $this->container->get('response_handler')->errorHandler('duplication', 'Already imported!', 400);
        }

        $entityManager = $this->getDoctrine()->getManager();
        /*$objUnit = new Unit();
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

        $entityManager->persist($objUnit);*/
        $objUnit = new ImportedUnit();
        $objUnit->setBuilding($request->get('building'));
        $objUnit->setFloor($request->get('floor'));
        $objUnit->setDoor($request->get('door'));
        $objUnit->setFloorArea($request->get('floor_area'));
        $objUnit->setUnitType($request->get('type'));
        $objUnit->setBalance($request->get('balance'));
        $objUnit->setHouseShare($request->get('house_share'));
        $objUnit->setImportedAt(new \DateTime('now'));
        $objUnit->setExternalId($request->get('id'));
        $objUnit->setIsAccepted(0);
        $objUnit->setImportSource($objImportSource);

        $entityManager->persist($objUnit);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler(
            "Unit with id: " . $objUnit->getId() . " has been imported!",
            $request->query->all()
        );
    }

    public function postBulkImportHouseAction(Request $request)
    {
        if (!$request->get('payload')) {
            return $this->container->get('response_handler')->errorHandler('empty_payload', 'Empty Payload!', 404);
        }
        $arrPayload = json_decode($request->get('payload'), TRUE);
        if (!$arrPayload) {
            return $this->container->get('response_handler')->errorHandler('invalid_payload', 'Invalid Payload!', 400);
        }

        $objImportSource = $this->container->get('validation_handler')->importSourceValidationHandler($request);
        $entityManager = $this->getDoctrine()->getManager();
        $arrSuccessMSG = [];

        foreach ($arrPayload as $rowPayload) {
            $validator = $this->container->get('validation_handler')->inputValidationHandlerArray(
                [
                    'name' => 'required',
                    'postal_code' => 'required|numeric',
                    'city' => 'required',
                    'street' => 'required',
                    'unit' => 'required',
                    'lot_number' => 'required|numeric',
                    'id' => 'required|numeric',
                ],
                $rowPayload
            );

            if ($validator['hasError']) {
                return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
            }

            $isAlreadyAdded = $this->getDoctrine()->getRepository(ImportedHouse::class)->findBy(['externalId' => $rowPayload['id'], 'isAccepted' => 1]);

            if ($isAlreadyAdded) {
                return $this->container->get('response_handler')->errorHandler('duplication', 'Already imported!', 400);
            }

            $objHouse = new ImportedHouse();
            $objHouse->setName($rowPayload['name']);
            $objHouse->setCountryCode($rowPayload['country_code'] ?? 'HU');
            $objHouse->setRegion($rowPayload['region']);
            $objHouse->setPostalCode($rowPayload['postal_code']);
            $objHouse->setCity($rowPayload['city']);
            $objHouse->setStreet($rowPayload['street']);
            $objHouse->setBuilding($rowPayload['building']);
            $objHouse->setUnit($rowPayload['unit']);
            $objHouse->setLotNumber($rowPayload['lot_number']);
            $objHouse->setGpsLatitude($rowPayload['gps_latitude']);
            $objHouse->setGpsLongitude($rowPayload['gps_longitude']);
            $objHouse->setTaxNumber($rowPayload['tax_number']);
            $objHouse->setFoundingDate(new \DateTime($rowPayload['founding_date']));
            $objHouse->setBankAccountNumber($rowPayload['bank_account_number']);
            $objHouse->setOwnershipSum($rowPayload['ownership_sum']);
            $objHouse->setImportedAt(new \DateTime('now'));
            $objHouse->setExternalId($rowPayload['id']);
            $objHouse->setIsAccepted(0);
            $objHouse->setImportSource($objImportSource);

            $entityManager->persist($objHouse);
            $entityManager->flush();

            $arrSuccessMSG[] = [
                "msg" => "ID: " . $rowPayload["id"] . ", " . $rowPayload["name"] . " importálva!",
                "id" => $rowPayload["id"],
            ];
        }

        return $this->container->get('response_handler')->successHandler(
            $arrSuccessMSG,
            []
        );
    }

    public function postBulkImportUnitAction(Request $request)
    {
        if (!$request->get('payload')) {
            return $this->container->get('response_handler')->errorHandler('empty_payload', 'Empty Payload!', 404);
        }
        $arrPayload = json_decode($request->get('payload'), TRUE);
        if (!$arrPayload) {
            return $this->container->get('response_handler')->errorHandler('invalid_payload', 'Invalid Payload!', 400);
        }

        $objImportSource = $this->container->get('validation_handler')->importSourceValidationHandler($request);
        $entityManager = $this->getDoctrine()->getManager();
        $arrSuccessMSG = [];

        foreach ($arrPayload as $rowPayload) {
            $validator = $this->container->get('validation_handler')->inputValidationHandlerArray(
                [
                    'building' => 'required',
                    'floor' => 'required|numeric',
                    'door' => 'required|numeric',
                    'floor_area' => 'required|numeric',
                    'type' => 'required|numeric',
                    'balance' => 'required|numeric',
                    'house_share' => 'required|numeric',
                    'id' => 'required|numeric',
                ],
                $rowPayload
            );

            if ($validator['hasError']) {
                return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
            }

            $isAlreadyAdded = $this->getDoctrine()->getRepository(ImportedUnit::class)->findBy(['externalId' => $rowPayload['id'], 'isAccepted' => 1]);

            if ($isAlreadyAdded) {
                return $this->container->get('response_handler')->errorHandler('duplication', 'Already imported!', 400);
            }

            $objUnit = new ImportedUnit();
            $objUnit->setBuilding($rowPayload['building']);
            $objUnit->setFloor($rowPayload['floor']);
            $objUnit->setDoor($rowPayload['door']);
            $objUnit->setFloorArea($rowPayload['floor_area']);
            $objUnit->setUnitType($rowPayload['type']);
            $objUnit->setBalance($rowPayload['balance']);
            $objUnit->setHouseShare($rowPayload['house_share']);
            $objUnit->setHouseId($rowPayload["house_id"]);
            $objUnit->setTenantId($rowPayload["unit_tenant_id"]);
            $objUnit->setImportedAt(new \DateTime('now'));
            $objUnit->setExternalId($rowPayload['id']);
            $objUnit->setIsAccepted(0);
            $objUnit->setImportSource($objImportSource);

            $entityManager->persist($objUnit);
            $entityManager->flush();

            $arrSuccessMSG[] = [
                "msg" => "ID: " . $rowPayload["id"] . ", Ház azonosító: " . $rowPayload["house_id"] . ", Lakó azonosító: " . $rowPayload["unit_tenant_id"] . ", " . $rowPayload["building"] . ", " . $rowPayload["floor"] . "/" . $rowPayload["door"] . ". importálva!",
                "id" => $rowPayload["id"],
            ];
        }

        return $this->container->get('response_handler')->successHandler(
            $arrSuccessMSG,
            []
        );
    }

    public function deleteHouseAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'api_key' => 'required',
                'id' => 'required|numeric',
            ],
            $request
        );

        if ($validator['hasError']) {
            return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
        }

        $objImportSource = $this->container->get('validation_handler')->importSourceValidationHandler($request);
        if ($objImportSource === FALSE) {
            return $this->container->get('response_handler')->errorHandler('invalid_api_key', 'Invalid Api Key!', 422);
        }

        $objImportedHouse = $this->getDoctrine()->getRepository(ImportedHouse::class)->findBy(['externalId' => $request->get('id'), 'isAccepted' => 1]);

        if (!$objImportedHouse || count($objImportedHouse) > 1) {
            return $this->container->get('response_handler')->errorHandler('house_not_found', 'House not found, nothing to delete!', 404);
        }

        $objImportedHouse = $objImportedHouse[0];

        $objHouse = $objImportedHouse->getHouse();
        if (!$objHouse) {
            return $this->container->get('response_handler')->errorHandler('house_not_found', 'House not found, nothing to delete!', 404);
        }

        $entityManager = $this->getDoctrine()->getManager();

        try {
            $entityManager->remove($objHouse);
            $entityManager->remove($objImportedHouse);
            $entityManager->flush();
        } catch (\Exception $e) {
            return $this->container->get('response_handler')->errorHandler('house_cannot_be_deleted', $e->getMessage(), 401);
        }

        return $this->container->get('response_handler')->successHandler(
            "Ház törölve!",
            []
        );
    }

    public function deleteUnitAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'api_key' => 'required',
                'id' => 'required|numeric',
            ],
            $request
        );

        if ($validator['hasError']) {
            return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
        }

        $objImportSource = $this->container->get('validation_handler')->importSourceValidationHandler($request);
        if ($objImportSource === FALSE) {
            return $this->container->get('response_handler')->errorHandler('invalid_api_key', 'Invalid Api Key!', 422);
        }

        $objImportedUnit = $this->getDoctrine()->getRepository(ImportedUnit::class)->findBy(['externalId' => $request->get('id'), 'isAccepted' => 1]);

        if (!$objImportedUnit || count($objImportedUnit) > 1) {
            return $this->container->get('response_handler')->errorHandler('unit_not_found', 'Unit not found, nothing to delete!', 404);
        }

        $objImportedUnit = $objImportedUnit[0];

        $objUnit = $objImportedUnit->getUnit();
        if (!$objUnit) {
            return $this->container->get('response_handler')->errorHandler('unit_not_found', 'Unit not found, nothing to delete!', 404);
        }

        $entityManager = $this->getDoctrine()->getManager();

        try {
            $entityManager->remove($objUnit);
            $entityManager->remove($objImportedUnit);
            $entityManager->flush();
        } catch (\Exception $e) {
            return $this->container->get('response_handler')->errorHandler('unit_cannot_be_deleted', $e->getMessage(), 401);
        }

        return $this->container->get('response_handler')->successHandler(
            "Albetét törölve!",
            []
        );
    }

    public function postForceImportHouseAction(Request $request)
    {
        if (!$this->getParameter('force_import')) {
            return $this->container->get('response_handler')->errorHandler('force_import_is_not_allowed', "Not allowed", 401);
        }

        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'name' => 'required',
                'postal_code' => 'required|numeric',
                'city' => 'required',
                'street' => 'required',
                'unit' => 'required',
                'lot_number' => 'required|numeric',
            ],
            $request
        );

        if ($validator['hasError']) {
            return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
        }

        $objImportSource = $this->container->get('validation_handler')->importSourceValidationHandler($request);
        if ($objImportSource === FALSE) {
            return $this->container->get('response_handler')->errorHandler('invalid_api_key', 'Invalid Api Key!', 422);
        }

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
        $objHouse->setTaxNumber($request->get('tax_number'));
        $objHouse->setFoundingDate(new \DateTime($request->get('founding_date')));
        $objHouse->setBankAccountNumber($request->get('bank_account_number'));
        $objHouse->setOwnershipSum($request->get('ownership_sum'));
        $objHouse->setStatus(1);
        $objHouse->setCreatedAt(new \DateTime('now'));
        $objHouse->setUpdatedAt(new \DateTime('now'));

        $entityManager->persist($objHouse);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler(
            "House with id: " . $objHouse->getId() . " and name: " . $objHouse->getName() . " has been imported!",
            $request->query->all()
        );
    }

    public function postForceImportUnitAction(Request $request)
    {
        if (!$this->getParameter('force_import')) {
            return $this->container->get('response_handler')->errorHandler('force_import_is_not_allowed', "Not allowed", 401);
        }

        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'building' => 'required',
                'floor' => 'required|numeric',
                'door' => 'required|numeric',
                'floor_area' => 'required|numeric',
                'balance' => 'required|numeric',
                'house_share' => 'required|numeric',
                'unit_type' => 'required',
            ],
            $request
        );

        if ($validator['hasError']) {
            return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
        }

        $objImportSource = $this->container->get('validation_handler')->importSourceValidationHandler($request);
        if ($objImportSource === FALSE) {
            return $this->container->get('response_handler')->errorHandler('invalid_api_key', 'Invalid Api Key!', 422);
        }

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
        $objUnit->setUnitType($request->get('unit_type'));
        if (is_numeric($request->get('unit_type'))) {
            $objUnit->setUnitType($request->get('unit_type'));
        } else {
            $key = array_search($request->get('unit_type'), $this->getParameter('unit_types'));
            if ($key === FALSE) {
                return $this->container->get('response_handler')->errorHandler("invalid_unit_type", "Invalid parameters", 422);
            }
            $objUnit->setUnitType($key);
        }
        $objUnit->setCreatedAt(new \DateTime('now'));
        $objUnit->setUpdatedAt(new \DateTime('now'));

        $entityManager->persist($objUnit);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler(
            "Unit with id: " . $objUnit->getId() . " has been imported!",
            $request->query->all()
        );
    }

    public function postBulkForceImportHouseAction(Request $request)
    {
        if (!$request->get('payload')) {
            return $this->container->get('response_handler')->errorHandler('empty_payload', 'Empty Payload!', 404);
        }
        $arrPayload = json_decode($request->get('payload'), TRUE);
        if (!$arrPayload) {
            return $this->container->get('response_handler')->errorHandler('invalid_payload', 'Invalid Payload!', 400);
        }

        $objImportSource = $this->container->get('validation_handler')->importSourceValidationHandler($request);
        if ($objImportSource === FALSE) {
            return $this->container->get('response_handler')->errorHandler('invalid_api_key', 'Invalid Api Key!', 422);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $arrSuccessMSG = [];

        foreach ($arrPayload as $rowPayload) {
            $validator = $this->container->get('validation_handler')->inputValidationHandlerArray(
                [
                    'name' => 'required',
                    'postal_code' => 'required|numeric',
                    'city' => 'required',
                    'street' => 'required',
                    'unit' => 'required',
                    'lot_number' => 'required|numeric',
                ],
                $rowPayload
            );

            if ($validator['hasError']) {
                return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
            }

            $objHouse = new House();
            $objHouse->setName($rowPayload['name']);
            $objHouse->setCountryCode($rowPayload['country_code'] ?? 'HU');
            $objHouse->setRegion($rowPayload['region']);
            $objHouse->setPostalCode($rowPayload['postal_code']);
            $objHouse->setCity($rowPayload['city']);
            $objHouse->setStreet($rowPayload['street']);
            $objHouse->setBuilding($rowPayload['building']);
            $objHouse->setUnit($rowPayload['unit']);
            $objHouse->setLotNumber($rowPayload['lot_number']);
            $objHouse->setGpsLatitude($rowPayload['gps_latitude']);
            $objHouse->setGpsLongitude($rowPayload['gps_longitude']);
            $objHouse->setTaxNumber($rowPayload['tax_number']);
            $objHouse->setFoundingDate(new \DateTime($rowPayload['founding_date']));
            $objHouse->setBankAccountNumber($rowPayload['bank_account_number']);
            $objHouse->setOwnershipSum($rowPayload['ownership_sum']);
            $objHouse->setStatus(1);
            $objHouse->setCreatedAt(new \DateTime('now'));
            $objHouse->setUpdatedAt(new \DateTime('now'));

            $entityManager->persist($objHouse);
            $entityManager->flush();

            $arrSuccessMSG[] = [
                "msg" => "ID: " . $rowPayload["id"] . ", " . $rowPayload["name"] . " importálva!",
                "id" => $rowPayload["id"],
            ];
        }

        return $this->container->get('response_handler')->successHandler(
            $arrSuccessMSG,
            []
        );
    }

    public function postBulkForceImportUnitAction(Request $request)
    {
        if (!$request->get('payload')) {
            return $this->container->get('response_handler')->errorHandler('empty_payload', 'Empty Payload!', 404);
        }
        $arrPayload = json_decode($request->get('payload'), TRUE);
        if (!$arrPayload) {
            return $this->container->get('response_handler')->errorHandler('invalid_payload', 'Invalid Payload!', 400);
        }

        $objImportSource = $this->container->get('validation_handler')->importSourceValidationHandler($request);
        if ($objImportSource === FALSE) {
            return $this->container->get('response_handler')->errorHandler('invalid_api_key', 'Invalid Api Key!', 422);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $arrSuccessMSG = [];

        foreach ($arrPayload as $rowPayload) {
            $validator = $this->container->get('validation_handler')->inputValidationHandlerArray(
                [
                    'building' => 'required',
                    'floor' => 'required|numeric',
                    'door' => 'required|numeric',
                    'floor_area' => 'required|numeric',
                    'balance' => 'required|numeric',
                    'house_share' => 'required|numeric',
                    'unit_type' => 'required',
                ],
                $rowPayload
            );

            if ($validator['hasError']) {
                return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
            }

            $objUnit = new Unit();
            if ($rowPayload['house_id']) {
                $numHouseID = $rowPayload['house_id'];
                $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);
                if (!$objHouse) {
                    return $this->container->get('response_handler')->errorHandler("invalid_house_id", "Invalid parameters", 422);
                }
                $objUnit->setHouse($objHouse);
            }
            $objUnit->setBuilding($rowPayload['building']);
            $objUnit->setFloor($rowPayload['floor']);
            $objUnit->setDoor($rowPayload['door']);
            $objUnit->setFloorArea($rowPayload['floor_area']);
            if (is_numeric($rowPayload['unit_type'])) {
                $objUnit->setUnitType($rowPayload['unit_type']);
            } else {
                $key = array_search($rowPayload['unit_type'], $this->getParameter('unit_types'));
                if ($key === FALSE) {
                    return $this->container->get('response_handler')->errorHandler("invalid_unit_type", "Invalid parameters", 422);
                }
                $objUnit->setUnitType($key);
            }
            $objUnit->setBalance($rowPayload['balance']);
            $objUnit->setHouseShare($rowPayload['house_share']);
            if ($rowPayload['unit_tenant_id']) {
                $numUnitTenantID = $rowPayload['unit_tenant_id'];
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

            $arrSuccessMSG[] = [
                "msg" => "ID: " . $rowPayload["id"] . ", Ház azonosító: " . $rowPayload["house_id"] . ", Lakó azonosító: " . $rowPayload["unit_tenant_id"] . ", " . $rowPayload["building"] . ", " . $rowPayload["floor"] . "/" . $rowPayload["door"] . ". importálva!",
                "id" => $rowPayload["id"],
            ];
        }

        return $this->container->get('response_handler')->successHandler(
            $arrSuccessMSG,
            []
        );
    }
}