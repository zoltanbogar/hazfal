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

class UserController extends Controller
{
    public function getHouseUsersByHouseIdAction(Request $request)
    {
        if (!$request->get("house_id")) {
            return $this->container->get('response_handler')->errorHandler("no_house_id_provided", "Invalid parameters", 422);
        }

        $numHouseID = $request->get("house_id");
        $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);

        if (!$objHouse) {
            return $this->container->get('response_handler')->errorHandler("house_not_exists", "Not found", 404);
        }

        $objHouseUsers = $objHouse->getHouseUsers();

        if (!$objHouseUsers) {
            return $this->container->get('response_handler')->errorHandler("house_users_not_exist", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objHouseUsers, $request->query->all());
    }

    public function getHouseUsersByUnitIdAction(Request $request)
    {
        if (!$request->get("unit_id")) {
            return $this->container->get('response_handler')->errorHandler("no_unit_id_provided", "Invalid parameters", 422);
        }

        $numUnitID = $request->get("unit_id");
        $objUnit = $this->getDoctrine()->getRepository(Unit::class)->find($numUnitID);

        if (!$objUnit) {
            return $this->container->get('response_handler')->errorHandler("unit_not_exists", "Not found", 404);
        }

        $objUnitTenant = $objUnit->getUnitTenant();

        if (!$objUnitTenant) {
            return $this->container->get('response_handler')->errorHandler("unit_tenant_not_exists", "Not found", 404);
        }

        $objTenant = $objUnitTenant->getTenant();

        if (!$objTenant) {
            return $this->container->get('response_handler')->errorHandler("tenant_not_exist", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objTenant, $request->query->all());
    }

    public function getUserByIdAction(Request $request)
    {
        if (!$request->get("user_id")) {
            $objUser = $this->getDoctrine()->getRepository(User::class)->findUserByApiKey($request->headers->get('X-AUTH-TOKEN'));

            return $this->container->get('response_handler')->successHandler($objUser, $request->query->all());
        }

        $numUserID = $request->get("user_id");
        $objUser = $this->getDoctrine()->getRepository(User::class)->find($numUserID);

        if (!$objUser) {
            return $this->container->get('response_handler')->errorHandler("user_not_exists", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objUser, $request->query->all());
    }

    public function getHouseUserByIdAction(Request $request)
    {
        if (!$request->get("house_user_id")) {
            return $this->container->get('response_handler')->errorHandler("no_house_user_id_provided", "Invalid parameters", 422);
        }

        $numHouseUserID = $request->get("house_user_id");
        $objHouseUser = $this->getDoctrine()->getRepository(HouseUser::class)->find($numHouseUserID);

        if (!$objHouseUser) {
            return $this->container->get('response_handler')->errorHandler("house_user_not_exists", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objHouseUser, $request->query->all());
    }

    public function getManagersByHouseIdAction(Request $request)
    {
        if (!$request->get("house_id")) {
            return $this->container->get('response_handler')->errorHandler("no_house_id_provided", "Invalid parameters", 422);
        }

        $numHouseID = $request->get("house_id");
        $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);

        if (!$objHouse) {
            return $this->container->get('response_handler')->errorHandler("house_not_exists", "Not found", 404);
        }

        $objHouseUser = $objHouse->getHouseUsers();

        if (!$objHouseUser) {
            return $this->container->get('response_handler')->errorHandler("house_user_not_exists", "Not found", 404);
        }

        $rowHouseUserID = [];
        foreach ($objHouseUser as $key => $data) {
            $rowHouseUserID[] = $data->getId();
        }

        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->createQuery(
            "SELECT house_user FROM AppBundle\Entity\HouseUser house_user WHERE house_user INSTANCE OF AppBundle\Entity\Manager AND house_user.id IN (".implode(
                ',',
                $rowHouseUserID
            ).")"
        );
        $products = $query->getResult();

        return $this->container->get('response_handler')->successHandler($products, $request->query->all());
    }

    public function postImportHouseUserAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'email' => 'required',
                'mailing_address' => 'required',
                'phone_number' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'company_name' => 'required',
                'company_address' => 'required',
                'company_tax_number' => 'required',
                'local_contact_number' => 'required',
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
        $objHouseUser = new Tenant();
        if ($request->get('user_id')) {
            $numUserID = $request->get("user_id");
            $objUser = $this->getDoctrine()->getRepository(User::class)->find($numUserID);
            if (!$objUser) {
                return $this->container->get('response_handler')->errorHandler("invalid_user_id", "Invalid parameters", 422);
            }
            $objHouseUser->setUser($objUser);
        }
        if ($request->get('house_id')) {
            $numHouseID = $request->get("house_id");
            $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);
            if (!$objHouse) {
                return $this->container->get('response_handler')->errorHandler("invalid_house_id", "Invalid parameters", 422);
            }
            $objHouseUser->setHouse($objHouse);
        }
        $objHouseUser->setEmail($request->get('email'));
        $objHouseUser->setMailingAddress($request->get('mailing_address'));
        $objHouseUser->setPhoneNumber($request->get('phone_number'));
        $objHouseUser->setFirstName($request->get('first_name'));
        $objHouseUser->setLastName($request->get('last_name'));
        $objHouseUser->setCompanyName($request->get('company_name'));
        $objHouseUser->setCompanyAddress($request->get('company_address'));
        $objHouseUser->setCompanyTaxNumber($request->get('company_tax_number'));
        $objHouseUser->setCreatedAt(new \DateTime('now'));
        $objHouseUser->setUpdatedAt(new \DateTime('now'));
        $objHouseUser->setLocalContactNumber($request->get('local_contact_number'));

        $entityManager->persist($objHouseUser);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler(
            "Tenant has been created!",
            $request->query->all()
        );
    }

    public function postImportManagerAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'email' => 'required',
                'mailing_address' => 'required',
                'phone_number' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'company_name' => 'required',
                'company_address' => 'required',
                'company_tax_number' => 'required',
                'website' => 'required',
                'logo_image' => 'required',
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
        $objHouseUser = new Manager();
        if ($request->get('user_id')) {
            $numUserID = $request->get("user_id");
            $objUser = $this->getDoctrine()->getRepository(User::class)->find($numUserID);
            if (!$objUser) {
                return $this->container->get('response_handler')->errorHandler("invalid_user_id", "Invalid parameters", 422);
            }
            $objHouseUser->setUser($objUser);
        }
        if ($request->get('house_id')) {
            $numHouseID = $request->get("house_id");
            $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);
            if (!$objHouse) {
                return $this->container->get('response_handler')->errorHandler("invalid_house_id", "Invalid parameters", 422);
            }
            $objHouseUser->setHouse($objHouse);
        }
        $objHouseUser->setEmail($request->get('email'));
        $objHouseUser->setMailingAddress($request->get('mailing_address'));
        $objHouseUser->setPhoneNumber($request->get('phone_number'));
        $objHouseUser->setFirstName($request->get('first_name'));
        $objHouseUser->setLastName($request->get('last_name'));
        $objHouseUser->setCompanyName($request->get('company_name'));
        $objHouseUser->setCompanyAddress($request->get('company_address'));
        $objHouseUser->setCompanyTaxNumber($request->get('company_tax_number'));
        $objHouseUser->setCreatedAt(new \DateTime('now'));
        $objHouseUser->setUpdatedAt(new \DateTime('now'));
        $objHouseUser->setWebsite($request->get('website'));
        $objHouseUser->setLogoImage($request->get('logo_image'));

        $entityManager->persist($objHouseUser);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler(
            "Manager has been created!",
            $request->query->all()
        );
    }
}