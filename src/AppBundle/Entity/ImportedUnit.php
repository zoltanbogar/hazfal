<?php

namespace AppBundle\Entity;

/**
 * ImportedUnit
 */
class ImportedUnit extends ImportedEntity
{
    /**
     * @var string|null
     */
    private $building;

    /**
     * @var int|null
     */
    private $floor;

    /**
     * @var int|null
     */
    private $door;

    /**
     * @var float|null
     */
    private $floorArea;

    /**
     * @var int|null
     */
    private $type;

    /**
     * @var int|null
     */
    private $balance;

    /**
     * @var string|null
     */
    private $houseShare;


    /**
     * Set building.
     *
     * @param string|null $building
     *
     * @return ImportedUnit
     */
    public function setBuilding($building = NULL)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * Get building.
     *
     * @return string|null
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * Set floor.
     *
     * @param int|null $floor
     *
     * @return ImportedUnit
     */
    public function setFloor($floor = NULL)
    {
        $this->floor = $floor;

        return $this;
    }

    /**
     * Get floor.
     *
     * @return int|null
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * Set door.
     *
     * @param int|null $door
     *
     * @return ImportedUnit
     */
    public function setDoor($door = NULL)
    {
        $this->door = $door;

        return $this;
    }

    /**
     * Get door.
     *
     * @return int|null
     */
    public function getDoor()
    {
        return $this->door;
    }

    /**
     * Set floorArea.
     *
     * @param float|null $floorArea
     *
     * @return ImportedUnit
     */
    public function setFloorArea($floorArea = NULL)
    {
        $this->floorArea = $floorArea;

        return $this;
    }

    /**
     * Get floorArea.
     *
     * @return float|null
     */
    public function getFloorArea()
    {
        return $this->floorArea;
    }

    /**
     * Set type.
     *
     * @param int|null $type
     *
     * @return ImportedUnit
     */
    public function setType($type = NULL)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set balance.
     *
     * @param int|null $balance
     *
     * @return ImportedUnit
     */
    public function setBalance($balance = NULL)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance.
     *
     * @return int|null
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set houseShare.
     *
     * @param string|null $houseShare
     *
     * @return ImportedUnit
     */
    public function setHouseShare($houseShare = NULL)
    {
        $this->houseShare = $houseShare;

        return $this;
    }

    /**
     * Get houseShare.
     *
     * @return string|null
     */
    public function getHouseShare()
    {
        return $this->houseShare;
    }

    /**
     * @var int|null
     */
    private $unit_type;


    /**
     * Set unitType.
     *
     * @param int|null $unitType
     *
     * @return ImportedUnit
     */
    public function setUnitType($unitType = null)
    {
        $this->unit_type = $unitType;

        return $this;
    }

    /**
     * Get unitType.
     *
     * @return int|null
     */
    public function getUnitType()
    {
        return $this->unit_type;
    }

    /**
     * @var int|null
     */
    private $house_id;

    /**
     * @var int|null
     */
    private $tenant_id;


    /**
     * Set houseId.
     *
     * @param int|null $houseId
     *
     * @return ImportedUnit
     */
    public function setHouseId($houseId = null)
    {
        $this->house_id = $houseId;

        return $this;
    }

    /**
     * Get houseId.
     *
     * @return int|null
     */
    public function getHouseId()
    {
        return $this->house_id;
    }

    /**
     * Set tenantId.
     *
     * @param int|null $tenantId
     *
     * @return ImportedUnit
     */
    public function setTenantId($tenantId = null)
    {
        $this->tenant_id = $tenantId;

        return $this;
    }

    /**
     * Get tenantId.
     *
     * @return int|null
     */
    public function getTenantId()
    {
        return $this->tenant_id;
    }

    /**
     * @var \AppBundle\Entity\unit
     */
    private $unit;


    /**
     * Set unit.
     *
     * @param \AppBundle\Entity\unit|null $unit
     *
     * @return ImportedUnit
     */
    public function setUnit(\AppBundle\Entity\unit $unit = null)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit.
     *
     * @return \AppBundle\Entity\unit|null
     */
    public function getUnit()
    {
        return $this->unit;
    }
}
