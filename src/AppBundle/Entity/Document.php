<?php

namespace AppBundle\Entity;

/**
 * Document
 */
class Document extends SocialEntity
{

    private $documentType;
    private $user;
    private $house;

    /**
     * @var int
     */
    private $documentTypeId;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var int
     */
    private $houseId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $filename;


    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Document
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set filename.
     *
     * @param string $filename
     *
     * @return Document
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
}
