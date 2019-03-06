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
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
}
