<?php

namespace User\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class ProfilePopupControl extends AbstractHelper {

    /**
     * @var AuthenticationService
     */
    protected $authService;
    protected $containerService;
    protected $objauth;
    protected $userOtherDetail;
    protected $transtiondetail;

    /**
     * __invoke
     *
     * @access public
     * @param \ZfcUser\Entity\UserInterface $user
     * @throws \ZfcUser\Exception\DomainException
     * @return String
     */
    public function __invoke() {
        
     if ($this->objauth->hasIdentity()) {
            $loggedIUserObj      = $this->objauth->getIdentity();
            $userId              = $loggedIUserObj->getId();
            $userschool_id       = $loggedIUserObj->getSchoolId();
            $OtherboardTable     = $this->containerService;
            $passwordKey         = "passwordUpdate";
            $checkPasswordupdate = $OtherboardTable->getUserOtherDetailsByKey($userId , $passwordKey,$status=1)->toArray();
            $profilekey          = "profileUpdate";
            $checkProfileupdate  = $OtherboardTable->getUserOtherDetailsByKey($userId , $profilekey,$status=1)->toArray();
            $schooldetail        = $this->tableUser->checkuserchooldetail($userId,$userschool_id)->current();
            $checkEmuser         = $this->getemstudent->checkAlreadyExist($userId); 
            $checkEmpackage= $this->transacttable->getTransactionDetailsByUserId($userId);
            if(empty($checkEmpackage)){
              $checkEmpackage      = $this->getemstudent->fetchuserdetail($userId); 
            }
            $userLoginOtherDetail = $this->userotherdetail->getUserOtherDetailsByKey($userId , 'mobile_login', $status=NULL);
            $mobileLoginFlag = 1;
            foreach($userLoginOtherDetail as $loginDetail) {
                $value = $loginDetail->value;
                if($value == '0') {
                    $mobileLoginFlag=0;
                }
            }
            $userOtherDetail = $this->userotherdetail->getUserOtherDetailsByKey($userId , 'register_by', $status=NULL);
            $regterByMobileFlag = 0;
            foreach($userOtherDetail as $loginDetail) {
                $value = $loginDetail->value;
                if($value == 'mobile') {
                    $regterByMobileFlag=1;
                }
            }
            $user_session = new Container('user');
            $login_by = $user_session->login_by;
            if($login_by=='email') {
                $mobileLoginFlag=1;
            }
           $view = $this->getView()->render('user/index/updateprofilepopup.phtml', array('user' => $loggedIUserObj, 'passwordupdate' => $checkPasswordupdate,'Profileupdate'=>$checkProfileupdate,"schooldetail"=>$schooldetail,"checkEmuser"=>$checkEmuser,"checkEmpackage"=>$checkEmpackage,'mobileLoginFlag'=>$mobileLoginFlag,'regterByMobileFlag'=>$regterByMobileFlag)); //Default template is leftsubjectlist.phtml    
           return $view;
        }
        
    }


    public function setWebsiteService($containerService,$tableUser,$getemstudent,$transtiondetail,$userOtherDetail) {
        $this->containerService = $containerService;
        $this->tableUser        = $tableUser;
        $this->getemstudent     = $getemstudent;
        $this->transtiondetail  = $transtiondetail;
        $this->userotherdetail  = $userOtherDetail;
        $this->transacttable = $transtiondetail;
        
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