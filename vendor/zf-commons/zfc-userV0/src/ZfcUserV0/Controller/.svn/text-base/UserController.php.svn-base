<?php

namespace ZfcUserV0\Controller;

use Zend\Form\Form;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;
use ZfcUser\Service\User as UserService;
use ZfcUser\Options\UserControllerOptionsInterface;
use ZfcUser\Mapper\UserLogsInterface as UserLogsMapperInterface;
use Common\Mapper\CommonMapper as CommonMapper;
use ScnSocialAuth\Mapper\UserProvider as UserProvider;
use Zend\View\Model\JsonModel;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Form\Element;

class UserController extends AbstractActionController {
    const ROUTE_CHANGEPASSWD = 'zfcuser/changepassword';
    const ROUTE_LOGIN = 'zfcuser/login';
    const ROUTE_REGISTER = 'zfcuser/register';
    const ROUTE_CHANGEEMAIL = 'zfcuser/changeemail';

    const CONTROLLER_NAME = 'zfcuser';

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var Form
     */
    protected $loginForm;

    /**
     * @var Form
     */
    protected $registerForm;

    /**
     * @var Form
     */
    protected $changePasswordForm;

    /**
     * @var Form
     */
    protected $changeEmailForm;
    protected $profileForm;

    /**
     * @todo Make this dynamic / translation-friendly
     * @var string
     */
    protected $failedLoginMessage = 'Authentication failed. Please try again.';

    /**
     * @var UserControllerOptionsInterface
     */
    protected $options;
    
    protected $service;
    
    public function getService() {
        if (!$this->service) {
            $this->service = $this->getServiceLocator()->get('lms_container_service');
        }
        return $this->service;
    }
    
    /**
     * User page
     */
    public function indexAction() {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }
        $userTypeId = $this->zfcUserAuthentication()->getIdentity()->getUserTypeId();
        if($userTypeId == 10){ // admin redirect
            return $this->redirect()->toRoute('admin/misreport');
        }
        else if($userTypeId == 1){
            return $this->redirect()->toRoute('zfcuser/user-dashboard');
        }else{
            return $this->redirect()->toRoute('zfcuser/myprofile');
        }
        //return new ViewModel();
    }

    /**
     * Login form
     * Author: Pradeep Kumar
     * Description:: Login API for mobile webservice
     */
    public function loginapiAction() {
        //if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
       //   return $this->redirect()->toRoute('home');
        //}
        $session_user = new Container('maskedEmailSession');
        $session_user->getManager()->getStorage()->clear('maskedEmailSession');

        $request = $this->getRequest();
        $form = $this->getLoginForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
            $redirect = strip_tags($redirect);
            $redirect = htmlspecialchars($redirect, ENT_QUOTES);
//            $redirect = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $redirect);
        } else {
            $redirect = false;
        }

        $redirect= preg_replace('/alert()/', '', $redirect);
        $maskEmail = $request->getPost()->identity;
        $maskEmailArr = explode('@',$maskEmail);

        if(isset($maskEmailArr[1])){

            if($maskEmailArr[1] == 'extramarkslive.com'){
                $maskedEmailObj = $this->getServiceLocator()->get("admin_mapper");
                $dataMasked = $maskedEmailObj->validateMaskedEmailPasswordExists($maskEmail,$request->getPost()->credential);
                if(count($dataMasked) > 0){
                    $maskedEmailSession = new Container('maskedEmailSession');
                    $maskedEmailSession->maskedEmail = $maskEmail;
                    echo json_encode( array ("output" => "Masked" , "email" => $maskEmail));
                }else{
                    echo json_encode( array ("output" => "Failed" ));
                }
                exit;
            }
        }

        // masking email ends here
        // Admin Login Block
        if($redirect == 'admin'){
            $adminEmail = $request->getPost()->identity;
            $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");

            $emailData = $zfcuserMapperObj->findByEmail($adminEmail);
            if(is_object($emailData)){
                $adminMapperObj = $this->getServiceLocator()->get("admin_mapper");
                $checkAdminData = $adminMapperObj->checkAdminUserEmail($emailData->getId());
                if(is_object($checkAdminData)){
                    // admin session
                    $sessionAdmin = new Container('admin_user_session');
                    $sessionAdmin->userId = $emailData->getId();
                }else{
                    echo json_encode( array ("output" => "Failed"));
                    exit;
                }
            }else{
                echo json_encode( array ("output" => "Failed"));
                exit;
            }

        }
        // Admin Login Block Ends
        //echo '<pre>';var_dump ($request->isPost());echo '</pre>';die('vikash');
        if (!$request->isPost()) {
            return array(
                'loginForm' => $form,
                'redirect' => $redirect,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
            );
        }

        if ($this->zfcUserAuthentication()->hasIdentity()) {

            $form->setData($request->getPost());

            if (!$form->isValid()) {
                $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo json_encode( array ("output" => "Failed"));
                    exit;
                }

                return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN) . ($redirect ? '?redirect=' . rawurlencode($redirect) : ''));
            }

            $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
            $returnVal = $this->sessionCheckForUser($userId);

            if($returnVal){
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo json_encode( array ("loginuserexists" => "1" , "user_id" => $userId ,"userssession_id" => $returnVal ) );
                    exit;
                }
                return array('loginForm' => $form, "previous_login_session" => true, "userssession_id" => $returnVal, "user_id" => $userId);
            }
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode( array ("output" => "success","redirect" => $redirect,"user_id" => $userId ));
                exit;
            }
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $request = $this->getRequest();
        $form = $this->getLoginForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
             $redirect = strip_tags($redirect);
             $redirect = htmlspecialchars($redirect, ENT_QUOTES);
        } else {
            $redirect = false;
        }

        if (!$request->isPost()) {
            return array(
                'loginForm' => $form,
                'redirect' => $redirect,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
            );
        }

        $form->setData($request->getPost());



        if (!$form->isValid()) {

            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode( array ("output" => "Failed") );
                exit;
            }
            return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN) . ($redirect ? '?redirect=' . rawurlencode($redirect) : ''));
        }

        // clear adapters
        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

        return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
    }






    /**
     * Login form
     */
    public function loginAction() {
        if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
          return $this->redirect()->toRoute('home');
        }
        
        $session_user = new Container('maskedEmailSession');
        $session_user->getManager()->getStorage()->clear('maskedEmailSession');
         
        $request = $this->getRequest();
        $form = $this->getLoginForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
            $redirect = strip_tags($redirect);
            $redirect = htmlspecialchars($redirect, ENT_QUOTES);
//            $redirect = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $redirect);
        } else {
            $redirect = false;
        }
        
        $redirect= preg_replace('/alert()/', '', $redirect);
        $maskEmail = $request->getPost()->identity;
        $maskEmailArr = explode('@',$maskEmail);
       
        if(isset($maskEmailArr[1])){
            
            if($maskEmailArr[1] == 'extramarkslive.com'){
                $maskedEmailObj = $this->getServiceLocator()->get("admin_mapper");
                $dataMasked = $maskedEmailObj->validateMaskedEmailPasswordExists($maskEmail,$request->getPost()->credential);
                if(count($dataMasked) > 0){
                    $maskedEmailSession = new Container('maskedEmailSession');
                    $maskedEmailSession->maskedEmail = $maskEmail;
                    echo json_encode( array ("output" => "Masked" , "email" => $maskEmail));
                }else{
                    echo json_encode( array ("output" => "Failed" ));
                }
                exit;
            }
        }
        
        // masking email ends here
        // Admin Login Block
        if($redirect == 'admin'){
            $adminEmail = $request->getPost()->identity;
            $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");
            
            $emailData = $zfcuserMapperObj->findByEmail($adminEmail);
            if(is_object($emailData)){
                $adminMapperObj = $this->getServiceLocator()->get("admin_mapper");      
                $checkAdminData = $adminMapperObj->checkAdminUserEmail($emailData->getId());
                if(is_object($checkAdminData)){
                    // admin session
                    $sessionAdmin = new Container('admin_user_session');
                    $sessionAdmin->userId = $emailData->getId();  
                }else{
                    echo json_encode( array ("output" => "Failed"));
                    exit;
                }
            }else{
                echo json_encode( array ("output" => "Failed"));
                exit;
            }
            
        }
        // Admin Login Block Ends
        //echo '<pre>';var_dump ($request->isPost());echo '</pre>';die('vikash');
        if (!$request->isPost()) {
            return array(
                'loginForm' => $form,
                'redirect' => $redirect,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
            );
        }
        
        if ($this->zfcUserAuthentication()->hasIdentity()) {
             
            $form->setData($request->getPost());

            if (!$form->isValid()) {
                $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo json_encode( array ("output" => "Failed"));
                    exit;
                }
                
                return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN) . ($redirect ? '?redirect=' . rawurlencode($redirect) : ''));
            }
            
            $userId = $this->zfcUserAuthentication()->getIdentity()->getId();           
            $returnVal = $this->sessionCheckForUser($userId);
           
            if($returnVal){
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo json_encode( array ("loginuserexists" => "1" , "user_id" => $userId ,"userssession_id" => $returnVal ) );
                    exit;
                }
                return array('loginForm' => $form, "previous_login_session" => true, "userssession_id" => $returnVal, "user_id" => $userId);
            }
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode( array ("output" => "success","redirect" => $redirect,"user_id" => $userId ));
                exit;
            }
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        
        $request = $this->getRequest();
        $form = $this->getLoginForm();

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
             $redirect = strip_tags($redirect);
             $redirect = htmlspecialchars($redirect, ENT_QUOTES);
        } else {
            $redirect = false;
        }

        if (!$request->isPost()) {
            return array(
                'loginForm' => $form,
                'redirect' => $redirect,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
            );
        }
        
        $form->setData($request->getPost());



        if (!$form->isValid()) {
            
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode( array ("output" => "Failed") );
                exit;
            }
            return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN) . ($redirect ? '?redirect=' . rawurlencode($redirect) : ''));
        }
        
        $postData = $request->getPost();
        $identityId = $postData->identity;
        $this->storeLoginDetailInSession($identityId);
        //echo 'asdf asdf asdf asdf'; exit;
        // clear adapters
        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

        return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
    }
    
    function storeLoginDetailInSession($email){
        $userTable = $this->getServiceLocator()->get('Assessment\Model\UserTable');
        $userRow = $userTable->getUserbyemail($email);
        $_SESSION['login_detail'] = $userRow;
        $_SESSION['is_login'] = true;
    }

    public function previoussessionAction() {
        $user_id = $this->params()->fromPost('user_id');
        $usersession_id = $this->params()->fromPost('usersession_id');
        $satPackage = $this->params()->fromPost('satPackage');
        $capPackage = $this->params()->fromPost('capPackage');
        if($satPackage == ''){
            $satPackage = 0;
        }
        if($capPackage == ''){
          $capPackage = 0;
        }
        $request = $this->getRequest();
        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
            $redirect = $request->getQuery()->get('redirect');
        } else {
            $redirect = '';
        }
        $userLogObj = $this->getServiceLocator()->get("lms_container_mapper");
        $data = $userLogObj->updatePreviousSessions($user_id, $usersession_id);
        $userLogObj->addNewSessionUserLog($user_id, $usersession_id);
        //  $backurl = $this->url()->fromRoute(static::CONTROLLER_NAME.'/authenticate');
        //  echo json_encode( array ("backurl" => $backurl) );
        //  exit;

        echo json_encode(array("output" => "success" , "redirect" => $redirect, 'satPackage'=> $satPackage, 'capPackage' => $capPackage));
        exit;
    }
    
    public function checkuserlogstatusAction() {
       if($this->zfcUserAuthentication()->hasIdentity())
       {       
            $userid = $this->zfcUserAuthentication()->getIdentity()->getId();
            
            $userLogObj = $this->getServiceLocator()->get("lms_container_mapper");
            
            $dataRes = $userLogObj->checkIfToDoLogout($userid,$_COOKIE['PHPSESSID']);
            
            if($dataRes->getLoginStatus() == 'login'){
                echo json_encode(array("output" => "success" ));
                exit;
            }else{
                echo json_encode(array("output" => "failure" ));
                exit;
            }
        }
        echo json_encode(array("output" => "success"));
        exit;
    }
    
    public function maskedemaildata($maskedEmail) {
        $masked_email = $maskedEmail;
        
        $maskedObj = $this->getServiceLocator()->get("admin_mapper");
        $data = $maskedObj->checkMaskedEmailExists($masked_email);
        return $data;
        
    }
    
        
    /**
     * Logout and clear the identity
     */
    public function logoutAction() {
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $userId = $this->zfcUserAuthentication()->getAuthService()->getIdentity()->getId();
            $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
            $this->zfcUserAuthentication()->getAuthAdapter()->logoutAdapters();
            $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

            $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));

            $userLogObj = $this->getServiceLocator()->get("lms_container_mapper");
            $data = $userLogObj->updateSession($userId);
            if ($this->getOptions()->getUseRedirectParameterIfPresent() && $redirect) {
                return $this->redirect()->toUrl($redirect);
            }
            unset($_SESSION);
        }
        session_destroy();
        return $this->redirect()->toRoute('home');
        
//        return $this->redirect()->toRoute($this->getOptions()->getLogoutRedirectRoute());
    }
    
    
     public function notifyparentAction()
    { 
        (int)$type = $this->getRequest()->getPost('type');
        if($type = '1'){
        $parentId = $this->zfcUserAuthentication()->getIdentity()->getId();
        $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
         $notificationdata = array(
                    'notification_text' =>'&nbsp;Add child',
                    'userid' => $parentId,
                     'type_id' => '2',    // group
                    'notification_url' => 'myChild',
                     'created_by' => $parentId,
                         'created_date'  	=> date('Y-m-d H:i:s'),	
                  );
                    
        $res = $notificationtable->insertnotification($notificationdata);
        }else{
            $res = 0;
        }
        echo $res; die;
        
    }
    
    public function logoutspecialAction() {
        
        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthAdapter()->logoutAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

        $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $redirect) {
            return $this->redirect()->toUrl($redirect);
        }
        return $this->redirect()->toRoute('home');
    }

    public function fromstudyboardAction() {
        $request = $this->getRequest();
////    $data = $request->getPost();
//      echo '<pre>';print_r ($data['credential']);echo '</pre>';die('vikash');
//      $adapterService = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
//      echo '<pre>';print_r ($adapter);echo '</pre>';die('vikash');
        $result = $adapter->prepareForAuthentication($this->getRequest());
        
//      $adapter = new \Zend\Authentication\Adapter\DbTable($adapterService,'user','email','password');
        $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);
        die;
//      echo "success";
//      echo json_encode(array("output" => "success"));
//      exit;
//      echo '<pre>';print_r ($result->isvasid());echo '</pre>';die('vikash');
    }

    /**
     * General-purpose authentication action
     */
    public function authenticateAction() {
        $userLogObj = $this->getServiceLocator()->get("lms_container_mapper");
        $reuestQueryData = $this->getRequest()->getQuery();

        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
            $userLogObj->addUserLog($userId);
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
        $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));

        $result = $adapter->prepareForAuthentication($this->getRequest());
        if($result == 'new user' && $result !== true){
            return $this->redirect()->toUrl($this->url()->fromRoute('zfcuser/registersocial'));
            
        }
        
        // Return early if an adapter returned a response
        if ($result instanceof Response) {
            return $result;
        }

        $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);
        
        if (!$auth->isValid()) {
            $this->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage($this->failedLoginMessage);
            $adapter->resetAdapters();
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode( array ("output" => "Failed") );
                exit;
            }
            return $this->redirect()->toUrl(
                            $this->url()->fromRoute(static::ROUTE_LOGIN) .
                            ($redirect ? '?redirect=' . rawurlencode($redirect) : '')
            );
        }
        
        //getting country code from ip address
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        
        // fetching country/state/city based on IP
        $tableIpCountry = $this->getServiceLocator()->get('Assessment\Model\IpCountryTable');
        $ipResultSet = $tableIpCountry->ipRange($ip_address);
        // entry in ip_country_code table
        $ipCountryObj = $this->getServiceLocator()->get("com_mapper");
        $ipCountryObj->ipcheckfunction($ipResultSet,$ip_address);
        // ends here
        
        $_SESSION['user_from_zf2'] = $auth->getIdentity();
        $userId = $auth->getIdentity();
        
        /**
         * @Ashutosh
         * to sat redirection if user have only sat package
         */
        
        $userLogCount = $userLogObj->getAllRecordCountUserLogs($userId);
        $satPackage = 0;
        $capPackage = 0;
        if($userLogCount>1){
            $userTrackerObj = $this->getServiceLocator()->get('Assessment\Model\UserTrackerTable');
            $trackerData = $userTrackerObj->getTrackerById($userId);

            $capPackageobj  = $this->getServiceLocator()->get('Psychometric\Model\StudyUserPackageFactory');
            $userpackagestable = $this->getServiceLocator()->get('Package\Model\TuserpackageTable');
            $userpackages = $userpackagestable->getPackagesubjects($userId);
            $containerObjects = $this->getServiceLocator()->get('Psychometric\Model\StudyUserPackageFactory');
            $capPackages = $capPackageobj->getUserPackage($userId,'','','','','','1');
            
            if($userId){
                $satPackages = $containerObjects->getUserPackage($userId,'','','','','','2');
                if(count($satPackages) && count($capPackages) < 1){
                  if(!$userpackages->count()){                            
                    $satPackage = count($satPackages);
                  }
                }else if(count($capPackages) && count($satPackages) < 1){
                  if(!$userpackages->count()){                            
                      $capPackage = count($capPackages);
                  }
                }else if(sizeof($trackerData)){
                  foreach($trackerData as $data){
                    if(strpos($data->page_url,'career-assessment-program')){
                      $capPackage = 1;
                    }else if(strpos($data->page_url,'cap')){
                      $capPackage = 1;
                    }
                  }
              }
            }
        }
        
        /*--------------------------End----------------------*/
        $returnVal = $this->sessionCheckForUser($auth->getIdentity());
        if($returnVal){
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode( array ("loginuserexists" => "1" , "user_id" => $auth->getIdentity() ,"userssession_id" => $returnVal, 'satPackage' => $satPackage, 'capPackage' => $capPackage ) );
                exit;
            }
            // return array('loginForm' => $form, "previous_login_session" => true, "userssession_id" => $returnVal, "user_id" => $userId);
        }
        $userLogObj->addUserLog($auth->getIdentity());
        $usertypeId = $this->zfcUserAuthentication()->getIdentity()->getUserTypeId();
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
           // echo "axt====>".$redirect;exit;
            echo json_encode( array ("output" => "success",'usertypeId'=>$usertypeId,"redirect" => $redirect, 'satPackage' => $satPackage, 'capPackage' => $capPackage ));
            exit;
        }
        if($auth->getCode() == '1'){
            // social logins redirect to myprofile section
            
            $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");
            $userObj = $zfcuserMapperObj->findById($auth->getIdentity());
            if($userObj->getMobile() != NULL){
                
                if($userLogCount<=1)
                    return $this->redirect()->toRoute('zfcuser/user-dashboard', array('controller' => 'zfcuser', 'action' => 'user-dashboard', 'id' => $userObj->getUserTypeId(), 'welcome' => 'welcome' ));
                else
                    return $this->redirect()->toRoute('zfcuser/user-dashboard', array('controller' => 'zfcuser', 'action' => 'user-dashboard', 'id' => $userObj->getUserTypeId() ));
            }else{
                return $this->redirect()->toRoute('zfcuser/myprofile');
            }
        }
        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $redirect) {
            return $this->redirect()->toUrl($redirect);
        }
        
        return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
    }
    
    public function sessionCheckForUser($userId) {
        $userLogObj = $this->getServiceLocator()->get("lms_container_mapper");
        $data = $userLogObj->checkSessionExists($userId);
        if (count($data)) {
            $userSessionId = $data->getUserSessionid();

            return $userSessionId;
        }
        return false;
    }

    private function southAfricaDomainCheck(){
        $manager  = $this->getServiceLocator()->get('ModuleManager');
        $modules  = $manager->getLoadedModules();
        $loadedModules      = array_keys($modules);
        $southafricaLoaded = in_array('Southafrica',$loadedModules);
        return $southafricaLoaded;
    }
    
    /**
     * Register new user
     */
    public function registerAction() {
        
        $southafricaLoaded = $this->southAfricaDomainCheck();
        if($southafricaLoaded === true)
            $selectBoardString = 'Select Curriculum';
        else
            $selectBoardString = 'Select Board';
        
        $session_user = new Container('maskedEmailSession');
        $session_user->getManager()->getStorage()->clear('maskedEmailSession');

        $redirect   = $this->params()->fromRoute('redirect');
        $redirect   = htmlspecialchars(strip_tags($redirect));
        if($redirect != ''){
            $redirect = '/'.$redirect;
        }else{
            $redirect = $redirect;
        }
        $urlRediretc = $this->params()->fromRoute('redirect');
        //echo '<pre>';print_r($role);die('macro die');
        $socialOptions = $this->getServiceLocator()->get('ScnSocialAuth-ModuleOptions');
        
        $boardList = $this->getService()->getBoardList();
        $valueOptions = array();
        $valueOptions[''] = $selectBoardString;
        foreach ($boardList as $container) {
            $valueOptions[$container->getRackId()] = $container->getRackName()->getName();
        }
        $comMapperObj = $this->getServiceLocator()->get("com_mapper");
        $countryData = $comMapperObj->getAllCountries();
        // if the user is logged in, we don't need to register
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        // if registration is disabled
        if (!$this->getOptions()->getEnableRegistration()) {
            return array('enableRegistration' => false);
        }

        $request = $this->getRequest();
        $service = $this->getUserService();
        $form = $this->getRegisterForm();
        $formInputFilter = $form->getInputFilter();
        $form->remove ( 'school_email' );
        // UAE validation (country phone code = 971 and country_id = 224 )
        $formPostData = $request->getPost();
        $dobMonth = $request->getPost('dob_month');
        $dobDay = $request->getPost('dob_day');
        $dobYear = $request->getPost('dob_year');
        if(!empty($dobMonth) && !empty($dobDay) && !empty($dobYear)) {
            if(strlen($dobMonth)==1) {
                $dobMonth = '0'.$dobMonth;
            }
            if(strlen($dobDay)==1) {
                $dobDay = '0'.$dobDay;
            }
            $dob = $dobDay.'-'.$dobMonth.'-'.$dobYear;
        } else {
            $dob = '';
        }
        
        /*if($request->isPost()) {
            $countryId = $formPostData->country_id;
            $mobile = $formPostData->mobile;
            $tablecountry = $this->getServiceLocator()->get('Assessment\Model\TcountryTable');
            $tablecountryphone = $this->getServiceLocator()->get('Assessment\Model\TcountryphoneTable');
            //$allCountriesPhoneDigits = $tablecountryphone->getAllCountryPhoneDetails();
            //echo '<pre>'; print_r($allCountriesPhoneDigits); exit;
            $countryRow = $tablecountry->getCountryDetailsByCountryId($countryId);
            $mobileDigits = strlen($mobile);
            
            if($mobileDigits!=12) {
                //$form->get('mobile')->setMessages(array('Please check your mobile number'));
                $form->get('mobile')->addError('Please check your mobile number');
                $form->markAsError();
            }
        }*/
//        echo '<pre>';print_r ($formPostData->board1);echo '</pre>';die('Vikash');
        // $_SESSION['register_class_array'] = array();
        if($formPostData->board1 != ''){
            $boardIdArray = explode("_",$formPostData->board1);
            $_SESSION['register_board_id'] = $formPostData->board1;
            $classList = $this->getService()->getchildList($boardIdArray[0]); 
            
            $_SESSION['other_board_session'] = $formPostData->otherboard;
            $_SESSION['register_class_array'] = array();
            foreach ($classList as $container) {
                $_SESSION['register_class_array'][$container->getRackId()] = $container->getRackName()->getName();
            }
        }
        if(isset($formPostData->class1)){
            $_SESSION['register_class_id'] = $formPostData->class1;
        }
        if($southafricaLoaded === true){
            $ipCaptureDetails['country'] = 'SOUTH AFRICA';
            $_SESSION['user_session_ip_country'] = $ipCaptureDetails['country'];
            $_SESSION['user_session_ip_state'] = 0;
            $_SESSION['user_session_ip_city'] = 0;
        }else{
            unset($_SESSION['user_session_ip_country']);
            unset($_SESSION['user_session_ip_state']);
            unset($_SESSION['user_session_ip_city']);
        }      
        if(!isset($_SESSION['user_session_ip_country']) && !isset($_SESSION['user_session_ip_state']) && !isset($_SESSION['user_session_ip_city']) ){
        
            //$ipCaptureDetails = $comMapperObj->useripcapturefunction();
            
            if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
                $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
                $ip_address = $_SERVER['REMOTE_ADDR'];
            }
            
            if($southafricaLoaded === true){
                $ipCaptureDetails['country'] = 'SOUTH AFRICA';
                $_SESSION['user_session_ip_country'] = $ipCaptureDetails['country'];
                $_SESSION['user_session_ip_state'] = 0;
                $_SESSION['user_session_ip_city'] = 0;
            }else{
                // fetching country/state/city based on IP
                $tableIpCountry = $this->getServiceLocator()->get('Assessment\Model\IpCountryTable');
                $ipResultSet = $tableIpCountry->ipRange($ip_address);
                $ipCaptureDetails = $comMapperObj->useripcapturefunction($ipResultSet,$ip_address);
            }
        
        
            if($ipCaptureDetails['country']){
                $countaryidDetails = $comMapperObj->getCountryIdByCountryName($ipCaptureDetails['country']);
                if(count($countaryidDetails) >0){
                    $countaryidDetailsNew = $countaryidDetails[0];
                    $_SESSION['statelist'] = $comMapperObj->getCountarybystate($countaryidDetailsNew->getCountryId());
                }
            }
        }
     
        $_SESSION['minCheck'] = (isset($_SESSION['minCheck']))?$_SESSION['minCheck']:'';   
        $_SESSION['maxCheck'] = (isset($_SESSION['maxCheck']))?$_SESSION['maxCheck']:'';   
        if(isset($formPostData->country_id)){
            if($formPostData->country_id == 224){
                $_SESSION['minCheck'] = 111111111;
                $_SESSION['maxCheck'] = 999999999;
                $_SESSION['register_country_id'] = 224;
            }else{
                $_SESSION['minCheck'] = 1111111111;
                $_SESSION['maxCheck'] = 9999999999;
                $_SESSION['register_country_id'] = $formPostData->country_id;
            }
        }
        // change filter
        //$formInputFilter->remove ( 'mobile' );
        $inputFactory = new \Zend\InputFilter\Factory();
        /*$formInputFilter->add ( $inputFactory->createInput ( array (
            'name' => 'mobile',
            'required' => false,
            'filters'  => array(
                array('name' => 'Int'),
            ),
            'validators' => array(
              array(
                  'name' => 'Between',
                  'options' => array(
                      'min' => $_SESSION['minCheck'],
                      'max' => $_SESSION['maxCheck'],
                      'messages' => array(
                         'notBetween' => 'Please enter a valid mobile number'
                      )
                  ),
              ),
            ),
        ) ) );*/
        
        
//        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
//            $redirect = $request->getQuery()->get('redirect');
//        } else {
//            $redirect = false;
//        }

//        $redirectUrl = $this->url()->fromRoute(static::ROUTE_REGISTER)
//                . ($redirect ? '?redirect=' . rawurlencode($redirect) : '');
        
        $redirectUrl = $this->url()->fromRoute(static::ROUTE_REGISTER)
                . ($redirect);
        $prg = $this->prg($redirectUrl, true);

        
        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
                'countryData' => $countryData,
                'boardList' => $valueOptions,
                'socialOptions' => $socialOptions,
                'southafricaLoaded' => $southafricaLoaded
            );
        }
//       $commonmapper =  $this->getServiceLocator()->get("com_mapper");
//       $prg          = $commonmapper->escaper($prg);
       $post         = $prg;
       $registration_plugin = $post['registration_plugin'];
        if(isset($post['user_type_id'])){
            if($post['user_type_id'] != 1){
                $formInputFilter = $form->getInputFilter ();
                // change filter
                $formInputFilter->remove ( 'school_name' );
                $inputFactory = new \Zend\InputFilter\Factory();
                $formInputFilter->add ( $inputFactory->createInput ( array (
                    'name' => 'school_name',
                    'required' => false
                ) ) );
            }
        }
//        echo '<pre>';print_r ($post);echo '</pre>';die('vikash');
        $countryDataById = $comMapperObj->getCountryById($post['country_id']);
        $countryDataArr = $countryDataById[0];
        $post['phone_code'] = $countryDataArr->getPhonecode();
        
        if(array_key_exists('ucountries',$post) && array_key_exists('states',$post) && array_key_exists('city',$post)){
            $countryId = $post['ucountries'];
            $stateId = $post['states'];
            
            $countryNameDet = $comMapperObj->getCountryById($countryId);
            $stateNameDet = $comMapperObj->getStateById($stateId);
            
            $ipCaptureDetails['countryNameChanged'] = $countryNameDet[0]->getCountryName();
            $ipCaptureDetails['stateNameChanged'] = $stateNameDet[0]->getStateName();
            $ipCaptureDetails['cityNameChanged'] = $post['city'];
            $post['countryID'] = $countryId;
            $post['stateID'] = $stateId;
            $post['cityValue'] = $post['city'];
        }else{
            $countryName = $post['ip_tracking_country'];
            $stateName = ucwords($post['ip_tracking_state']);
            $cityName = $post['ip_tracking_city'];
            
            $countryDetails = $comMapperObj->getCountryIdByCountryName($countryName);
            $stateDetails = $comMapperObj->getStateIdByStateName($stateName);
            
            $post['countryID'] = $countryDetails[0]->getCountryId();
            if( count($stateDetails) > 0)
                $post['stateID'] = $stateDetails[0]->getStateId();
            else
                $post['stateID'] = 0;
            $post['cityValue'] = $cityName ;
        }
        
        
        if($post['user_type_id'] == 1){
            //$schoolId  = $comMapperObj->getSchoolIdFromERP($post['school_name'],$post['city']);
            $schoolId  = $post['school_id_hidden'];
            $emSchool = false;
            if($schoolId != NULL && $schoolId != '0'){
                $post['school_id'] = $schoolId;
                $emSchool  = $comMapperObj->getSchoolStatusFromId($schoolId);
            }
        }
        if(!empty($dob)) {
            $post['dob'] = $dob;
        }
        $user = $service->register($post);
        
//        $redirect = isset($prg['redirect']) ? $prg['redirect'] : null;

        if (!$user) {
            return array(
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
                'countryData' => $countryData,
                'states' => $statelist,
                'boardList' => $valueOptions,
                'socialOptions' => $socialOptions,
                'southafricaLoaded' => $southafricaLoaded
            );
        }
        
        
        if($registration_plugin == '1'){
            $tablePluginRegistration = $this->getServiceLocator()->get('Assessment\Model\RegistrationViaPluginDetailsTable');
            $dataPlugin['user_id'] = $user->getId();
            $dataPlugin['source'] =  $post['source'];
            $dataPlugin['referer'] =  $post['referer'];
            $dataPlugin['current_page'] = $post['current_page'];
            $tablePluginRegistration->addPluginRegistration($dataPlugin);
        }
        
        $userId = $user->getId();
        $ipCaptureDetails['countryNameIP'] = $post['ip_tracking_country'];
        $ipCaptureDetails['stateNameIP'] = $post['ip_tracking_state'];
        $ipCaptureDetails['cityNameIP'] = $post['ip_tracking_city'];
        
        $res['userId'] = $userId;
        
        if($post['user_type_id'] == '1'){
            $boardArr = explode('_',$post['board1']);
//            $res['board_id'] = $boardArr[0];
//            $res['class_id'] = $post['class1'];
            if(isset($post['otherboard']) && $post['otherboard']!=''){
                $tableuserother = $this->getServiceLocator()->get('Assessment\Model\UserOtherDetailsTable');
                $data['user_id'] = $userId;
                $data['key_name'] = "other_board";
                $data['value'] = $post['otherboard'];
                $tableuserother->insertUserOtherDetails($data);
            }
            
            // code to enter student in tablet_ems_students table
            date_default_timezone_set('Asia/Kolkata');
            $tableemsStudents = $this->getServiceLocator()->get('Assessment\Model\TemsstudentsTable');
            $data = array('user_id' => $userId, 'activation_code' => '0' , 'registration_date' => date("Y-m-d H:i:s") , 'source' => 3 );
            $tableemsStudents->addEmsStudents($data);
        }
        
        
        // update user_ip_capture table
        $comMapperObj->addUserIpCapture($userId,$ipCaptureDetails);
        $tableuser = $this->getServiceLocator()->get('Assessment\Model\UserTable');
        //$tableuser->updateIpCaptureUser($res);
        
        if ($service->getOptions()->getLoginAfterRegistration()) {
            $identityFields = $service->getOptions()->getAuthIdentityFields();
            if (in_array('email', $identityFields)) {
                $post['identity'] = $user->getEmail();
            } elseif (in_array('username', $identityFields)) {
                $post['identity'] = $user->getUsername();
            }
            $passwordArr = explode('@',$user->getEmail());
//            $passwordConcat = '';
//            if(strlen($passwordArr[0])<6){
//                for($passVar = 1 ; $passVar <= 6-strlen($passwordArr[0]) ; $passVar++){
//                    $passwordConcat .= $passVar;  
//                }
//            }
//            $password = $passwordArr[0].$passwordConcat;
            $password = $passwordArr[0];
            $post['credential'] = $password;
            $request->setPost(new Parameters($post));
            
            // entry in token table
            $tokentable = $this->getServiceLocator()->get('Psychometric\Model\TforgotpasswordtokensTable');
                // Add unique token for user in forgot password table.
            $tokenId = $tokentable->addToken($id = '', $user->getId(), $user->getEmail(), $password);
            
            // entry in token table ends here
            
            // Registration mails here
            
            ///////// Student register, Get this html content ///////
            
            $filepath= __DIR__ . '../../../../view/mailer/';
            
            $this->storeLoginDetailInSession($user->getEmail());
            
            if($user->getUserTypeId() == 1){
                $filepath = $filepath.'welcomemailstudent.html';
                $file_content = file_get_contents($filepath);
            }
           /////////////////  End ///////////////////////////////

            ///////// Parent register, Get this html content ///////
            if($user->getUserTypeId() == 2){
                $filepath = $filepath.'welcomemailparent.html';
                $file_content = file_get_contents($filepath);
            }

           ///////// Mentor/Teacher register, Get this html content ///////
            if($user->getUserTypeId() == 3){
                $filepath = $filepath.'welcomemailmentor.html';
                $file_content = file_get_contents($filepath);
            }
            
            $event = $this->getEvent();
            $requestURL = $event->getRequest();
            $router = $event->getRouter();
            $uri = $router->getRequestUri();
            $baseUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
            $config=$this->getServiceLocator()->get('config');
            
            if($user->getUserTypeId() == 1 && $config['registration_drive']['package_drive']=='ON'){
                if($emSchool) {
                    $msgTxt = "Congratulations! You are now a registered user of Extramarks LIVE. Enjoy free access to all the topics of every subject of your chosen class for 2 months";
                    $discount = "- Access all chapter of every subject free of cost for 2 months<br>";
                } else {
                    $msgTxt = "Congratulations! You are now a registered user of Extramarks LIVE. Enjoy free access to all the topics of every subject of your chosen class for 15 days";
                    $discount = "- Access all chapter of every subject free of cost for 15 days<br>";
                }
            } else {
                $discount = "- Access one chapter of every subject free of cost<br>";
                $msgTxt = "Congratulations! You are now a registered user of Extramarks LIVE.Enjoy free access to one topic of every subject of your chosen class.";
            }
            $regMessage = str_replace('{STUDENT_NAME}', $user->getDisplayName(), $file_content);
            $regMessage = str_replace('{DISCOUNT_MESSAGE}', $discount, $regMessage);
            $regMessage = str_replace('{STUDENT_USER ID}', $user->getEmail(), $regMessage);
            $regMessage = str_replace('{STUDENT_PASSWORD}', $password, $regMessage);
            $regMessage = str_replace('{ACTIVATIONLINK}', $baseUrl . "/user/change-password?id=" . $user->getId() . "&token=" . $password, $regMessage);
            $regMessage = str_replace('{BASE_URL}', $baseUrl , $regMessage);
            
            $regSubject= "Registration confirmation";
            // sent Registration Email
            $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent'); 
            $emailData = array("email_id" => $user->getEmail(), 'subject' => $regSubject, 'message' => $regMessage, 'mail_type' => 'registration', 'status' => 1);
            $mailContentTable->addEmailContent($emailData);
            // Registration mail ends here
            
            $config=$this->getServiceLocator()->get('config');
            $defaultstates = $config['msg_engine'];// get the msg config data
            if($defaultstates['status'] == 'ON'){
               $msglog  = $this->getServiceLocator()->get('Package\Model\TmsgLogTable');
               
               $usermobile = $user->getMobile();
               $mobile     = explode("-", $usermobile);
               $mob_number = str_replace('+','',$mobile[0]).$mobile[1];
               if($mobile[1]) {
                    $smsArr = array('to_mobile_number'=>$mob_number,
                        'msg_txt' => $msgTxt,
                        'user_id' => $user->getId(),
                        'mobile_number' => $usermobile,
                        'sms_type' => 'registration'
                    );
                    $data = $comMapperObj->smssendprocess($smsArr);
                    /*$urltohit = "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=347144&username=9811816077&password=agmtj&To=".$mob_number."&Text=".urlencode($msgTxt); 
                    $datas = file_get_contents($urltohit);
                    
                    $data = array('user_id'=>$user->getId(),'mobile_number'=>$usermobile,'api_response'=>$datas,'sms_type'=>'registration');*/
                    $result = $msglog->addlog($data);
               } 
            } 
                
            if($user->getUserTypeId() == 1 || $user->getUserTypeId() == 2 || $user->getUserTypeId() == 3){
                $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
                if($redirect == ''){
                  $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));
                }
                $result = $adapter->prepareForAuthentication($this->getRequest());

                // Return early if an adapter returned a response
                if ($result instanceof Response) {
                    return $result;
                }

                $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);
                $_SESSION['user_from_zf2'] = $auth->getIdentity();
                //$boardList = $this->getService()->getBoardList();
                //$valueOptions = array();
                //$valueOptions[''] = 'Select Board';
//                foreach ($boardList as $container) {
//                    $valueOptions[$container->getRackId()] = $container->getRackName()->getName();
//                    }
                $userLogObj = $this->getServiceLocator()->get("lms_container_mapper");
                $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
                $userLogObj->addUserLog($userId);
                if($user->getUserTypeId() == 1 || $user->getUserTypeId() == 3){
                    $this->externalInvitations($user);
                }
                // student login and class board select pop-up
                
                if ($post['countryID'] != '') {
                    $states = $comMapperObj->getCountarybystate($post['countryID']);
                }

                return array(
//                    'registerForm' => $form,
//                    'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                    'redirect' => $redirect,
                    'countryData' => $countryData,
                    'stateList' => $states,
//                    'socialOptions' => $socialOptions,
                    'password' => $password,
                    'boardList' => $valueOptions,
                    'userObj' => $user,
                    'res' => $post,
                    'urlRediretc' => $urlRediretc,
                    'southafricaLoaded' => $southafricaLoaded
//                    'ipCaptureDetails' => $ipCaptureDetails
                );
            }
            
            return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
        }

        // TODO: Add the redirect parameter here...
        return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN) . ($redirect ? '?redirect=' . rawurlencode($redirect) : ''));
    }
    
    private function externalInvitations($user){
        if($user->getUserTypeId() == 1){
            $invitationFrom = "mentor";
            $notifactionUrl = 'mymentor';
            $invitedAs = 'Mentor';
        }
        else if($user->getUserTypeId() == 3){
            $invitationFrom = "student";
            $notifactionUrl = 'my-students';
            $invitedAs = 'Student';
        }
        // in case registered from mentor/student invitation
        $tableExternalEmail = $this->getServiceLocator()->get('Assessment\Model\InviteexternalemailTable');
        
        $externalInvitationUserCount = $tableExternalEmail->checkVaildInvitationLearnerCount($user,$user->getUserTypeId(),0);
        while($externalInvitationUserCount>0){
            $externalInvitationUser = $tableExternalEmail->checkVaildInvitationLearner($user,$user->getUserTypeId(),0);
            if($externalInvitationUser != "0"){

               $table     = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
               $addRelation = $table->addRelationByInvitation($externalInvitationUser->invitation_from_id, $externalInvitationUser->subject_id, $invitationFrom, $user);

                // add notification
               $relationId[] = $addRelation;
                // if notification pre for current relation Id delete it
               $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
               $notification = $notificationtable->getnotification('',$addRelation);
               if(count($notification)>'0'){
                  foreach($notification as $notify){
                        $data = array(
                            'notification_status' => '2',
                        );
                        $notificationtable->updateStatus($notify->notification_id,$data);
                  }
               }

               $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");
               $countemail = $zfcuserMapperObj->findById($externalInvitationUser->invitation_from_id);
        
                // add notification for  mentor request
                $notifydata = array(
                    'notification_text' => $countemail->getDisplayname().'&nbsp; sent Request to be your '.$invitedAs.'&nbsp;',
                    'userid'            => $user->getId(),
                    'type_id'           => $user->getUserTypeId(),
                    'relation_id'       => $addRelation,
                    'notification_url'  => $notifactionUrl,
                    'created_by'        => $externalInvitationUser->invitation_from_id,
                    'created_date'      => date('Y-m-d H:i:s'),    
                    );

                $notificationtable->insertnotification($notifydata); 
            }
            $externalInvitationUserCount--;
        }
    }
    public function registersocialAction() {
        
        $southafricaLoaded = $this->southAfricaDomainCheck();
        if($southafricaLoaded === true)
            $selectBoardString = 'Select Curriculum';
        else
            $selectBoardString = 'Select Board';

        
        $session_user = new Container('maskedEmailSession');
        $session_user->getManager()->getStorage()->clear('maskedEmailSession');

        $redirect   = $this->params()->fromRoute('redirect');
        $redirect   = htmlspecialchars(strip_tags($redirect));
        if($redirect != ''){
            $redirect = '/'.$redirect;
        }else{
            $redirect = $redirect;
        }
        $urlRediretc = $this->params()->fromRoute('redirect');
        //echo '<pre>';print_r($role);die('macro die');
        $socialOptions = $this->getServiceLocator()->get('ScnSocialAuth-ModuleOptions');
        $boardList = $this->getService()->getBoardList();
        $valueOptions = array();
        $valueOptions[''] = $selectBoardString;
        foreach ($boardList as $container) {
            $valueOptions[$container->getRackId()] = $container->getRackName()->getName();
        }
        $comMapperObj = $this->getServiceLocator()->get("com_mapper");
        $countryData = $comMapperObj->getAllCountries();
        // if the user is logged in, we don't need to register
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        // if registration is disabled
        if (!$this->getOptions()->getEnableRegistration()) {
            return array('enableRegistration' => false);
        }

        $request = $this->getRequest();
        $service = $this->getUserService();
        $form = $this->getRegisterForm();
        $formInputFilter = $form->getInputFilter ();
        
        // UAE validation (country phone code = 971 and country_id = 224 )
        $formPostData = $request->getPost();
//        echo '<pre>';print_r ($formPostData->board1);echo '</pre>';die('Vikash');
        // $_SESSION['register_class_array'] = array();
        
        if($formPostData->board1 != ''){
            $boardIdArray = explode("_",$formPostData->board1);
            $_SESSION['register_board_id'] = $formPostData->board1;
            $classList = $this->getService()->getchildList($boardIdArray[0]); 
            
            $_SESSION['other_board_session'] = $formPostData->otherboard;
            $_SESSION['register_class_array'] = array();
            foreach ($classList as $container) {
                $_SESSION['register_class_array'][$container->getRackId()] = $container->getRackName()->getName();
            }
        }
        if(isset($formPostData->class1)){
            $_SESSION['register_class_id'] = $formPostData->class1;
        }
        if($southafricaLoaded === true){
            $ipCaptureDetails['country'] = 'SOUTH AFRICA';
            $_SESSION['user_session_ip_country'] = $ipCaptureDetails['country'];
            $_SESSION['user_session_ip_state'] = 0;
            $_SESSION['user_session_ip_city'] = 0;
        }else{
            unset($_SESSION['user_session_ip_country']);
            unset($_SESSION['user_session_ip_state']);
            unset($_SESSION['user_session_ip_city']);
        }                    
        if(!isset($_SESSION['user_session_ip_country']) && !isset($_SESSION['user_session_ip_state']) && !isset($_SESSION['user_session_ip_city']) ){
        
            //$ipCaptureDetails = $comMapperObj->useripcapturefunction();
            
            if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
                $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
                $ip_address = $_SERVER['REMOTE_ADDR'];
            }

            if($southafricaLoaded === true){
                $ipCaptureDetails['country'] = 'SOUTH AFRICA';
                $_SESSION['user_session_ip_country'] = $ipCaptureDetails['country'];
                $_SESSION['user_session_ip_state'] = 0;
                $_SESSION['user_session_ip_city'] = 0;
            }else{
                // fetching country/state/city based on IP
                $tableIpCountry = $this->getServiceLocator()->get('Assessment\Model\IpCountryTable');
                $ipResultSet = $tableIpCountry->ipRange($ip_address);
                $ipCaptureDetails = $comMapperObj->useripcapturefunction($ipResultSet,$ip_address);
            }

            if($ipCaptureDetails['country']){
                $countaryidDetails = $comMapperObj->getCountryIdByCountryName($ipCaptureDetails['country']);
                if(count($countaryidDetails) >0){
                    $countaryidDetailsNew = $countaryidDetails[0];
                    $_SESSION['statelist'] = $comMapperObj->getCountarybystate($countaryidDetailsNew->getCountryId());
                }
            }
        }
                
        $_SESSION['minCheck'] = (isset($_SESSION['minCheck']))?$_SESSION['minCheck']:'';   
        $_SESSION['maxCheck'] = (isset($_SESSION['maxCheck']))?$_SESSION['maxCheck']:'';   
        if(isset($formPostData->country_id)){
            if($formPostData->country_id == 224){
                $_SESSION['minCheck'] = 111111111;
                $_SESSION['maxCheck'] = 999999999;
                $_SESSION['register_country_id'] = 224;
            }else{
                $_SESSION['minCheck'] = 1111111111;
                $_SESSION['maxCheck'] = 9999999999;
                $_SESSION['register_country_id'] = $formPostData->country_id;
            }
        }
        // change filter
        $formInputFilter->remove ( 'mobile' );
        $inputFactory = new \Zend\InputFilter\Factory();
        $formInputFilter->add ( $inputFactory->createInput ( array (
            'name' => 'mobile',
            'required' => false,
            'filters'  => array(
                array('name' => 'Int'),
            ),
            'validators' => array(
              array(
                  'name' => 'Between',
                  'options' => array(
                      'min' => $_SESSION['minCheck'],
                      'max' => $_SESSION['maxCheck'],
                      'messages' => array(
                         'notBetween' => 'Please enter a valid mobile number'
                      )
                  ),
              ),
            ),
        ) ) );
        
        
//        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
//            $redirect = $request->getQuery()->get('redirect');
//        } else {
//            $redirect = false;
//        }

//        $redirectUrl = $this->url()->fromRoute(static::ROUTE_REGISTER)
//                . ($redirect ? '?redirect=' . rawurlencode($redirect) : '');
        
        $redirectUrl = $this->url()->fromRoute('zfcuser/registersocial')
                . ($redirect);
        $prg = $this->prg($redirectUrl, true);

        
        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
                'countryData' => $countryData,
                'boardList' => $valueOptions,
                'socialOptions' => $socialOptions,
                'southafricaLoaded' => $southafricaLoaded
            );
        }
//       $commonmapper =  $this->getServiceLocator()->get("com_mapper");
//       $prg          = $commonmapper->escaper($prg);
       $post         = $prg;
        
        if(isset($post['user_type_id'])){
            if($post['user_type_id'] != 1){
                $formInputFilter = $form->getInputFilter ();
                // change filter
                $formInputFilter->remove ( 'school_name' );
                $inputFactory = new \Zend\InputFilter\Factory();
                $formInputFilter->add ( $inputFactory->createInput ( array (
                    'name' => 'school_name',
                    'required' => false
                ) ) );
            }
        }
//        echo '<pre>';print_r ($post);echo '</pre>';die('vikash');
        $countryDataById = $comMapperObj->getCountryById($post['country_id']);
        $countryDataArr = $countryDataById[0];
        $post['phone_code'] = $countryDataArr->getPhonecode();
        
        if(array_key_exists('ucountries',$post) && array_key_exists('states',$post) && array_key_exists('city',$post)){
            $countryId = $post['ucountries'];
            $stateId = $post['states'];
            
            $countryNameDet = $comMapperObj->getCountryById($countryId);
            $stateNameDet = $comMapperObj->getStateById($stateId);
            
            $ipCaptureDetails['countryNameChanged'] = $countryNameDet[0]->getCountryName();
            $ipCaptureDetails['stateNameChanged'] = $stateNameDet[0]->getStateName();
            $ipCaptureDetails['cityNameChanged'] = $post['city'];
            $post['countryID'] = $countryId;
            $post['stateID'] = $stateId;
            $post['cityValue'] = $post['city'];
        }else{
            $countryName = $post['ip_tracking_country'];
            $stateName = ucwords($post['ip_tracking_state']);
            $cityName = $post['ip_tracking_city'];
            
            $countryDetails = $comMapperObj->getCountryIdByCountryName($countryName);
            $stateDetails = $comMapperObj->getStateIdByStateName($stateName);
            
            $post['countryID'] = $countryDetails[0]->getCountryId();
            $post['stateID'] = $stateDetails[0]->getStateId();
            $post['cityValue'] = $cityName ;
        }
        
        if($post['user_type_id'] == 1){
            //$schoolId  = $comMapperObj->getSchoolIdFromERP($post['school_name'],$post['city']);
            $schoolId  = $post['school_id_hidden'];
            $emSchool = false;
            if($schoolId != NULL && $schoolId != '0'){
                $post['school_id'] = $schoolId;
                $emSchool  = $comMapperObj->getSchoolStatusFromId($schoolId);
        }
        }
        $user = $service->register($post);
        
//        $redirect = isset($prg['redirect']) ? $prg['redirect'] : null;

        if (!$user) {
            return array(
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
                'countryData' => $countryData,
                'states' => $statelist,
                'boardList' => $valueOptions,
                'socialOptions' => $socialOptions,
                'southafricaLoaded' => $southafricaLoaded
            );
        }
        
        
        $userId = $user->getId();
        $ipCaptureDetails['countryNameIP'] = $post['ip_tracking_country'];
        $ipCaptureDetails['stateNameIP'] = $post['ip_tracking_state'];
        $ipCaptureDetails['cityNameIP'] = $post['ip_tracking_city'];
        
        $res['userId'] = $userId;
        
        if($post['user_type_id'] == '1'){
            $boardArr = explode('_',$post['board1']);
//            $res['board_id'] = $boardArr[0];
//            $res['class_id'] = $post['class1'];
            if(isset($post['otherboard']) && $post['otherboard']!=''){
                $tableuserother = $this->getServiceLocator()->get('Assessment\Model\UserOtherDetailsTable');
                $data['user_id'] = $userId;
                $data['key_name'] = "other_board";
                $data['value'] = $post['otherboard'];
                $tableuserother->insertUserOtherDetails($data);
            }
            // code to enter student in tablet_ems_students table
            date_default_timezone_set('Asia/Kolkata');
            $tableemsStudents = $this->getServiceLocator()->get('Assessment\Model\TemsstudentsTable');
            $data = array('user_id' => $userId, 'activation_code' => '0' , 'registration_date' => date("Y-m-d H:i:s") , 'source' => 3 );
            $tableemsStudents->addEmsStudents($data);
        }
        
        
        // update user_ip_capture table
        $comMapperObj->addUserIpCapture($userId,$ipCaptureDetails);
        $tableuser = $this->getServiceLocator()->get('Assessment\Model\UserTable');
        
        if ($service->getOptions()->getLoginAfterRegistration()) {
            $identityFields = $service->getOptions()->getAuthIdentityFields();
            if (in_array('email', $identityFields)) {
                $post['identity'] = $user->getEmail();
            } elseif (in_array('username', $identityFields)) {
                $post['identity'] = $user->getUsername();
            }
            $passwordArr = explode('@',$user->getEmail());
//            $passwordConcat = '';
//            if(strlen($passwordArr[0])<6){
//                for($passVar = 1 ; $passVar <= 6-strlen($passwordArr[0]) ; $passVar++){
//                    $passwordConcat .= $passVar;  
//                }
//            }
//            $password = $passwordArr[0].$passwordConcat;
            $password = $passwordArr[0];
            $post['credential'] = $password;
            $request->setPost(new Parameters($post));
            
            
            if($user->getUserTypeId() == 1 || $user->getUserTypeId() == 2 || $user->getUserTypeId() == 3){
                $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
                if($redirect == ''){
                    $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));
                }
                $result = $adapter->prepareForAuthentication($this->getRequest());

                // Return early if an adapter returned a response
                if ($result instanceof Response) {
                    return $result;
                }

                $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);
                $_SESSION['user_from_zf2'] = $auth->getIdentity();
                
                $userLogObj = $this->getServiceLocator()->get("lms_container_mapper");
                $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
                $userLogObj->addUserLog($userId);
                if($user->getUserTypeId() == 1 || $user->getUserTypeId() == 3){
                    $this->externalInvitations($user);
                }
                $config=$this->getServiceLocator()->get('config');
                $defaultstates = $config['msg_engine'];// get the msg config data
                if($defaultstates['status'] == 'ON'){
                   $msglog  = $this->getServiceLocator()->get('Package\Model\TmsgLogTable');

                   $msgTxt = "Congratulations! You are now a registered user of Extramarks LIVE.Enjoy free access to one topic of every subject of your chosen class."; 
                   $usermobile = $user->getMobile();
                   $mobile     = explode("-", $usermobile);
                   $mob_number = str_replace('+','',$mobile[0]).$mobile[1];
                   if($mobile[1]) {
                        $smsArr = array('to_mobile_number'=>$mob_number,
                            'msg_txt' => $msgTxt,
                            'user_id' => $user->getId(),
                            'mobile_number' => $usermobile,
                            'sms_type' => 'registration'
                        );
                        $data = $comMapperObj->smssendprocess($smsArr);
                        /*$urltohit = "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=347144&username=9811816077&password=agmtj&To=".$mob_number."&Text=".urlencode($msgTxt); 
                        $datas = file_get_contents($urltohit);

                        $data = array('user_id'=>$user->getId(),'mobile_number'=>$usermobile,'api_response'=>$datas);*/
                        $result = $msglog->addlog($data);
                   }
                }
                
                    if($user->getUserTypeId() == 1){
                        return $this->redirect()->toRoute('zfcuser/user-dashboard', array('controller' => 'zfcuser', 'action' => 'user-dashboard', 'id' => $user->getUserTypeId() , 'welcome' => 'welcome' ));
                    }
                if ($post['countryID'] != '') {
                    $states = $comMapperObj->getCountarybystate($post['countryID']);
                }
                
                return array(
//                    'registerForm' => $form,
//                    'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                    'redirect' => $redirect,
                    'countryData' => $countryData,
                    'stateList' => $states,
//                    'socialOptions' => $socialOptions,
                    'password' => $password,
                    'boardList' => $valueOptions,
                    'userObj' => $user,
                    'res' => $post,
                    'urlRediretc' => $urlRediretc,
                    'southafricaLoaded' => $southafricaLoaded
//                    'ipCaptureDetails' => $ipCaptureDetails
                );
            }
            
            return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
        }

        // TODO: Add the redirect parameter here...
        return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN) . ($redirect ? '?redirect=' . rawurlencode($redirect) : ''));
    }
    public function registermaskedemailAction() {
                
        $southafricaLoaded = $this->southAfricaDomainCheck();
        if($southafricaLoaded === true)
            $selectBoardString = 'Select Curriculum';
        else
            $selectBoardString = 'Select Board';

        $request = $this->getRequest();
        $maskedEmailSession = new Container('maskedEmailSession');
        if ($maskedEmailSession->offsetExists('maskedEmail')) {
            $masked_email = $maskedEmailSession->offsetGet('maskedEmail');
            $maskedEmailData = $this->maskedemaildata($masked_email);
        } else {
            $masked_email = false;
            $maskedEmailData = '';
            return $this->redirect()->toRoute('home');
        }
        $schoolLoginObj = $this->getServiceLocator()->get("admin_mapper");
        if($maskedEmailData){
            $schoolLoginAdd = $schoolLoginObj->addSchoolLoginData($masked_email);
            $schoolLoginId = $schoolLoginAdd->getId();
        }
        
        $redirect   = $this->params()->fromRoute('redirect');
        $redirect   = htmlspecialchars(strip_tags($redirect));
        if($redirect != ''){
            $redirect = '/'.$redirect;
        }else{
            $redirect = $redirect;
        }
        $urlRediretc = $this->params()->fromRoute('redirect');
        
        $socialOptions = $this->getServiceLocator()->get('ScnSocialAuth-ModuleOptions');
        $boardList = $this->getService()->getBoardList();
        $valueOptions = array();
        $valueOptions[''] = $selectBoardString;
        foreach ($boardList as $container) {
            $valueOptions[$container->getRackId()] = $container->getRackName()->getName();
        }
        $comMapperObj = $this->getServiceLocator()->get("com_mapper");
        $countryData = $comMapperObj->getAllCountries();
        
        // if the user is logged in, we don't need to register
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        // if registration is disabled
        if (!$this->getOptions()->getEnableRegistration()) {
            return array('enableRegistration' => false);
        }

        
        $service = $this->getUserService();
        $form = $this->getRegisterForm();
        
        $formInputFilter = $form->getInputFilter ();
        
                
        // UAE validation (country phone code = 971 and country_id = 224 )
        $formPostData = $request->getPost();
        
        if($formPostData->board1 != ''){
            $boardIdArray = explode("_",$formPostData->board1);
            $_SESSION['register_board_id'] = $formPostData->board1;
            $classList = $this->getService()->getchildList($boardIdArray[0]); 
            
            $_SESSION['other_board_session'] = $formPostData->otherboard;
            $_SESSION['register_class_array'] = array();
            foreach ($classList as $container) {
                $_SESSION['register_class_array'][$container->getRackId()] = $container->getRackName()->getName();
            }
        }
        if(isset($formPostData->class1)){
            $_SESSION['register_class_id'] = $formPostData->class1;
        }
        if($southafricaLoaded === true){
            $ipCaptureDetails['country'] = 'SOUTH AFRICA';
            $_SESSION['user_session_ip_country'] = $ipCaptureDetails['country'];
            $_SESSION['user_session_ip_state'] = 0;
            $_SESSION['user_session_ip_city'] = 0;
        }else{
            unset($_SESSION['user_session_ip_country']);
            unset($_SESSION['user_session_ip_state']);
            unset($_SESSION['user_session_ip_city']);
        }              
        if(!isset($_SESSION['user_session_ip_country']) && !isset($_SESSION['user_session_ip_state']) && !isset($_SESSION['user_session_ip_city']) ){
        
            //$ipCaptureDetails = $comMapperObj->useripcapturefunction();
            
            if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
                $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
                $ip_address = $_SERVER['REMOTE_ADDR'];
            }

            if($southafricaLoaded === true){
                $ipCaptureDetails['country'] = 'SOUTH AFRICA';
                $_SESSION['user_session_ip_country'] = $ipCaptureDetails['country'];
                $_SESSION['user_session_ip_state'] = 0;
                $_SESSION['user_session_ip_city'] = 0;
            }else{
                // fetching country/state/city based on IP
                $tableIpCountry = $this->getServiceLocator()->get('Assessment\Model\IpCountryTable');
                $ipResultSet = $tableIpCountry->ipRange($ip_address);
                $ipCaptureDetails = $comMapperObj->useripcapturefunction($ipResultSet,$ip_address);
            }
            if($ipCaptureDetails['country']){
                $countaryidDetails = $comMapperObj->getCountryIdByCountryName($ipCaptureDetails['country']);
                if(count($countaryidDetails) >0){
                    $countaryidDetailsNew = $countaryidDetails[0];
                    $_SESSION['statelist'] = $comMapperObj->getCountarybystate($countaryidDetailsNew->getCountryId());
                }
            }
        }
                
        $_SESSION['minCheck'] = (isset($_SESSION['minCheck']))?$_SESSION['minCheck']:'';   
        $_SESSION['maxCheck'] = (isset($_SESSION['maxCheck']))?$_SESSION['maxCheck']:'';   
        
        if(isset($formPostData->country_id)){
            if($formPostData->country_id == 224){
                $_SESSION['minCheck'] = 111111111;
                $_SESSION['maxCheck'] = 999999999;
                $_SESSION['register_country_id'] = 224;
            }else{
                $_SESSION['minCheck'] = 1111111111;
                $_SESSION['maxCheck'] = 9999999999;
                $_SESSION['register_country_id'] = $formPostData->country_id;
            }
        }

        // change filter
        $formInputFilter->remove ( 'mobile' );
        $inputFactory = new \Zend\InputFilter\Factory();
        $formInputFilter->add ( $inputFactory->createInput ( array (
            'name' => 'mobile',
            'required' => false,
            'filters'  => array(
                array('name' => 'Int'),
            ),
            'validators' => array(
              array(
                  'name' => 'Between',
                  'options' => array(
                      'min' => $_SESSION['minCheck'],
                      'max' => $_SESSION['maxCheck'],
                      'messages' => array(
                         'notBetween' => 'Please enter a valid mobile number'
                      )
                  ),
              ),
            ),
        ) ) );
        
//        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $request->getQuery()->get('redirect')) {
//            $redirect = $request->getQuery()->get('redirect');
//        } else {
//            $redirect = false;
//        }

//        $redirectUrl = $this->url()->fromRoute(static::ROUTE_REGISTER)
//                . ($redirect ? '?redirect=' . rawurlencode($redirect) : '');
        
        $redirectUrl = $this->url()->fromRoute('zfcuser/registermaskedemail')
                . ($redirect);
        $prg = $this->prg($redirectUrl, true);

         
        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
                'countryData' => $countryData,
                'boardList' => $valueOptions,
                'socialOptions' => $socialOptions,
                'masked' => 'masked',
                'maskedEmailData' => $maskedEmailData,
                'southafricaLoaded' => $southafricaLoaded
            );
        }

        $post = $prg;
        
        if(isset($post['user_type_id'])){
            if($post['user_type_id'] != 1){
                $formInputFilter = $form->getInputFilter ();
                // change filter
                $formInputFilter->remove ( 'school_name' );
                $inputFactory = new \Zend\InputFilter\Factory();
                $formInputFilter->add ( $inputFactory->createInput ( array (
                    'name' => 'school_name',
                    'required' => false
                ) ) );
            }
        }
        
        $countryDataById = $comMapperObj->getCountryById($post['country_id']);
        $countryDataArr = $countryDataById[0];
        $post['phone_code'] = $countryDataArr->getPhonecode();
        
        if(array_key_exists('ucountries',$post) && array_key_exists('states',$post) && array_key_exists('city',$post)){
            $countryId = $post['ucountries'];
            $stateId = $post['states'];
            
            $countryNameDet = $comMapperObj->getCountryById($countryId);
            $stateNameDet = $comMapperObj->getStateById($stateId);
            
            $ipCaptureDetails['countryNameChanged'] = $countryNameDet[0]->getCountryName();
            $ipCaptureDetails['stateNameChanged'] = $stateNameDet[0]->getStateName();
            $ipCaptureDetails['cityNameChanged'] = $post['city'];
            $post['countryID'] = $countryId;
            $post['stateID'] = $stateId;
            $post['cityValue'] = $post['city'];
        }else{
            $countryName = $post['ip_tracking_country'];
            $stateName = ucwords($post['ip_tracking_state']);
            $cityName = $post['ip_tracking_city'];
            
            $countryDetails = $comMapperObj->getCountryIdByCountryName($countryName);
            $stateDetails = $comMapperObj->getStateIdByStateName($stateName);
            
            $post['countryID'] = $countryDetails[0]->getCountryId();
            if( count($stateDetails) > 0)
                $post['stateID'] = $stateDetails[0]->getStateId();
            else
                $post['stateID'] = 0;
            $post['cityValue'] = $cityName ;
        }
        
        if($post['user_type_id'] == 1){
            //$schoolId  = $comMapperObj->getSchoolIdFromERP($post['school_name'],$post['city']);
            $schoolId  = $post['school_id_hidden'];
            $emSchool = false;
            if($schoolId != NULL && $schoolId != '0'){
                $post['school_id'] = $schoolId;
                $emSchool  = $comMapperObj->getSchoolStatusFromId($schoolId);
        }
        }
        $user = $service->register($post);
        
        if($user){
            if($post['school_email']){
                $schoolLoginUpdate = $schoolLoginObj->updateSchoolLoginData($post['school_email'],$user->getId());
            }
        }
        $redirect = isset($prg['redirect']) ? $prg['redirect'] : null;

        if (!$user) {
            return array(
                'registerForm' => $form,
                'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                'redirect' => $redirect,
                'countryData' => $countryData,
                'socialOptions' => $socialOptions,
                'boardList' => $valueOptions,
                'masked' => 'masked',
                'maskedEmailData' => $maskedEmailData,
                'southafricaLoaded' => $southafricaLoaded
            );
        }
        
        $userId = $user->getId();
        $ipCaptureDetails['countryNameIP'] = $post['ip_tracking_country'];
        $ipCaptureDetails['stateNameIP'] = $post['ip_tracking_state'];
        $ipCaptureDetails['cityNameIP'] = $post['ip_tracking_city'];
        
        $res['userId'] = $userId;
        
        if($post['user_type_id'] == '1'){
            if(isset($post['otherboard']) && $post['otherboard']!=''){
                $tableuserother = $this->getServiceLocator()->get('Assessment\Model\UserOtherDetailsTable');
                $data['user_id'] = $userId;
                $data['key_name'] = "other_board";
                $data['value'] = $post['otherboard'];
                $tableuserother->insertUserOtherDetails($data);
            }
            // code to enter student in tablet_ems_students table
            date_default_timezone_set('Asia/Kolkata');
            $tableemsStudents = $this->getServiceLocator()->get('Assessment\Model\TemsstudentsTable');
            $data = array('user_id' => $userId, 'activation_code' => '0' , 'registration_date' => date("Y-m-d H:i:s") , 'source' => 3 );
            $tableemsStudents->addEmsStudents($data);
        }
        
        // update user_ip_capture table
        $comMapperObj->addUserIpCapture($userId,$ipCaptureDetails);
        
        if ($service->getOptions()->getLoginAfterRegistration()) {
            $identityFields = $service->getOptions()->getAuthIdentityFields();
            if (in_array('email', $identityFields)) {
                $post['identity'] = $user->getEmail();
            } elseif (in_array('username', $identityFields)) {
                $post['identity'] = $user->getUsername();
            }
            $passwordArr = explode('@',$user->getEmail());
//            $passwordConcat = '';
//            if(strlen($passwordArr[0])<6){
//                for($passVar = 1 ; $passVar <= 6-strlen($passwordArr[0]) ; $passVar++){
//                    $passwordConcat .= $passVar;  
//                }
//            }
//            $password = $passwordArr[0].$passwordConcat;
            $password = $passwordArr[0];
            $post['credential'] = $password;
            $request->setPost(new Parameters($post));
            
            // entry in token table
            $tokentable = $this->getServiceLocator()->get('Psychometric\Model\TforgotpasswordtokensTable');
                // Add unique token for user in forgot password table.
            $tokenId = $tokentable->addToken($id = '', $user->getId(), $user->getEmail(), $password);
            
            // entry in token table ends here
            
            // Registration mails here
            $this->storeLoginDetailInSession($user->getEmail());
            ///////// Student register, Get this html content ///////
            $filepath= __DIR__ . '../../../../view/mailer/';
            if($user->getUserTypeId() == 1){
//                $filepath = 'public/mailer/welcomemailstudent.html';
                $filepath = $filepath.'welcomemailstudent.html';
                $file_content = file_get_contents($filepath);
            }
           /////////////////  End ///////////////////////////////

            ///////// Parent register, Get this html content ///////
            if($user->getUserTypeId() == 2){
//                $filepath = 'public/mailer/welcomemailparent.html';
                $filepath = $filepath.'welcomemailparent.html';
                $file_content = file_get_contents($filepath);
            }

           ///////// Mentor/Teacher register, Get this html content ///////
            if($user->getUserTypeId() == 3){
//                $filepath = 'public/mailer/welcomemailmentor.html';
                $filepath = $filepath.'welcomemailmentor.html';
                $file_content = file_get_contents($filepath);
            }
            
            $event = $this->getEvent();
            $requestURL = $event->getRequest();
            $router = $event->getRouter();
            $uri = $router->getRequestUri();
            $baseUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
            $config=$this->getServiceLocator()->get('config');
            
            if($user->getUserTypeId() == 1 && $config['registration_drive']['package_drive']=='ON'){
                if($emSchool) {
                    $msgTxt = "Congratulations! You are now a registered user of Extramarks LIVE. Enjoy free access to all the topics of every subject of your chosen class for 2 months";
                    $discount = "- Access all chapter of every subject free of cost for 2 months<br>";
                } else {
                    $msgTxt = "Congratulations! You are now a registered user of Extramarks LIVE. Enjoy free access to all the topics of every subject of your chosen class for 15 days";
                    $discount = "- Access all chapter of every subject free of cost for 15 days<br>";
                }
            } else {
                $discount = "- Access one chapter of every subject free of cost<br>";
                $msgTxt = "Congratulations! You are now a registered user of Extramarks LIVE.Enjoy free access to one topic of every subject of your chosen class.";
            }
            $regMessage = str_replace('{STUDENT_NAME}', $user->getDisplayName(), $file_content);
            $regMessage = str_replace('{DISCOUNT_MESSAGE}', $discount, $regMessage);
            $regMessage = str_replace('{STUDENT_USER ID}', $user->getEmail(), $regMessage);
            $regMessage = str_replace('{STUDENT_PASSWORD}', $password, $regMessage);
            $regMessage = str_replace('{ACTIVATIONLINK}', $baseUrl . "/user/change-password?id=" . $user->getId() . "&token=" . $password, $regMessage);
            $regMessage = str_replace('{BASE_URL}', $baseUrl , $regMessage);
            
            $regSubject= "Registration confirmation";
            // sent Registration Email
            $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent'); 
            $emailData = array("email_id" => $user->getEmail(), 'subject' => $regSubject, 'message' => $regMessage, 'mail_type' => 'registration', 'status' => 1);
            $mailContentTable->addEmailContent($emailData);
            // Registration mail ends here
            
            $config=$this->getServiceLocator()->get('config');
            $defaultstates = $config['msg_engine'];// get the msg config data
            if($defaultstates['status'] == 'ON'){
               $msglog  = $this->getServiceLocator()->get('Package\Model\TmsgLogTable');
               
               $usermobile = $user->getMobile();
               $mobile     = explode("-", $usermobile);
               $mob_number = str_replace('+','',$mobile[0]).$mobile[1];
               if($mobile[1]) {
                    $smsArr = array('to_mobile_number'=>$mob_number,
                        'msg_txt' => $msgTxt,
                        'user_id' => $user->getId(),
                        'mobile_number' => $usermobile,
                        'sms_type' => 'registration'
                    );
                    $data = $comMapperObj->smssendprocess($smsArr);
                    /*$urltohit = "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=347144&username=9811816077&password=agmtj&To=".$mob_number."&Text=".urlencode($msgTxt); 
                    $datas = file_get_contents($urltohit);
                    $data = array('user_id'=>$user->getId(),'mobile_number'=>$usermobile,'api_response'=>$datas,'sms_type'=>'registration');*/
                    $result = $msglog->addlog($data);
               }
            }
            
            
            if($user->getUserTypeId() == 1 || $user->getUserTypeId() == 2 || $user->getUserTypeId() == 3){
                $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
                if($redirect == ''){
                    $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));
                }
                $result = $adapter->prepareForAuthentication($this->getRequest());

                // Return early if an adapter returned a response
                if ($result instanceof Response) {
                    return $result;
                }

                $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);
                $_SESSION['user_from_zf2'] = $auth->getIdentity();
                //$boardList = $this->getService()->getBoardList();
                //$valueOptions = array();
                //$valueOptions[''] = 'Select Board';
//                foreach ($boardList as $container) {
//                    $valueOptions[$container->getRackId()] = $container->getRackName()->getName();
//                    }
                $userLogObj = $this->getServiceLocator()->get("lms_container_mapper");
                $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
                $userLogObj->addUserLog($userId);
                if($user->getUserTypeId() == 1 || $user->getUserTypeId() == 3){
                    $this->externalInvitations($user);
                }
                // student login and class board select pop-up
                
                if ($post['countryID'] != '') {
                    $states = $comMapperObj->getCountarybystate($post['countryID']);
                }

                return array(
//                    'registerForm' => $form,
//                    'enableRegistration' => $this->getOptions()->getEnableRegistration(),
                    'redirect' => $redirect,
                    'countryData' => $countryData,
                    'stateList' => $states,
//                    'socialOptions' => $socialOptions,
                    'password' => $password,
                    'boardList' => $valueOptions,
                    'userObj' => $user,
                    'res' => $post,
                    'urlRediretc' => $urlRediretc,
                    'maskedEmailData' => $maskedEmailData,
                    'southafricaLoaded' => $southafricaLoaded
//                    'ipCaptureDetails' => $ipCaptureDetails
                );
            }
            
            return $this->forward()->dispatch(static::CONTROLLER_NAME, array('action' => 'authenticate'));
        }

        // TODO: Add the redirect parameter here...
        return $this->redirect()->toUrl($this->url()->fromRoute(static::ROUTE_LOGIN) . ($redirect ? '?redirect=' . rawurlencode($redirect) : ''));
    }
    
    public function checkemailexistsAction() {
        $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");
        $countemail = $zfcuserMapperObj->findByEmail($_POST['email_id']);
        if (count($countemail) == 0) {
            echo json_encode(array(
                'output' => 'success',
            ));
            die;
        }else{
            echo json_encode(array(
                'output' => 'failure',
            ));
            die;
        }
        
    }
    
    public function checkcountryphonedegitsAction() {
        $comMapperObj = $this->getServiceLocator()->get("com_mapper");
        $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");
        //$countemail = $zfcuserMapperObj->findByEmail($_POST['email_id']);
        $phone_code = trim($this->getRequest()->getPost('phone_code'),'+');
        
        $phone_digits = $this->getRequest()->getPost('phone_digits');
        
        
        $country_type = $this->getRequest()->getPost('country_type');
        $tablecountryphone = $this->getServiceLocator()->get('Assessment\Model\TcountryphoneTable');
        //$allCountriesPhoneDigits = $tablecountryphone->getAllCountryPhoneDetails();
        
        $countryId='';    
        if($country_type=='country_code') {
            $countryDetails = $comMapperObj->getCountryByPhoneCode($phone_code);
            //$allCountryDetails = $comMapperObj->getAllCountries();
            foreach($countryDetails as $country) {
                if($country->getPhoneCode()==$phone_code) {
                    $countryId = $country->getCountryId();
                    break;
                }
            }
            $phoneCodeLength = strlen($phone_code);
            $totalLength = $phoneCodeLength + $phone_digits;
            /*if($totalLength > 15) {
                echo json_encode(array(
                    'output' => 'fail',
                    'message' => 'phone code plus phone digit can not be greater than 15 digits'
                ));
                die;
            }*/
            if(empty($countryId)) {    
                $countryPhoneDigits = array();
            } else {
                $countryPhoneDigits = $tablecountryphone->getCountryDetailsByCountryId($countryId);
            }
        } else {
            $countryRow = $comMapperObj->getCountryById($phone_code);
            foreach($countryRow as $country) {
                $phoneCode = $country->getPhoneCode();
            }
            
            $phoneCodeLength = strlen($phoneCode);
            $totalLength = $phoneCodeLength + $phone_digits;
            /*if($totalLength > 15) {
                echo json_encode(array(
                    'output' => 'fail',
                    'message' => 'phone code plus phone digit can not be greater than 15 digits'
                ));
                die;
            }*/
            $countryPhoneDigits = $tablecountryphone->getCountryDetailsByCountryId($phone_code);
        }
        $flag = false;
        
        if(count($countryPhoneDigits) > 0) {
            foreach($countryPhoneDigits as $countryphone) {
                if($countryphone->lower_phone_digit_limit <= $totalLength && $totalLength <= $countryphone->upper_phone_digit_limit) {
                    $flag=true;
                }
            }
        } else {
            if($totalLength>=10 && $totalLength <= 15){
                $flag=true;
            }
        }
        if ($flag) {
            echo json_encode(array(
                'output' => 'success',
            ));
            die;
        }else{
            echo json_encode(array(
                'output' => 'failure',
            ));
            die;
        }
        
    }
    
    public function getchildcountAction() {
        $userObj = $this->getServiceLocator()->get("Assessment\Model\UserTable");
        $parentId = $this->zfcUserAuthentication()->getIdentity()->getId();
        $childDataCount = $userObj->countChildData($parentId);
        if ($childDataCount == 0) {
            $tableparentchild = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
            $childData = $tableparentchild->getChildData($parentId);
            $childDataCount = $tableparentchild->countChildparentData($parentId);
        }
        if($childDataCount > 0) {
            echo json_encode(array(
                'output' => 'success',
            ));
            die;
        } else {
            echo json_encode(array(
                'output' => 'fail',
            ));
            die;
        }
    }
    
    public function registerchildAction() {
        
        $userEmail = array();
        if (isset($_POST['childName']) && $_POST['childName'] != '') {
//            $service = $this->getUserService();
//            $table = $this->getServiceLocator()->get('ZfcUser\Model\TuserTable');
            // Check username is already exists or not
            
            $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");
            $countemail = $zfcuserMapperObj->findByUsername($_POST['childUserId']);
            
//            $countemail = $table->checkUserName($_POST['childUserId']);
            if (count($countemail) == 0) {
                $parentId = $this->zfcUserAuthentication()->getIdentity()->getId();
                $service = $this->getUserService();
                $user = $service->registerChild($_POST,$parentId);
                
                $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");        
                $countemail = $zfcuserMapperObj->findByEmail($_POST['childUserId']);
                
                if(isset($_POST['otherboardchild']) && $_POST['otherboardchild']!=''){
                    $tableuserother = $this->getServiceLocator()->get('Assessment\Model\UserOtherDetailsTable');
                    $data['user_id'] = $countemail->getId();
                    $data['key_name'] = "other_board";
                    $data['value'] = $_POST['otherboardchild'];
                    $tableuserother->insertUserOtherDetails($data);
                }
//                $user = $table->addChildUser($_POST);

                if ($user != "") {
                    $res['countryID'] = $_POST['ucountriesChild'];
                    $res['stateID'] = $_POST['state'];
                    $res['cityValue'] = $_POST['city'];
                    $res['userId'] = $countemail->getId();
                    $tableuser = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                    $tableuser->updateIpCaptureUser($res);
        
                    $redirect_url = '';
                    $_SESSION['redirecturl'] = $_SERVER['HTTP_REFERER'];
                    if (isset($_SESSION['redirecturl']) && $_SESSION['redirecturl'] != '') {
                        $redirect_url = $_SESSION['redirecturl'];
                    }
                    // code to enter student in tablet_ems_students table
                    date_default_timezone_set('Asia/Kolkata');
                    $tableemsStudents = $this->getServiceLocator()->get('Assessment\Model\TemsstudentsTable');
                    $data = array('user_id' => $res['userId'], 'activation_code' => '0' , 'registration_date' => date("Y-m-d H:i:s") , 'source' => 3 );
                    $tableemsStudents->addEmsStudents($data);
                    echo json_encode(array(
                        'output' => 'success',
                        'message' => 'Child has been added successfully',
                        'redirect_url' => $redirect_url
                    ));
                    exit;
//                    $result = new JsonModel(array(
//                        'output' => 'success',
//                        'redirect_url' => $redirect_url
//                    ));
                } else {
                    echo json_encode(array(
                        'output' => 'notsuccess',
                    ));
                    exit;
                    
//                    $result = new JsonModel(array(
//                        'output' => 'notsuccess',
//                    ));
                }
            } else {
                echo json_encode(array(
                    'output' => 'usernameExists',
                ));
                    exit;
                    
//                $result = new JsonModel(array(
//                    'output' => 'usernameExists',
//                ));
            }
        }
        return $result;
    }
    
    public function invitelearnerAction() {
        
        if (isset($_POST['invited_email']) && $_POST['invited_email'] != '') {
            $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");
            $countemail = $zfcuserMapperObj->findByUsername($_POST['invited_email']);
            if (count($countemail) == 0) {
                $invitationFromId = $this->zfcUserAuthentication()->getIdentity()->getId();
                $loginDisplayName = $this->zfcUserAuthentication()->getIdentity()->getDisplayName();
                $tableExternalEmail = $this->getServiceLocator()->get('Assessment\Model\InviteexternalemailTable');
                $alreadyInvited = $tableExternalEmail->checkDuplicateInvite($invitationFromId,$_POST['invited_email'],1);
                
                if($alreadyInvited == '0'){
                    $user = $tableExternalEmail->inviteRelation($invitationFromId,$_POST['invited_email'],1);

                    if ($user != "") {
                        $filepath= __DIR__ . '../../../../view/mailer/';
                        $filepath = $filepath.'invitelearnerexternal.html';
                        $file_content = file_get_contents($filepath);

                        $event = $this->getEvent();
                        $requestURL = $event->getRequest();
                        $router = $event->getRouter();
                        $uri = $router->getRequestUri();
                        $baseUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());

                        $regMessage = str_replace('{BASE_URL}', $baseUrl , $file_content);

                        $regSubject= "Invite Learner";
                        // sent Invitation Email
                        $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent'); 
                        $emailData = array("email_id" => $_POST['invited_email'], 'subject' => $regSubject, 'message' => $regMessage, 'mail_type' => 'useractivities', 'status' => 1);

                        $mailContentTable->addEmailContent($emailData);

                        $redirect_url = '';
                        $_SESSION['redirecturl'] = $_SERVER['HTTP_REFERER'];
                        if (isset($_SESSION['redirecturl']) && $_SESSION['redirecturl'] != '') {
                            $redirect_url = $_SESSION['redirecturl'];
                        }
                        echo json_encode(array(
                            'output' => 'success',
                            'message' => 'Child has been added successfully',
                            'redirect_url' => $redirect_url
                        ));
                        exit;
                    }else {
                        echo json_encode(array(
                            'output' => 'notsuccess',
                        ));
                        exit;
                    }
                }else{
                    echo json_encode(array(
                        'output' => 'duplicateinvite',
                    ));
                    exit;
                }
            } else {
                echo json_encode(array(
                    'output' => 'usernameExists',
                ));
                exit;
            }
        }
        return $result;
    }
    
    public function validemailsubmissionAction() {
        
        $userEmail = array();
        if (isset($_POST['email']) && $_POST['email'] != '') {
            // Check email
            
            $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");
            $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
            $newEmailData = $zfcuserMapperObj->findByEmail($_POST['email']);
            
            if (count($newEmailData) == 0) {
                $service = $this->getUserService();
                $user = $service->updateValidEmail($_POST['email'],$userId);
                if ($user != "") {
                    // new login details email
                    
                    $passwordArr = explode('@',$user->getEmail());
                    $password = $passwordArr[0];
                    
                    $filepath= __DIR__ . '../../../../view/mailer/';
                    if($user->getUserTypeId() == 1){
                        $filepath = $filepath.'welcomemailstudent.html';
                        $file_content = file_get_contents($filepath);
                    }
                   /////////////////  End ///////////////////////////////
                    
                    ///////// Parent register, Get this html content ///////
                    if($user->getUserTypeId() == 2){
                        $filepath = $filepath.'welcomemailparent.html';
                        $file_content = file_get_contents($filepath);
                    }

                   ///////// Mentor/Teacher register, Get this html content ///////
                    if($user->getUserTypeId() == 3){
                        $filepath = $filepath.'welcomemailmentor.html';
                        $file_content = file_get_contents($filepath);
                    }

                    $event = $this->getEvent();
                    $requestURL = $event->getRequest();
                    $router = $event->getRouter();
                    $uri = $router->getRequestUri();
                    $baseUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
                    
                    $regMessage = str_replace('{STUDENT_NAME}', $user->getDisplayName(), $file_content);
                    $regMessage = str_replace('{STUDENT_USER ID}', $user->getEmail(), $regMessage);
                    $regMessage = str_replace('{STUDENT_PASSWORD}', $password, $regMessage);
                    $regMessage = str_replace('{ACTIVATIONLINK}', $baseUrl . "/user/change-password?id=" . $user->getId() . "&token=" . $password, $regMessage);
                    $regMessage = str_replace('{BASE_URL}', $baseUrl , $regMessage);

                    $regSubject= "Registration confirmation";
                    // sent Registration Email
                    $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent'); 
                    $emailData = array("email_id" => $user->getEmail(), 'subject' => $regSubject, 'message' => $regMessage, 'mail_type' => 'registration', 'status' => 1);
                    
                    $mailContentTable->addEmailContent($emailData);

                    echo json_encode(array(
                        'output' => 'success'
                    ));
                    exit;

                } else {
                    echo json_encode(array(
                        'output' => 'notsuccess',
                    ));
                    exit;
                }
            } else {
                echo json_encode(array(
                    'output' => 'emailExists',
                ));
                exit;
            }
        }
        return $result;
    }
    
    public function linkchildAction() {        
        $userEmail = array();
        //echo '<pre>'; print_r($_POST); exit;
        if (isset($_POST['linkChildUserId']) && $_POST['linkChildUserId'] != '') {
            $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");
        
            $countemail = $zfcuserMapperObj->findByEmail($_POST['linkChildUserId']);
            //echo '<pre>';print($countemail->getuserTypeId());die('vikash');
            if (count($countemail) == 1) {
                $authUser = $this->zfcUserAuthentication()->getIdentity();
                $parentId = $authUser->getId();
                $userTypeId= $authUser->getuserTypeId();
                $emailId = $authUser->getEmail();
                if(trim($emailId) == trim($_POST['linkChildUserId'])) {
                    echo json_encode(array(
                            'output' => 'self',
                        ));
                    exit;
                }
                if($countemail->getuserTypeId()=='3' && $userTypeId=='2'){
                    echo json_encode(array(
                            'output' => 'cannotaddmentor',
                        ));
                    exit;
                }
                $parentDetails = $zfcuserMapperObj->findById($parentId);
                
                $childdetail =  $countemail;
                $childId = $countemail->getId();
                
//                $user = $service->registerChild($_POST,$parentId);
                
                $table = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
               
             //add parent child relation request in t_parent_child
                $user = $table->addrelation($parentId,$childId,'child');
                if($user=='0'){
                    
                    echo json_encode(array(
                            'output' => 'exists',
                        ));
                    exit;
                }
                     $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
                 
                   $notification = $notificationtable->getnotification('',$user);
                   if(count($notification)>'0'){
                      foreach($notification as $notify){
                       $notifydata = array(
                      'notification_status' => '2',
                     ); 
                       $notificationtable->updateStatus($notify->notification_id,$notifydata);
                      }
                   }
                
                
                if ($user != "") {
                    if($user !='0'){
                    $redirect_url = '';
                     $_SESSION['redirecturl'] = $_SERVER['HTTP_REFERER'];
                    if (isset($_SESSION['redirecturl']) && $_SESSION['redirecturl'] != '') {
                        $redirect_url = $_SESSION['redirecturl'];
                    }
                    
                                 
                   // add request notification 
                           $notificationdata = array(
                    'notification_text' => $parentDetails->getDisplayName().'&nbsp;has sent you request to be his child',
                    'userid' => $childId,
                     'type_id' => '2',    // group
                     'relation_id'=> $user,
                     'notification_url' => 'my-parent/2',
                     'created_by' => $parentId,
                         'created_date'  	=> date('Y-m-d H:i:s'),	
                  );
                    
                  $notificationtable->insertnotification($notificationdata);
                    
                    
                    echo json_encode(array(
                        'output' => 'success',
                        'message' => 'Child has been linked successfully',
                        'redirect_url' => $redirect_url
                    ));
                    exit;
//                    $result = new JsonModel(array(
//                        'output' => 'success',
//                        'message' => 'Child has been linked successfully',
//                        'redirect_url' => $redirect_url
//                    ));
                }else{
                    
                    echo json_encode(array(
                            'output' => 'exists',
                        ));
                    exit;
//                    $result = new JsonModel(array(
//                                'output' => 'exists',
//                            ));
                }                
                } else {
                    echo json_encode(array(
                            'output' => 'notsuccess',
                        ));
                    exit;
//                    $result = new JsonModel(array(
//                                'output' => 'notsuccess',
//                            ));
                }
            } else {
                echo json_encode(array(
                            'output' => 'emailNotExists',
                        ));
                exit;

//                $result = new JsonModel(array(
//                            'output' => 'emailNotExists',
//                        ));
            }
        }
        return $result;
    }
    
    
    /**
     * Change the users password
     */
    public function changepasswordAction() {
        // if the user isn't logged in, we can't change password
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
            $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
        $tablestudent = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');

        // This function get the all mentors of student.
        $addedMentor = $tablestudent->getAll($userId, 'student');
        $addedMentors = $addedMentor->buffer();


        // This function get the all students of loged mentor.
        $addedStudent = $tablestudent->getAll($userId, 'mentor');
        $addedStudents = $addedStudent->buffer();
        $form = $this->getChangePasswordForm();
        $prg = $this->prg(static::ROUTE_CHANGEPASSWD);
        
        $fm = $this->flashMessenger()->setNamespace('change-password')->getMessages();
        if (isset($fm[0])) {
            $status = $fm[0];
        } else {
            $status = null;
        }
        
            if ($prg instanceof Response) {
                return $prg;
            } elseif ($prg === false) {
                return array(
                    'status' => $status,
                    'changePasswordForm' => $form,
                    'addedMentors' => $addedMentors,
                    'addedStudents' => $addedStudents,
                );
            }
        
        $form->setData($prg);
        
            if (!$form->isValid()) {
                return array(
                    'status' => false,
                    'changePasswordForm' => $form,
                     'addedMentors' => $addedMentors,
                    'addedStudents' => $addedStudents,
                );
            }
        
        if (!$this->getUserService()->changePassword($form->getData())) {
            return array(
                'status' => false,
                'changePasswordForm' => $form,
                 'addedMentors' => $addedMentors,
                'addedStudents' => $addedStudents,
            );
        }

        $this->flashMessenger()->setNamespace('change-password')->addMessage(true);
            return $this->redirect()->toRoute(static::ROUTE_CHANGEPASSWD);
    }
    
    
    
    public function changeEmailAction() {
        // if the user isn't logged in, we can't change email
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }

        $form = $this->getChangeEmailForm();
        $request = $this->getRequest();
        $request->getPost()->set('identity', $this->getUserService()->getAuthService()->getIdentity()->getEmail());

        $fm = $this->flashMessenger()->setNamespace('change-email')->getMessages();
        if (isset($fm[0])) {
            $status = $fm[0];
        } else {
            $status = null;
        }

        $prg = $this->prg(static::ROUTE_CHANGEEMAIL);
        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'status' => $status,
                'changeEmailForm' => $form,
            );
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            return array(
                'status' => false,
                'changeEmailForm' => $form,
            );
        }

        $change = $this->getUserService()->changeEmail($prg);

        if (!$change) {
            $this->flashMessenger()->setNamespace('change-email')->addMessage(false);
            return array(
                'status' => false,
                'changeEmailForm' => $form,
            );
        }

        $this->flashMessenger()->setNamespace('change-email')->addMessage(true);
        return $this->redirect()->toRoute(static::ROUTE_CHANGEEMAIL);
    }

    /**
     * Getters/setters for DI stuff
     */
    public function getUserService() {
        if (!$this->userService) {
            $this->userService = $this->getServiceLocator()->get('zfcuser_user_service');
        }
        return $this->userService;
    }

    public function setUserService(UserService $userService) {
        $this->userService = $userService;
        return $this;
    }

    public function getRegisterForm() {
        if (!$this->registerForm) {
            $this->setRegisterForm($this->getServiceLocator()->get('zfcuser_register_form'));
        }
        return $this->registerForm;
    }
    
    public function setRegisterForm(Form $registerForm) {
        $this->registerForm = $registerForm;
    }
    
//    public function getProfileForm() {
//        if (!$this->profileForm) {
//            $this->setProfileForm($this->getServiceLocator()->get('zfcuser_profile_form'));
//        }
//        return $this->profileForm;
//    }
//
//    public function setProfileForm(Form $profileForm) {
//        $this->profileForm = $profileForm;
//    }

    public function getLoginForm() {
        if (!$this->loginForm) {
            $this->setLoginForm($this->getServiceLocator()->get('zfcuser_login_form'));
        }
        return $this->loginForm;
    }

    public function setLoginForm(Form $loginForm) {
        $this->loginForm = $loginForm;
        $fm = $this->flashMessenger()->setNamespace('zfcuser-login-form')->getMessages();
        if (isset($fm[0])) {
            $this->loginForm->setMessages(
                    array('identity' => array($fm[0]))
            );
        }
        return $this;
    }

    public function getChangePasswordForm() {
        if (!$this->changePasswordForm) {
            $this->setChangePasswordForm($this->getServiceLocator()->get('zfcuser_change_password_form'));
        }
        return $this->changePasswordForm;
    }

    public function setChangePasswordForm(Form $changePasswordForm) {
        $this->changePasswordForm = $changePasswordForm;
        return $this;
    }

    /**
     * set options
     *
     * @param UserControllerOptionsInterface $options
     * @return UserController
     */
    public function setOptions(UserControllerOptionsInterface $options) {
        $this->options = $options;
        return $this;
    }

    /**
     * get options
     *
     * @return UserControllerOptionsInterface
     */
    public function getOptions() {
        if (!$this->options instanceof UserControllerOptionsInterface) {
            $this->setOptions($this->getServiceLocator()->get('zfcuser_module_options'));
        }
        return $this->options;
    }

    /**
     * Get changeEmailForm.
     *
     * @return changeEmailForm.
     */
    public function getChangeEmailForm() {
        if (!$this->changeEmailForm) {
            $this->setChangeEmailForm($this->getServiceLocator()->get('zfcuser_change_email_form'));
        }
        return $this->changeEmailForm;
    }

    /**
     * Set changeEmailForm.
     *
     * @param changeEmailForm the value to set.
     */
    public function setChangeEmailForm($changeEmailForm) {
        $this->changeEmailForm = $changeEmailForm;
        return $this;
    }

    public function myProfileAction() {

        $userdetails = array();
        $user = array();
        $nameChild = '';
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        $nav_array = array();

        if ($auth->hasIdentity()) {
            $userObj = $auth->getIdentity();
            
            
            $scnauthMapperObj = $this->getServiceLocator()->get("scnauth_mapper");
            $socialProvider = $scnauthMapperObj->findProvidersByUser($userObj);
            
//            if(count($socialProvider)>0){
//                foreach($socialProvider as $key => $val){
//                    $providerName = $key;
//                }
//            }
            
            $userid = $userObj->getId();
            if($userObj->getUserTypeId() == 10){ // admin redirect
                return $this->redirect()->toRoute('admin/misreport');
            }
            $tableuser = $this->getServiceLocator()->get('Assessment\Model\UserTable');
            $childData = $tableuser->getChildData($userid);
            $childDataCount = $tableuser->countChildData($userid);
            //$childDataCount = count($childData);
            if( $childDataCount == 0 ){
                $tableparentchild = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
                $childData = $tableparentchild->getChildData($userid);
                $childDataCount = $tableparentchild->countChildparentData($userid);
                //$childDataCount = count($childData);
                
            }
//            echo '<pre>';print_r ($childDataCount);echo '</pre>';die('vikash');
            $userLogObj = $this->getServiceLocator()->get("lms_container_mapper");
            
            $isClass = false;
            $classDataObj = $userLogObj->getContainer($userObj->getClassId());
            if(is_object($classDataObj)){
                if (is_object($classDataObj->getRackType())){
                    $isClass = ($classDataObj->getRackType()->getTypeName() == 'class')?true:false;
                }
            }
            
            $userLogCount = $userLogObj->getAllRecordCountUserLogs($userid);
            
            /* Boards */
            $boardList = $this->getService()->getBoardList();
            $valueOptions = array();
            //$valueOptions[''] = 'Select Board';
            foreach ($boardList as $container) {
                $valueOptions[$container->getRackId()] = $container->getRackName()->getName();
            }
            
            $tablestudent = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');

            // This function get the all mentors of student.
            $addedMentor = $tablestudent->getAll($userid, 'student');
            $addedMentors = $addedMentor->buffer();


            // This function get the all students of loged mentor.
            $addedStudent = $tablestudent->getAll($userid, 'mentor');
            $addedStudents = $addedStudent->buffer();

//            $userdetails = $user->buffer()->current();
            
            // This function get the chiled users of loged user.             
//            $userchild = $table->getuserdetailsChild($userid);
//            foreach ($userchild as $child) {
//                $nameChild .=$child->nameChild . '<br/>';
//            }
//            echo '<pre>';print_r($addedMentors);echo '</pre>';die('Macro Die');
            $childcount = 0;
            if($userObj->getUserTypeId() == 2){
                $usertable = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                $childData = $usertable->getChildData($userid); 
                $childcount = $usertable->countChildData($userid);
                //$childcount = count($childData);
            }
            $comMapperObj = $this->getServiceLocator()->get("com_mapper");
            $countryData = $comMapperObj->getAllCountries();

            $userCountryId = $userObj->getCountryId();
            $userStateId   = $userObj->getStateId(); 
            $userCity      = $userObj->getOtherCity(); 
            $statelist     = $comMapperObj->getCountarybystate($userCountryId);
            
            
            $viewModel = new ViewModel(
                            array(
                                'userObj' => $userObj,
                                'addedMentors' => $addedMentors,
                                'addedStudents' => $addedStudents,
                                'userLogCount' => $userLogCount,
                                'boardList' => $valueOptions,
                                'countryData' => $countryData,
                                'social' => count($socialProvider),
//                               'providerName' => $providerName,
                                'childDataCount' => $childDataCount,
                                'isClass' => $isClass,
                                'childcount'    => $childcount,
                                'states'        => $statelist,
                                'userCountryId' => $userCountryId,
                                'userStateId'   => $userStateId,
                                'userCity'      => $userCity
                                
                            )
            );

            return $viewModel;
        }
    }
    
    public function validemailAction() {

        $userdetails = array();
        $user = array();
        $nameChild = '';
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        $nav_array = array();

        if ($auth->hasIdentity()) {
            $userObj = $auth->getIdentity();
            
            
            $scnauthMapperObj = $this->getServiceLocator()->get("scnauth_mapper");
            $socialProvider = $scnauthMapperObj->findProvidersByUser($userObj);
            
            
            $userid = $userObj->getId();
            if($userObj->getUserTypeId() == 10){ // admin redirect
                return $this->redirect()->toRoute('admin/misreport');
            }
            $tableuser = $this->getServiceLocator()->get('Assessment\Model\UserTable');
            $childData = $tableuser->getChildData($userid);
            $childDataCount = $tableuser->countChildData($userid);
            //$childDataCount = count($childData);
            if( $childDataCount == 0 ) {
                $tableparentchild = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
                $childData = $tableparentchild->getChildData($userid);
                $childDataCount = $tableparentchild->countChildparentData($userid);
                //$childDataCount = count($childData);
            }

            $userLogObj = $this->getServiceLocator()->get("lms_container_mapper");
            
            $isClass = false;
            $classDataObj = $userLogObj->getContainer($userObj->getClassId());
            if(is_object($classDataObj)){
                if (is_object($classDataObj->getRackType())){
                    $isClass = ($classDataObj->getRackType()->getTypeName() == 'class')?true:false;
                }
            }
            
            $userLogCount = $userLogObj->getAllRecordCountUserLogs($userid);
            
            /* Boards */
            $boardList = $this->getService()->getBoardList();
            $valueOptions = array();
            //$valueOptions[''] = 'Select Board';
            foreach ($boardList as $container) {
                $valueOptions[$container->getRackId()] = $container->getRackName()->getName();
            }
            
            $tablestudent = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');

            // This function get the all mentors of student.
            $addedMentor = $tablestudent->getAll($userid, 'student');
            $addedMentors = $addedMentor->buffer();


            // This function get the all students of loged mentor.
            $addedStudent = $tablestudent->getAll($userid, 'mentor');
            $addedStudents = $addedStudent->buffer();


            $comMapperObj = $this->getServiceLocator()->get("com_mapper");
            $countryData = $comMapperObj->getAllCountries();            
            $viewModel = new ViewModel(
                            array(
                                'userObj' => $userObj,
                                 'addedMentors' => $addedMentors,
                                'addedStudents' => $addedStudents,
                                'userLogCount' => $userLogCount,
                                'boardList' => $valueOptions,
                                'countryData' => $countryData,
                                'social' => count($socialProvider),
//                                'providerName' => $providerName,
                                'childDataCount' => $childDataCount,
                                'isClass' => $isClass
                            )
            );

            return $viewModel;
        }
    }
    
    
    public function updateBoardClassAction() {
        //print_r($_POST);exit;
        if($_POST['urlRid'] != ''){
            $urlId = $_POST['urlRid'];
        }else{
           $urlId = ''; 
        }
        unset($_POST['urlRid']);
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        $userObj = $auth->getIdentity();
        
       
        $service = $this->getUserService();
        $user = $service->updateBoardClass($_POST);
        
        if(isset($_POST['otherboardname']) && $_POST['otherboardname']!=''){
            $tableuserother = $this->getServiceLocator()->get('Assessment\Model\UserOtherDetailsTable');
            $data['user_id'] = $userObj->getId();
            $data['key_name'] = "other_board";
            $data['value'] = $_POST['otherboardname'];
            $tableuserother->insertUserOtherDetails($data);
        }
        if($urlId == ''){
            echo $user;
        }else{
            echo 2;
        }
        die;
        
    }
    
    public function socialDataCaptureAction() {
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        $userObj = $auth->getIdentity();
        
        $postData = $this->getRequest()->getPost();
        $email = $postData['emailId']; 
        
        $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");
        
        $countemail = $zfcuserMapperObj->findByEmail($email);
        if (count($countemail) > 0) {
            if($countemail->getMobile() != NULL){
                echo json_encode( array ("output" => "mailExists") );
                exit;
            }
        }
        
        $service = $this->getUserService();
        
        $comMapperObj = $this->getServiceLocator()->get("com_mapper");
        $countryDataById = $comMapperObj->getCountryById($_POST['phonecode']);
        $countryDataArr = $countryDataById[0];
        $_POST['phone_code'] = $countryDataArr->getPhonecode();
        
        $user = $service->updateSocialData($_POST);
        echo json_encode( array ("output" => "success") );
        exit;
        
    }
 //public function changeprofileAction after login mentors checks image size and changes profile details based userId.It returns view page
//    public function changeprofileAction() {
////        echo '<pre>';var_dump($this->zfcUserAuthentication()->hasIdentity()); echo '</pre>';die('macro Die');
//        if ($this->zfcUserAuthentication()->hasIdentity()) {
////$comMapperObj = $this->getServiceLocator()->get("com_mapper");
////        $countryData = $comMapperObj->getAllCountries();
////        $boardList = $comMapperObj->getBoardList();
//           $form = $this->getProfileForm(); 
//           $form->bind($this->zfcUserAuthentication()->getIdentity());
//        }
//        echo '<pre>';print_r('llll'); echo '</pre>';die('macro Die');
//        return array(
//                'profileForm' => $form,
//            'userObj'=>$this->zfcUserAuthentication()->getIdentity(),
////            'countryData'=>$countryData,
////            'boardList'=>$boardList
//            );
//            $viewModel = new ViewModel(array(
//                'countries' => $countries,
//                'states' => $states,
//                'cities' => $cities,
//                'boards' => $_SESSION['boards'],
//                'classes' => $_SESSION['classes'],
//                'usertypes' => $usertypes,
//                'user' => $userdetails,
//                'fileerror' => $fileerror,
//                'nurseryarray' => $nurseryarray,
//                'addedMentors' => $addedMentors,
//                'addedStudents' => $addedStudents,
//            ));
//            return $viewModel;
//        
//    }
    
 public function changeprofileAction()
 {
   if ($this->zfcUserAuthentication()->hasIdentity())
    {
       
      $userid        = $this->zfcUserAuthentication()->getIdentity()->getId();
      $usertypeid    = $this->zfcUserAuthentication()->getIdentity()->getUserTypeId();
      if($usertypeid == 10){ // admin redirect
          return $this->redirect()->toRoute('admin/misreport');
      }
      $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
      $commonObj         = $this->getServiceLocator()->get("com_mapper");
      if($this->getRequest()->isPost())
       {
         $filename = '';
         $imageName = '';
         $fileerror = '';
         
        if(isset($_FILES['image_file']) && $_FILES['image_file']['name'] != ""){
            $Filedata = $commonObj->validateImage($_FILES);
            if(!is_array($Filedata))
            {
               if($Filedata == 'corrupt'){
                    return $this->redirect()->toUrl('changeprofile?error=tfalse');
               } else{
                   return $this->redirect()->toUrl('changeprofile?error=false');
               }
            }
        } 
        if($Filedata['image_file']['name'] != "" && $_POST['hidfile'] == "") {
             $filename  = str_replace(' ', '_', $Filedata['image_file']['name']);
             $file_type = substr(strrchr($Filedata['image_file']['name'], '.'), 1);
             $imageName = $_POST['hiduserid'] . '.' . $file_type;                                      
                  $fileUploaded = $this->ftpFileUploaded($Filedata['image_file']['tmp_name'],'uploads/profileimages/' . $imageName); 
                }else if ($_POST['hidfile'] != "" && $Filedata['image_file']['name'] == "") {
                  $imageName = $_POST['hidfile'];
                }else if($Filedata['image_file']['name'] != "" && $_POST['hidfile'] != "") {
                    unlink('public/uploads/profileimages/' . $_POST['hidfile']);
                    $filename = str_replace(' ', '_', $Filedata['image_file']['name']);
                    $file_type = substr(strrchr($Filedata['image_file']['name'], '.'), 1);
                    $imageName = $_POST['hiduserid'] . '.' . $file_type;                                        
                    $fileUploaded = $this->ftpFileUploaded($Filedata['image_file']['tmp_name'],'uploads/profileimages/' . $imageName);
                }
               $post       = $this->getRequest()->getPost();
//               $postdata   = $commonObj->escaper($post);
//               echo "<pre />"; print_r($postdata);exit;
               $table      = $this->getServiceLocator()->get('Assessment\Model\UserTable');
               $userdetail = $table->getuserdetailsForSubscription($post['hiduserid'],$usertypeid);
              
               foreach($userdetail as $user){
                   $username = $user->username;
               }    
                $update_status = $table->updateprofile($post,$imageName,$userid);
                if($post['allowschedule'] =='1' )
                {
                $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                $ParentData = $table->getParentData($userid); 
                if($ParentData->count()>0){
                    $classId        = $this->zfcUserAuthentication()->getIdentity()->getClassId();
                    $data = array(
                        'notification_text' =>  $this->zfcUserAuthentication()->getIdentity()->getDisplayName().'&nbsp; has allow to schedule plans ',
                        'userid' => $ParentData->current()->user_id,
                        'type_id' => '1',    
                        'notification_url' => 'calendarschedule/'.$classId.'/'.$userid,
                        'created_by'       => $userid,
                        'created_date'      => date('Y-m-d H:i:s'),	
                    );
                    $notificationtable->insertnotification($data);
                }
              }
                
                if ($update_status >= 0) {
                    if ($fileerror == '') {
                        return $this->redirect()->toUrl('myprofile?succ=true');
                    } else {
                        return $this->redirect()->toUrl('changeprofile?userid=' . $userid. "&fileerror=1");
                    }
                }
       }else{

        $tablestudent  = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
        
        $table  = $this->getServiceLocator()->get('Assessment\Model\UserTable');
        $user          = $table->getuserdetailsForSubscription($userid,$usertypeid);
       
        foreach($user as $userChildName) {
             $userdetails = $userChildName;             
             $userchild = $table->getuserdetailsChild($userid);
             foreach ($userchild as $child) {
                   $userdetails->nameChild .=$child->nameChild . ',';
                   }
               }
  
             
        $addedMentor   = $tablestudent->getAll($userid, 'student');
        $addedMentors  = $addedMentor->buffer();

        $addedStudent  = $tablestudent->getAll($userid, 'mentor');
        $addedStudents = $addedStudent->buffer();
        
                
        $comMapperObj  = $this->getServiceLocator()->get("com_mapper");
        $countryData   = $comMapperObj->getAllCountries();
        
        
//        if(!empty($userdetails->country_id)){
//            $countaryid = $userdetails->country_id;
//        }else{
//            foreach($countryData as $cdate){  
//              $countaryid = $cdate->getcountryid();
//             }
//        }
        
        if( !isset($userdetails->state_id) ||  !isset($userdetails->country_id)){
            
            if(!isset($_SESSION['user_session_ip_country_profile']) && !isset($_SESSION['user_session_ip_state_profile']) && !isset($_SESSION['user_session_ip_city_profile']) ){
                $tableIpCountry = $this->getServiceLocator()->get('Assessment\Model\IpCountryTable');
                $ip_address = $userdetails->ip;
                if(!isset ($ip_address)){
                    if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
                        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    }else{
                        $ip_address = $_SERVER['REMOTE_ADDR'];
                    }
                }
                $ipResultSet = $tableIpCountry->ipRange($ip_address);
                $ipCaptureDetails = $comMapperObj->useripcaptureeditprofile($ipResultSet);
                
            }
            if($_SESSION['user_session_ip_country_profile']){
                $countaryidDetails = $comMapperObj->getCountryIdByCountryName($_SESSION['user_session_ip_country_profile']);
                if(count($countaryidDetails) >0){
                    $countaryidDetailsNew = $countaryidDetails[0];
                    $countaryid = $countaryidDetailsNew->getCountryId();
                    $stateid = 0; $cityValue ='';
                    if($_SESSION['user_session_ip_state_profile'] != '0'){
                        $stateDetails = $comMapperObj->getStateIdByStateName($_SESSION['user_session_ip_state_profile']);
                        if( count($stateDetails) > 0)
                            $stateid = $stateDetails[0]->getStateId();
                        else
                            $stateid = 0;
                    }
                    if($_SESSION['user_session_ip_city_profile'] != '0')
                        $cityValue = $_SESSION['user_session_ip_city_profile'];
                }
            }
        }else{
            $countaryid = $userdetails->country_id;
            $stateid = $userdetails->state_id;
            $cityValue = $userdetails->other_city;
        }
        //echo '<pre>';print_r ();echo '</pre>';die('Vikash');
         // echo "<pre />"; print_r($userdetails); exit;
        $statelist    = $comMapperObj->getCountarybystate($countaryid);
        
         
        $boardList = $this->getService()->getBoardList();
        $valueOptions = array();
        $valueOptions[''] = 'Select Board';
        foreach ($boardList as $container) {
            $valueOptions[$container->getRackId()] = $container->getRackName()->getName();
        }
        
        if($userdetails->boardId == '0' || $userdetails->boardId == '1' || $userdetails->boardId == '2')
            $userdetails->boardId = '5554';
        $classList = $this->getService()->getchildList(($userdetails->boardId!='')?$userdetails->boardId:'5554'); 
         
        $ClassOptions = array();
        $ClassOptions[''] = 'Select Class';
        foreach ($classList as $container) {
            $ClassOptions[$container->getRackId()] = $container->getRackName()->getName();
        }
        $table  = $this->getServiceLocator()->get('Assessment\Model\TusertypeTable');
        $usertypes = $table->getuserTypes();
         //echo "<pre />"; print_r($userdetails);
         $viewModel = new ViewModel(array('addedMentors'  => $addedMentors,
                                          'addedStudents' => $addedStudents,
                                          'countries'     => $countryData,
                                          'states'        => $statelist,
                                          'countaryid'    => $countaryid,
                                          'stateid'       => $stateid,  
                                          'cityValue'     => $cityValue,
                                          'user'          => $userdetails,
                                          'boards'        => $valueOptions,
                                          'usertypes'     => $usertypes,
                                          'classes'       => $ClassOptions 
                               ));
         return $viewModel;
            }   
   } 
    
  }
  
 public function getstatesAction() 
  {
    if($this->zfcUserAuthentication()->hasIdentity())
     {       
       $userid = $this->zfcUserAuthentication()->getIdentity()->getId();
     }
    
        $comMapperObj  = $this->getServiceLocator()->get("com_mapper");  
        $html = "";
        if (isset($_POST['countryid'])) {
             $post   = $comMapperObj->escaper($_POST);
             $result = preg_replace("/[^a-zA-Z0-9]+/", "", $_POST['countryid']);
             $statess  = $comMapperObj->getCountarybystate((int)$result);
             
            $html.='<option value="">State</option>';
            foreach ( $statess as $statename) {
               
             $html.='<option value="' . $statename->getstateId() . '">' . strtoupper($statename->getstatename()).'</option>';
            }

            $result = new \Zend\View\Model\JsonModel(array(
                'output' => 'success',
                'success' => true,
                'statenames' => $html,
            ));
            return $result;
        }
    }

    
  public function getschoolsAction(){
		$comMapperObj  = $this->getServiceLocator()->get("com_mapper");  
		$html = array();
		if (isset($_POST['schoolNameValue'])) {
		    $ipCityValue = $_POST['ipCityValue'];
		    $userCityValue = $_POST['userCityValue'];
		    $limitRes = $_POST['limitRes'];
		    $city = NULL;
		    if($userCityValue !=''){
		        $city = $userCityValue;
		    }else if($ipCityValue != "0"){
		        $city = $ipCityValue;
		    }
		    $schoolName  = $comMapperObj->getSchoolNameFromERP($_POST['schoolNameValue'],$city,$limitRes);
		    foreach ($schoolName as $school) {
		       $emLogo = '';
		       if($school['schoolReportStatus'] == '4'){
		           $event = $this->getEvent();
		           $requestURL = $event->getRequest();
		           $router = $event->getRouter();
		           $uri = $router->getRequestUri();
		           $basepath = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());

		           //$emLogo[] = "<img height='20' align='absmiddle' src='" . $basepath . "/images/website/logo.png'>";
		           $htmlImage = $basepath . "/images/website/logo-25.png";
		           $htmlImageSchool = $basepath . "/images/website/dps-register.jpg";
		       }else{
		           $htmlImage = "";
		           $htmlImageSchool = "";
		       }
		       if((isset($school['areaName']) && $school['areaName'] != "" && $school['areaName'] == $city)){
		           $htmlText = $school['schoolName']." ,".$school['areaName'];
		       }else{
		           $htmlText = $school['schoolName'];
		       }
		       $html[] = array('text'=>$htmlText,'image'=>$htmlImage,'image_school'=>$htmlImageSchool,'schoolId'=>$school['schoolId']);
		    }
		    echo json_encode( array ("output" => $html) );
		    exit;
		}
	    }
  public function openregisterpopupAction(){
      $source = $this->getRequest()->getPost('source');         
      return array(
                'source' => $source
            );
  }
  public function openregisterpopupexternalAction(){
      $referer = "$_SERVER[HTTP_REFERER]";
      
      $config     = $this->getServiceLocator()->get('config');
      $sourceDetails = $config['registration_external_source'];
      $authorizedFlag = false;
      
      foreach($sourceDetails as $key => $value){
          if(strpos($referer,$value) !== false){
              $authorizedFlag = true;
          }
      }
      
      if($authorizedFlag === false){
          echo "<script> alert ('You are not authorized');</script>";
          die;
      }
      $source = $this->getRequest()->getPost('source');         
      return array(
                'source' => $source
            );
  }
    
 public function updateprofileAction() 
  {
     
        if ($this->zfcUserAuthentication()->hasIdentity()) {
          
            if (isset($_POST) && $_POST['hiduserid'] != "") {
                $filename = '';
                $imageName = '';
                $fileerror = '';
                if ($_FILES['image_file']['name'] != "" && $_POST['hidfile'] == "") {
                    $filename = str_replace(' ', '_', $_FILES['image_file']['name']);
                    $file_type = substr(strrchr($_FILES['image_file']['name'], '.'), 1);
                    $imageName = $_POST['hiduserid'] . '.' . $file_type;                                      
                    //move_uploaded_file($_FILES['image_file']['tmp_name'], 'public/uploads/profileimages/' . $imageName);
                    $fileUploaded = $this->ftpFileUploaded($_FILES['image_file']['tmp_name'],'uploads/profileimages/' . $imageName); 
                } else if ($_POST['hidfile'] != "" && $_FILES['image_file']['name'] == "") {
                    $imageName = $_POST['hidfile'];
                } else if ($_FILES['image_file']['name'] != "" && $_POST['hidfile'] != "") {
                    unlink('public/uploads/profileimages/' . $_POST['hidfile']);
                    $filename = str_replace(' ', '_', $_FILES['image_file']['name']);
                    $file_type = substr(strrchr($_FILES['image_file']['name'], '.'), 1);
                    $imageName = $_POST['hiduserid'] . '.' . $file_type;                                        
                    //move_uploaded_file($_FILES['image_file']['tmp_name'], 'public/uploads/profileimages/' . $imageName);
                    $fileUploaded = $this->ftpFileUploaded($_FILES['image_file']['tmp_name'],'uploads/profileimages/' . $imageName);
                }
                
                $table = $this->getServiceLocator()->get('ZfcUser\Model\TuserTable');
               $userdetail = $table->getuserdetails($_POST['hiduserid']);
              
               foreach($userdetail as $user){
                   $username = $user->username;
               }
                $table = $this->getServiceLocator()->get('ZfcUser\Model\TusertypeTable');
                // Get user type name
                $getuser_type_name = $table->getusertypename($_POST['usertype']);
                $_SESSION['user']['user_type_name'] = $getuser_type_name->name;
               
                $table = $this->getServiceLocator()->get('ZfcUser\Model\TuserTable');
                // Add user details
                $update_status = $table->addUser($_POST, '0', 'update', $imageName,'','',$username);
                if ($update_status >= 0) {
                    if ($fileerror == '') {
                        return $this->redirect()->toUrl('my-profile?succ=true');
                    } else {
                        return $this->redirect()->toUrl('change-profile?userid=' . $_SESSION['user']['userId'] . "&fileerror=1");
                    }
                }
            }
        }
    } 
  
 public function ftpFileUploaded($sourcePath, $targetPath)
  {
     $config     = $this->getServiceLocator()->get('config');
     $ftpDetails = $config['ftp_config'];
     $conn_id = ftp_connect($ftpDetails['FTP_SERVER']);        // set up basic connection        
     $login_result = ftp_login($conn_id, $ftpDetails['FTP_USERNAME'], $ftpDetails['FTP_PASSWORD']); // ftp login     
      if($login_result){
           $upload = ftp_put($conn_id, $targetPath, $sourcePath, FTP_BINARY);  // upload the file
             if (!$upload) {  // check upload status
                $fileStatus = 'error';
            }else {
                $fileStatus = 'success';
            }
        }else{
            $fileStatus = 'error';
        }       
        ftp_close($conn_id); // close the FTP stream     
        //echo "<pre />"; print_r($fileStatus);exit;
        return $fileStatus;
    } 
    
    public function forgotpasswordAction() {
        $result = new ViewModel();
        $result->setTerminal(true);
        return $result;
    }
    
    public function checkemailAction() {
        if (isset($_POST['email']) && $_POST['email'] != "") {
            $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");
        
            $countemail = $zfcuserMapperObj->findByEmail($_POST['email']);
            
//            $table = $this->getServiceLocator()->get('ZfcUser\Model\TuserTable');
//            // Check email is already exists or not
//            $countemail = $table->checkEmail($_POST['email'], 'user');
            if (count($countemail) > 0) {
                echo json_encode( array ("output" => "success") );
                    exit;
            } else {
                echo json_encode( array ("output" => "notsuccess") );
                    exit;
            }
        }
    }
    
    public function sentforgotrequestAction() {
//        global $forgotPasswordSubject;
//        global $frogotPasswordMessage;
        
        $filepath = __DIR__ . '../../../../view/mailer/';
        
        $filepath = $filepath.'forgotpasswordmessage.html';
        $frogotPasswordMessage = file_get_contents($filepath);
        $forgotPasswordSubject = "Request for new password on Extramarks";
        
        $event = $this->getEvent();
        $requestURL = $event->getRequest();
        $router = $event->getRouter();
        $uri = $router->getRequestUri();
        $baseUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
        
        if (isset($_POST['email']) && $_POST['email'] != "") {
            
            $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");
        
            $getUserdetais = $zfcuserMapperObj->findByEmail($_POST['email']);
            
//            $table = $this->getServiceLocator()->get('ZfcUser\Model\TuserTable');
//            // Get user details of loged user
//            $getUserdetais = $table->getuserid($_POST['email']);
            $username = ucfirst($getUserdetais->getDisplayName());
            $userid = $getUserdetais->getId();
            $to = $_POST['email'];
            $token = '';
            $token = $this->getUniqueCode('15');
            date_default_timezone_set('Asia/Kolkata');
            $date=date("d/m/Y");
            $time=date("h:i a");
            $tokenTime=date("Y-m-d H:i:s");
//            $config     = $this->getServiceLocator()->get('config');
//            $configUrlDetails = $config['urls'];
//            $baseUrl=$configUrlDetails['baseUrl'];
            //$baseUrl="http://localhost/emr/public";
                // Add unique token for user in forgot password table.
            //$tokenId = $tokentable->updteToken($id='',$getUserdetais->user_id, $token);
            $resetlinkurl = $baseUrl."/user/resetforgetpass?token=".$token; 
            //$updatePassword = $table->changepwd($token, $userid);
            $frogotPasswordMessage = str_replace("{FULLNAME}", "$username", $frogotPasswordMessage);
            $frogotPasswordMessage = str_replace("{ACTIVATIONLINK}", "$resetlinkurl", $frogotPasswordMessage);
            $frogotPasswordMessage = str_replace("{DATE}", "$date", $frogotPasswordMessage);
            $frogotPasswordMessage = str_replace("{TIME}", "$time", $frogotPasswordMessage);
            $frogotPasswordMessage = str_replace("{BASE_URL}", "$baseUrl", $frogotPasswordMessage);
          //$frogotPasswordMessage = str_replace("<EMILADDRESS>", "$to", $frogotPasswordMessage);            
             
              $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent'); 
              $emailData = array("email_id" => $to, 'subject' => $forgotPasswordSubject, 'message' => $frogotPasswordMessage, 'mail_type' => 'useractivities', 'status' => 1);
              $mailContentTable->addEmailContent($emailData);
             // Add unique token for user in forgot password table
                $tokentable = $this->getServiceLocator()->get('Psychometric\Model\TforgotpasswordtokensTable');     
              $addToken = $tokentable->addToken($id = '', $userid, $to, $token,$update='',$tokenTime);
              
              if ($addToken > 0) {
                    echo json_encode( array ("output" => "success") );
                    exit;
                } else {
                    echo json_encode( array ("output" => "notsuccess") );
                    exit;

                }
           
            /*if ($updatePassword > 0) {  
                if (sendMail($to, $forgotPasswordSubject, $frogotPasswordMessage)) {
                    $tokentable = $this->getServiceLocator()->get('ZfcUser\Model\TforgotpasswordtokensTable');
                    // Add unique token for user in forgot password table
                    $addToken = $tokentable->addToken($id = '', $getUserdetais->user_id, $getUserdetais->email_id, $token);
                    if ($addToken > 0) {
                        $result = new JsonModel(array(
                            'output' => 'success',
                        ));
                    } else {
                        $result = new JsonModel(array(
                            'output' => 'notsuccess',
                        ));
                    }
                }
            }*/
            
        }
    }
    
    public function valid_pass($password,$confirmPassword) {
        if(empty($password) || empty($confirmPassword)) return 'required';
        $passArr = explode(' ',$password);
        if(count($passArr) > 1) return 'space';
        if(strlen($password)<6) return 'length';
        if($password != $confirmPassword) return 'match';
        if(preg_match('/[^A-Za-z0-9\!,$@;#&()+:._]/', $password)) return 'special';
        return true;
    }
    
    function forgetpassvalidateAction() {
        if(isset($_POST) && $_POST['userid']!=''){
            $newpass = $_POST['newpwd'];
            $confirmpwd = $_POST['confirm-pwd'];
            $userid = $_POST['userid']; 
            $tokenid= $_POST['tokenid'];
            $tokentable = $this->getServiceLocator()->get('Psychometric\Model\TforgotpasswordtokensTable');
             // Add unique token for user in forgot password table.
            $tokenId = $tokentable->getTokenById($tokenid);
            if(count($tokenId) > 0) {
                $tokendetail = $tokenId->current();
                if($tokendetail->user_id == $userid && $tokendetail->status == '1') {
                    $validResponse = $this->valid_pass($newpass,$confirmpwd);
                    if(($validResponse == 'match' || $validResponse == 'length' || $validResponse == 'space' || $validResponse == 'special' || $validResponse == 'required') && $validResponse!='1') {
                        $responseArr = array(
                            'response' => $validResponse,
                            'status' => false,
                        );
                        echo json_encode($responseArr);
                        exit;
                    } else {
                        $responseArr = array(
                            'response' => $validResponse,
                            'status' => true,
                        );
                        echo json_encode($responseArr);
                        exit;
                    }
                } else {
                    $responseArr = array(
                        'response' => 'failed',
                        'status' => false,
                    );
                    echo json_encode($responseArr);
                    exit;
                    /*$result = new ViewModel(array(
                        'activelink'=>'0',
                        'output' => 'failed',
                        'msg'=>'Password reset failed!',
                    ));*/
                }
            } else {
                $responseArr = array(
                        'response' => 'failed',
                        'status' => false,
                    );
                echo json_encode($responseArr);
                exit;
            }
            
        }
    }
    
    function childpasswordcheckAction() {
        if(isset($_POST)){
            $newpass = $_POST['newpwd'];
            $confirmpwd = $newpass;
            $validResponse = $this->valid_pass($newpass,$confirmpwd);
            if(($validResponse == 'match' || $validResponse == 'length' || $validResponse == 'space' || $validResponse == 'special' || $validResponse == 'required') && $validResponse!='1') {
                $responseArr = array(
                    'response' => $validResponse,
                    'status' => false,
                );
                echo json_encode($responseArr);
                exit;
            } else {
                $responseArr = array(
                    'response' => $validResponse,
                    'status' => true,
                );
                echo json_encode($responseArr);
                exit;
            }
        }        
    }
    
    function resetForgetPassAction(){
       
          $filepath = __DIR__ . '../../../../view/mailer/';
       
          $filepath = $filepath.'resetpasswordMessage.html';
          $resetpasswordMessage = file_get_contents($filepath);
          
          $resetpasswordSubject = "Confirmation Email";
       
          if(isset($_POST) && $_POST['userid']!=''){
            $newpass = $_POST['newpwd'];
            $confirmpwd = $_POST['confirm-pwd'];
            $userid = $_POST['userid']; 
            $tokenid= $_POST['tokenid'];
            $tokentable = $this->getServiceLocator()->get('Psychometric\Model\TforgotpasswordtokensTable');
             // Add unique token for user in forgot password table.
            $tokenId = $tokentable->getTokenById($tokenid);
            if(count($tokenId) > 0){
                $tokendetail = $tokenId->current();
                
                if($tokendetail->user_id == $userid && $tokendetail->status == '1'){
                    $resetPwd = $this->getUserService()->resetPassword($newpass,$userid);

                    /*
                     * to send reset password confirmation email
                     */

                    $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");

                    $getUserdetais = $zfcuserMapperObj->findById($userid);

                    $username = ucfirst($getUserdetais->getDisplayName());

                    $to = $getUserdetais->getEmail();
                    date_default_timezone_set('Asia/Kolkata');
                    $date=date("d/m/Y");
                    $time=date("h:i a");
                    //$reqdate= date("d/m/Y h:i a",strtotime($tokendetail->token_time));
                    $reqdate= date("d/m/Y",strtotime($tokendetail->token_time));
                    $config     = $this->getServiceLocator()->get('config');
                    $event = $this->getEvent();
                    $requestURL = $event->getRequest();
                    $router = $event->getRouter();
                    $uri = $router->getRequestUri();
                    $baseUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());

                    $resetpasswordMessage = str_replace("{FULLNAME}", "$username", $resetpasswordMessage);

                    $resetpasswordMessage = str_replace("{DATE}", "$date", $resetpasswordMessage);
                    $resetpasswordMessage = str_replace("{TIME}", "$time", $resetpasswordMessage);
                    $resetpasswordMessage = str_replace("{REQDATE}", "$reqdate", $resetpasswordMessage);
                    $resetpasswordMessage = str_replace("{BASE_URL}", "$baseUrl", $resetpasswordMessage);


                    $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent'); 
                    $emailData = array("email_id" => $to, 'subject' => $resetpasswordSubject, 'message' => $resetpasswordMessage, 'mail_type' => 'useractivities', 'status' => 1);
                    $mailContentTable->addEmailContent($emailData);

                    /*
                     * End to send reset password confirmation email
                    */


                    $tokentable = $this->getServiceLocator()->get('Psychometric\Model\TforgotpasswordtokensTable');
                    $tokentable->addToken($tokenid, $userid, '' , '', 'update');
                     $result = new ViewModel(array(
                              'activelink'=>'0',
                              'output' => 'success',
                              'msg'=>'Password updated successfully',
                      ));
                }else{
                        $result = new ViewModel(array(
                                  'activelink'=>'0',
                                  'output' => 'failed',
                                  'msg'=>'Password reset failed!',
                          ));
                }
          }else{
              $result = new ViewModel(array(
                      'activelink'=>'0',
                      'output' => 'failed',
                      'msg'=>'Password reset failed!',
              ));
          }
            return $result;       
      
        }
        
        if(isset($_GET['token'])){
            $tokenid = $_GET['token'];
              $tokentable = $this->getServiceLocator()->get('Psychometric\Model\TforgotpasswordtokensTable');
             // Add unique token for user in forgot password table.
            $tokenId = $tokentable->getToken($tokenid);
            $tokendetail = $tokenId->current();
            
            if(count($tokendetail)>0 && $tokendetail->status==1){ 
                //echo date("h:i:s");
                date_default_timezone_set('Asia/Kolkata');
                $to_time = time();
                $from_time = strtotime($tokendetail->token_time);
                if($to_time > $from_time) {
                    $timeDiff= round(($to_time - $from_time)/ 60) ;
                } else {
                    $timeDiff=0;
                }
               // echo $timeDiff;
                if($timeDiff==0 || $timeDiff > 30) {
                    
                    $result = new ViewModel(array(
                        'activelink'=>'0',
                        'output' => 'failed',
                        'msg'=>'Reset Password time has been expired. Please try again.',
                  ));
                    return $result;
                  
                } else {
                
                        $tid = $tokendetail->id;
                        $userid = $tokendetail->user_id;
                        $email_id= $tokendetail->email_id;
                        $status= $tokendetail->status;              
                        $result = new ViewModel(array(
                              'activelink'=>'1',
                              'userid'=>$userid,
                              'tokenid'=>$tid,
                      ));
                          return $result;
                }   
               
               
            } else {
                
                $result = new ViewModel(array(
                      'activelink'=>'0',
                       'output' => 'failed',
                      'msg'=>'This is not valid link.',
                ));
                return $result;
            }
            
            
        }else {
            $result = new ViewModel(array(
                      'activelink'=>'0',
                      'output' => 'failed',
                      'msg'=>'This is not valid link.',
                ));
            return $result;
        }
       
   

    }
    
    public function getUniqueCode($length = "") {
        $code = md5(uniqid(rand(), true));
        if ($length != "")
            return substr($code, 0, $length);
        else
            return $code;
    }
    
    ///// Tablet API FOR TABLET ACTIVATION AND NEW USER REGISTRATION ////////
    public function tabletregisterAction(){
        global $Apivalidkey;
        $apikeyarr = array_keys($Apivalidkey);
        $insert_data = array();
            $event = $this->getEvent();
          
            $requestURL = $event->getRequest();
            $router = $event->getRouter();            
            $uri = $router->getRequestUri();
            $baseUrl =  $uri->getHost(). $requestURL->getBaseUrl();
        $data_value = $_REQUEST['data'];
        if ($data_value == '') {
            $result = new JsonModel(array(
                'status' => false,
                'message' => "Your provide data value",
            ));
            return $result;
        }

        $get_value = json_decode($data_value);
        $tabletdetails = $get_value->tablet_details;
        $licensedetails = $get_value->license_details;
        //echo '<pre>';print_r($licensedetails);echo '</pre>';die('Macro Die');

        $license_id = $licensedetails->license_id;
        $tablet_id = $licensedetails->tablet_id;
        ///$activation_key = $licensedetails->activation_key;
        $email_id = $licensedetails->email_id;
        $start_date = $licensedetails->start_date;
        $expiry_date = $licensedetails->expiry_date;
        $buffer_days = $licensedetails->buffer_days;

        $manufacturer = $tabletdetails->manufacturer;
        $model = $tabletdetails->model;
        $application_ver = $tabletdetails->app_version_name;
        $app_version_code = $tabletdetails->app_version_code;
        $operating_system = $tabletdetails->operating_system;
        $api_key = $_REQUEST['api_key'];
        $checksum = $_REQUEST['checksum'];

        if ($license_id == '') {
            $result = new JsonModel(array(
                'status' => false,
                'message' => "Please provide license id.",
            ));
            return $result;
        } else {
            $insert_data['license_id'] = $licensedetails->license_id;
        }
        if ($tablet_id == '') {
            $result = new JsonModel(array(
                'status' => false,
                'message' => "Please provide  tablet id.",
            ));
            return $result;
        } else {
            $insert_data['tablet_id'] = $licensedetails->tablet_id;
        }
        if ($manufacturer == '') {
            $result = new JsonModel(array(
                'status' => false,
                'message' => "Please provide tablet manufacturer.",
            ));
            return $result;
        } else {
            $insert_data['manufacturer'] = $tabletdetails->manufacturer;
        }
        if ($application_ver == '') {
            $result = new JsonModel(array(
                'status' => false,
                'message' => "Please provide application version.",
            ));
            return $result;
        } else {
            $insert_data['app_version_name'] = $tabletdetails->app_version_name;
        }
        if ($checksum == '') {
            $result = new JsonModel(array(
                'status' => false,
                'message' => "Please provide checksum value",
            ));
            return $result;
        }
        //$insert_data['activation_key'] =$licensedetails->activation_key;
        $insert_data['email'] = $licensedetails->email_id;
        $insert_data['start_date'] = $licensedetails->start_date;
        $insert_data['expiry_date'] = $licensedetails->expiry_date;
        $insert_data['buffer_days'] = $licensedetails->buffer_days;
        $insert_data['tablet_id'] = $licensedetails->tablet_id;

        $insert_data['model'] = $tabletdetails->model;

        $insert_data['user_type_id'] = 1;
       
            $operating_system = '';
            $model_number = ''; 
            $tablet_brand = '';       
            $tablet_config = '';
        
        //// Check api key is valid or not ///////////               
        if (!in_array($api_key, $apikeyarr)) {
            $result = new JsonModel(array(
                'status' => false,
                'message' => "Invalid api key.",
            ));
            return $result;
        }
        $salt = $Apivalidkey[$api_key];
        /// Create checksum  data for check valid data sting /////////
        $newchecksum = md5($salt . $manufacturer . $model . $license_id . $tablet_id . $start_date . $expiry_date . $app_version_code);
        /*if ($newchecksum != $checksum) {
            $result = new JsonModel(array(
                'status' => false,
                'message' => "Pass Data value not matched.",
            ));
            return $result;
        }*/
        
        //// CHECK API IS VALID OR NOT FOR THIS  WEBSERVICE
        if($apikeyarr[1]!=$api_key){ 
             $webservices_URL = 'http://tablic.extramarks.com/wsTrinfinWeb/service.asmx?wsdl';
                //$webservices_URL = 'http://115.112.128.13/wsTrinfinWeb/service.asmx?wsdl';
                $client = new Client($webservices_URL);
                $params = array(
                    'LicenseId' => $license_id,
                    'TabletId' => $tablet_id
                );

                $results = $client->GetLicenseDetails($params);                
                             
                if ($results->GetLicenseDetailsResult == 'INVALID_LICENSEID') {
                    $result = new JsonModel(array(
                       'status' => false,
                       'message'=> "Your license id is invalid",
                    ));
                       return $result; 
                   
                }else { 
                    $element = simplexml_load_string($results->GetLicenseDetailsResult);
                    $project_name = $element->dtLicenseDetails->ProjectName;                
                    $startdate = date('Y-m-d H:i', strtotime($element->dtLicenseDetails->StartDate));
                    $exprirydate = date('Y-m-d H:i', strtotime($element->dtLicenseDetails->ExpiryDate));
                    $activation_type = $element->dtLicenseDetails->ActivationType;
                    $buffer_days = $element->dtLicenseDetails->BufferDays;
                    //$license = $this->getServiceLocator()->get('ZfcUser\Model\TuserlicensedetailTable');
                    //$license_detail = $license->GetuserBylicenceID($license_id);
                 }
        }else{
                    $project_name=''; 
                    $startdate = $start_date;
                    $exprirydate = $expiry_date;
                    $activation_type = 'Online';
                    $buffer_days = $buffer_days;
        }
        
        ///////////// GET LICENSE details by license id ///////// 
        $license = $this->getServiceLocator()->get('Assessment\Model\TuserlicensedetailTable');
        $license_detail = $license->GetuserBylicenceID($license_id);
        
        $redirecturl = $baseUrl."/user/go-online/"; 
        
        if($email_id!=''){
            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
                if (!preg_match($regex, $email_id)) {                           
                   $result = new JsonModel(array(
                      'status' => false,
                     'message' => "Invalid  Email Address",
                    ));
                   return  $result;
                }
                    //// User already registered. Activation again after deactivation tablet.//// 
                if(!empty($license_detail)){                                                         
                            $userid = $license_detail->user_id;
                            $usertable = $this->getServiceLocator()->get('Assessment\Model\TuserTable');
                            $user_details = $usertable->getuserdetailsById($userid);
                            //echo '<pre>';print_r($user_details->current());echo '</pre>';die('Macro Die');
                             $email_id =$user_details->current()->emailId;
                             $username =$user_details->current()->username;
                             if(!empty($user_details)){
                                $result = new JsonModel(array(
                                'status'=>true, 
                                'message'=>'Activation successfully with same license id',
                                'redirecturl'=>$redirecturl,
                                'email_id' =>$email_id,
                                'username' =>$username,
                                'ask_for_password' =>true,   
                            ));
                                return $result;
                          }
                   } 
                /////////////// END Already registered ///////////////                 
                $usertable = $this->getServiceLocator()->get('Assessment\Model\TuserTable');
                $user_details = $usertable->getuserid($email_id);
                $this->storeLoginDetailInSession($email_id);
                        //////////// if user enter email id , This user is register on website.///////////
                         if(!empty($user_details)){
                            
                            if(empty($license_detail)){ 
                                 /////// Add license details ////////////
                                $data = array('user_id' => $user_details->user_id, 'license_id' => $license_id, 'tablet_id' => $tablet_id, 'manufacturer' => $manufacturer, 'application_ver' => $application_ver, 'project_name' => $project_name, 'start_date' => $startdate, 'expiration_date' => $exprirydate, 'buffer_days' => $buffer_days, 'activation_type' => $activation_type);
                                        $user_license_id = $license->addUserlicence($data);
                            }
                            $result = new JsonModel(array(
                             'status'=> true,
                             'message' => 'Your email Id is exist. Please insert password ',
                             'ask_for_password' =>true,
                            ));
                            return $result;
                         }else {
                                                            
                              ////// if user registered one tablet and  active with another tablet /////////////
                                 if(!empty($license_detail)){
                                                         
                                    $userid = $license_detail->user_id;
                                    $usertable = $this->getServiceLocator()->get('Assessment\Model\TuserTable');
                                    $user_details = $usertable->getuserdetailsById($userid);
                                   
                                       $email_id =$user_details->current()->emailId;
                                       $username =$user_details->current()->username;
                                            /***if(!empty($user_details)){
                                                $result = new JsonModel(array(
                                                'status'=>true, 
                                                'message'=>'Activation successfully with same license id',
                                                'redirecturl'=>$redirecturl,
                                                'email_id' =>$email_id,
                                                'username' =>$username,
                                            ));
                                                return $result;   

                                            }else{
                                                $userEmail = explode("@", $email_id);                                 
                                                $table = $this->getServiceLocator()->get('Assessment\Model\TuserTable');                                
                                               // Add user details to user table.........
                                               $user = $table->addUser($insert_data, $userEmail[0], 'user');
                                               $last_id = $user->user_id; 
                                            }*/
                                    
                                    if($license_detail->license_id==$license_id && $license_detail->tablet_id==$tablet_id){
                                        $result = new JsonModel(array(
                                        'status'=>true, 
                                        'message'=>'Activation successfully with same license id on same tablet',
                                         'redirecturl'=>$redirecturl,
                                        'email_id' =>$email_id,
                                    ));
                                        return $result;   
                                    }else{
                                        $data = array('user_id' => $userid, 'license_id' => $license_id, 'tablet_id' => $tablet_id, 'manufacturer' => $manufacturer, 'application_ver' => $application_ver, 'project_name' => $project_name, 'start_date' => $startdate, 'expiration_date' => $exprirydate, 'buffer_days' => $buffer_days, 'activation_type' => $activation_type,'operating_system'=>$operating_system,'model_number'=>$model_number,'tablet_brand'=>$tablet_brand,'tablet_config'=>$tablet_config,'tablet_mapped'=>1);
                                $user_license_id = $license->addUserlicence($data);
                                        $result = new JsonModel(array(
                                               'status'=>true, 
                                                'message'=>'New tablet activation successfully with same license id',
                                                'redirecturl'=>$redirecturl,
                                                'email_id' =>$email_id,
                                            ));
                                                return $result;
                                    }
                                    
                                    
                                    
                                 }else{
                                       $userEmail = explode("@", $email_id);                                 
                                       $table = $this->getServiceLocator()->get('Assessment\Model\TuserTable');                                
                                       // Add user details to user table.........
                                       $password = md5($userEmail[0]);
                                       $userdata = array('email'=>$email_id,'password'=>$password,'gender'=>'Male','user_type_id'=>1,'username'=>Null); 
                                       $user = $table->addUser($userdata);                                       
                                       $last_id = $user;
                                       
                                       if(empty($license_detail))
                                         {
                                           //// Some field is not in database.....
                                $data = array('user_id' => $last_id, 'license_id' => $license_id, 'tablet_id' => $tablet_id, 'manufacturer' => $manufacturer, 'application_ver' => $application_ver, 'project_name' => $project_name, 'start_date' => $startdate, 'expiration_date' => $exprirydate, 'buffer_days' => $buffer_days, 'activation_type' => $activation_type,'operating_system'=>$operating_system,'model_number'=>$model_number,'tablet_brand'=>$tablet_brand,'tablet_config'=>$tablet_config,'tablet_mapped'=>1);
                                                                        
                                $user_license_id = $license->addUserlicence($data);
                                         }
                                         $result = new JsonModel(array(
                                             'status' => true,
                                             'message' => "Your account  has been created successfully.you can login with email id",
                                             'ask_for_password' => false,
                                             'redirecturl'=>$redirecturl,
                                             'emailid' =>$email_id,
                                           ));
                                 return $result;
                                    }

                                
                            }
        }else{  /// WHEN USER NOT ENTER EMAIL ID FOR ACTIVATION TABLET.... 
        
                ////// when user already register in one tablet, it use another tablet.
                      if(!empty($license_detail)){     
                          $userid = $license_detail->user_id;
                          $usertable = $this->getServiceLocator()->get('Assessment\Model\TuserTable');
                          $user_details = $usertable->getuserdetailsById($userid);
                          //echo '<pre>';print_r($user_details->current());echo '</pre>';die('Macro Die');
                            if($license_detail->license_id==$license_id && $license_detail->tablet_id==$tablet_id){
                                
                                     $result = new JsonModel(array(
                                        'status'=>true, 
                                        'message'=>'Activation successfully with same license id',
                                        'redirecturl' => $redirecturl,
                                         'username' =>$license_id,
                                         'ask_for_password' =>true,
                                    ));
                                        return $result;   
                                
                                     }else{
                                        $data = array('user_id' => $userid, 'license_id' => $license_id, 'tablet_id' => $tablet_id, 'manufacturer' => $manufacturer, 'application_ver' => $application_ver, 'project_name' => $project_name, 'start_date' => $startdate, 'expiration_date' => $exprirydate, 'buffer_days' => $buffer_days, 'activation_type' => $activation_type,'operating_system'=>$operating_system,'model_number'=>$model_number,'tablet_brand'=>$tablet_brand,'tablet_config'=>$tablet_config,'tablet_mapped'=>1);
                                $user_license_id = $license->addUserlicence($data);
                                
                                $result = new JsonModel(array(
                                       'status'=>true, 
                                        'message'=>'New tablet activation successfully with same license id',
                                        'redirecturl' => $redirecturl,
                                        'username' =>$license_id,
                                    ));
                                        return $result;
                                    }
                                    
                         
                      }else {
                            //////// New user tablet activation //////
                            $userid = $license_id;
                            $password = md5($license_id);
                            $userdata['username'] = $license_id;
                            $userdata['password'] = $password;
                            $userdata['user_type_id'] = '1';
                            /*if ($board_id == '') {
                                $board_id = '';
                            }if ($class_id == '') {
                                $class_id = '';
                            }
                            $_POST['board'] = $board_id;
                            $_POST['class'] = $class_id;*/
                            
                            //$token = $this->getUniqueCode('5');                    

                            $usertable = $this->getServiceLocator()->get('Assessment\Model\TuserTable');
                            //$table = $this->getServiceLocator()->get('Assessment\Model\TuserTable');
                            $user_details = $usertable->getuserbyusername($userid);
                            
                            
                            if (empty($user_details)) {

                                //////// Add user details in user table.........
                                $userdata = array('email'=>Null,'password'=>$password,'gender'=>'Male','user_type_id'=>1,'username'=>$license_id); 
                                $user = $usertable->addUser($userdata);
                                $lastuser = $usertable->getuserbyusername($userid);                               
                                $last_id = $lastuser->user_id;
                                ////////// Add license details .............
                                if (empty($license_detail)) {
                                    $data = array('user_id' => $last_id, 'license_id' => $license_id, 'tablet_id' => $tablet_id, 'manufacturer' => $manufacturer, 'application_ver' => $application_ver, 'project_name' => $project_name, 'start_date' => $start_date, 'expiration_date' => $expiry_date, 'buffer_days' => $buffer_days, 'activation_type' => $activation_type, 'operating_system' => $operating_system, 'model_number' => $model_number, 'tablet_brand' => $tablet_brand, 'tablet_config' => $tablet_config,'tablet_mapped'=>1);
                                    $user_license_id = $license->addUserlicence($data);
                                }
                                $result = new JsonModel(array(
                                    'status' => true,
                                    'message' => "Your account  has been created successfully. You can login with license id.",
                                    'redirecturl' => $redirecturl,
                                    'username' => $license_id,
                                ));
                                return $result;
                            } else {
                                $lastuser = $usertable->getuserbyusername($userid);                               
                                $last_id = $lastuser->user_id;
                                if (empty($license_detail)) {
                                    ////////// Add license details .............
                                    $data = array('user_id' => $last_id, 'license_id' => $license_id, 'tablet_id' => $tablet_id, 'manufacturer' => $manufacturer, 'application_ver' => $application_ver, 'project_name' => $project_name, 'start_date' => $startdate, 'expiration_date' => $exprirydate, 'buffer_days' => $buffer_days, 'activation_type' => $activation_type, 'operating_system' => $operating_system, 'model_number' => $model_number, 'tablet_brand' => $tablet_brand, 'tablet_config' => $tablet_config,'tablet_mapped'=>1);
                                                                        
                                    $user_license_id = $license->addUserlicence($data);
                                }
                                $result = new JsonModel(array(
                                    'status' => true,
                                    'message' => "Your account  has been created successfully. You can login with license id.",
                                    'redirecturl' => $redirecturl,
                                    'username' => $license_id,
                                ));
                                return $result;
                            }
                        }
        }
        
        $result = new JsonModel($result);
        return $result;
    }
    
    /////////////// Confirm password for tablet api ///////////////////
    public function confirmpasswordAction() {
        
         global $Apivalidkey;         
        $apikeyarr =  array_keys($Apivalidkey);
        
        $data_value  = $_REQUEST['data'];
           if(empty($data_value)){
               $result = new JsonModel(array(
                  'status'=>'Please provide data value',              
                       )); 
               return $result;
           }
            $insert_data =array();
            $get_value = json_decode($data_value);

            $tabletdetails = $get_value->tablet_details;
            $licensedetails =$get_value->license_details;
               
               $license_id = $licensedetails->license_id;
                //$license_id = '19090449804';
               $tablet_id =$licensedetails->tablet_id;
              // $activation_key = $licensedetails->activation_key;
               $email_id   = $licensedetails->email_id;
               $start_date = $licensedetails->start_date;
               $expiry_date =$licensedetails->expiry_date;
               $buffer_days = $licensedetails->buffer_days;
               $password = $licensedetails->password;
                
               $manufacturer = $tabletdetails->manufacturer;
 	       $model =$tabletdetails->model;
               $application_ver =$tabletdetails->app_version_name;
               $app_version_code = $tabletdetails->app_version_code;
               $checksum   = $_REQUEST['checksum'];
               $api_key=$_REQUEST['api_key'];
               $_REQUEST['usertype'] =1;
               
                /////////////// Get license details By license id /////////////
                $license = $this->getServiceLocator()->get('Assessment\Model\TuserlicensedetailTable');
                $license_detail = $license->GetuserBylicenceID($license_id);
               
                $salt = $Apivalidkey[$api_key];
                if (!in_array($api_key, $apikeyarr)) {
                     $result = new JsonModel(array(
                        'status' => false,
                       'message'=> "Invalid api key.",
                    ));
                     return $result;
                }
           $newchecksum  = md5($salt.$manufacturer.$model.$license_id.$tablet_id.$start_date.$expiry_date.$app_version_code); 
         
            if($license_id==''){
                $result = new JsonModel(array(
                        'status' => false,
                       'message'=> "Please provide license id.",
                    ));
                       return $result;                 
            }if ($tablet_id==''){
                $result = new JsonModel(array(
                        'status' => false,
                       'message'=> "Please provide  tablet id.",
                    ));               
                return $result;
            }if($manufacturer==''){
                $result = new JsonModel(array(
                        'status' => false,
                       'message'=> "Please provide tablet manufacturer.",
                    ));                     
                return $result;
                
            }if($application_ver==''){
                $result = new JsonModel(array(
                        'status' => false,
                       'message'=> "Please provide application version.",
                    ));                     
                return $result;
            }if($checksum==''){
                 $result = new JsonModel(array(
                        'status' => false,
                       'message'=> "Please provide checksum value",
                    ));               
                return $result;
            }
            if(empty($password) || $password==''){
               //$license->updatemappedstatus($license_detail->id,0);
            }
            $operating_system = '';
            $model_number = ''; 
            $tablet_brand = '';       
            $tablet_config = '';
            
            if($newchecksum!=$checksum){
                  $result = new JsonModel(array(
                    'status' => false,
                    'message'=> "Invalid pass data value string.",
               ));
               return $result;
            }
            //else {
            if($apikeyarr[1]!=$api_key){
                 $webservices_URL = 'http://tablic.extramarks.com/wsTrinfinWeb/service.asmx?wsdl';
                //$webservices_URL = 'http://115.112.128.13/wsTrinfinWeb/service.asmx?wsdl';
                $client = new Client($webservices_URL);
                $params = array(
                    'LicenseId' => $license_id,
                    'TabletId' => $tablet_id
                );

                $results = $client->GetLicenseDetails($params);
              
                if ($results->GetLicenseDetailsResult =='INVALID_LICENSEID') {
                        $result = new JsonModel(array(
                            'status' => false,
                            'message' => "Your license id is invalid",
                        ));
                        return  $result;
                   
                }else {
                $element = simplexml_load_string($results->GetLicenseDetailsResult);
                $project_name = $element->dtLicenseDetails->ProjectName;
                $startdate = date('Y-m-d H:i', strtotime($element->dtLicenseDetails->StartDate));
                $exprirydate = date('Y-m-d H:i', strtotime($element->dtLicenseDetails->ExpiryDate));
                $activation_type = $element->dtLicenseDetails->ActivationType;
                $buffer_days = $element->dtLicenseDetails->BufferDays;
                    }
            }else { 
                $project_name=null; 
                $startdate = $start_date;
                $exprirydate = $expiry_date;
                $activation_type = 'Online';
                $buffer_days = $buffer_days;
            }
                /////////////// Get license details By license id /////////////
                //$license = $this->getServiceLocator()->get('ZfcUser\Model\TuserlicensedetailTable');
                //$license_detail = $license->GetuserBylicenceID($license_id);
                
                                ///////// when user regsitered or activate again //////////
                 if(!empty($license_detail)){                                                         
                                    $userid = $license_detail->user_id;
                                    $usertable = $this->getServiceLocator()->get('Assessment\Model\TuserTable');
                                    $user_details = $usertable->getuserdetailsById($userid);
                                    $user_value  = $user_details->current();
                                    
                                    $email_id =$user_value->emailId;
                                    $username =$user_value->username; 
                                            if ($email_id != '') {
                                                    $user['userEmail'] = $email_id;
                                                } else {
                                                    $user['userEmail'] = $username;
                                                }
                                                $user['userPassword'] = $password;                                                
                                                $user_details = $usertable->checkLogin($user);
                                                $password = md5($password);
                                                if ($user_details->password != $password) {
                                                    $license->updatemappedstatus($license_detail->id, 0);
                                                    $result = new JsonModel(array(
                                                        'status' => false,
                                                        'message' => "Your password did not match ,Please insert valid password",
                                                    ));
                                                    return $result;
                                                }else {
                                                    $license->updatemappedstatus($license_detail->id,1);
                                                     $result = new JsonModel(array(
                                                        'status' => true,
                                                        'message' => "Our Account has been merged on this email id",
                                                         'email_id' =>$email_id,
                                                         'username' =>$username,
                                                         
                                                    ));
                                                    return $result;
                                                }
                        }
                         ///////// END  //////////
                        
                /////////////// Get user details  by Email and password first time /////////////
                $usertable = $this->getServiceLocator()->get('Assessment\Model\TuserTable');
               if($email_id!=''){
                    $user['userEmail'] = $email_id;
               }else {
                    $user['userEmail'] = $license_id;
               }
                    $user['userPassword']= $password;
                    $user_details = $usertable->checkLogin($user);         
                
                $user_details->password;    
                $password = md5($password);
                    if($user_details->password!=$password){
                         
                        $license->updatemappedstatus($license_detail->id,0);                         
                        $result = new JsonModel(array(
                            'status' => false,
                            'message' => " Your password did not match ,Please insert valid password",
                        ));
                        return  $result;                        
                    }else {
                        if(empty($license_detail)){  
                                 /////// Add license details ////////////
                                        $data = array('user_id' => $user_details->user_id, 'license_id' => $license_id, 'tablet_id' => $tablet_id, 'manufacturer' => $manufacturer, 'application_ver' => $application_ver, 'project_name' => $project_name, 'start_date' => $startdate, 'expiration_date' => $exprirydate, 'buffer_days' => $buffer_days, 'activation_type' => $activation_type,'operating_system'=>$operating_system,'model_number'=>$model_number,'tablet_brand'=>$tablet_brand,'tablet_config'=>$tablet_config);
                                        $user_license_id = $license->addUserlicence($data); 
                                 }
                             
                             //////////////   If user enter right password, tablet_mapped field will be activate.///////////
                             $license->updatemappedstatus($license_detail->id,1);
                             
                           $result = new JsonModel(array(
                            'status' => true,
                            'message' => "Our Account has been merged on this email id",
                             'redirecturl'=>$redirecturl,
                             'email_id' =>$email_id,
                              
                        ));
                        return  $result;        
                        
                    }
                
                 //}
                
            //}
    }
    
    
     /////// Go online url for tablet //////////////////
    public function goOnlineAction(){
        global $Apivalidkey;
        $apikeyarr =  array_keys($Apivalidkey);
        $license_id = $_REQUEST['license_id'];
        $tablet_id = $_REQUEST['tablet_id'];
        $email_id = $_REQUEST['email_id'];
        $username = $_REQUEST['username'];
        $manufacturer = $_REQUEST['manufacturer'];
        $application_ver = $_REQUEST['application_ver'];
        $checksum = $_REQUEST['checksum'];
        $api_key=$_REQUEST['api_key'];
        
        
        $salt = $Apivalidkey[$api_key];
                if (!in_array($api_key, $apikeyarr)) {
                     $result = new JsonModel(array(
                        'status' => false,
                       'message'=> "Invalid api key.",
                    ));
                     return $result;
                }
        $newchecksum  = md5($salt.$license_id.$tablet_id.$email_id.$username.$manufacturer.$application_ver);
        
         /*if($newchecksum!=$checksum){
                $result = new JsonModel(array(
                    'status' => false,
                    'message' => "Invalid data string.",                   
                ));
                return $result;
                
                }*/
        
        if ($license_id == '') {
            $result = new JsonModel(array(
                'status' => false,
                'message' => "Please provide license id.",
            ));
            return $result;
        }if ($tablet_id == '') {
            $result = new JsonModel(array(
                'status' => false,
                'message' => "Please provide tablet id",
            ));
            return $result;
        } if ($manufacturer == '') {
            $result = new JsonModel(array(
                'status' => false,
                'message' => "Please provide tablet manufacturer.",
            ));
            return $result;
        } if ($application_ver == '') {
            $result = new JsonModel(array(
                'status' => false,
                'message' => "Please provide application version.",
            ));
            return $result;
        }
        
         $license = $this->getServiceLocator()->get('Assessment\Model\TuserlicensedetailTable');
         $license_detail = $license->GetuserBylicenceID($license_id);
           
            if(empty($license_detail)){
                $result = new ViewModel(array(
                    'status' => false,
                    'message' => 'User not registered',
                ));
                
                return $result;
            }
           
                  
             if(isset($email_id) && $email_id==''){
            if (isset($username) && $username == '') {
                $license = $this->getServiceLocator()->get('Assessment\Model\TuserlicensedetailTable');
                $license_detail = $license->GetuserBylicenceID($license_id);
                $userid = $license_detail->user_id;
                $usertable = $this->getServiceLocator()->get('Assessment\Model\TuserTable');
                $userdata = $usertable->getuserdetailsById($userid);
                $user_details = $userdata->current();
                $emailID = $user_details->emailId;
                $emailID = $user_details->username;
            }
        }
            
            $usertable = $this->getServiceLocator()->get('Assessment\Model\TuserTable');
            if(isset($email_id) && $email_id!=''){               
                $user_details = $usertable->getuserid($email_id);
                $userEmail = explode("@", $email_id);
                $emailID = $email_id;
                $password = $userEmail[0];
            }else if(isset($username) && $username!=""){ 
                $user_details = $usertable->getuserbyusername($username);
                 $emailID = $username;
                $password = $username;
            }
            $session = new Container('tabletuser');
           //echo '<pre>';print_r($license_detail->tablet_mapped);echo '</pre>';die('Macro Die');
                if($license_detail->tablet_mapped==0){
                    //$_SESSION['user']['tabletactive'] = $license_detail->tablet_mapped;
                     
                     $session->tabletactive='0';                   
                    if($user_details->email_id!=''){
                     //$_SESSION['user']['email'] = $user_details->email_id;
                      //setcookie("Email", $user_details->emailId);
                       $session->email=$user_details->emailId;
                    }else{
                       //$_SESSION['user']['username'] = $user_details->username;
                       //setcookie("user_name", $user_details->username);
                       $session->user_name=$user_details->username;
                    }
                     
                    return $this->redirect()->toRoute('home');
                    
                }else { 
                        //// auto login , when new user register////
                        if((count($user_details) > 0)){
                    $table = $this->getServiceLocator()->get('Assessment\Model\TusertypeTable');
                    if ($user_details->user_type_id != '') {      // Get User type name
                        $getuser_type_name = $table->getusertypename($user_details->user_type_id);
                    }
                //setcookie("user_name", $user_details->username);
                //setcookie("Email", $user_details->emailId);
                $session->email=$user_details->emailId;
                $session->user_name=$user_details->username;

                //setcookie("tabletactive", 0, time() + (86400 * 30), "/"); 
                //$_SESSION['user']['userId'] = $user_details->user_id;
                //$this->redirect()->toUrl($baseUrl . '/dashboard/1');
                //return $this->redirect()->toRoute('home');

                $result = new ViewModel(array(
                    'emailid' => $emailID,
                    'username' => $emailID,
                    'password' => $password,
                ));
                return $result;
            }
                }
                          
        //}

       
    }
    //// ask for password for tablet api uses/////
     public function askPasswordAction(){
         $result = new ViewModel();
        $result->setTerminal(true);
        return $result;
    }
    
    public function updatetabletmappedAction(){ 
        $userid = $this->getRequest()->getPost('user_id');
        $tmapped = $this->getRequest()->getPost('tablet_mapped');
        $license = $this->getServiceLocator()->get('Assessment\Model\TuserlicensedetailTable');
        $license->updatemappedstatusbyuserid($userid,1);
        die;
    }
    
    
    public function changeuserroleAction() {
        
        
         $params = $this->params()->fromPost();
         
         if( $params['userrole']==0) {
             $_SESSION['user_role']='';
         } else {
             
             $_SESSION['user_role']=$params['userrole'];
         }
         echo $_SESSION['user_role'];
         
         exit;
    }
    
    public function notifymentorAction()
     {
       (int)$type = $this->getRequest()->getPost('type');
       if($type = '1'){
        $mentorId = $this->zfcUserAuthentication()->getIdentity()->getId();
        $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
        $notificationdata = array(
                        'notification_text' =>'&nbsp;Add learner',
                        'userid' => $mentorId,
                        'type_id' => '2',    // group
                         'notification_url' => 'my-students',
                         'created_by' => $mentorId,
                         'created_date'  => date('Y-m-d H:i:s'),	
                   );
                    
        $res = $notificationtable->insertnotification($notificationdata);
        }else{
            $res = 0;
        }
        echo $res; die;
        
    }
    
      
    
}
