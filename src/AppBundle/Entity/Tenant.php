<?php

namespace AppBundle\Entity;

/**
 * Tenant
 */
class Tenant extends HouseUser
{

    /**
     * @var string|null
     */
    private $localContactNumber;


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
