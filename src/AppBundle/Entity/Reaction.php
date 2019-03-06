<?php

namespace AppBundle\Entity;

/**
 * Reaction
 */
class Reaction
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $reactionType;

    /**
     * @var \DateTime
     */
    private $createdAt;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Reaction
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
