<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $user = $this->getUser();

        return $this->container->get('response_handler')->successHandler($user, []);
    }
}