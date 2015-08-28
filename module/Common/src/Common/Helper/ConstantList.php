<?php

/**
 * Lms\Helper
 * 
 * @author
 * @version 
 */

namespace Common\Helper;

use Zend\View\Helper\AbstractHelper;

//use Lms\Service\Container;

/**
 * View Helper
 */
class ConstantList extends AbstractHelper {

    protected $service = null;
    protected $constant;
    
    /**
     * @return the $service
     */
    public function getService() {
        return $this->service;
    }

    /**
     * @param Container $service
     */
    public function setService($service) {
        $this->service = $service;
    }

    public function __invoke() {
       
    	$config = $this->service->get('Config');
    	$this->setConstantList($config['constant']);
                                                      
        return $this->getConstantList();
    }

    public function getConstantList() {
        return $this->constant;
    }
    
    public function setConstantList($constant) {
        $this->constant=$constant;
        return $this;
    }

}
