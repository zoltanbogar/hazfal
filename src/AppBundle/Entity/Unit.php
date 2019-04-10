<?php

namespace AppBundle\Entity;

/**
 * Unit
 */
class Unit
{
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $payments;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $bills;

    /**
     * @var \AppBundle\Entity\House
     */
    private $house;

    /**
     * @var \AppBundle\Entity\UnitTenant
     */
    private $unitTenant;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->payments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bills = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add payment.
     *
     * @param \AppBundle\Entity\Payment $payment
     *
     * @return Unit
     */
    public function addPayment(\AppBundle\Entity\Payment $payment)
    {
        $this->payments[] = $payment;

        return $this;
    }

    /**
     * Remove payment.
     *
     * @param \AppBundle\Entity\Payment $payment
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePayment(\AppBundle\Entity\Payment $payment)
    {
        return $this->payments->removeElement($payment);
    }

    /**
     * Get payments.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Add bill.
     *
     * @param \AppBundle\Entity\Bill $bill
     *
     * @return Unit
     */
    public function addBill(\AppBundle\Entity\Bill $bill)
    {
        $this->bills[] = $bill;

        return $this;
    }

    /**
     * Remove bill.
     *
     * @param \AppBundle\Entity\Bill $bill
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBill(\AppBundle\Entity\Bill $bill)
    {
        return $this->bills->removeElement($bill);
    }

    /**
     * Get bills.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBills()
    {
        return $this->bills;
    }

    /**
     * Set house.
     *
     * @param \AppBundle\Entity\House|null $house
     *
     * @return Unit
     */
    public function setHouse(\AppBundle\Entity\House $house = null)
    {
        $this->house = $house;

        return $this;
    }

    /**
     * Get house.
     *
     * @return \AppBundle\Entity\House|null
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * Set unitTenant.
     *
     * @param \AppBundle\Entity\UnitTenant|null $unitTenant
     *
     * @return Unit
     */
    public function setUnitTenant(\AppBundle\Entity\UnitTenant $unitTenant = null)
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
    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;


    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Unit
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
     * @return Unit
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $orders;


    /**
     * Add order.
     *
     * @param \AppBundle\Entity\Order $order
     *
     * @return Unit
     */
    public function addOrder(\AppBundle\Entity\Order $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order.
     *
     * @param \AppBundle\Entity\Order $order
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeOrder(\AppBundle\Entity\Order $order)
    {
        return $this->orders->removeElement($order);
    }

    /**
     * Get orders.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $importedBills;


    /**
     * Add importedBill.
     *
     * @param \AppBundle\Entity\ImportedBill $importedBill
     *
     * @return Unit
     */
    public function addImportedBill(\AppBundle\Entity\ImportedBill $importedBill)
    {
        $this->importedBills[] = $importedBill;

        return $this;
    }

    /**
     * Remove importedBill.
     *
     * @param \AppBundle\Entity\ImportedBill $importedBill
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeImportedBill(\AppBundle\Entity\ImportedBill $importedBill)
    {
        return $this->importedBills->removeElement($importedBill);
    }

    /**
     * Get importedBills.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImportedBills()
    {
        return $this->importedBills;
    }
}
