<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * House
 */
class House
{
    /**
     * @var int
     */
    private $id;

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
    private $lotNumber;

    /**
     * @var string
     */
    private $gpsLatitude;

    /**
     * @var string
     */
    private $gpsLongitude;

    /**
     * @var int
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $documents;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $units;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $houseUsers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->malfunctions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->units = new \Doctrine\Common\Collections\ArrayCollection();
        $this->houseUsers = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set lotNumber.
     *
     * @param string $lotNumber
     *
     * @return House
     */
    public function setLotNumber($lotNumber)
    {
        $this->lotNumber = $lotNumber;

        return $this;
    }

    /**
     * Get lotNumber.
     *
     * @return string
     */
    public function getLotNumber()
    {
        return $this->lotNumber;
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

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return House
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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return House
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
     * @return House
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
     * Add document.
     *
     * @param \AppBundle\Entity\Document $document
     *
     * @return House
     */
    public function addDocument(\AppBundle\Entity\Document $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document.
     *
     * @param \AppBundle\Entity\Document $document
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDocument(\AppBundle\Entity\Document $document)
    {
        return $this->documents->removeElement($document);
    }

    /**
     * Get documents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Add unit.
     *
     * @param \AppBundle\Entity\Unit $unit
     *
     * @return House
     */
    public function addUnit(\AppBundle\Entity\Unit $unit)
    {
        $this->units[] = $unit;

        return $this;
    }

    /**
     * Remove unit.
     *
     * @param \AppBundle\Entity\Unit $unit
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUnit(\AppBundle\Entity\Unit $unit)
    {
        return $this->units->removeElement($unit);
    }

    /**
     * Get units.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * Add houseUser.
     *
     * @param \AppBundle\Entity\HouseUser $houseUser
     *
     * @return House
     */
    public function addHouseUser(\AppBundle\Entity\HouseUser $houseUser)
    {
        $this->houseUsers[] = $houseUser;

        return $this;
    }

    /**
     * Remove houseUser.
     *
     * @param \AppBundle\Entity\HouseUser $houseUser
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeHouseUser(\AppBundle\Entity\HouseUser $houseUser)
    {
        return $this->houseUsers->removeElement($houseUser);
    }

    /**
     * Get houseUsers.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHouseUsers()
    {
        return $this->houseUsers;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $posts;


    /**
     * Add post.
     *
     * @param \AppBundle\Entity\Post $post
     *
     * @return House
     */
    public function addPost(\AppBundle\Entity\Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post.
     *
     * @param \AppBundle\Entity\Post $post
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePost(\AppBundle\Entity\Post $post)
    {
        return $this->posts->removeElement($post);
    }

    /**
     * Get posts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $malfunctions;


    /**
     * Add malfunction.
     *
     * @param \AppBundle\Entity\Malfunction $malfunction
     *
     * @return House
     */
    public function addMalfunction(\AppBundle\Entity\Malfunction $malfunction)
    {
        $this->malfunctions[] = $malfunction;

        return $this;
    }

    /**
     * Remove malfunction.
     *
     * @param \AppBundle\Entity\Malfunction $malfunction
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMalfunction(\AppBundle\Entity\Malfunction $malfunction)
    {
        return $this->malfunctions->removeElement($malfunction);
    }

    /**
     * Get malfunctions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMalfunctions()
    {
        return $this->malfunctions;
    }

    /**
     * @var \DateTime|null
     */
    private $deletedAt;


    /**
     * Set deletedAt.
     *
     * @param \DateTime|null $deletedAt
     *
     * @return House
     */
    public function setDeletedAt($deletedAt = NULL)
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
     * @var \AppBundle\Entity\ImportedHouse
     */
    private $importedHouse;


    /**
     * Set importedHouse.
     *
     * @param \AppBundle\Entity\ImportedHouse|null $importedHouse
     *
     * @return House
     */
    public function setImportedHouse(\AppBundle\Entity\ImportedHouse $importedHouse = NULL)
    {
        $this->importedHouse = $importedHouse;

        return $this;
    }

    /**
     * Get importedHouse.
     *
     * @return \AppBundle\Entity\ImportedHouse|null
     */
    public function getImportedHouse()
    {
        return $this->importedHouse;
    }

    /**
     * @var bool|null
     */
    private $isImported;


    /**
     * Set isImported.
     *
     * @param bool|null $isImported
     *
     * @return House
     */
    public function setIsImported($isImported = NULL)
    {
        $this->isImported = $isImported;

        return $this;
    }

    /**
     * Get isImported.
     *
     * @return bool|null
     */
    public function getIsImported()
    {
        return $this->isImported;
    }

    /**
     * @var \AppBundle\Entity\HouseUser
     */
    private $houseManager;


    /**
     * Set houseManager.
     *
     * @param \AppBundle\Entity\HouseUser|null $houseManager
     *
     * @return House
     */
    public function setHouseManager(\AppBundle\Entity\HouseUser $houseManager = NULL)
    {
        $this->houseManager = $houseManager;

        return $this;
    }

    /**
     * Get houseManager.
     *
     * @return \AppBundle\Entity\HouseUser|null
     */
    public function getHouseManager()
    {
        return $this->houseManager;
    }

    /**
     * return the full address of the house
     * eg.: 1021 Budapest, Budakeszi út 77/B.
     * @return string
     * @author pali
     *
     */
    public function getAddress()
    {
        return $this->getPostalCode() . " " . $this->getCity() . ", " . $this->getStreet() . " " . $this->getBuilding();
    }

    public function getPercent()
    {
        $houseUsers = $this->getHouseUsers();
        $numCountHouseUsers = count($houseUsers);
        if ($numCountHouseUsers == 0) {
            return "0%";
        }
        $numCountRegisteredUsers = 0;
        foreach ($houseUsers as $data) {
            if ($data->getUser()) {
                $numCountRegisteredUsers++;
            }
        }

        if ($numCountRegisteredUsers == 0) {
            return "0%";
        }

        return $numCountRegisteredUsers / $numCountHouseUsers * 100 . "%";
    }
}
