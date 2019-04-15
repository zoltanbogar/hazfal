<?php

namespace AppBundle\Entity;

/**
 * UnitTenant
 */
class UnitTenant
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $ownershipShare;

    /**
     * @var int
     */
    private $status;

    /**
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \AppBundle\Entity\Tenant
     */
    private $tenant;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $units;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->units = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set type.
     *
     * @param int $type
     *
     * @return UnitTenant
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set ownershipShare.
     *
     * @param string $ownershipShare
     *
     * @return UnitTenant
     */
    public function setOwnershipShare($ownershipShare)
    {
        $this->ownershipShare = $ownershipShare;

        return $this;
    }

    /**
     * Get ownershipShare.
     *
     * @return string
     */
    public function getOwnershipShare()
    {
        return $this->ownershipShare;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return UnitTenant
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return UnitTenant
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

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return UnitTenant
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
     * @return UnitTenant
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
     * Set tenant.
     *
     * @param \AppBundle\Entity\Tenant|null $tenant
     *
     * @return UnitTenant
     */
    public function setTenant(\AppBundle\Entity\Tenant $tenant = null)
    {
        $this->tenant = $tenant;

        return $this;
    }

    /**
     * Get tenant.
     *
     * @return \AppBundle\Entity\Tenant|null
     */
    public function getTenant()
    {
        return $this->tenant;
    }

    /**
     * Add unit.
     *
     * @param \AppBundle\Entity\Unit $unit
     *
     * @return UnitTenant
     */
    public function addUnit(\AppBundle\Entity\Unit $unit)
    {
        $this->units[] = $unit;

        return $this;
    }

    /**
     * Remove unit.
     *
     * @param \AppBundle\Entity\Unit $unit
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUnit(\AppBundle\Entity\Unit $unit)
    {
        return $this->units->removeElement($unit);
    }

    /**
     * Get units.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUnits()
    {
        return $this->units;
    }
    /**
     * @var int|null
     */
    private $unitTenantType;


    /**
     * Set unitTenantType.
     *
     * @param int|null $unitTenantType
     *
     * @return UnitTenant
     */
    public function setUnitTenantType($unitTenantType = null)
    {
        $this->unitTenantType = $unitTenantType;

        return $this;
    }

    /**
     * Get unitTenantType.
     *
     * @return int|null
     */
    public function getUnitTenantType()
    {
        return $this->unitTenantType;
    }
}
