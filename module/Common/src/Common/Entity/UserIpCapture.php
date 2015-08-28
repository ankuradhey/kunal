<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * MaskedSchoolDetails
 *
 * @ORM\Table(name="user_ip_capture" )
 * @ORM\Entity
 */

class UserIpCapture 
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
     * @ORM\Column(name="user_id", type="integer")
     * @var integer
     */
    protected $userId;
    
    /**
     * @ORM\Column(name="ip_based_country", type="string")
     * @var string
     */
    protected $ipBasedCountry;

    
    /**
     * @ORM\Column(name="ip_based_state", type="string")
     * @var string
     */
    protected $ipBasedState;

    /**
     * @ORM\Column(name="ip_based_city", type="string")
     * @var string
     */
    protected $ipBasedCity;

        
    /**
     * @ORM\Column(name="changed_location_country", type="string")
     * @var string
     */
    protected $changedLocationCountry;

    
    /**
     * @ORM\Column(name="changed_location_state", type="string")
     * @var string
     */
    protected $changedLocationState;

    /**
     * @ORM\Column(name="changed_location_city", type="string")
     * @var string
     */
    protected $changedLocationCity;
    
    /**
     * @ORM\Column(name="change_flag", type="integer")
     * @var integer
     */
    protected $changeFlag;

    
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

    
    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    
    public function getIpBasedCountry()
    {
        return $this->ipBasedCountry;
    }

    
    public function setIpBasedCountry($ipBasedCountry)
    {
        $this->ipBasedCountry = $ipBasedCountry;
        return $this;
    }
    
    
    public function getIpBasedState()
    {
        return $this->ipBasedState;
    }

    
    public function setIpBasedState($ipBasedState)
    {
        $this->ipBasedState = $ipBasedState;
        return $this;
    }

    public function getIpBasedCity()
    {
        return $this->ipBasedCity;
    }

    public function setIpBasedCity($ipBasedCity)
    {
        $this->ipBasedCity = $ipBasedCity;
        return $this;
    }
    
    
    public function getChangedLocationCountry()
    {
        return $this->changedLocationCountry;
    }

    public function setChangedLocationCountry($changedLocationCountry)
    {
        $this->changedLocationCountry = $changedLocationCountry;
        return $this;
    }
    
    public function getChangedLocationState()
    {
        return $this->changedLocationState;
    }

    public function setChangedLocationState($changedLocationState)
    {
        $this->changedLocationState = $changedLocationState;
        return $this;
    }
    
    
    public function getChangedLocationCity()
    {
        return $this->changedLocationCity;
    }

    public function setChangedLocationCity($changedLocationCity)
    {
        $this->changedLocationCity = $changedLocationCity;
        return $this;
    }
    
    
    public function getChangeFlag()
    {
        return $this->changeFlag;
    }

    public function setChangeFlag($changeFlag)
    {
        $this->changeFlag = $changeFlag;
        return $this;
    }
    
}
