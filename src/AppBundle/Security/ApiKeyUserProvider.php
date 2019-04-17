<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use AppBundle\Security\ApiKeyAuthenticator;

use AppBundle\Entity\User as UserEntity;

class ApiKeyUserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        return new User(
            $username,
            NULL,
            // the roles for the user - you may choose to determine
            // these dynamically somehow based on the user
            ['ROLE_USER']
        );
    }

    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }
}