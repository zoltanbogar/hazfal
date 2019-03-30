<?php

namespace AppBundle\Entity;

/**
 * Post
 */
class Post extends SocialEntity
{
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
    public function setIsUrgent($isUrgent = NULL)
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
    /**
     * @var \AppBundle\Entity\House
     */
    private $house;


    /**
     * Set house.
     *
     * @param \AppBundle\Entity\House|null $house
     *
     * @return Post
     */
    public function setHouse(\AppBundle\Entity\House $house = null)
    {
        $this->house = $house;

        return $this;
    }

    /**
     * Get house.
     *
     * @return \AppBundle\Entity\House|null
     */
    public function getHouse()
    {
        return $this->house;
    }
}
