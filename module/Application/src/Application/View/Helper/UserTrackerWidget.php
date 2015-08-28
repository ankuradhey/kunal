<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

class UserTrackerWidget extends AbstractHelper {

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
//         $current_url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];     
         $current_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];  
         $data['user_id'] = $this->objauth;
         $data['session'] = session_id();
         $data['page_url'] = $current_url;
         $this->tableGateway->insertUserTracker($data); 
    }

    public function setTableGateway($tableGateway, $objauth) {
        $this->tableGateway = $tableGateway;
        $this->objauth      = $objauth;
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
