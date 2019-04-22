<?php
namespace AppBundle\Security;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        foreach ($user->getPermissions() as $permission) {
            if ($permission->getSlug() == 'user') {
                throw new AccessDeniedHttpException('Hozzáférés megtagadva!');
            }
        }
    }

    public function checkPostAuth(UserInterface $user)
    {

    }
}