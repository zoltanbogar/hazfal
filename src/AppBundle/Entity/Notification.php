<?php

namespace AppBundle\Entity;

/**
 * Notification
 */
class Notification
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \DateTime
     */
    private $seenAt;

    /**
     * @var string
     */
    private $pushSeenAt;

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
     * Set type.
     *
     * @param int $type
     *
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Notification
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set seenAt.
     *
     * @param \DateTime $seenAt
     *
     * @return Notification
     */
    public function setSeenAt($seenAt)
    {
        $this->seenAt = $seenAt;

        return $this;
    }

    /**
     * Get seenAt.
     *
     * @return \DateTime
     */
    public function getSeenAt()
    {
        return $this->seenAt;
    }

    /**
     * Set pushSeenAt.
     *
     * @param string $pushSeenAt
     *
     * @return Notification
     */
    public function setPushSeenAt($pushSeenAt)
    {
        $this->pushSeenAt = $pushSeenAt;

        return $this;
    }

    /**
     * Get pushSeenAt.
     *
     * @return string
     */
    public function getPushSeenAt()
    {
        return $this->pushSeenAt;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Notification
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
