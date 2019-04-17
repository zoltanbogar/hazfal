<?php

namespace AppBundle\Entity;

/**
 * ImportedEntity
 */
class ImportedEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $importedAt;

    /**
     * @var int
     */
    private $externalId;

    /**
     * @var bool
     */
    private $isAccepted;

    /**
     * @var \AppBundle\Entity\ImportSource
     */
    private $importSource;


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
     * Set importedAt.
     *
     * @param \DateTime $importedAt
     *
     * @return ImportedEntity
     */
    public function setImportedAt($importedAt)
    {
        $this->importedAt = $importedAt;

        return $this;
    }

    /**
     * Get importedAt.
     *
     * @return \DateTime
     */
    public function getImportedAt()
    {
        return $this->importedAt;
    }

    /**
     * Set externalId.
     *
     * @param int $externalId
     *
     * @return ImportedEntity
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Get externalId.
     *
     * @return int
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * Set isAccepted.
     *
     * @param bool $isAccepted
     *
     * @return ImportedEntity
     */
    public function setIsAccepted($isAccepted)
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    /**
     * Get isAccepted.
     *
     * @return bool
     */
    public function getIsAccepted()
    {
        return $this->isAccepted;
    }

    /**
     * Set importSource.
     *
     * @param \AppBundle\Entity\ImportSource|null $importSource
     *
     * @return ImportedEntity
     */
    public function setImportSource(\AppBundle\Entity\ImportSource $importSource = NULL)
    {
        $this->importSource = $importSource;

        return $this;
    }

    /**
     * Get importSource.
     *
     * @return \AppBundle\Entity\ImportSource|null
     */
    public function getImportSource()
    {
        return $this->importSource;
    }
    /**
     * @var \DateTime|null
     */
    private $acceptedAt;


    /**
     * Set acceptedAt.
     *
     * @param \DateTime|null $acceptedAt
     *
     * @return ImportedEntity
     */
    public function setAcceptedAt($acceptedAt = null)
    {
        $this->acceptedAt = $acceptedAt;

        return $this;
    }

    /**
     * Get acceptedAt.
     *
     * @return \DateTime|null
     */
    public function getAcceptedAt()
    {
        return $this->acceptedAt;
    }
    /**
     * @var \DateTime|null
     */
    private $deletedAt;


    /**
     * Set deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return ImportedEntity
     */
    public function setDeletedAt($deletedAt = null)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt.
     *
     * @return \DateTime|null
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }
}
