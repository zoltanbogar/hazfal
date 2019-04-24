<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\House;
use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render(
            'default/index.html.twig',
            [
                'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
            ]
        );
    }

    /**
     * @Route("/house-dashboard/{houseId}", name="house-dashboard", methods={"GET"})
     * @Route("/house-dashboard/{houseId}/{apiKey}", name="house-dashboard-with-apikey", methods={"GET"})
     */
    public function getHouseDashboardAction($houseId, $apiKey = NULL)
    {
        $objUser = $this->getUser();

        if ($objUser === NULL) {
            if ($apiKey !== NULL) {
                $objUser = $this->getDoctrine()->getRepository(User::class)->findUserByApiKey($apiKey);

                if (!$objUser) {
                    return $this->container->get('response_handler')->errorHandler("invalid_parameters", "User not found", 404);
                }
            } else {
                return $this->container->get('response_handler')->errorHandler("invalid_parameters", "User not found", 404);
            }
        }

        $objHouse = $this->getDoctrine()->getRepository(House::class)->find($houseId);

        $arrUnits = [];
        $arrHistory = [];
        $numHistoryCounter = 0;

        if ($objUser->getHouseUsers() !== NULL) {
            foreach ($objUser->getHouseUsers() as $houseUser) {
                if ($houseUser->getUnitTenant() !== NULL) {
                    foreach ($houseUser->getUnitTenant() as $tenant) {
                        if ((int)$houseId === $tenant->getUnit()->getHouse()->getId()) {
                            $arrUnits[] = [
                                'id' => $tenant->getUnit()->getId(),
                                'type' => $tenant->getUnit()->getUnitType(),
                                'address' => $tenant->getUnit()->getBuilding() . " " . $tenant->getUnit()->getFloor() . " " . $tenant->getUnit()->getDoor(),
                                'balance' => $tenant->getUnit()->getBalance(),
                            ];
                        }
                    }
                }
            }
        }

        $objPosts = $this->getDoctrine()->getRepository(Post::class)->findLatestPostsInHouseById($houseId);

        if ($objPosts !== NULL) {
            foreach ($objPosts as $objPost) {
                if ($numHistoryCounter >= 10) {
                    break;
                }

                $arrHistory[] = [
                    'userImage' => $objPost->getUser()->getProfileImage(),
                    'userId' => $objPost->getUser()->getId(),
                    'id' => $objPost->getId(),
                    'type' => 'post',
                    'when' => $objPost->getWhenCreated(),
                ];

                $objComments = $this->getDoctrine()->getRepository(Comment::class)->findLatestCommentsByPostId($objPost->getId());

                if ($objComments) {
                    foreach ($objComments as $objComment) {
                        $arrHistory[] = [
                            'userImage' => $objComment->getUser()->getProfileImage(),
                            'userId' => $objComment->getUser()->getId(),
                            'id' => $objComment->getId(),
                            'type' => 'comment',
                            'when' => $objComment->getWhenCreated(),
                        ];
                        $numHistoryCounter++;
                    }
                }
                $numHistoryCounter++;
            }
        }

        $arrResponseData = [
            'isEnabled' => $objUser->isEnabled(),
            'isGotPhonePhone' => ($objUser->getPhoneNumber() !== NULL ? TRUE : FALSE),
            'nextMeetingAt' => $this->randomDateInRange(
                new \Datetime(date("Y-m-d H:i:s", strtotime("+7 days"))),
                new \Datetime(date("Y-m-d H:i:s", strtotime("+2 months")))
            ),
            'houseHistory' => $arrHistory,
            'houseManager' => [
                'userImage' => ($objHouse->getHouseManager() && $objHouse->getHouseManager()->getUser() ? $objHouse->getHouseManager()->getUser(
                )->getProfileImage() : NULL),
                'userId' => ($objHouse->getHouseManager()->getUser() ? $objHouse->getHouseManager()->getUser()->getId() : NULL),
                'name' => ($objHouse->getHouseManager()->getUser() ? $objHouse->getHouseManager()->getUser()->getFullName() : NULL),
                'company' => $objHouse->getHouseManager()->getCompanyName(),
                'phone' => ($objHouse->getHouseManager()->getUser() ? $objHouse->getHouseManager()->getUser()->getPhoneNumber() : NULL),
                'email' => ($objHouse->getHouseManager()->getUser() ? $objHouse->getHouseManager()->getUser()->getEmail() : NULL),
                'address' => ($objHouse->getHouseManager()->getUser() ? $objHouse->getHouseManager()->getUser()->getOfficialAddress() : NULL),
            ],
            'units' => $arrUnits,
        ];

        return $this->container->get('response_handler')->successHandler($arrResponseData, []);
    }

    private function randomDateInRange(\DateTime $start, \DateTime $end)
    {
        $randomTimestamp = mt_rand($start->getTimestamp(), $end->getTimestamp());
        $randomDate = new \DateTime();
        $randomDate->setTimestamp($randomTimestamp);

        return $randomDate->format('Y-m-d H:i:s');
    }
}
