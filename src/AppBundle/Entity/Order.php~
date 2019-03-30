<?php

namespace AppBundle\Entity;

/**
 * Order
 */
class Order
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $orderId;

    /**
     * @var int
     */
    private $isPaid;

    /**
     * @var string
     */
    private $cart;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \AppBundle\Entity\User
     */
    private $user;

    /**
     * @var \AppBundle\Entity\PaymentMethod
     */
    private $paymentMethod;

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
     * Set orderId.
     *
     * @param string $orderId
     *
     * @return Order
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get orderId.
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set isPaid.
     *
     * @param int $isPaid
     *
     * @return Order
     */
    public function setIsPaid($isPaid)
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    /**
     * Get isPaid.
     *
     * @return int
     */
    public function getIsPaid()
    {
        return $this->isPaid;
    }

    /**
     * Set cart.
     *
     * @param string $cart
     *
     * @return Order
     */
    public function setCart($cart)
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * Get cart.
     *
     * @return string
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Order
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
     * @return Order
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
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $user
     *
     * @return Order
     */
    public function setUser(\AppBundle\Entity\User $user = NULL)
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
     * Set paymentMethod.
     *
     * @param \AppBundle\Entity\PaymentMethod|null $paymentMethod
     *
     * @return Order
     */
    public function setPaymentMethod(\AppBundle\Entity\PaymentMethod $paymentMethod = NULL)
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
     * @var string
     */
    private $checkHash;


    /**
     * Set checkHash.
     *
     * @param string $checkHash
     *
     * @return Order
     */
    public function setCheckHash($checkHash)
    {
        $this->checkHash = $checkHash;

        return $this;
    }

    /**
     * Get checkHash.
     *
     * @return string
     */
    public function getCheckHash()
    {
        return $this->checkHash;
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
     * @return Order
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
}
