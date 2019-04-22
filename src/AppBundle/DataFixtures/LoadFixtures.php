<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\House;
use AppBundle\Entity\HouseUser;
use AppBundle\Entity\ImportSource;
use AppBundle\Entity\Manager;
use AppBundle\Entity\Payment;
use AppBundle\Entity\PaymentMethod;
use AppBundle\Entity\Permission;
use AppBundle\Entity\Tenant;
use AppBundle\Entity\Unit;
use AppBundle\Entity\UnitTenant;
use AppBundle\Entity\User;
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
            $objHouse = new House();
            $objHouse->setName('House ' . $i);
            $objHouse->setCountryCode('HU');
            $objHouse->setRegion($rowRegion[array_rand($rowRegion, 1)]);
            $objHouse->setPostalCode($i);
            $objHouse->setCity('Varos_' . $i);
            $objHouse->setStreet('Utca_' . $i);
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
            $objTenant->setLocalContactNumber('LocalContantNumber_' . $i);
            $objTenant->setUser(NULL);
            $objTenant->setEmail('Email_' . $i . '@example.com');
            $objTenant->setMailingAddress('MailingAddress_' . $i);
            $objTenant->setPhoneNumber('+3630' . str_pad($i, 7, '0', STR_PAD_LEFT));
            $objTenant->setFirstName('First_' . $i);
            $objTenant->setLastName('Last_' . $i);
            $objTenant->setCompanyName('Epam Systems Kft.');
            $objTenant->setCompanyAddress('6723 Szeged, Felso-Tisza part 25.');
            $objTenant->setCompanyTaxNumber('asdasdasdas');
            $objTenant->setRegistrationToken(str_pad(($i + 20), 8, '0', STR_PAD_LEFT));
            $objTenant->setInviteSentAt(new \DateTime('2019-02-' . str_pad($i, 2, '0', STR_PAD_LEFT)));
            $objTenant->setDeletedAt(NULL);
            $objTenant->setCreatedAt(new \DateTime('2019-02-' . str_pad($i, 2, '0', STR_PAD_LEFT)));
            $objTenant->setUpdatedAt(new \DateTime('2019-02-' . str_pad($i, 2, '0', STR_PAD_LEFT)));
            $objTenant->setHouse($objHouse);
            $manager->persist($objTenant);

            $objManager = new Manager();
            $objManager->setWebsite('www.Website' . $i . ".com");
            $objManager->setLogoImage('Image_' . $i);
            $objManager->setUser(NULL);
            $objManager->setEmail('Email_' . $i . '@example.com');
            $objManager->setMailingAddress('MailingAddress_' . $i);
            $objManager->setPhoneNumber('+3630' . str_pad($i, 7, '0', STR_PAD_LEFT));
            $objManager->setFirstName('First_' . $i);
            $objManager->setLastName('Last_' . $i);
            $objManager->setCompanyName('Epam Systems Kft.');
            $objManager->setCompanyAddress('6723 Szeged, Felso-Tisza part 25.');
            $objManager->setCompanyTaxNumber('asdasdasdas');
            $objManager->setRegistrationToken(str_pad($i, 8, '1', STR_PAD_LEFT));
            $objManager->setInviteSentAt(new \DateTime('2019-02-' . str_pad($i, 2, '0', STR_PAD_LEFT)));
            $objManager->setDeletedAt(NULL);
            $objManager->setCreatedAt(new \DateTime('2019-02-' . str_pad($i, 2, '0', STR_PAD_LEFT)));
            $objManager->setUpdatedAt(new \DateTime('2019-02-' . str_pad($i, 2, '0', STR_PAD_LEFT)));
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
            $objUnit->setBuilding('Building_' . $i);
            $objUnit->setFloor($i);
            $objUnit->setDoor($i);
            $objUnit->setFloorArea($i * 1337);
            $objUnit->setUnitType(1);
            $objUnit->setBalance(rand(-1, 1) * $i * 1337);
            $objUnit->setHouseShare("0.5");
            $objUnit->setCreatedAt(new \DateTime('now'));
            $objUnit->setUpdatedAt(new \DateTime('now'));
            $manager->persist($objUnit);
        }

        $objImportSource = new ImportSource();
        $objImportSource->setIsActive(1);
        $objImportSource->setName('eControl');
        $objImportSource->setEmail('info@zipnet.hu');
        $objImportSource->setUsername('econtrol');
        $objImportSource->setSlug('econtrol');
        $objImportSource->setApiKey('MWI2NWFmZjYyZDUyZWIxODY5NGExOTRhMWU3MDQ3YmQzY2ZkMmE4Mw==');
        $manager->persist($objImportSource);

        $objImportSource = new ImportSource();
        $objImportSource->setIsActive(1);
        $objImportSource->setName('Multiház');
        $objImportSource->setEmail('profi@scha.hu');
        $objImportSource->setUsername('multihaz');
        $objImportSource->setSlug('multihaz');
        $objImportSource->setApiKey('NDg0MTcwZTBjNzI4NzY3NGYzZTQ4ZDhmMmMxN2E4MzhhZGQyZTlhZg==');
        $manager->persist($objImportSource);

        $objImportSource = new ImportSource();
        $objImportSource->setIsActive(1);
        $objImportSource->setName('KöltségSQL');
        $objImportSource->setEmail('info@koltsegsql.hu');
        $objImportSource->setUsername('koltsegsql');
        $objImportSource->setSlug('koltsegsql');
        $objImportSource->setApiKey('MDU5ZjY4NDExMjVjZDA1MzYzN2RmYjk2Yzg5MzJmNDczYjllNDgyYw==');
        $manager->persist($objImportSource);

        $objPaymentMethod = new PaymentMethod();
        $objPaymentMethod->setName('Csoportos beszedés');
        $objPaymentMethod->setSlug('csopbesz');
        $objPaymentMethod->setDescription('Csoportos beszedés');
        $manager->persist($objPaymentMethod);

        $objPaymentMethod2 = new PaymentMethod();
        $objPaymentMethod2->setName('Átutalás');
        $objPaymentMethod2->setSlug('atutalas');
        $objPaymentMethod2->setDescription('Átutalás');
        $manager->persist($objPaymentMethod2);

        $objPaymentMethod3 = new PaymentMethod();
        $objPaymentMethod3->setName('Csekk');
        $objPaymentMethod3->setSlug('csekk');
        $objPaymentMethod3->setDescription('Csekk');
        $manager->persist($objPaymentMethod3);

        $objUser = new User();
        $objUser->setRegistrationDate(new \DateTime('now'));
        $objUser->setFirstName('Christoph');
        $objUser->setLastName('Waltz');
        $objUser->setDateOfBirth(\DateTime::createFromFormat('Y-m-d', '1956-10-04'));
        $objUser->setPlaceOfBirth('Bécs, Ausztria');
        $objUser->setBio(
            'Kétszeres Oscar-díjas, kétszeres Golden Globe-díjas és kétszeres BAFTA-díjas osztrák színész. Egyike azon kevés színészeknek, akik az összes BAFTA-, Oscar- és Golden Globe-jelölésből elnyerték a díjat.'
        );
        $objUser->setSex(1);
        $objUser->setLocalPhoneNumber('0666123456');
        $objUser->setOfficialAddress('5600 Békéscsaba, Attila utca 21.');
        $objUser->setCurrentLocation('5600 Békéscsaba, Attila utca 21.');
        $objUser->setJoinToken('123456');
        $objUser->setApiKey('12345654321x');
        $objUser->setUsername('chris_waltz');
        $objUser->setUsernameCanonical('chris_waltz');
        $objUser->setEmail('chris_waltz@gmail.com');
        $objUser->setEmailCanonical('chris_waltz@gmail.com');
        $objUser->setEnabled(1);
        $objUser->setPlainPassword('admin');
        $objUser->setRoles(['ROLE_ADMIN']);
        $manager->persist($objUser);

        $manager->flush();

        $objUser2 = new User();
        $objUser2->setRegistrationDate(new \DateTime('now'));
        $objUser2->setFirstName('Test');
        $objUser2->setLastName('User');
        $objUser2->setDateOfBirth(\DateTime::createFromFormat('Y-m-d', '1966-11-14'));
        $objUser2->setPlaceOfBirth('Budapest, Magyarország');
        $objUser2->setBio(
            'A Lorem Ipsum egy egyszerû szövegrészlete, szövegutánzata a betûszedõ és nyomdaiparnak.'
        );
        $objUser2->setSex(1);
        $objUser2->setLocalPhoneNumber('0666123456');
        $objUser2->setOfficialAddress('1120 Budapest, Fő utca 1.');
        $objUser2->setCurrentLocation('1120 Budapest, Fő utca 1.');
        $objUser2->setJoinToken('654321');
        $objUser2->setApiKey('12345654321x');
        $objUser2->setUsername('test_user');
        $objUser2->setUsernameCanonical('test_user');
        $objUser2->setEmail('test_user@gmail.com');
        $objUser2->setEmailCanonical('test_user@gmail.com');
        $objUser2->setEnabled(1);
        $objUser2->setPlainPassword('user');
        $objUser2->setRoles(['ROLE_USER']);
        $manager->persist($objUser2);

        $manager->flush();

        $permission = new Permission();
        $permission->setName('SuperAdmin');
        $permission->setSlug('super_admin');
        $permission->setCreatedAt(new \DateTime('now'));

        $manager->persist($permission);
        $manager->flush();

        $objUser->addPermission($permission);
        $manager->persist($objUser);
        $manager->flush();

        $permission = new Permission();
        $permission->setName('Sales');
        $permission->setSlug('sales');
        $permission->setCreatedAt(new \DateTime('now'));

        $manager->persist($permission);
        $manager->flush();

        $permission = new Permission();
        $permission->setName('Finance');
        $permission->setSlug('finance');
        $permission->setCreatedAt(new \DateTime('now'));

        $manager->persist($permission);
        $manager->flush();

        $permission = new Permission();
        $permission->setName('User');
        $permission->setSlug('user');
        $permission->setCreatedAt(new \DateTime('now'));

        $manager->persist($permission);
        $manager->flush();

        $objUser2->addPermission($permission);
        $manager->persist($objUser2);
        $manager->flush();

        $objPayment = new Payment();
        $objUnit = $manager->getRepository(Unit::class)->find(15);
        $objPayment->setUnit($objUnit);
        $objPaymentMethod = $manager->getRepository(PaymentMethod::class)->find(1);
        $objPayment->setPaymentMethod($objPaymentMethod);
        $objUser = $manager->getRepository(User::class)->find(1);
        $objPayment->setUser($objUser);
        $objHouseUser = $manager->getRepository(HouseUser::class)->find(11);
        $objPayment->setHouseUser($objHouseUser);
        $objPayment->setPaymentDate(\DateTime::createFromFormat('Y-m-d', '2019-02-04'));
        $objPayment->setBookingDate(\DateTime::createFromFormat('Y-m-d', '2019-03-14'));
        $objPayment->setReceiptNumber('133712378');
        $objPayment->setAmount(39999);
        $objPayment->setStatus(1);
        $manager->persist($objPayment);

        $objPayment = new Payment();
        $objUnit = $manager->getRepository(Unit::class)->find(16);
        $objPayment->setUnit($objUnit);
        $objPaymentMethod = $manager->getRepository(PaymentMethod::class)->find(2);
        $objPayment->setPaymentMethod($objPaymentMethod);
        $objUser = $manager->getRepository(User::class)->find(1);
        $objPayment->setUser($objUser);
        $objHouseUser = $manager->getRepository(HouseUser::class)->find(11);
        $objPayment->setHouseUser($objHouseUser);
        $objPayment->setPaymentDate(\DateTime::createFromFormat('Y-m-d', '2019-03-04'));
        $objPayment->setBookingDate(\DateTime::createFromFormat('Y-m-d', '2019-04-14'));
        $objPayment->setReceiptNumber('133712379');
        $objPayment->setAmount(34999);
        $objPayment->setStatus(1);
        $manager->persist($objPayment);

        $objPayment = new Payment();
        $objUnit = $manager->getRepository(Unit::class)->find(15);
        $objPayment->setUnit($objUnit);
        $objPaymentMethod = $manager->getRepository(PaymentMethod::class)->find(3);
        $objPayment->setPaymentMethod($objPaymentMethod);
        $objUser = $manager->getRepository(User::class)->find(1);
        $objPayment->setUser($objUser);
        $objHouseUser = $manager->getRepository(HouseUser::class)->find(11);
        $objPayment->setHouseUser($objHouseUser);
        $objPayment->setPaymentDate(\DateTime::createFromFormat('Y-m-d', '2019-04-07'));
        $objPayment->setBookingDate(\DateTime::createFromFormat('Y-m-d', '2019-05-24'));
        $objPayment->setReceiptNumber('133712380');
        $objPayment->setAmount(45000);
        $objPayment->setStatus(1);
        $manager->persist($objPayment);

        $manager->flush();
    }
}