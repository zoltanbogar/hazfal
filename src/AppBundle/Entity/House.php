<?php

namespace AppBundle\Entity;

/**
 * House
 */
class House
{
    private $houseUser;
    private $documents;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $houseUserId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var string
     */
    private $region;

    /**
     * @var string
     */
    private $postalCode;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $building;

    /**
     * @var string
     */
    private $unit;

    /**
     * @var string
     */
    private $hrsz;

    /**
     * @var string
     */
    private $gpsLatitude;

    /**
     * @var string
     */
    private $gpsLongitude;


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
     * Set name.
     *
     * @param string $name
     *
     * @return House
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set countryCode.
     *
     * @param string $countryCode
     *
     * @return House
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode.
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set region.
     *
     * @param string $region
     *
     * @return House
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region.
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set postalCode.
     *
     * @param string $postalCode
     *
     * @return House
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode.
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return House
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set street.
     *
     * @param string $street
     *
     * @return House
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street.
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set building.
     *
     * @param string $building
     *
     * @return House
     */
    public function setBuilding($building)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * Get building.
     *
     * @return string
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * Set unit.
     *
     * @param string $unit
     *
     * @return House
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit.
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set hrsz.
     *
     * @param string $hrsz
     *
     * @return House
     */
    public function setHrsz($hrsz)
    {
        $this->hrsz = $hrsz;

        return $this;
    }

    /**
     * Get hrsz.
     *
     * @return string
     */
    public function getHrsz()
    {
        return $this->hrsz;
    }

    /**
     * Set gpsLatitude.
     *
     * @param string $gpsLatitude
     *
     * @return House
     */
    public function setGpsLatitude($gpsLatitude)
    {
        $this->gpsLatitude = $gpsLatitude;

        return $this;
    }

    /**
     * Get gpsLatitude.
     *
     * @return string
     */
    public function getGpsLatitude()
    {
        return $this->gpsLatitude;
    }

    /**
     * Set gpsLongitude.
     *
     * @param string $gpsLongitude
     *
     * @return House
     */
    public function setGpsLongitude($gpsLongitude)
    {
        $this->gpsLongitude = $gpsLongitude;

        return $this;
    }

    /**
     * Get gpsLongitude.
     *
     * @return string
     */
    public function getGpsLongitude()
    {
        return $this->gpsLongitude;
    }
}
