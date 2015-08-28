<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="t_country")
 * @ORM\Entity
 */
class Country {
    /**
     * @var integer
     *
     * @ORM\Column(name="country_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $countryId;
    
    /**
     * @var string
     *
    * @ORM\Column(name="country_name", type="string", length=255)
     
     */
    private $countryName;
    
    
    /**
     * @var string
     *
    * @ORM\Column(name="iso", type="string", length=2)
     
     */
    private $iso;
    
    /**
     * @var string
     *
    * @ORM\Column(name="nicename", type="string", length=80)
     
     */
    private $nicename;
    
    /**
     * @var string
     *
    * @ORM\Column(name="iso3", type="string", length=2)
     
     */
    private $isoThree;
    
    /**
     * @var integer
     *
    * @ORM\Column(name="numcode", type="integer", length=3)
     
     */
    private $numcode;
    
    /**
     * @var integer
     *
    * @ORM\Column(name="phonecode", type="integer", length=5)
     
     */
    private $phonecode;
    
    /**
     * @var integer
     *
    * @ORM\Column(name="status", type="integer")
     
     */
    
    private $status;

public function setCountryId($id) {
            $this->countryId = $id;
    }
    public function getCountryId() {
            return $this->countryId;
    }
    public function getStatus() {
           return  $this->status;
    }
    public function setStatus($status) {
            $this->status = $status;
    }
    
    public function setCountryName($countryName) {
            $this->status = $status;
    }
    public function getCountryName() {
           return  $this->countryName;
    }
    public function setIso($iso) {
            $this->iso = $iso;
    }
    public function getIso() {
           return  $this->iso;
    }
    public function setNicename($nicename) {
            $this->nicename = $nicename;
    }
    public function getNicename() {
           return  $this->nicename;
    }
    public function setIsoThree($isoThree) {
            $this->isoThree = $isoThree;
    }
    public function getIsoThree() {
           return  $this->isoThree;
    }
    public function setNumcode($numcode) {
            $this->numcode = $numcode;
    }
    public function getNumcode() {
           return  $this->numcode;
    }
    
    public function setPhonecode($phonecode) {
            $this->phonecode = $phonecode;
    }
    public function getPhonecode() {
           return  $this->phonecode;
    }

}
