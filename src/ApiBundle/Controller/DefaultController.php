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

    public function postSendSMSAction(Request $request)
    {
        if (!$request->get('phone_number')) {
            return $this->container->get('response_handler')->errorHandler("invalid_phone_number", "Invalid parameters", 422);
        }

        $token = $request->headers->get('X-AUTH-TOKEN');
        $entityManager = $this->getDoctrine()->getManager();

        $objUser = $entityManager->getRepository(User::class)->findUserByApiKey($token);

        if (!$objUser) {
            return $this->container->get('response_handler')->errorHandler("invalid_token", "Invalid parameters", 422);
        }

        $strPhoneNumber = $request->get('phone_number');
        if (strpos($strPhoneNumber, '+') !== 0) {
            $strPhoneNumber = '+'.$strPhoneNumber;
        }
        $strPhoneNumber = str_replace(' ', '', $strPhoneNumber);

        $account_sid = $this->getParameter('twilio_account_sid');
        $auth_token = $this->getParameter('twilio_auth_token');
        $twilio_number = $this->getParameter('twilio_phone');

        //TODO ha nem telt el még mondjuk 10 perc, akkor adjuk vissza az előző kódot, ne generáljunk újat
        $strCode = $num_str = sprintf("%06d", mt_rand(1, 999999));;

        try {
            $client = new Client($account_sid, $auth_token);
            $client->messages->create(
                $strPhoneNumber,
                [
                    'from' => $twilio_number,
                    'body' => $strCode,
                ]
            );
        } catch (\Exception $objException) {
            return $this->container->get('response_handler')->errorHandler($objException->getLine(), $objException->getMessage(), 500);
        }

        $objUser->setPhoneConformationSentAt(new \DateTime('now'));
        $objUser->setPhoneConformationCode($strCode);
        $objUser->setPhoneNumber($strPhoneNumber);

        $entityManager->persist($objUser);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler('SMS Sent!', []);
    }

    public function putSavePhoneNumberAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'sms_code' => 'required|numeric',
            ],
            $request
        );

        if ($validator['hasError']) {
            return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
        }

        $token = $request->headers->get('X-AUTH-TOKEN');
        $entityManager = $this->getDoctrine()->getManager();

        $objUser = $entityManager->getRepository(User::class)->findUserByApiKey($token);

        if (!$objUser) {
            return $this->container->get('response_handler')->errorHandler("invalid_token", "Invalid parameters", 422);
        }

        if ($objUser->getPhoneConformationCode() !== $request->get('sms_code')) {
            return $this->container->get('response_handler')->errorHandler("invalid_sms_code", "Invalid parameters", 422);
        }

        $objUser->setPhoneConformationSentAt(NULL);
        $objUser->setPhoneConformationCode(NULL);

        $entityManager->persist($objUser);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler('Phone number saved!', []);
    }
}
