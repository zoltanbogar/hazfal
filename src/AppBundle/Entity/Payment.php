<?php

namespace AppBundle\Entity;

/**
 * Payment
 */
class Payment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $paymentDate;

    /**
     * @var \DateTime
     */
    private $bookingDate;

    /**
     * @var string
     */
    private $receiptNumber;

    /**
     * @var int
     */
    private $value;

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
     * Set paymentDate.
     *
     * @param \DateTime $paymentDate
     *
     * @return Payment
     */
    public function setPaymentDate($paymentDate)
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    /**
     * Get paymentDate.
     *
     * @return \DateTime
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * Set bookingDate.
     *
     * @param \DateTime $bookingDate
     *
     * @return Payment
     */
    public function setBookingDate($bookingDate)
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }

    /**
     * Get bookingDate.
     *
     * @return \DateTime
     */
    public function getBookingDate()
    {
        return $this->bookingDate;
    }

    /**
     * Set receiptNumber.
     *
     * @param string $receiptNumber
     *
     * @return Payment
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
     * Set value.
     *
     * @param int $value
     *
     * @return Payment
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return Payment
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
