<?php

namespace Notification\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

class MetaTagsWidget extends AbstractHelper {

    /**
     * @var AuthenticationService
     */
    protected $authService;
    protected $tableGateway;
    protected $objauth;

    /**
     * __invoke
     *
     * @access public
     * @param \ZfcUser\Entity\UserInterface $user
     * @throws \ZfcUser\Exception\DomainException
     * @return String
     */
    public function __invoke($metatagsarray = array()) {
        $view = new ViewModel();        
                
          $current_url = $_SERVER["REQUEST_URI"];      
          $current_url = str_replace('%20',' ', $current_url);
          if (strpos($current_url,'?') !== false) {
                $urlStringArray = explode('?',$current_url);
                $current_url = $urlStringArray[0];
            }
            
          if($current_url!=''){
          $meta_data = $this->tableGateway->GetMetaTagByUrl($current_url); 
          $metadata = $meta_data->current();
           }else{
              $meta_data = $this->tableGateway->GetMetaTagByUrl('');
              $metadata = $meta_data->current();
           }      
           //echo '<pre>';print_r($meta_data);echo '</pre>';die('Macro Die'); 
            if(empty($metadata)){              
                $meta_data = $this->tableGateway->GetMetaTagByUrl('/default');
                $metadata = $meta_data->current();
              } 
                  
          $view = $this->getView()->render("notification/index/metags.phtml", array('metatagsarray' => $metatagsarray, 'metadata' => $metadata));
          
        return $view;
    }

    public function setTableGateway($tableGateway, $objauth) {
        $this->tableGateway = $tableGateway;
        //$this->objauth      = $objauth;
    }

    public function setAuthServices($objauth) {

        $this->objauth = $objauth;
    }

    /**
     * Get authService.
     *
     * @return AuthenticationService
     */
    public function getAuthService() {
        return $this->authService;
    }

    /**
     * Set authService.
     *
     * @param AuthenticationService $authService
     * @return \ZfcUser\View\Helper\ZfcUserDisplayName
     */
    public function setAuthService(AuthenticationService $authService) {
        $this->authService = $authService;
        return $this;
    }

}
