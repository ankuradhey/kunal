<?php

namespace Notification\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Cache\StorageFactory;
use Zend\Session\SaveHandler\Cache;
use Zend\Session\SessionManager;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;
use Zend\ViewModel\JsonModel;
use Zend\Escaper\Escaper;

class IndexController extends AbstractActionController 
{
    protected $userTable;
    protected $questionTable;
    protected $mentorPaperTable;
    protected $mentorQuestionTable;
    protected $mentorPaperQuestionTable;
    protected $mentorAssignPaperTable;
    protected $mentorPaperUserAnswerTable;

    function getUserTable() {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Assessment\Model\UserTable');
          }
        return $this->userTable;
    }

    function getQuestionTable() {
        if(!$this->questionTable) {
             $sm = $this->getServiceLocator();
             $this->questionTable = $sm->get('Assessment\Model\QuestionTable');
           }
        return $this->questionTable;
    }

    public function indexAction() 
    {
      return new ViewModel(array());
    }

   public function updatenotificationstatusAction(){
                    
     if(isset($_POST))
     {
        $updatestatus    = '0';
        $notificationId  = $_POST['notificationId'];
        $type            = $_POST['type'];
        if($type == 'seen')
         {
            $data = array('seen' =>'1','modified_date'=>date('Y-m-d h:i:s'));
            $notificationtable = $this->getServiceLocator()->get('notification\Model\NotificationTable');
            $updatestatus      = $notificationtable->updateStatus($notificationId,$data);                 
         }
         if($type == 'status')
         {
            $data              = array( 'notification_status' =>'2',);
            $notificationtable = $this->getServiceLocator()->get('notification\Model\NotificationTable');
            $updatestatus      = $notificationtable->updateStatus($notificationId,$data);               
         }
         if($updatestatus != '0') {
                 $result = new \Zend\View\Model\JsonModel(array(
                'output' => 'sucusses',));
         }else{
            $result = new \Zend\View\Model\JsonModel(array(
                      'output' => 'notsucusses',
                     ));
                }
            }        
         return $result;
    }
 

  public function mypaperAction() 
   {

        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $tablestudent = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');

            // This function get the all mentors of student.
            $addedMentor  = $tablestudent->getAll($this->zfcUserAuthentication()->getIdentity()->getId(), 'student');
            $addedMentors = $addedMentor->buffer();

            $addedStudent  = $tablestudent->getAll($this->zfcUserAuthentication()->getIdentity()->getId(), 'mentor');
            $addedStudents = $addedStudent->buffer();


            $mentorId        = $this->zfcUserAuthentication()->getIdentity()->getId();
            $mentorPaperList = $this->getMentorAssignPaperTable()->getMentorAssignedPaperList($mentorId);
            return new ViewModel(array(
                'mentorPaperList' => $mentorPaperList,
                'addedMentors' => $addedMentors,
                'addedStudents' => $addedStudents,
            ));
        }
    }
    
  public function chachegetChildList($param_id)
   {
      if(class_exists('Memcached')) 
       {       
            $cache = $this->getServiceLocator()->get('config');
            $memcached = StorageFactory::factory($cache['memcached']);
        }
       $containerservice   = $this->getServiceLocator()->get('lms_container_service');
      if(class_exists('Memcached')){
            if (($chapterdetails = $memcached->getItem('rackResourse-'.$param_id)) == FALSE) {
                 $chapterdetail  = $containerservice->getChildList($param_id);
                foreach($chapterdetail as $key=>$chdetail)
                {
                    $chapterdetails[$key]['chapter_name'] = $chdetail->getRackName()->getName();
                    $chapterdetails[$key]['id'] = $chdetail->getRackId();
                    $chapterdetails[$key]['rack_type_id'] = $chdetail->getRackType()->getRackTypeId();
               }
              $memcached->setItem('rackResourse-'.$param_id, $chapterdetails);  
            } 
             
         }else{
                $chapterdetail  = $containerservice->getChildList($param_id);
                foreach($chapterdetail as $key=>$chdetail)
                {
                    $chapterdetails[$key]['chapter_name'] = $chdetail->getRackName()->getName();
                    $chapterdetails[$key]['id'] = $chdetail->getRackId();
                    $chapterdetails[$key]['rack_type_id'] = $chdetail->getRackType()->getRackTypeId();
                }
            }
    
    
    return $chapterdetails;
      
   }
   
   public function chachegetSubjectChildList($param_id)
   {
      if(class_exists('Memcached')) 
       {       
            $cache = $this->getServiceLocator()->get('config');
            $memcached = StorageFactory::factory($cache['memcached']);
        }
    $containerservice   = $this->getServiceLocator()->get('lms_container_service');
  if (class_exists('Memcached')){
            if (($chapterdetails = $memcached->getItem('racksubjectResourse-'.$param_id)) == FALSE) {
                 $chapterdetail  = $containerservice->getChildList($param_id);//$chaptertable->getChaptersBySubjectId($subjectId);
                foreach($chapterdetail as $key=>$chdetail)
                {
                    $chapterdetails[$key]['subject_name'] = $chdetail->getRackName()->getName();
                    $chapterdetails[$key]['id'] = $chdetail->getRackId();
                    $chapterdetails[$key]['rack_type_id'] = $chdetail->getRackType()->getRackTypeId();
               }
              $memcached->setItem('racksubjectResourse-'.$param_id, $chapterdetails);  
            } 
             
         }else{
                $chapterdetail  = $containerservice->getChildList($param_id);//$chaptertable->getChaptersBySubjectId($subjectId);
                foreach($chapterdetail as $key=>$chdetail)
                {
                    $chapterdetails[$key]['subject_name'] = $chdetail->getRackName()->getName();
                    $chapterdetails[$key]['id'] = $chdetail->getRackId();
                    $chapterdetails[$key]['rack_type_id'] = $chdetail->getRackType()->getRackTypeId();
                }
            }
    
    
    return $chapterdetails;
      
   }  
    
 public function calendarscheduleAction() 
 {
    /*Get Monthly Scheduler events */
    $cache     = $this->getServiceLocator()->get('config');
    if(class_exists('Memcached')) 
       {                 
          $memcached = StorageFactory::factory($cache['memcached']);
       }
     $containerservice = $this->getServiceLocator()->get('lms_container_service');
     $stable           = $this->getServiceLocator()->get('Package\Model\TuserpackageTable');
     $auth             = $this->getServiceLocator()->get('zfcuser_auth_service');
     $usertable        = $this->getServiceLocator()->get('Assessment\Model\UserTable');
     if(isset($_POST['type'])) {
            $userId = '';
            $getLessonsTable = $this->getServiceLocator()->get('Notification\Model\TlessonplanTable');
            if ($_POST['type'] == 'onload' || $_POST['type'] == '3') {
                    if ($_POST['type'] == '3') {
                        $deleventss = $getLessonsTable->deleteLesson($_POST, 5);
                    }
                $events = array();
                if (isset($_GET['studentId']) && $_GET['studentId'] != '') {
                    $userId = $_GET['studentId'];
                }else{
                        $userId   = $auth->getIdentity()->getId(); 
                        $userType = $auth->getIdentity()->getUserTypeId();
                }
                 if($userType == '2' || $userType == '3')
                  {
                        $pChildId   = isset($_POST['userId'])?$_POST['userId']:'';
                  }
                 $userId     = (isset($pChildId) && $pChildId !='')?$pChildId:$userId;
                 if (class_exists('Memcached')){
                   if (($eventss = $memcached->getItem('eventss-'.$userId)) == FALSE) {
                        $eventss = $getLessonsTable->getLessons('1', 'calender', $userId);
                        $memcached->setItem('eventss-'.$userId, $eventss);
                   }                     
                }else{
                    $eventss = $getLessonsTable->getLessons('1', 'calender', $userId);
                 }
              
                $userallowed    = $usertable->getuserdetailsById($userId)->current();
                $allowschedule  = @$userallowed->allowschedule;                  
               
                foreach ($eventss as $key => $eventsss) {
                    $subjectDetails = $containerservice->getParentList($eventsss->package_usage_id);
                    $classId    = $subjectDetails[1]['rack_id'];
                    
                    $events[$key]['title']   = $eventsss->lesson_name . '-/' . $eventsss->plan_id . '-/' . $eventsss->package_usage_id . '-/' . $eventsss->is_completed . '-/' . $eventsss->plan_date . '-/' . $eventsss->type.'-/'.$eventsss->assign_type.'-/'.$allowschedule;
                    $events[$key]['start']   = $eventsss->plan_date;
                    $events[$key]['end']     = $eventsss->end_date;
                    $events[$key]['allDay']  = 'true';
                    $events[$key]['classId'] = $classId;
                }
               
                $result = new \Zend\View\Model\JsonModel(array(
                            'events' => $events,
                        ));
                return $result;
          
            }
        } else if (isset($_POST['subjectId'])) {
            //Filter Scheduler events by subject name
        $testChapterdetails   = array();

        $chapterdetails = $this->chachegetChildList($_POST['subjectId']);
        $testChapterdetail    = $chapterdetails;
        
          $view = new ViewModel(array('chapterdetails' => $chapterdetails,'testChapterdetails' => $testChapterdetails,));
          $view->setTemplate('Notification/index/tabslist.phtml');
          $view->setTerminal(true);
          return $view;
        }else{
            //Get list of subjects and chapters for a subject
            $pclassId   = $this->params()->fromRoute('id', 0);
           
            $containerservice   = $this->getServiceLocator()->get('lms_container_service');
            
            $subjectNameDetails = array();           
            if($auth->hasIdentity()) {
                $userObj  = $auth->getIdentity();
                $board_id = $userObj->getBoardId();
                $class_id = $userObj->getClassId();
                $user_id  = $userObj->getId();
                $userType = $userObj->getUserTypeId();
             }
           if($userType == '2' || $userType == '3')
           {
               $pChildId   = $this->params()->fromRoute('userid', 0);
           }
           
            $user_id     = (isset($pChildId))?$pChildId:$user_id;
            $classparent = $containerservice->getParentList($pclassId);
            $customBoardId  = $_SESSION['customBoarddetail']["customboardId"];
            $customBoard= $this->getServiceLocator()->get('website_service')->getCustomboardClassDetail($customBoardId);
            
            $board_id    = $customBoard['rack_id'];
            $board_name  = $customBoard['rack_name'];
            
            $class_id    = $pclassId;
            $class_name  = $classparent[1]['rack_name'];

            if(!isset($board_id) && !isset($class_id))
            {
                 return $this->redirect()->toRoute('home');
            }
          
            $stable             = $this->getServiceLocator()->get('Package\Model\TuserpackageTable');
             if(class_exists('Memcached')){
                 if (($chapterdetails = $memcached->getItem('subjectdetails-'.$class_id.'-'.$board_id.'-'.$user_id)) == FALSE) {
                      $subjectdetails     = $stable->getUserPackageSubjectByClassBoard($class_id, $board_id,$user_id);
                      $memcached->setItem('chapterdetails-'.$class_id.'-'.$board_id.'-'.$user_id, $subjectdetails);
                 }
             }else{
                     $subjectdetails     = $stable->getUserPackageSubjectByClassBoard($class_id, $board_id,$user_id);
             }
            $subjectNameDetails     = $this->chachegetSubjectChildList($class_id);//$containerservice->getChildList($class_id);

            $childSubjectDetails = array();
            $chepters = array();
            $testChapterdetails = array();
            if (count($subjectNameDetails)>0) {
                $subjectId       = $subjectNameDetails[0]['id'];                
                if ($subjectId != '') {
                    //$parentSubjectName   = $subjectNameDetails->buffer()->current()->subject_name;
                   // $childSubjectDetails = $subjecttable->getSubjectById('', $parentSubjectName);
                }
                
               $chepters  = $this->chachegetChildList($subjectId); //$containerservice->getChildList($subjectId);                              
               $testChapterdetails = $this->chachegetChildList($subjectId);//$containerservice->getChildList($subjectId);
               
            }
       
            $userName = '';
            if (isset($_GET['studentId'])) {
                $getStdentId = $_GET['studentId'];
                $userName  = $userObj->getDisplayName();
            }
            $navigationarray = array();
            $result = new ViewModel(array(
                        'navigationarray' => $navigationarray,
                        'chapterdetails' => $chepters,
                        'calenderSubjects' => $subjectNameDetails,
                        'subjectId' => $subjectId,
                        'testChapterdetails' => $testChapterdetails,
                        'childSubjectDetails' => $childSubjectDetails,
                        'userName' => $userName,
                        'class_id'=>$class_id,
                        "board_name"=>$board_name,
                        "board_id"=>$board_id,
                        "class_name"=>$class_name, 
                        "user_id"=>$user_id,
                    ));
            return $result;
        }
        
    }   
    
public function subjectcolorAction()
{
  global $subjectColor;
  $params           = $this->params()->fromPost();
  $auth             = $this->getServiceLocator()->get('zfcuser_auth_service');
  $containerservice = $this->getServiceLocator()->get('lms_container_service');
  if(class_exists('Memcached')) 
  {       
    $cache = $this->getServiceLocator()->get('config');
    $memcached = StorageFactory::factory($cache['memcached']);
 }
 $returnArr     = array();
 $subjectColors = array();
 $subjectClass  = array();
 if ($auth->hasIdentity()) 
 {
    $userObj  = $auth->getIdentity();
    $user_id  = $userObj->getId();
    $userType = $userObj->getUserTypeId();
  }
  if($userType == '2' || $userType == '3')
   {
     $pChildId   = $params['currentuser_id'];
   } 
  $user_id       = (isset($pChildId))?$pChildId:$user_id;
  if(empty($params['chapterId']))
  {
   $subjectcolorTable   = $this->getServiceLocator()->get('Notification\Model\TTchaterResourceTable');   
   $getLessonsTable = $this->getServiceLocator()->get('Notification\Model\TlessonplanTable');
    if(class_exists('Memcached'))
     {
      if(($lessonPlan = $memcached->getItem('lessonPlan-'.$user_id)) == FALSE) {
         $lessonPlan  = $getLessonsTable->getChapterlistByUserId($user_id,$completed=1);
         $memcached->setItem('lessonPlan-'.$user_id, $lessonPlan);
       }
     }else{
       $lessonPlan      = $getLessonsTable->getChapterlistByUserId($user_id,$completed=1);
     }
     foreach($lessonPlan as $lplan){
       $subjectDetails = $containerservice->getParentList($lplan->package_usage_id);//$table->getSubjectByChapterId($lplan->package_usage_id);
       $subjectName    = strtolower($subjectDetails[2]['rack_name']);//SubjectName
       $subjectIds    = $subjectDetails[2]['rack_id'];
       $subjectColor  = $subjectcolorTable->getsubjectcolors($subjectIds);
 
       if(isset($subjectColor->value) && !empty($subjectColor->value))
            $subjectColors[$lplan->package_usage_id] = isset($subjectDetails[2]['color'])?strtolower($subjectDetails[2]['color']):'#A9A9A9';
       else
            $subjectColors[$lplan->package_usage_id] = '#A9A9A9';
            $subjectClass[$lplan->package_usage_id] = $subjectDetails[1]['rack_name'];//class_name;
       }     
            $returnArr['subject_color'] = $subjectColors;
            $returnArr['subject_class'] = $subjectClass;
    }         
        $result = new \Zend\View\Model\JsonModel(array(
            'subject_color' => $returnArr,
            'chapterId'=>@$chapter_id
        ));        
        echo json_encode($returnArr);     
        exit;
    }
    
    
  public function  schedulerchapterlistAction() 
  {
        if(class_exists('Memcached')) 
        {       
           $cache = $this->getServiceLocator()->get('config');
           $memcached = StorageFactory::factory($cache['memcached']);
         } 

        $layout = $this->layout();
        $layout->setTemplate('layout/paper');
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        $containerservice   = $this->getServiceLocator()->get('lms_container_service');

        $navigation   = $this->params()->fromRoute('subject_id', 0);
        $subjectId    = $navigation;
        $classid      = $this->params()->fromRoute('classId', 0);
        $customboard  = $this->params()->fromRoute('customboard', 0);
        
        $navigationArray = @explode('-', $navigation);
        $resourcemodel   = $this->getServiceLocator()->get("Notification\Model\TTchaterResourceTable");
        $getLessonsTable = $this->getServiceLocator()->get('Notification\Model\TlessonplanTable');
        
        if(isset($_POST['type']) && $_POST['type'] == 'reschdule'){ 
            $chpt_id           = strip_tags($_POST['chapterId']);
             if(!empty($chpt_id)){
                $classparent       = $containerservice->getParentList($chpt_id);
                $re_class_id       = $classparent['1']['rack_id'];
                $re_subject_id     = $classparent['2']['rack_id'];
                $re_Sub_subject_id = $classparent['3']['rack_id'];
             }else{
                $re_subject_id     = strip_tags($_POST['subject_id']);
                $classparent       = $containerservice->getParentList($re_subject_id);
                $re_class_id       = $classparent['1']['rack_id'];
             }  
            $lessionid         = $_POST['lessionId'];
            $puser_id          = $_POST['user_id'];
            $paramtype         = $_POST['type'];
            $customboard       = $_POST['customboardid'];
            $sceduledetail = $getLessonsTable->checkStatus($lessionid)->current();
        }
        $subjectId = (isset($re_subject_id))?$re_subject_id:$subjectId;
        $classid   = (isset($re_class_id))?$re_class_id:$classid;
                
        $chepters = array();
        $chapterdetail  = $containerservice->getChildList($subjectId);//$chaptertable->getChaptersBySubjectId($subjectId);
        foreach($chapterdetail as $key=>$chdetail)
        {         
            if ($chdetail->getRackType()->getRackTypeId() =='7' || $chdetail->getRackType()->getRackTypeId() =='8') {                
                $chep_ides[] = $chdetail->getRackId();
           }else{               
                $chepters[$key]['chapter_name'] = $chdetail->getRackName()->getName();
                $chepters[$key]['id'] = $chdetail->getRackId();
                $chepters[$key]['rack_type_id'] = $chdetail->getRackType()->getRackTypeId();
            }
        }
        
        if(!empty($chep_ides)){
          $chepter_idstring  = implode("-",$chep_ides);
          $subchepter_detail =  $resourcemodel->getsubchecterlist($chep_ides); 
          foreach($subchepter_detail as $key=>$subchapters)
           {
                $chepters[$key]['chapter_name'] = $subchapters->chapter_name;
                $chepters[$key]['id'] = $subchapters->rack_id;
                $chepters[$key]['rack_type_id'] = $subchapters->rack_type_id;
           }          
        }     
        $puserid    = $this->params()->fromRoute('userId', 0);
         $usersubjects = array();
         if ($auth->hasIdentity()) {
                $userObj  = $auth->getIdentity();
                $boardId  = $userObj->getBoardId();
                $classId  = $userObj->getClassId();
                $userId   = $userObj->getId(); 
                $userType = $userObj->getUserTypeId();
                
            if($userType == '2' || $userType == '3')
            {
              $pChildId   = $puserid;
            }   
             if(isset($_POST['type']) && $_POST['type'] == 'reschdule')
             { 
                 $pChildId  = $puser_id;
             }
            
            $userId     = (isset($pChildId))?$pChildId:$userId; 
            $userpackagestable = $this->getServiceLocator()->get('Package\Model\TuserpackageTable');
            if(class_exists('Memcached')){
                 if (($userpackagessubjects = $memcached->getItem('userpackagessubjects-'.$userId)) == FALSE) {
                        $userpackagessubjects = $userpackagestable->getPackagesubjects($userId);
                        $memcached->setItem('userpackagessubjects-'.$userId, $userpackagessubjects);
                }
             }else{
                $userpackagessubjects = $userpackagestable->getPackagesubjects($userId);
             }
            if ($userpackagessubjects->count()) {
              foreach ($userpackagessubjects as $userpackagessubject) {
                    $explode = explode(',', $userpackagessubject->syllabus_id);
                    foreach ($explode as $val) {
                        $usersubjects[] = $val . "-" . $userpackagessubject->board;
                    }
                }
            }
         }
         if($classid){
           $classparent  = $containerservice->getParentList($classid);          
            $boardId     = $classparent[0]['rack_id'];
            $classId     = $classid;
            $boardName   = $classparent[0]['rack_name'];
            $className   = $classparent[1]['rack_name'];
         }  
        // subjectdetails
            $subjectDetails = $containerservice->getContainerList($subjectId);
        foreach ($subjectDetails as $subjects) {
            $subjectName    = $subjects->getRackName()->getName();
        }
        foreach ($chepters as $chapterdetail) {
            $chapter_ids[] = $chapterdetail['id'];
        }
        $chapter_idstr = implode(',',  array_unique($chapter_ids));
        $statusArr     = $this->getFreeChapter($boardId, $classId, $subjectId, $chapter_idstr,$userId);
        
        $juniorArr = array('nursery', 'kg', 'i', 'ii', 'iii', 'iv', 'v');
        $seniorArr = array('vi', 'vii', 'viii', 'ix', 'x', 'xi', 'xii');

        return new ViewModel(array(
                'usersubjects' => $usersubjects,
                'chapterdetails' => $chepters,
                'subjectName' => @$subjectName,
                'boardName' => @$boardName,
                'boardId' => $boardId,
                'className' => @$className,
                'classId' => $classId,
                'subjectId' => @$subjectId,
                'testChapterdetails' => @$chepters,
                'statusArr' => $statusArr,
                'user_id'   =>$userId,
                'customboard'=>$customboard,
                'lessionid'=>@$lessionid,
                'paramtype'=>@$paramtype,
                'sceduledetail'=>@$sceduledetail
                ));
    }
    
  public function scheduleroprationAction()
  {
     $getLessonsTable   = $this->getServiceLocator()->get('Notification\Model\TlessonplanTable');
     $auth              = $this->getServiceLocator()->get('zfcuser_auth_service');
     $containerservice  = $this->getServiceLocator()->get('lms_container_service');
     $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
     $apiservice        = $this->getServiceLocator()->get('api_service');
     $usertable         = $this->getServiceLocator()->get('Assessment\Model\UserTable');
     $user_id           = $auth->getIdentity()->getId();
     $userType          = $auth->getIdentity()->getUserTypeId(); 
     $userObj           = $auth->getIdentity();
     
     if($_POST['user_id']!=$user_id)
        {
          $result = new \Zend\View\Model\JsonModel(array(
                    'output' => 1,
                    'msg' =>  "You are not able accept/reject schedule ",
                   ));
          return $result;
        } 
     if(isset($_POST['type']) && $_POST['type'] == 'accept'){
         
         $data       = array('modified_on'=>date('Y-m-d H:i:s'),'assign_type'=>'2');
         $lession_id = $_POST['lessionId'];
         $eventss    = $getLessonsTable->updatelessons('update', $data,$lession_id);
         if($eventss){
         // $parentdetail= $usertable->UserMinDetailByID($eventss->assign_user_id);
          $classparent = $containerservice->getParentList($_POST['subject_id']);
          $nuuid       = $apiservice->generateUuid($type=NULL);
          $data        = array(
                            'notification_text' =>  $userObj->getDisplayName().'&nbsp;has accept your Plan ',
                            'userid'            => $eventss->assign_user_id,
                            'type_id'           => '1',    
                            'notification_url'  => 'calendarschedule/'.$classparent[1]['rack_id'].'/'.$user_id,
                            'created_by'        => $user_id,
                            'created_date'      => date('Y-m-d H:i:s'),
                            'notification_uuid' => $nuuid,
                         );
           $notificationtable->insertnotification($data);
             
         }
         $result = new \Zend\View\Model\JsonModel(array(
                    'output' => 0,
                    'eventId' =>  $eventss->plan_id,
                    'planDate' => $eventss->plan_date,
                    //'tab_type' => $eventss->assign_type,
                    ));
     }else if(isset($_POST['type']) && $_POST['type'] == 'decline'){
         
           $data       = array('modified_on'=>date('Y-m-d H:i:s'),'status'=>'3'); // status ==>3 Decline status
           $lession_id = $_POST['lessionId'];
           $eventss    = $getLessonsTable->updatelessons('update', $data,$lession_id);
           
         if($eventss){
          //$parentdetail= $usertable->UserMinDetailByID($eventss->assign_user_id);
          $nuuid       = $apiservice->generateUuid($type=NULL);
          $classparent = $containerservice->getParentList($_POST['subject_id']);
          $nuuid       = $apiservice->generateUuid($type=NULL);
          $data        = array(
                            'notification_text' =>  $userObj->getDisplayName().'&nbsp;has Decline your Plan ',
                            'userid'            => $eventss->assign_user_id,
                            'type_id'           => '1',    
                            'notification_url'  => 'calendarschedule/'.$classparent[1]['rack_id'].'/'.$user_id,
                            'created_by'        => $user_id,
                            'created_date'      => date('Y-m-d H:i:s'),
                            'notification_uuid' => $nuuid,
                         );
           
            $notificationtable->insertnotification($data);
             
         }
           
           $result     = new \Zend\View\Model\JsonModel(array(
                        'output' => 0,
                        'eventId' =>  $eventss->plan_id,
                        'planDate' => $eventss->plan_date,
                       // 'tab_type' => $eventss->assign_type,
                      ));             
    }
    
   return $result;   
  }    
    
 /** For getting free chapter of board class**/
  public function getFreeChapter($boardId, $classId, $subjectId, $chapter_ids,$userId=null) {
       
        if(class_exists('Memcached')) 
         {       
            $cache     = $this->getServiceLocator()->get('config');
            $memcached = StorageFactory::factory($cache['memcached']);
        } 
        $auth             = $this->getServiceLocator()->get('zfcuser_auth_service');
        $containerservice = $this->getServiceLocator()->get('lms_container_service');
        $user_id          = $auth->getIdentity()->getId();
        $userType         = $auth->getIdentity()->getUserTypeId();
                
        if($userType == '2' || $userType == '3')
        {
              $pChildId   = $userId;
        }   
         $user_id     = (isset($pChildId))?$pChildId:$user_id; 
         
         $statusArr['permission'] = 'denied';        
         $stable                  = $this->getServiceLocator()->get('Package\Model\TuserpackageTable');
         if(class_exists('Memcached')){
                 if (($userpackagessubjects   = $memcached->getItem('userPackageDetail-'.$classId.'-'.$boardId.'-'.$user_id)) == FALSE){
                        $userPackageDetail    = $stable->getUserPackageSubjectByClassBoard($classId, $boardId,$user_id);
                        $memcached->setItem('userPackageDetail-'.$classId.'-'.$boardId.'-'.$user_id, $userPackageDetail);
                }
             }else{
                 $userPackageDetail   = $stable->getUserPackageSubjectByClassBoard($classId, $boardId,$user_id);//$stable->getUserPackageSubjectByClassBoard($classId, $boardId);
             }
         // echo "<pre />"; print_r($userPackageDetail); exit;   
            $freeChapterTable  = $this->getServiceLocator()->get('Notification\Model\TfreechapterTable');           
                if(class_exists('Memcached'))
                {
                    $cheptr_ids   = explode(',',$chapter_ids);
                    $cheptr_idstr = implode('-', $cheptr_ids);
                     if (($userpackagessubjects   = $memcached->getItem('chapterDetails-'.$cheptr_idstr)) == FALSE) {                     
                            $chapterDetails    = $freeChapterTable->getChapterExistByChapterId($chapter_ids);
                            $memcached->setItem('chapterDetails-'.$cheptr_idstr, $chapterDetails);
                 }
                }else{ 
                   $chapterDetails    = $freeChapterTable->getChapterExistByChapterId($chapter_ids);
                }
 
        $firstChapter = $this->chachegetChildList($subjectId);

        $arraychapter  =  array(); 
        foreach($firstChapter as $chepters)
        {
            $arraychapter[] = $chepters['id'];
        }   
        // This is done for the sub-subject case.
        $subjectId  = $subjectId; //isset($firstChapter[0]['id'])?$firstChapter[0]['id']:$subjectId;
        $chapterIds = explode(',', $chapter_ids);
        $key        = array_search(@$chapterDetails->buffer()->current()->container_id, $chapterIds, true);
        //echo "<pre />"; print_r($userPackageDetail);exit;
         foreach($userPackageDetail as $allpackagesbys)
         {
             @$explode = explode(',', $allpackagesbys->syllabus_id);
             foreach($explode as $val){
                        @$usersubjects_ids[]=$val;
                  }
         }  
    
        if ($userPackageDetail->count()) {
            $syllabus_id =$usersubjects_ids; //explode(',', $userPackageDetail->buffer()->current()->syllabus_id);
            
            if (in_array($subjectId, $syllabus_id)) {
                $statusArr['permission'] = 'access';
                $statusArr['package'] = 'packages';
            } else if ($chapterDetails->count()) {
                $statusArr['permission'] = 'access';
                $statusArr['chapterExist'] = 'chapterExist';
                $statusArr['key'] = $key + 1;
                $statusArr['container_id'] = $chapterDetails->buffer()->current()->container_id;
            }else if(in_array($firstChapter[0]['id'], $chapterIds)){
                $statusArr['permission'] = 'access';
                $statusArr['chapterNotExist'] = 'chapterNotExist';
                $statusArr['key'] = 1;
            }else if (in_array($firstChapter[0]['id'], $arraychapter)) {               
                $statusArr['permission'] = 'access';
                $statusArr['chapterNotExist'] = 'chapterNotExist';
                $statusArr['key'] = 1;
            }
        } else {
    
            if ($chapterDetails->count()) {
                $statusArr['permission'] = 'access';
                $statusArr['chapterExist'] = 'chapterExist'; 
                $statusArr['key'] = $key + 1;
                $statusArr['container_id'] = $chapterDetails->buffer()->current()->container_id;
            } else if (in_array($firstChapter[0]['id'], $arraychapter)) {               
                $statusArr['permission'] = 'access';
                $statusArr['chapterNotExist'] = 'chapterNotExist';
                $statusArr['key'] = 1;
            }
        }
        
       // echo  "<pre />"; print_r($statusArr); exit;   
        return $statusArr;
    }  
    
  
    public function freechapterAction() 
    {        
      $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
      $containerservice   = $this->getServiceLocator()->get('lms_container_service'); 
      $view = new ViewModel();
      $view->setTerminal(false);
      $navigation = $this->params()->fromPost(); //echo '<pre>';print_r($navigation);die('here1');  
      if($auth->hasIdentity()) {
                $userObj  = $auth->getIdentity();
                $boardId = $userObj->getBoardId();
                $classId = $userObj->getClassId();
                $userId  = $userObj->getId(); 
      }
      $statusArr = array();
      $stable    = $this->getServiceLocator()->get('Package\Model\TuserpackageTable');
      //$table = $this->getServiceLocator()->get('Container\Model\TmainFactory');
      $post_user_id   = $navigation['userId'];
      $chapterIds     = ltrim($navigation['chapterIds'], ',');
      $subjectDetails = $containerservice->getParentList($navigation['board_class_subject_chapter_id']);//$table->getSubjectByChapterId($navigation['board_class_subject_chapter_id']);
      
      $board_class_subject_id = $subjectDetails['2']['rack_id'];
      $board_class_id = $subjectDetails['1']['rack_id'];
      $board_id = $subjectDetails['0']['rack_id'];        

      $statusArr = $this->getFreeChapter($board_id, $board_class_id, $board_class_subject_id, $chapterIds,$post_user_id);

       
        echo json_encode($statusArr);
        exit;
    }
    
  public function addLessonsAction() {
   //Scheduling Tasks add and update scheduled tasks 
        
    $auth              = $this->getServiceLocator()->get('zfcuser_auth_service');
    $containerservice  = $this->getServiceLocator()->get('lms_container_service'); 
    $getLessons        = $this->getServiceLocator()->get('Notification\Model\TlessonplanTable');
    $usertable         = $this->getServiceLocator()->get('Assessment\Model\UserTable');
    $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
    $apiservice        =  $this->getServiceLocator()->get('api_service');
    $comMapperObj      = $this->getServiceLocator()->get("com_mapper");
        
    if(isset($_POST['lName']))
    {
        if($_POST['lName'] =='' || $_POST['lName'] == 'Click or enter an event name.')
        {                
            $result = new \Zend\View\Model\JsonModel(array(
                            'output' => 2,
                            'msg'=>'Click or enter an event name.'
           )); 
            return $result;
        }           
    }
    if($auth->hasIdentity()) 
    {
        $userObj    = $auth->getIdentity();
        $userId     = $userObj->getId();
        $usertypeId = $userObj->getUserTypeId();
        $curr_user  = $userId;  
    }
    $post_user_id = $_POST['userId'];
    if($post_user_id == $userId)
    {
        $userId       = $userId;
        $assign_type  = 2;
        $post_user_id = '';
    }else{
        $userId         = $post_user_id;
        $post_user_id   = $curr_user;
        $userallowed    = $usertable->getuserdetailsById($userId)->current();
        $allowschedule  = isset($userallowed->allowschedule)?$userallowed->allowschedule:'1';                  
        $assign_type    = ($allowschedule == "1")?"2":"1";
    }
               //// Parent or mentor REschedule of child's task.
    if(isset($_POST['re_paramtype']) && $_POST['re_paramtype'] == 'reschdule'){
        $lesson_id = $_POST['re_less_id'];
        $savedlesson = $getLessons->getlessonById($lesson_id);
                
        $fdate = $_POST['date'];
        $dd = substr($fdate, 0, 2);
        $mm = substr($fdate, 3, 2);
        $yy = substr($fdate, 6, 4);
        $fromDate = '20' . $yy . "-" . $mm . "-" . $dd;
        $edate = $_POST['endDate'];
        $dd = substr($edate, 0, 2);
        $mm = substr($edate, 3, 2);
        $yy = substr($edate, 6, 4);
        $endDate = '20' . $yy . "-" . $mm . "-" . $dd;
        $title = $_POST['title'];
        $lName = $_POST['lName'];
        $plan_id = $_POST['re_less_id'];
        $uuid  = $_POST['uuid']?$_POST['uuid']:$apiservice->generateUuid($type=NULL);
        if($title >0){
           $customboard = $comMapperObj->getcustomboardPrimaryId($_POST['customboardid'],$title);
        }else{
            $customboard = NULL;
        }
        $post_user_id = ($post_user_id !='')?$post_user_id:$savedlesson->assign_user_id;
        $eventss   = $getLessons->addLessons($title, $fromDate, $endDate, $lName, 'update', $_POST['tabType'], $plan_id,'',$userId,$post_user_id,$assign_type,$uuid,$customboard);
                
        $chpt_id   = strip_tags($title);
        $bordetail = $containerservice->getParentList($chpt_id);
        $re_class_id       = $bordetail['1']['rack_id'];
        $nuuid = $apiservice->generateUuid($type=NULL);
        if($usertypeId =='2' || $usertypeId =='3' ){
          $data = array(
                'notification_text' =>  $userObj->getDisplayName().'&nbsp;has reschedule your Plan ',
                'userid' => $userId,
                'type_id' => '1',    
                'notification_url' => 'calendarschedule/'.$re_class_id,
                'created_by'       => $curr_user,
                'created_date'      => date('Y-m-d H:i:s'),
                'notification_uuid' => $nuuid,
           );
           $notificationtable->insertnotification($data);
        }else{
        if($savedlesson->assign_user_id !='' || $savedlesson->assign_user_id !='0'){
        $data = array(
            'notification_text' =>  $userObj->getDisplayName().'&nbsp;has reschedule your Plan ',
            'userid'            => $savedlesson->assign_user_id,
            'type_id'           => '1',    
            'notification_url'  => 'calendarschedule/'.$re_class_id.'/'.$userId,
            'created_by'        => $userId,
            'created_date'      => date('Y-m-d H:i:s'),
            'notification_uuid' => $nuuid,
           );
        $notificationtable->insertnotification($data);
       } 
    }
    $result = new \Zend\View\Model\JsonModel(array(
        'output' => 0,
        'eventId' => $eventss->plan_id,
        'planDate' => $eventss->plan_date
    )); 
   }else{
       /*New schedule added By user*/
    if(isset($_POST['title']) && $_POST['title'] != '0') {
      if ($_POST['type'] == '2') {
        $fdate = $_POST['date'];
        $dd = substr($fdate, 0, 2);
        $mm = substr($fdate, 3, 2);
        $yy = substr($fdate, 6, 4);
        $fromDate = '20' . $yy . "-" . $mm . "-" . $dd;
        $edate = $_POST['endDate'];
        $dd = substr($edate, 0, 2);
        $mm = substr($edate, 3, 2);
        $yy = substr($edate, 6, 4);
        $endDate = '20' . $yy . "-" . $mm . "-" . $dd;
        $title   = $_POST['title'];
        $lName   = $_POST['lName'];
        $uuid    = $_POST['uuid'];
        $customboard = $comMapperObj->getcustomboardPrimaryId($_POST['customboardid'],$title);
    } else {
        $fromDate = $_POST['date'];
        $endDate = $_POST['endDate'];
        $title = $_POST['title'];
        $lName = $_POST['lName'];
        $uuid    = $_POST['uuid'];
        $customboard = $comMapperObj->getcustomboardPrimaryId($_POST['customboardid'],$title);
       }
       
    if(isset($_POST['page'])) {
         $result = $getLessons->updateLessonProgress($fromDate, $endDate, $_POST['lessionId']);
    }else{
        $checkEvent = $getLessons->checkLessons($title, $fromDate, $endDate, $_POST['tabType'],$userId,$post_user_id);
        $event = $checkEvent->current();
        if ($checkEvent->count() == '1') {
            if(!empty($event->assign_user_id))
                $assign_type = $event->assign_type;
            $eventss = $getLessons->addLessons($title, $fromDate, $endDate, $lName, 'update', $_POST['tabType'], $event->plan_id, $event->is_completed,$userId,$post_user_id,$assign_type,$uuid,$customboard);
            $result = new \Zend\View\Model\JsonModel(array(
                         'output' => 1, 
                     )); 
        }else{
            $uuid = $apiservice->generateUuid($type=NULL);
            $eventss = $getLessons->addLessons($title, $fromDate, $endDate, $lName, 'insert', $_POST['tabType'],'','',$userId,$post_user_id,$assign_type,$uuid,$customboard);
            if($usertypeId =='2' || $usertypeId =='3' )
            {
                $nuuid = $apiservice->generateUuid($type=NULL);
                $chpt_id   = strip_tags($title);
                $bordetail = $containerservice->getParentList($chpt_id);
                $re_class_id       = $bordetail['1']['rack_id'];  
                $data = array(
                        'notification_text' =>  $userObj->getDisplayName().'&nbsp;has schedule a new Plan ',
                        'userid' => $userId,
                        'type_id' => '1',    
                        'notification_url' => 'calendarschedule/'.$re_class_id,
                        'created_by'       => $curr_user,
                        'created_date'      => date('Y-m-d H:i:s'),
                        'notification_uuid' => $nuuid,
                      );
                $notificationtable->insertnotification($data);
            }
            $result =new \Zend\View\Model\JsonModel(array(
                'output' => 0,
                'eventId' => $eventss->plan_id,
                'planDate' => $eventss->plan_date
            ));
        }
    }
  }else{
      /*Custom schedule added here*/
        $fdate = $_POST['date'];
        $dd = substr($fdate, 0, 2);
        $mm = substr($fdate, 3, 2);
        $yy = substr($fdate, 6, 4);
        $fromDate = '20' . $yy . "-" . $mm . "-" . $dd;
        $edate = $_POST['endDate'];
        $dd = substr($edate, 0, 2);
        $mm = substr($edate, 3, 2);
        $yy = substr($edate, 6, 4);
        $endDate = '20' . $yy . "-" . $mm . "-" . $dd;
        $title = $_POST['title'];
        $lName = $_POST['lName'];
        $uuid  = $_POST['uuid']?$_POST['uuid']:$apiservice->generateUuid($type=NULL);
            
        $eventss = $getLessons->addLessons($title, $fromDate, $endDate, $lName, 'insert', $_POST['tabType'],'','',$userId,$post_user_id,$assign_type,$uuid);
        $result = new \Zend\View\Model\JsonModel(array(
                    'output' => 0,
                    'eventId' => $eventss->plan_id,
                    'planDate' => $eventss->plan_date
                ));
        }
 } //exit;
 return $result;
} 
 
  public function calenderOperationsAction()
  {
    /*
     * Save the tasks on dragging chapters to calender *
     */
       $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
       $containerservice   = $this->getServiceLocator()->get('lms_container_service'); 
       $getLessons = $this->getServiceLocator()->get('Notification\Model\TlessonplanTable');
       
        if($auth->hasIdentity()) {
                $userObj = $auth->getIdentity();
                $boardId = $userObj->getBoardId();
                $classId = $userObj->getClassId();
                $userId  = $userObj->getId(); 
      }
        if ($_POST['type'] != 1) {
            if (isset($_POST['dragType'])) {
                $checkStatus = $getLessons->checkStatus($_POST['lessionId']);
                if ($checkStatus->current()->is_completed == 1) {
                    $result = new \Zend\View\Model\JsonModel(array(
                                'output' => 2,
                            ));
                } else {
                    if ($_POST['dragType'] > 0) {
                        $checkEvent = $getLessons->checkLessonsDrag($_POST['chapterId'], $_POST['fdate'], $_POST['endDate'], $_POST['tabType'],$userId);
                        if (count($checkEvent->buffer()) >= 1) {
                            $result = new \Zend\View\Model\JsonModel(array(
                                        'output' => 1,
                                    ));
                        } else {
                            $eventss = $getLessons->updateLesson($_POST,$userId);
                            $result = new \Zend\View\Model\JsonModel(array(
                                        'output' => 0,
                                    ));
                        }
                    } else {
                        $eventss = $getLessons->updateLesson($_POST,$userId);
                        $result = new \Zend\View\Model\JsonModel(array(
                                    'output' => 0,
                                ));
                    }
                }
            } else {
                $checkEvent = $getLessons->checkLessons($_POST['chapterId'], $_POST['fdate'], $_POST['endDate'], $_POST['tabType'],$userId);
                if ($checkEvent->count() == '1') {
                    $result = new \Zend\View\Model\JsonModel(array(
                                'output' => 1,
                            ));
                } else {
                    $eventss = $getLessons->updateLesson($_POST,$userId);
                    $result = new \Zend\View\Model\JsonModel(array(
                                'output' => 0,
                            ));
                }
            }
        } else {
            $result = $getLessons->updateLesson($_POST,$userId);
        }
        return $result;
    }
    
    
    public function schedulerReportAction()
    {
       $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
       $containerservice   = $this->getServiceLocator()->get('lms_container_service'); 
       $userId = $this->params()->fromRoute('id');
       $subjectId='';
       $customBoardRackId='';
        if($auth->hasIdentity()) {
                 $userObj = $auth->getIdentity();
                 $loginUserId  = $userObj->getId();
                 $userTypeId = $userObj->getUserTypeId();
                 $tstudentAndMentorTable = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
                 $rowSet = $tstudentAndMentorTable->getStudentMentorRelationRows($loginUserId,$userId);
                 foreach($rowSet as $row) {
                    $subjectId = $row->subject_id;
                    $customBoardRackId = $row->custom_board_rack_id;  
                    break;
                 }
        }
       
       if(empty($userId)){
        if($auth->hasIdentity()) { 
                $userObj = $auth->getIdentity();
                $userId  = $userObj->getId();
      }           
       }

       $getLessons = $this->getServiceLocator()->get('Notification\Model\TlessonplanTable');
       
       $eventss = $getLessons->getLessons('1', 'progress',  $userId,'cplan');
       
       $eventss->buffer();
       $overdue = $getLessons->getLessons('0', 'progress', $userId,'cplan');
       $overdue->buffer();
       
       $leftdue = $getLessons->getLessons('2', 'progress', $userId,'cplan');
       $leftdue->buffer();
       
         $result = new ViewModel(array(
                  'eventss' => $eventss,
                  'overdue' => $overdue,
                  'leftdue' => $leftdue,
                  'userTypeId' => $userTypeId,
                  'userId' => $userId,
                  'subjectId' => $subjectId,
                  'customBoardRackId' => $customBoardRackId
              ));
         $result->setTerminal(true);
        return $result;
        
    }       
    
public function notesAction() 
{
  $subjectdetails  = array();
  $subjectsNotes   = array();
  $subjectId       = '';
  $totalnotes      = '';
  $notesId         = '';
  $notesTable = $this->getServiceLocator()->get('Notification\Model\TNotesTable');
        //Deleting a note by note id
  $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
  $containerservice   = $this->getServiceLocator()->get('lms_container_service'); 
  if($auth->hasIdentity()) 
    {
        $userObj = $auth->getIdentity();
        $userId  = $userObj->getId(); 
    }     
    
    if(isset($_POST['delNoteid']) && $_POST['delNoteid'] != '') 
     {
        $subjectId = $_POST['subject_id'];
        $delnotes = $notesTable->deleteNotes($_POST);
        $totalnotes = $notesTable->gettotalNotes($userId, $_POST['delcontainerId'], $_POST['subject_id'], $_POST['delChapterId']);
        $notes = $notesTable->getNotesPrevAndNext($userId, $_POST, 'delete');
        $notesView = $notes->buffer()->current()->note;
        $notes_id = $notes->buffer()->current()->note_id;
        $notesAll = $notes_id . '-' . $notesView;
        $result = new \Zend\View\Model\JsonModel(array(
                    'output' => 'success',
                    'totalnotes' => $totalnotes,
                    'noteId' => $notesAll,
                ));
        return $result;
   }else if(isset($_POST['data']) && $_POST['data'] != '') {
     $uploadTable = $this->getServiceLocator()->get('Assessment\Model\TuploadsdownloadsTable');
            $postdata   = explode("&",$_POST['data']);
            $_POST['containerId'] = $postdata[0];
            $_POST['notes']       = $postdata[1];
            $_POST['chapter_id']  = $postdata[2];
            $_POST['subjectId']   = $postdata[3];
            $_POST['note_id']     = $postdata[4];
            $_POST['type']        = $postdata[5];
            $_POST['inOrUpType']  = @$postdata[6];
            $_POST['customBoardId']   = $postdata[7];
            //$_POST['subjectId']   = @$postdata[7];
            $_POST['for_ques']    = '3';
            $_POST['image_file']  = '';
            $_POST['file_name']   = '';
            if(is_uploaded_file(@$_FILES['file']['tmp_name'])){  
                $file_name  = $_FILES['file'];
                $filename              = $file_name['name'];
                $filepath              = $file_name['tmp_name'];
                $filetype              = $file_name['type'];
                $commonObj = $this->getServiceLocator()->get("com_mapper");
                $resdata =$commonObj->is_correctfile($filepath);
               if($resdata == 'true'){
                   $ext                 = pathinfo($filename, PATHINFO_EXTENSION);
                   $changefilename      = uniqid().'.'.$ext;
                   $fileUploaded        = $this->ftpFileUploaded($filepath, '/uploads/questionimages/' . $changefilename);
                   $_POST['image_file'] = $changefilename;
                   $_POST['file_name']  = $filename;
                }else{
                        $result = new \Zend\View\Model\JsonModel(array(					
                                         'output'=> 'error',				
                                    ));
                        return $result;  
                }
           }
          
            //Add or update notes
            if ($_POST['type'] == 'notes' && $_POST['inOrUpType'] == 'update') {
                $notesId = $notesTable->updateNotes($_POST);
            } else {
                if ($_POST['type'] == 'sevices' && isset($_POST['note_id']) && $_POST['note_id'] != '') {
                    $notes = $notesTable->updateNotes($_POST);
                    $notesId = $notes->buffer()->current()->note_id;
                } else {
                   $notesdes = htmlspecialchars(strip_tags($_POST['notes']));
                   $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                   $customBoardRackId = $comMapperObj->getcustomboardPrimaryId($_POST['customBoardId'],$_POST['chapter_id']); 
                   $_POST['customBoardRackID']=$customBoardRackId;
                   //save the notes here by containerId and chapterId
                   $notesId = $notesTable->saveNotes($_POST['containerId'],$notesdes, $_POST['chapter_id'],$userId,$customBoardRackId);
                   if($_POST['image_file'] !='') 
                    $uploadTable->addquesDetails($_POST,$userId,$notesId);
                  //  $totalnotes = $notesTable->gettotalNotes($userId, $_POST['containerId'], $_POST['subjectId'], $_POST['chapter_id']);
                }
            }
              $totalnotes = $notesTable->gettotalNotes($userId,'',$_POST['subjectId'], $_POST['chapter_id']);
            if ($notesId) {
                $result = new \Zend\View\Model\JsonModel(array(
                            'output' => 'success',
                            'note_id' => $notesId,
                            'totalnotes' => $totalnotes,
                        ));
                return $result;
            }
        } else {
            $navigation = $this->params()->fromRoute('param1', 0);
            $class_id = $navigation;
            $subjectdetails = array();
            $navigationarray = array();
            $classparent = $containerservice->getParentList($class_id);          
            
            $navigationarray['0'] = $classparent[0]['rack_name'];//$board_name;
            $navigationarray['1'] = $classparent[0]['rack_id'];//$board_id
            $navigationarray['2'] = $classparent[1]['rack_name'];//classname
            $navigationarray['3'] = $class_id; 
            
            // This function get the all subjects based on class id.
             $subjectDetails = $containerservice->getChildList($class_id); 
               foreach($subjectDetails as $key=>$chdetail)
                {
                  $subjectdetails[$key]['subject_name'] = $chdetail->getRackName()->getName();
                  $subjectdetails[$key]['id'] = $chdetail->getRackId();
                  $subjectdetails[$key]['rack_type_id'] = $chdetail->getRackType()->getRackTypeId();
                }
              
           $boardClasslist = $containerservice->getParentList($class_id);    
           $childSubjectDetails = array();
           //get the subjects names basedon classId
           $currentChildSubjectss =$containerservice->getChildList($subjectdetails[0]['id']);
           // $childSubjectDetails = $this->arrayToObject($currentChildSubjectss);
           foreach($currentChildSubjectss as $key=>$chdetail)
           {
              if($chdetail->getRackType()->getRackTypeId()=='7' || $chdetail->getRackType()->getRackTypeId() =='8'){
                    $childSubjectDetails[$key]['chapter_name'] = $chdetail->getRackName()->getName();
                    $childSubjectDetails[$key]['id'] = $chdetail->getRackId();
                    $childSubjectDetails[$key]['rack_type_id'] = $chdetail->getRackType()->getRackTypeId();           
                 }  
              }          
            $container_id = '';
            $getUserId = '';
            //Get all notes for a service with in a subject 
            //$userId = '';
//            if (isset($_GET['studentId']) && $_GET['studentId'] != '') {
//                $userId = $_GET['studentId'];
//                $getUserId = $_GET['studentId'];
//            } else {
//                $userId = $userId;
//            }
            $userId = $userId;
            $subjectId = $subjectdetails[0]['id'];
            
            $notes = $notesTable->getNotes($userId, $container_id, $subjectId);
            $ncount = 0;
           
            if (count($notes) != 0) {
                foreach ($notes as $key => $chapternotes) {
                    $ncount +=1;
                    if (array_key_exists($chapternotes->name, $subjectsNotes)) {
                        $subjectsNotes[$chapternotes->name]['totalNotes'] += 1;
                        if ($chapternotes->note != '') {
                            $subjectsNotes[$chapternotes->name][$ncount] = $chapternotes->note_id . '-/' . $chapternotes->note.'-/'.$chapternotes->pdf_file_name.'-/'.$chapternotes->file_name.'-/'.$chapternotes->up_down_id;
                        }
                    } else {
                        $ncount = 1;
                        if ($chapternotes->note != '') {
                            $subjectsNotes[$chapternotes->name][$ncount] = $chapternotes->note_id . '-/' . $chapternotes->note.'-/'.$chapternotes->pdf_file_name.'-/'.$chapternotes->file_name.'-/'.$chapternotes->up_down_id;
                            $subjectsNotes[$chapternotes->name]['updated_date'] = $chapternotes->updated_date;
                            $subjectsNotes[$chapternotes->name]['container_id'] = $chapternotes->container_id;
                            $subjectsNotes[$chapternotes->name]['chapter_id'] = $chapternotes->chapter_id;
                        }
                        $subjectsNotes[$chapternotes->name]['totalNotes'] = 1;
                    }
                }
            }
            if (isset($_GET['studentId'])) {
                $getStdentId = $_GET['studentId'];
                $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                //Here get the userDetails by userId
                $user = $this->getUser($getStdentId);
                $userName = $user->buffer()->current()->display_name;
            }
         
            //echo "<pre />"; print_r($boardClasslist); exit;
       $result = new ViewModel(array(
                 'subjects' => $subjectdetails,
                 'notes' => $subjectsNotes,
                 //'navigationarray' => $navigation,
                 'subjectId' => @$subjectId,
                 'subjectName' => @$subjectName,
                 'currentChildSubjects' => $childSubjectDetails,
                 'getUserId' => $getUserId,
                 "navigationarray"=>$navigationarray,
                 'userName' => @$userName,
                 'userId' => $userId,
                 'parentlist'=>$boardClasslist,  
                ));
        }
        return $result;
    }
    
    public function ajaxProgressNotesAction() 
    {     
      //Get notes on change of subject
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        $containerservice   = $this->getServiceLocator()->get('lms_container_service'); 
         if($auth->hasIdentity()) {
            $userObj = $auth->getIdentity();
            $userIdes  = $userObj->getId(); 
         }  
        $rurl=explode('-',$_POST['rurl']);
        $postArray = $this->escaper($_POST);
        $subjectsNotes   = array();
        $container_id    = '';
        $subjectId       = '';
        $notes           = array();
        $getUserId       = '';
        $notesTable      = $this->getServiceLocator()->get('Notification\Model\TNotesTable');
        if (isset($postArray['delChapterId']) && $postArray['delChapterId'] != '') {
            $delnotes = $notesTable->deleteNotes($postArray);
        }
        if (isset($postArray['subject_id']) && $postArray['subject_id'] != '') {
            $subjectId = $postArray['subject_id'];
            if (isset($postArray['value']) && $postArray['value'] == 1) {
                $userId    = $postArray['id'];
                $getUserId = $postArray['id'];
            } else {
                $userId = $userIdes;
            }
            $notes = $notesTable->getNotes($userId, $container_id, $postArray['subject_id']);

        }
        $ncount = 0;
      
        if (count($notes) != 0) 
        {
            foreach ($notes as $key => $chapternotes) 
            { 
                $ncount +=1;
                if (array_key_exists($chapternotes->chapter_name, $subjectsNotes)) {
                    $subjectsNotes[$chapternotes->chapter_name]['totalNotes'] += 1;
                    if ($chapternotes->note != '') {
                        $subjectsNotes[$chapternotes->chapter_name][$ncount] = $chapternotes->note_id . '-/' . $chapternotes->note.'-/'.$chapternotes->pdf_file_name.'-/'.$chapternotes->file_name.'-/'.$chapternotes->up_down_id;
                    }
                } else {
                    $ncount = 1;
                    if ($chapternotes->note != '') {
                        $subjectsNotes[$chapternotes->chapter_name][$ncount] = $chapternotes->note_id . '-/' . $chapternotes->note.'-/'.$chapternotes->pdf_file_name.'-/'.$chapternotes->file_name.'-/'.$chapternotes->up_down_id;
                        $subjectsNotes[$chapternotes->chapter_name]['updated_date'] = $chapternotes->updated_date;
                        $subjectsNotes[$chapternotes->chapter_name]['container_id'] = $chapternotes->container_id;
                        $subjectsNotes[$chapternotes->chapter_name]['chapter_id'] = $chapternotes->chapter_id;
                    }
                    $subjectsNotes[$chapternotes->chapter_name]['totalNotes'] = 1;
                }
            }
        }
        
        $result = new ViewModel(array(
                    'notes' => $subjectsNotes,
                    'rurl' => $rurl,
                    'subjectId' => $subjectId,
                    'getUserId' => $getUserId,
                ));
        $result->setTerminal(true);
        return $result;
    }
    
 public function childSubjectsAction() 
 {
  //On subjects drop down change list out of child subjects by subject name
   $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
   $containerservice   = $this->getServiceLocator()->get('lms_container_service'); 
     if($auth->hasIdentity()) 
     {
        $userObj = $auth->getIdentity();
        $boardId = $userObj->getBoardId();
        $classId = $userObj->getClassId();
        $userId  = $userObj->getId(); 
     }    
    $output = '';
   // $chaptertable = $this->getServiceLocator()->get('Container\Model\TmainFactory');
    $classparent  = $containerservice->getParentList($_POST['subjectId']);
    $board_id     = $classparent[0]['rack_id'];
    $board_name   = $classparent[0]['rack_name'];
    $class_id     = $classparent[1]['rack_id'];
    $class_name   = $classparent[1]['rack_name'];
    $subject_name = $classparent[2]['rack_name'];
   
    if ($_POST['subjectname'] != '0') 
    {
        $currentChildSubjectss = $this->chachegetSubjectChildList($_POST['subjectId']);//$sbjectstable->getSubjectsByClassId($class_id, $_POST['subjectname'])->toArray();
        $childSubjectDetails   = $currentChildSubjectss;//$this->arrayToObject($currentChildSubjectss);
     }
   
    $output.='';
     if($childSubjectDetails[0]['rack_type_id'] =='7'){
    if (count((array) $childSubjectDetails) > 1) {
        if (isset($_POST['getnotes']) && $_POST['getnotes'] == '1') {
            $rurl = "'" . $_POST["rurl"] . "'";
            $id = $_POST["id"];
            //loded subjects in dropdown box
           
            $output.='<div class="col-sm-5 text-right">Select Title</div>
                    <div class="col-sm-4"><select name="sub-select" id="sub-select1" class="CostomSelect" onchange="getnotes(' . $rurl . ',1,' . $id . ')">
                    ';
            }else{
                //here loded sub subjects in dropdownbox
            $output.='<span>Select sub subject</span>
                    <select name="sub-select" id="sub-select1" class="CostomSelect" onchange="getSubjectsCalender(1)">';
            }
            //echo "<pre />"; print_r($childSubjectDetails); exit;
            foreach ($childSubjectDetails as $childSubjectDetail) {
              if($childSubjectDetail['rack_type_id'] == '7'){
                     if ($childSubjectDetail['id'] != "" || $childSubjectDetail['id'] != 0) {
                        $output.='<option value="' . $childSubjectDetail['id'] . '">' . $childSubjectDetail['subject_name'] . '</option>';
                    }  
                }
            }
            $spanWrapper='<span class="select-wrapper"></span>';
            $spanHolder='<span class="holder"></span>';
            $output.='</select></div><script> jQuery(document).ready(function() {
                $(".CostomSelect").each(function(){
            $(this).wrap('."'".$spanWrapper."'".');
            $(this).after('."'".$spanHolder."'".');
        });
        $(".CostomSelect").change(function(){
            var selectedOption = $(this).find(":selected").text();
            $(this).next(".holder").text(selectedOption);
        }).trigger("change");
        });
        </script>';
         }
        
       }  
        echo $output;
        exit;
    }  
    
   public function ajaxviewnotesAction() {
        
        //Get notes on change of subject
        $subjectsNotes = array();
        $container_id = '';
        $subjectId = '';
        $notes = array();
        $getUserId = '';
         $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
         if($auth->hasIdentity()) {
                $userObj = $auth->getIdentity();
                $boardId = $userObj->getBoardId();
                $classId = $userObj->getClassId();
                $userId  = $userObj->getId(); 
      }
      
        $notesTable = $this->getServiceLocator()->get('Notification\Model\TNotesTable');
        if (isset($_POST['delChapterId']) && $_POST['delChapterId'] != '') {
            $delnotes = $notesTable->deleteNotes($_POST);
        }
        if (isset($_POST['subject_id']) && $_POST['subject_id'] != '') {
            $subjectId = strip_tags($_POST['subject_id']);
            if (isset($_POST['value']) && $_POST['value'] == 1) {
                $userId = strip_tags($_POST['id']);
                $getUserId = strip_tags($_POST['id']);
            } else {
                $userId = $userId;
            }
            $notes = $notesTable->getNotes($userId, $container_id, strip_tags($_POST['subject_id']));
        }
        $ncount = 0;
        if (count($notes) != 0) {
            foreach ($notes as $key => $chapternotes) {
                $ncount +=1;
                if (array_key_exists($chapternotes->name, $subjectsNotes)) {
                    $subjectsNotes[$chapternotes->name]['totalNotes'] += 1;
                    $notedate = date("j F , Y, g:i a",strtotime($chapternotes->updated_date)); 
                    if ($chapternotes->note != '') {
                        $subjectsNotes[$chapternotes->name][$ncount] = $chapternotes->note_id . '-/' . $chapternotes->note.'  ('.$notedate.')';
                    }
                } else {
                    $ncount = 1;
                    if ($chapternotes->note != '') {
                         $notedate = date("j F , Y, g:i a",strtotime($chapternotes->updated_date)); 
                        $subjectsNotes[$chapternotes->name][$ncount] = $chapternotes->note_id . '-/' . $chapternotes->note.'  ('.$notedate.')';
                        //$subjectsNotes[$chapternotes->name]['updated_date'] = $chapternotes->updated_date;
                        $subjectsNotes[$chapternotes->name]['container_id'] = $chapternotes->container_id;
                        $subjectsNotes[$chapternotes->name]['chapter_id'] = $chapternotes->chapter_id;
                    }
                    $subjectsNotes[$chapternotes->name]['totalNotes'] = 1;
                }
            }
        }
        //echo '<pre>'; print_r($subjectsNotes); exit;
        $result = new ViewModel(array(
                    'notes' => $subjectsNotes,
                    'rurl' => $_POST['rurl'],
                    'subjectId' => $subjectId,
                    'getUserId' => $getUserId,
                ));
        $result->setTerminal(true);
        return $result;
    }  
  public function notesPrevAndNextAction() 
  {  
     //Notes pagination for a service
     $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
         if($auth->hasIdentity()) {
                $userObj = $auth->getIdentity();                
                $userId  = $userObj->getId(); 
      }
     // echo "<pre />"; print_r($_POST); exit;
     $notesTable = $this->getServiceLocator()->get('Notification\Model\TNotesTable');
     $totalnotes = $notesTable->gettotalNotes($userId,$_POST['containerId'], $_POST['subject_id'],$_POST['chapter_id']);
     //echo "<pre />"; print_r($_POST); exit;
     $notes = $notesTable->getNotesPrevAndNext($userId, $_POST)->buffer();
     
    // echo "<pre />"; print_r($notes->current());exit;
     $notesView = $notes->current()->note;
     $notes_id  = $notes->current()->note_id;
     $notesfile = $notes->current()->pdf_file_name;
     $currentfile = $notes->current()->file_name;
     $file_id = $notes->current()->up_down_id;
     $notesAll = $notes_id . '-/' . $notesView.'-/'.$notesfile.'-/'.$currentfile.'-/'.$file_id;
     $result = new \Zend\View\Model\JsonModel(array(
               'noteId' => $notesAll,
               'totalnotes' => $totalnotes,
           ));
      return $result;

      echo $notesAll;
    exit;
    }  
    
   public function commentsAction() {
                      
       
       $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
         if($auth->hasIdentity()) {
                $userObj = $auth->getIdentity();
                $boardId = $userObj->getBoardId();
                $classId = $userObj->getClassId();
                $userId  = $userObj->getId(); 
      }
//    echo "<pre />"; print_r($_POST); 
//    echo "<pre />"; print_r($_FILES); exit;
      
      if(isset($_POST['data']))
       {
            $postdata   = explode("&",$_POST['data']);

            $_POST['boardid']     = $postdata[0];
            $_POST['classid']     = $postdata[1];
            $_POST['chapter_id']  = $postdata[2];
            $_POST['subjectId']   = $postdata[3];
            $_POST['commenttext'] = $postdata[4];
            $_POST['questionaskedtoarray'] = json_decode($postdata[5]);
            $_POST['customBoardId']   = $postdata[6];
            $_POST['for_ques']    = '1';
            $_POST['image_file']  = '';
            $_POST['file_name']   = '';
            
            if(is_uploaded_file(@$_FILES['file']['tmp_name'])){  
                  $file_name  = $_FILES['file'];
                  $filename              = $file_name['name'];
                  $filepath              = $file_name['tmp_name'];
                  $filetype              = $file_name['type'];
                  $commonObj = $this->getServiceLocator()->get("com_mapper");
                  $resdata =$commonObj->is_correctfile($filepath);
                
                 if($resdata == 'true'){
                   $ext                 = pathinfo($filename, PATHINFO_EXTENSION);
                   $changefilename      = uniqid().'.'.$ext;
                   $fileUploaded        = $this->ftpFileUploaded($filepath, '/uploads/questionimages/' . $changefilename);
                   $_POST['image_file'] = $changefilename;
                   $_POST['file_name'] = $filename;
                   
                }else{
                        $result = new \Zend\View\Model\JsonModel(array(					
                                         'output'=> 'error',				
                                    ));
                        return $result;  
                }
           }
        }  
      
      $containerservice   = $this->getServiceLocator()->get('lms_container_service');
        //Add comment to a service group conversation 
       $postdata = $this->escaper(array("chapter_id"=>$_POST['chapter_id'],"subjectId"=>$_POST['subjectId']));
       
        if (isset($userId) && $userId != "") {
            $currentchapter_details = $containerservice->getParentList($postdata['chapter_id']);
           
            $_POST['boardid'] = $currentchapter_details['0']['rack_id'];
            $_POST['classid'] = $currentchapter_details['1']['rack_id'];
            $commentTable = $this->getServiceLocator()->get('Notification\Model\TquestionTable');
            $uploadTable = $this->getServiceLocator()->get('Assessment\Model\TuploadsdownloadsTable');
            $comMapperObj = $this->getServiceLocator()->get("com_mapper");
            $_POST['customBoardRackID'] = $comMapperObj->getcustomboardPrimaryId($_POST['customBoardId'],$_POST['chapter_id']); 
            if (isset($_POST) && $_POST != "") {
                if (isset($_POST['questionaskedtoarray'])) {
                    foreach ($_POST['questionaskedtoarray'] as $askedto) {
                        $id = explode("_", $askedto);
                        $_POST['questionaskedto'] = $id['1'];
                        if($_POST['questionaskedto'] == 'group')
                          {
                             $_POST['groupownerId'] = $userId; 
                          }else{
                              $_POST['groupownerId']   = $id['0'];
                              $_POST['stud_mentor_id'] = $id['2'];
                          }
                        $addcomment = $commentTable->savecomment($_POST,$userId);
                        $uploadTable->addquesDetails($_POST,$userId,$addcomment);
                    }
                } else {
                    $addcomment = $commentTable->savecomment($_POST,$userId);
                    $uploadTable->addquesDetails($_POST,$userId,$addcomment);
                }
                if ($addcomment > 0) {
                   
                        $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
                        $userId = $userId;
                        //Code to get group conversation 
                        $groupids = $table->getallrecords($userId);
                        $group_userids = array();
                        $ids = '';
                        if ($groupids->count()) {
                            foreach ($groupids as $idds) {
                                $ids .= "'" . $idds->friend_id . "'" . ",";
                                $ids .= "'" . $idds->user_id . "'" . ",";
                            }
                        }
                        $tIds = rtrim($ids, ",");
                        if ($tIds != '') {
                            $friendGroupIds = $table->getallrecords($tIds, $userId);
                            if ($friendGroupIds->count()) {
                                foreach ($friendGroupIds as $fof) {
                                    $tIds .= ",'" . $fof->friend_id . "'";
                                }
                            }
                        }
                        if ($tIds != '') {
                            $tIds .= ",'" . $userId . "'";
                        } else {
                            $tIds .= "'" . $userId . "'";
                        }
                        //End of code to get group conversation 
                       
                                 
                       if($tIds!='' && $_POST['chapter_id']!=''){
                        $countComments = $commentTable->countComments($tIds, $_POST['chapter_id'], $type = 'container',$userId);
                     
                        $currentPage = 1;
                        $resultsPerPage = 5;
                        $paginator = $commentTable->getComments($tIds, $_POST['chapter_id'], $type = 'container', $currentPage, $resultsPerPage,$userId);
                       
                       //This function to get chapterdetails by chapterId
                        $currentchapter_details = $containerservice->getParentList($_POST['chapter_id']);
                      
                        
                        if (isset($_POST['groupownerId']) && $_POST['groupownerId'] != '') {
                        
                        
                          $result = new ViewModel(array(
                                    'get_comments' => $paginator,
                                    'currentPage' => $currentPage,
                                    'resultsPerPage' => $resultsPerPage,
                                    'countComments' => $paginator->count(),
                                    'countposts' => $countComments,
                                    'currentchapter_details' => $currentchapter_details,
                                    'output' => 'success',
                                    'userId' =>$userId, 
                                ));
                          $result->setTerminal(true);
                           return $result;
                        }else{
                              $result = new ViewModel(array(
                               'get_comments' => $paginator,
                               'currentPage' => $currentPage,
                               'resultsPerPage' => $resultsPerPage,
                               'countComments' => $paginator->count(),
                               'countposts' => $countComments,
                               'currentchapter_details' => $currentchapter_details,
                               'userId' =>$userId,   
                                ));
                              $result->setTerminal(true);
                               return $result;
                          }
                       }
                     
                         if (isset($_POST['groupownerId']) && $_POST['groupownerId'] != '') {                             
                              $result = new \Zend\View\Model\JsonModel(array(					
						'output' 		=> 'success',				
					));
                                        return $result;
                            }
                      
                } else {
                    $result = new \Zend\View\Model\JsonModel(array(
                                'output' => 'notsuccess',
                            ));                     
                     return $result;
            
                }
              
            }
        }
    }
 public function mentorquestionAction(){
    
   $auth = $this->getServiceLocator()->get('zfcuser_auth_service');    
    if(!$auth->hasIdentity()){        
        $result = new ViewModel(array('currentPage' => '',));
        $result->setTerminal(true);
         return $result;
    }
    if($auth->hasIdentity()) {
                $userObj = $auth->getIdentity();
                $boardId = $userObj->getBoardId();
                $classId = $userObj->getClassId();
                $userId  = $userObj->getId(); 
      } 
      
    $chepter_id = '';
    $chepter_id = $this->params()->fromRoute('param1');
    $chepter_id = $chepter_id;  
    $containerservice       = $this->getServiceLocator()->get('lms_container_service');
    $currentchapter_details = $containerservice->getParentList($chepter_id);
   // echo "<pre />"; print_r($currentchapter_details); exit;
    $subject_id = $currentchapter_details['2']['rack_id'];    
    $table      = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
    //This function get the all mentors of loged student.
    $addedMentors = $table->getAllStudents($userId, 'student', '', $subject_id)->toArray();
    
    
    $countres     = count($addedMentors);
    $result = new \Zend\View\Model\JsonModel(array(
                                'output' => $addedMentors,
                                'counts' =>$countres,
                            ));                     
                     return $result;

  }    
    
 public function commentlistsAction()
 {
    $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
    
    if(!$auth->hasIdentity()){
        
        $result = new ViewModel(array('currentPage' => '',));
        $result->setTerminal(true);
         return $result;
    }
    if($auth->hasIdentity()) {
                $userObj = $auth->getIdentity();
                $boardId = $userObj->getBoardId();
                $classId = $userObj->getClassId();
                $userId  = $userObj->getId(); 
      }    
    $containerservice   = $this->getServiceLocator()->get('lms_container_service');
    
    $chepter_id = '';
    $chepter_id = $this->params()->fromRoute('param1');
    $chepter_id = ($chepter_id =='')?"6010":$chepter_id;
   //Code to get user group comments to a service 
        $paginator = "";
        $countComments = "";
        $countposts = "";
        $tIds = '';
        if (isset($userId) && $userId != "") {
            $table     = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
            $userId    = $userId;
            $groupids  = $table->getallrecords($userId);
            $group_userids = array();
            $ids = '';
            if ($groupids->count()) {
                foreach ($groupids as $idds) {
                    $ids .= "'" . $idds->friend_id . "'" . ",";
                    $ids .= "'" . $idds->user_id . "'" . ",";
                }
            }
            if ($ids != '') {
                $tIds = trim($ids, ',');
            } else {
                $tIds = "'" . $userId . "'";
            }
            if ($tIds != '') {
                $friendGroupIds = $table->getallrecords($tIds, $userId);
                if ($friendGroupIds->count()) {
                    foreach ($friendGroupIds as $fof) {
                        $tIds .= ",'" . $fof->friend_id . "'";
                    }
                }
            }
            $tIds .= ",'" . $userId . "'";
            $commentTable = $this->getServiceLocator()->get('Notification\Model\TquestionTable');
            $countComments = $commentTable->countComments($tIds, $chepter_id, $type = 'container',$userId);
            $currentPage = 1;
            $resultsPerPage = 5;
            $paginator = $commentTable->getComments($tIds, $chepter_id, $type = 'container', $currentPage, $resultsPerPage,$userId);
        } 
        $tIds .= ",'" . $userId . "'";
        $commentTable  = $this->getServiceLocator()->get('Notification\Model\TquestionTable');
        $countComments = $commentTable->countComments($tIds, $chepter_id, $type = 'container',$userId);
        $currentchapter_details = $containerservice->getParentList($chepter_id); 
            
        $result = new ViewModel(array('currentPage' => $currentPage,
                                      'resultsPerPage' => $resultsPerPage, 
                                      'get_comments' => $paginator, 
                                      'countposts' => $countposts, 
                                      'currentchapter_details' => $currentchapter_details,
                                      'userId' =>$userId,
            ));
        $result->setTerminal(true);
         return $result;
 }
 
 

public function replyQuestionAction() {
    $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
    if($auth->hasIdentity()) {
                $userObj = $auth->getIdentity();
                $boardId = $userObj->getBoardId();
                $classId = $userObj->getClassId();
                $userId  = $userObj->getId(); 
      }    
        if (isset($_POST['questionid']) && $_POST['questionid'] != "") {
            $replyonquestion = $this->getServiceLocator()->get('Notification\Model\TreplyonquestionTable');
            $getReplys = $replyonquestion->getReplys($_POST,$userId);
            $count_answers = $replyonquestion->countReplys($_POST);
            
            $viewModel = new ViewModel();
            $template = 'notification/index/replyquestion.phtml';
            $viewModel->setTemplate($template)
                      ->setVariables(array(
                        'getreply' => $getReplys,
                        'count_reply' => $count_answers,
                    ));
            $viewModel->setTerminal(true);
            return $viewModel;

        }
    }

    //public function replyAddAction add reply question based on questionId and counts the each question replys returns JsonData
    public function replyaddAction() {
    
    if(isset($_POST['data'])){
      
      $postdata   = explode("&",$_POST['data']);
      $_POST['userid']       = $postdata[0];
      $_POST['questionid']   = $postdata[1];
      $_POST['replymessage'] = $postdata[2];
      $_POST['image_file']   = '';
      $_POST['for_ques']     = '2';
      $_POST['file_name']    = '';
      
      if(is_uploaded_file(@$_FILES['file']['tmp_name'])){  
            $file_name = $_FILES['file'];
            $filename  = $file_name['name'];
            $filepath  = $file_name['tmp_name'];
            $filetype  = $file_name['type'];
            $typeinfo  = explode("/",$filetype);
            $commonObj = $this->getServiceLocator()->get("com_mapper");
          if($typeinfo[0] == 'image'){
           $resdata =$commonObj->is_correctfile($filepath);
          }
          if($resdata == 'true'){
             $ext                 = pathinfo($filename, PATHINFO_EXTENSION);
             $changefilename      = uniqid().'.'.$ext;
             $fileUploaded        = $this->ftpFileUploaded($filepath, '/uploads/questionimages/' . $changefilename);
              $_POST['image_file']= $changefilename;
              $_POST['file_name'] = $filename;
          }else{
              $result = new \Zend\View\Model\JsonModel(array(
                            'output' => 'error'));
                return $result;
          }
     }  
     $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
     if($auth->hasIdentity()) {
                $userObj = $auth->getIdentity();
                $boardId = $userObj->getBoardId();
                $classId = $userObj->getClassId();
                $userId  = $userObj->getId(); 
      }    
        if (isset($_POST['questionid']) && $_POST['questionid'] != "") {
            $replyonquestion = $this->getServiceLocator()->get('Notification\Model\TreplyonquestionTable');
            $uploadTable = $this->getServiceLocator()->get('Assessment\Model\TuploadsdownloadsTable');
            
            $addReply = $replyonquestion->addReply($_POST,$userId);
            $_POST['customBoardRackID']=NULL;
            $uploadTable->addquesDetails($_POST,$userId,$addReply);  
            
            $count_answers = $replyonquestion->countReplys($_POST);
            if ($addReply > 0) {
                $result = new \Zend\View\Model\JsonModel(array(
                            'output' => 'success',
                            'count_reply' => $count_answers,
                        ));
                return $result;
            }
        }
        
    }
  }
  
  public function ftpFileUploaded($sourcePath, $targetPath)
  {
        // echo $sourcePath;echo '<pre>';echo $targetPath;die;
     
     $config     = $this->getServiceLocator()->get('config');
     $ftpDetails = $config['ftp_config'];
  
     $conn_id = ftp_connect($ftpDetails['FTP_SERVER']);       
     $login_result = ftp_login($conn_id, $ftpDetails['FTP_USERNAME'], $ftpDetails['FTP_PASSWORD']); // ftp login     
    //ftp_mkdir($conn_id, $dir);
    //ftp_chmod($conn_id, 0777, $dir);
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
        return $fileStatus;
    }
    
    public function nextprevideoAction(){
        $id = $this->getRequest()->getPost('id');    
        $tickertable = $this->getServiceLocator()->get('Assessment\Model\TickerTable');
         $tickervideo =$tickertable->getmaskbyid($id);
         $videodata = $tickervideo->current();
         
         /// Next previous value ////         
           $nextdata = $tickertable->getnextdatabyid($id);
            $nextvalue  = $nextdata->current();            
            $predata = $tickertable->getpredatabyid($id);          
            $prevalue  = $predata->current();                      
           
            $result = new ViewModel(array(
            'videodata' =>$videodata,
              'nextvalue'=>@$nextvalue->ticker_id,
              'prevalue'=>@$prevalue->ticker_id,
          ));
           $result->setTerminal(true);
            return $result;        
    }
    
   public function forcedownloadAction() {
//      if(isset($_SESSION['user']['userId']) && $_SESSION['user']['userId']!=""){
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $file = $this->params()->fromQuery('file');
            $id   = $this->params()->fromQuery('id');
            
            $filename = 'public/uploads/questionimages/' . $file;
            $uploaddownload = $this->getServiceLocator()->get('Assessment\Model\TuploadsdownloadsTable');
            $fileNamecurrent = $uploaddownload->getcurrentfilename($id)->file_name; 
           
            $uploaddownload->output_file($filename, $file, '',$fileNamecurrent);
        }
    } 
 
  public function escaper($arr)
  {
      $postArray = array();
      while (list($var, $val) = each($arr)) {
        $escaper = new Escaper('utf-8');
        $output  = $escaper->escapeHtmlAttr($val);
        $output  = $escaper->escapeHtml($output);
        $output  = $escaper->escapeJs($output);
        $output  = $escaper->escapeCss($output);
        $output  = $escaper->escapeUrl($output);
        $postArray[$var] = $output;  
    }
      return $postArray;  
   }     
 

}/*End of Class*/

?>
