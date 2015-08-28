<?php

namespace Notification\View\Helper;

use Zend\Cache\StorageFactory;
use Zend\Session\SaveHandler\Cache;
use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

class SubjectSchedulerWidget extends AbstractHelper
{        
    /**
     * @var AuthenticationService
     */
    protected $authService;
    
    protected $objauth;
    
    protected $userPackageGateway;
    
    protected $lmsService;
    // protected $chacheGateway;
    
    /**
     * __invoke
     *
     * @access public
     * @param \ZfcUser\Entity\UserInterface $user
     * @throws \ZfcUser\Exception\DomainException
     * @return String
     */
    public function __invoke($navclass_id =null,$navchild_id=null,$navboard_id=null) {
        $usersubjects = array();
        $classId = $navclass_id;
           
        $userId  = ''; 
        if ($this->objauth->hasIdentity()) {
              $loggedIUserObj = $this->objauth->getIdentity();
              $userId         = $loggedIUserObj->getId();
              $userType       = $loggedIUserObj->getUserTypeId();
        }
        
        if($userType == '2' || $userType == '3')
           {
               $pChildId   = $navchild_id;
           }
           
        $userId     = (isset($pChildId))?$pChildId:$userId;
        
        $subjectDetails       = array();
        $subjectsresult       = $this->lmsService->getChildList($classId);
        foreach($subjectsresult as $key=>$detail)
        {
            $subjectDetails[$key]['subject_name'] = $detail->getRackName()->getName();
            $subjectDetails[$key]['id']           = $detail->getRackId();
            $subjectDetails[$key]['rack_type_id'] = $detail->getRackType()->getRackTypeId();
            $subjectDetails[$key]['language']     = $detail->getRackName()->getLanguage();
            
            if($detail->getContainerDetail()->count() > 0) {
                 $containerDetail = $detail->getContainerDetail();
                 foreach($containerDetail as $detaildata ) 
                  {
                    if($detaildata->getColKey()=='icon')
                        $subjectDetails[$key]['icon']='/images/icons/'.$detaildata->getValue();
                    if($detaildata->getColKey()=='color')
                             $subjectDetails[$key]['color']=$detaildata->getValue();
                  }                
            }
        }
       //echo "<pre />"; print_r($subjectDetails); 
        $userpackagessubjects = $this->userPackageGateway->getPackagesubjects($userId);
      
        if($userpackagessubjects->count())
        {
           foreach($userpackagessubjects as $userpackagessubject)
            {
                $explode = explode(',', $userpackagessubject->syllabus_id);
                foreach($explode as $val){
                        $usersubjects[]=$val;
                  }
             }
        }       
        $view = new ViewModel();
        $view = $this->getView()->render("notification/index/subjectscheduler.phtml",array('subjectDetails'=>$subjectDetails, 'navigationarray'=>$navclass_id,'usersubjects'=>$usersubjects,'user_id'=>$userId,'usertype'=>$userType,'custom_board_ids'=>$navboard_id)); //Default template is leftsubjectlist.phtml    
        
        return $view;
    }
    
    public function setTableGateway($tableGateway,$userPackagetableGateway)
    {
        $this->tableGateway       = $tableGateway;
        $this->userPackageGateway = $userPackagetableGateway;
    }
    
  /** User define function 1 **/  
    public function setAuthServices($objauth) 
      {
        $this->objauth = $objauth;
      }
      
    /** User define function 2 **/  
      public function setLmsServices($lmsService) 
      {
        $this->lmsService = $lmsService;
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

}

