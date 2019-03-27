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

class DefaultController extends Controller
{
    const CONTENT_TYPE = 'application/json';
    const RESPONSE_FORMAT = 'json';

    public function indexAction()
    {
        return $this->render('ApiBundle:Default:index.html.twig');
    }

    private function callLogger($statusCode, $request)
    {
        $dateTime = (new \DateTime())->format('Y-m-d H:i:s');

        file_put_contents(
            "../tmp/api_log.txt",
            $dateTime.", ".$request->server->get('REQUEST_URI').", ".$statusCode.", ".print_r($request, TRUE)."\n\n",
            FILE_APPEND | LOCK_EX
        );
    }

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
            //TODO return logged in user
            $user = $this->get('security.token_storage')->getToken()->getUser();

            return $this->container->get('response_handler')->successHandler($user, $request->query->all());

            return $user;
        }

        $numUserID = $request->get("user_id");
        $objUser = $this->getDoctrine()->getRepository(User::class)->find($numUserID);

        if (!$objUser) {
            return $this->container->get('response_handler')->errorHandler("user_not_exists", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objUser, $request->query->all());
    }

    public function getDocumentsByHouseIdAction(Request $request)
    {
        if (!$request->get("house_id")) {
            return $this->container->get('response_handler')->errorHandler("no_house_id_provided", "Invalid parameters", 422);
        }

        $numHouseID = $request->get("house_id");
        $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);

        if (!$objHouse) {
            return $this->container->get('response_handler')->errorHandler("house_not_exists", "Not found", 404);
        }

        $objHouseDocuments = $objHouse->getDocuments();

        if (!$objHouseDocuments) {
            return $this->container->get('response_handler')->errorHandler("house_users_not_exist", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objHouseDocuments, $request->query->all());
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

    public function getPostsByHouseIdAction(Request $request)
    {
        if (!$request->get("house_id")) {
            return $this->container->get('response_handler')->errorHandler("no_house_id_provided", "Invalid parameters", 422);
        }

        $numHouseID = $request->get("house_id");
        $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);

        if (!$objHouse) {
            return $this->container->get('response_handler')->errorHandler("house_not_exists", "Not found", 404);
        }

        $objPost = $objHouse->getPosts();

        if (!$objPost) {
            return $this->container->get('response_handler')->errorHandler("post_not_exists", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objPost, $request->query->all());
    }

    public function getMalfunctionsByHouseIdAction(Request $request)
    {
        if (!$request->get("house_id")) {
            return $this->container->get('response_handler')->errorHandler("no_house_id_provided", "Invalid parameters", 422);
        }

        $numHouseID = $request->get("house_id");
        $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);

        if (!$objHouse) {
            return $this->container->get('response_handler')->errorHandler("house_not_exists", "Not found", 404);
        }

        $objMalfunction = $objHouse->getMalfunctions();

        if (!$objMalfunction) {
            return $this->container->get('response_handler')->errorHandler("malfunction_not_exists", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objMalfunction, $request->query->all());
    }

    public function postPostToHouseAction(Request $request)
    {
        if ($request->query->get('type') === NULL) {
            return $this->container->get('response_handler')->errorHandler("type_not_exists", "Not found", 404);
        }

        $type = $request->query->get('type');

        if ($type !== "post") {
            return $this->container->get('response_handler')->errorHandler("no_valid_type_provided", "Invalid parameters", 422);
        }

        if (!$request->query->get('subject') || !$request->query->get('content') || !$request->query->get('house_id')) {
            return $this->container->get('response_handler')->errorHandler("no_valid_data_provided", "Invalid parameters", 422);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $objPost = new Post();
        $objPost->setSubject($request->query->get('subject'));
        $objPost->setContent($request->query->get('content'));
        $objPost->setIsUrgent($request->query->get('is_urgent') ?? 0);

        $objHouse = $entityManager->find(House::class, $request->query->get('house_id'));
        $objPost->setHouse($objHouse);
        $objPost->setCreatedAt(new \DateTime('now'));
        $objPost->setUpdatedAt(new \DateTime('now'));
        $objPost->setDeletedAt(NULL);

        try {
            $entityManager->persist($objPost);
            $entityManager->flush();
        } catch (\Exception $objException) {
            return $this->container->get('response_handler')->errorHandler("cannot_save_post", $objException->getMessage(), 500);
        }

        return $this->container->get('response_handler')->successHandler($objPost, $request->query->all());
    }

    public function postMalfunctionToHouseAction(Request $request)
    {
        if ($request->query->get('type') === NULL) {
            return $this->container->get('response_handler')->errorHandler("type_not_exists", "Not found", 404);
        }

        $type = $request->query->get('type');

        if ($type !== "malfunction") {
            return $this->container->get('response_handler')->errorHandler("no_valid_type_provided", "Invalid parameters", 422);
        }

        if (!$request->query->get('subject') || !$request->query->get('content') || !$request->query->get('house_id')) {
            return $this->container->get('response_handler')->errorHandler("no_valid_data_provided", "Invalid parameters", 422);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $objMalfunction = new Malfunction();
        $objMalfunction->setSubject($request->query->get('subject'));
        $objMalfunction->setContent($request->query->get('content'));
        $objMalfunction->setStatus($request->query->get('status') ?? 1);

        $objHouse = $entityManager->find(House::class, $request->query->get('house_id'));
        $objMalfunction->setHouse($objHouse);
        $objMalfunction->setCreatedAt(new \DateTime('now'));
        $objMalfunction->setUpdatedAt(new \DateTime('now'));
        $objMalfunction->setDeletedAt(NULL);

        try {
            $entityManager->persist($objMalfunction);
            $entityManager->flush();
        } catch (\Exception $objException) {
            return $this->container->get('response_handler')->errorHandler("cannot_save_post", $objException->getMessage(), 500);
        }

        return $this->container->get('response_handler')->successHandler($objMalfunction, $request->query->all());
    }

    public function postCommentToSocialEntityAction(Request $request)
    {
        if ($request->query->get('type') === NULL) {
            return $this->container->get('response_handler')->errorHandler("type_not_exists", "Not found", 404);
        }

        $type = $request->query->get('type');

        if ($type !== "comment") {
            return $this->container->get('response_handler')->errorHandler("no_valid_type_provided", "Invalid parameters", 422);
        }

        if (!$request->query->get('content') || !$request->query->get('social_entity_id')) {
            return $this->container->get('response_handler')->errorHandler("no_valid_data_provided", "Invalid parameters", 422);
        }

        //FIXME user_id-t a bejelentkezett usertől kell szerezni
        $user_id = 1;

        $entityManager = $this->getDoctrine()->getManager();

        $objComment = new Comment();
        $objComment->setContent($request->query->get('content'));

        $objSocialEntity = $entityManager->find(SocialEntity::class, $request->query->get('social_entity_id'));

        //FIXME user id
        //$objUser = $entityManager->find(User::class, $request->query->get('user_id'));
        $objUser = $entityManager->find(User::class, $user_id);
        $objComment->setUser($objUser);
        $objComment->setSocialEntity($objSocialEntity);
        $objComment->setCreatedAt(new \DateTime('now'));
        $objComment->setUpdatedAt(new \DateTime('now'));
        $objComment->setDeletedAt(NULL);

        try {
            $entityManager->persist($objComment);
            $entityManager->flush();
        } catch (\Exception $objException) {
            return $this->container->get('response_handler')->errorHandler("cannot_save_post", $objException->getMessage(), 500);
        }

        return $this->container->get('response_handler')->successHandler($objComment, $request->query->all());
    }

    public function getCommentsBySocialEntityIdAction(Request $request)
    {
        if (!$request->get("social_entity_id")) {
            return $this->container->get('response_handler')->errorHandler("no_social_entity_id_provided", "Invalid parameters", 422);
        }

        $numSocialEntity = $request->get("social_entity_id");
        $objSocialEntity = $this->getDoctrine()->getRepository(SocialEntity::class)->find($numSocialEntity);

        if (!$objSocialEntity) {
            return $this->container->get('response_handler')->errorHandler("social_entity_not_exists", "Not found", 404);
        }

        $objComment = $objSocialEntity->getComments();

        if (!$objComment) {
            return $this->container->get('response_handler')->errorHandler("comment_not_exists", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objComment, $request->query->all());
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

    public function getBalanceByHouseIdAction(Request $request)
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

        $tblUnit = [];

        foreach ($objUnit as $key => $data) {
            $tblUnit[] = [
                'id' => $data->getId(),
                'address' => $data->getBuilding()." ".$data->getFloor()." ".$data->getDoor(),
                'building' => $data->getBuilding(),
                'floor' => $data->getFloor(),
                'door' => $data->getDoor(),
                'type' => $data->getType(), //FIXME ez egy szám, kell egy dictionary, hogy mi minek felel meg, pl. 1 = Lakás, 2 = Garázs
                //'date' => $data->getDate(),
                'date' => new \DateTime('now'), //FIXME valami dátumot kell visszadani
                'balance' => $data->getBalance(),
            ];
        }

        return $this->container->get('response_handler')->successHandler($tblUnit, $request->query->all());
    }

    public function getDetailedBalanceByUnitIdAction(Request $request)
    {
        if (!$request->get("unit_id")) {
            return $this->container->get('response_handler')->errorHandler("no_unit_id_provided", "Invalid parameters", 422);
        }

        $numUnitID = $request->get("unit_id");
        $objUnit = $this->getDoctrine()->getRepository(Unit::class)->find($numUnitID);

        if (!$objUnit) {
            return $this->container->get('response_handler')->errorHandler("unit_not_exists", "Not found", 404);
        }

        $objBills = $objUnit->getBills();
        $tblBills = [];
        $objPayments = $objUnit->getPayments();
        $tblPayments = [];

        foreach ($objBills as $key => $data) {
            if ($request->query->get('start_date') && $request->query->get('end_date')) {
                try {
                    $startDate = new \DateTime($request->query->get('start_date'));
                    $endDate = new \DateTime($request->query->get('end_date'));
                } catch (\Exception $e) {
                    return $this->container->get('response_handler')->errorHandler("wrong_date_format", "Invalid parameters", 422);
                }
                $billDate = new \DateTime('now');

                if ($billDate < $startDate || $billDate > $endDate) {
                    continue;
                }
            }
            $tblBills[] = [
                'name' => $data->getBillCategory(),
                'date' => new \DateTime('now'), //FIXME valami dátumot kell visszadani
                'amount' => $data->getAmount(),
                'itemDetails' => [
                    'invoiceNumber' => $data->getReceiptNumber(),
                    'dataProvider' => 'nincs_tarolva_a_dbben',
                    'other' => $data->getDetails(),
                    'issued_at' => $data->getIssuedAt(),
                    'due_date' => $data->getDueDate(),
                ],
            ];
        }

        foreach ($objPayments as $key => $data) {
            if ($request->query->get('start_date') && $request->query->get('end_date')) {
                try {
                    $startDate = new \DateTime($request->query->get('start_date'));
                    $endDate = new \DateTime($request->query->get('end_date'));
                } catch (\Exception $e) {
                    return $this->container->get('response_handler')->errorHandler("wrong_date_format", "Invalid parameters", 422);
                }
                $paymentDate = new \DateTime('now');

                if ($paymentDate < $startDate || $paymentDate > $endDate) {
                    continue;
                }
            }
            $tblPayments[] = [
                'name' => $data->getPaymentMethod()->getName(),
                'date' => new \DateTime('now'), //FIXME valami dátumot kell visszadani
                'amount' => $data->getAmount(),
                'itemDetails' => [
                    'invoiceNumber' => $data->getReceiptNumber(),
                    'paid_at' => $data->getPaymentDate(),
                    'booked_at' => $data->getBookingDate(),
                    'user' => [
                        'first_name' => ($data->getUser() ? $data->getUser()->getFirstName() : NULL),
                        'last_name' => ($data->getUser() ? $data->getUser()->getLastName() : NULL),
                        'id' => ($data->getUser() ? $data->getUser()->getId() : NULL),
                    ],
                    'houseUser' => [
                        'first_name' => ($data->getHouseUser() ? $data->getHouseUser()->getFirstName() : NULL),
                        'last_name' => ($data->getHouseUser() ? $data->getHouseUser()->getLastName() : NULL),
                        'id' => ($data->getHouseUser() ? $data->getHouseUser()->getId() : NULL),
                    ],
                ],
            ];
        }

        $tblUnit = [
            'id' => $objUnit->getId(),
            'address' => $objUnit->getBuilding()." ".$objUnit->getFloor()." ".$objUnit->getDoor(),
            'building' => $objUnit->getBuilding(),
            'floor' => $objUnit->getFloor(),
            'door' => $objUnit->getDoor(),
            'type' => $objUnit->getType(), //FIXME ez egy szám, kell egy dictionary, hogy mi minek felel meg, pl. 1 = Lakás, 2 = Garázs
            //'date' => $data->getDate(),
            'date' => new \DateTime('now'), //FIXME valami dátumot kell visszadani
            'balance' => $objUnit->getBalance(),
            'balanceItems' => [
                'bills' => $tblBills,
                'payments' => $tblPayments,
            ],
        ];

        return $this->container->get('response_handler')->successHandler($tblUnit, $request->query->all());
    }

    public function getBillsByUnitIdAction(Request $request)
    {
        if (!$request->get("unit_id")) {
            return $this->container->get('response_handler')->errorHandler("no_unit_id_provided", "Invalid parameters", 422);
        }

        $numUnitID = $request->get("unit_id");
        $objUnit = $this->getDoctrine()->getRepository(Unit::class)->find($numUnitID);

        if (!$objUnit) {
            return $this->container->get('response_handler')->errorHandler("unit_not_exists", "Not found", 404);
        }

        $objBills = $objUnit->getBills();
        $tblBills = [];

        foreach ($objBills as $key => $data) {
            if ($request->query->get('start_date') && $request->query->get('end_date')) {
                try {
                    $startDate = new \DateTime($request->query->get('start_date'));
                    $endDate = new \DateTime($request->query->get('end_date'));
                } catch (\Exception $e) {
                    return $this->container->get('response_handler')->errorHandler("wrong_date_format", "Invalid parameters", 422);
                }
                $billDate = new \DateTime('now');

                if ($billDate < $startDate || $billDate > $endDate) {
                    continue;
                }
            }
            $tblBills[] = [
                'name' => $data->getBillCategory(),
                'date' => new \DateTime('now'), //FIXME valami dátumot kell visszadani
                'amount' => $data->getAmount(),
                'itemDetails' => [
                    'invoiceNumber' => $data->getReceiptNumber(),
                    'dataProvider' => 'nincs_tarolva_a_dbben',
                    'other' => $data->getDetails(),
                    'issued_at' => $data->getIssuedAt(),
                    'due_date' => $data->getDueDate(),
                ],
            ];
        }

        return $this->container->get('response_handler')->successHandler($tblBills, $request->query->all());
    }

    public function getPaymentMethodsAction(Request $request)
    {
        $objPaymentMethod = $this->getDoctrine()->getRepository(PaymentMethod::class)->findAll();

        if (!$objPaymentMethod) {
            return $this->container->get('response_handler')->errorHandler("payment_method_not_exists", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objPaymentMethod, $request->query->all());
    }

    public function getRegistrationByTokenAction(Request $request)
    {
        if (!$request->get("token") || !$request->get("username") || !$request->get("first_name")) {
            return $this->container->get('response_handler')->errorHandler("invalid_params", "Invalid parameters", 422);
        }

        $strToken = $request->get("token");
        $objHouseUser = $this->getDoctrine()->getRepository(HouseUser::class)->findBy(['registrationToken' => $strToken]);

        if (!$objHouseUser) {
            return $this->container->get('response_handler')->errorHandler("token_not_exists", "Not found", 404);
        } else {
            if (count($objHouseUser) > 1) {
                return $this->container->get('response_handler')->errorHandler("more_than_one_house_user_found", "Not found", 404);
            }
        }

        $objHouseUser = $objHouseUser[0];
        $objUser = $objHouseUser->getUser();

        if ($objUser !== NULL) {
            return $this->container->get('response_handler')->errorHandler("user_already_registered", "Invalid parameters", 422);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $strDateOfBirth = new \DateTime($request->get("date_of_birth"));
        $objUser = new User();
        $objUser->setFirstName($request->get("first_name"));
        $objUser->setLastName($request->get("last_name"));
        $objUser->setRegistrationDate(new \DateTime('now'));
        $objUser->setDateOfBirth($strDateOfBirth);
        $objUser->setPlaceOfBirth($request->get("place_of_birth"));
        $objUser->setBio($request->get("bio"));
        $objUser->setSex($request->get("sex"));
        $objUser->setPhoneNumber($request->get("phone_number"));
        $objUser->setLocalPhoneNumber($request->get("local_phone_number"));
        $objUser->setOfficialAddress($request->get("official_address"));
        $objUser->setCurrentLocation($request->get("current_location"));
        $objUser->setJoinToken($request->get("token"));
        $objUser->setApiKey(substr(base64_encode(sha1(mt_rand())), 0, 64));
        $objUser->setUsername($request->get("username"));

        $entityManager->persist($objUser);
        $entityManager->flush();

        $objHouseUser->setUser($objUser);

        $entityManager->persist($objHouseUser);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler($objUser, $request->query->all());
    }

    public function loginAction(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $userManager = $this->get('fos_user.user_manager');

        if (!$email || !$password) {
            return $this->container->get('response_handler')->errorHandler("missing_credentials", "Invalid parameters", 422);
        }

        $objUser = $this->getDoctrine()->getRepository(User::class)->findBy(['email' => $email]);
        if (!$objUser) {
            $objUser = $this->getDoctrine()->getRepository(User::class)->findBy(['username' => $email]);
        }

        if (!$objUser) {
            return $this->container->get('response_handler')->errorHandler("user_not_found", "Not found", 404);
        } else {
            if (count($objUser) > 1) {
                return $this->container->get('response_handler')->errorHandler("more_than_one_house_user_found", "Not found", 404);
            }
        }

        $objUser = $objUser[0];

        //return $this->container->get('response_handler')->successHandler($objUser, $request->query->all());
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($objUser);

        $isValidPassword = $encoder->isPasswordValid($objUser->getPassword(), $password, $objUser->getSalt());

        if (!$isValidPassword) {
            return $this->container->get('response_handler')->errorHandler("invalid_credentials", "Invalid parameters", 422);
        }

        var_dump("wow you have made it");
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

    public function postImportDocumentAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'type' => 'required',
                'user_id' => 'required|numeric',
                'document_type_id' => 'required|numeric',
                'house_id' => 'required|numeric',
                'name' => 'required',
                'filename' => 'required',
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
        if ($request->get('type') !== 'document') {
            return $this->container->get('response_handler')->errorHandler("invalid_entity_type", "Invalid parameters", 422);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $objDocument = new Document();
        if ($request->get('user_id')) {
            $numUserID = $request->get("user_id");
            $objUser = $this->getDoctrine()->getRepository(User::class)->find($numUserID);
            if (!$objUser) {
                return $this->container->get('response_handler')->errorHandler("invalid_user_id", "Invalid parameters", 422);
            }
            $objDocument->setUser($objUser);
        }
        if ($request->get('house_id')) {
            $numHouseID = $request->get("house_id");
            $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);
            if (!$objHouse) {
                return $this->container->get('response_handler')->errorHandler("invalid_house_id", "Invalid parameters", 422);
            }
            $objDocument->setHouse($objHouse);
        }
        if ($request->get('document_type_id')) {
            $numTypeID = $request->get('document_type_id');
            $objDocumentType = $this->getDoctrine()->getRepository(DocumentType::class)->find($numTypeID);
            if (!$objDocumentType) {
                return $this->container->get('response_handler')->errorHandler("invalid_document_type", "Invalid parameters", 422);
            }
            $objDocument->setDocumentType($objDocumentType);
        }
        $objDocument->setName($request->get('name'));
        $objDocument->setFilename($request->get('filename'));
        $objDocument->setCreatedAt(new \DateTime('now'));
        $objDocument->setUpdatedAt(new \DateTime('now'));

        $entityManager->persist($objDocument);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler(
            "Document has been created!",
            $request->query->all()
        );
    }

    public function postImportBillAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'unit_id' => 'required|numeric',
                'amount' => 'required|numeric',
                'receipt_number' => 'required|numeric',
                'issued_for_month' => 'required|numeric',
                'bill_category' => 'required',
                'details' => 'required',
                'issued_at' => 'required|date',
                'due_date' => 'required|date',
                'api_key' => 'required',
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
        $objBill = new Bill();
        if ($request->get('unit_id')) {
            $numUnitID = $request->get("unit_id");
            $objUnit = $this->getDoctrine()->getRepository(Unit::class)->find($numUnitID);
            if (!$objUnit) {
                return $this->container->get('response_handler')->errorHandler("invalid_unit_id", "Invalid parameters", 422);
            }
            $objBill->setUnit($objUnit);
        }
        $objBill->setAmount($request->get('amount'));
        $objBill->setBillCategory($request->get('bill_category'));
        $objBill->setStatus(1);
        $objBill->setDetails($request->get('details'));
        $objBill->setReceiptNumber($request->get('receipt_number'));
        $objBill->setIssuedForMonth($request->get('issued_for_month'));
        $objBill->setCreatedAt(new \DateTime('now'));
        $objBill->setIssuedAt(new \DateTime($request->get('issued_at')));
        $objBill->setDueDate(new \DateTime($request->get('due_date')));

        $entityManager->persist($objBill);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler(
            "Bill has been created!",
            $request->query->all()
        );
    }

    public function getExportPaymentsAction(Request $request)
    {
        $objPayment = NULL;
        if ($request->query->get('payment_month')) {
            $objPayment = $this->getDoctrine()->getRepository(Payment::class)->findAllInMonth($request->query->get('payment_month'));
        } else if ($request->query->get('start_date') && $request->query->get('end_date')) {
            $objPayment = $this->getDoctrine()->getRepository(Payment::class)->findAllInInterval(
                $request->query->get('start_date'),
                $request->query->get('end_date')
            );
        } else if ($request->query->get('start_date')) {
            $objPayment = $this->getDoctrine()->getRepository(Payment::class)->findAllAfterMonth($request->query->get('start_date'));
        } else if ($request->query->get('end_date')) {
            $objPayment = $this->getDoctrine()->getRepository(Payment::class)->findAllBeforeMonth($request->query->get('end_date'));
        }

        if (!$objPayment) {
            return $this->container->get('response_handler')->errorHandler("no_payment_found", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objPayment, []);
    }

    public function successfulBorgunPaymentAction(Request $request)
    {
        if ($request->get('status') !== 'OK') {
            return $this->container->get('response_handler')->errorHandler("status_not_ok", "Invalid parameters", 400);
        }

        $orderID = $request->get('orderid');
        $authorizationCode = $request->get('authorizationcode');
        $cerditCardNumber = $request->get('creditcardnumber');
        $amount = $request->get('amount');
        $currency = $request->get('currency');
        $orderHash = $request->get('orderhash');
        $merchantID = $request->get('marchantid');
        $buyerName = $request->get('buyername');
        $buyerEmail = $request->get('buyeremail');
        $step = $request->get('step');
        $isDebit = $request->get('isdebit');

        $objOrder = $this->getDoctrine()->getRepository(Order::class)->findBy(
            [
                'orderId' => $orderID,
                'isPaid' => 0,
                'checkHash' => $orderHash,
            ]
        );

        if (!$objOrder || count($objOrder) > 1) {
            return $this->container->get('response_handler')->errorHandler("status_not_ok", "Invalid parameters", 400);
            //mit csinálunk, ha nem egyezik?
        }

        $objOrder = $objOrder[0];

        $entityManager = $this->getDoctrine()->getManager();

        $objPayment = new Payment();
        if (TRUE) { //FIXME
            $numPaymentMethodID = 1; //$request->get("unit_id");
            $objPaymentMethod = $this->getDoctrine()->getRepository(PaymentMethod::class)->find($numPaymentMethodID);
            if (!$objPaymentMethod) {
                return $this->container->get('response_handler')->errorHandler("invalid_payment_method_id", "Invalid parameters", 422);
            }
            $objPayment->setPaymentMethod($objPaymentMethod);
        }
        $objPayment->setPaymentDate(new \DateTime('now'));
        $objPayment->setBookingDate(new \DateTime('now'));
        $objPayment->setReceiptNumber($orderID);
        $objPayment->setAmount($amount);
        $objPayment->setStatus(1);
        $objPayment->setUnit($objOrder->getUnit());
        //FIXME save houseuser
        //FIXME save user

        $entityManager->persist($objPayment);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler("Payment saved!", []);
    }

    public function initBorgunPaymentAction(Request $request)
    {
        $param = $request->get('bills');
        if (!$param) {
            return $this->container->get('response_handler')->errorHandler("no_bill_found", "Not found", 404);
        }
        if (!$request->get('unit_id') || !is_numeric($request->get('unit_id'))) {
            return $this->container->get('response_handler')->errorHandler("no_unit_id_found", "Not found", 404);
        }
        $tblParam = json_decode($param, TRUE);
        $tblItems = [];
        $numSumAmount = 0;

        foreach ($tblParam as $rowParam) {
            $objBill = $this->getDoctrine()->getRepository(Bill::class)->findBy(
                [
                    'amount' => $rowParam["amount"],
                    'id' => $rowParam["id"],
                ]
            );

            if (!$objBill || count($objBill) > 1) {
                //logolni a csalást
                return $this->container->get('response_handler')->errorHandler("status_not_ok", "Invalid parameters", 400);
            }

            $objBill = $objBill[0];
            $tblItems[] = [
                'itemdescription_0' => $objBill->getDetails(),
                'itemcount_0' => 1,
                'itemunitamount_0' => $objBill->getAmount(),
                'itemamount_0' => $objBill->getAmount(),
            ];
            $numSumAmount += (int)$objBill->getAmount();
        }

        $repository = $this->getDoctrine()->getRepository(Order::class);
        $count = $repository->countAll();

        $entityManager = $this->getDoctrine()->getManager();

        $objOrder = new Order();
        $objOrder->setOrderId(str_pad(++$count, 12, 0, STR_PAD_LEFT));
        $objOrder->setIsPaid(0);
        $objOrder->setCart(json_encode($tblItems));
        $objOrder->setCreatedAt(new \DateTime('now'));
        $objOrder->setUpdatedAt(new \DateTime('now'));
        if (TRUE) { //FIXME
            $numUserID = 1; //$request->get("unit_id");
            $objUser = $this->getDoctrine()->getRepository(User::class)->find($numUserID);
            if (!$objUser) {
                return $this->container->get('response_handler')->errorHandler("invalid_user", "Invalid parameters", 422);
            }
            $objOrder->setUser($objUser);
        }
        if (TRUE) { //FIXME
            $numPaymentMethodID = 1; //$request->get("unit_id");
            $objPaymentMethod = $this->getDoctrine()->getRepository(PaymentMethod::class)->find($numPaymentMethodID);
            if (!$objPaymentMethod) {
                return $this->container->get('response_handler')->errorHandler("invalid_payment_method_id", "Invalid parameters", 422);
            }
            $objOrder->setPaymentMethod($objPaymentMethod);
        }

        $objUnit = $this->getDoctrine()->getRepository(Unit::class)->find($request->get('unit_id'));
        if (!$objUnit) {
            return $this->container->get('response_handler')->errorHandler("invalid_unit_id", "Invalid parameters", 422);
        }
        $objOrder->setUnit($objUnit);

        $entityManager->persist($objOrder);
        $entityManager->flush();

        $postURL = $this->getParameter('borgun_url');
        $merchantID = $this->getParameter('borgun_merchantid');
        $paymentGatewayID = $this->getParameter('borgun_paymentgatewayid');
        $successURL = $this->getParameter('borgun_returnurlsuccess');
        $successAPIURL = $this->getParameter('borgun_returnurlsuccessserver');
        $cancelURL = $this->getParameter('borgun_returnurlcancel');
        $errorURL = $this->getParameter('borgun_returnurlerror');
        $secretKey = $this->getParameter('borgun_secret');
        $defaultCurrency = $this->getParameter('borgun_default_curreny');
        $defaultLanguage = $this->getParameter('borgun_default_language');

        $strOrderID = $objOrder->getOrderId();

        $message = utf8_encode($merchantID.'|'.$successURL.'|'.$successAPIURL.'|'.$strOrderID.'|'.$numSumAmount.'|'.$defaultCurrency);

        $checkhash = hash_hmac('sha256', $message, $secretKey);

        $objOrder->setCheckHash($checkhash);

        $entityManager->persist($objOrder);
        $entityManager->flush();

        $response = [
            'action' => $postURL,
            'merchantid' => $merchantID,
            'paymentgatewayid' => $paymentGatewayID,
            'checkhash' => $checkhash,
            'orderid' => $strOrderID,
            'currency' => $defaultCurrency,
            'language' => $defaultLanguage,
            'buyername' => 'Frici',
            'buyeremail' => 'frici@myemail.com',
            'returnurlsuccess' => $successURL,
            'returnurlsuccessserver' => $successAPIURL,
            'returnurlcancel' => $cancelURL,
            'returnurlerror' => $errorURL,
            'pagetype ' => 0,
            'skipreceiptpage ' => 0,
            'merchantemail' => 'info@hazfal.hu',
            'items' => $tblItems,
        ];

        return $this->container->get('response_handler')->successHandler($response, []);
    }
}
