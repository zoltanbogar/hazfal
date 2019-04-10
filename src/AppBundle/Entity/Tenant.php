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
     * @var \AppBundle\Entity\UnitTenant
     */
    private $unitTenant;


    /**
     * Set localContactNumber.
     *
     * @param string|null $localContactNumber
     *
     * @return Tenant
     */
    public function setLocalContactNumber($localContactNumber = NULL)
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

    /**
     * Set unitTenant.
     *
     * @param \AppBundle\Entity\UnitTenant|null $unitTenant
     *
     * @return Tenant
     */
    public function setUnitTenant(\AppBundle\Entity\UnitTenant $unitTenant = NULL)
    {
        $this->unitTenant = $unitTenant;

        return $this;
    }

    /**
     * Get unitTenant.
     *
     * @return \AppBundle\Entity\UnitTenant|null
     */
    public function getUnitTenant()
    {
        return $this->unitTenant;
    }

}
