<?php

namespace ApiBundle\Controller;

use ApiBundle\Service\ResponseHandler;

use AppBundle\Entity\HouseUser;
use AppBundle\Entity\Permission;
use AppBundle\Entity\Registration;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Ranvis\Identicon\Tile;
use Ranvis\Identicon\Identicon;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $userManager = $this->get('fos_user.user_manager');

        if (!$email || !$password) {
            return $this->container->get('response_handler')->errorHandler("missing_credentials", "Invalid parameters", 422);
        }

        $objUser = $userManager->findUserByEmail($email);//$this->getDoctrine()->getRepository(User::class)->findUserByEmail($email);
        if (!$objUser) {
            $objUser = $userManager->findUserByUsername($email);//$this->getDoctrine()->getRepository(User::class)->findUserByUsername($email);
        }

        if (!$objUser) {
            return $this->container->get('response_handler')->errorHandler("user_not_found", "Not found", 404);
        }

        $defaultEncoder = new MessageDigestPasswordEncoder('sha512', TRUE, 5000);

        $encoders = [
            User::class => $defaultEncoder,
        ];
        $encoderFactory = new EncoderFactory($encoders);
        $encoder = $encoderFactory->getEncoder($objUser);

        $isValidPassword = $encoder->isPasswordValid($objUser->getPassword(), $password, $objUser->getSalt());

        if (!$isValidPassword) {
            return $this->container->get('response_handler')->errorHandler("invalid_credentials", "Invalid password", 422);
        }

        $token = new UsernamePasswordToken($objUser, $objUser->getPassword(), $objUser->getApiKey(), $objUser->getRoles());
        $this->get('security.token_storage')->setToken($token);

        $this->get('session')->set('_security_main', serialize($token));

        // Fire the login event manually
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

        $userData = [];
        $userData['id'] = $objUser->getId();
        $userData['accessToken'] = $objUser->getApiKey();
        $userData['name'] = $objUser->getLastName() . " " . $objUser->getFirstName();
        $userData['profileUrl'] = "/assets/images/profile/" . $objUser->getProfileImage();
        $userData['houses'] = [];
        $units = [];

        $houseUsers = $objUser->getHouseUsers();
        foreach ($houseUsers as $houseUser) {
            if ($houseUser) {
                $house = $houseUser->getHouse();
                $tenant = $houseUser->getUnitTenant()->first();
                if ($tenant) {
                    foreach ($tenant->getUnits() as $unit) {
                        $units[] = [
                            'id' => $unit->getId(),
                            'address' => $unit->getBuilding() . ". " . $unit->getFloor() . " em. " . $unit->getDoor() . " ajtó",
                            'type' => $unit->getType()
                        ];
                    }
                }
                $userData['houses'][] = [
                    'id' => $house->getId(),
                    'address' => $house->getPostalCode() . " " . $house->getCity() . ", " . $house->getStreet() . " " . $house->getBuilding(),
                    'units' => $units
                ];
            }
        }

        return $this->container->get('response_handler')->successHandler($userData, $request->query->all());
    }

    public function getRegistrationByTokenAction(Request $request)
    {
        if (!$request->get("token") || !$request->get("first_name") || !$request->get("last_name") || !$request->get("gender")) {
            return $this->container->get('response_handler')->errorHandler("invalid_params", "Minden mező kitöltése kötelező", 422);
        }

        $strToken = $request->get("token");
        $objHouseUser = $this->getDoctrine()->getRepository(HouseUser::class)->findBy(['registrationToken' => $strToken]);

        if (!$objHouseUser) {
            return $this->container->get('response_handler')->errorHandler("token_not_exists", "Regisztrációs kód nem található", 404);
        } else {
            if (count($objHouseUser) > 1) {
                return $this->container->get('response_handler')->errorHandler("more_than_one_house_user_found", "Több felhasználónál is szerepel a kód", 422);
            }
        }

        $objHouseUser = $objHouseUser[0];
        $objUser = $objHouseUser->getUser();

        if ($objUser !== NULL) {
            return $this->container->get('response_handler')->errorHandler("user_with_token_already_registered", "A kód (" . $strToken . ") már fel lett használva", 422);
        }
        /**
         * $objUser = $this->getDoctrine()->getRepository(User::class)->findUserByUsername($request->get("username"));
         *
         * if ($objUser) {
         * return $this->container->get('response_handler')->errorHandler("username_already_registered", "Invalid parameters", 422);
         * }
         */
        $objUser = $this->getDoctrine()->getRepository(User::class)->findUserByEmail($request->get("email"));

        if ($objUser) {
            return $this->container->get('response_handler')->errorHandler("user_email_already_registered", "E-mail cím foglalt", 422);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $objPermission = $entityManager->getRepository(Permission::class)->findOneBy(['slug' => 'user']);

        $strDateOfBirth = new \DateTime($request->get("date_of_birth"));
        $user->setFirstName($request->get("first_name"));
        $user->setLastName($request->get("last_name"));
        $user->setRegistrationDate(new \DateTime('now'));
        $user->setDateOfBirth($strDateOfBirth);
        $user->setPlaceOfBirth($request->get("place_of_birth"));
        $user->setBio($request->get("bio"));
        $user->setSex($request->get("gender"));
        //$user->setPhoneNumber($request->get("phone_number"));
        $user->setLocalPhoneNumber($request->get("local_phone_number"));
        $user->setOfficialAddress($request->get("official_address"));
        $user->setCurrentLocation($request->get("current_location"));
        $user->setJoinToken($request->get("token"));
        $user->setApiKey(substr(base64_encode(sha1(mt_rand())), 0, 64));
        $user->setUsername($request->get("username"));
        $user->setEmail($request->get("email"));
        $user->setPlainPassword($request->get("password"));
        $user->setPassword($request->get("password"));
        $user->setEnabled(FALSE);
        $user->setRoles(['ROLE_USER']);

        //TODO send confirmation email

        $userManager->updateUser($user);

        $objHouseUser->setUser($user);

        $entityManager->flush();

        $user->addPermission($objPermission);
        $entityManager->persist($user);
        $entityManager->flush();

        $this->sendConfirmationEmailToUser($user, $request);

        $identiconDir = 'images/identicon';
        $identiconFile = $user->getId() . '.png';
        if (!file_exists($identiconDir)) {
            mkdir($identiconDir, 0777, TRUE);
        }

        $tile = new Tile();

        do {
            $numTiles = rand(3, 8);
            $numColors = rand(2, 4);
            $identicon = new Identicon(256, $tile, $numTiles, $numColors, TRUE);
            $minHashLenght = $identicon->getMinimumHashLength();
        } while ($minHashLenght >= 33);

        $hash = md5($user->getId() . $user->getSalt());
        try {
            $draw = $identicon->draw($hash);
            $draw->save($identiconDir . '/' . $identiconFile);
        } catch (\Exception $objException) {
            $numTiles = 7;
            $numColors = 2;
            $identicon = new Identicon(256, $tile, $numTiles, $numColors, TRUE);
            $draw = $identicon->draw($hash);
            $draw->save($identiconDir . '/' . $identiconFile);
        }

        $defaultEncoder = new MessageDigestPasswordEncoder('sha512', TRUE, 5000);

        $encoders = [
            User::class => $defaultEncoder,
        ];
        $encoderFactory = new EncoderFactory($encoders);
        $encoder = $encoderFactory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($request->get("password"), $user->getSalt());
        $user->setPassword($encodedPassword);
        $user->setProfileImage($identiconFile);
        $userManager->updateUser($user);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler($user, $request->query->all());
    }

    /**
     * get the houseuser by a token for registration
     * @author pali
     * @param Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function getHouseUserByTokenAction(Request $request)
    {
        if (!$request->get("token")) {
            return $this->container->get('response_handler')->errorHandler("no_house_user_token_provided", "Hiányzó belépő kód", 422);
        }

        $strToken = $request->get("token");
        $objHouseUser = $this->getDoctrine()->getRepository(HouseUser::class)->findBy(['registrationToken' => $strToken]);

        if (!$objHouseUser) {
            return $this->container->get('response_handler')->errorHandler("token_not_exists", "Nem megfelelő kód", 404);
        } else {
            if (count($objHouseUser) > 1) {
                return $this->container->get('response_handler')->errorHandler("more_than_one_house_user_found", "Invalid credentials", 422);
            }
        }

        $objHouseUser = $objHouseUser[0];
        $objUser = $objHouseUser->getUser();

        if ($objUser !== NULL) {
            return $this->container->get('response_handler')->errorHandler("user_with_token_already_registered", "A kódot már felasználták", 422);
        }
        $objHouse = $objHouseUser->getHouse();

        if ($objHouse === NULL) {
            return $this->container->get('response_handler')->errorHandler("house_doesnt_exists", "A kódhoz nem tartozik ház", 422);
        }

        $returnParams = [
            'success' => true,
            'data' => [
                "id" => $objHouseUser->getId(),
                "name" => $objHouseUser->getLastName() . " " . $objHouseUser->getFirstName(),
                "houseAddress" => $objHouse->getAddress(),
                'token' => $strToken,
            ]
        ];

        return $this->container->get('response_handler')->successHandler($returnParams, $request->query->all());
    }


    public function getConfirmRegistrationAction($hash, Request $request)
    {
        $objRegistration = $this->getDoctrine()->getRepository(Registration::class)->findRegistrationByHash($hash);
        if ($objRegistration) {
            $objUser = $objRegistration->getUser();
            if ($objUser) {
                $entityManager = $this->getDoctrine()->getManager();
                $userManager = $this->get('fos_user.user_manager');

                $objUser->setEnabled(TRUE);
                $userManager->updateUser($objUser);

                $objRegistration->setStatus(0);
                $objRegistration->setUpdatedAt(new \DateTime('now'));

                $entityManager->persist($objRegistration);
                $entityManager->flush();

                return new Response('Sikeres aktiválás!', 200);
            }
        }

        return new Response('A link nem található, vagy lejárt!', 404);
    }

    private function sendConfirmationEmailToUser($objUser, $request)
    {
        $hash = $this->saveRegistration($objUser);

        $message = (new \Swift_Message('Házfal regisztráció megerősítés'))
            ->setFrom(
                array(
                    'info@hazfal.hu' => 'Hazfal Info'
                )
            )
            ->setTo($objUser->getEmail())
            ->setBody(
                $this->renderView(
                    'Email/registration.verify.html.twig',
                    array(
                        'link' => $request->getSchemeAndHttpHost() . $this->generateUrl('api_get_confirm_registration', ['hash' => $hash])
                    )
                ),
                'text/html'
            )->addPart(
                $this->renderView(
                    'Email/registration.verify.txt.twig',
                    array(
                        'link' => $request->getSchemeAndHttpHost() . $this->generateUrl('api_get_confirm_registration', ['hash' => $hash])
                    )
                ),
                'text/plain'
            );

        try {
            $this->container->get('mailer')->send($message);
        } catch (\Throwable $t) {
            echo $t->getCode() . "\n";
            echo $t->getMessage() . "\n";
        }
    }

    private function saveRegistration($objUser)
    {
        $hash = substr(base64_encode(sha1(mt_rand())), 0, 64);
        $entityManager = $this->getDoctrine()->getManager();
        $objRegistration = new Registration();
        $objRegistration->setHash($hash);
        $objRegistration->setStatus(1);
        $objRegistration->setCreatedAt(new \DateTime('now'));
        $objRegistration->setUpdatedAt(new \DateTime('now'));
        $objRegistration->setUser($objUser);
        $entityManager->persist($objRegistration);
        $entityManager->flush();

        return $hash;
    }
}
