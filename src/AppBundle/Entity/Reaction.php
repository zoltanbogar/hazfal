<?php

namespace AppBundle\Entity;

/**
 * Reaction
 */
class Reaction extends SocialEntity
{

    /**
     * @var int
     */
    private $reactionType;

    /**
     * @var \AppBundle\Entity\SocialEntity
     */
    private $socialEntity;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var int
     */
    private $socialEntityId;

    /**
     * @var \AppBundle\Entity\User
     */
    private $user;

    /**
     * Set reactionType.
     *
     * @param int $reactionType
     *
     * @return Reaction
     */
    public function setReactionType($reactionType)
    {
        $this->reactionType = $reactionType;

        return $this;
    }

    /**
     * Get reactionType.
     *
     * @return int
     */
    public function getReactionType()
    {
        return $this->reactionType;
    }

    /**
     * Set user.
     *
     * @param int $user
     *
     * @return Reaction
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * return the type name of the reaction
     *
     * @return string
     */
    public function getReactionTypeName():string
    {
        $typeNames = self::getReactionTypes();
        return $typeNames[$this->getReactionType()];
    }

    public static function getReactionTypes():array
    {
        return $typeNames = [
            1 => 'smiley',
            2 => 'thumbsup',
            3 => 'heart',
            4 => 'rage',
            5 => 'laughing',
            6 => 'hushed',
            7 => 'cry'
        ];
    }

    /**
     * Set socialEntity.
     *
     * @param \AppBundle\Entity\SocialEntity|null $socialEntity
     *
     * @return Comment
     */
    public function setSocialEntity(\AppBundle\Entity\SocialEntity $socialEntity = null)
    {
        $this->socialEntity = $socialEntity;

        return $this;
    }

    /**
     * Get socialEntity.
     *
     * @return \AppBundle\Entity\SocialEntity|null
     */
    public function getSocialEntity()
    {
        return $this->socialEntity;
    }

    /**
     * Set userId.
     *
     * @param int $userId
     *
     * @return Reaction
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set socialEntityId.
     *
     * @param int $socialEntityId
     *
     * @return Reaction
     */
    public function setSocialEntityId($socialEntityId)
    {
        $this->socialEntityId = $socialEntityId;

        return $this;
    }

    /**
     * Get socialEntityId.
     *
     * @return int
     */
    public function getSocialEntityId()
    {
        return $this->socialEntityId;
    }
}
