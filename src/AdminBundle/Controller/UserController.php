<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function getUsersAction(Request $request)
    {
        if ($request->query->get('error')) {
            $error = $request->query->get('error');
        }

        $users = [];

        $objUsers = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();

        foreach ($objUsers as $user) {
            foreach ($user->getPermissions() as $permission) {
                if ($permission->getSlug() != 'sales') {
                    $users[] = $user;
                }
            }
        }

        return $this->render(
            'Admin\User\users.html.twig',
            [
                'objUsers' => $users,
                'error' => $error ?? NULL,
                'success' => $request->get('success')
            ]
        );
    }

    public function getUserReadAction($userId, $objectType, Request $request)
    {
        $objUser = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userId);

        $objPermissions = $this->getDoctrine()
            ->getRepository('AppBundle:Permission')
            ->findAll();

        return $this->render(
            'Admin\User\form.html.twig',
            [
                'objUser' => $objUser,
                'objectType' => $objectType,
                'objPermissions' => $objPermissions,
                'isEditable' => FALSE,
                'isNew' => FALSE,
                'error' => NULL,
                'success' => $request->get('success')
            ]
        );
    }

    public function getUserAddAction($objectType, Request $request)
    {
        $objUser = new User();

        $objPermissions = $this->getDoctrine()
            ->getRepository('AppBundle:Permission')
            ->findAll();

        return $this->render(
            'Admin\User\form.html.twig',
            [
                'objUser' => $objUser,
                'objectType' => $objectType,
                'objPermissions' => $objPermissions,
                'isEditable' => TRUE,
                'isNew' => TRUE,
                'error' => NULL,
                'success' => NULL,
            ]
        );
    }

    public function getUserEditAction($userId, $objectType)
    {
        $objUser = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userId);

        $objPermissions = $this->getDoctrine()
            ->getRepository('AppBundle:Permission')
            ->findAll();

        return $this->render(
            'Admin\User\form.html.twig',
            [
                'objUser' => $objUser,
                'objectType' => $objectType,
                'objPermissions' => $objPermissions,
                'isEditable' => TRUE,
                'isNew' => FALSE,
                'error' => NULL,
                'success' => NULL,
            ]
        );
    }

    public function postUserAddAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'inputEmail' => 'required',
                'inputPassword' => 'required',
                'inputPasswordAgain' => 'required',
                'inputPermissionId' => 'required',
                'inputPhoneNumber' => 'required',
                'inputUserName' => 'required',
                'inputSex' => 'required',
                'inputIsActive' => 'required',
                'inputFirstName' => 'required',
                'inputLastName' => 'required',
                'inputDateOfBirth' => 'required',
                'inputPlaceOfBirth' => 'required',
                'inputOfficialAddress' => 'required',
                'inputCurrentLocation' => 'required',
                'inputBio' => 'required',
            ],
            $request
        );

        $error = NULL;
        $success = TRUE;
        $objUserID = 0;

        if ($validator['hasError']) {
            $error = $validator;
            $success = FALSE;
        }

        if (!$error) {
            $entityManager = $this->getDoctrine()->getManager();

            $objPermission = $entityManager->getRepository('AppBundle:Permission')->find($request->get('inputPermissionId'));
            $strDateOfBirth = new \DateTime($request->get("inputDateOfBirth"));

            $objUser = new User();
            $objUser->setUsername($request->get('inputUserName'));
            $objUser->setUsernameCanonical($request->get('inputUserName'));
            $objUser->setEmail($request->get('inputEmail'));
            $objUser->setEmailCanonical($request->get('inputEmail'));
            $objUser->setEnabled($request->get('inputIsActive'));
            $objUser->setPlainPassword($request->get('inputPassword'));
            $objUser->setRegistrationDate(new \DateTime());
            $objUser->setFirstName($request->get('inputFirstName'));
            $objUser->setLastName($request->get('inputLastName'));
            $objUser->setDateOfBirth($strDateOfBirth);
            $objUser->setPlaceOfBirth($request->get('inputPlaceOfBirth'));
            $objUser->setSex($request->get('inputSex'));
            $objUser->setApiKey(substr(base64_encode(sha1(mt_rand())), 0, 64));
            $objUser->setLocalPhoneNumber($request->get('inputPhoneNumber'));
            $objUser->setOfficialAddress($request->get('inputOfficialAddress'));
            $objUser->setCurrentLocation($request->get('inputCurrentLocation'));
            $objUser->setBio($request->get('inputBio'));
            $objUser->setPermission($objPermission);

            $entityManager->persist($objUser);
            $entityManager->flush();

            $objUserID = $objUser->getId();
        }

        return $this->redirectToRoute(
            'admin_get_user_read',
            [
                'userId' => $objUserID,
                'error' => NULL,
                'success' => $success
            ]
        );
    }

    public function postUserEditAction($userId, Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'inputEmail' => 'required',
                'inputPermissionId' => 'required',
                'inputPhoneNumber' => 'required',
                'inputUserName' => 'required',
                'inputSex' => 'required',
                'inputIsActive' => 'required',
                'inputFirstName' => 'required',
                'inputLastName' => 'required',
                'inputDateOfBirth' => 'required',
                'inputPlaceOfBirth' => 'required',
                'inputOfficialAddress' => 'required',
                'inputCurrentLocation' => 'required',
                'inputBio' => 'required',
            ],
            $request
        );

        $error = NULL;
        $success = TRUE;

        if ($validator['hasError']) {
            $error = $validator;
            $success = FALSE;
        }

        $objUser = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userId);

        $objPermissions = $this->getDoctrine()
            ->getRepository('AppBundle:Permission')
            ->findAll();

        if (!$error) {
            $entityManager = $this->getDoctrine()->getManager();

            $objPermission = $entityManager->getRepository('AppBundle:Permission')->find($request->get('inputPermissionId'));
            $strDateOfBirth = new \DateTime($request->get("inputDateOfBirth"));

            $objUser->setUsername($request->get('inputUserName'));
            $objUser->setUsernameCanonical($request->get('inputUserName'));
            $objUser->setEmail($request->get('inputEmail'));
            $objUser->setEmailCanonical($request->get('inputEmail'));
            $objUser->setEnabled($request->get('inputIsActive'));
            $objUser->setFirstName($request->get('inputFirstName'));
            $objUser->setLastName($request->get('inputLastName'));
            $objUser->setDateOfBirth($strDateOfBirth);
            $objUser->setPlaceOfBirth($request->get('inputPlaceOfBirth'));
            $objUser->setSex($request->get('inputSex'));
            $objUser->setLocalPhoneNumber($request->get('inputPhoneNumber'));
            $objUser->setOfficialAddress($request->get('inputOfficialAddress'));
            $objUser->setCurrentLocation($request->get('inputCurrentLocation'));
            $objUser->setBio($request->get('inputBio'));
            $objUser->setPermission($objPermission);

            $entityManager->persist($objUser);
            $entityManager->flush();
        }

        return $this->render(
            'Admin\User\form.html.twig',
            [
                'objUser' => $objUser,
                'objPermissions' => $objPermissions,
                'isEditable' => TRUE,
                'isNew' => FALSE,
                'error' => $error,
                'success' => $success,
            ]
        );
    }

    public function getUserDeleteAction($userId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $objUser = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($userId);

        $error = NULL;
        $success = TRUE;

        try {
            $objUser->setDeletedAt(new \DateTime('now'));
            $entityManager->persist($objUser);
            $entityManager->flush();
        } catch (\Exception $e) {
            $error = [
                'errorTitle' => 'Nem törölhető',
                'errorText' => $e->getMessage(),
            ];
            $success = FALSE;
        }

        return $this->redirectToRoute(
            'admin_get_users',
            [
                'error' => $error,
                'success' => $success
            ]
        );
    }
}
