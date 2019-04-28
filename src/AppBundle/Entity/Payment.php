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
     * @var int
     */
    private $unitId;

    /**
     * @var int
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
     * @var string
     */
    private $receiptNumber;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var int
     */
    private $status;

    /**
     * @var \AppBundle\Entity\PaymentMethod
     */
    private $paymentMethod;

    /**
     * @var \AppBundle\Entity\Unit
     */
    private $unit;

    /**
     * @var \AppBundle\Entity\User
     */
    private $user;

    /**
     * @var \AppBundle\Entity\HouseUser
     */
    private $houseUser;


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
     * Set unitId.
     *
     * @param int $unitId
     *
     * @return Payment
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
     * @param int $paymentMethodId
     *
     * @return Payment
     */
    public function setPaymentMethodId($paymentMethodId)
    {
        $this->paymentMethodId = $paymentMethodId;

        return $this;
    }

    /**
     * Get paymentMethodId.
     *
     * @return int
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
     * Set amount.
     *
     * @param int $amount
     *
     * @return Payment
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

    /**
     * Set paymentMethod.
     *
     * @param \AppBundle\Entity\PaymentMethod|null $paymentMethod
     *
     * @return Payment
     */
    public function setPaymentMethod(\AppBundle\Entity\PaymentMethod $paymentMethod = null)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get paymentMethod.
     *
     * @return \AppBundle\Entity\PaymentMethod|null
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Set unit.
     *
     * @param \AppBundle\Entity\Unit|null $unit
     *
     * @return Payment
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
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $user
     *
     * @return Payment
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \AppBundle\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set houseUser.
     *
     * @param \AppBundle\Entity\HouseUser|null $houseUser
     *
     * @return Payment
     */
    public function setHouseUser(\AppBundle\Entity\HouseUser $houseUser = null)
    {
        $this->houseUser = $houseUser;

        return $this;
    }

    /**
     * Get houseUser.
     *
     * @return \AppBundle\Entity\HouseUser|null
     */
    public function getHouseUser()
    {
        return $this->houseUser;
    }
    /**
     * @var \AppBundle\Entity\ImportedPayment
     */
    private $importedPayment;


    /**
     * Set importedPayment.
     *
     * @param \AppBundle\Entity\ImportedPayment|null $importedPayment
     *
     * @return Payment
     */
    public function setImportedPayment(\AppBundle\Entity\ImportedPayment $importedPayment = null)
    {
        $this->importedPayment = $importedPayment;

        return $this;
    }

    /**
     * Get importedPayment.
     *
     * @return \AppBundle\Entity\ImportedPayment|null
     */
    public function getImportedPayment()
    {
        return $this->importedPayment;
    }
}
