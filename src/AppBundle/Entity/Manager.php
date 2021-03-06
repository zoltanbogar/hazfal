<?php

namespace AppBundle\Entity;

/**
 * Manager
 */
class Manager extends HouseUser
{

    /**
     * @var string
     */
    private $website;

    /**
     * @var string
     */
    private $logoImage;


    /**
     * Set website.
     *
     * @param string $website
     *
     * @return Manager
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website.
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set logoImage.
     *
     * @param string $logoImage
     *
     * @return Manager
     */
    public function setLogoImage($logoImage)
    {
        $this->logoImage = $logoImage;

        return $this;
    }

    /**
     * Get logoImage.
     *
     * @return string
     */
    public function getLogoImage()
    {
        return $this->logoImage;
    }
}
