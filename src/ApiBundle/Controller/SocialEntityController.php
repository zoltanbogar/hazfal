<?php

namespace ApiBundle\Controller;

use ApiBundle\Service\ResponseHandler;

use AppBundle\Entity\Bill;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentType;
use AppBundle\Entity\House;
use AppBundle\Entity\HouseUser;
use AppBundle\Entity\Malfunction;
use AppBundle\Entity\Manager;
use AppBundle\Entity\Order;
use AppBundle\Entity\Payment;
use AppBundle\Entity\PaymentMethod;
use AppBundle\Entity\Post;
use AppBundle\Entity\SocialEntity;
use AppBundle\Entity\Tenant;
use AppBundle\Entity\Unit;
use AppBundle\Entity\UnitTenant;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\Criteria;

use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Twilio\Rest\Client;

class SocialEntityController extends Controller
{
    public function getDocumentsByHouseIdAction(Request $request)
    {
        if (!$request->get("house_id")) {
            return $this->container->get('response_handler')->errorHandler("no_house_id_provided", "Invalid parameters", 422);
        }

        $numHouseID = $request->get("house_id");
        $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);

        if (!$objHouse) {
            return $this->container->get('response_handler')->errorHandler("house_not_exists", "Not found", 404);
        }

        $objHouseDocuments = $objHouse->getDocuments();

        if (!$objHouseDocuments) {
            return $this->container->get('response_handler')->errorHandler("house_users_not_exist", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objHouseDocuments, $request->query->all());
    }

    public function getPostsByHouseIdAction(Request $request)
    {
        if (!$request->get("house_id")) {
            return $this->container->get('response_handler')->errorHandler("no_house_id_provided", "Invalid parameters", 422);
        }

        $numHouseID = $request->get("house_id");
        $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);

        if (!$objHouse) {
            return $this->container->get('response_handler')->errorHandler("house_not_exists", "Not found", 404);
        }
        $criteria = Criteria::create();
        $criteria->orderBy(['id' => 'DESC']);
        $objPost = $objHouse->getPosts()->matching($criteria);

        if (!$objPost) {
            return $this->container->get('response_handler')->errorHandler("post_not_exists", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objPost, $request->query->all());
    }

    public function getMalfunctionsByHouseIdAction(Request $request)
    {
        if (!$request->get("house_id")) {
            return $this->container->get('response_handler')->errorHandler("no_house_id_provided", "Invalid parameters", 422);
        }

        $numHouseID = $request->get("house_id");
        $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);

        if (!$objHouse) {
            return $this->container->get('response_handler')->errorHandler("house_not_exists", "Not found", 404);
        }

        $objMalfunction = $objHouse->getMalfunctions();

        if (!$objMalfunction) {
            return $this->container->get('response_handler')->errorHandler("malfunction_not_exists", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objMalfunction, $request->query->all());
    }

    public function postPostToHouseAction(Request $request)
    {
        if ($request->get('type') === NULL) {
            return $this->container->get('response_handler')->errorHandler("type_not_exists", "Not found", 404);
        }

        $type = $request->get('type');
        
        if ($type !== "post") {
            return $this->container->get('response_handler')->errorHandler("no_valid_type_provided", "Invalid parameters", 422);
        }
        
        if (!$request->get('subject') || !$request->get('content') || !$request->get('house_id')) {
            return $this->container->get('response_handler')->errorHandler("no_valid_data_provided", "Invalid parameters", 422);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $objPost = new Post();
        $objPost->setSubject($request->get('subject'));
        $objPost->setContent($request->get('content'));
        $objPost->setIsUrgent($request->get('is_urgent') ?? 0);

        $objHouse = $entityManager->find(House::class, $request->get('house_id'));
        $objPost->setHouse($objHouse);
        $objPost->setCreatedAt(new \DateTime('now'));
        $objPost->setUpdatedAt(new \DateTime('now'));
        $objPost->setDeletedAt(NULL);

        try {
            $entityManager->persist($objPost);
            $entityManager->flush();
        } catch (\Exception $objException) {
            return $this->container->get('response_handler')->errorHandler("cannot_save_post", $objException->getMessage(), 500);
        }

        $criteria = Criteria::create();
        $criteria->orderBy(['id' => 'DESC']);
        $objPosts = $objHouse->getPosts()->matching($criteria);

        if (!$objPosts) {
            return $this->container->get('response_handler')->errorHandler("post_not_exists", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objPosts, $request->query->all());
    }

    public function postMalfunctionToHouseAction(Request $request)
    {
        if ($request->query->get('type') === NULL) {
            return $this->container->get('response_handler')->errorHandler("type_not_exists", "Not found", 404);
        }

        $type = $request->query->get('type');

        if ($type !== "malfunction") {
            return $this->container->get('response_handler')->errorHandler("no_valid_type_provided", "Invalid parameters", 422);
        }

        if (!$request->query->get('subject') || !$request->query->get('content') || !$request->query->get('house_id')) {
            return $this->container->get('response_handler')->errorHandler("no_valid_data_provided", "Invalid parameters", 422);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $objMalfunction = new Malfunction();
        $objMalfunction->setSubject($request->query->get('subject'));
        $objMalfunction->setContent($request->query->get('content'));
        $objMalfunction->setStatus($request->query->get('status') ?? 1);

        $objHouse = $entityManager->find(House::class, $request->query->get('house_id'));
        $objMalfunction->setHouse($objHouse);
        $objMalfunction->setCreatedAt(new \DateTime('now'));
        $objMalfunction->setUpdatedAt(new \DateTime('now'));
        $objMalfunction->setDeletedAt(NULL);

        try {
            $entityManager->persist($objMalfunction);
            $entityManager->flush();
        } catch (\Exception $objException) {
            return $this->container->get('response_handler')->errorHandler("cannot_save_post", $objException->getMessage(), 500);
        }

        return $this->container->get('response_handler')->successHandler($objMalfunction, $request->query->all());
    }

    public function postCommentToSocialEntityAction(Request $request)
    {
        if ($request->query->get('type') === NULL) {
            return $this->container->get('response_handler')->errorHandler("type_not_exists", "Not found", 404);
        }

        $type = $request->query->get('type');

        if ($type !== "comment") {
            return $this->container->get('response_handler')->errorHandler("no_valid_type_provided", "Invalid parameters", 422);
        }

        if (!$request->query->get('content') || !$request->query->get('social_entity_id')) {
            return $this->container->get('response_handler')->errorHandler("no_valid_data_provided", "Invalid parameters", 422);
        }

        //FIXME user_id-t a bejelentkezett usertől kell szerezni
        $user_id = 1;

        $entityManager = $this->getDoctrine()->getManager();

        $objComment = new Comment();
        $objComment->setContent($request->query->get('content'));

        $objSocialEntity = $entityManager->find(SocialEntity::class, $request->query->get('social_entity_id'));

        //FIXME user id
        //$objUser = $entityManager->find(User::class, $request->query->get('user_id'));
        $objUser = $entityManager->find(User::class, $user_id);
        $objComment->setUser($objUser);
        $objComment->setSocialEntity($objSocialEntity);
        $objComment->setCreatedAt(new \DateTime('now'));
        $objComment->setUpdatedAt(new \DateTime('now'));
        $objComment->setDeletedAt(NULL);

        try {
            $entityManager->persist($objComment);
            $entityManager->flush();
        } catch (\Exception $objException) {
            return $this->container->get('response_handler')->errorHandler("cannot_save_post", $objException->getMessage(), 500);
        }

        return $this->container->get('response_handler')->successHandler($objComment, $request->query->all());
    }

    public function getCommentsBySocialEntityIdAction(Request $request)
    {
        if (!$request->get("social_entity_id")) {
            return $this->container->get('response_handler')->errorHandler("no_social_entity_id_provided", "Invalid parameters", 422);
        }

        $numSocialEntity = $request->get("social_entity_id");
        $objSocialEntity = $this->getDoctrine()->getRepository(SocialEntity::class)->find($numSocialEntity);

        if (!$objSocialEntity) {
            return $this->container->get('response_handler')->errorHandler("social_entity_not_exists", "Not found", 404);
        }

        $objComment = $objSocialEntity->getComments();

        if (!$objComment) {
            return $this->container->get('response_handler')->errorHandler("comment_not_exists", "Not found", 404);
        }

        return $this->container->get('response_handler')->successHandler($objComment, $request->query->all());
    }

    public function postImportDocumentAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'type' => 'required',
                'user_id' => 'required|numeric',
                'document_type_id' => 'required|numeric',
                'house_id' => 'required|numeric',
                'name' => 'required',
                'filename' => 'required',
            ],
            $request
        );

        if ($validator['hasError']) {
            return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
        }

        $validator = $this->container->get('validation_handler')->importSourceValidationHandler($request);
        if (!$validator) {
            return $this->container->get('response_handler')->errorHandler('invalid_api_key', 'Invalid Api Key!', 422);
        }
        //TODO validálni a duplikáció elkerülésének érdekében
        if ($request->get('type') !== 'document') {
            return $this->container->get('response_handler')->errorHandler("invalid_entity_type", "Invalid parameters", 422);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $objDocument = new Document();
        if ($request->get('user_id')) {
            $numUserID = $request->get("user_id");
            $objUser = $this->getDoctrine()->getRepository(User::class)->find($numUserID);
            if (!$objUser) {
                return $this->container->get('response_handler')->errorHandler("invalid_user_id", "Invalid parameters", 422);
            }
            $objDocument->setUser($objUser);
        }
        if ($request->get('house_id')) {
            $numHouseID = $request->get("house_id");
            $objHouse = $this->getDoctrine()->getRepository(House::class)->find($numHouseID);
            if (!$objHouse) {
                return $this->container->get('response_handler')->errorHandler("invalid_house_id", "Invalid parameters", 422);
            }
            $objDocument->setHouse($objHouse);
        }
        if ($request->get('document_type_id')) {
            $numTypeID = $request->get('document_type_id');
            $objDocumentType = $this->getDoctrine()->getRepository(DocumentType::class)->find($numTypeID);
            if (!$objDocumentType) {
                return $this->container->get('response_handler')->errorHandler("invalid_document_type", "Invalid parameters", 422);
            }
            $objDocument->setDocumentType($objDocumentType);
        }
        $objDocument->setName($request->get('name'));
        $objDocument->setFilename($request->get('filename'));
        $objDocument->setCreatedAt(new \DateTime('now'));
        $objDocument->setUpdatedAt(new \DateTime('now'));

        $entityManager->persist($objDocument);
        $entityManager->flush();

        return $this->container->get('response_handler')->successHandler(
            "Document has been created!",
            $request->query->all()
        );
    }
}