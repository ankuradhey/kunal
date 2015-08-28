<?php

namespace Notification\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

class NotificationWidget extends AbstractHelper {

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
    public function __invoke($notificationarray = array()) {
        $view = new ViewModel();
        $newnotification = array();
        if ($this->objauth->hasIdentity()) {
            $loggedIUserObj = $this->objauth->getIdentity();

            $userId = $loggedIUserObj->getId();
             
            if ($userId != '') {
                $newnotification = $this->tableGateway->getnotification($userId);
            }
        }
        $view = $this->getView()->render("notification/index/notification.phtml", array('notificationarray' => $notificationarray, 'newnotification' => $newnotification)); //Default template is leftsubjectlist.phtml    
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
