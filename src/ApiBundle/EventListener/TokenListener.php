<?php

namespace ApiBundle\EventListener;

use CashbackCloud\ApiBundle\Controller\ApiController;
//use CashbackCloud\ApiBundle\ErrorHandler\BasicErrorMessage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TokenListener
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        /**@var Controller $controller */
        $controller = $event->getController();
        if (!is_array($controller)) {
            return;
        }
        $host = $event->getRequest()->getHost();
        $api = strpos($host, "api");
        if ($api !== FALSE) {
            if ($event->getRequest()->headers->get('accept') == "application/json") {
                if ($event->getRequest()->get('req_auth')) {
                    $token = $event->getRequest()->headers->get('access-token');
                    if (!$token) {
                        throw new \Exception("blabla");//BasicErrorMessage::MISSING_ACCESS_KEY['info'],BasicErrorMessage::MISSING_ACCESS_KEY['code']);


                        //throw new AccessDeniedException(BasicErrorMessage::MISSING_ACCESS_KEY['info'],null,BasicErrorMessage::MISSING_ACCESS_KEY['code']);
                    }
                    //new Response(dump($token));die();
                    //$user = $this->em->getRepository('CashbackCloudApiBundle:ApiAccessToken')
                    //    ->getUserByToken($token);

                    //if (!$user) {
                    //    throw new \Exception(
                    //        "blabla"
                    //);//throw new AccessDeniedHttpException(BasicErrorMessage::INVALID_ACCESS_KEY['info'],null,BasicErrorMessage::INVALID_ACCESS_KEY['code']);
                    //}
                    //new Response(dump($controller,$controller instanceof  ApiController));die();
                    //if ($controller[0] instanceof ApiController)
                    //    $controller[0]->setUser($user);
                }
            } else {
                if ($event->getRequest()->get("code")) {

                } else {
                    throw new BadRequestHttpException("bad request");
                }
            }
        }
    }

    public function onKernelResponse()
    {

    }
}