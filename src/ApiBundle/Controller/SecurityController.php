<?php

namespace ApiBundle\Controller;

use ApiBundle\Service\ResponseHandler;

use AppBundle\Entity\HouseUser;
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

        return $this->container->get('response_handler')->successHandler($objUser, $request->query->all());
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
                return $this->container->get('response_handler')->errorHandler("more_than_one_house_user_found", "Invalid credentials", 422);
            }
        }

        $objHouseUser = $objHouseUser[0];
        $objUser = $objHouseUser->getUser();

        if ($objUser !== NULL) {
            return $this->container->get('response_handler')->errorHandler("user_with_token_already_registered", "Invalid parameters", 422);
        }

        $objUser = $this->getDoctrine()->getRepository(User::class)->findUserByUsername($request->get("username"));

        if ($objUser) {
            return $this->container->get('response_handler')->errorHandler("username_already_registered", "Invalid parameters", 422);
        }

        $objUser = $this->getDoctrine()->getRepository(User::class)->findUserByEmail($request->get("email"));

        if ($objUser) {
            return $this->container->get('response_handler')->errorHandler("user_email_already_registered", "Invalid parameters", 422);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();

        $strDateOfBirth = new \DateTime($request->get("date_of_birth"));
        $user->setFirstName($request->get("first_name"));
        $user->setLastName($request->get("last_name"));
        $user->setRegistrationDate(new \DateTime('now'));
        $user->setDateOfBirth($strDateOfBirth);
        $user->setPlaceOfBirth($request->get("place_of_birth"));
        $user->setBio($request->get("bio"));
        $user->setSex($request->get("sex"));
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
        $user->setEnabled(TRUE);

        $userManager->updateUser($user);

        $objHouseUser->setUser($user);

        $entityManager->flush();

        $identiconDir = 'images/identicon';
        $identiconFile = $user->getId() . '.png';
        if (!file_exists($identiconDir)) {
            mkdir($identiconDir, 0777, TRUE);
        }

        $tile = new Tile();
        $minHashLenght = 0;

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
}