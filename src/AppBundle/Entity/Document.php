<?php

namespace AppBundle\Entity;

/**
 * Document
 */
class Document extends SocialEntity
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var int
     */
    private $documentTypeId;

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
     * @var \AppBundle\Entity\User
     */
    private $user;

    /**
     * @var \AppBundle\Entity\DocumentType
     */
    private $documentType;

    /**
     * @var \AppBundle\Entity\House
     */
    private $house;


    /**
     * Set userId.
     *
     * @param int $userId
     *
     * @return Document
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
     * Set documentTypeId.
     *
     * @param int $documentTypeId
     *
     * @return Document
     */
    public function setDocumentTypeId($documentTypeId)
    {
        $this->documentTypeId = $documentTypeId;

        return $this;
    }

    /**
     * Get documentTypeId.
     *
     * @return int
     */
    public function getDocumentTypeId()
    {
        return $this->documentTypeId;
    }

    /**
     * Set houseId.
     *
     * @param int $houseId
     *
     * @return Document
     */
    public function setHouseId($houseId)
    {
        $this->houseId = $houseId;

        return $this;
    }

    /**
     * Get houseId.
     *
     * @return int
     */
    public function getHouseId()
    {
        return $this->houseId;
    }

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

    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $user
     *
     * @return Document
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
     * Set documentType.
     *
     * @param \AppBundle\Entity\DocumentType|null $documentType
     *
     * @return Document
     */
    public function setDocumentType(\AppBundle\Entity\DocumentType $documentType = NULL)
    {
        $this->documentType = $documentType;

        return $this;
    }

    /**
     * Get documentType.
     *
     * @return \AppBundle\Entity\DocumentType|null
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

    /**
     * Set house.
     *
     * @param \AppBundle\Entity\House|null $house
     *
     * @return Document
     */
    public function setHouse(\AppBundle\Entity\House $house = NULL)
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