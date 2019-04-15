<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SalesController extends Controller
{
    public function getSalesUsersAction(Request $request)
    {
        if ($request->query->get('error')) {
            $error = $request->query->get('error');
        }

        $permissionSlug = 'sales';

        $objSalesUsers = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findUsersByPermission($permissionSlug);

        return $this->render(
            'Admin\SalesUsers\salesUsers.html.twig',
            [
                'objSalesUsers' => $objSalesUsers,
                'error' => $error ?? NULL,
                'success' => $request->get('success')
            ]
        );
    }
}
