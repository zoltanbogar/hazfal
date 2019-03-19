<?php

namespace ApiBundle\Controller;

use ApiBundle\Service\ResponseHandler;

use AppBundle\Entity\Comment;
use AppBundle\Entity\House;
use AppBundle\Entity\HouseUser;
use AppBundle\Entity\Malfunction;
use AppBundle\Entity\PaymentMethod;
use AppBundle\Entity\Post;
use AppBundle\Entity\SocialEntity;
use AppBundle\Entity\Unit;
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
}
