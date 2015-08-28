<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="city_data")
 * @ORM\Entity
 */
class CityData {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=100)

     */
    private $state;
    
    /**
     * @var string
     *
     * @ORM\Column(name="city",  type="string", length=100)

     */
   private $city;


    public function setState($state) {
        $this->state = $state;
    }

    public function getState() {
        return $this->state;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function getCity() {
        return $this->city;
    }
    
   public function setId($id){
      $this->id = $id;
   }
  
  public function getId(){
      $this->id;
  }

}
