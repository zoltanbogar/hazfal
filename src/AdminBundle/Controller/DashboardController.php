<?php


namespace AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
        return $this->render(
            'AdminBundle:Dashboard:index.html.twig',
            []
        );
    }

    public function navigateAction($pageName)
    {
        return $this->render(
            sprintf(
                "%s.html.twig",
                $pageName
            )
        );
    }
}