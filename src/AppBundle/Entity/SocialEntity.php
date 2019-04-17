<?php

namespace AppBundle\Entity;

/**
 * SocialEntity
 */
class SocialEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \DateTime
     */
    private $deletedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $images;

    /**
     * @var \AppBundle\Entity\Reaction
     */
    private $reactions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reactions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return SocialEntity
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

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return SocialEntity
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt.
     *
     * @param \DateTime $deletedAt
     *
     * @return SocialEntity
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt.
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Add image.
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return SocialEntity
     */
    public function addImage(\AppBundle\Entity\Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image.
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeImage(\AppBundle\Entity\Image $image)
    {
        return $this->images->removeElement($image);
    }

    /**
     * Get images.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $comments;


    /**
     * Add comment.
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return SocialEntity
     */
    public function addComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment.
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeComment(\AppBundle\Entity\Comment $comment)
    {
        return $this->comments->removeElement($comment);
    }

    /**
     * Get comments.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }


    /**
     * Add Reaction.
     *
     * @param \AppBundle\Entity\Reaction $reaction
     *
     * @return SocialEntity
     */
    public function addReaction(\AppBundle\Entity\Reaction $reaction)
    {
        $this->reactions[] = $reaction;

        return $this;
    }

    /**
     * Remove Reaction.
     *
     * @param \AppBundle\Entity\Reaction $reaction
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeReaction(\AppBundle\Entity\Reaction $reaction)
    {
        return $this->reactions->removeElement($reaction);
    }

    /**
     * Get reactions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReactions()
    {
        return $this->reactions;
    }

    /**
     * when was the social entity created
     * eg. 2 perce | 2 órája | 2 napja | 2 hete
     *
     * @return void
     */
    public function getWhenCreated()
    {
        $now = new \DateTime('now');
        $createdAt = $this->getCreatedAt();
        $dateDiff = $createdAt->diff($now);
        if( $dateDiff->y > 0 ) {
            return $dateDiff->y." éve";
        } elseif( $dateDiff->m > 0 ) {
            return $dateDiff->m." hónapja";
        } elseif( $dateDiff->d > 0 ) {
            $days = $dateDiff->d;
            if( $dateDiff->d > 7 ) {
                $days = round( $dateDiff->d / 7);
            }
            return $days." napja";
        } elseif( $dateDiff->h > 0 ) {
            return $dateDiff->h." órája";
        } elseif( $dateDiff->i > 0 ) {
            return $dateDiff->i." perce";
        } 
        return 'Éppen most';
    }
}
