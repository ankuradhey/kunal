<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="t_state")
 * @ORM\Entity
 */
class State {

    /**
     * @var integer
     *
     * @ORM\Column(name="state_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $stateId;

    /**
     * @var string
     *
     * @ORM\Column(name="state_name", type="string", length=255)

     */
    private $stateName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="country_id",  type="integer", length=20)

     */
   private $country_id;
    /**
     * @var \Common\Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="Common\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="country_id")
     * })
     */
    private $country;

    public function setCountry($country) {
        $this->country = $country;
    }

    public function getCountry() {
        return $this->country;
    }

    public function setStateName($stateName) {
        $this->stateName = $stateName;
    }

    public function getStateName() {
        return $this->stateName;
    }

    public function setStateId($stateId) {
        $this->stateId = $stateId;
    }

    public function getStateId() {
        return $this->stateId;
    }
    
   public function setcountryId(){
      $this->countryId = $countryId;
   }
  
  public function getcountryId(){
      $this->countryId;
  }

}
