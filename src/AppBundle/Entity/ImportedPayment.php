<?php

namespace AppBundle\Entity;

/**
 * ImportedPayment
 */
class ImportedPayment extends ImportedEntity
{
    /**
     * @var int
     */
    private $unitId;

    /**
     * @var int|null
     */
    private $paymentMethodId;

    /**
     * @var \DateTime
     */
    private $paymentDate;

    /**
     * @var \DateTime
     */
    private $bookingDate;

    /**
     * @var string|null
     */
    private $receiptNumber;

    /**
     * @var int
     */
    private $amount;


    /**
     * Set unitId.
     *
     * @param int $unitId
     *
     * @return ImportedPayment
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
     * Set paymentMethodId.
     *
     * @param int|null $paymentMethodId
     *
     * @return ImportedPayment
     */
    public function setPaymentMethodId($paymentMethodId = NULL)
    {
        $this->paymentMethodId = $paymentMethodId;

        return $this;
    }

    /**
     * Get paymentMethodId.
     *
     * @return int|null
     */
    public function getPaymentMethodId()
    {
        return $this->paymentMethodId;
    }

    /**
     * Set paymentDate.
     *
     * @param \DateTime $paymentDate
     *
     * @return ImportedPayment
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
     * @return ImportedPayment
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
     * @param string|null $receiptNumber
     *
     * @return ImportedPayment
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
     * Set amount.
     *
     * @param int $amount
     *
     * @return ImportedPayment
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
     * @var int
     */
    private $usetId;

    /**
     * @var int
     */
    private $houseUserId;


    /**
     * Set usetId.
     *
     * @param int $usetId
     *
     * @return ImportedPayment
     */
    public function setUsetId($usetId)
    {
        $this->usetId = $usetId;

        return $this;
    }

    /**
     * Get usetId.
     *
     * @return int
     */
    public function getUsetId()
    {
        return $this->usetId;
    }

    /**
     * Set houseUserId.
     *
     * @param int $houseUserId
     *
     * @return ImportedPayment
     */
    public function setHouseUserId($houseUserId)
    {
        $this->houseUserId = $houseUserId;

        return $this;
    }

    /**
     * Get houseUserId.
     *
     * @return int
     */
    public function getHouseUserId()
    {
        return $this->houseUserId;
    }
    /**
     * @var int
     */
    private $uniId;

    /**
     * @var int
     */
    private $userId;


    /**
     * Set uniId.
     *
     * @param int $uniId
     *
     * @return ImportedPayment
     */
    public function setUniId($uniId)
    {
        $this->uniId = $uniId;

        return $this;
    }

    /**
     * Get uniId.
     *
     * @return int
     */
    public function getUniId()
    {
        return $this->uniId;
    }

    /**
     * Set userId.
     *
     * @param int $userId
     *
     * @return ImportedPayment
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }
    /**
     * @var \AppBundle\Entity\Payment
     */
    private $payment;


    /**
     * Set payment.
     *
     * @param \AppBundle\Entity\Payment|null $payment
     *
     * @return ImportedPayment
     */
    public function setPayment(\AppBundle\Entity\Payment $payment = null)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment.
     *
     * @return \AppBundle\Entity\Payment|null
     */
    public function getPayment()
    {
        return $this->payment;
    }
}
