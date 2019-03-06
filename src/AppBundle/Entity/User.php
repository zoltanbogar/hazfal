<?php

namespace AppBundle\Entity;

/**
 * User
 */
class User
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime|null
     */
    private $lastLogin;

    /**
     * @var \DateTime
     */
    private $registrationDate;

    /**
     * @var \DateTime|null
     */
    private $deletedAt;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var \DateTime
     */
    private $dateOfBirth;

    /**
     * @var string
     */
    private $placeOfBirth;

    /**
     * @var string
     */
    private $bio;

    /**
     * @var int
     */
    private $sex;

    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * @var string
     */
    private $localPhoneNumber;

    /**
     * @var int|null
     */
    private $idNumber;

    /**
     * @var string
     */
    private $officialAddress;

    /**
     * @var string
     */
    private $currentLocation;


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
     * Set lastLogin.
     *
     * @param \DateTime|null $lastLogin
     *
     * @return User
     */
    public function setLastLogin($lastLogin = null)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin.
     *
     * @return \DateTime|null
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set registrationDate.
     *
     * @param \DateTime $registrationDate
     *
     * @return User
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * Get registrationDate.
     *
     * @return \DateTime
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * Set deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return User
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
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return User
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
     * @return User
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
     * Set dateOfBirth.
     *
     * @param \DateTime $dateOfBirth
     *
     * @return User
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth.
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set placeOfBirth.
     *
     * @param string $placeOfBirth
     *
     * @return User
     */
    public function setPlaceOfBirth($placeOfBirth)
    {
        $this->placeOfBirth = $placeOfBirth;

        return $this;
    }

    /**
     * Get placeOfBirth.
     *
     * @return string
     */
    public function getPlaceOfBirth()
    {
        return $this->placeOfBirth;
    }

    /**
     * Set bio.
     *
     * @param string $bio
     *
     * @return User
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio.
     *
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set sex.
     *
     * @param int $sex
     *
     * @return User
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex.
     *
     * @return int
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set phoneNumber.
     *
     * @param string $phoneNumber
     *
     * @return User
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
     * Set localPhoneNumber.
     *
     * @param string $localPhoneNumber
     *
     * @return User
     */
    public function setLocalPhoneNumber($localPhoneNumber)
    {
        $this->localPhoneNumber = $localPhoneNumber;

        return $this;
    }

    /**
     * Get localPhoneNumber.
     *
     * @return string
     */
    public function getLocalPhoneNumber()
    {
        return $this->localPhoneNumber;
    }

    /**
     * Set idNumber.
     *
     * @param int|null $idNumber
     *
     * @return User
     */
    public function setIdNumber($idNumber = null)
    {
        $this->idNumber = $idNumber;

        return $this;
    }

    /**
     * Get idNumber.
     *
     * @return int|null
     */
    public function getIdNumber()
    {
        return $this->idNumber;
    }

    /**
     * Set officialAddress.
     *
     * @param string $officialAddress
     *
     * @return User
     */
    public function setOfficialAddress($officialAddress)
    {
        $this->officialAddress = $officialAddress;

        return $this;
    }

    /**
     * Get officialAddress.
     *
     * @return string
     */
    public function getOfficialAddress()
    {
        return $this->officialAddress;
    }

    /**
     * Set currentLocation.
     *
     * @param string $currentLocation
     *
     * @return User
     */
    public function setCurrentLocation($currentLocation)
    {
        $this->currentLocation = $currentLocation;

        return $this;
    }

    /**
     * Get currentLocation.
     *
     * @return string
     */
    public function getCurrentLocation()
    {
        return $this->currentLocation;
    }
}
