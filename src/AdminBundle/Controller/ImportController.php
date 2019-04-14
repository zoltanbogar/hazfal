<?php


namespace AdminBundle\Controller;


use AppBundle\Entity\Bill;
use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentType;
use AppBundle\Entity\House;
use AppBundle\Entity\HouseUser;
use AppBundle\Entity\ImportedBill;
use AppBundle\Entity\ImportedDocument;
use AppBundle\Entity\ImportedHouse;
use AppBundle\Entity\ImportedHouseUser;
use AppBundle\Entity\ImportedPayment;
use AppBundle\Entity\ImportedUnit;
use AppBundle\Entity\Manager;
use AppBundle\Entity\Payment;
use AppBundle\Entity\PaymentMethod;
use AppBundle\Entity\Tenant;
use AppBundle\Entity\Unit;
use AppBundle\Entity\UnitTenant;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ImportController extends Controller
{
    public function getHouseUsersAction(Request $request)
    {
        if ($request->query->get('error')) {
            $error = $request->query->get('error');
        }
        $objHouseUsers = $this->getDoctrine()
            ->getRepository('AppBundle:ImportedHouseUser')
            ->findBy(['isAccepted' => 0]);

        return $this->render(
            'Admin\Import\HouseUser\houseUsers.html.twig',
            [
                'objHouseUsers' => $objHouseUsers,
                'error' => $error ?? NULL,
                'success' => $request->get('success'),
            ]
        );
    }

    public function getHouseUserImportAction($importedHouseUserId)
    {
        $error = NULL;
        if (!$importedHouseUserId || !is_numeric($importedHouseUserId)) {
            $error = [
                'errorTitle' => 'Hiba',
                'errorText' => 'Nem megfelelő azonosító',
            ];
        }

        $objImportedHouseUser = $this->getDoctrine()->getRepository(ImportedHouseUser::class)->find($importedHouseUserId);
        if (!$objImportedHouseUser) {
            $error = [
                'errorTitle' => 'Hiba',
                'errorText' => 'Nem megfelelő azonosító',
            ];
        }

        if (!$error) {
            if ($objImportedHouseUser->getHouseUserType() === 'tenant') {
                $objHouseUser = new Tenant();
                $objHouseUser->setLocalContactNumber($objImportedHouseUser->getLocalContactNumber());
            } else if ($objImportedHouseUser->getHouseUserType() === 'manager') {
                $objHouseUser = new Manager();
                $objHouseUser->setWebsite($objImportedHouseUser->getWebsite());
                $objHouseUser->setLogoImage($objImportedHouseUser->getLogoImage());
            } else {
                $error = [
                    'errorTitle' => 'Típus hiba',
                    'errorText' => 'Nem megfelelő típus',
                ];
            }
        }

        if (!$error) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($objImportedHouseUser->getUserId()) {
                $numUserID = $objImportedHouseUser->getUserId();
                $objUser = $this->getDoctrine()->getRepository(User::class)->find($numUserID);
                if (!$objUser) {
                    return $this->redirectToRoute(
                        'admin_get_import_house_users',
                        [
                            'error' => $error = [
                                'errorTitle' => 'Felhasználó hiba',
                                'errorText' => 'Nem megfelelő azonosító',
                            ],
                        ]
                    );
                }
                $objHouseUser->setUser($objUser);
            }
            if ($objImportedHouseUser->getHouseId()) {
                $numHouseID = $objImportedHouseUser->getHouseId();
                $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);
                if (!$objHouse) {
                    return $this->redirectToRoute(
                        'admin_get_import_house_users',
                        [
                            'error' => $error = [
                                'errorTitle' => 'Ház hiba',
                                'errorText' => 'Nem megfelelő azonosító',
                            ],
                        ]
                    );
                }
                $objHouseUser->setHouse($objHouse);
            }
            $objHouseUser->setEmail($objImportedHouseUser->getEmail());
            $objHouseUser->setMailingAddress($objImportedHouseUser->getMailingAddress());
            $objHouseUser->setPhoneNumber($objImportedHouseUser->getPhoneNumber());
            $objHouseUser->setFirstName($objImportedHouseUser->getFirstName());
            $objHouseUser->setLastName($objImportedHouseUser->getLastName());
            $objHouseUser->setCompanyName($objImportedHouseUser->getCompanyName());
            $objHouseUser->setCompanyAddress($objImportedHouseUser->getCompanyAddress());
            $objHouseUser->setCompanyTaxNumber($objImportedHouseUser->getCompanyTaxNumber());
            $objHouseUser->setCreatedAt(new \DateTime('now'));
            $objHouseUser->setUpdatedAt(new \DateTime('now'));

            $entityManager->persist($objHouseUser);

            $objImportedHouseUser->setIsAccepted(1);
            $objImportedHouseUser->setAcceptedAt(new \DateTime('now'));

            $entityManager->persist($objImportedHouseUser);

            $entityManager->flush();
        }

        return $this->redirectToRoute(
            'admin_get_import_house_users',
            [
                'error' => $error,
            ]
        );
    }

    public function getHouseUserDeleteAction($importedHouseUserId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $objImportedHouseUser = $this->getDoctrine()
            ->getRepository(ImportedHouseUser::class)
            ->find($importedHouseUserId);

        $error = NULL;

        try {
            $entityManager->remove($objImportedHouseUser);
            $entityManager->flush();
        } catch (\Exception $e) {
            $error = [
                'errorTitle' => 'Nem törölhető',
                'errorText' => $e->getMessage(),
            ];
        }

        return $this->redirectToRoute(
            'admin_get_import_house_users',
            [
                'error' => $error,
            ]
        );
    }

    public function getUsersAction(Request $request)
    {
        if ($request->query->get('error')) {
            $error = $request->query->get('error');
        }
        $objUsers = $this->getDoctrine()
            ->getRepository('AppBundle:ImportedUser')
            ->findBy(['isAccepted' => 0]);

        return $this->render(
            'Admin\Import\User\users.html.twig',
            [
                'objUsers' => $objUsers,
                'error' => $error ?? NULL,
                'success' => $request->get('success'),
            ]
        );
    }

    public function getHousesAction(Request $request)
    {
        if ($request->query->get('error')) {
            $error = $request->query->get('error');
        }
        $objHouses = $this->getDoctrine()
            ->getRepository('AppBundle:ImportedHouse')
            ->findBy(['isAccepted' => 0]);

        return $this->render(
            'Admin\Import\House\houses.html.twig',
            [
                'objHouses' => $objHouses,
                'error' => $error ?? NULL,
                'success' => $request->get('success'),
            ]
        );
    }

    public function getHouseImportAction($importedHouseId)
    {
        $error = NULL;
        if (!$importedHouseId || !is_numeric($importedHouseId)) {
            $error = [
                'errorTitle' => 'Hiba',
                'errorText' => 'Nem megfelelő azonosító',
            ];
        }

        $objImportedHouse = $this->getDoctrine()->getRepository(ImportedHouse::class)->find($importedHouseId);
        if (!$objImportedHouse) {
            $error = [
                'errorTitle' => 'Hiba',
                'errorText' => 'Nem megfelelő azonosító',
            ];
        }

        if (!$error) {
            $entityManager = $this->getDoctrine()->getManager();

            $objHouse = new House();
            $objHouse->setName($objImportedHouse->getName());
            $objHouse->setCountryCode($objImportedHouse->getCountryCode());
            $objHouse->setRegion($objImportedHouse->getRegion());
            $objHouse->setPostalCode($objImportedHouse->getPostalCode());
            $objHouse->setCity($objImportedHouse->getCity());
            $objHouse->setStreet($objImportedHouse->getStreet());
            $objHouse->setBuilding($objImportedHouse->getBuilding());
            $objHouse->setUnit($objImportedHouse->getUnit());
            $objHouse->setLotNumber($objImportedHouse->getLotNumber());
            $objHouse->setGpsLatitude($objImportedHouse->getGpsLatitude());
            $objHouse->setGpsLongitude($objImportedHouse->getGpsLongitude());
            $objHouse->setStatus(1);
            $objHouse->setCreatedAt(new \DateTime('now'));
            $objHouse->setUpdatedAt(new \DateTime('now'));

            $entityManager->persist($objHouse);

            $objImportedHouse->setIsAccepted(1);
            $objImportedHouse->setAcceptedAt(new \DateTime('now'));

            $entityManager->persist($objImportedHouse);

            $entityManager->flush();
        }

        return $this->redirectToRoute(
            'admin_get_import_houses',
            [
                'error' => $error,
            ]
        );
    }

    public function getHouseDeleteAction($importedHouseId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $objImportedHouse = $this->getDoctrine()
            ->getRepository(ImportedHouse::class)
            ->find($importedHouseId);

        $error = NULL;

        try {
            $entityManager->remove($objImportedHouse);
            $entityManager->flush();
        } catch (\Exception $e) {
            $error = [
                'errorTitle' => 'Nem törölhető',
                'errorText' => $e->getMessage(),
            ];
        }

        return $this->redirectToRoute(
            'admin_get_import_houses',
            [
                'error' => $error,
            ]
        );
    }

    public function getUnitsAction(Request $request)
    {
        if ($request->query->get('error')) {
            $error = $request->query->get('error');
        }
        $objUnits = $this->getDoctrine()
            ->getRepository('AppBundle:ImportedUnit')
            ->findBy(['isAccepted' => 0]);

        return $this->render(
            'Admin\Import\Unit\units.html.twig',
            [
                'objUnits' => $objUnits,
                'error' => $error ?? NULL,
                'success' => $request->get('success'),
            ]
        );
    }

    public function getUnitImportAction($importedUnitId)
    {
        $error = NULL;
        if (!$importedUnitId || !is_numeric($importedUnitId)) {
            $error = [
                'errorTitle' => 'Hiba',
                'errorText' => 'Nem megfelelő azonosító',
            ];
        }

        $objImportedUnit = $this->getDoctrine()->getRepository(ImportedUnit::class)->find($importedUnitId);
        if (!$objImportedUnit) {
            $error = [
                'errorTitle' => 'Hiba',
                'errorText' => 'Nem megfelelő azonosító',
            ];
        }

        if (!$error) {
            $entityManager = $this->getDoctrine()->getManager();

            $objUnit = new Unit();
            if ($objImportedUnit->getHouseId()) {
                $numHouseID = $objImportedUnit->getHouseId();
                $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);
                if (!$objHouse) {
                    return $this->redirectToRoute(
                        'admin_get_import_units',
                        [
                            'error' => $error = [
                                'errorTitle' => 'Ház hiba',
                                'errorText' => 'Nem megfelelő azonosító',
                            ],
                        ]
                    );
                }
                $objUnit->setHouse($objHouse);
            }
            if ($objImportedUnit->getTenantId()) {
                $numTenantID = $objImportedUnit->getTenantId();
                $objTenant = $this->getDoctrine()->getRepository(HouseUser::class)->find($numTenantID);
                if (!$objTenant) {
                    return $this->redirectToRoute(
                        'admin_get_import_units',
                        [
                            'error' => $error = [
                                'errorTitle' => 'Lakó hiba',
                                'errorText' => 'Nem megfelelő azonosító',
                            ],
                        ]
                    );
                }

                try {
                    $objUnitTenant = new UnitTenant();
                    $objUnitTenant->setStatus(1);
                    $objUnitTenant->setCreatedAt(new \DateTime('now'));
                    $objUnitTenant->setUpdatedAt(new \DateTime('now'));
                    $objUnitTenant->setTenant($objTenant);

                    $entityManager->persist($objUnitTenant);
                    $entityManager->flush();

                    $objUnit->setUnitTenant($objUnitTenant);
                } catch (\Exception $e) {
                    return $this->redirectToRoute(
                        'admin_get_import_units',
                        [
                            'error' => $error = [
                                'errorTitle' => 'Lakó hiba',
                                'errorText' => $e->getMessage(),
                            ],
                        ]
                    );
                }
            }
            $objUnit->setBuilding($objImportedUnit->getBuilding());
            $objUnit->setFloor($objImportedUnit->getFloor());
            $objUnit->setDoor($objImportedUnit->getDoor());
            $objUnit->setFloorArea($objImportedUnit->getFloorArea());
            $objUnit->setType($objImportedUnit->getUnitType());
            $objUnit->setBalance($objImportedUnit->getBalance());
            $objUnit->setHouseShare($objImportedUnit->getHouseShare());
            $objUnit->setCreatedAt(new \DateTime('now'));
            $objUnit->setUpdatedAt(new \DateTime('now'));

            $entityManager->persist($objHouse);

            $objImportedUnit->setIsAccepted(1);
            $objImportedUnit->setAcceptedAt(new \DateTime('now'));

            $entityManager->persist($objImportedUnit);

            $entityManager->flush();
        }

        return $this->redirectToRoute(
            'admin_get_import_units',
            [
                'error' => $error,
            ]
        );
    }

    public function getUnitDeleteAction($importedUnitId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $objImportedUnit = $this->getDoctrine()
            ->getRepository(ImportedUnit::class)
            ->find($importedUnitId);

        $error = NULL;

        try {
            $entityManager->remove($objImportedUnit);
            $entityManager->flush();
        } catch (\Exception $e) {
            $error = [
                'errorTitle' => 'Nem törölhető',
                'errorText' => $e->getMessage(),
            ];
        }

        return $this->redirectToRoute(
            'admin_get_import_units',
            [
                'error' => $error,
            ]
        );
    }

    public function getPaymentsAction(Request $request)
    {
        if ($request->query->get('error')) {
            $error = $request->query->get('error');
        }
        $objPayments = $this->getDoctrine()
            ->getRepository('AppBundle:ImportedPayment')
            ->findBy(['isAccepted' => 0]);

        return $this->render(
            'Admin\Import\Payment\payments.html.twig',
            [
                'objPayments' => $objPayments,
                'error' => $error ?? NULL,
                'success' => $request->get('success'),
            ]
        );
    }

    public function getPaymentImportAction($importedPaymentId)
    {
        $error = NULL;
        if (!$importedPaymentId || !is_numeric($importedPaymentId)) {
            $error = [
                'errorTitle' => 'Hiba',
                'errorText' => 'Nem megfelelő azonosító',
            ];
        }

        $objImportedPayment = $this->getDoctrine()->getRepository(ImportedPayment::class)->find($importedPaymentId);
        if (!$objImportedPayment) {
            $error = [
                'errorTitle' => 'Hiba',
                'errorText' => 'Nem megfelelő azonosító',
            ];
        }

        if (!$error) {
            $entityManager = $this->getDoctrine()->getManager();

            $objPayment = new Payment();
            if ($objImportedPayment->getUnitId()) {
                $numUnitID = $objImportedPayment->getUnitId();
                $objUnit = $this->getDoctrine()->getRepository(Unit::class)->find($numUnitID);
                if (!$objUnit) {
                    return $this->redirectToRoute(
                        'admin_get_import_payments',
                        [
                            'error' => $error = [
                                'errorTitle' => 'Albetét hiba',
                                'errorText' => 'Nem megfelelő azonosító',
                            ],
                        ]
                    );
                }
                $objPayment->setUnit($objUnit);
            }
            if ($objImportedPayment->getPaymentMethodId()) {
                $numPaymentMethodID = $objImportedPayment->getPaymentMethodId();
                $objPaymentMethod = $this->getDoctrine()->getRepository(PaymentMethod::class)->find($numPaymentMethodID);
                if (!$objPaymentMethod) {
                    return $this->redirectToRoute(
                        'admin_get_import_payments',
                        [
                            'error' => $error = [
                                'errorTitle' => 'Fizetési mód hiba',
                                'errorText' => 'Nem megfelelő azonosító',
                            ],
                        ]
                    );
                }
                $objPayment->setPaymentMethod($objPaymentMethod);
            } else {
                $objPaymentMethod = $this->getDoctrine()->getRepository(PaymentMethod::class)->find(1);
                $objPayment->setPaymentMethod($objPaymentMethod);
            }
            if ($objImportedPayment->getUserId()) {
                $numUserID = $objImportedPayment->getUserId();
                $objUser = $this->getDoctrine()->getRepository(User::class)->find($numUserID);
                if (!$objUser) {
                    return $this->redirectToRoute(
                        'admin_get_import_payments',
                        [
                            'error' => $error = [
                                'errorTitle' => 'Felhasználó hiba',
                                'errorText' => 'Nem megfelelő azonosító',
                            ],
                        ]
                    );
                }
                $objPayment->setUser($objUser);
            }
            if ($objImportedPayment->getHouseUserId()) {
                $numHouseUserID = $objImportedPayment->getHouseUserId();
                $objHouseUser = $this->getDoctrine()->getRepository(HouseUser::class)->find($numHouseUserID);
                if (!$objHouseUser) {
                    return $this->redirectToRoute(
                        'admin_get_import_payments',
                        [
                            'error' => $error = [
                                'errorTitle' => 'Technikai felhasználó hiba',
                                'errorText' => 'Nem megfelelő azonosító',
                            ],
                        ]
                    );
                }
                $objPayment->setHouseUser($objHouseUser);
            }
            $objPayment->setPaymentDate($objImportedPayment->getPaymentDate());
            $objPayment->setBookingDate($objImportedPayment->getBookingDate());
            $objPayment->setReceiptNumber($objImportedPayment->getReceiptNumber());
            $objPayment->setAmount($objImportedPayment->getAmount());
            $objPayment->setStatus(1);

            $entityManager->persist($objPayment);

            $objImportedPayment->setIsAccepted(1);
            $objImportedPayment->setAcceptedAt(new \DateTime('now'));

            $entityManager->persist($objImportedPayment);

            $entityManager->flush();
        }

        return $this->redirectToRoute(
            'admin_get_import_payments',
            [
                'error' => $error,
            ]
        );
    }

    public function getPaymentDeleteAction($importedPaymentId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $objImportedPayment = $this->getDoctrine()
            ->getRepository(ImportedPayment::class)
            ->find($importedPaymentId);

        $error = NULL;

        try {
            $entityManager->remove($objImportedPayment);
            $entityManager->flush();
        } catch (\Exception $e) {
            $error = [
                'errorTitle' => 'Nem törölhető',
                'errorText' => $e->getMessage(),
            ];
        }

        return $this->redirectToRoute(
            'admin_get_import_payments',
            [
                'error' => $error,
            ]
        );
    }

    public function getBillsAction(Request $request)
    {
        if ($request->query->get('error')) {
            $error = $request->query->get('error');
        }
        $objBills = $this->getDoctrine()
            ->getRepository('AppBundle:ImportedBill')
            ->findBy(['isAccepted' => 0]);

        return $this->render(
            'Admin\Import\Bill\bills.html.twig',
            [
                'objBills' => $objBills,
                'error' => $error ?? NULL,
                'success' => $request->get('success'),
            ]
        );
    }

    public function getBillImportAction($importedBillId)
    {
        $error = NULL;
        if (!$importedBillId || !is_numeric($importedBillId)) {
            $error = [
                'errorTitle' => 'Hiba',
                'errorText' => 'Nem megfelelő azonosító',
            ];
        }

        $objImportedBill = $this->getDoctrine()->getRepository(ImportedBill::class)->find($importedBillId);
        if (!$objImportedBill) {
            $error = [
                'errorTitle' => 'Hiba',
                'errorText' => 'Nem megfelelő azonosító',
            ];
        }

        if (!$error) {
            $entityManager = $this->getDoctrine()->getManager();

            $objBill = new Bill();
            if ($objImportedBill->getUnitId()) {
                $numUnitID = $objImportedBill->getUnitId();
                $objUnit = $this->getDoctrine()->getRepository(Unit::class)->find($numUnitID);
                if (!$objUnit) {
                    return $this->redirectToRoute(
                        'admin_get_import_payments',
                        [
                            'error' => $error = [
                                'errorTitle' => 'Albetét hiba',
                                'errorText' => 'Nem megfelelő azonosító',
                            ],
                        ]
                    );
                }
                $objBill->setUnit($objUnit);
            }
            $objBill->setBillCategory($objImportedBill->getBillCategory());
            $objBill->setAmount($objImportedBill->getAmount());
            $objBill->setStatus(1);
            $objBill->setDetails($objImportedBill->getDetails());
            $objBill->setCreatedAt(new \DateTime('now'));
            $objBill->setIssuedAt($objImportedBill->getIssuedAt());
            $objBill->setDueDate($objImportedBill->getDueDate());
            $objBill->setReceiptNumber($objImportedBill->getReceiptNumber());
            $objBill->setIssuedForMonth($objImportedBill->getIssuedForMonth());

            $entityManager->persist($objBill);

            $objImportedBill->setIsAccepted(1);
            $objImportedBill->setAcceptedAt(new \DateTime('now'));

            $entityManager->persist($objImportedBill);

            $entityManager->flush();
        }

        return $this->redirectToRoute(
            'admin_get_import_bills',
            [
                'error' => $error,
            ]
        );
    }

    public function getBillDeleteAction($importedBillId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $objImportedBill = $this->getDoctrine()
            ->getRepository(ImportedBill::class)
            ->find($importedBillId);

        $error = NULL;

        try {
            $entityManager->remove($objImportedBill);
            $entityManager->flush();
        } catch (\Exception $e) {
            $error = [
                'errorTitle' => 'Nem törölhető',
                'errorText' => $e->getMessage(),
            ];
        }

        return $this->redirectToRoute(
            'admin_get_import_bills',
            [
                'error' => $error,
            ]
        );
    }

    public function getDocumentsAction(Request $request)
    {
        if ($request->query->get('error')) {
            $error = $request->query->get('error');
        }
        $objDocuments = $this->getDoctrine()
            ->getRepository('AppBundle:ImportedDocument')
            ->findBy(['isAccepted' => 0]);

        return $this->render(
            'Admin\Import\Document\documents.html.twig',
            [
                'objDocuments' => $objDocuments,
                'error' => $error ?? NULL,
                'success' => $request->get('success'),
            ]
        );
    }

    public function getDocumentImportAction($importedDocumentId)
    {
        $error = NULL;
        if (!$importedDocumentId || !is_numeric($importedDocumentId)) {
            $error = [
                'errorTitle' => 'Hiba',
                'errorText' => 'Nem megfelelő azonosító',
            ];
        }

        $objImportedDocument = $this->getDoctrine()->getRepository(ImportedDocument::class)->find($importedDocumentId);
        if (!$objImportedDocument) {
            $error = [
                'errorTitle' => 'Hiba',
                'errorText' => 'Nem megfelelő azonosító',
            ];
        }

        if (!$error) {
            $entityManager = $this->getDoctrine()->getManager();

            $objDocument = new Document();
            if ($objImportedDocument->getUserId()) {
                $numUserID = $objImportedDocument->getUserId();
                $objUser = $this->getDoctrine()->getRepository(User::class)->find($numUserID);
                if (!$objUser) {
                    return $this->redirectToRoute(
                        'admin_get_import_documents',
                        [
                            'error' => $error = [
                                'errorTitle' => 'Felhasználó hiba',
                                'errorText' => 'Nem megfelelő azonosító',
                            ],
                        ]
                    );
                }
                $objDocument->setUser($objUser);
            }
            if ($objImportedDocument->getDocumentTypeId()) {
                $numDocumentTypeID = $objImportedDocument->getDocumentTypeId();
                $objDocumentType = $this->getDoctrine()->getRepository(DocumentType::class)->find($numDocumentTypeID);
                if (!$objDocumentType) {
                    return $this->redirectToRoute(
                        'admin_get_import_documents',
                        [
                            'error' => $error = [
                                'errorTitle' => 'Dokumentum típus hiba',
                                'errorText' => 'Nem megfelelő azonosító',
                            ],
                        ]
                    );
                }
                $objDocument->setDocumentType($objDocumentType);
            } else {
                $objDocumentType = $this->getDoctrine()->getRepository(DocumentType::class)->find(1);
                $objDocument->setDocumentType($objDocumentType);
            }
            if ($objImportedDocument->getHouseId()) {
                $numHouseID = $objImportedDocument->getHouseId();
                $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);
                if (!$objHouse) {
                    return $this->redirectToRoute(
                        'admin_get_import_documents',
                        [
                            'error' => $error = [
                                'errorTitle' => 'Ház hiba',
                                'errorText' => 'Nem megfelelő azonosító',
                            ],
                        ]
                    );
                }
                $objDocument->setHouse($objHouse);
            }
            $objDocument->setName($objImportedDocument->getName());
            $objDocument->setFilename($objImportedDocument->getFilename());
            $objDocument->setCreatedAt(new \DateTime('now'));
            $objDocument->setUpdatedAt(new \DateTime('now'));

            $entityManager->persist($objDocument);

            $objImportedDocument->setIsAccepted(1);
            $objImportedDocument->setAcceptedAt(new \DateTime('now'));

            $entityManager->persist($objImportedDocument);

            $entityManager->flush();
        }

        return $this->redirectToRoute(
            'admin_get_import_documents',
            [
                'error' => $error,
            ]
        );
    }

    public function getDocumentDeleteAction($importedDocumentId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $objImportedDocument = $this->getDoctrine()
            ->getRepository(ImportedDocument::class)
            ->find($importedDocumentId);

        $error = NULL;

        try {
            $entityManager->remove($objImportedDocument);
            $entityManager->flush();
        } catch (\Exception $e) {
            $error = [
                'errorTitle' => 'Nem törölhető',
                'errorText' => $e->getMessage(),
            ];
        }

        return $this->redirectToRoute(
            'admin_get_import_documents',
            [
                'error' => $error,
            ]
        );
    }
}