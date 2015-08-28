<?php

namespace User\Controller;

use Zend\Http\Request;
use Api\Controller\SmsController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
// use Zend\Session\Container; // We need this when using sessions
use Zend\Cache\StorageFactory;
use Zend\Session\SaveHandler\Cache;
use ZfcUser\Options\UserControllerOptionsInterface;
use Zend\Session\SessionManager;
use ZfcUser\Service\User as UserService;
use Zend\ViewModel\JsonModel;
use Zend\Escaper\Escaper;
use Zend\Form\Form;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;

class IndexController extends AbstractActionController {

    protected $service, $mentorAssignPaperTable;
    protected $changePasswordForm;
    protected $lmsService;
    
    const ROUTE_CHANGEPASSWD = 'zfcuser/changepassword';

    public function getService() {
        if (!$this->service) {
            $this->service = $this->getServiceLocator()->get('lms_container_service');
        }
        return $this->service;
    }
    
    /**
     * @var UserControllerOptionsInterface
     */
    protected $options;
    
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
    
    function getMentorAssignPaperTable() {
        if (!$this->mentorAssignPaperTable) {

            $sm = $this->getServiceLocator();
            $this->mentorAssignPaperTable = $sm->get('Assessment\Model\MentorAssignPaperTable');
        }
        return $this->mentorAssignPaperTable;
    }

    public function myprofileAction() {
        
        $userdetails = array();
        $user = array();
        $nameChild = '';
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        $nav_array = array();
        $studentId = $this->params()->fromRoute('id');
        $boarddetail='';
        if ($auth->hasIdentity()) {
            $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
            $userObj = $auth->getIdentity();
            $userid = $userObj->getId();
            /*$userTypeId = $userObj->getUserTypeId();
            if(isset($_SESSION['user_role']) && $_SESSION['user_role']!='') {
                $userTypeId =  $_SESSION['user_role'];
            }
            if($userTypeId==1) {
                $userRowSet = $table->getprofilebyid($userid);
                $userdetails = $userRowSet->current();
                $board_id = $userdetails->boardId;
                $class_id = $userdetails->classId;
                $boardName = $userdetails->boardName;
                $customBoardName = $userdetails->customBoardName;
                echo $board_id.' '.$class_id.' '.$boardName.' '.$customBoardName; exit;
            }*/
            //echo $userTypeId; exit;
            //echo '<pre>'; print_r($userObj); exit;
            
            $scnauthMapperObj = $this->getServiceLocator()->get("scnauth_mapper");
            $socialProvider = $scnauthMapperObj->findProvidersByUser($userObj);

//            if(count($socialProvider)>0){
//                foreach($socialProvider as $key => $val){
//                    $providerName = $key;
//                }
//            }
            $tableuser = $this->getServiceLocator()->get('Assessment\Model\UserTable');
            if ($userObj->getUserTypeId() == 10) { // admin redirect
                return $this->redirect()->toRoute('admin/misreport');
            }
            
            $childData = $tableuser->getChildData($userid);
            $childDataCount = $tableuser->countChildData($userid);
            
            //$childDataCount = count($childData);
            if ($childDataCount == 0) {
                $tableparentchild = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
                $childData = $tableparentchild->getChildData($userid);
                $childDataCount = $tableparentchild->countChildparentData($userid); 
                //$childDataCount = count($childData);
            }
//            echo '<pre>';print_r ($childDataCount);echo '</pre>';die('vikash');
            $userLogObj = $this->getServiceLocator()->get("lms_container_mapper");
            
            $isClass = false;
            $classDataObj = $userLogObj->getContainer($userObj->getClassId());
            if (is_object($classDataObj)) {
                if (is_object($classDataObj->getRackType())) {
                    $isClass = ($classDataObj->getRackType()->getTypeName() == 'class') ? true : false;
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
            if ($userObj->getUserTypeId() == 2) {
                $usertable = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                $childData = $usertable->getChildData($userid);
                $childcount = $usertable->countChildData($userid);
                //$childcount = count($childData);
            }
            $comMapperObj = $this->getServiceLocator()->get("com_mapper");
            $countryData = $comMapperObj->getAllCountries();
            
            $userCountryId = $userObj->getCountryId();
            $userStateId = $userObj->getStateId();
            $userCity = $userObj->getOtherCity();
            $statelist = $comMapperObj->getCountarybystate($userCountryId);
            if(!empty($userObj->getCustomBoardRackId()))
            {
                 $this->websiteMapper = $this->getServiceLocator()->get('website_mapper');
                 $boarddetail =  $this->websiteMapper->getcustomboarddetail($userObj->getCustomBoardRackId());
                
            }
            
            
            
            $viewModel = new ViewModel(
                    array(
                        'userObj' => $userObj,
                        'addedMentors' => $addedMentors,
                        'addedStudents' => $addedStudents,
                        'userLogCount' => $userLogCount,
                        'boardList' => $valueOptions,
                        'countryData' => $countryData,
                        'social' => count($socialProvider),
        //              'providerName' => $providerName,
                        'childDataCount' => $childDataCount,
                        'isClass' => $isClass,
                        'childcount' => $childcount,
                        'states' => $statelist,
                        'userCountryId' => $userCountryId,
                        'userStateId' => $userStateId,
                        'customBord'=>$boarddetail,
                        'userCity' => $userCity
                    )
            );
            
            return $viewModel;
        }
    }
    
    public function studentProfileAction() {        
            $userdetails = array();
            $user = array();
            $nameChild = '';
            $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
            $service_obj = $this->getServiceLocator()->get('lms_container_service');
            $nav_array = array();
            $studentId = $this->params()->fromRoute('id');
            if ($auth->hasIdentity()) {
                $loggedinUserObj = $auth->getIdentity();
                $tableuser = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                //$userid = $userObj->getId();
                if(isset($studentId) && !empty($studentId)) {
                    $userid = $studentId;
                    $userObj = $tableuser->getuserdetailsById($userid);
                    //echo '<pre>'; print_r($userObj); exit;
                }
                //echo $userid; exit;
                foreach($userObj as $user) {
                    $userArr = (array)$user;
                    $classId = $userArr['classId'];
                    $userTypeId = $userArr['user_type_id'];
                    $userCountryId = $userArr['country_id'];
                    $userStateId = $userArr['state_id'];
                    $userCity = $userArr['other_city'];
                }
                
                $classname = '';
                $allParents = $service_obj->getParentList($classId);
                foreach($allParents as $parent) {
                    if(isset($parent['class'])) {
                        foreach($parent['class'] as $parentclass) {
                            //echo $parentclass['rack_id'].'===='.$classId.'<br>';
                            if($parentclass['rack_id']==$classId){
                                $classname = $parentclass['rack_name'];
                            }
                        }
                    }
                    //echo '<pre>'; print_r($parent); exit;
                }
                $classname = !empty($classname)?$classname:'N/A';
                
                $socialProvider = array();
                //echo '<pre>'; print_r($userObj); exit;
                //$userObj = $auth->getIdentity();
                //$scnauthMapperObj = $this->getServiceLocator()->get("scnauth_mapper");
                //$socialProvider = $scnauthMapperObj->findProvidersByUser($userObj);
                //echo '<pre>'; print_r($socialProvider); exit;

                if ($userTypeId == 10) { // admin redirect
                    return $this->redirect()->toRoute('admin/misreport');
                }

                $childData = $tableuser->getChildData($userid);
                $childDataCount = $tableuser->countChildData($userid);

                //$childDataCount = count($childData);
                if ($childDataCount == 0) {
                    $tableparentchild = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
                    $childData = $tableparentchild->getChildData($userid);
                    $childDataCount = $tableparentchild->countChildparentData($userid); 
                    //$childDataCount = count($childData);
                }
    //            echo '<pre>';print_r ($childDataCount);echo '</pre>';die('vikash');
                $userLogObj = $this->getServiceLocator()->get("lms_container_mapper");


                //echo $userObj->getUserId(); exit;
                //echo $userObj->getClassId(); exit;
                $isClass = false;
                $classDataObj = $userLogObj->getContainer($classId);
                if (is_object($classDataObj)) {
                    if (is_object($classDataObj->getRackType())) {
                        $isClass = ($classDataObj->getRackType()->getTypeName() == 'class') ? true : false;
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
                if ($userTypeId == 2) {
                    $usertable = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                    $childData = $usertable->getChildData($userid);
                    $childcount = $usertable->countChildData($userid);
                    //$childcount = count($childData);
                }
                $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                $countryData = $comMapperObj->getAllCountries();

    //            $userCountryId = $userObj->getCountryId();
    //            $userStateId = $userObj->getStateId();
    //            $userCity = $userObj->getOtherCity();
                $statelist = $comMapperObj->getCountarybystate($userCountryId);
                //echo '<pre>'; print_r($userCity); 
                //echo $userCity;
                //echo '<pre>'; print_r($addedStudents); exit;
                $viewModel = new ViewModel(
                        array(
                            'loggedinUserObj' => $loggedinUserObj,        
                            'userObj' => $userArr,
                            'addedMentors' => $addedMentors,
                            'addedStudents' => $addedStudents,
                            'userLogCount' => $userLogCount,
                            'boardList' => $valueOptions,
                            'countryData' => $countryData,
                            'social' => count($socialProvider),
                            //'providerName' => $providerName,
                            'childDataCount' => $childDataCount,
                            'isClass' => $isClass,
                            'childcount' => $childcount,
                            'states' => $statelist,
                            'userCountryId' => $userCountryId,
                            'userStateId' => $userStateId,
                            'userCity' => $userCity,
                            'className' => $classname,
                            'student_profile' => true
                        )
                );
                
                //$this->layout('layout/layout.phtml');    
                //$viewModel->setTerminal(false);
                return $viewModel;
            }
    }

    //public function myGroupsAction creates group ,get active status group members details based on userName .It returns view page
    public function myGroupsAction() {
        
        $userdetails = array();
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        if ($auth->hasIdentity()) {
            $userObj = $auth->getIdentity();
            $userId = $userObj->getId();
            $useremail = $userObj->getEmail();

            $tabview = $this->params()->fromRoute('id', 0);
            $tablestudent = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
            // This function get the all mentors of student.
            $addedMentor = $tablestudent->getAll($userId, 'student');
            $addedMentors = $addedMentor->buffer();

            $addedStudent = $tablestudent->getAll($userId, 'mentor');
            $addedStudents = $addedStudent->buffer();
            //echo '<pre>'; print_r($_REQUEST);
            $requestIds = array();
            $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
            // Get all group members of loged user
            $studentNames = $table->getAllfriends($userId);

         $addedStudent = $tablestudent->getAll($userId, 'mentor');
         $addedStudents = $addedStudent->buffer();
         //echo '<pre>'; print_r($addedMentors); exit;
         $requestIds = array();
         $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
         // Get all group members of loged user
         $studentNames = $table->getAllfriends($userId);
         
         $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
         // Get All add member request of loged user
         $getyourRequestss = $table->getAllfriendsrequests($userId);
         $getyourRequests = $getyourRequestss->buffer();

          if(count($getyourRequests) != 0) {
            foreach ($getyourRequests as $friendIds) 
            {
               $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
               // Check the status of group members of loged user
               $studentIds = $table->checkStatusOfGroupIds($friendIds->user_id, $userId);
               if ($studentIds != 0) {
                     $requestIds[$friendIds->user_id]['requestStatus'] = 1;
                    } else {
                     $requestIds[$friendIds->user_id]['requestStatus'] = 0;
                    }
                }
            }
            
            // Get the loged user details         
            $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');           
            $groupownerids = $table->getgroupowner($userId);
            //get group members of loged user
            $groupmemberids = $table->getAllActivefriends($userId);            
            //get group list of all     
            $groupusers = $table->getAllgroupfriends($userId);
          
            // array of all users whose query will be displayed
            $ids      = '';
            $tIds     = '';
            $groupids = '';
            foreach ($groupmemberids as $idds) {
                $ids .= "'" . $idds->friend_id . "'" . ",";
            }
            foreach ($groupusers as $idds) {
                $ids .= "'" . $idds->user_id . "'" . ",";
            }

            foreach ($groupownerids as $gids) {
                $groupids .= "'" . $gids->user_id . "'" . ",";
            }
            if ($groupids == '') {
                $groupids .= "'" . $userId . "'";
            }
            $group_userids = array();            
            $ids .= "'" . $userId . "'" . ",";
            $tIdss = rtrim($ids, ",");

            $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
            // Get user names of group members of loged user
            $userNames      = $table->groupNames($tIdss);
        
            $commentTable   = $this->getServiceLocator()->get('Assessment\Model\UserQuestionTable');
            $currentPage    = 1;
            $resultsPerPage = 2;
            // Get total comments based on All group members of loged user
            $totalCount = $commentTable->totalCount($tIdss, '', '', '', '', '', '', '', rtrim($groupids, ','));
            // Get total comments and total questions based on All group members of loged user
            

            $paginator = $commentTable->getQestions($tIdss, '', '', '', '', '', $currentPage, $resultsPerPage, rtrim($groupids, ','));
            $paginator->buffer();
          
            $boardList = $this->getService()->getCustomBoardList();
            
                $valueOptions = array();
                //$valueOptions[''] = 'Select Board';
                foreach ($boardList as $key=>$container) {
                    $valueOptions[$container[0]['customBoard']['customBoardId']] = $container[0]['customBoard']['boardName'];
                }
            // get my group active member count 
            $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
            $activefriends = $table->getAllActivefriends($userId);

            $activefriendcount = count($activefriends);
            $tabId = $this->params()->fromRoute('id');
            $tabId = (isset($tabId)) ? $this->params()->fromRoute('id') : '1';

            if ((count($studentNames) > 0) || (count($getyourRequests) > 0)) {
                $viewModel = new ViewModel(array(
                    'getstudentdetails' => $studentNames,
                    'getyourRequests' => $getyourRequests,
                    'getuserdetails' => $userdetails,
                    'boards' => $valueOptions,
                    'userObj' => $userObj,
                    'getQuestions' => $paginator,
                    'userNames' => $userNames,
                    'countComments' => $paginator,
                    'countposts' => $totalCount,
                    'currentPage' => $currentPage,
                    'resultsPerPage' => $resultsPerPage,
                    'requestIds' => $requestIds,
                    'addedMentors' => $addedMentors,
                    'addedStudents' => $addedStudents,
                    'tabview' => $tabview,
                    'activefriendcount' => $activefriendcount,
                     'userId' => $userId,
                    'tabId' => $tabId,
                ));
            } else {
                $viewModel = new ViewModel(array(
                    // 'boards' => $boarddetails,
                    'getuserdetails' => $userdetails,
                    'userObj' => $userObj,
                    'getQuestions' => $paginator,
                    'countComments' => $paginator,
                    'countposts' => $totalCount,
                    'currentPage' => $currentPage,
                    'resultsPerPage' => $resultsPerPage,
                    'requestIds' => $requestIds,
                    'addedMentors' => $addedMentors,
                    'addedStudents' => $addedStudents,
                    'tabview' => $tabview,
                    'activefriendcount' => $activefriendcount,
                    'userId' => $userId,
                    'tabId' => $tabId,
                ));
            }
            return $viewModel;
        }else{
            return $this->redirect()->toRoute('home');
        }
    }

    public function mySubscriptionsAction() {
        $packages = array();
        $orders = array();
        $ordersDetailes = array();
        $userdetails = array();
        $userorder = array();
        $satpackage = array();

        if ($this->zfcUserAuthentication()->hasIdentity()) {

            $loggedIUserObj = $this->zfcUserAuthentication()->getIdentity();
            // getting user type either(Student, Mentor, Teacher)
            $userTypeId = $loggedIUserObj->getUserTypeId();

            $userId = $loggedIUserObj->getId();



            //echo $_SESSION['user']['userId']; die;
            $tablestudent = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
            $packageclasstable = $this->getServiceLocator()->get('Package\Model\TpackageclassesTable');
            // This function get the all mentors of student.
            $addedMentor = $tablestudent->getAll($userId, 'student');
            $addedMentors = $addedMentor->buffer();

            $addedStudent = $tablestudent->getAll($userId, 'mentor');
            $addedStudents = $addedStudent->buffer();

            $table = $this->getServiceLocator()->get('Assessment\Model\TuserpackageTable');
            // This function get the all subscribed packages of loged user.            
            $packages = $table->getPackages($userId, 'all', 'all', 'all_active_inactive');

            // This function get the all orders of subscribed packages of loged user
            $orders = $packages->buffer();
            // This function get the all order details of subscribed packages of loged user
           // $ordersDetailes = $table->getPackages($userId, 'one', $count = 1, 'all');


            $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
            // This function get the child user detailes of loged user
            $user = $table->getuserdetailsForSubscription($userId, $userTypeId);
            $nextclassnameArray = array('');
            foreach ($packages as $packagelist) { //print_r($packagelist);die;
                if ($packagelist->package_category == '1') {
                    $nextclassList = $packageclasstable->getnextClass($packagelist->actual_package_id);
                    $classList = $this->getServiceLocator()->get('lms_container_service')->getContainerList($nextclassList->current()->class_id);
                    foreach ($classList as $class) {

                        $nextclassnameArray[$packagelist->actual_package_id] = $class->getRackName()->getName();
                    }
                }
            } //echo '<pre>';
            //print_r($nextclassnameArray);die;
            return new ViewModel(array(
                'packages' => $packages,
                'orders' => $orders,
               // 'ordersDetailes' => $ordersDetailes,
                'getuserdetails' => $userdetails,
                'addedMentors' => $addedMentors,
                'addedStudents' => $addedStudents,
                'nextclassnameArray' => $nextclassnameArray,
                    // 'satpackages' =>$satpackage,
            ));
        } else {
            return $this->redirect()->toRoute('home');
        }
    }

    public function ajaxSubscriptionsAction() {
        if (isset($_POST['packageId'])) {
            if ($this->zfcUserAuthentication()->hasIdentity()) {
                $loggedIUserObj = $this->zfcUserAuthentication()->getIdentity();
                // getting user type either(Student, Mentor, Teacher)
                $userTypeId = $loggedIUserObj->getUserTypeId();
                $userid = $loggedIUserObj->getId();
                $ordersDetailes = array();
                $table = $this->getServiceLocator()->get('Assessment\Model\TuserpackageTable');
                $parentchildtable = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
                $usertable = $this->getServiceLocator()->get('Assessment\Model\userTable');
                // This function get the all order details of subscribed packages of loged user
                $ordersDetailes = $table->getPackages('', $_POST['packageId'], 'one', 'all');
                $package_user_id = $ordersDetailes->current()->user_id;
                $status=1;
               $parentchildrelation=$parentchildtable->getrelation($userid,$package_user_id,$status); 
               $parentData = $usertable->getuserdetailsById($package_user_id);
               $parentDataArray = $parentData->current();
               $parent_id = $parentDataArray->parent_id;
                if ($userid == $package_user_id || ($parentchildrelation>0 || (isset($parent_id) && ($parent_id ==$userid)))) {
                    $tablepackageclass = $this->getServiceLocator()->get('Package\Model\TpackageclassesTable');
                    $nextclassDetails = $tablepackageclass->getnextClass($_POST['actual_package_id']);
                    $transaction_id = $ordersDetailes->current()->transaction_id;
                    $nextClassOrdersDetailes = $table->getInactivePackages($transaction_id,$_POST['actual_package_id']);
                    $ordersDetailes = $table->getPackages('', $_POST['packageId'], 'one', 'all');
                    $nextclassname = '';
                if(isset($_POST['package_category']) && $_POST['package_category']=='1'){
                        $classList = $this->getServiceLocator()->get('lms_container_service')->getContainerList($nextclassDetails->current()->class_id);
                        foreach ($classList as $class) {

                            $nextclassname = $class->getRackName()->getName(); //die;
                        }
              }
                    if (count($ordersDetailes) != 0) {
                        $result = new ViewModel(array(
                            'ordersDetailes' => $ordersDetailes,
                            'user_package_id' => $_POST['packageId'],
                            'nextclassDetails' => $nextclassDetails->current(),
                            'nextclassname'=>$nextclassname,
                            'nextClassOrdersDetailes' => $nextClassOrdersDetailes,
                        ));
                    }
                    $result->setTerminal(true);
                    return $result;
                }
            }
        }die;
    }

    public function myMentorAction() {
//       echo '<pre>';print_r('jkh');echo '</pre>';die('Macro Die');
        $mentors = array();
        $mentorss = '';
        $addedMentors = array();
        $addedMentorss = array();
        $userdetails = array();
        $subjectArray = array();
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');

        if ($auth->hasIdentity()) {
            $userObj = $auth->getIdentity();
//     $table = $this->getServiceLocator()->get('ZfcUser\Model\TusertypeTable');
            // This function get the all user types.
//            $mentors = $table->getAll();
//            $table = $this->getServiceLocator()->get('ZfcUser\Model\TuserTable');
//            // This function get the loged user detailes.
//            $user = $table->getuserdetailsById($_SESSION['user']['userId']);
            // Auto Complete mentor names based on student board and Class
//            $mentorNames = $table->getMentorNames($user->buffer()->current()->board_id, $user->buffer()->current()->class_id);
//            $mentorssIds = array();
//            if (count($mentorNames) != 0) {
//                foreach ($mentorNames as $names) {
//                    if ($names->subject_id != 0) {
//                        $mentorss.= '"' . $names->first_name . '"' . ',';
//                        $mentorssIds[$names->first_name] = $names->user_id;
//                    }
//                }
//            }
//            $mentor_names = rtrim($mentorss, ",");
            $userid = $userObj->getId();
            $table = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');

            // This function get the all mentors of student.
            $addedMentor = $table->getAll($userid, 'student');
            $addedMentors = $addedMentor->buffer();
            foreach ($addedMentors as $addedMentorss) {
                //echo "<pre />";  print_r($addedMentorss);
                if ($addedMentorss->subject_id != '') {
                    $subjectArray[$addedMentorss->id]['subjectId'] = $addedMentorss->subject_id;
                }
            }

            $addedStudent = $table->getAll($userid, 'mentor');
            $addedStudents = $addedStudent->buffer();

            // echo "<pre />"; print_r($addedMentors);exit;
//            foreach ($user as $userChildName) {
//                $userdetails = $userChildName;
//            }
            // board class   
//         $basesettings = $this->getServiceLocator()->get('config');
//        $constantarray = $basesettings['constants'];
//        $junior_board_name = $constantarray['junior_board_name'];
//        $boardtable = $this->getServiceLocator()->get('Container\Model\TmainFactory');
//        $boarddetail = $boardtable->getBoards('', $junior_board_name)->toArray();
//        $boarddetails = $this->arrayToObject($boarddetail);
//        
//          $currentboardid = $_SESSION['currentBoardId'];
//          $currentclassid = $_SESSION['currentClassId'];
//        
//          $currentboardid = $currentboardid?$currentboardid:$boardId;//$_SESSION['currentBoardId'];
//          $currentclassid = $currentclassid?$currentclassid:$classId;
//        
//          $nurseryCount = $_SESSION['boards'][$currentboardid]['nurseryCount'];
//          $classdetails = $_SESSION['classes'][$currentboardid];
//          $nurseryarray = $this->getBoardClassList();
//            $subjectdetails = array();
//            $sbjectstable = $this->getServiceLocator()->get('Container\Model\TmainFactory');
//            // This function get the all subjects based on loged user class id.
//            if (class_exists('Memcached') && $cache['cacheEnabled'] == '1') {
//                if (($subjectdetails = $memcached->getItem('subjectdetails-' . $currentclassid)) == FALSE) {
//                    $subjectdetailss = $sbjectstable->getSubjectsByClassId($currentclassid)->toArray();
//                    $subjectdetails = $this->arrayToObject($subjectdetailss);
//                    $memcached->setItem('subjectdetails-' . $currentclassid, $subjectdetails);
//                }
//            } else {
//                 
//                 $packagetable = $this->getServiceLocator()->get('Package\Model\TuserpackageTable');
//              $userpackagessubjects = $packagetable->getPackagesubjects($_SESSION['user']['userId']);
//            // echo '<pre>';print_r($userpackagessubjects);die;           
//           
//         if($userpackagessubjects->count())
//        {
//                foreach($userpackagessubjects as $userpackagessubject)
//                {
//                         $explode = explode(',', $userpackagessubject->syllabus_id);
//                         foreach($explode as $val){
//                            $subjectIds .= "" . $val . "" . ",";
//                           //$usersubjects[]=$val;
//                        }
//                         
//                }
//                  
//        }
//           $subjectIds = ltrim($subjectIds, ',');
//         
//             if($subjectIds!=''){
//             $subjectdetailss = $sbjectstable->getSubjectsByClassIdsubjectId($currentclassid,rtrim($subjectIds, ','))->toArray();
//              $subjectdetails = $this->arrayToObject($subjectdetailss);  
//          
//             }
//          // $subjectdetailss = $sbjectstable->getSubjectsByClassId($currentclassid)->toArray();
            // $subjectdetails = $this->arrayToObject($subjectdetailss);
//            }
            // Non EM Users
            //  echo '<pre>'; print_r($addedMentors); die;
            $tempmentors = array();
//             $temptable = $this->getServiceLocator()->get('ZfcUser\Model\TtempgroupsTable');
//             $mentorrequestlists =  $temptable->pendingrequest('','',$userid);
            /*             * **************** made it emtpty for basic testing  
             * ******** */
            $mentorrequestlists = array();
            /*             * ****************
             * ******** */

            if (count($mentorrequestlists) > '0') {

                foreach ($mentorrequestlists as $temprequest) {
                    $type = explode("-", $temprequest->request_type);
                    if ($type['0'] == 'mentor') {

                        $subjectId = $type['1'];
                        $sbjectstable = $this->getServiceLocator()->get('Container\Model\TmainFactory');
                        $subjectLists = $sbjectstable->getSubjectById($subjectId);

                        $subjectList = $subjectLists->buffer();
                        foreach ($subjectList as $subject) {

                            $boardname = $subject->board_name;
                            $classname = $subject->class_name;
                            $subjectname = $subject->subject_name;
                        }

                        $tempmentors[] = array(
                            'id' => $temprequest->id,
                            'email_id' => $temprequest->email_id,
                            'board_name' => $boardname,
                            'class_name' => $classname,
                            'subject_name' => $subjectname
                        );
                    }
                }
            }
            $nurseryarray = array();
            return new ViewModel(array(
                'mentors' => $mentors,
//                'mentorNames' => $mentor_names,
//                'nurseryCount' => $nurseryCount,
                'boards' => $this->getServiceLocator()->get('lms_container_mapper')->getCustomBoard(),
//                'classes' => $classdetails,
//                'currentboardid' => $currentboardid,
//                'currentclassid' => $currentclassid,
//                'junior_board_name' => $junior_board_name,
//                 'nurseryarray' => $nurseryarray,
                'subjectArray' => $subjectArray,
                'addedMentors' => $addedMentors,
                'userObj' => $userObj,
//                'mentorssIds' => $mentorssIds,
                'addedStudents' => $addedStudents,
//                'tempmentors' => $tempmentors,
            ));
        } else {
            return $this->redirect()->toRoute('home');
        }
    }

    public function myparentAction() {
        $userObj = $this->zfcUserAuthentication()->getIdentity();
        $userId = $userObj->getId();
        $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
        $ParentData = $table->getParentData($userId);

        $parentall = array();
        foreach ($ParentData as $parents) {
            $parentall[] = $parents;
        }
        $tabview = $this->params()->fromRoute('id', 0);

        //request sent to parent 
        $table = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
        $parentrequest = $table->getpendingrequest($userId, 'child', 'sent');

        //request receive from parent
        $table = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
        $childrequest = $table->getpendingrequest($userId, 'child', 'receive');

        $tablestudent = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');

        // This function get the all mentors of student.
        $addedMentor = $tablestudent->getAll($userId, 'student');
        $addedMentors = $addedMentor->buffer();

        $addedStudent = $tablestudent->getAll($userId, 'mentor');
        $addedStudents = $addedStudent->buffer();



        $view = new ViewModel(array(
            'ParentData' => $parentall,
            'parentrequest' => $parentrequest,
            'childrequest' => $childrequest,
            'addedMentors' => $addedMentors,
            'addedStudents' => $addedStudents,
            'tabview' => $tabview,
        ));
        return $view;
    }

    public function getLmsService() {
        if (!$this->lmsService) {
            $this->lmsService = $this->getServiceLocator()->get('lms_container_service');
        }
        return $this->lmsService;
    }
    
    public function getLmsMapper(){
        if (!$this->lmsMapper) {
             $this->lmsMapper = $this->getServiceLocator()->get('lms_container_mapper');
         }
        return $this->lmsMapper;
    }

    public function myStudentsAction() {
        $subjectArray = array();
        $addedStudents = array();
        $userdetails = array();
        $mentors = array();
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');

        if ($auth->hasIdentity()) {
            $userObj = $auth->getIdentity();
            $userId = $userObj->getId();
            $table = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
                                                    // This function get the all students of loged mentor.
            $addedStudent = $table->getAll($userId, 'mentor');
            $addedStudents = $addedStudent->buffer();
            foreach ($addedStudents as $addedStudentss) {
                $subjectArray[$addedStudentss->id]['subjectId'] = $addedStudentss->subject_id;
            }
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == '1') {
                $classId = $userObj->getClassId();
                return $this->redirect()->toRoute('website/default', array('controller' => 'index', 'action' => 'dashboard', 'param1' => $classId));
                exit;
            }
            $addedMentor = $table->getAll($userObj->getId(), 'student');
            $addedMentors = $addedMentor->buffer();
            $boardList = $this->getServiceLocator()->get('lms_container_mapper')->getCustomBoard();
            
            $newarr = array();
            $totalAttempts = array();
            $returnArray = array();
            $percentageCountArr = array();
            $subjectFullArr = array();
                foreach($addedStudents as $key => $added_student) {
                    $returnArray[$key] = $added_student;
                    $studentId = $added_student->student_id;
                    $classId = $added_student->class_ids;
                    $class = strtolower($added_student->class_name);
                    $subjectName = $added_student->subject_name;
                    $subjectId = isset($added_student->sub_id)?$added_student->sub_id:$added_student->subject_id;
                    
                    if(!isset($subjectId)){
                        
                        $lmsMapperObj = $this->getServiceLocator()->get('lms_container_mapper');
                        $boardDataObj = $lmsMapperObj->getContainer($added_student->board_id);
                        if(is_object($boardDataObj)){
                            if (is_object($boardDataObj->getRackName())){
                                $returnArray[$key]->board_name = $boardDataObj->getRackName()->getName();
                            }
                        }
                        $classDataObj = $lmsMapperObj->getContainer($added_student->class_id);
                        if(is_object($classDataObj)){
                            if (is_object($classDataObj->getRackName())){
                                $returnArray[$key]->class_name = $classDataObj->getRackName()->getName();
                            }
                        }
                    }
                  
                    if(isset($subjectId)){
                        
                        $class = $this->getLmsService()->getParent($subjectId)->getRackName()->getName();
                        $classId = $this->getLmsService()->getParent($subjectId)->getRackId();
                       
                        $board = $this->getLmsService()->getParent($classId)->getRackName()->getName();
                        $subjects = $this->getLmsService()->getChildList($classId);
                       
                        $subjectArr = array();
                        foreach ($subjects as $subjK => $subjV) {
                            $subjectArr[$subjK]['name'] = $subjV->getRackName()->getName();
                            $subjectArr[$subjK]['id'] = $subjV->getRackId();
                        }
                        $chapters = $this->getLmsService()->getChaptersFromSubject($subjectId);
                        $subchapters = $this->getLmsService()->getLmsMapper()->getHirarchy($subjectId);
                        $chaptersArr = array();
                        $_getSubjectArr = array();
                        foreach ($subchapters as $chK => $chV) {
                            $chaptersArr[] = $chV->getRackId();
                            $_getSubjectArr[$chV->getRackId()]['subjectId'] = $subjectId;
                            $_getSubjectArr[$chV->getRackId()]['subjectName'] = $subjectName;
                            $_getSubjectArr[$chV->getRackId()]['chapterName'] = $chV->getRackName()->getName();
                        }
                        $chapters = array_map(function($x) {
                            return $x['rackId'];
                        }, $chapters);
                        
                         $testPerformanceArr = array();
                         if(count($chaptersArr) > count($chapters)) {
                             $chapters = array_merge($chapters,$chaptersArr);
                             //$_chapters = $chaptersArr;
                             $chapters = array_unique($chapters);
                             //$chapters = $chaptersArr;
                         }
                         $chapterdetail = $this->getLmsService()->getLmsMapper()->getHirarchy($subjectId);
                        foreach ($chapterdetail as $chapter) {
                            $chapters[] = $chapter->getRackId();
                        }
                        if($chapters){
                            $testPerformance = $this->getLmsService()->getTestPerformance($studentId, $chapters, 'single', true, true,'asc');
                            foreach ($testPerformance as $testK => $testV) {
                                @$chapterId = $testV[0]['chapterSetId'][0]['chapterId'];
                                @$levelId = $testV[0]['levelId'];
                                $testPerformanceArr[$studentId."-".$subjectId."-".$chapterId . "-" . $levelId][] = $testV;
                            }
                        }
                        $totalpercentage = 0;
                        $percentageCount = 0;
                        foreach ($testPerformanceArr as $key => $reportrecord) {
                                    $chapterArr = explode('-',$key);
                                    $chapterId = $chapterArr[0];
                                    $rightCount = array_reduce($reportrecord, function($carry, $item){
                                        return $carry+=$item['rightAnswerCount'];
                                    },0);
                                    
                                    $totalQuesCount = array_reduce($reportrecord, function($carry, $item){
                                        return $carry+=$item['totalQues'];
                                    },0);
                                    $keyArr = explode('-',$key);
                                    if($totalQuesCount!=0) {
                                        $percentage = ($rightCount / $totalQuesCount) * 100;
                                    } else {
                                        $percentage = 0;
                                    }
                                    $totalpercentage = $totalpercentage + $percentage;
                                    $percentageCount++;     
                        }
                        if($percentageCount!=0){
                            $totalScore = $totalpercentage/$percentageCount;
                            $actualPercentage = round($totalScore, 2);
                        }
                        
                        if(count($testPerformanceArr)) {
                            if($key) {
                                $percentageCountArr[$keyArr[0].'-'.$keyArr[1]]['percentage'] = $actualPercentage;
                                $percentageCountArr[$keyArr[0].'-'.$keyArr[1]]['testTaken'] = $percentageCount;
                            }
                        }
                        $_chapters = $this->getLmsService()->getChaptersFromSubject($subjectId);
                        $_chapters = array_map(function($x) {
                            return $x['rackId'];
                        }, $_chapters);
                        $testPerformanceArrs = array();
                        
                        $testPerformanceArr = array();
                        if(count($chaptersArr) > count($_chapters)) {
                            $_chapters = array_merge($_chapters,$chaptersArr);
                            $_chapters = array_unique($_chapters);
                        }
                        if($_chapters){
                           
                        $testPerformances = $this->getLmsService()->getTestPerformance($studentId, $_chapters, 'single', true, $groupbyset = true);
                         

                        foreach ($testPerformances as $testK => $testV) {
                            @$chapterId = $testV[0]['chapterSetId'][0]['chapterId'];
                            @$levelId = $testV[0]['levelId'];
                            $testPerformanceArrs[$studentId ." ". $chapterId . "-" . $levelId . "-" . $subjectId][] = $testV;
                        }  
                        
                        $total = 0;
                        foreach ($testPerformanceArrs as $key => $reportrecord) {
                            $total = $total + count($reportrecord);
                        }

                        $totalAttempts[$studentId.'-'.$added_student->subject_id] = $total;
                       
                    }
                  }
                }
                         
            return new ViewModel(array(
                'addedStudents' => $returnArray,
                'userObj' => $userObj,
                'addedMentors' => $addedMentors,
                'mentors' => $mentors,
                'subjectArray' => $subjectArray,
                'boards' => $boardList,
                'percentageArr' => $percentageCountArr,
                //'percentageArr' => $newarr,
                'total_attempts' => $totalAttempts
            ));
        }
    }
    
    public function mypaperAction() {
        //if(isset($_SESSION['user']['userId']) && $_SESSION['user']['userId']!=""){
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $tablestudent = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');

            // This function get the all mentors of student.
            $addedMentor = $tablestudent->getAll($this->zfcUserAuthentication()->getIdentity()->getId(), 'student');
            $addedMentors = $addedMentor->buffer();

            $addedStudent = $tablestudent->getAll($this->zfcUserAuthentication()->getIdentity()->getId(), 'mentor');
            $addedStudents = $addedStudent->buffer();


            $mentorId = $this->zfcUserAuthentication()->getIdentity()->getId();
            //echo $mentorId; exit;
            $mentorPaperList = $this->getMentorAssignPaperTable()->getMentorAssignedPaperList($mentorId);
            /*$PaperAssignToStudent = array();
            foreach($mentorPaperList as $paperlist) {
                $paperAssignedId = $paperlist->paperAssignId;
                $rowSetCount = $this->getMentorAssignPaperTable()->getPaperAssignedToTotalStudent($paperAssignedId);
                $PaperAssignToStudent[$paperAssignedId] = $rowSetCount;
            }*/
            //echo '<pre>'; print_r($PaperAssignToStudent); exit;
            return new ViewModel(array(
                'mentorPaperList' => $mentorPaperList,
                'addedMentors' => $addedMentors,
                'addedStudents' => $addedStudents,
                //'assignedToTotalStu' => $PaperAssignToStudent
            ));
        } else {
            return $this->redirect()->toRoute('home');
        }
    }
    
    public function paperstatusAction() {
        
//       if(isset($_SESSION['user']['userId']) && $_SESSION['user']['userId']!=""){
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $paper_id = $this->params()->fromRoute('id', 0);
            $tablestudent = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');

            // This function get the all mentors of student.
            $addedMentor = $tablestudent->getAll($this->zfcUserAuthentication()->getIdentity()->getId(), 'student');
            $addedMentors = $addedMentor->buffer();

            $addedStudent = $tablestudent->getAll($this->zfcUserAuthentication()->getIdentity()->getId(), 'mentor');
            $addedStudents = $addedStudent->buffer();


            $mentorId = $this->zfcUserAuthentication()->getIdentity()->getId();
            //echo $mentorId; exit;
            $mentorPaperList = $this->getMentorAssignPaperTable()->getMentorAssignedPaperList($mentorId,'','',$paper_id);
            $PaperAssignToStudent = array();
            foreach($mentorPaperList as $paperlist) {
                $paperAssignedId = $paperlist->paperAssignId;
                echo $paperAssignedId; 
                //$rowSetCount = $this->getMentorAssignPaperTable()->getPaperAssignedToTotalStudent($paperAssignedId);
                //$PaperAssignToStudent[$paperAssignedId] = $rowSetCount;
            }
            exit;
            //echo '<pre>'; print_r($PaperAssignToStudent); exit;
            return new ViewModel(array(
                'mentorPaperList' => $mentorPaperList,
                'addedMentors' => $addedMentors,
                'addedStudents' => $addedStudents,
                'assignedToTotalStu' => $PaperAssignToStudent
            ));
        } else {
            return $this->redirect()->toRoute('home');
        }
    }

    public function mychildAction() {
        $userObj = $this->zfcUserAuthentication()->getIdentity();
        $userId = $userObj->getId();
        //get child data
        $ordersDetailes[] = '';
        $table         = $this->getServiceLocator()->get('Assessment\Model\UserTable');
        $childData     = $table->getChildData($userId);
        $comMapperObj  = $this->getServiceLocator()->get("com_mapper");
        $countryData   = $comMapperObj->getAllCountries();
        $userCountryId = $userObj->getCountryId();
        $userStateId   = $userObj->getStateId();
        $userCity      = $userObj->getOtherCity();
        $statelist     = $comMapperObj->getCountarybystate($userCountryId);

        $tabview = $this->params()->fromRoute('id', 0);
        $this->websiteMapper = $this->getServiceLocator()->get('website_mapper');
        $childArray = array();
        foreach ($childData as $key => $aChild) {
            //get user child relation created through request
            //echo '<pre>'; print_r(); exit;
            if($aChild->customBoardRackId!=''){
                
                $boarddetails =  $this->websiteMapper->getcustomboarddetail($aChild->customBoardRackId);
                @$childArray[$key]->board_name = $boarddetails['boardName'];
                //$childArray[$key]->board_id = $boarddetails['board_id'];
            }else{
                @$childArray[$key]->board_name = $aChild->boardName;
                //$childArray[$key]->board_id = $aChild->boardId;
            }
            //$aChild->customBoardName;
            $table = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
            $relationcount = '';
            $relationcount  = $table->getrelation($userId, $aChild->user_id, '1');
            $relationdetail = $table->getrelationdetail($userId, $aChild->user_id, '1')->current();
            
            @$childArray[$key]->relationid= $relationdetail->id; 
            @$childArray[$key]->relationcount = $relationcount;
            $childArray[$key]->child_id   = $aChild->user_id;
            $childArray[$key]->first_name = $aChild->firstName;
            $childArray[$key]->user_name  = $aChild->username;
            $childArray[$key]->email_id   = $aChild->emailId;
            $childArray[$key]->class_name = $aChild->className;
            $childArray[$key]->class_id   = $aChild->classId;
        }
        //get child package info
        $table = $this->getServiceLocator()->get('Assessment\Model\TuserpackageTable');
        $childordersDetailes = array();
        foreach ($childData as $child) {
            $ChildId = $child->user_id;
            $childordersDetailes[$ChildId] = $table->getPackages($ChildId, 'all', 'all');
        }
        //request  to child
        $table             = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
        $parentrequestsent = $table->getpendingrequest($userId, 'parent', 'sent'); 
        //request receive from child
        $table               = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
        $childrequestreceive = $table->getpendingrequest($userId, 'parent', 'receive');
        $tablestudent        = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
        //This function get the all mentors of student.
        $addedMentor   = $tablestudent->getAll($userId, 'student');
        $addedMentors  = $addedMentor->buffer();
        $addedStudent  = $tablestudent->getAll($userId, 'mentor');
        $addedStudents = $addedStudent->buffer();

        $boardList    = $this->getService()->getCustomBoardList();
        $valueOptions = array();
        //$valueOptions[''] = 'Select Board';
        $valueOptions=$boardList;
        $view = new ViewModel(array(
            'childData' => $childArray,
            'ParentData' => @$parentall,
            'parentrequestsent' => @$parentrequestsent,
            'childrequestreceive' => @$childrequestreceive,
            'addedMentors' => $addedMentors,
            'boardList' => $valueOptions,
            'addedStudents' => $addedStudents,
            'tabview' => $tabview,
            'countries' => $countryData,
            'states' => $statelist,
            'userCountryId' => $userCountryId,
            'userStateId' => $userStateId,
            'userCity' => $userCity
        ));
        return $view;
    }

    public function changeprofileAction() { 
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $tableuserother = $this->getServiceLocator()->get('Assessment\Model\UserOtherDetailsTable');
            $userid = $this->zfcUserAuthentication()->getIdentity()->getId();
            $usertypeid = $this->zfcUserAuthentication()->getIdentity()->getUserTypeId();
            if ($usertypeid == 10) { // admin redirect
                return $this->redirect()->toRoute('admin/misreport');
            }
            $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
            $commonObj = $this->getServiceLocator()->get("com_mapper");
            $userRegOtherDetails = $tableuserother->getUserOtherDetailsByKey($userid , 'register_by', $status=Null);
            foreach($userRegOtherDetails as $otherdetail) {
                $register_by = $otherdetail->value;
            }
            if ($this->getRequest()->isPost()) {
                $filename = '';
                $imageName = '';
                $fileerror = '';
                
                if (isset($_FILES['image_file']) && $_FILES['image_file']['name'] != "") {
                    $Filedata = $commonObj->validateImage($_FILES);
                    $event = $this->getEvent();
                    $request = $event->getRequest();
                    
                    if (!is_array($Filedata)) {
                        if ($Filedata == 'corrupt') {
                            return $this->redirect()->toUrl('changeprofile?error=tfalse');
                        } else {
                            return $this->redirect()->toUrl('changeprofile?error=false');
                        }
                    }
                }
                if ($Filedata['image_file']['name'] != "" && $_POST['hidfile'] == "") {
                    $filename = str_replace(' ', '_', $Filedata['image_file']['name']);
                    $file_type = substr(strrchr($Filedata['image_file']['name'], '.'), 1);
                    $imageName = $_POST['hiduserid'] . '.' . $file_type;
                    
                    $fileUploaded = $this->ftpFileUploaded($Filedata['image_file']['tmp_name'], '/uploads/profileimages/' . $imageName);
//                    move_uploaded_file($Filedata['image_file']['tmp_name'], '/uploads/profileimages/' . $imageName);
                } else if ($_POST['hidfile'] != "" && $Filedata['image_file']['name'] == "") {
                    $imageName = $_POST['hidfile'];
                } else if ($Filedata['image_file']['name'] != "" && $_POST['hidfile'] != "") {
                    //unlink('public/uploads/profileimages/' . $_POST['hidfile']);
                    $filename = str_replace(' ', '_', $Filedata['image_file']['name']);
                    $file_type = substr(strrchr($Filedata['image_file']['name'], '.'), 1);
                    $imageName = $_POST['hiduserid'] . '.' . $file_type;
                    
                    $fileUploaded = $this->ftpFileUploaded($Filedata['image_file']['tmp_name'], '/uploads/profileimages/' . $imageName);
//                    move_uploaded_file($Filedata['image_file']['tmp_name'], '/uploads/profileimages/' . $imageName);
                }
                $post = $this->getRequest()->getPost();
//               $postdata   = $commonObj->escaper($post);
//               echo "<pre />"; print_r($postdata);exit;
                //echo "<pre />"; print_r($post);exit;
                $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                $userdetail = $table->getuserdetailsForSubscription($post['hiduserid'], $usertypeid);

                foreach ($userdetail as $user) {
                    $username = $user->username;
                }
                //echo '<pre>'; print_r($post); exit;
                if(isset($post['usertype']) && $post['usertype']==1) {
                $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                $getcustomboard = $comMapperObj->getcustomboardPrimaryId($post['board'],$post['classnames']); 
                $post['customboardId'] = $getcustomboard;
                $boardIdArray = explode("_",$post['board']);
                $boardRackIds=$this->getService()->getTaggedBoardList($boardIdArray[0]);
                $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                $newBoardId = $comMapperObj->getContainerRackId($boardRackIds,$post['classnames']);
                $post['resource_board_id'] = $newBoardId;
                }
                //echo $userid.' === '.$imageName; echo '<pre>'; print_r($post);exit;
                $update_status = $table->updateprofile($post, $imageName, $userid);
                if ($post['allowschedule'] == '1') {
                    $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                    $ParentData = $table->getParentData($userid);
                    if ($ParentData->count() > 0) {
                        $classId = $this->zfcUserAuthentication()->getIdentity()->getClassId();
                        $data = array(
                            'notification_text' => $this->zfcUserAuthentication()->getIdentity()->getDisplayName() . '&nbsp; has allow to schedule plans ',
                            'userid' => $ParentData->current()->user_id,
                            'type_id' => '1',
                            'notification_url' => 'calendarschedule/' . $classId . '/' . $userid,
                            'created_by' => $userid,
                            'created_date' => date('Y-m-d H:i:s'),
                        );
                        $notificationtable->insertnotification($data);
                    }
                }

                if ($update_status >= 0) {
                    if ($fileerror == '') {
                        return $this->redirect()->toUrl('myprofile?succ=true');
                    } else {
                        return $this->redirect()->toUrl('changeprofile?userid=' . $userid . "&fileerror=1");
                    }
                }
            } else {
                
                $tablestudent = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');

                $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                $user = $table->getuserdetailsForSubscription($userid, $usertypeid);

                
                foreach ($user as $userChildName) {
                    $userdetails = $userChildName;
                    $userchild = $table->getuserdetailsChild($userid);
                    $userProfileDetails = $table->getprofilebyid($userid);
                    foreach ($userchild as $child) {
                        $userdetails->nameChild .=$child->nameChild . ',';
                    }
                }

                
                $addedMentor = $tablestudent->getAll($userid, 'student');
                $addedMentors = $addedMentor->buffer();

                $addedStudent = $tablestudent->getAll($userid, 'mentor');
                $addedStudents = $addedStudent->buffer();


                $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                $countryData = $comMapperObj->getAllCountries();
                
                if (!isset($userdetails->state_id) || !isset($userdetails->country_id)) {

                    if (!isset($_SESSION['user_session_ip_country_profile']) && !isset($_SESSION['user_session_ip_state_profile']) && !isset($_SESSION['user_session_ip_city_profile'])) {
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
                    if ($_SESSION['user_session_ip_country_profile']) {
                        $countaryidDetails = $comMapperObj->getCountryIdByCountryName($_SESSION['user_session_ip_country_profile']);
                        if (count($countaryidDetails) > 0) {
                            $countaryidDetailsNew = $countaryidDetails[0];
                            $countaryid = $countaryidDetailsNew->getCountryId();
                            $stateid = 0;
                            $cityValue = '';
                            if ($_SESSION['user_session_ip_state_profile'] != '0') {
                                $stateDetails = $comMapperObj->getStateIdByStateName($_SESSION['user_session_ip_state_profile']);
                                if (count($stateDetails) > 0)
                                    $stateid = $stateDetails[0]->getStateId();
                                else
                                    $stateid = 0;
                            }
                            if ($_SESSION['user_session_ip_city_profile'] != '0')
                                $cityValue = $_SESSION['user_session_ip_city_profile'];
                        }
                    }
                }else {
                    $countaryid = $userdetails->country_id;
                    $stateid = $userdetails->state_id;
                    $cityValue = $userdetails->other_city;
                }
                
                $statelist = $comMapperObj->getCountarybystate($countaryid);

                
                $boardList = $this->getService()->getCustomBoardList();
                $valueOptions = array();
                $valueOptions =  $boardList;
                 
                /*if ($userdetails->boardId == '0' || $userdetails->boardId == '1' || $userdetails->boardId == '2')
                    $userdetails->boardId = '34';
                //$classList = $this->getService()->getchildList(($userdetails->boardId != '') ? $userdetails->boardId : '34');
                */
                //echo $userdetails->boardId; echo '<pre>'; print_r($userdetails); exit;
                /*$boardIds = array();
                foreach($valueOptions as $keyBoard => $valBoard) {
                    $newArr = $valBoard[0];
                    $boardIds[$newArr['rackId']] = $newArr['customBoardId'];
                }
                if(isset($boardIds[$userdetails->boardId])) {
                    $userdetails->boardId = $boardIds[$userdetails->boardId];
                }*/
                foreach($userProfileDetails as $userProfile) {
                    //echo '<pre>'; print_r($userProfile); exit;
                    $userdetails->boardId = $userProfile->custom_board_id;
                }
                //echo $userdetails->boardId; exit;
                $boardRackIds=$this->getService()->getTaggedBoardList(($userdetails->boardId != '') ? $userdetails->boardId : '34');
                $array = array_column($boardRackIds, 'rackId');
                $rackContainerIds='';
                if(count($array)>0){
                    $rackContainerIds=implode(',',$array);
                }
                
                //echo $userdetails->boardId; print_r($boardRackIds); exit;
                $classList = array();
                if(!empty($rackContainerIds)) {
                    $customContainerList = $this->getService()->getCustomBoardChildList($rackContainerIds);
                    foreach($customContainerList as $customContainer){
                       $classList[] = $customContainer->getResourceRack();
                    }
                }
                
                $ClassOptions = array();
                $ClassOptions[''] = 'Select Class';
                foreach ($classList as $container) {
                    $ClassOptions[$container->getRackId()] = $container->getRackName()->getName();
                }
                
                $table = $this->getServiceLocator()->get('Assessment\Model\TusertypeTable');
                $usertypes = $table->getuserTypes();
              //  echo "<pre />"; print_r($valueOptions);die;
                
                $viewModel = new ViewModel(array('addedMentors' => $addedMentors,
                    'addedStudents' => $addedStudents,
                    'countries' => $countryData,
                    'states' => $statelist,
                    'countaryid' => $countaryid,
                    'stateid' => $stateid,
                    'cityValue' => $cityValue,
                    'user' => $userdetails,
                    'boards' => $valueOptions,
                    'usertypes' => $usertypes,
                    'classes' => $ClassOptions,
                    'register_by' => $register_by 
                ));
                return $viewModel;
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

    /**
     * Change the users password
     */
    public function changepasswordAction() {
        // if the user isn't logged in, we can't change password
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            // return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
            return $this->redirect()->toRoute('zfcuser/login');
        }
        $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
        $tablestudent = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
        
        // This function get the all mentors of student.
        $addedMentor = $tablestudent->getAll($userId, 'student');
        $addedMentors = $addedMentor->buffer();
        
        
        // This function get the all students of loged mentor.
        $addedStudent = $tablestudent->getAll($userId, 'mentor');
        $addedStudents = $addedStudent->buffer();
        //$form = new SearchForm();
        $form = $this->getChangePasswordForm();
        $prg = $this->prg(static::ROUTE_CHANGEPASSWD);
        
        if($this->getRequest()->isPost()) {
            $changeData = $this->getRequest()->getPost();
            //echo '<pre>'; print_r($changeData); exit;
            $identity = $changeData['identity'];
            
            
            
            
            $credential = $changeData['credential'];
            $newpassword= $changeData['newCredential'];
            $confirmpassword=$changeData['newCredentialVerify'];
            $validResponse = $this->valid_pass($newpassword,$confirmpassword,$credential);
            //echo '<pre>'; print_r($changeData); exit;
            if(($validResponse == 'match' || $validResponse == 'length' || $validResponse == 'space' || $validResponse == 'special' || $validResponse == 'required') && $validResponse!='1') {
                $responseArr = array(
                    'response' => $validResponse,
                    'status' => false,
                    'changePasswordForm' => $form,
                    'addedMentors' => $addedMentors,
                    'addedStudents' => $addedStudents,
                );
                echo json_encode($responseArr);
                exit;
            }
            
            $config=$this->getServiceLocator()->get('config');
            $defaultstates = isset($config['msg_engine'])?$config['msg_engine']:'';
            if(isset($defaultstates['status']) && $defaultstates['status'] == 'ON') {
               $tablestudent = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                $newEmailData = $tablestudent->getUserbyemail($identity);
                if(!empty($newEmailData)) {
                    $userId = $newEmailData->user_id;
                    $mobileNumber = $newEmailData->mobile;
                } else {
                    $newEmailData = $tablestudent->getUserByMobile($identity);
                    $userId = $newEmailData->user_id;
                    $mobileNumber = $newEmailData->mobile;
                }
                $tableuserother = $this->getServiceLocator()->get('Assessment\Model\UserOtherDetailsTable');
                $userRegOtherDetails = $tableuserother->getUserOtherDetailsByKey($userId, 'register_by', Null);
                foreach($userRegOtherDetails as $otherdetail) {
                    $register_by = $otherdetail->value;
                }
                if($register_by=='mobile') {
                        $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                        $msglog  = $this->getServiceLocator()->get('Package\Model\TmsgLogTable');
                        $date = date('Y-m-d');
                        $time = date('h:i:s');
                        $msgTxt="Dear user,<br>
Your Extramarks Smart Study Pack password has been reset on ($date) at ($time) as requested on ($date). For any query call 18001025301";
                        $usermobile = $mobileNumber;
                        $mobile     = explode("-", $usermobile);
                        $mob_number = $mobile[1];
                        if($mobile[1]) {
                            $smsArr = array('to_mobile_number'=>$mob_number,
                                'msg_txt' => $msgTxt,
                                'user_id' => $userId,
                                'mobile_number' => $mob_number,
                                'sms_type' => 'Password change'
                            );
                            $data = $comMapperObj->smssendprocess($smsArr);
                            $result = $msglog->addlog($data);
                        }
                }
            }
            
            $fm = $this->flashMessenger()->setNamespace('change-password')->getMessages();
            if (isset($fm[0])) {
                $status = $fm[0];
            } else {
                $status = null;
            }

            if ($prg instanceof Response) {
                return $prg;
            } elseif ($prg === false) {
                $responseArr = array(
                    'status' => $status,
                    'changePasswordForm' => $form,
                    'addedMentors' => $addedMentors,
                    'addedStudents' => $addedStudents,
                );
                echo json_encode($responseArr);
                exit;
            }
            
            $userArrData = array();
            foreach($changeData as $key => $data) {
                $userArrData[$key] = $data;
            }
            
            
                
            if (!$this->getServiceLocator()->get('zfcuser_user_service')->changePassword($userArrData)) {
                $responseArr = array(
                    'status' => false,
                    'response' => 'current',
                    'changePasswordForm' => $form,
                    'addedMentors' => $addedMentors,
                    'addedStudents' => $addedStudents,
                );
                echo json_encode($responseArr);
                exit;
            } else {
                $responseArr = array(
                    'status' => true,
                    'changePasswordForm' => $form,
                    'addedMentors' => $addedMentors,
                    'addedStudents' => $addedStudents,
                );
                echo json_encode($responseArr);
                exit;
            }
        }
       
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
        
        if (!$this->getServiceLocator()->get('zfcuser_user_service')->changePassword($form->getData())) {
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
    
    public function valid_pass($password,$confirmPassword,$current) {
        if(empty($current) || empty($password) || empty($confirmPassword)) return 'required';
        $passArr = explode(' ',$password);
        if(count($passArr) > 1) return 'space';
        if(strlen($password)<6) return 'length';
        if($password != $confirmPassword) return 'match';
        if(preg_match('/[^A-Za-z0-9\!,$@;#&()+:._]/', $password)) return 'special';
        return true;
    }

    public function getChangePasswordForm() {
        if (!isset($this->changePasswordForm) && !$this->changePasswordForm) {
            $this->setChangePasswordForm($this->getServiceLocator()->get('zfcuser_change_password_form'));
        }
        return $this->changePasswordForm;
    }

    public function setChangePasswordForm(Form $changePasswordForm) {
        $this->changePasswordForm = $changePasswordForm;
        return $this;
    }

    // user view the profile of send request user.
    public function userprofileAction() {
        $request = $this->getRequest();
        $userid = $request->getQuery('id');
        if ($userid != '') {
            $user_type = $request->getPost('user_type');
            $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
            if($user_type=='students') {
                $board_name = $request->getPost('board_name');
                $class_name = $request->getPost('class_name');
                 $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
                $userObj = $auth->getIdentity(); 
                $mentorId = $userObj->getId();
                //echo $userid.' '.$mentorId; exit;
                $userdetails = $table->getstudentprofilebyid($userid,$mentorId);
                $userdetails = $userdetails->current();
                $userdetails->customBoardName=$board_name;
                $userdetails->customClassName=$class_name;
                //echo '<pre>'; print_r($userdetails); exit;
                
                //if(empty($userdetails)) {
                    //echo 'asdfasd';
                    //$userdetails = $table->getprofilebyid($userid);
                    //$userdetails = $userdetails->current();
                //}
                //echo count($userdetails); echo '<pre>'; print_r($userdetails); exit;
            } else {
                $userdetails = $table->getprofilebyid($userid);
                $userdetails = $userdetails->current();
            }
            
            //echo '<pre>'; print_r($userdetails); exit;
            $board_id = $userdetails->boardId;
            $class_id = $userdetails->classId;
            $boardName = $userdetails->boardName;
            $customBoardName = $userdetails->customBoardName;
            //echo $boardName; exit;
            $school_id = $userdetails->school_id;
            //echo $school_id.'==='.$board_id.'==='.$class_id; exit;
            /*if(!isset($school_id)){
                //echo $school_id.'==='.$board_id.'==='.$class_id; exit;
                $lmsMapperObj = $this->getServiceLocator()->get('lms_container_mapper');
                $boardDataObj = $lmsMapperObj->getContainer($board_id);
                if(is_object($boardDataObj)){
                    if (is_object($boardDataObj->getRackName())){
                        $userdetails->board_name = $customBoardName;
                        //$userdetails->board_name = $boardDataObj->getRackName()->getName();
                    }
                }
                $classDataObj = $lmsMapperObj->getContainer($class_id);
                if(is_object($classDataObj)){
                    if (is_object($classDataObj->getRackName())){
                        $userdetails->class_name = $boardName;
                        //$userdetails->class_name = $classDataObj->getRackName()->getName();
                    }
                }
            }*/
        }
        //echo "<pre>"; print_r($userdetails); exit;
        
        $result = new ViewModel();
        $result->setVariables(array('getuserdetails' => $userdetails,));
        $result->setTerminal(true);
        return $result;
    }

    public function changepasswordchildAction() {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            // redirect to the login redirect route
            return $this->redirect()->toRoute($this->getOptions()->getLoginRedirectRoute());
        }
        if ($this->getEvent()->getRouteMatch()->getParam('userId')) {
            $childId = $this->getEvent()->getRouteMatch()->getParam('userId');
            $user_id = $childId;
        }
        if (isset($_POST['userId']) || isset($_GET['userId'])) {
            if (isset($_POST['userId'])) {
                $userid = $_POST['userId'];
            } else {
                $userid = $_GET['userId'];
            }
            if (isset($_POST['cnfpwrd'])) {

                $this->getServiceLocator()->get('zfcuser_user_service')->changePasswordChild($_POST['cnfpwrd'], $userid);
            }
            $user_id = $userid;
        }
        $tablestudent = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');

        $addedMentor = $tablestudent->getAll($user_id, 'student');
        $addedMentors = $addedMentor->buffer();


        // This function get the all students of loged mentor.
        $addedStudent = $tablestudent->getAll($user_id, 'mentor');
        $addedStudents = $addedStudent->buffer();
        return array(
            'status' => false,
            'id' => $childId,
            'addedMentors' => $addedMentors,
            'addedStudents' => $addedStudents,
        );

//         $result = new ViewModel(array(
//            'username' => $username,
//        ));
//        return $result;
    }

    public function userdashboardAction() {
        $config=$this->getServiceLocator()->get('config');
        $role = $this->params()->fromRoute('id');
        $welcome = $this->params()->fromRoute('welcome');
        
          if(!isset($_SESSION['currencyType']) && empty($_SESSION['currencyType']))
          {
            
                if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
                $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }else{
                $ip_address = $_SERVER['REMOTE_ADDR'];
                }
                $tableIpCountry = $this->getServiceLocator()->get('Assessment\Model\IpCountryTable');
                $ipResultSet = $tableIpCountry->ipRange($ip_address);
                // entry in ip_country_code table
                 $ipCountryObj = $this->getServiceLocator()->get("com_mapper");
                 $ipCountryObj->ipcheckfunction($ipResultSet,$ip_address);
          }
      
        if(strlen($role) > '1'){
            $userLogObj = $this->getService();     
            $sesionData = $userLogObj->getUserLogByLogId($role);
            
            foreach ($sesionData as $userSession){
                $erpUserId = $userSession->getUserId();
                $erpUserLoginStatus = $userSession->getLoginStatus();
                $erpUserIp = $userSession->getIp();
            }
            if($erpUserLoginStatus == 'login'){
                $tableuser = $this->getServiceLocator()->get('Assessment\Model\UserTable');        
    //            $erpUserSessionArr = new Container('erpUserSession');
                $erpUserSessionArr = array();
                //$erpUserSessionArr->getManager()->getStorage()->clear('erpUserSession');
                $userDetails = $tableuser->getprofilebyid($erpUserId);
                foreach($userDetails as $data){
        //            $localAddr = $_SERVER['REMOTE_ADDR'];
        //            if($erpUserIp == $localAddr){
                        $erpUserSessionArr['Email'] = $data->emailId;
                        $erpUserSessionArr['user_from_zf2_erp_md5_pwd'] = $data->password;

        //            }
                }       

                if (isset($erpUserSessionArr['Email'])) {
                    $email = $erpUserSessionArr['Email'];
                    if ($email) {
                        $request = new Request();
                        $request->setMethod(Request::METHOD_POST);
                        $request->getPost()->set('identity',$email);
                        if ( isset($erpUserSessionArr['user_from_zf2_erp_md5_pwd']) ) {
                            $request->getPost()->set('md5_credential',$erpUserSessionArr['user_from_zf2_erp_md5_pwd']);
                        }else{
                            //$request->getPost()->set('credential',$erpUserSessionArr->offsetGet('user_from_zf2_erp_pwd'));
                        }
                    }
                    $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
                    $adapter->prepareForAuthentication($request);
                    $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);

                    //return $this->redirect()->toUrl('http://10.1.10.98/school_lms/public/user/user-dashboard');
                    //echo '<pre>';var_dump ($auth->isValid());echo '</pre>';die('vikash');
                }
                $role = 2;
            }
        }
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }
//        echo $usertype = $this->params()->fromQuery('id');       
        
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        $userObj = $auth->getIdentity();
        $userId = $userObj->getId();
        $usertype = $userObj->getUserTypeId();
        $validEmail = $userObj->getValidEmail();
        if ($usertype == 10) { // admin redirect
            return $this->redirect()->toRoute('admin/misreport');
        }
        $tablestumentor = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
        $userTable = $this->getServiceLocator()->get('Assessment\Model\UserTable');
        $this->getemstudent = $this->getServiceLocator()->get('Assessment\Model\TemsstudentsTable');
            // This function get the all students of loged mentor.
            $addedStudent = $tablestumentor->getAll($userId, 'mentor');
            $addedStudents = $addedStudent->buffer();
            $totalStudent = 0;
            foreach ($addedStudents as $addedStudentss) {
                $totalStudent++;
                break;
            }
            $_SESSION['user_role'] = $role;
            //if($totalStudent > 0 && (($role == 3 && $usertype==3) || (empty($role) && $usertype==3)) ) {
        if($totalStudent > 0 && (($role == 3) || (empty($role) && $usertype==3)) ) {
                return $this->redirect()->toRoute('my-students');
                exit;
        }
       
        $classId = $userObj->getClassId();
        $boardId = $userObj->getBoardId();
        //echo $boardId; exit;
        $userLogObj = $this->getServiceLocator()->get("lms_container_mapper");
        $countuserlog = $userLogObj->getAllRecordCountUserLogs($userId);
        if($welcome=='' && $countuserlog<=1){
            $welcome = 'welcome';
        }
        if ($role != '') {
            $changeUserType = $role;
            $_SESSION['user_role'] = $role;
//           $usertype  = $role;
        } else {
            $_SESSION['user_role'] = $usertype;
            $changeUserType = $usertype;
        }

        $isEmail = strpos($userObj->getEmail(), '@');
        if ($validEmail == '5' && ( $userObj->getEmail() != NULL && $isEmail !== false)) {
            return $this->redirect()->toRoute('zfcuser/validemail');
        }
        
        $filepath= __DIR__ . '../../../../view/mailer/';
        $freeStudentfilepath = $filepath.'freeStudent.html';
        $freeStudent = file_get_contents($freeStudentfilepath);
       
        
        $freeParentfilepath = $filepath.'freeParent.html';
        $freeParent = file_get_contents($freeParentfilepath);
        
        $freeTeacherfilepath = $filepath.'freeTeacher.html';
        $freeTeacher = file_get_contents($freeTeacherfilepath);
        
        $satPackage = array();
        $capPackage = 0;
        $containerObjects = $this->getServiceLocator()->get('Psychometric\Model\StudyUserPackageFactory');
        if($userId){
          $UserPackage = $containerObjects->getUserPackage($userId);
          $capPackages = $containerObjects->getUserPackage($userId,'','','','','','1');
          
          foreach($UserPackage as $key=>$package){
              if($package->package_id == 8 || $package->package_id == 9 || $package->package_id == 15){
                  $satPackage[$key] = $package->package_id;
              }
          }
          if(sizeof($capPackages)){
            $capPackage = sizeof($capPackages);
          }
        }
        $userschool_id = $userObj->getSchoolId();  
        $schooldetail  = $userTable->checkuserchooldetail($userId,$userschool_id)->current();
        $checkEmuser   = $this->getemstudent->checkAlreadyExist($userId);
        
        $_SESSION['register_school_id'] = @$schooldetail->school_id;
//        $duration = ($schooldetail->school_id !="" && !empty($checkEmuser))?"1 month" :"";  /* '' ==>15days */ 
//        $type     = ($duration == '')?" ":"for"; 
//        $freeStudent = str_replace('{TYPE}', $type, $freeStudent);
//        $freeStudent = str_replace('{DURATION}', $duration, $freeStudent);
        
        
        //$classId = $this->params()->fromRoute('param1');
        $isClass = false;
        if($classId){
            $userLogObj = $this->getServiceLocator()->get("lms_container_mapper");
            $isClass = false;
            $classDataObj = $userLogObj->getContainer($classId);
            if(is_object($classDataObj)){
                if (is_object($classDataObj->getRackType())){
                    $isClass = ($classDataObj->getRackType()->getTypeName() == 'class' || $classDataObj->getRackType()->getTypeName() == 'package')?true:false;
                }
            }
        }
        //echo $classId.' baljeet '.$isClass; exit;
        
        $valueOptions = array();
        if(!$isClass){
            $boardList = $this->getServiceLocator()->get('lms_container_mapper')->getCustomBoard();
            foreach($boardList as $key => $board) {
                $boardName = $board[0]['customBoard']['boardName'];
                $customBoardId = $board[0]['customBoard']['customBoardId'];
                $valueOptions[$customBoardId] = $boardName;
            }
        }
        //echo '<pre>'; print_r($valueOptions); exit;
        if($changeUserType == 1) {
            if ($welcome != '') {
                $testGiven=0;
                $winATab=false;
                if(isset($config['registration_drive']['win_a_tab']) && $config['registration_drive']['win_a_tab']=='ON'){
                    $testGiven=$this->getServiceLocator()->get('Mcq\Model\RegistrationQuizTable')->checkRegistrationQuizGiven($userId);
                    $winATab=true;
                }
                
                $result = new ViewModel(array(
                    'usertypeid' => $changeUserType,
                    'countuserlog' => $countuserlog,
                    'welcome' => $welcome,
                    'freestudent' => $freeStudent,
                    'freeparent' => $freeParent,
                    'freeteacher' => $freeTeacher,
                    'satPackage' => $satPackage,
                    'capPackage' => $capPackage,
                    'classid' => $classId,
                    'boardid'=> $boardId,
                    'testGiven'=>$testGiven,
                    'winATab'=>$winATab,
                    'config'=>$config,
                    'schoolId'=>$_SESSION['register_school_id'],
                    'isClass' => $isClass,
                    'boardList' => $valueOptions,
                ));
                return $result;
            } else {
                return $this->redirect()->toRoute('website/default', array('controller' => 'index', 'action' => 'dashboard', 'param1' => $classId));
            }
        } else if ($changeUserType == 2) {
            // calling layout for select subject new design
            $usertable = $this->getServiceLocator()->get('Assessment\Model\UserTable');
            $childData = $usertable->getChildData($userId);
            $childcount = $usertable->countChildData($userId);
            //$childcount = count($childData);

            $table = $this->getServiceLocator()->get('Assessment\Model\TuserpackageTable');
            // This function get the all subscribed packages of loged user.            
            $packages = $table->getPackages($userId, 'all', 'all', 'all');

            $comMapperObj = $this->getServiceLocator()->get("com_mapper");
            $countryData = $comMapperObj->getAllCountries();
            $userCountryId = $userObj->getCountryId();
            $userStateId = $userObj->getStateId();
            $userCity = $userObj->getOtherCity();
            $statelist = $comMapperObj->getCountarybystate($userCountryId);

            $boardList = $this->getService()->getBoardList();
            $valueOptions = array();
            $valueOptions[''] = 'Select Board';
            foreach ($boardList as $container) {
                $valueOptions[$container->getRackId()] = $container->getRackName()->getName();
            }

            $result = new ViewModel(array(
                'usertypeid' => $changeUserType,
                'childData' => $childData,
                'countuserlog' => $countuserlog,
                'parentpkg' => $packages,
                'welcome' => $welcome,
                'childcount' => $childcount,
                'boardList' => $valueOptions,
                'countries' => $countryData,
                'states' => $statelist,
                'userCountryId' => $userCountryId,
                'userStateId' => $userStateId,
                'userObj' => $userObj,
                'classid' => $classId,
                'userCity' => $userCity,
                'freeparent' => $freeParent,
                'satPackage' => $satPackage,
                'capPackage' => $capPackage,
                'isClass' => $isClass,
            ));
            return $result;
        } else if ($changeUserType == 3) {
            $result = new ViewModel(array(
                'usertypeid' => $changeUserType,
                'countuserlog' => $countuserlog,
                'welcome' => $welcome,
                'classid' => $classId,
                'freeteacher' => $freeTeacher,
                'satPackage' => $satPackage,
                'capPackage' => $capPackage,
                'isClass' => $isClass,
                'boardList' => $valueOptions,
            ));
            return $result;
        } else {
            $result = new ViewModel(array(
                'usertypeid' => 1,
                'freestudent' => $freeStudent,
                'satPackage' => $satPackage,
                'capPackage' => $capPackage,
                'classid' => $classId,
                'isClass' => $isClass,
                'boardList' => $valueOptions,
            ));
            return $result;
        }
    }
    
    /* This code begin send sms for testing purpose */
    public function sendsmsAction(){
        $example = new SmsController();
        $example->getEventManager()->attach(array('fooo', 'callEvent', 'bazo'), function($e) {
            $event  = $e->getName();
            $target = get_class($e->getTarget()); // "Example"
            $params = $e->getParams();
            printf(
                'Handled event "%s" on target "%s", with parameters %s',
                $event,
                $target,
                json_encode($params)
            );
        });
        
        $example->callEvent('445646445', 'Amy sms content write here');
        echo '<br/>';
        $example->fooo('446445', 'Amy sms content write here');
        echo '<br/>';
        $example->bazo('45', $_REQUEST['email']);
        return false;
    }
    
    public function smsAction()
     {
        if ($this->getRequest()->isPost()) 
        { 
            if(isset($_REQUEST['email_id']) && !empty ($_REQUEST['email_id']))
            {
                /*
                $this->redirect()->toRoute('user/defaults', array(
                    'controller' => 'user',
                    'action' =>  'sendsms',
                    'param1' => $_REQUEST['email_id']
                ));
                */
                $this->redirect()->toUrl("sendsms?email=".$_REQUEST['email_id']);
            }        
             
        } 
        $view = new ViewModel(array(
            'message' => 'Hello world',
        ));
        $view->setTemplate('user/index/sms.phtml');
        return $view;
    }
    
  public function changechildstatusAction() {

        if($this->zfcUserAuthentication()->hasIdentity()){
          if (isset($_POST)){
                $userObj = $this->zfcUserAuthentication()->getIdentity();
                $Id      = $_POST['Id'];
                $data    = array(
                  'status' => $_POST['Status'],
                 );
               // echo "<pre />"; print_r($_POST); die;
                //Status
                   $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');                 
                   $notification = $notificationtable->getnotification('',$Id);
                   if(count($notification)>'0'){
                      foreach($notification as $notify){
                       $notifydata = array(
                      'notification_status' => '2',
                     ); 
                       $notificationtable->updateStatus($notify->notification_id,$notifydata);
                      }
                   }
                $table = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
                $updatestatus = $table->updateStatus($Id, $data);                
                if ($_POST['Status'] == '1') {                           
                    $notificationdata = array(
                      'notification_text' => $userObj->getDisplayname().'&nbsp; has accepted the request to be your parent.',
                       'userid' => $_POST['childID'],
                       'type_id' => '1',    // group
                       'relation_id'=> $Id,
                       'notification_url' => 'my-parent/1',
                       'created_by' =>  $userObj->getId(),
                       'created_date'  	=> date('Y-m-d H:i:s'),	
                    );                           
             }else if(isset($_POST['Status']) && $_POST['Status'] == '2'){                           
                           if($_POST['type']== 'delete'){                               
                            $notificationdata = array(
                            'notification_text' => $userObj->getDisplayname().'&nbsp;has deleted you as his child',
                            'userid' => $_POST['childID'],
                            'type_id' => '1',    // group
                            'relation_id'=> $Id,
                            'notification_url' => 'my-parent/1',
                            'created_by' => $userObj->getId(),
                            'created_date'  	=> date('Y-m-d H:i:s'),	
                            );
             }else{
                   $notificationdata = array(
                    'notification_text' => $userObj->getDisplayname().'&nbsp;has declined your request to be his/her Child',
                    'userid' => $_POST['childID'],
                     'type_id' => '1',    // group
                     'relation_id'=> $Id,
                     'notification_url' => 'my-parent/1',
                     'created_by' => $userObj->getId(),
                     'created_date'  	=> date('Y-m-d H:i:s'),	
                    );
               }
            } 
            $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
            $notificationtable->insertnotification($notificationdata);
                //if activating set parent Id in t_user
                if ($_POST['Status'] == '1') {
                    $table = $this->getServiceLocator()->get('Assessment\Model\TuserTable');
                    $updateId = $table->updateparentID($_POST['childID'], $userObj->getId());
                }
                if ($updatestatus != '0') {
                    $result = new \Zend\View\Model\JsonModel(array(
                        'output' => 'sucusses',
                    ));
                }else if($updateId != '0'){
                    $result = new \Zend\View\Model\JsonModel(array(
                        'output' => 'sucusses',
                    ));
                } else {
                    $result = new \Zend\View\Model\JsonModel(array(
                        'output' => 'notsucusses',
                    ));
                }
                return $result;
            }
        }
    }
    
   public function mentorpopupAction(){
      $post    =  $this->getRequest()->getPost();
      if($post['type'] == 'add'){
          $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
         if ($auth->hasIdentity()) {
            $userObj = $auth->getIdentity();
         }
       $result = new ViewModel(array(
            'boards' => $this->getServiceLocator()->get('lms_container_mapper')->getCustomBoard(),
             'userObj' => $userObj,
        ));
        $result->setTerminal(true);
        return $result;
       
      }
  } 
  public function updatepasswordAction(){
      if($this->zfcUserAuthentication()->hasIdentity()){
        $userObj = $this->zfcUserAuthentication()->getIdentity();
        if ($this->getRequest()->isPost()) 
        { 
          $post        = $this->getRequest()->getPost();
          $currentpwd  = $post['credential'];
          $newpassword = $post['newpassword'];
          $conformpwd  = $post['conformpassword'];
          $hintpassword  = '';
          
          $userid     = $userObj->getId();
          $tableuser  = $this->getServiceLocator()->get('Assessment\Model\UserTable');
          $otherTable = $this->getServiceLocator()->get('Assessment\Model\UserOtherDetailsTable');
          $pwdcorrect = $tableuser->ispasswordcorrect($userid,$currentpwd);
          
          if($pwdcorrect == 0)
          {
              $result = new \Zend\View\Model\JsonModel(array(
                'output' => 'errorpwd',));
              return $result;
          }
         if($pwdcorrect > 0 && ($newpassword == $conformpwd))
         {
            $updatestatus =  $tableuser->updatepassworddetail($conformpwd,$hintpassword,$userid);
            $data = array(
                     "user_id"=>$userid,
                     "key_name"=>"passwordUpdate",
                     "value"=>"1"
                );
            $otherdetailadded = $otherTable->InsertOtherDetail($data);
            $result = new \Zend\View\Model\JsonModel(array(
                'output' => 'success',));
              return $result;
         }else{
          
             $result = new \Zend\View\Model\JsonModel(array(
                'output' => 'error',));
              return $result;
         }  
          
          
        }
        
      }
 }
 public function updatepackageprofileAction()
 {
      if($this->zfcUserAuthentication()->hasIdentity()){
        $userObj = $this->zfcUserAuthentication()->getIdentity();
        if ($this->getRequest()->isPost()) 
           { 
            $post              = $this->getRequest()->getPost();
   
        if($post['data'] == 'file')
        {
            $filename = '';
            $imageName = '';
            $fileerror = '';
           
            $commonObj = $this->getServiceLocator()->get("com_mapper");
            if (isset($_FILES['image_file']) && $_FILES['image_file']['name'] != "") {
                    $Filedata = $commonObj->validateImage($_FILES);
                    
                    if (!is_array($Filedata)) {
                        if ($Filedata == 'corrupt') {
                            $result = new \Zend\View\Model\JsonModel(array(
                            'output' => 'tfalse',));
                            return $result;
                        } else {
                            $result = new \Zend\View\Model\JsonModel(array(
                            'output' => 'false',));
                            return $result;
                            
                        }
                    }
                }
                if ($Filedata['image_file']['name'] != "") {
                    $filename = str_replace(' ', '_', $Filedata['image_file']['name']);
                    $file_type = substr(strrchr($Filedata['image_file']['name'], '.'), 1);
                    $imageName = $userObj->getId() . '.' . $file_type;
                    $fileUploaded = $this->ftpFileUploaded($Filedata['image_file']['tmp_name'], '/uploads/profileimages/' . $imageName);
                    if($fileUploaded){
                       $data = array('user_photo'=>$imageName);
                       $tableuser  = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                       $update_status = $tableuser->updateUserAddress($data, $userObj->getId());
                    }
                    $result = new \Zend\View\Model\JsonModel(array(
                            'output' => 'success',));
                            return $result;
                     
               } else{
                   $result = new \Zend\View\Model\JsonModel(array(
                            'output' => 'false',));
                            return $result;
               }    
            
                
        }else{
            $posts['phone']     = $post['uphonenumber'];
            $posts['gender']   = $post['gender'];
            $posts['dob']  = $post['dob'];
            $posts["phcode"]= $post['phonecode'];
            
            $userid     = $userObj->getId();
            $school_ids     = $userObj->getSchoolId();
            
            $tableuser  = $this->getServiceLocator()->get('Assessment\Model\UserTable');
            $update_status = $tableuser->updatepkgprofile($posts, $userid);

            $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
            $classId = $this->zfcUserAuthentication()->getIdentity()->getClassId();
                 
            $data = array(
                  'notification_text' => "Dear&nbsp;".$this->zfcUserAuthentication()->getIdentity()->getDisplayName() . '&nbsp;Complete Your Profile',
                  'userid' => $userid,
                  'type_id' => '1',
                  'notification_url' => '',
                  'created_by' => $userid,
                   'created_date' => date('Y-m-d H:i:s'),
                );
            $notificationtable->insertnotification($data);
             
            $otherTable = $this->getServiceLocator()->get('Assessment\Model\UserOtherDetailsTable');
          
            $data = array(
                     "user_id"=>$userid,
                     "key_name"=>"profileUpdate",
                     "value"=>"1"
                );
            
            $otherdetailadded = $otherTable->InsertOtherDetail($data);
             
            
            
            $result = new \Zend\View\Model\JsonModel(array(
                'output' => 'success',));
              return $result;
          }
        }
      $result = new \Zend\View\Model\JsonModel(array(
                'output' => 'error',));
              return $result;
      
      }else{
          $result = new \Zend\View\Model\JsonModel(array(
                'output' => 'error',));
              return $result;
      }
 }

  public function allQuestionsAction() {
        $getQuestions = "";
        $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
        
         $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
         if($auth->hasIdentity()) {
                $userObj = $auth->getIdentity();
                $userId  = $userObj->getId(); 
      }
        
        // $userId = $_SESSION['user']['userId'];
        // Get all group ids of loged user

        $groupid = '';
        if (isset($_POST['type']) && $_POST['type'] == 'group') {
            // get group owner id's of loged user 
            $groupids = $table->getgroupowner($userId);

            //get group owner id's of loged user 
            $groupownerids = $table->getgroupowner($userId);

            //get group members of loged user
            $groupmemberids = $table->getAllActivefriends($userId);

            //get group list of all     
            $groupusers = $table->getAllgroupfriends($userId);
            //echo '<pre>'; print_r($groupmemberids); die;
            // array of all users whose query will be displayed 
            $ids = '';
            $tIds = '';
            $groupid = '';
            foreach ($groupmemberids as $idds) {
                $ids .= "'" . $idds->friend_id . "'" . ",";
            }
            foreach ($groupusers as $idds) {
                $ids .= "'" . $idds->user_id . "'" . ",";
            }

            foreach ($groupownerids as $gids) {
                $groupid .= "'" . $gids->user_id . "'" . ",";
            }
            if ($groupid == '') {
                $groupid .= "'" . $userId . "'";
            }

            $group_userids = array();

            $ids .= "'" . $userId . "'" . ",";
            $tIds = rtrim($ids, ",");
        } else {
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
                // Get all ids of loged user groupmembers and group members friends
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
        }

        $commentTable = $this->getServiceLocator()->get('Notification\Model\TquestionTable');
        // Get total count of comments

        //echo "<pre />"; print_r($_POST); exit;
        $rack_id=array();
        $details =  $this->getServiceLocator()->get('lms_container_service')->getChildrecursively($_POST['chapterid']);
       foreach($details as $dl)
       {           
           $rack_id[] = $dl->getRackId();
       }   
       $chapter_id=implode(",",$rack_id);
        $totalCount = $commentTable->totalCount($tIds, $_POST['group_name'], $_POST['board'], $_POST['classid'], $_POST['subjectid'], $chapter_id, '', '', rtrim($groupid, ','));
        $currentPage = '1';
        $resultsPerPage = '2';
        // Get total questins and comments
        $paginator = $commentTable->getQestions($tIds, $_POST['group_name'], $_POST['board'], $_POST['classid'], $_POST['subjectid'],$chapter_id, $currentPage, $resultsPerPage, rtrim($groupid, ','));
        $result = new ViewModel(array(
            'getQuestions' => $paginator,
            'countComments' => $paginator,
            'countposts' => $totalCount,
            'currentPage' => $currentPage,
            'resultsPerPage' => $resultsPerPage,
        ));
        $result->setTerminal(true);
        return $result;
    } 
    
    public function mobileUrlAction() {
        $mobile = $this->getRequest()->getPost('mobile');
        $url = "http://10.1.6.19/SaveCall/SaveCall.aspx?CampaignID=Outbound&Duplicate=2&priority=2&Remark=Default&Phoneno=$mobile&Name=Alok&City=Noida";
        $datas = file_get_contents($url);
        echo json_encode($datas);
        exit;
    }
    
    public function unsubscriptionAction() {
        $userId = $this->params()->fromRoute('userId');
        $result = new ViewModel(array(
            'user_id' => $userId,
        ));
        return $result;
    }
    
    /* This code end send sms for testing purpose */
    
    /* get the card details in case of used activation code.
     * 
     */
    public function ajaxGetCardDetailsAction() {
        if (isset($_POST['packageId']) && isset($_POST['code_assign_id'])) {
            if ($this->zfcUserAuthentication()->hasIdentity()) {
                $loggedIUserObj = $this->zfcUserAuthentication()->getIdentity();
                // getting user type either(Student, Mentor, Teacher)
                $userTypeId = $loggedIUserObj->getUserTypeId();
                $userid = $loggedIUserObj->getId();
                $table = $this->getServiceLocator()->get('Assessment\Model\TuserpackageTable');
                $activationCodeAssignmentTable = $this->getServiceLocator()->get('Dealer\Model\ActivationCodeAssignmentTable');
                // This function get the all order details of subscribed packages of loged user
                $activationCodeAssignmentData = $activationCodeAssignmentTable->getCardDetailsById($_POST['code_assign_id'], $_POST['transaction_id'], $_POST['user_pkg_id']);
                
                $result = new ViewModel(array(
                    'cardDetailes' => $activationCodeAssignmentData
                ));
                
                $result->setTerminal(true);
                return $result;
            }
        }die;
    }
    
    /* check the activation code and update the subscription validity if correct.
     * 
     */
    public function validatEextensionCodeAction() {
        $request = $this->getRequest();
        $post = $request->getPost();
        
        $code_assign_id = $post->cai;
        $excode = $post->excode;
        $transaction_id = $post->tranid;
        $user_package_id = $post->upkgid;
        
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $loggedIUserObj = $this->zfcUserAuthentication()->getIdentity();
            // getting user type either(Student, Mentor, Teacher)
            $userTypeId = $loggedIUserObj->getUserTypeId();
            $user_id = $loggedIUserObj->getId();
            $activationCodeAssignmentTable = $this->getServiceLocator()->get('Dealer\Model\ActivationCodeAssignmentTable');
            // This function get the card details
            $activationCodeAssignmentData = $activationCodeAssignmentTable->getCardDetailsByAssignId($code_assign_id, $excode);
            //echo '<pre>';print_r($activationCodeAssignmentData); echo '</pre>';die('macro Die');
            
            if(!empty($activationCodeAssignmentData) && count($activationCodeAssignmentData)) {
                
                if($activationCodeAssignmentData->extension_used_status == 1) {
                    echo '2';
                    die;
                } else {
                    $userPackageTable = $this->getServiceLocator()->get('Package\Model\TuserpackageTable');
                    $userTransactionTable = $this->getServiceLocator()->get('Package\Model\TusertransactionTable');
                    $userPackageData = $userPackageTable->getUserPackageTransactionAssignDetails($transaction_id, $code_assign_id, $user_package_id);
                    if(count($userPackageData)) {
                        
                        $currentValidTill = $userPackageData->current()->valid_till;
                        $currentDate = date('Y-m-d H:i:s');
                        
                        $diff = strtotime($currentDate) - (strtotime($currentValidTill));
                        $differenceDays = floor($diff/3600/24);
                        
                        $differenceDays = ($differenceDays <= 0) ? 0 : $differenceDays;
                        
                        $updatePkgData = array();
                        $updatePkgData['days'] = $userPackageData->current()->days;
                        $updatePkgData['display_validity_date'] = $userPackageData->current()->display_validity_date;
                        $updatePkgData['valid_date'] = $userPackageData->current()->valid_date;
                        $updatePkgValidDate = $userPackageTable->updateUserPackagesByExtensionCode($updatePkgData, $user_id, $transaction_id, $user_package_id, $differenceDays);
                        if(!empty($updatePkgValidDate)) {
                            $userExCodeData = array();
                            $userExCodeData['extension_used_status'] = 1;
                            $userExCodeData['extension_used_date'] = date('Y-m-d H:i:s');
                            $activationCodeAssignmentTable->updateUserExtensionCode($userExCodeData, $code_assign_id, $excode);
                            $formatedUpdatePkgValidDate = date('d F, Y', strtotime($updatePkgValidDate));
                            echo json_encode(array("valid_date" => $updatePkgValidDate, "formated_valid_date" => $formatedUpdatePkgValidDate));
                            die;
                        }
                    }
                }
            } else {
                echo "0";
                die;
            }
            
        }
        die;
    }
    
    public function employeeReferralAction(){
        
        $result = new \Zend\View\Model\JsonModel(array(
                        'output' => 'success',
                    ));
        $data=array();
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        if($auth->hasIdentity()) {
                $userObj = $auth->getIdentity();
                $data['referred_by_user_id']=$userId  = $userObj->getId(); 
            }
        if($this->getRequest()->isPost()){
            $data['name']=$name=$_POST['name'];
            if($this->validateEmail($_POST['email'])=='Valid'){
                $data['email']=$email=$_POST['email'];
            }else{
                $result = new \Zend\View\Model\JsonModel(array(
                        'output' => 'fail','message'=>'invalid email'
                    ));
            }
            
            if($_POST['mobile']!=''){
                $data['mobile']=$mobile=$_POST['mobile'];
            }
            
            if($_POST['message']!=''){
                $data['message']=$mobile=$_POST['message'];
            }
            
            $userTableModel=$this->getServiceLocator()->get('Assessment\Model\UserTable');
            $alreadyRegisteredUser=(array)$userTableModel->getUserbyemail($_POST['email']);
            if(empty($alreadyRegisteredUser)){
                $data['referred_to_user_id']=0;
                
                $filepath= __DIR__ . '../../../../view/mailer/';
                /*
                 * Email Content
                 */
            }else{
                if($userId==$alreadyRegisteredUser['user_id']){
                    $result = new \Zend\View\Model\JsonModel(array(
                        'output' => 'fail','message'=>'self'
                    ));
                    return $result;
                }
                
                $data['referred_to_user_id']=$alreadyRegisteredUser['user_id'];
                
                $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
                $notificationdata = array(
                       'notification_text' => $userObj->getDisplayname().'&nbsp; has referred you something.',
                       'userid' => $alreadyRegisteredUser['user_id'],
                       'type_id' => '6',    
                       'relation_id'=> '0',
                       'notification_url' => 'home',
                       'created_by' =>  $userObj->getId(),
                       'created_date'  	=> date('Y-m-d H:i:s'),	
                    ); 
                $notificationtable->insertnotification($notificationdata);
            }
            $empTableModel=$this->getServiceLocator()->get('Assessment\Model\EmployeeReferralTable');
            $insertData=$empTableModel->insertdata($data);
            if($mobile!=''){
                $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                $msgTxt='Referral Sent';
                $smsArr = array('to_mobile_number'=>$mobile,
                        'msg_txt' => $msgTxt,
                        'user_id' => $userObj->getId(),
                        'mobile_number' => $userObj->getMobile(),
                        'sms_type' => 'referral'
                    );
                $smsResult = $comMapperObj->smssendprocess($smsArr);
            }
            
        }else{
            $result = new \Zend\View\Model\JsonModel(array(
                        'output' => 'fail','message'=>'invalid method'
                    ));
        }
        return $result;
    }
    
    public function validateEmail($email)
    {
        $expression  ="/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD"; 
        if(!preg_match($expression, $email)) {
            $return = "Not_valid";
        }else{
            $return = "Valid"; 
        }
        return $return;  
    }

}
