<?php

namespace AppBundle\Entity;

/**
 * HouseUser
 */
class HouseUser
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $mailingAddress;

    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $companyName;

    /**
     * @var string
     */
    private $companyAddress;

    /**
     * @var string
     */
    private $companyTaxNumber;

    /**
     * @var string
     */
    private $registrationToken;

    /**
     * @var \DateTime
     */
    private $inviteSentAt;

    /**
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;


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
     * Set type.
     *
     * @param int $type
     *
     * @return HouseUser
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
     * Set email.
     *
     * @param string $email
     *
     * @return HouseUser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set mailingAddress.
     *
     * @param string $mailingAddress
     *
     * @return HouseUser
     */
    public function setMailingAddress($mailingAddress)
    {
        $this->mailingAddress = $mailingAddress;

        return $this;
    }

    /**
     * Get mailingAddress.
     *
     * @return string
     */
    public function getMailingAddress()
    {
        return $this->mailingAddress;
    }

    /**
     * Set phoneNumber.
     *
     * @param string $phoneNumber
     *
     * @return HouseUser
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber.
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return HouseUser
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return HouseUser
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set companyName.
     *
     * @param string $companyName
     *
     * @return HouseUser
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName.
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set companyAddress.
     *
     * @param string $companyAddress
     *
     * @return HouseUser
     */
    public function setCompanyAddress($companyAddress)
    {
        $this->companyAddress = $companyAddress;

        return $this;
    }

    /**
     * Get companyAddress.
     *
     * @return string
     */
    public function getCompanyAddress()
    {
        return $this->companyAddress;
    }

    /**
     * Set companyTaxNumber.
     *
     * @param string $companyTaxNumber
     *
     * @return HouseUser
     */
    public function setCompanyTaxNumber($companyTaxNumber)
    {
        $this->companyTaxNumber = $companyTaxNumber;

        return $this;
    }

    /**
     * Get companyTaxNumber.
     *
     * @return string
     */
    public function getCompanyTaxNumber()
    {
        return $this->companyTaxNumber;
    }

    /**
     * Set registrationToken.
     *
     * @param string $registrationToken
     *
     * @return HouseUser
     */
    public function setRegistrationToken($registrationToken)
    {
        $this->registrationToken = $registrationToken;

        return $this;
    }

    /**
     * Get registrationToken.
     *
     * @return string
     */
    public function getRegistrationToken()
    {
        return $this->registrationToken;
    }

    /**
     * Set inviteSentAt.
     *
     * @param \DateTime $inviteSentAt
     *
     * @return HouseUser
     */
    public function setInviteSentAt($inviteSentAt)
    {
        $this->inviteSentAt = $inviteSentAt;

        return $this;
    }

    /**
     * Get inviteSentAt.
     *
     * @return \DateTime
     */
    public function getInviteSentAt()
    {
        return $this->inviteSentAt;
    }

    /**
     * Set deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return HouseUser
     */
    public function setDeletedAt($deletedAt = null)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt.
     *
     * @return \DateTime|null
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return HouseUser
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
     * @return HouseUser
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
}
