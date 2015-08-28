<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
/** @ORM\Entity 
 *  @ORM\Table(name="t_country")
 * 
 * 
 * */
class Country
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @ORM\Column(name="country_id")
     */
    protected $countryId;
    /** @ORM\Column(type="string") 
     *  @ORM\Column(name="country_name")
     * */
    protected   $name;

    /** @ORM\Column(type="string") 
     * @ORM\Column(name="phonecode")
     * */
    protected $phoneCode;

    /** @ORM\Column(type="string") 
     *  @ORM\Column(name="numcode")
     * 
     * */
    protected $numCode;

    /** @ORM\Column(type="string") */
    protected $iso;
    /** @ORM\Column(type="string") */
    protected $iso3;
    /** @ORM\Column(type="integer") */
    protected $status;
 
    function setName($name){
    	$this->name=$name;
        return $this;
    }
    function getName(){return $this->name;}
    function countryList(){
    	$this->fetchAll();
    }
    
}

?>