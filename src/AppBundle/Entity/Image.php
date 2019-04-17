<?php

namespace AppBundle\Entity;

/**
 * Image
 */
class Image
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var \AppBundle\Entity\Image
     */
    private $socialEntity;


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
     * Set filename.
     *
     * @param string $filename
     *
     * @return Image
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set socialEntity.
     *
     * @param \AppBundle\Entity\SocialEntity|null $socialEntity
     *
     * @return SocialEntity
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
