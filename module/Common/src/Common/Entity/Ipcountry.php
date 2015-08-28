<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="ipcountry")
 * @ORM\Entity
 */
class Ipcountry {
     
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var integer
     *
    * @ORM\Column(name="ipFrom", type="integer", length=10)
     
     */
    private $ipFrom;
    /**
     * @var integer
     *
    * @ORM\Column(name="ipTo", type="integer", length=10)
     
     */
    private $ipTo;
    
    
    /**
     * @var string
     *
    * @ORM\Column(name="countrySHORT", type="string", length=2)
     
     */
    private $countrySHORT;
    
    /**
     * @var string
     *
    * @ORM\Column(name="countryLONG", type="string", length=255)
     
     */
    private $countryLONG;
    
    /**
     * @var string
     *
    * @ORM\Column(name="state", type="string", length=255)
     
     */
    private $state;
    
    /**
     * @var string
     *
    * @ORM\Column(name="city", type="string", length=255)
     
     */
    private $city;
    
    
    public function setId($id) {
            $this->id = $id;
    }
    public function getId() {
            return $this->id;
    }
    
    public function setIpFrom($ipFrom) {
            $this->ipFrom = $ipFrom;
    }
    public function getIpFrom() {
            return $this->ipFrom;
    }
    public function getIpTo() {
           return  $this->ipTo;
    }
    public function setIpTo($ipTo) {
            $this->ipTo = $ipTo;
    }
    
    public function setCountryCode($countryCode) {
            $this->countrySHORT = $countryCode;
    }
    public function getCountryCode() {
           return  $this->countrySHORT;
    }
    public function setCountryName($countryName) {
            $this->countryLONG = $countryName;
    }
    public function getCountryName() {
           return  $this->countryLONG;
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
    

}
