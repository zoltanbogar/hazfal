<?php

namespace AppBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function findUserByUsername($strUserName)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :username')
            ->setParameter('username', $strUserName)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findUserByEmail($strEmail)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $strEmail)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findUserByApiKey($strApiKey)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.apiKey = :apiKey')
            ->setParameter('apiKey', $strApiKey)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findUsersByPermissions($permissionSlugArray)
    {
        $query_string = 'SELECT u FROM AppBundle:User u JOIN u.permissions p WHERE p.slug IN(:slug)';
        $query = $this->getEntityManager()->createQuery($query_string);
        $query->setParameter(
            'slug', $permissionSlugArray
        );

        return $query->getResult();
    }
}
