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
     * @var \AppBundle\Entity\User
     */
    private $user;

    /**
     * @var \AppBundle\Entity\Image
     */
    private $image;


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


    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $house
     *
     * @return Post
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get User.
     *
     * @return \AppBundle\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set image.
     *
     * @param \AppBundle\Entity\Image|null $house
     *
     * @return Post
     */
    public function setImage(\AppBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return \AppBundle\Entity\Image|null
     */
    public function getImage()
    {
        return $this->image;
    }

}
