<?php

namespace AppBundle\Entity;

/**
 * ImportedHouse
 */
class ImportedHouse extends ImportedEntity
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $countryCode;

    /**
     * @var string|null
     */
    private $region;

    /**
     * @var string|null
     */
    private $postalCode;

    /**
     * @var string|null
     */
    private $city;

    /**
     * @var string|null
     */
    private $street;

    /**
     * @var string|null
     */
    private $building;

    /**
     * @var string|null
     */
    private $unit;

    /**
     * @var string|null
     */
    private $lotNumber;

    /**
     * @var string|null
     */
    private $gpsLatitude;

    /**
     * @var string|null
     */
    private $gpsLongitude;


    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return ImportedHouse
     */
    public function setName($name = NULL)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set countryCode.
     *
     * @param string|null $countryCode
     *
     * @return ImportedHouse
     */
    public function setCountryCode($countryCode = NULL)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode.
     *
     * @return string|null
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set region.
     *
     * @param string|null $region
     *
     * @return ImportedHouse
     */
    public function setRegion($region = NULL)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region.
     *
     * @return string|null
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set postalCode.
     *
     * @param string|null $postalCode
     *
     * @return ImportedHouse
     */
    public function setPostalCode($postalCode = NULL)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode.
     *
     * @return string|null
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set city.
     *
     * @param string|null $city
     *
     * @return ImportedHouse
     */
    public function setCity($city = NULL)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string|null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set street.
     *
     * @param string|null $street
     *
     * @return ImportedHouse
     */
    public function setStreet($street = NULL)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street.
     *
     * @return string|null
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set building.
     *
     * @param string|null $building
     *
     * @return ImportedHouse
     */
    public function setBuilding($building = NULL)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * Get building.
     *
     * @return string|null
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * Set unit.
     *
     * @param string|null $unit
     *
     * @return ImportedHouse
     */
    public function setUnit($unit = NULL)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit.
     *
     * @return string|null
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set lotNumber.
     *
     * @param string|null $lotNumber
     *
     * @return ImportedHouse
     */
    public function setLotNumber($lotNumber = NULL)
    {
        $this->lotNumber = $lotNumber;

        return $this;
    }

    /**
     * Get lotNumber.
     *
     * @return string|null
     */
    public function getLotNumber()
    {
        return $this->lotNumber;
    }

    /**
     * Set gpsLatitude.
     *
     * @param string|null $gpsLatitude
     *
     * @return ImportedHouse
     */
    public function setGpsLatitude($gpsLatitude = NULL)
    {
        $this->gpsLatitude = $gpsLatitude;

        return $this;
    }

    /**
     * Get gpsLatitude.
     *
     * @return string|null
     */
    public function getGpsLatitude()
    {
        return $this->gpsLatitude;
    }

    /**
     * Set gpsLongitude.
     *
     * @param string|null $gpsLongitude
     *
     * @return ImportedHouse
     */
    public function setGpsLongitude($gpsLongitude = NULL)
    {
        $this->gpsLongitude = $gpsLongitude;

        return $this;
    }

    /**
     * Get gpsLongitude.
     *
     * @return string|null
     */
    public function getGpsLongitude()
    {
        return $this->gpsLongitude;
    }

    /**
     * @var \AppBundle\Entity\House
     */
    private $house;


    /**
     * Set house.
     *
     * @param \AppBundle\Entity\House|null $house
     *
     * @return ImportedHouse
     */
    public function setHouse(\AppBundle\Entity\House $house = NULL)
    {
        $this->house = $house;

        return $this;
    }

    /**
     * Get house.
     *
     * @return \AppBundle\Entity\House|null
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * @var string|null
     */
    private $taxNumber;

    /**
     * @var \DateTime|null
     */
    private $foundingDate;

    /**
     * @var string|null
     */
    private $bankAccountNumber;


    /**
     * Set taxNumber.
     *
     * @param string|null $taxNumber
     *
     * @return ImportedHouse
     */
    public function setTaxNumber($taxNumber = NULL)
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }

    /**
     * Get taxNumber.
     *
     * @return string|null
     */
    public function getTaxNumber()
    {
        return $this->taxNumber;
    }

    /**
     * Set foundingDate.
     *
     * @param \DateTime|null $foundingDate
     *
     * @return ImportedHouse
     */
    public function setFoundingDate($foundingDate = NULL)
    {
        $this->foundingDate = $foundingDate;

        return $this;
    }

    /**
     * Get foundingDate.
     *
     * @return \DateTime|null
     */
    public function getFoundingDate()
    {
        return $this->foundingDate;
    }

    /**
     * Set bankAccountNumber.
     *
     * @param string|null $bankAccountNumber
     *
     * @return ImportedHouse
     */
    public function setBankAccountNumber($bankAccountNumber = NULL)
    {
        $this->bankAccountNumber = $bankAccountNumber;

        return $this;
    }

    /**
     * Get bankAccountNumber.
     *
     * @return string|null
     */
    public function getBankAccountNumber()
    {
        return $this->bankAccountNumber;
    }

    /**
     * @var float|null
     */
    private $ownershipSum;


    /**
     * Set ownershipSum.
     *
     * @param float|null $ownershipSum
     *
     * @return ImportedHouse
     */
    public function setOwnershipSum($ownershipSum = NULL)
    {
        $this->ownershipSum = $ownershipSum;

        return $this;
    }

    /**
     * Get ownershipSum.
     *
     * @return float|null
     */
    public function getOwnershipSum()
    {
        return $this->ownershipSum;
    }
}
