<?php

namespace Common\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator;
use ZfcBase\EventManager\EventProvider;
use Common\Mapper\CommonMapper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Zend\Session\Container as BoardSession;

class Cservice extends EventProvider implements ServiceManagerAwareInterface {

    protected $serviceManager;

    /**
     * @var LmsMapper
     */
    protected $commMapper = NULL;
    public $countryList;

    function __construct() {

//        $this->countryList=new BoardSession("boards");
    }

    public function getServiceManager() {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
        return $this;
    }

/**
     * 
     * @param string $format
     * return orphan List of Conatiner 
     */

    function getCountryList($status) {
//        echo '<pre>';var_dump($this->getCommonMapper()->getAllCountries()); echo '</pre>';die('macro Die');
        return $this->countryList = $this->getCommonMapper()->getAllCountries($status);
    }

//    function isOprhan($conatinerId){  
//        if($this->getLmsMapper()->getParent($conatinerId)->getRackId()){ return false;}else{ return true;}
//        
//    }

    /**
     * getLmsMapper
     *
     * @return LmsMapper
     */
    public function getCommonMapper() {
        if (null === $this->commMapper) {

            $this->setCommonMapper($this->getServiceManager()->get('com_mapper'));
        }
//        echo '<pre>';print_r($this->commMapper); echo '</pre>';die('macro Die');
        return $this->commMapper;
    }

    /**
     * setUserMapper
     *
     * @param UserMapperInterface $userMapper
     * @return User
     */
    public function setCommonMapper(CommonMapper $commonMapper) {
        $this->commMapper = $commonMapper;
        return $this;
    }

    
    
    
     /**
     * 
     *  Author: Pradeep Kumar
     *  Description: To Get the statelist from the country Id 
     *  setUserMapper
     *
     * @param UserMapperInterface $userMapper
     * @return User
     */
    public function getCountarybystate($country_id) {
        return  $this->getCommonMapper()->getCountarybystate($country_id);
    }


 
}