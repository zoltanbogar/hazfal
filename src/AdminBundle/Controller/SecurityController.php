<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    public function getLoginAction()
    {
        return $this->render(
            'AdminBundle:Security:login.html.twig',
            [
                'csrf_token' => 'foo',
                'error' => NULL,
                'last_username' => NULL,
            ]
        );
    }

    public function postLoginAction(Request $request)
    {
        /** @var $session Session */
        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } else if (NULL !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = NULL;
        }

        if (!$error instanceof AuthenticationException) {
            $error = NULL; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (NULL === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->tokenManager
            ? $this->tokenManager->getToken('authenticate')->getValue()
            : NULL;

        return $this->renderLogin(
            [
                'last_username' => $lastUsername,
                'error' => $error,
                'csrf_token' => $csrfToken,
            ]
        );
    }

    public function loginAdminAction()
    {

        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        //var_dump($lastUsername);
        //die("asdasdsa");

        return $this->render(
            'security/login.html.twig',
            [
                // last username entered by the user
                'last_username' => $lastUsername,
                'error' => $error,
                //'csrf_token' => $error,
            ]
        );
    }

    public function logoutAdminAction(Request $request)
    {
        $this->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();

        return $this->redirectToRoute('admin_post_loginn');
    }

    public function loginAdminasdasdsaAction()
    {
        var_dump("foo");
        die("asdasdsa");
    }

    public function loginassdasdasAction(Request $request)
    {
        var_dump($request);
        die("asdasdsa");
    }
}