<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * MaskedSchoolDetails
 *
 * @ORM\Table(name="ip_country_code" )
 * @ORM\Entity
 */

class IpCountryCode 
{
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="country_code", type="string")
     * @var string
     */
    protected $countryCode;
    /**
     * @ORM\Column(name="state", type="string")
     * @var string
     */
    protected $state;
    /**
     * @ORM\Column(name="city", type="string")
     * @var string
     */
    protected $city;
    
     /**
     * @ORM\Column(name="ip", type="string")
     * @var string
     */
    protected $ip;

    
    /**
     * @ORM\Column(name="date", type="datetime")
     * @var datetime
     */
    protected $date;

         /**
     * @ORM\Column(name="status", type="string")
     * @var string
     */
    protected $status;

    
    public function __construct() {
        $this->date = new \DateTime();
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
     * Set id.
     *
     * @param int $id
     * @return UserInterface
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * Get schoolName.
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set username.
     *
     * @param string $username
     * @return UserInterface
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }
    
    
    public function setState($state) {
            $this->state = $state;
    }
    public function getState() {
           return  $this->state;
    }
    public function setCity($city) {
            $this->city = $city;
    }
    public function getCity() {
           return  $this->city;
    }
    
    /**
     * Get email.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set email.
     *
     * @param string $email
     * @return UserInterface
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }
    
    
    /**
     * Get email.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set email.
     *
     * @param string $email
     * @return UserInterface
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    
}
