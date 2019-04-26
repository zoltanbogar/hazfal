<?php

namespace ApiBundle\Controller;

use ApiBundle\Service\ResponseHandler;

use AppBundle\Entity\Bill;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Document;
use AppBundle\Entity\DocumentType;
use AppBundle\Entity\House;
use AppBundle\Entity\HouseUser;
use AppBundle\Entity\ImportedDocument;
use AppBundle\Entity\ImportedHouseUser;
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
use AppBundle\Entity\Reaction;
use AppBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $returnData = [];
        foreach ($objPost as $post) {
            $objUser = $post->getUser();
            $socialEntity = $this->getDoctrine()->getRepository(SocialEntity::class)->find($post->getId());
            $reactionTypes = [];
            $postReactions = $socialEntity->getReactions();
            foreach ($postReactions as $reaction) {
                if (get_class($reaction) !== "AppBundle\Entity\Post" && !in_array($reaction->getReactionTypeName(), $reactionTypes)) {
                    $reactionTypes[] = $reaction->getReactionTypeName();
                }
            }
            $comments = [];
            foreach ($post->getComments() as $comment) {
                $commentUser = $comment->getUser();

                $commentReactionTypes = [];
                foreach ($comment->getReactions() as $reaction) {
                    if (get_class($reaction) !== "AppBundle\Entity\Comment" && !in_array($reaction->getReactionTypeName(), $commentReactionTypes)) {
                        $commentReactionTypes[] = $reaction->getReactionTypeName();
                    }
                }
                $comments[] = [
                    'id' => $comment->getId(),
                    'name' => $commentUser->getLastName() . " " . $commentUser->getFirstName(),
                    'userId' => $commentUser->getId(),
                    'content' => $comment->getContent(),
                    'when' => $comment->getWhenCreated(),
                    'image' => "/assets/images/profile/" . $commentUser->getProfileImage(),
                    'reactionTypes' => $commentReactionTypes,
                    'reactionCount' => $comment->getReactions()->count(),
                ];
            }
            $returnData[] = [
                'id' => $post->getId(),
                'content' => $post->getContent(),
                'createdAt' => $post->getWhenCreated(),
                'userName' => $objUser->getLastName() . " " . $objUser->getFirstName(),
                'userId' => $objUser->getId(),
                'isUrgent' => $post->getIsUrgent(),
                'comments' => $comments,
                'reactionTypes' => $reactionTypes,
                'reactionCount' => $post->getReactions()->count(),
                'posterImage' => "/assets/images/profile/" . $objUser->getProfileImage(),
                'image' => $post->getImages()->first() ? $post->getImages()->first()->getFilename() : '',
            ];
        }

        return $this->container->get('response_handler')->successHandler($returnData, $request->query->all());
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
        $returnData = [];
        foreach ($objMalfunction as $malfunction) {
            $returnData[] = [
                'id' => $malfunction->getId(),
                'status' => $malfunction->getStatus(),
                'subject' => $malfunction->getSubject(),
                'content' => $malfunction->getContent(),
                'createdAt' => $malfunction->getCreatedAt()->format('Y.m.d'),
            ];
        }

        return $this->container->get('response_handler')->successHandler($returnData, $request->query->all());
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

        if (!$request->get('subject') || !$request->get('content') || !$request->get('house_id') || !$request->get('user_id')) {
            return $this->container->get('response_handler')->errorHandler("no_valid_data_provided", "Invalid parameters", 422);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $user_id = $request->get('user_id');
        $objUser = $entityManager->find(User::class, $user_id);
        $objPost = new Post();
        $objPost->setSubject($request->get('subject'));
        $objPost->setContent($request->get('content'));
        $objPost->setIsUrgent($request->get('is_urgent') ?? 0);
        $objPost->setUser($objUser);

        $objHouse = $entityManager->find(House::class, $request->get('house_id'));
        $objPost->setHouse($objHouse);
        $objPost->setCreatedAt(new \DateTime('now'));
        $objPost->setUpdatedAt(new \DateTime('now'));
        $objPost->setDeletedAt(NULL);

        $file = $request->files->get('file');
        if ($file) {
            $image = new Image();
            $image->setSocialEntity($objPost);
            $image->setFilename('/assets/images/post/' . $file->getFilename() . '.' . $file->guessExtension());

            try {
                $file->move($this->getParameter('assets_path') . 'images/post/', $file->getFilename() . '.' . $file->guessExtension());
            } catch (\Throwable $th) {
                return $this->container->get('response_handler')->errorHandler("cannot_move_image", $th->getMessage(), 500);
            }
            $entityManager->persist($image);
            $objPost->setImage($image);
        }

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
        $returnData = [];
        foreach ($objPosts as $post) {
            $user = $post->getUser();
            $socialEntity = $this->getDoctrine()->getRepository(SocialEntity::class)->find($post->getId());
            $reactionTypes = [];
            foreach ($post->getReactions() as $reaction) {
                if (get_class($reaction) !== "AppBundle\Entity\Post" && !in_array($reaction->getReactionTypeName(), $reactionTypes)) {
                    $reactionTypes[] = $reaction->getReactionTypeName();
                }
            }
            $comments = [];
            foreach ($post->getComments() as $comment) {
                $commentUser = $comment->getUser();

                $commentReactionTypes = [];
                foreach ($comment->getReactions() as $reaction) {
                    if (get_class($reaction) !== "AppBundle\Entity\Comment" && !in_array($reaction->getReactionTypeName(), $commentReactionTypes)) {
                        $commentReactionTypes[] = $reaction->getReactionTypeName();
                    }
                }

                $comments[] = [
                    'id' => $comment->getId(),
                    'name' => $commentUser->getLastName() . " " . $commentUser->getFirstName(),
                    'content' => $comment->getContent(),
                    'when' => $comment->getWhenCreated(),
                    'image' => "/assets/images/profile/" . $commentUser->getProfileImage(),
                    'reactionTypes' => $commentReactionTypes,
                    'reactionCount' => $comment->getReactions()->count(),
                ];
            }
            $returnData[] = [
                'id' => $post->getId(),
                'content' => $post->getContent(),
                'createdAt' => $post->getWhenCreated(),
                'userName' => $user->getLastName() . " " . $user->getFirstName(),
                'userId' => $user->getId(),
                'isUrgent' => $post->getIsUrgent(),
                'comments' => $comments,
                'reactionTypes' => $reactionTypes,
                'reactionCount' => $post->getReactions()->count(),
                'posterImage' => "/assets/images/profile/" . $user->getProfileImage(),
                'image' => $post->getImages()->first() ? $post->getImages()->first()->getFilename() : '',
            ];
        }

        return $this->container->get('response_handler')->successHandler($returnData, $request->query->all());
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
        if ($request->get('type') === NULL) {
            return $this->container->get('response_handler')->errorHandler("type_not_exists", "Not found", 404);
        }

        $type = $request->get('type');

        if ($type !== "comment") {
            return $this->container->get('response_handler')->errorHandler("no_valid_type_provided", "Invalid parameters", 422);
        }

        if (!$request->get('content') || !$request->get('social_entity_id')) {
            return $this->container->get('response_handler')->errorHandler("no_valid_data_provided", "Invalid parameters", 422);
        }

        $user_id = $request->get('user_id');

        $entityManager = $this->getDoctrine()->getManager();

        $objComment = new Comment();
        $objComment->setContent($request->get('content'));

        $objSocialEntity = $entityManager->find(SocialEntity::class, $request->get('social_entity_id'));

        $objUser = $entityManager->find(User::class, $user_id);
        $objComment->setUser($objUser);
        $objComment->setParentSocialEntity($objSocialEntity);
        $objComment->setCreatedAt(new \DateTime('now'));
        $objComment->setUpdatedAt(new \DateTime('now'));
        $objComment->setDeletedAt(NULL);

        try {
            $entityManager->persist($objComment);
            $entityManager->flush();
        } catch (\Exception $objException) {
            return $this->container->get('response_handler')->errorHandler("cannot_save_post", $objException->getMessage(), 500);
        }

        $post = $entityManager->find(Post::class, $request->get('social_entity_id'));
        $postUser = $post->getUser();

        $reactionTypes = [];
        foreach ($post->getReactions() as $reaction) {
            if (get_class($reaction) !== "AppBundle\Entity\Post" && !in_array($reaction->getReactionTypeName(), $reactionTypes)) {
                $reactionTypes[] = $reaction->getReactionTypeName();
            }
        }
        $comments = [];
        foreach ($post->getComments() as $comment) {
            $commentUser = $comment->getUser();

            $commentReactionTypes = [];
            foreach ($comment->getReactions() as $reaction) {
                if (get_class($reaction) !== "AppBundle\Entity\Comment" && !in_array($reaction->getReactionTypeName(), $commentReactionTypes)) {
                    $commentReactionTypes[] = $reaction->getReactionTypeName();
                }
            }


            $comments[] = [
                'id' => $comment->getId(),
                'name' => $commentUser->getLastName() . " " . $commentUser->getFirstName(),
                'userId' => $commentUser->getId(),
                'content' => $comment->getContent(),
                'when' => $comment->getWhenCreated(),
                'image' => "/assets/images/profile/" . $commentUser->getProfileImage(),
                'reactionTypes' => $commentReactionTypes,
                'reactionCount' => $comment->getReactions()->count(),
            ];
        }
        $returnData = [
            'id' => $post->getId(),
            'content' => $post->getContent(),
            'createdAt' => $post->getWhenCreated(),
            'userName' => $postUser->getLastName() . " " . $postUser->getFirstName(),
            'userId' => $postUser->getId(),
            'isUrgent' => $post->getIsUrgent(),
            'comments' => $comments,
            'reactionTypes' => $reactionTypes,
            'reactionCount' => $post->getReactions()->count(),
            'posterImage' => "/assets/images/profile/" . $postUser->getProfileImage(),
            'image' => $post->getImages()->first() ? $post->getImages()->first()->getFilename() : '',
        ];

        return $this->container->get('response_handler')->successHandler($returnData, $request->query->all());
    }

    /**
     * post a reaction to a social entity
     * @author pali
     */
    public function postReactionToSocialEntityAction(Request $request)
    {

        if (!$request->get('reaction_type') || !$request->get('social_entity_id')) {
            return $this->container->get('response_handler')->errorHandler("no_valid_data_provided", "Invalid parameters", 422);
        }

        //FIXME user_id-t a bejelentkezett usertől kell szerezni
        $userId = $request->get('user_id');

        $entityManager = $this->getDoctrine()->getManager();

        $allReactions = Reaction::getReactionTypes();
        $reactionType = array_search($request->get('reaction_type'), $allReactions);
        if (!$reactionType) {
            return $this->container->get('response_handler')->errorHandler("no_valid_data_provided", "Invalid parameters", 422);
        }

        $reaction = $this->getDoctrine()->getRepository(Reaction::class)->findOneBy(
            ['userId' => $userId, 'socialEntityId' => $request->get('social_entity_id')]
        );
        if (!$reaction) {
            $reaction = new Reaction();
        }
        $reaction->setReactionType($reactionType);

        $objSocialEntity = $entityManager->find(SocialEntity::class, $request->get('social_entity_id'));

        $objUser = $entityManager->find(User::class, $userId);
        $reaction->setUser($objUser);
        $reaction->setSocialEntity($objSocialEntity);
        $reaction->setCreatedAt(new \DateTime('now'));
        $reaction->setUpdatedAt(new \DateTime('now'));
        $reaction->setDeletedAt(NULL);

        try {
            $entityManager->persist($reaction);
            $entityManager->flush();
        } catch (\Exception $objException) {
            return $this->container->get('response_handler')->errorHandler("cannot_save_post", $objException->getMessage(), 500);
        }

        $post = $entityManager->find(Post::class, $request->get('social_entity_id'));
        $postUser = $post->getUser();
        $socialEntity = $objSocialEntity;
        $reactionTypes = [];
        foreach ($post->getReactions() as $reaction) {
            if (get_class($reaction) !== "AppBundle\Entity\Post" && !in_array($reaction->getReactionTypeName(), $reactionTypes)) {
                $reactionTypes[] = $reaction->getReactionTypeName();
            }
        }
        $comments = [];
        foreach ($post->getComments() as $comment) {
            $commentUser = $comment->getUser();

            $commentReactionTypes = [];
            foreach ($comment->getReactions() as $reaction) {
                if (get_class($reaction) !== "AppBundle\Entity\Comment" && !in_array($reaction->getReactionTypeName(), $commentReactionTypes)) {
                    $commentReactionTypes[] = $reaction->getReactionTypeName();
                }
            }
            $comments[] = [
                'id' => $comment->getId(),
                'name' => $commentUser->getLastName() . " " . $commentUser->getFirstName(),
                'content' => $comment->getContent(),
                'when' => $comment->getWhenCreated(),
                'image' => "/assets/images/profile/" . $commentUser->getProfileImage(),
                'reactionTypes' => $commentReactionTypes,
                'reactionCount' => $comment->getReactions()->count(),
            ];
        }
        $returnData = [
            'id' => $post->getId(),
            'content' => $post->getContent(),
            'createdAt' => $post->getWhenCreated(),
            'userName' => $postUser->getLastName() . " " . $postUser->getFirstName(),
            'userId' => $postUser->getId(),
            'isUrgent' => $post->getIsUrgent(),
            'comments' => $comments,
            'reactionTypes' => $reactionTypes,
            'reactionCount' => $post->getReactions()->count(),
            'posterImage' => "/assets/images/profile/" . $postUser->getProfileImage(),
            'image' => $post->getImages()->first() ? $post->getImages()->first()->getFilename() : '',
        ];

        return $this->container->get('response_handler')->successHandler($returnData, $request->query->all());

    }


    /**
     * post a reaction to a social entity
     * @author pali
     */
    public function postReactionToCommentAction(Request $request)
    {

        if (!$request->get('reaction_type') || !$request->get('social_entity_id')) {
            return $this->container->get('response_handler')->errorHandler("no_valid_data_provided", "Invalid parameters", 422);
        }

        //FIXME user_id-t a bejelentkezett usertől kell szerezni
        $userId = $request->get('user_id');

        $entityManager = $this->getDoctrine()->getManager();

        $allReactions = Reaction::getReactionTypes();
        $reactionType = array_search($request->get('reaction_type'), $allReactions);
        if (!$reactionType) {
            return $this->container->get('response_handler')->errorHandler("no_valid_data_provided", "Invalid parameters", 422);
        }

        $reaction = $this->getDoctrine()->getRepository(Reaction::class)->findOneBy(
            ['userId' => $userId, 'socialEntityId' => $request->get('social_entity_id')]
        );
        if (!$reaction) {
            $reaction = new Reaction();
        }
        $reaction->setReactionType($reactionType);

        $objSocialEntity = $entityManager->find(SocialEntity::class, $request->get('social_entity_id'));

        $objUser = $entityManager->find(User::class, $userId);
        $reaction->setUser($objUser);
        $reaction->setSocialEntity($objSocialEntity);
        $reaction->setCreatedAt(new \DateTime('now'));
        $reaction->setUpdatedAt(new \DateTime('now'));
        $reaction->setDeletedAt(NULL);

        try {
            $entityManager->persist($reaction);
            $entityManager->flush();
        } catch (\Exception $objException) {
            return $this->container->get('response_handler')->errorHandler("cannot_save_post", $objException->getMessage(), 500);
        }

        $post = $entityManager->find(Post::class, $request->get('post_id'));
        $postUser = $post->getUser();
        $socialEntity = $objSocialEntity;
        $reactionTypes = [];
        foreach ($post->getReactions() as $reaction) {
            if (get_class($reaction) !== "AppBundle\Entity\Post" && !in_array($reaction->getReactionTypeName(), $reactionTypes)) {
                $reactionTypes[] = $reaction->getReactionTypeName();
            }
        }
        $comments = [];
        foreach ($post->getComments() as $comment) {
            $commentUser = $comment->getUser();

            $commentReactionTypes = [];
            foreach ($comment->getReactions() as $reaction) {
                if (get_class($reaction) !== "AppBundle\Entity\Comment" && !in_array($reaction->getReactionTypeName(), $commentReactionTypes)) {
                    $commentReactionTypes[] = $reaction->getReactionTypeName();
                }
            }
            $comments[] = [
                'id' => $comment->getId(),
                'name' => $commentUser->getLastName() . " " . $commentUser->getFirstName(),
                'content' => $comment->getContent(),
                'when' => $comment->getWhenCreated(),
                'image' => "/assets/images/profile/" . $commentUser->getProfileImage(),
                'reactionTypes' => $commentReactionTypes,
                'reactionCount' => $comment->getReactions()->count(),
            ];
        }
        $returnData = [
            'id' => $post->getId(),
            'content' => $post->getContent(),
            'createdAt' => $post->getWhenCreated(),
            'userName' => $postUser->getLastName() . " " . $postUser->getFirstName(),
            'userId' => $postUser->getId(),
            'isUrgent' => $post->getIsUrgent(),
            'comments' => $comments,
            'reactionTypes' => $reactionTypes,
            'reactionCount' => $post->getReactions()->count(),
            'posterImage' => "/assets/images/profile/" . $postUser->getProfileImage(),
            'image' => $post->getImages()->first() ? $post->getImages()->first()->getFilename() : '',
        ];

        return $this->container->get('response_handler')->successHandler($returnData, $request->query->all());

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
                'id' => 'required|numeric',
            ],
            $request
        );

        if ($validator['hasError']) {
            return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
        }

        $objImportSource = $this->container->get('validation_handler')->importSourceValidationHandler($request);
        if ($objImportSource === FALSE) {
            return $this->container->get('response_handler')->errorHandler('invalid_api_key', 'Invalid Api Key!', 422);
        }

        $isAlreadyAdded = $this->getDoctrine()->getRepository(ImportedDocument::class)->findBy(['externalId' => $request->get('id'), 'isAccepted' => 1]);

        if ($isAlreadyAdded) {
            return $this->container->get('response_handler')->errorHandler('duplication', 'Already imported!', 400);
        }

        if ($request->get('type') !== 'document') {
            return $this->container->get('response_handler')->errorHandler("invalid_entity_type", "Invalid parameters", 422);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $objDocument = new ImportedDocument();
        $objDocument->setName($request->get('name'));
        $objDocument->setHouseId($request->get('house_id'));
        $objDocument->setUserId($request->get('user_id'));
        $objDocument->setImportedAt(new \DateTime('now'));
        $objDocument->setExternalId($request->get('id'));
        $objDocument->setIsAccepted(0);
        $objDocument->setImportSource($objImportSource);

        try {
            $datetime = new \Datetime('now');
            $file = $request->files->get('file');
            $filename = $request->get('user_id') . "_" . str_replace(" ", "_", substr($request->get('filename'), 0, 64)) . "_" . $datetime->format(
                    'Y-m-d_H-i-s'
                ) . "." . $file->guessExtension();
            $objDocument->setFilename($filename);

            $file->move($this->getParameter('doc_uploads_dir'), $filename);

            $entityManager->persist($objDocument);
            $entityManager->flush();
        } catch (\Exception $e) {
            return $this->container->get('response_handler')->errorHandler('file_error', 'File hiba', 400);
        }

        return $this->container->get('response_handler')->successHandler(
            "Document has been imported!",
            $request->query->all()
        );
    }

    public function postBulkImportDocumentAction(Request $request)
    {
        if (!$request->get('payload')) {
            return $this->container->get('response_handler')->errorHandler('empty_payload', 'Empty Payload!', 404);
        }
        $arrPayload = json_decode($request->get('payload'), TRUE);
        if (!$arrPayload) {
            return $this->container->get('response_handler')->errorHandler('invalid_payload', 'Invalid Payload!', 400);
        }

        $objImportSource = $this->container->get('validation_handler')->importSourceValidationHandler($request);
        $entityManager = $this->getDoctrine()->getManager();
        $arrSuccessMSG = [];

        foreach ($arrPayload as $rowPayload) {
            $validator = $this->container->get('validation_handler')->inputValidationHandlerArray(
                [
                    'type' => 'required',
                    'user_id' => 'required|numeric',
                    'document_type_id' => 'required|numeric',
                    'house_id' => 'required|numeric',
                    'name' => 'required',
                    'filename' => 'required',
                    'id' => 'required|numeric',
                ],
                $rowPayload
            );

            if ($validator['hasError']) {
                return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
            }

            $isAlreadyAdded = $this->getDoctrine()->getRepository(ImportedDocument::class)->findBy(['externalId' => $rowPayload['id'], 'isAccepted' => 1]);

            if ($isAlreadyAdded) {
                return $this->container->get('response_handler')->errorHandler('duplication', 'Already imported!', 400);
            }

            $objDocument = new ImportedDocument();
            $objDocument->setName($rowPayload['name']);
            $objDocument->setFilename($rowPayload['filename']);
            $objDocument->setHouseId($rowPayload['house_id']);
            $objDocument->setUserId($rowPayload['user_id']);
            $objDocument->setImportedAt(new \DateTime('now'));
            $objDocument->setExternalId($rowPayload['id']);
            $objDocument->setIsAccepted(0);
            $objDocument->setImportSource($objImportSource);

            try {
                $datetime = new \Datetime('now');
                $file = $request->files->get($rowPayload['filename']);
                $filename = $request->get('user_id') . "_" . str_replace(" ", "_", substr($rowPayload['filename'], 0, 64)) . "_" . $datetime->format(
                        'Y-m-d_H-i-s'
                    ) . "." . $file->guessExtension();
                $objDocument->setFilename($filename);

                $file->move($this->getParameter('doc_uploads_dir'), $filename);

                $entityManager->persist($objDocument);
                $entityManager->flush();
            } catch (\Exception $e) {
                return $this->container->get('response_handler')->errorHandler('file_error', 'File hiba', 400);
            }

            $arrSuccessMSG[] = [
                "msg" => "ID: " . $rowPayload["id"] . ", Dokumentum név: " . $rowPayload["name"] . ", Dokumentum fájlnév: " . $rowPayload["filename"] . ". importálva!",
                "id" => $rowPayload["id"],
            ];
        }

        return $this->container->get('response_handler')->successHandler(
            $arrSuccessMSG,
            []
        );
    }

    public function deleteDocumentAction(Request $request)
    {
        $validator = $this->container->get('validation_handler')->inputValidationHandler(
            [
                'api_key' => 'required',
                'id' => 'required|numeric',
            ],
            $request
        );

        if ($validator['hasError']) {
            return $this->container->get('response_handler')->errorHandler($validator['errorLabel'], $validator['errorText'], $validator['errorCode']);
        }

        $objImportSource = $this->container->get('validation_handler')->importSourceValidationHandler($request);
        if ($objImportSource === FALSE) {
            return $this->container->get('response_handler')->errorHandler('invalid_api_key', 'Invalid Api Key!', 422);
        }

        $objImportedDocument = $this->getDoctrine()->getRepository(ImportedDocument::class)->findBy(['externalId' => $request->get('id'), 'isAccepted' => 1]);

        if (!$objImportedDocument || count($objImportedDocument) > 1) {
            return $this->container->get('response_handler')->errorHandler('document_not_found', 'Document not found, nothing to delete!', 404);
        }

        $objImportedDocument = $objImportedDocument[0];

        $objDocument = $objImportedDocument->getDocument();
        if (!$objDocument) {
            return $this->container->get('response_handler')->errorHandler('document_not_found', 'Document not found, nothing to delete!', 404);
        }

        $entityManager = $this->getDoctrine()->getManager();

        try {
            $entityManager->remove($objDocument);
            $entityManager->remove($objImportedDocument);
            $entityManager->flush();
        } catch (\Exception $e) {
            return $this->container->get('response_handler')->errorHandler('document_cannot_be_deleted', $e->getMessage(), 401);
        }

        return $this->container->get('response_handler')->successHandler(
            "Dokumentum törölve!",
            []
        );
    }
}
