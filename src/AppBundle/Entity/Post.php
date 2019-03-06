<?php

namespace AppBundle\Entity;

/**
 * Post
 */
class Post
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $content;

    /**
     * @var bool|null
     */
    private $isUrgent;


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
     * Set subject.
     *
     * @param string $subject
     *
     * @return Post
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Post
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
     * Set isUrgent.
     *
     * @param bool|null $isUrgent
     *
     * @return Post
     */
    public function setIsUrgent($isUrgent = null)
    {
        $this->isUrgent = $isUrgent;

        return $this;
    }

    /**
     * Get isUrgent.
     *
     * @return bool|null
     */
    public function getIsUrgent()
    {
        return $this->isUrgent;
    }
}
