<?php

namespace Notification\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

class TickerWidget extends AbstractHelper {

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
    public function __invoke($tickertype = '') {
        $view = new ViewModel();
        $tickerss = array();
        //echo $tickertype;die;
        $tickers = $this->tableGateway->getAllActiveTicker($tickertype);
         $countdata = count($tickers);
        $view = $this->getView()->render("notification/index/tickers.phtml", array('tickers' => $tickers,'countdata'=>$countdata)); //Default template is leftsubjectlist.phtml    
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
