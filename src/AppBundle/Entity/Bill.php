<?php

namespace AppBundle\Entity;

/**
 * Bill
 */
class Bill
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $billCategory;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $details;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $issuedAt;

    /**
     * @var \DateTime
     */
    private $dueDate;

    /**
     * @var string
     */
    private $receiptNumber;

    /**
     * @var string
     */
    private $issuedForMonth;

    /**
     * @var \AppBundle\Entity\Unit
     */
    private $unit;


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
     * Set billCategory.
     *
     * @param string $billCategory
     *
     * @return Bill
     */
    public function setBillCategory($billCategory)
    {
        $this->billCategory = $billCategory;

        return $this;
    }

    /**
     * Get billCategory.
     *
     * @return string
     */
    public function getBillCategory()
    {
        return $this->billCategory;
    }

    /**
     * Set amount.
     *
     * @param int $amount
     *
     * @return Bill
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return Bill
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
     * Set details.
     *
     * @param string $details
     *
     * @return Bill
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details.
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Bill
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
     * Set issuedAt.
     *
     * @param \DateTime $issuedAt
     *
     * @return Bill
     */
    public function setIssuedAt($issuedAt)
    {
        $this->issuedAt = $issuedAt;

        return $this;
    }

    /**
     * Get issuedAt.
     *
     * @return \DateTime
     */
    public function getIssuedAt()
    {
        return $this->issuedAt;
    }

    /**
     * Set dueDate.
     *
     * @param \DateTime $dueDate
     *
     * @return Bill
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate.
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set receiptNumber.
     *
     * @param string $receiptNumber
     *
     * @return Bill
     */
    public function setReceiptNumber($receiptNumber)
    {
        $this->receiptNumber = $receiptNumber;

        return $this;
    }

    /**
     * Get receiptNumber.
     *
     * @return string
     */
    public function getReceiptNumber()
    {
        return $this->receiptNumber;
    }

    /**
     * Set issuedForMonth.
     *
     * @param string $issuedForMonth
     *
     * @return Bill
     */
    public function setIssuedForMonth($issuedForMonth)
    {
        $this->issuedForMonth = $issuedForMonth;

        return $this;
    }

    /**
     * Get issuedForMonth.
     *
     * @return string
     */
    public function getIssuedForMonth()
    {
        return $this->issuedForMonth;
    }

    /**
     * Set unit.
     *
     * @param \AppBundle\Entity\Unit|null $unit
     *
     * @return Bill
     */
    public function setUnit(\AppBundle\Entity\Unit $unit = null)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit.
     *
     * @return \AppBundle\Entity\Unit|null
     */
    public function getUnit()
    {
        return $this->unit;
    }
    /**
     * @var int
     */
    private $unitId;


    /**
     * Set unitId.
     *
     * @param int $unitId
     *
     * @return Bill
     */
    public function setUnitId($unitId)
    {
        $this->unitId = $unitId;

        return $this;
    }

    /**
     * Get unitId.
     *
     * @return int
     */
    public function getUnitId()
    {
        return $this->unitId;
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return Bill
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
