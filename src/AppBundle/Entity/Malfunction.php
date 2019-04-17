<?php

namespace AppBundle\Entity;

/**
 * Malfunction
 */
class Malfunction extends SocialEntity
{
    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $content;

    /**
     * @var int
     */
    private $status;


    /**
     * Set subject.
     *
     * @param string $subject
     *
     * @return Malfunction
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Malfunction
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return Malfunction
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
     * @var \AppBundle\Entity\House
     */
    private $house;


    /**
     * Set house.
     *
     * @param \AppBundle\Entity\House|null $house
     *
     * @return Malfunction
     */
    public function setHouse(\AppBundle\Entity\House $house = null)
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
}
