<?php

namespace AppBundle\Entity;

/**
 * Tenant
 */
class Tenant
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string|null
     */
    private $localContactNumber;


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
     * Set localContactNumber.
     *
     * @param string|null $localContactNumber
     *
     * @return Tenant
     */
    public function setLocalContactNumber($localContactNumber = null)
    {
        $this->localContactNumber = $localContactNumber;

        return $this;
    }

    /**
     * Get localContactNumber.
     *
     * @return string|null
     */
    public function getLocalContactNumber()
    {
        return $this->localContactNumber;
    }
}
