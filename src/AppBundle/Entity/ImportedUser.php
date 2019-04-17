<?php

namespace AppBundle\Entity;

/**
 * ImportedUser
 */
class ImportedUser extends ImportedEntity
{
    /**
     * @var \DateTime|null
     */
    private $registrationDate;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var \DateTime|null
     */
    private $dateOfBirth;

    /**
     * @var string|null
     */
    private $placeOfBirth;

    /**
     * @var string|null
     */
    private $bio;

    /**
     * @var int|null
     */
    private $sex;

    /**
     * @var string|null
     */
    private $phoneNumber;

    /**
     * @var string|null
     */
    private $localPhoneNumber;

    /**
     * @var int|null
     */
    private $idNumber;

    /**
     * @var string|null
     */
    private $officialAddress;

    /**
     * @var string|null
     */
    private $currentLocation;

    /**
     * @var string|null
     */
    private $joinToken;


    /**
     * Set registrationDate.
     *
     * @param \DateTime|null $registrationDate
     *
     * @return ImportedUser
     */
    public function setRegistrationDate($registrationDate = NULL)
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * Get registrationDate.
     *
     * @return \DateTime|null
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return ImportedUser
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
     * @return ImportedUser
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
     * @param \DateTime|null $dateOfBirth
     *
     * @return ImportedUser
     */
    public function setDateOfBirth($dateOfBirth = NULL)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth.
     *
     * @return \DateTime|null
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set placeOfBirth.
     *
     * @param string|null $placeOfBirth
     *
     * @return ImportedUser
     */
    public function setPlaceOfBirth($placeOfBirth = NULL)
    {
        $this->placeOfBirth = $placeOfBirth;

        return $this;
    }

    /**
     * Get placeOfBirth.
     *
     * @return string|null
     */
    public function getPlaceOfBirth()
    {
        return $this->placeOfBirth;
    }

    /**
     * Set bio.
     *
     * @param string|null $bio
     *
     * @return ImportedUser
     */
    public function setBio($bio = NULL)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio.
     *
     * @return string|null
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set sex.
     *
     * @param int|null $sex
     *
     * @return ImportedUser
     */
    public function setSex($sex = NULL)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex.
     *
     * @return int|null
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set phoneNumber.
     *
     * @param string|null $phoneNumber
     *
     * @return ImportedUser
     */
    public function setPhoneNumber($phoneNumber = NULL)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber.
     *
     * @return string|null
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set localPhoneNumber.
     *
     * @param string|null $localPhoneNumber
     *
     * @return ImportedUser
     */
    public function setLocalPhoneNumber($localPhoneNumber = NULL)
    {
        $this->localPhoneNumber = $localPhoneNumber;

        return $this;
    }

    /**
     * Get localPhoneNumber.
     *
     * @return string|null
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
     * @return ImportedUser
     */
    public function setIdNumber($idNumber = NULL)
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
     * @param string|null $officialAddress
     *
     * @return ImportedUser
     */
    public function setOfficialAddress($officialAddress = NULL)
    {
        $this->officialAddress = $officialAddress;

        return $this;
    }

    /**
     * Get officialAddress.
     *
     * @return string|null
     */
    public function getOfficialAddress()
    {
        return $this->officialAddress;
    }

    /**
     * Set currentLocation.
     *
     * @param string|null $currentLocation
     *
     * @return ImportedUser
     */
    public function setCurrentLocation($currentLocation = NULL)
    {
        $this->currentLocation = $currentLocation;

        return $this;
    }

    /**
     * Get currentLocation.
     *
     * @return string|null
     */
    public function getCurrentLocation()
    {
        return $this->currentLocation;
    }

    /**
     * Set joinToken.
     *
     * @param string|null $joinToken
     *
     * @return ImportedUser
     */
    public function setJoinToken($joinToken = NULL)
    {
        $this->joinToken = $joinToken;

        return $this;
    }

    /**
     * Get joinToken.
     *
     * @return string|null
     */
    public function getJoinToken()
    {
        return $this->joinToken;
    }
}
