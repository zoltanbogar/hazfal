<?php

namespace AppBundle\Entity;

/**
 * ImportedBill
 */
class ImportedBill extends ImportedEntity
{
    /**
     * @var string|null
     */
    private $billCategory;

    /**
     * @var int|null
     */
    private $amount;

    /**
     * @var string|null
     */
    private $details;

    /**
     * @var \DateTime|null
     */
    private $issuedAt;

    /**
     * @var \DateTime|null
     */
    private $dueDate;

    /**
     * @var string|null
     */
    private $receiptNumber;

    /**
     * @var string|null
     */
    private $issuedForMonth;


    /**
     * Set billCategory.
     *
     * @param string|null $billCategory
     *
     * @return ImportedBill
     */
    public function setBillCategory($billCategory = NULL)
    {
        $this->billCategory = $billCategory;

        return $this;
    }

    /**
     * Get billCategory.
     *
     * @return string|null
     */
    public function getBillCategory()
    {
        return $this->billCategory;
    }

    /**
     * Set amount.
     *
     * @param int|null $amount
     *
     * @return ImportedBill
     */
    public function setAmount($amount = NULL)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return int|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set details.
     *
     * @param string|null $details
     *
     * @return ImportedBill
     */
    public function setDetails($details = NULL)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details.
     *
     * @return string|null
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set issuedAt.
     *
     * @param \DateTime|null $issuedAt
     *
     * @return ImportedBill
     */
    public function setIssuedAt($issuedAt = NULL)
    {
        $this->issuedAt = $issuedAt;

        return $this;
    }

    /**
     * Get issuedAt.
     *
     * @return \DateTime|null
     */
    public function getIssuedAt()
    {
        return $this->issuedAt;
    }

    /**
     * Set dueDate.
     *
     * @param \DateTime|null $dueDate
     *
     * @return ImportedBill
     */
    public function setDueDate($dueDate = NULL)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate.
     *
     * @return \DateTime|null
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set receiptNumber.
     *
     * @param string|null $receiptNumber
     *
     * @return ImportedBill
     */
    public function setReceiptNumber($receiptNumber = NULL)
    {
        $this->receiptNumber = $receiptNumber;

        return $this;
    }

    /**
     * Get receiptNumber.
     *
     * @return string|null
     */
    public function getReceiptNumber()
    {
        return $this->receiptNumber;
    }

    /**
     * Set issuedForMonth.
     *
     * @param string|null $issuedForMonth
     *
     * @return ImportedBill
     */
    public function setIssuedForMonth($issuedForMonth = NULL)
    {
        $this->issuedForMonth = $issuedForMonth;

        return $this;
    }

    /**
     * Get issuedForMonth.
     *
     * @return string|null
     */
    public function getIssuedForMonth()
    {
        return $this->issuedForMonth;
    }
    /**
     * @var \AppBundle\Entity\Unit
     */
    private $unit;


    /**
     * Set unit.
     *
     * @param \AppBundle\Entity\Unit|null $unit
     *
     * @return ImportedBill
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
     * @return ImportedBill
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
}