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
}
