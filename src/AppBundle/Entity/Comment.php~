<?php

namespace AppBundle\Entity;

/**
 * Comment
 */
class Comment extends SocialEntity
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var \AppBundle\Entity\User
     */
    private $user;


    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Comment
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
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $user
     *
     * @return Comment
     */
    public function setUser(\AppBundle\Entity\User $user = NULL)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \AppBundle\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @var \AppBundle\Entity\SocialEntity
     */
    private $socialEntity;


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
}
