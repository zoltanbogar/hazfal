<?php

namespace ApiBundle\EventListener;

use AppBundle\Entity\User;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use ApiBundle\Controller\SecurityController;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

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
        if ($controller[0] instanceof SecurityController) {
            return;
        } else if ($controller[0] instanceof ExceptionController) {
            return;
            throw new BadRequestHttpException('Bad request http exception');
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