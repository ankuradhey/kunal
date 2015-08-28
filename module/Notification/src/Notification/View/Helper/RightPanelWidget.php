<?php

namespace Notification\View\Helper;

use Zend\Cache\StorageFactory;
use Zend\Session\SaveHandler\Cache;
use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

class RightPanelWidget extends AbstractHelper
{        
    /**
     * @var AuthenticationService
     */
    protected $authService;
    protected $serviceLocator;
    protected $tableGateway;
    
    /**
     * __invoke
     *
     * @access public
     * @param \ZfcUser\Entity\UserInterface $user
     * @throws \ZfcUser\Exception\DomainException
     * @return String
     */
    public function __invoke($class_id = null ,$navigationarray  = array(), $satPackage = null, $capPackage = null) 
    {
        $mentorStuArray = array();
        $childArray = array();
        $eventss = '';
        $overdue = '';
     
       if($this->objauth->hasIdentity()) {
                $loggedIUserObj = $this->objauth->getIdentity();
                $usertypeId     = $loggedIUserObj->getUserTypeId();
                $userId         = $loggedIUserObj->getId();
         }
         
         
        if(isset($userId) && $userId!=''){
      
            $userId = $userId;
            $usertypeId = $usertypeId;
            $navigationarray = $navigationarray;
            
             if(isset($_SESSION['user_role']) && $_SESSION['user_role']!=''){
                 $usertypeId =  $_SESSION['user_role'];                                                                                                                   }         
            
            if($usertypeId == 'Parent' || $usertypeId ==2)
             {
                $childData = $this->tableUser->getChildData($userId);
               //echo "<pre />"; print_r($childData); exit("xyz");
                $childArray = array();
                foreach($childData as $key => $aChild) {
                    $boardDetail = array();
                    $classDetail = array();
                //$boardDetail =  $this->tableGateway->getUserBoardAndClassName($aChild->board_id, '');
               // $classDetail    =  $this->tableGateway->getUserBoardAndClassName('', $aChild->class_id);
                  
                   
                $childArray[$key]['child_id']   = $aChild->user_id;
                $childArray[$key]['first_name'] = $aChild->firstName;
                $childArray[$key]['user_name']  = $aChild->username;
                $childArray[$key]['email_id']   = $aChild->emailId;
//            
             if(count($boardDetail)){
                $childArray[$key]->board_name = $boardDetail->boardName;
            }
            if(count($classDetail)){
                $childArray[$key]->class_name = $classDetail->className;
            }            
               
            $childArray[$key]['board_id'] = $aChild->boardId;
            
            $childArray[$key]['class_id'] = $aChild->classId;
         }
      
        }
        
          $studentData    = $this->mentorstudent->getAll($userId,'mentor','activestu');
          $mentorStuArray = array(); 
          foreach($studentData as $M_students)
           {
              $mentorStuArray[] = $M_students;
           }  
            $eventss = $this->getLessons->getLessons('1', 'progress', $userId,'cplan');
            $eventss->buffer(); 
            $overdue = $this->getLessons->getLessons('0', 'progress', $userId,'cplan');
            $overdue->buffer(); 
        }
    
        $class_name=$this->getServiceLocator()->get('lms_container_service')->getParentList($class_id);
        $view = new ViewModel();
        $view = $this->getView()->render("notification/index/rightpanel.phtml",array('usertype'=>@$usertypeId,"userid"=>@$userId, 'navigationarray' => $navigationarray,'childData'=>$childArray,'mentorStudentdata'=>$mentorStuArray,
                    'eventss' => $eventss,
                    'overdue' => $overdue,
                    "class_id"=>$class_id,
                    "satPackage" => $satPackage,
                    'capPackage' => $capPackage,
                    'class_name' => $class_name[1]['rack_name'],
           )); //Default template is leftsubjectlist.phtml    
        
        return $view;
        
    }
    
    public function setTableGateway($tableUser,$getLessons,$mentorstudent)
    {
//        $this->tableGateway  = $tableGateway;
            $this->tableUser     = $tableUser;
            $this->mentorstudent = $mentorstudent;
            $this->getLessons    = $getLessons;

        
    }
    
      public function setAuthServices($objauth) 
      {
        $this->objauth = $objauth;
      }
    
   /**
     * Get authService.
     *
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * Set authService.
     *
     * @param AuthenticationService $authService
     * @return \ZfcUser\View\Helper\ZfcUserDisplayName
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }
    
    public function setServiceLocator($serviceLocator){
        
        $this->serviceLocator = $serviceLocator;
    }
    
    public function getServiceLocator(){
        
        return $this->serviceLocator;
    }

}
