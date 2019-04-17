<?php

namespace ApiBundle\EventListener;

use AppBundle\Entity\User;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

use ApiBundle\Controller\SecurityController;
use AdminBundle\Controller\SecurityController as AdminSecurityController;
use ApiBundle\Controller\FinanceController;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

use FOS\UserBundle\Controller\SecurityController as FosSecurityController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
        if ($controller[0] instanceof SecurityController || $controller[0] instanceof AdminSecurityController || $controller[0] instanceof FosSecurityController || $controller[0] instanceof FinanceController) {
            return;
        } else if ($controller[0] instanceof ExceptionController || $controller[0] instanceof ProfilerController) {
            return;
        } else {
            if (strpos($event->getRequest()->attributes->get('_route'), 'admin') !== FALSE) return;
        }

        $token = $event->getRequest()->headers->get('X-AUTH-TOKEN');

        if (!$token) {
            throw new AccessDeniedHttpException('No token provided! Teszteléshez használd a "12345654321x" tokent');
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
