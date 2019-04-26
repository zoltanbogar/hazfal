<?php

namespace AppBundle\Entity;

/**
 * ImportedDocument
 */
class ImportedDocument extends ImportedEntity
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var int|null
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
     * Set userId.
     *
     * @param int $userId
     *
     * @return ImportedDocument
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
     * @param int|null $documentTypeId
     *
     * @return ImportedDocument
     */
    public function setDocumentTypeId($documentTypeId = NULL)
    {
        $this->documentTypeId = $documentTypeId;

        return $this;
    }

    /**
     * Get documentTypeId.
     *
     * @return int|null
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
     * @return ImportedDocument
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
     * @return ImportedDocument
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
     * @return ImportedDocument
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
     * @var \AppBundle\Entity\Document
     */
    private $document;


    /**
     * Set document.
     *
     * @param \AppBundle\Entity\Document|null $document
     *
     * @return ImportedDocument
     */
    public function setDocument(\AppBundle\Entity\Document $document = null)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document.
     *
     * @return \AppBundle\Entity\Document|null
     */
    public function getDocument()
    {
        return $this->document;
    }
}
