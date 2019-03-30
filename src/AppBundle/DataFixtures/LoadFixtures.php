<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\House;
use AppBundle\Entity\HouseUser;
use AppBundle\Entity\Manager;
use AppBundle\Entity\Tenant;
use AppBundle\Entity\Unit;
use AppBundle\Entity\UnitTenant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $rowRegion = [
            'Csongrad',
            'Bekes',
            'Heves',
            'Tolna',
            'Pest',
            'BorsodAZ',
            'GyőrMS',
            'Zala',
            'HajduB',
            'Fejer',
        ];
        for ($i = 1; $i <= 20; $i++) {
            /*$objHouseUser = new HouseUser();
            $objHouseUser->setUser(NULL);
            $objHouseUser->setEmail('Email_'.$i.'@example.com');
            $objHouseUser->setMailingAddress('MailingAddress_'.$i);
            $objHouseUser->setPhoneNumber('+3630'.str_pad($i, 7, '0', STR_PAD_LEFT));
            $objHouseUser->setFirstName('First_'.$i);
            $objHouseUser->setLastName('Last_'.$i);
            $objHouseUser->setCompanyName('Epam Systems Kft.');
            $objHouseUser->setCompanyAddress('6723 Szeged, Felso-Tisza part 25.');
            $objHouseUser->setCompanyTaxNumber('asdasdasdas');
            $objHouseUser->setRegistrationToken(str_pad($i, 8, '0', STR_PAD_LEFT));
            $objHouseUser->setInviteSentAt(new \DateTime('2019-02-'.str_pad($i, 2, '0', STR_PAD_LEFT)));
            $objHouseUser->setDeletedAt(NULL);
            $objHouseUser->setCreatedAt(new \DateTime('2019-02-'.str_pad($i, 2, '0', STR_PAD_LEFT)));
            $objHouseUser->setUpdatedAt(new \DateTime('2019-02-'.str_pad($i, 2, '0', STR_PAD_LEFT)));
            $objHouseUser->setType('tenant');
            $manager->persist($objHouseUser);*/

            $objHouse = new House();
            //$objHouse->addHouseUser($objTenant);
            $objHouse->setName('House '.$i);
            $objHouse->setCountryCode('HU');
            $objHouse->setRegion($rowRegion[array_rand($rowRegion, 1)]);
            $objHouse->setPostalCode($i);
            $objHouse->setCity('Varos_'.$i);
            $objHouse->setStreet('Utca_'.$i);
            $objHouse->setBuilding(rand(1, 200));
            $objHouse->setUnit('Unit');
            $objHouse->setLotNumber(rand(1, 200));
            $objHouse->setGpsLatitude(rand(30, 40));
            $objHouse->setGpsLongitude(rand(30, 40));
            $objHouse->setStatus(1);
            $objHouse->setDeletedAt(NULL);
            $objHouse->setCreatedAt(new \DateTime('now'));
            $objHouse->setUpdatedAt(new \DateTime('now'));
            $manager->persist($objHouse);

            $objTenant = new Tenant();
            $objTenant->setLocalContactNumber('LocalContantNumber_'.$i);
            $objTenant->setUser(NULL);
            $objTenant->setEmail('Email_'.$i.'@example.com');
            $objTenant->setMailingAddress('MailingAddress_'.$i);
            $objTenant->setPhoneNumber('+3630'.str_pad($i, 7, '0', STR_PAD_LEFT));
            $objTenant->setFirstName('First_'.$i);
            $objTenant->setLastName('Last_'.$i);
            $objTenant->setCompanyName('Epam Systems Kft.');
            $objTenant->setCompanyAddress('6723 Szeged, Felso-Tisza part 25.');
            $objTenant->setCompanyTaxNumber('asdasdasdas');
            $objTenant->setRegistrationToken(str_pad($i, 8, '0', STR_PAD_LEFT));
            $objTenant->setInviteSentAt(new \DateTime('2019-02-'.str_pad($i, 2, '0', STR_PAD_LEFT)));
            $objTenant->setDeletedAt(NULL);
            $objTenant->setCreatedAt(new \DateTime('2019-02-'.str_pad($i, 2, '0', STR_PAD_LEFT)));
            $objTenant->setUpdatedAt(new \DateTime('2019-02-'.str_pad($i, 2, '0', STR_PAD_LEFT)));
            $objTenant->setHouse($objHouse);
            $manager->persist($objTenant);

            $objManager = new Manager();
            $objManager->setWebsite('www.Website'.$i.".com");
            $objManager->setLogoImage('Image_'.$i);
            $objManager->setUser(NULL);
            $objManager->setEmail('Email_'.$i.'@example.com');
            $objManager->setMailingAddress('MailingAddress_'.$i);
            $objManager->setPhoneNumber('+3630'.str_pad($i, 7, '0', STR_PAD_LEFT));
            $objManager->setFirstName('First_'.$i);
            $objManager->setLastName('Last_'.$i);
            $objManager->setCompanyName('Epam Systems Kft.');
            $objManager->setCompanyAddress('6723 Szeged, Felso-Tisza part 25.');
            $objManager->setCompanyTaxNumber('asdasdasdas');
            $objManager->setRegistrationToken(str_pad($i, 8, '1', STR_PAD_LEFT));
            $objManager->setInviteSentAt(new \DateTime('2019-02-'.str_pad($i, 2, '0', STR_PAD_LEFT)));
            $objManager->setDeletedAt(NULL);
            $objManager->setCreatedAt(new \DateTime('2019-02-'.str_pad($i, 2, '0', STR_PAD_LEFT)));
            $objManager->setUpdatedAt(new \DateTime('2019-02-'.str_pad($i, 2, '0', STR_PAD_LEFT)));
            $objManager->setHouse($objHouse);
            $manager->persist($objManager);


            $objUnitTenant = new UnitTenant();
            $objUnitTenant->setType(1);
            $objUnitTenant->setOwnershipShare(0.5);
            $objUnitTenant->setStatus(1);
            $objUnitTenant->setCreatedAt(new \DateTime('now'));
            $objUnitTenant->setUpdatedAt(new \DateTime('now'));
            $manager->persist($objUnitTenant);

            $objUnit = new Unit();
            $objUnit->setHouse($objHouse);
            $objUnit->setUnitTenant($objUnitTenant);
            $objUnit->setBuilding('Building_'.$i);
            $objUnit->setFloor($i);
            $objUnit->setDoor($i);
            $objUnit->setFloorArea($i * 1337);
            $objUnit->setType(1);
            $objUnit->setBalance(rand(-1, 1) * $i * 1337);
            $objUnit->setHouseShare("0.5");
            $manager->persist($objUnit);
        }

        $manager->flush();
    }
}