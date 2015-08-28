<?php

/**
 * Lms\Helper
 * 
 * @author
 * @version 
 */

namespace Common\Helper;

use Zend\View\Helper\AbstractHelper;

class Fileversion extends AbstractHelper {

    protected $containerService = null;
    public $configdata = null;
    public $status;
      
    public function __invoke($url) 
    {
       $filepath = explode("module", __FILE__);
       $filepath = $filepath[0]."public"; 
       $configdata = $this->configdata;
       $path     = pathinfo($url);
 
       $ver      = $configdata['v1'];
       return $path['dirname'].'/'.$ver.'/'.$path['basename'];

    } 
    
    /**
     * @param Container $containerService
     */
     public function getContainerService() {
        return $this->containerService;
     }

    /**
     * @param Container $containerService
     */
    public function setContainerService($containerService) {
        $this->containerService = $containerService;
    }
    
  
    /**
     * @param Container $containerService
     */
    public function setConfigservice($configdata) {
        $this->configdata = $configdata;
    }
  
    
}