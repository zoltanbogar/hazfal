<?php

namespace ApiBundle\Controller;

use ApiBundle\Service\ResponseHandler;

use AppBundle\Entity\Bill;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentType;
use AppBundle\Entity\House;
use AppBundle\Entity\HouseUser;
use AppBundle\Entity\ImportedBill;
use AppBundle\Entity\ImportedHouseUser;
use AppBundle\Entity\ImportedPayment;
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

class FinanceController extends Controller
{
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
                'address' => $data->getBuilding() . ". " . $data->getFloor() . " em. " . $data->getDoor() . " ajtó",
                'building' => $data->getBuilding(),
                'floor' => $data->getFloor(),
                'door' => $data->getDoor(),
                'type' => $data->getType() == 1 ? "Lakás" : "Garázs",
                //FIXME ez egy szám, kell egy dictionary, hogy mi minek felel meg, pl. 1 = Lakás, 2 = Garázs
                //'date' => $data->getDate(),
                'date' => date('Y.m.d'),
                //new \DateTime('now'),//FIXME valami dátumot kell visszadani
                'balance' => -1 * $data->getBalance(),
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
                'id' => $data->getId(),
                'name' => $data->getBillCategory(),
                'date' => $data->getCreatedAt()->format('Y.m.d'),//new \DateTime('now'), //FIXME valami dátumot kell visszadani
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
                'date' => $data->getPaymentDate()->format('Y.m.d'),//new \DateTime('now'), //FIXME valami dátumot kell visszadani
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
            'address' => $objUnit->getBuilding() . " ép. " . $objUnit->getFloor() . ". em. " . $objUnit->getDoor(),
            'building' => $objUnit->getBuilding(),
            'floor' => $objUnit->getFloor(),
            'door' => $objUnit->getDoor(),
            'type' => $objUnit->getType(), //FIXME ez egy szám, kell egy dictionary, hogy mi minek felel meg, pl. 1 = Lakás, 2 = Garázs
            //'date' => $data->getDate(),
            'date' => date('Y.m.d'), //new \DateTime('now'), //FIXME valami dátumot kell visszadani
            'balance' => -1 * $objUnit->getBalance(),
            'balanceItems' => [
                'bills' => $tblBills,
                'payments' => $tblPayments,
            ],
        ];

        return $this->container->get('response_handler')->successHandler($tblUnit, $request->query->all());
    }


    public function getPaymentMethodsAction(Request $request)
    {
        $objPaymentMethod = $this->getDoctrine()->getRepository(PaymentMethod::class)->findAll();

        if (!$objPaymentMethod) {
            return $this->container->get('response_handler')->errorHandler("payment_method_not_exists", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objPaymentMethod, $request->query->all());
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

        $isAlreadyAdded = $this->getDoctrine()->getRepository(ImportedBill::class)->findBy(['externalId' => $request->get('id'), 'isAccepted' => 1]);

        if ($isAlreadyAdded) {
            return $this->container->get('response_handler')->errorHandler('duplication', 'Already imported!', 400);
        }

        $entityManager = $this->getDoctrine()->getManager();
        /*$objBill = new Bill();
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

        $entityManager->persist($objBill);*/
        $objBill = new ImportedBill();
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
        $objBill->setDetails($request->get('details'));
        $objBill->setReceiptNumber($request->get('receipt_number'));
        $objBill->setIssuedForMonth($request->get('issued_for_month'));
        $objBill->setIssuedAt(new \DateTime($request->get('issued_at')));
        $objBill->setDueDate(new \DateTime($request->get('due_date')));
        $objBill->setImportedAt(new \DateTime('now'));
        $objBill->setExternalId($request->get('id'));
        $objBill->setIsAccepted(0);
        $objBill->setImportSource($objImportSource);

        $entityManager->persist($objBill);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler(
            "Bill has been imported!",
            $request->query->all()
        );
    }

    public function postImportPaymentAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'unit_id' => 'required|numeric',
                //'payment_method_id' => 'required|numeric',
                'user_id' => 'required|numeric',
                'house_user_id' => 'required|numeric',
                'payment_date' => 'required|date',
                'booking_date' => 'required|date',
                'receipt_number' => 'required|numeric',
                'amount' => 'required|numeric',
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

        $isAlreadyAdded = $this->getDoctrine()->getRepository(ImportedPayment::class)->findBy(['externalId' => $request->get('id'), 'isAccepted' => 1]);

        if ($isAlreadyAdded) {
            return $this->container->get('response_handler')->errorHandler('duplication', 'Already imported!', 400);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $objPayment = new ImportedPayment();
        $objPayment->setUnitId($request->get('unit_id'));
        $objPayment->setAmount($request->get('amount'));
        $objPayment->setReceiptNumber($request->get('receipt_number'));
        $objPayment->setBookingDate(new \DateTime($request->get('booking_date')));
        $objPayment->setPaymentDate(new \DateTime($request->get('payment_date')));
        $objPayment->setHouseUserId($request->get('house_user_id'));
        $objPayment->setUserId($request->get('user_id'));
        $objPayment->setImportedAt(new \DateTime('now'));
        $objPayment->setExternalId($request->get('id'));
        $objPayment->setIsAccepted(0);
        $objPayment->setImportSource($objImportSource);
        //var_dump($objPayment->getUnitId());
        //die("asdas");
        $entityManager->persist($objPayment);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler(
            "Payment has been imported!",
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
        } else {
            $objPayment = $this->getDoctrine()->getRepository(Payment::class)->findAll();
        }

        if (!$objPayment) {
            return $this->container->get('response_handler')->errorHandler("no_payment_found", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objPayment, []);
    }

    public function successfulBorgunPaymentAction(Request $request)
    {
        if ($request->get('status') !== 'OK') {
            error_log('status_not_ok: '.$orderId);
            return $this->redirect($this->getParameter('frontend_url')."/fizetes/bankkartyas-fizetes/sikertelen");
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
        $defaultCurrency = $this->getParameter('borgun_default_curreny');

        $secretKey = $this->getParameter('borgun_secret');

        $message = utf8_encode($orderID.'|'.$amount.'|'.$defaultCurrency);

        $checkhash = hash_hmac('sha256', $message, $secretKey);

        $objOrder = $this->getDoctrine()->getRepository(Order::class)->findBy(
            [
                'orderId' => $orderID
            ]
        );

        if (!$objOrder || count($objOrder) > 1) {
            error_log('Sikertelen fizetes: '.$orderId);
            return $this->redirect($this->getParameter('frontend_url')."/fizetes/bankkartyas-fizetes/sikertelen");
            //return $this->container->get('response_handler')->errorHandler("cant_find_object", "Invalid parameters", 400);
            //mit csinálunk, ha nem egyezik?
        }

        $objOrder = $objOrder[0];
        $objOrder->setIsPaid(1);
        $entityManager = $this->getDoctrine()->getManager();
        
        $entityManager->persist($objOrder);
        $objPayment = new Payment();

        if (TRUE) { //FIXME
            $numPaymentMethodID = 1; //$request->get("unit_id");
            $objPaymentMethod = $this->getDoctrine()->getRepository(PaymentMethod::class)->find($numPaymentMethodID);
            if (!$objPaymentMethod) {
                error_log('invalid payment method: '.$orderId);
                return $this->redirect($this->getParameter('frontend_url')."/fizetes/bankkartyas-fizetes/sikertelen");
                //return $this->container->get('response_handler')->errorHandler("invalid_payment_method_id", "Invalid parameters", 422);
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
        
        return $this->redirect($this->getParameter('frontend_url')."/fizetes/bankkartyas-fizetes/sikeres");
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
        $tblParam = $param;
        $tblItems = [];
        $numSumAmount = 0;

        foreach ($tblParam as $key => $rowParam) {
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
                'Itemdescription_'.$key => $objBill->getDetails(),
                'Itemcount_'.$key => 1,
                'Itemunitamount_'.$key => $objBill->getAmount(),
                'Itemamount_'.$key => $objBill->getAmount(),
            ];
            $numSumAmount += (int)$objBill->getAmount();
        }

        $repository = $this->getDoctrine()->getRepository(Order::class);
        $count = $repository->countAll();

        $entityManager = $this->getDoctrine()->getManager();
        $objUser = $this->getDoctrine()->getRepository(User::class)->findUserByApiKey($request->headers->get('X-AUTH-TOKEN'));

        $objOrder = new Order();
        $objOrder->setOrderId(str_pad(++$count, 12, 0, STR_PAD_LEFT));
        $objOrder->setIsPaid(0);
        $objOrder->setCart(json_encode($tblItems));
        $objOrder->setCreatedAt(new \DateTime('now'));
        $objOrder->setUpdatedAt(new \DateTime('now'));
        $objOrder->setUser($objUser);
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
        $successAPIURL =$this->getParameter('borgun_returnurlsuccessserver');
        $cancelURL = $this->getParameter('borgun_returnurlcancel');
        $errorURL = $this->getParameter('borgun_returnurlerror');
        $secretKey = $this->getParameter('borgun_secret');
        $defaultCurrency = $this->getParameter('borgun_default_curreny');
        $defaultLanguage = $this->getParameter('borgun_default_language');

        $strOrderID = $objOrder->getOrderId();

        $message = utf8_encode($merchantID . '|' . $successURL . '|' . $successAPIURL . '|' . $strOrderID . '|' . $numSumAmount . '|' . $defaultCurrency);

        $checkhash = hash_hmac('sha256', $message, $secretKey);

        $objOrder->setCheckHash($checkhash);

        $entityManager->persist($objOrder);
        $entityManager->flush();

        $response = [
            'action' => $postURL,
            'Merchantid' => $merchantID,
            'paymentgatewayid' => $paymentGatewayID,
            'checkhash' => $checkhash,
            'Orderid' => $strOrderID,
            'amount' => $numSumAmount,
            'currency' => $defaultCurrency,
            'language' => $defaultLanguage,
            'buyername' => $objUser->getFullname(),
            'buyeremail' => $objUser->getEmail(),
            'returnurlsuccess' => $successURL,
            'returnurlsuccessserver' => $successAPIURL, // conditional
            'returnurlcancel' => $cancelURL,
            'returnurlerror' => $errorURL,
            'pagetype' => 0,
            'skipreceiptpage' => 0,
            'merchantemail' => 'info@hazfal.hu',
            //'items' => $tblItems,
        ];
        foreach ($tblItems as $key => $item) {
            foreach($item as $billKey => $billItem) {
                $response[$billKey] = $billItem;
            }
        }
        return $this->container->get('response_handler')->successHandler($response, []);
    }

    public function unsuccessBorgunPaymentAction(Request $request)
    {
        $params = $request->getContent();
        $baseUrl = $this->getParameter('frontend_url')."/fizetes/bankkartyas-fizetes/sikertelen";
        if($params)
            $baseUrl .= "?".$params;
        return $this->redirect($baseUrl);
    }
}
