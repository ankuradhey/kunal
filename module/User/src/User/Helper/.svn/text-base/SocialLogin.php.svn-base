<?php

namespace User\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

class SocialLogin extends AbstractHelper {

    /**
     * @var AuthenticationService
     */
    protected $authService;
    protected $containerService;
    protected $objauth;

    /**
     * __invoke
     *
     * @access public
     * @param \ZfcUser\Entity\UserInterface $user
     * @throws \ZfcUser\Exception\DomainException
     * @return String
     */
    public function __invoke() {
        
        $socialProvider = array();
        if ($this->objauth->hasIdentity()) {
            $loggedIUserObj = $this->objauth->getIdentity();
            $userId = $loggedIUserObj->getId();
            $scnauthMapperObj = $this->containerService; 
            $socialProvider = $scnauthMapperObj->findProvidersByUser($loggedIUserObj);
            return $socialProvider;
        }
        
    }

    public function setWebsiteService($containerService, $objauth) {
        $this->containerService = $containerService;
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