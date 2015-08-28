<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

class NavLoginWidget extends AbstractHelper {

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
    public function __invoke($notificationarray = array(),$layout='application',$newDesign=false) {
        $view = new ViewModel();
        $newnotification = array();
        if($newDesign){
            $view = $this->getView()->render("$layout/index/navlogin_v1.phtml", array('userobj' => $this->objauth));
        }else{
            $view = $this->getView()->render("$layout/index/navlogin.phtml", array('userobj' => $this->objauth)); //Default template is leftsubjectlist.phtml    
        }
        
        return $view;
    }

    public function setTableGateway($tableGateway) {
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
