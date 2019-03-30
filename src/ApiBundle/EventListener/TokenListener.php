<?php

namespace ApiBundle\EventListener;

use AppBundle\Entity\User;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use ApiBundle\Controller\SecurityController;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class TokenListener
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        /*if (!is_array($controller)) {
            return;
        }*/
        if ($controller[0] instanceof SecurityController) {
            return;
        }
        $token = $event->getRequest()->headers->get('X-AUTH-TOKEN');


        if (!$token) {
            throw new AccessDeniedHttpException('No token provided!');
        }

        $user = $this->entityManager->getRepository(User::class)->findUserByApiKey($token);

        if (!$user) {
            throw new AccessDeniedHttpException('Access Denied!');
        }

        return;
    }

    public function onKernelResponse()
    {

    }
}