<?php

namespace AppBundle\Entity;

/**
 * Unit
 */
class Unit
{
    private $payments;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $building;

    /**
     * @var int
     */
    private $floor;

    /**
     * @var int
     */
    private $door;

    /**
     * @var float
     */
    private $floorArea;

    /**
     * @var int
     */
    private $type;

    /**
     * @var int
     */
    private $balance;

    /**
     * @var string
     */
    private $houseShare;


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
     * Set building.
     *
     * @param string $building
     *
     * @return Unit
     */
    public function setBuilding($building)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * Get building.
     *
     * @return string
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * Set floor.
     *
     * @param int $floor
     *
     * @return Unit
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;

        return $this;
    }

    /**
     * Get floor.
     *
     * @return int
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * Set door.
     *
     * @param int $door
     *
     * @return Unit
     */
    public function setDoor($door)
    {
        $this->door = $door;

        return $this;
    }

    /**
     * Get door.
     *
     * @return int
     */
    public function getDoor()
    {
        return $this->door;
    }

    /**
     * Set floorArea.
     *
     * @param float $floorArea
     *
     * @return Unit
     */
    public function setFloorArea($floorArea)
    {
        $this->floorArea = $floorArea;

        return $this;
    }

    /**
     * Get floorArea.
     *
     * @return float
     */
    public function getFloorArea()
    {
        return $this->floorArea;
    }

    /**
     * Set type.
     *
     * @param int $type
     *
     * @return Unit
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
     * Set balance.
     *
     * @param int $balance
     *
     * @return Unit
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance.
     *
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set houseShare.
     *
     * @param string $houseShare
     *
     * @return Unit
     */
    public function setHouseShare($houseShare)
    {
        $this->houseShare = $houseShare;

        return $this;
    }

    /**
     * Get houseShare.
     *
     * @return string
     */
    public function getHouseShare()
    {
        return $this->houseShare;
    }
}
