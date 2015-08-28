<?php

namespace ZfcUserV0\Service;

use Zend\Authentication\AuthenticationService;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Crypt\Password\Bcrypt;
use Zend\Stdlib\Hydrator;
use ZfcBase\EventManager\EventProvider;
use ZfcUserV0\Mapper\UserInterface as UserMapperInterface;
use ZfcUserV0\Options\UserServiceOptionsInterface;
use Common\Entity\Country;
use Common\Entity\State;
use Lms\Entity\ResourceRack;
use ScnSocialAuth\Mapper\UserProviderInterface;
use ScnSocialAuth\Options\ModuleOptions;

class User extends EventProvider implements ServiceManagerAwareInterface {

    /**
     * @var UserMapperInterface
     */
    protected $userMapper;
    protected $commonMapper;
    
    /**
     * @var UserProviderInterface
     */
    protected $userProviderMapper = 'ScnSocialAuth-UserProviderMapper';
    /**
     * @var AuthenticationService
     */
    protected $authService;

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
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var UserServiceOptionsInterface
     */
    protected $options;

    /**
     * @var Hydrator\ClassMethods
     */
    protected $formHydrator;
    
    
    /**
     * set userProviderMapper
     *
     * @param  UserProviderInterface $mapper
     * @return HybridAuth
     */
    public function setUserProviderMapper(UserProviderInterface $userProviderMapper)
    {
        $this->userProviderMapper = $userProviderMapper;

        return $this;
    }

    /**
     * get mapper
     *
     * @return UserProviderInterface
     */
    public function getUserProviderMapper()
    {
        if (!$this->userProviderMapper instanceof UserProviderInterface) {
            $this->setUserProviderMapper($this->getServiceManager()->get('ScnSocialAuth-UserProviderMapper'));
        }

        return $this->userProviderMapper;
    }
    
    /**
     * createFromForm
     *
     * @param array $data
     * @return \ZfcUser\Entity\UserInterface
     * @throws Exception\InvalidArgumentException
     */
    public function register(array $data) {
        //echo '<pre>'; print_r($data); exit;
        $class = $this->getOptions()->getUserEntityClass();
        $user = new $class;
        $form = $this->getRegisterForm();
        $form->setHydrator($this->getFormHydrator());
        $form->bind($user);
        $data['country_id'] = trim($data['country_id'], '+');
        if(isset($data['school_name'])){
            $data['school_name'] = strip_tags($data['school_name']);
            $data['school_name'] = htmlspecialchars($data['school_name'], ENT_QUOTES);
        }
        $dobMonth = $data['dob_month'];
        $dobDay = $data['dob_day'];
        $dobYear = $data['dob_year'];
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
        $form->setData($data);
        if (!$form->isValid()) {
            return false;
        }
        $passwordArr = explode('@', $data['email']);
        $user = $form->getData();
        
        $password = $passwordArr[0];
        $user->setPassword(md5($password));

        $user->setUsername($data['email']);
        $user->setDob($dob);
        if($data['user_type_id'] == '1'){   
            $user->setSchoolName($data['school_name']);
            if (array_key_exists('school_id', $data)){
                $user->setSchoolId($data['school_id']);
            }
            $boardArr = explode('_',$data['board1']);
            
            $resourceRackClass = 'Lms\Entity\ResourceRack';
            $resourceRackObj  = new $resourceRackClass;

            $resourceRackObj->setRackId($data['class1']);
            $user->setClass($resourceRackObj);

            $resourceRackObj  = new $resourceRackClass;

            $resourceRackObj->setRackId($boardArr[0]);
            $user->setBoard($resourceRackObj);
        }
        
        // country
        $countryClass = 'Common\Entity\Country';
        $countryObj = new $countryClass;
        $countryObj->setCountryId($data['countryID']);
        $user->setCountry($countryObj);
        
        // state
        $stateClass = 'Common\Entity\State';
        $stateObj = new $stateClass;
        $stateObj->setStateId($data['stateID']);
        $user->setState($stateObj);
        
        // city
        $user->setOtherCity($data['cityValue']);
        
        //echo '<pre>';print_r ($user);echo '</pre>';die('Vikash');
        // If user state is enabled, set the default state value
        if ($this->getOptions()->getEnableUserState()) {
            if ($this->getOptions()->getDefaultUserState()) {
                $user->setState($this->getOptions()->getDefaultUserState());
            }
        }
        
        // save ip
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
//            $ip = $_SERVER['REMOTE_ADDR'] . ', ' . $_SERVER['HTTP_X_FORWARDED_FOR'];
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        $user->setIp($ip);   
        $user->setAllowschedule(0);   
        
        $user->setMobile("+".$data['phone_code'].'-'.$data['mobile']);
        
        
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $user, 'form' => $form));
        $this->getUserMapper()->insert($user);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user, 'form' => $form));
        $userDetails = $this->getUserMapper()->findByEmail($user->getEmail());
        $user->setId($userDetails->getId());
        
        if(isset($data['hiddenProvider'])){
            $localUserProvider = clone($this->getUserProviderMapper()->getEntityPrototype());
            $localUserProvider->setUserId($userDetails->getId())
                ->setProviderId($data['hiddenIdentifier'])
                ->setProvider($data['hiddenProvider']);
            $this->getUserProviderMapper()->insert($localUserProvider);
        }
            
        return $user;
    }

    public function registerCap(array $data) {

        $class = $this->getOptions()->getUserEntityClass();
        $user = new $class;


//        $bcrypt = new Bcrypt;
//        $bcrypt->setCost($this->getOptions()->getPasswordCost());

//        $user->setPassword($bcrypt->create($data['password']));
        $user->setPassword(md5($data['password']));
        $user->setUsername($data['email']);
        $user->setEmail($data['email']);
        $user->setDisplayName($data['display_name']);
        $user->setGender($data['gender']);
        $user->setUserTypeId($data['user_type_id']);
        $user->setPhone($data['phone']);
//        $user->setClassId($data['class_id']);
        $user->setAllowschedule(0);
        $resourceRackClass = 'Lms\Entity\ResourceRack';
        $resourceRackObj  = new $resourceRackClass;
        
        $resourceRackObj->setRackId($data['class_id']);
        $user->setClass($resourceRackObj);
        
        $resourceRackObj  = new $resourceRackClass;
        
        $resourceRackObj->setRackId($data['board_id']);
        $user->setBoard($resourceRackObj);
        
        
        $user->setDob($data['dob']);
        if(isset($data['school_name'])){
            $data['school_name'] = strip_tags($data['school_name']);
            $data['school_name'] = htmlspecialchars($data['school_name'], ENT_QUOTES);
        }
        $user->setSchoolName($data['school_name']);
        $user->setProductType($data['product_type']);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $user, 'form' => $form));
        $this->getUserMapper()->insert($user);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user, 'form' => $form));
        return $user;
    }

    public function registerChild(array $data, $parentId) {
        $class = $this->getOptions()->getUserEntityClass();
        $user = new $class;


//        $bcrypt = new Bcrypt;
//        $bcrypt->setCost($this->getOptions()->getPasswordCost());

//        $user->setPassword($bcrypt->create($data['childPassword']));
        $user->setPassword(md5($data['childPassword']));
        $user->setUsername($data['childUserId']);
        $user->setEmail($data['childUserId']);
        $user->setDisplayName($data['childName']);
        if(isset($data['school_name'])){
            $data['school_name'] = strip_tags($data['school_name']);
            $data['school_name'] = htmlspecialchars($data['school_name'], ENT_QUOTES);
        }
        $user->setSchoolName($data['school_name']);
        $user->setGender($data['gender']);
        $user->setUserTypeId(1);
        $user->setAllowschedule(0);
        $resourceRackClass = 'Lms\Entity\ResourceRack';
        $resourceRackObj  = new $resourceRackClass;
        
        $resourceRackObj->setRackId($data['boardChild']);
        $user->setBoard($resourceRackObj);
        
        $resourceRackObj  = new $resourceRackClass;
        $resourceRackObj->setRackId($data['classChild']);
        $user->setClass($resourceRackObj);
        
//        $user->setBoardId($data['boardChild']);
//        $user->setClassId($data['classChild']);
        $user->setParentId($parentId);
        $user->setAge($data['childAge']);
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        $user->setIp($ip);
        
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $user));
        $this->getUserMapper()->insert($user);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $user));
        return $user;
    }
    public function updateValidEmail($email,$userId) {
        
        $currentUser = $this->getAuthService()->getIdentity();
        $passwordArr = explode('@', $email);
        
        $password = $passwordArr[0];
        $currentUser->setPassword(md5($password));
        $currentUser->setUsername($email);
        $currentUser->setEmail($email);
        $currentUser->setValidEmail(NULL);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $currentUser));
        return $currentUser;
    }

    public function updateBoardClass(array $data) {
//        echo '<pre>';print_r ($data);echo '</pre>';die('vikash');
        $currentUser = $this->getAuthService()->getIdentity();

        $boardId = $data['boardId'];
        $classId = $data['classId'];

//        $boardClass = 'Lms\Entity\ResourceRack';
//        $boardClassObj  = new $boardClass;
//        $boardClassObj->setRackId($boardId);
//        
        $currentUser->setBoardId($boardId);
        $currentUser->setClassId($classId);
        
//        $boardClassObj->setRackId($classId);
//        $currentUser->setClass($boardClassObj);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $currentUser));

        return true;
    }

    public function updateCapData(array $data) {
//        echo '<pre>';print_r ($data);echo '</pre>';die('vikash');
        $currentUser = $this->getAuthService()->getIdentity();

        $displayName = $data['first_name'] . ' ' . $data['last_name'];

        $currentUser->setDisplayName($displayName);
        $currentUser->setClassId($data['class_id']);
        $currentUser->setBoardId($data['board_id']);
        $currentUser->setDob($data['dob']);
        if(isset($data['school_name'])){
            $data['school_name'] = strip_tags($data['school_name']);
            $data['school_name'] = htmlspecialchars($data['school_name'], ENT_QUOTES);
        }
        $currentUser->setSchoolName($data['school_name']);
        $currentUser->setGender($data['gender']);
        $currentUser->setPhone($data['mobile']);
//        $boardClassObj->setRackId($classId);
//        $currentUser->setClass($boardClassObj);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $currentUser));

        return true;
    }

    public function updateSocialData(array $data) {
        $currentUser = $this->getAuthService()->getIdentity();
        $data['country_id'] = trim($data['phonecode'], '+');
        
        if(!$currentUser->getEmail()){
            $currentUser->setEmail($data['emailId']);
        }
        $currentUser->setCountryId($data['country_id']);
        $currentUser->setUserTypeId($data['usertype']);
        $currentUser->setMobile("+".$data['phone_code'].'-'.$data['phone']);
        $currentUser->setGender($data['sex']);
        
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $currentUser));

        return true;
    }
    
    public function updateIpCaptureUser(array $data) {
        $currentUser = $this->getAuthService()->getIdentity();
        
        $currentUser->setCountryId($data['countryID']);
        
        $stateClass = 'Common\Entity\State';
        $stateObj = new $stateClass;

        $stateObj->setStateId($data['stateID']);
//        echo '<pre>';print_r ($countryObj);echo '</pre>';die('vikash');
        $currentUser->setState($stateObj);
        
        
//        $currentUser->setState($data['stateID']);
        $currentUser->setOtherCity($data['cityValue']);
        
        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $currentUser));

        return true;
    }

    /**
     * change the current users password
     *
     * @param array $data
     * @return boolean
     */
    public function changePassword(array $data) {
        if(isset($data['childId']))
            $currentUser = $this->getUserMapper()->findById($data['childId']);
        else
            $currentUser = $this->getAuthService()->getIdentity();

        $oldPass = $data['credential'];
        $newPass = $data['newCredential'];

//        $bcrypt = new Bcrypt;
//        $bcrypt->setCost($this->getOptions()->getPasswordCost());

//        if (!$bcrypt->verify($oldPass, $currentUser->getPassword())) {
        if (md5($oldPass) != $currentUser->getPassword()) {
            return false;
        }

//        $pass = $bcrypt->create($newPass);
        $pass = md5($newPass);
        $currentUser->setPassword($pass);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $currentUser));

        return true;
    }
    public function changePasswordChild($newPass, $userid) {
        $currentUser = $this->getUserMapper()->findById($userid);

//        $bcrypt = new Bcrypt;
//        $bcrypt->setCost($this->getOptions()->getPasswordCost());

//        $pass = $bcrypt->create($newPass);
        $pass = md5($newPass);
        $currentUser->setPassword($pass);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $currentUser));

        return true;
    }
    
    
    public function resetPassword($newpass,$userid) {
        $currentUser = $this->getUserMapper()->findById($userid);
        
//        $bcrypt = new Bcrypt;
//        $bcrypt->setCost($this->getOptions()->getPasswordCost());


//        $pass = $bcrypt->create($newpass);
        $pass = md5($newpass);
        $currentUser->setPassword($pass);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $currentUser));

        return true;
    }
    
    
    public function changeEmail(array $data) {
        $currentUser = $this->getAuthService()->getIdentity();

//        $bcrypt = new Bcrypt;
//        $bcrypt->setCost($this->getOptions()->getPasswordCost());

//        if (!$bcrypt->verify($data['credential'], $currentUser->getPassword())) {
        if (md5($data['credential']) != $currentUser->getPassword() ) {
            return false;
        }

        $currentUser->setEmail($data['newIdentity']);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $currentUser));

        return true;
    }

    /**
     * getUserMapper
     *
     * @return UserMapperInterface
     */
    public function getUserMapper() {
        if (null === $this->userMapper) {
            $this->userMapper = $this->getServiceManager()->get('zfcuser_user_mapper');
        }
        return $this->userMapper;
    }
    
    public function getCommonMapper() {
        if (null === $this->commonMapper) {
            $this->commonMapper = $this->getServiceManager()->get('com_mapper');
        }
        return $this->commonMapper;
    }

    /**
     * setUserMapper
     *
     * @param UserMapperInterface $userMapper
     * @return User
     */
    public function setUserMapper(UserMapperInterface $userMapper) {
        $this->userMapper = $userMapper;
        return $this;
    }

    /**
     * getAuthService
     *
     * @return AuthenticationService
     */
    public function getAuthService() {
        if (null === $this->authService) {
            $this->authService = $this->getServiceManager()->get('zfcuser_auth_service');
        }
        return $this->authService;
    }

    /**
     * setAuthenticationService
     *
     * @param AuthenticationService $authService
     * @return User
     */
    public function setAuthService(AuthenticationService $authService) {
        $this->authService = $authService;
        return $this;
    }

    /**
     * @return Form
     */
    public function getRegisterForm() {
        if (null === $this->registerForm) {
            $this->registerForm = $this->getServiceManager()->get('zfcuser_register_form');
        }
        return $this->registerForm;
    }

    /**
     * @param Form $registerForm
     * @return User
     */
    public function setRegisterForm(Form $registerForm) {
        $this->registerForm = $registerForm;
        return $this;
    }

    /**
     * @return Form
     */
    public function getChangePasswordForm() {
        if (null === $this->changePasswordForm) {
            $this->changePasswordForm = $this->getServiceManager()->get('zfcuser_change_password_form');
        }
        return $this->changePasswordForm;
    }

    /**
     * @param Form $changePasswordForm
     * @return User
     */
    public function setChangePasswordForm(Form $changePasswordForm) {
        $this->changePasswordForm = $changePasswordForm;
        return $this;
    }

    /**
     * @param $username,$password
     * @return User
     */
//    public function adminAuthentication($username, $password) {
//
//
//        return $this->getUserMapper()->adminAuthentication($username, $password);
//    }

    public function updateAddress($address) {
        $currentUser = $this->getAuthService()->getIdentity();

        $currentUser->setAddress($address);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $currentUser));

        return true;
    }

    /**
     * get service options
     *
     * @return UserServiceOptionsInterface
     */
    public function getOptions() {
        if (!$this->options instanceof UserServiceOptionsInterface) {
            $this->setOptions($this->getServiceManager()->get('zfcuser_module_options'));
        }
        return $this->options;
    }

    /**
     * set service options
     *
     * @param UserServiceOptionsInterface $options
     */
    public function setOptions(UserServiceOptionsInterface $options) {
        $this->options = $options;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager() {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Return the Form Hydrator
     *
     * @return \Zend\Stdlib\Hydrator\ClassMethods
     */
    public function getFormHydrator() {
        if (!$this->formHydrator instanceof Hydrator\HydratorInterface) {
            $this->setFormHydrator($this->getServiceManager()->get('zfcuser_register_form_hydrator'));
        }

        return $this->formHydrator;
    }

    /**
     * Set the Form Hydrator to use
     *
     * @param Hydrator\HydratorInterface $formHydrator
     * @return User
     */
    public function setFormHydrator(Hydrator\HydratorInterface $formHydrator) {
        $this->formHydrator = $formHydrator;
        return $this;
    }
    
    
    public function updateTestimonialData($data) {
        $currentUser = $this->getAuthService()->getIdentity();
        $currentUser->setBoardId($data['board_id']);
        $currentUser->setClassId($data['class_id']);
        if(isset($data['school_name'])){
            $data['school_name'] = strip_tags($data['school_name']);
            $data['school_name'] = htmlspecialchars($data['school_name'], ENT_QUOTES);
        }
        $currentUser->setSchoolName($data['school_name']);
        $currentUser->setDisplayName($data['name']);

        $this->getEventManager()->trigger(__FUNCTION__, $this, array('user' => $currentUser));
        $this->getUserMapper()->update($currentUser);
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, array('user' => $currentUser));

        return true;
    }
}
