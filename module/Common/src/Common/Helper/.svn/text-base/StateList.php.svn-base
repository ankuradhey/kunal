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
class StateList extends AbstractHelper {

    protected $containerService = null;
    public $clist = array();
    public $status;

    /**
     * @return the $containerService
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

    public function __invoke($pr) {
        $this->status=$pr;
//            $containerService->setCommonMapper($serviceManager->get('com_mapper'));
           $this->setStateList($this->containerService->getStateList($country_id));
//                                                        echo '<pre>';var_dump($countryList->clist); echo '</pre>';die('macro Die');
//        echo '<pre>';print_r($this->getCountryList()); echo '</pre>';die('macro Die');
        return $this->getStateList();
    }

    public function getStateList() {
        return $this->clist;
    }
    
    public function setStateList($clist) {
        $this->clist=$clist;
        return $this;
    }

}
