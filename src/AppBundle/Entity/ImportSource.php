<?php

namespace AppBundle\Entity;

/**
 * ImportSource
 */
class ImportSource
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var bool
     */
    private $isActive;


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
     * Set name.
     *
     * @param string $name
     *
     * @return ImportSource
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
     * Set slug.
     *
     * @param string $slug
     *
     * @return ImportSource
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set apiKey.
     *
     * @param string $apiKey
     *
     * @return ImportSource
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set isActive.
     *
     * @param bool $isActive
     *
     * @return ImportSource
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive.
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    /**
     * @var string
     */
    private $oneToMany;


    /**
     * Set oneToMany.
     *
     * @param string $oneToMany
     *
     * @return ImportSource
     */
    public function setOneToMany($oneToMany)
    {
        $this->oneToMany = $oneToMany;

        return $this;
    }

    /**
     * Get oneToMany.
     *
     * @return string
     */
    public function getOneToMany()
    {
        return $this->oneToMany;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $importedEntities;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->importedEntities = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add importedEntity.
     *
     * @param \AppBundle\Entity\ImportedEntity $importedEntity
     *
     * @return ImportSource
     */
    public function addImportedEntity(\AppBundle\Entity\ImportedEntity $importedEntity)
    {
        $this->importedEntities[] = $importedEntity;

        return $this;
    }

    /**
     * Remove importedEntity.
     *
     * @param \AppBundle\Entity\ImportedEntity $importedEntity
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeImportedEntity(\AppBundle\Entity\ImportedEntity $importedEntity)
    {
        return $this->importedEntities->removeElement($importedEntity);
    }

    /**
     * Get importedEntities.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImportedEntities()
    {
        return $this->importedEntities;
    }
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $username;


    /**
     * Set email.
     *
     * @param string $email
     *
     * @return ImportSource
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return ImportSource
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
}
