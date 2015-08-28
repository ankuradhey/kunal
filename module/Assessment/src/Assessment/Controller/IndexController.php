<?php

namespace Assessment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container; // We need this when using sessions
use Zend\Cache\StorageFactory;
use Zend\Authentication\AuthenticationService;
use Zend\Session\SaveHandler\Cache;
use Zend\Session\SessionManager;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter as paginatorArray;
use Common\Mapper\CommonMapper as CommonMapper;
use ZfcUser\Service\User as UserService;
use Zend\ViewModel\JsonModel;
use Zend\Escaper\Escaper;
use Zend\Http\Request;
use Zend\Http\Client;
use Zend\Stdlib\Parameters;

class IndexController extends AbstractActionController {

    protected $userTable;
    protected $questionTable;
    protected $mentorPaperTable;
    protected $mentorQuestionTable;
    protected $mentorPaperQuestionTable;
    protected $mentorAssignPaperTable;
    protected $mentorPaperUserAnswerTable;
    protected $mentorPaperUploadFileTable;
    protected $apiService;
    protected $answerTable;

    function getUserTable() {
        if (!$this->userTable) {

            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Assessment\Model\UserTable');
        }
        return $this->userTable;
    }

    public function getApiService() {
        if (!$this->apiService) {
            $this->apiService = $this->getServiceLocator()->get('api_service');
        }
        return $this->apiService;
    }
    
    function getQuestionTable() {
        if (!$this->questionTable) {

            $sm = $this->getServiceLocator();
            $this->questionTable = $sm->get('Mcq\Model\QuestionsTable');
        }
        return $this->questionTable;
    }

    function getMentorPaperTable() {
        if (!$this->mentorPaperTable) {

            $sm = $this->getServiceLocator();
            $this->mentorPaperTable = $sm->get('Assessment\Model\MentorPaperTable');
        }
        return $this->mentorPaperTable;
    }

    function getMentorQuestionTable() {
        if (!$this->mentorQuestionTable) {

            $sm = $this->getServiceLocator();
            $this->mentorQuestionTable = $sm->get('Assessment\Model\MentorQuestionTable');
        }
        return $this->mentorQuestionTable;
    }

    function getMentorPaperQuestionTable() {
        if (!$this->mentorPaperQuestionTable) {

            $sm = $this->getServiceLocator();
            $this->mentorPaperQuestionTable = $sm->get('Assessment\Model\MentorPaperQuestionTable');
        }
        return $this->mentorPaperQuestionTable;
    }

    function getMentorPaperUserAnswerTable() {
        if (!$this->mentorPaperUserAnswerTable) {

            $sm = $this->getServiceLocator();
            $this->mentorPaperUserAnswerTable = $sm->get('Assessment\Model\MentorPaperUserAnswerTable');
        }
        return $this->mentorPaperUserAnswerTable;
    }

    function getMentorAssignPaperTable() {
        if (!$this->mentorAssignPaperTable) {

            $sm = $this->getServiceLocator();
            $this->mentorAssignPaperTable = $sm->get('Assessment\Model\MentorAssignPaperTable');
        }
        return $this->mentorAssignPaperTable;
    }
    function getMentorPaperUploadFileTable() {
        if (!$this->mentorPaperUploadFileTable) {

            $sm = $this->getServiceLocator();
            $this->mentorPaperUploadFileTable = $sm->get('Assessment\Model\MentorPaperUploadFileTable');
        }
        return $this->mentorPaperUploadFileTable;
    }

    function getAnswerTable() {
        if (!$this->answerTable) {

            $sm = $this->getServiceLocator();
            $this->answerTable = $sm->get('Mcq\Model\AnswerTable');
        }
        return $this->answerTable;
    }
    
    protected $options;
    
    protected $service;
    
    public function getService() {
        if (!$this->service) {
            $this->service = $this->getServiceLocator()->get('lms_container_service');
        }
        return $this->service;
    }

    

    public function createpaperAction() {
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $studentmentorRelationId = $this->params()->fromRoute('studentId', 0);
            $loggedIUserObj = $this->zfcUserAuthentication()->getIdentity();

            $mentorId = $loggedIUserObj->getId();


            // getting the mentor student's  name, class, subject

            $tablestudent = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
            $userDetails = $tablestudent->getStudentMentorRelationDetails($studentmentorRelationId);
//echo '<pre>';print_r('jhghhgj');echo '</pre>';die('Macro Die');
//        echo '<pre>User Details';var_dump($userDetails);exit;
            foreach ($userDetails as $user) {
//            echo '<pre>';print_r($user);echo '</pre>';die('Macro Die');
                $studentName = $user->name;
                $boardId = $user->board_id;
                $boardName = $user->board_name;
                $classId = $user->class_id;
                $className = $user->class_name;
                $subjectId = $user->subject_id;
                $subjectName = $user->subject_name;
                $studentId = $user->student_id;
            }
            $data = $this->getRequest()->getPost();

            if (count($data) > 0) {

                // adding mentor paper
                $dataMentor['mentor_paper_class'] = $data['classId'];
                $dataMentor['mentor_paper_subject'] = $data['subjectId'];
                $dataMentor['mentor_paper_name'] = $data['mentor_paper_name'];
                $dataMentor['mentor_paper_marks'] = $data['mentor_paper_marks'];
                $dataMentor['mentor_paper_time'] = $data['mentor_paper_hour'] . ':' . $data['mentor_paper_minute'];
                $dataMentor['mentor_paper_added_by'] = $mentorId;
                $dataMentor['mentor_paper_question_source'] = $data['papersource'];
                $dataMentor['mentor_paper_instruction'] = $data['mentor_paper_instruction'];
                $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                $customBoardId = $comMapperObj->getcustomboardPrimaryId($data['boardId'],$data['classId']); 
                
                $dataMentor['custom_board_rack_id'] = $customBoardId;
                $mentorPaperId = $this->getMentorPaperTable()->insertdata($dataMentor);
                
                
//                echo '<pre>';print_r($mentorPaperId);echo '</pre>';die('Macro Die');

                if ($data['papersource'] == 'add') {
                    foreach ($data['ques'] as $question) {
                        if ($question['questionsource'] == 'self') {
                            // adding mentor question
                            $dataMentorQuestion['mentor_paper_id'] = $mentorPaperId;
                            $dataMentorQuestion['mentor_question'] = htmlentities($question['ques']);
                            $dataMentorQuestion['mentor_question_marks'] = $question['marks'];
                            $mentorQuestionId = $this->getMentorQuestionTable()->insertdata($dataMentorQuestion);

                            // adding mentor paper question
                            $dataMentorPaperQuestion['mentor_paper_id'] = $mentorPaperId;
                            $dataMentorPaperQuestion['question_id'] = $mentorQuestionId;
                            $dataMentorPaperQuestion['mentor_paper_question_marks'] = $question['marks'];
                            $dataMentorPaperQuestion['mentor_paper_question_source'] = $question['questionsource'];
                            $mentorPaperQuestionId = $this->getMentorPaperQuestionTable()->insertdata($dataMentorPaperQuestion);
                        } else if ($question['questionsource'] == 'lms') {
                            // adding mentor paper question in case of lms question
                            $dataMentorPaperQuestion['mentor_paper_id'] = $mentorPaperId;
                            $dataMentorPaperQuestion['question_id'] = $question['questionId'];
                            $dataMentorPaperQuestion['mentor_paper_question_marks'] = $question['marks'];
                            $dataMentorPaperQuestion['mentor_paper_question_source'] = $question['questionsource'];
                            //echo '<pre>';print_r($dataMentorPaperQuestion);die;
                            $mentorPaperQuestionId = $this->getMentorPaperQuestionTable()->insertdata($dataMentorPaperQuestion);
                        }
                    }
                } else if ($data['papersource'] == 'upload') { //echo '<pre>';print_r($_FILES);die;
                    $counter = count($_FILES['ques']['name']);
                    $totalPaperMarks = 0;
                    for ($i = 0; $i < $counter; $i++) {
                        $filename = str_replace(' ', '_', $_FILES['ques']['name'][$i]['file']);

                        $file_type = substr(strrchr($_FILES['ques']['name'][$i]['file'], '.'), 1);

                        $questionfile = 'ques_' . $i . '_' . $mentorPaperId . '.' . $file_type;
                        //move_uploaded_file($_FILES['ques']['tmp_name'][$i]['file'],'public/uploads/mentorquestionfiles/'.$questionfile);
                        $fileUploaded = $this->ftpFileUploaded($_FILES['ques']['tmp_name'][$i]['file'], '/uploads/mentorquestionfiles/' . $questionfile);

                        $totalPaperMarks = $totalPaperMarks + $data['ques'][$i]['marks'];
                        // adding mentor paper Upload file
                        $dataMentorUploadfile['mentor_paper_id'] = $mentorPaperId;
                        $dataMentorUploadfile['paper_upload_file_type'] = 'ques';
                        $dataMentorUploadfile['paper_upload_file_name'] = $questionfile;
                        $dataMentorUploadfile['paper_upload_file_marks'] = $data['ques'][$i]['marks'];
                        $this->getMentorPaperUploadFileTable()->insertdata($dataMentorUploadfile);
                    }
                    // update paper table with total marks
                    $mentorPaperUpdateData = array('mentor_paper_marks' => $totalPaperMarks);
                    $this->getMentorPaperTable()->updateData($mentorPaperId, $mentorPaperUpdateData);
                }

                // assigning paper to student
                $dataMentorAssignPaper['mentor_paper_id'] = $mentorPaperId;
                $dataMentorAssignPaper['mentor_paper_assigned_by'] = $mentorId;
                $dataMentorAssignPaper['mentor_paper_assigned_to'] = $studentId;
                $this->getMentorAssignPaperTable()->insertdata($dataMentorAssignPaper);
                
                // add notification for  mentor assign paper
                $userObj   = $this->zfcUserAuthentication()->getIdentity();
                $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
                $notifydata = array(
                    'notification_text' => $userObj->getDisplayname().' has assigned a '. $data['subjectName'] .' test to you on '.date('d-m-Y').'.',
                    'userid' => $studentId,
                    'type_id' => '3',    // mentor
                    'relation_id'=> '0',
                    'notification_url' => 'myassignedpaper',
                    'created_by' => $userObj->getId(),
                    'notification_uuid' => $this->getApiService()->generateUuid(),
                    'created_date'      => date('Y-m-d H:i:s'),    
                    );

                $notificationtable->insertnotification($notifydata);
                return $this->redirect()->toRoute('mypaper', array(''));
            }
            $tablestudent = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
            $addedStudent = $tablestudent->getAll($loggedIUserObj->getId(), 'mentor');
            $addedStudents = $addedStudent->buffer();
            //echo '<pre>';print_r($boardId); echo '</pre>';die('Macro Die');
            return new ViewModel(array(
                'studentName' => $studentName,
                'boardId' => $boardId,
                'boardName' => $boardName,
                'classId' => $classId,
                'className' => $className,
                'subjectId' => $subjectId,
                'subjectName' => $subjectName,
                'addedStudents' => $addedStudents,
                'userObj' => $loggedIUserObj,
                'studentId'=>$studentId
            ));
        }
    }

    /*public function ftpFileUploaded($sourcePath, $targetPath,$file_name='') {
        $config = $this->getServiceLocator()->get('config');
        $ftpDetails = $config['ftp_config'];
        //echo '<pre>'; print_r($ftpDetails); exit;
        $conn_id = ftp_connect($ftpDetails['FTP_SERVER']);       // set up basic connection  

        $login_result = ftp_login($conn_id, $ftpDetails['FTP_USERNAME'], $ftpDetails['FTP_PASSWORD']); // ftp login        

        if ($login_result) {
            if(is_dir($targetPath)){
                if(!empty($file_name)) {
                    $upload = ftp_put($conn_id, $targetPath.$file_name, $sourcePath, FTP_BINARY);  // upload the file
                } else {
                    $upload = ftp_put($conn_id, $targetPath, $sourcePath, FTP_BINARY);
                }
                if (!$upload) {  // check upload status
                    $fileStatus = 'error';
                } else {
                    $fileStatus = 'success';
                }
            }else if (@ftp_mkdir($conn_id, $targetPath)) {
                if(!empty($file_name)) {
                    $upload = ftp_put($conn_id, $targetPath.$file_name, $sourcePath, FTP_BINARY);  // upload the file
                } else {
                    $upload = ftp_put($conn_id, $targetPath, $sourcePath, FTP_BINARY);
                }
                if (!$upload) {  // check upload status
                    $fileStatus = 'error';
                } else {
                    $fileStatus = 'success';
                }
            } else {
                $fileStatus = 'error';
            }
        } else {
            $fileStatus = 'error';
        }
        ftp_close($conn_id); // close the FTP stream               
        return $fileStatus;
    }*/
    public function ftp_mksubdirs($ftpcon,$ftpbasedir,$ftpath){
   @ftp_chdir($ftpcon, $ftpbasedir); // /var/www/uploads
   $parts = explode('/',$ftpath); // 2013/06/11/username
   foreach($parts as $part){
      if(!@ftp_chdir($ftpcon, $part)){
         ftp_mkdir($ftpcon, $part);
         ftp_chdir($ftpcon, $part);
         @ftp_chmod($ftpcon, 0777, $part);
      }
   }
}
    public function ftpFileUploaded($sourcePath, $targetPath,$id='',$name='')
    {
        $upload = '';
        $config     = $this->getServiceLocator()->get('config');
        $ftpDetails = $config['ftp_config'];
        $conn_id = ftp_connect($ftpDetails['FTP_SERVER']);        // set up basic connection        
        $login_result = ftp_login($conn_id, $ftpDetails['FTP_USERNAME'], $ftpDetails['FTP_PASSWORD']); // ftp login     
         if($login_result) {
             if(!empty($id) && !empty($name)) {
                if(!is_dir($targetPath.$id.'/')) {
                    $res =  $this->ftp_mksubdirs($conn_id, $targetPath,$id);
                }
                //$target_path = $targetPath.$id.'/'.$name;
                $target_path = $targetPath.$id.'/'.$name;
                //if (ftp_chmod($conn_id, 0644, $target_path) !== false) {
                    @ftp_chmod($conn_id, 0777, $targetPath);
                    $upload = ftp_put($conn_id, $target_path, $sourcePath, FTP_BINARY);
                    //echo $conn_id.' ============ '.$target_path.' ========== '.$sourcePath; exit;
                //}
             } else {
                //@ftp_chmod($conn_id, 0777, $targetPath);
                @ftp_chmod($conn_id, 0777, $targetPath); 
                $upload = ftp_put($conn_id, $targetPath, $sourcePath, FTP_BINARY);
                //echo $conn_id.'========='.$targetPath.'============'.$sourcePath; exit;
             }
                // upload the file

               if (!$upload) {  // check upload status
                   $fileStatus = 'error';
               } else {
                   $fileStatus = 'success';
               }

           }else{
               $fileStatus = 'error';
           }       
           ftp_close($conn_id);
           return $fileStatus;
    }
    

    public function myassignedpaperAction() {
        $studentId=$this->getEvent()->getRouteMatch()->getParam('id');
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $studentPanel = 'yes';
            $studentId = $this->zfcUserAuthentication()->getIdentity()->getId();
            $studentPaperList = $this->getMentorAssignPaperTable()->getMentorAssignedPaperList('', $studentId);

            return new ViewModel(array(
                'studentPaperList' => $studentPaperList,
                'studentPanel' => $studentPanel,
            ));
        }
//        else{
//            if($studentId!=''){
//            $studentPanel = 'onflyyes';
//
//            $this->userregistrationapiAction();
//            $studentPaperList = $this->getMentorAssignPaperTable()->getMentorAssignedPaperList('', $studentId);
//            return new ViewModel(array(
//                'studentPaperList' => $studentPaperList,
//                'studentPanel' => $studentPanel,
//            ));
//            }
//        }
    }


    /*
     * Author: Pradeep Kumar
     * Description: User Login for Mobile API, Temperory Mehtod
     */

     public function userregistrationapiAction(){
                $email="guest4@extramarks.com";
                $pass="guest4";
                $baseUrl= 'http://localhost/school_lms/public/user/login?redirect=myassignedpaper';
                $request = new Request();
                $request->getHeaders()->addHeaders(array('Content-Type'=>'application/x-www-form-urlencoded; charset=UTF-8'));
                $request->setUri($baseUrl);
                $request->setMethod('POST');
                $request->setPost(new Parameters(array('identity' =>$email,'credential'=>$pass)));

                $client = new Client();
                $response = $client->dispatch($request);
                print_r($response);
                $data = json_decode($response->getBody(), true);
                print_r($data);
                exit;


               
                //echo '<pre>';print_r ($authAdapter);echo '</pre>';die('vikash');
               
                // zf2 authentication
                //$adapter = $this->zfcUserAuthentication()->getAuthAdapter();
                //$adapter->prepareForAuthentication($authAdapter);
                //$this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);
                
                /* //$viewModel = new ViewModel();
            $email="guest4@extramarks.com";
            $pass="guest4";
            $email_address = $email;
            $password = $pass;
            # data needs to be POSTed to the Play url as JSON.
            $data = array("identity"=>$email_address,"credential"=>$password);
            $data_string = json_encode($data);
            $event = $this->getEvent();
            $requestURL = $event->getRequest();
            //$baseUrl= 'http://'.$_SERVER['SERVER_NAME'].$requestURL->getBaseUrl();
            $baseUrl= 'http://localhost/school_lms/public/user/login?redirect=myassignedpaper'; 
            $ch = curl_init($baseUrl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
            );
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

            //execute post
            $result = curl_exec($ch);

            //close connection
            curl_close($ch);
            echo $result; die;
            return $result;
            //exit;*/

    }


    public function studenttestattemptAction() {
//        if(isset($_SESSION['user']['userId']) && $_SESSION['user']['userId']!=""){
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $layout = $this->layout();
            $layout->setTemplate('layout/test');
            $pageSubmit = 'no';
            // getting the paper Id
            $paperAssignId = $this->params()->fromRoute('paperassignId', 0);
            $paperList = $this->getMentorAssignPaperTable()->getMentorAssignedPaperList('', '', $paperAssignId);

            foreach ($paperList as $paper) {
                $paperId = $paper->paperId;
                $paperName = $paper->paperName;
                $paperMarks = $paper->paperMarks;
                $paperClass = $paper->paperClass;
                $paperSubject = $paper->paperSubject;
                $paperSource = $paper->paperSource;
                $paperInstruction = $paper->paperInstruction;
                $paperTime = $paper->paperTime;
            }

            if ($paperSource == 'upload') {
                // getting the paper questions
                $paperQuestion = $this->getMentorPaperTable()->getPaperQuestion($paperId, $paperSource);
            } else {
                $counter = 0;
                // getting the paper questions
                $paperQuestionId = $this->getMentorPaperQuestionTable()->getPaperQuestionId($paperId);
                foreach ($paperQuestionId as $question) {

                    if ($question->mentorPaperQuestionsource == 'self') {
                        $questionDetails = $this->getMentorQuestionTable()->getQuestionDetails($question->questionId);
                        foreach ($questionDetails as $qDetail) {
                            $paperQuestion[$counter]['mentorPaperQuestionId'] = $question->mentorPaperQuestionId;
                            $paperQuestion[$counter]['question'] = $qDetail->mentorQuestion;
                            $paperQuestion[$counter]['questionMarks'] = $qDetail->mentorQuestionMarks;
                            $counter++;
                        }
                    } else if ($question->mentorPaperQuestionsource == 'lms') {
                        $questionDetails = $this->getQuestionTable()->fetchAll($question->questionId);
                        foreach ($questionDetails as $qDetail) {
                            $paperQuestion[$counter]['mentorPaperQuestionId'] = $question->mentorPaperQuestionId;
                            $paperQuestion[$counter]['question'] = $qDetail->question;
                            $paperQuestion[$counter]['questionMarks'] = $question->mentorPaperQuestionMarks;
                            $counter++;
                        }
                    }
                }
            }
            //echo '<pre>';print_r($paperQuestion);die;
            // saving the student answers
            $data = $this->getRequest()->getPost();

            //
            if (count($data) > 0) {
                if ($data['source'] == 'add') {
                    foreach ($data['ques'] as $key => $value) {

                        $paperData = explode('_', $key);
                        //echo '<pre>';print_r($paperData);die;
                        $dataUserAns['mentor_paper_assign_id'] = $paperData[0];
                        $dataUserAns['mentor_paper_question_id'] = $paperData[1];
                        $dataUserAns['mentor_paper_user_ans'] = htmlentities($value['ans']);
                        $this->getMentorPaperUserAnswerTable()->insertdata($dataUserAns);
                    }
                } else if ($data['source'] == 'upload') {


                    $counter = count($_FILES['ans']['name']);

                    for ($i = 0; $i < $counter; $i++) {
                        $filename = str_replace(' ', '_', $_FILES['ans']['name'][$i]);
                        $file_type = substr(strrchr($_FILES['ans']['name'][$i], '.'), 1);
                        $answerfile = 'ans_' . $i . '_' . $paperId . '.' . $file_type;
                        //move_uploaded_file($_FILES['ans']['tmp_name'][$i],'public/uploads/mentorquestionfiles/'.$answerfile);
                        $fileUploaded = $this->ftpFileUploaded($_FILES['ans']['tmp_name'][$i], '/uploads/mentorquestionfiles/' . $answerfile);
                        // adding user answer Upload file
                        $dataMentorUploadfile['mentor_paper_id'] = $paperId;
                        $dataMentorUploadfile['paper_upload_file_type'] = 'ans';
                        $dataMentorUploadfile['paper_upload_file_name'] = $answerfile;
                        $this->getMentorPaperUploadFileTable()->insertdata($dataMentorUploadfile);
                    }
                }

                // update user answer table
                $dataAssignPaper = array('mentor_paper_attempt_status' => 'yes', 'mentor_paper_attempt_date' => date('Y-m-d H:i:s'));
                $this->getMentorAssignPaperTable()->updateData($paperAssignId, $dataAssignPaper);
                $pageSubmit = 'yes';
                
                // add notification for  test submission
                $getPaperMentorId = $this->getMentorPaperTable()->getMentorNameByPaperId($paperId);
                
                $userObj   = $this->zfcUserAuthentication()->getIdentity();
                $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
                $notifydata = array(
                    'notification_text' => $userObj->getDisplayname().' has completed '.$paperSubject.' test on '.date('d-m-Y').'.',
                    'userid' => $getPaperMentorId,
                    'type_id' => '3',    // mentor
                    'relation_id'=> '0',
                    'notification_url' => 'mypaper',
                    'created_by' => $userObj->getId(),
                    'notification_uuid' => $this->getApiService()->generateUuid(),
                    'created_date'      => date('Y-m-d H:i:s'),    
                    );

                $notificationtable->insertnotification($notifydata);
            }

            return new ViewModel(array(
                'paperQuestion' => $paperQuestion,
                'paperName' => $paperName,
                'paperMarks' => $paperMarks,
                'paperClass' => $paperClass,
                'paperSubject' => $paperSubject,
                'paperAssignId' => $paperAssignId,
                'paperSource' => $paperSource,
                'paperInstruction' => $paperInstruction,
                'pageSubmit' => $pageSubmit,
                'paperTime' => $paperTime
            ));
        }
    }

    public function mentortestevaluateAction() {
//        if(isset($_SESSION['user']['userId']) && $_SESSION['user']['userId']!=""){
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $layout = $this->layout();
            $layout->setTemplate('layout/test');
            $pageSubmit = 'no';
            // getting the paper Id
            $paperAssignId = $this->params()->fromRoute('paperassignId', 0);
            $studentId = $this->params()->fromRoute('studentId', 0);
            $paperList = $this->getMentorAssignPaperTable()->getMentorAssignedPaperList('', '', $paperAssignId);

            foreach ($paperList as $paper) {
                $paperId = $paper->paperId;
                $paperName = $paper->paperName;
                $paperMarks = $paper->paperMarks;
                $paperClass = $paper->paperClass;
                $paperClassId = $paper->paperClassId;
                $paperSubject = $paper->paperSubject;
                $paperSource = $paper->paperSource;
                $paperInstruction = $paper->paperInstruction;
                $paperTime = $paper->paperTime;
            }

            if ($paperSource == 'upload') {
                // getting the paper questions
                $paperQuestion = $this->getMentorPaperTable()->getPaperUserQuestionAnswer($paperId, $paperSource);
            } else {
                $counter = 0;
                // getting the paper questions
                $paperQuestionId = $this->getMentorPaperQuestionTable()->getPaperQuestionAnswer($paperId);

                foreach ($paperQuestionId as $question) {
                    if ($question->mentorPaperQuestionsource == 'self') {
                        $questionDetails = $this->getMentorQuestionTable()->getQuestionDetails($question->questionId);
                        foreach ($questionDetails as $qDetail) {
                            $paperQuestion[$counter]['mentorPaperQuestionId'] = $question->mentorPaperQuestionId;
                            $paperQuestion[$counter]['question'] = $qDetail->mentorQuestion;
                            $paperQuestion[$counter]['questionMarks'] = $qDetail->mentorQuestionMarks;
                            $paperQuestion[$counter]['answer'] = $question->answer;
                            $paperQuestion[$counter]['paperuseransId'] = $question->paperuseransId;
                            $counter++;
                        }
                    } else if ($question->mentorPaperQuestionsource == 'lms') {
                        $questionDetails = $this->getQuestionTable()->fetchAll($question->questionId);
                        foreach ($questionDetails as $qDetail) {
                            /* $paperQuestion[$counter]['question'] = $qDetail->mentorQuestion;
                              $paperQuestion[$counter]['questionMarks'] = $qDetail->mentorQuestionMarks;
                              $counter++; */
                            $paperQuestion[$counter]['mentorPaperQuestionId'] = $question->mentorPaperQuestionId;
                            $paperQuestion[$counter]['question'] = $qDetail->question;
                            $paperQuestion[$counter]['questionMarks'] = $question->mentorPaperQuestionMarks;
                            $paperQuestion[$counter]['answer'] = $question->answer;
                            $paperQuestion[$counter]['paperuseransId'] = $question->paperuseransId;
                            $counter++;
                        }
                    }
                }
            }
            // saving the student answers

            $data = $this->getRequest()->getPost();


            if (count($data) > 0) { //echo '<pre>';print_r($data);die;
                if ($data['source'] == 'add') {
                    foreach ($data['ques'] as $key => $value) {
                        // updating the marks and comments for the answer for questions
                        $paperData = explode('_', $key);
                        $paperUserAnsId = $paperData[1];

                        $dataUserAns = array('mentor_paper_ques_marks' => $value['marks'], 'mentor_paper_question_comment' => $value['comments']);
                        $this->getMentorPaperUserAnswerTable()->updateData($paperUserAnsId, $dataUserAns);
                    }
                }

                // update user assign table
                $dataAssignPaper = array('mentor_paper_evaluate_status' => 'yes', 'mentor_paper_evaluate_date' => date('Y-m-d H:i:s'), 'mentor_paper_evaluated_score' => $data['totalMarks'], 'mentor_paper_evaluated_comment' => $data['mentor_paper_evaluated_comment']);
                $this->getMentorAssignPaperTable()->updateData($paperAssignId, $dataAssignPaper);
                $cacheKey = 'testPerformance_' . $studentId . '_' . $paperClassId;
                
                $this->getServiceLocator()->get('report_container_service')->removeCachedItemByKey($cacheKey);
                $pageSubmit = 'yes';
            }

            return new ViewModel(array(
                'paperQuestionAnswer' => $paperQuestion,
                'paperName' => $paperName,
                'paperMarks' => $paperMarks,
                'paperClass' => $paperClass,
                'paperSubject' => $paperSubject,
                'paperAssignId' => $paperAssignId,
                'paperSource' => $paperSource,
                'paperInstruction' => $paperInstruction,
                'pageSubmit' => $pageSubmit,
                'paperTime' => $paperTime
            ));
        }
    }

    public function testevaluationresultAction() {
//        if(isset($_SESSION['user']['userId']) && $_SESSION['user']['userId']!=""){
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $layout = $this->layout();
            $layout->setTemplate('layout/test');

            // getting the paper Id
            $paperAssignId = $this->params()->fromRoute('paperassignId', 0);
            $paperList = $this->getMentorAssignPaperTable()->getMentorAssignedPaperList('', '', $paperAssignId);

            foreach ($paperList as $paper) {
                $paperId = $paper->paperId;
                $paperName = $paper->paperName;
                $paperMarks = $paper->paperMarks;
                $paperClass = $paper->paperClass;
                $paperSubject = $paper->paperSubject;
                $paperEvaluatedScore = $paper->paperEvaluatedScore;
                $paperEvaluatedComment = $paper->paperEvaluatedComment;
                $paperSource = $paper->paperSource;
                $paperInstruction = $paper->paperInstruction;
                $paperTime = $paper->paperTime;
            }

            /* // getting the user paper questions answer
              $paperQuestionAnswer = $this->getMentorPaperTable()->getPaperUserQuestionAnswer($paperId, $paperSource); */


            if ($paperSource == 'upload') {
                // getting the paper questions
                $paperQuestion = $this->getMentorPaperTable()->getPaperUserQuestionAnswer($paperId, $paperSource);
            } else {
                $counter = 0;
                // getting the paper questions
                $paperQuestionId = $this->getMentorPaperQuestionTable()->getPaperQuestionAnswer($paperId);

                foreach ($paperQuestionId as $question) {
                    if ($question->mentorPaperQuestionsource == 'self') {
                        $questionDetails = $this->getMentorQuestionTable()->getQuestionDetails($question->questionId);
                        foreach ($questionDetails as $qDetail) {
                            $paperQuestion[$counter]['mentorPaperQuestionId'] = $question->mentorPaperQuestionId;
                            $paperQuestion[$counter]['question'] = $qDetail->mentorQuestion;
                            $paperQuestion[$counter]['questionMarks'] = $qDetail->mentorQuestionMarks;
                            $paperQuestion[$counter]['answer'] = $question->answer;
                            $paperQuestion[$counter]['paperuseransId'] = $question->paperuseransId;
                            $paperQuestion[$counter]['paperQuesMarks'] = $question->paperQuesMarks;
                            $paperQuestion[$counter]['paperQuestionComment'] = $question->paperQuesComment;
                            $counter++;
                        }
                    } else if ($question->mentorPaperQuestionsource == 'lms') {
                        $questionDetails = $this->getQuestionTable()->fetchAll($question->questionId);
                        foreach ($questionDetails as $qDetail) {
                            /* $paperQuestion[$counter]['question'] = $qDetail->mentorQuestion;
                              $paperQuestion[$counter]['questionMarks'] = $qDetail->mentorQuestionMarks;
                              $counter++; */
                            $paperQuestion[$counter]['mentorPaperQuestionId'] = $question->mentorPaperQuestionId;
                            $paperQuestion[$counter]['question'] = $qDetail->question;
                            $paperQuestion[$counter]['questionMarks'] = $question->mentorPaperQuestionMarks;
                            $paperQuestion[$counter]['answer'] = $question->answer;
                            $paperQuestion[$counter]['paperuseransId'] = $question->paperuseransId;
                            $paperQuestion[$counter]['paperQuesMarks'] = $question->paperQuesMarks;
                            $paperQuestion[$counter]['paperQuestionComment'] = $question->paperQuesComment;
                            $counter++;
                        }
                    }
                }
            }

            // saving the student answers

            $data = $this->getRequest()->getPost();


            if (count($data) > 0) {
                foreach ($data['ques'] as $key => $value) {
                    // updating the marks and comments for the answer for questions
                    $paperData = explode('_', $key);
                    $paperUserAnsId = $paperData[1];

                    $dataUserAns = array('mentor_paper_ques_marks' => $value['marks'], 'mentor_paper_question_comment' => $value['comments']);
                    $this->getMentorPaperUserAnswerTable()->updateData($paperUserAnsId, $dataUserAns);
                }
                // update user answer table
                $dataAssignPaper = array('mentor_paper_evaluate_status' => 'yes', 'mentor_paper_evaluate_date' => date('Y-m-d H:i:s'), 'mentor_paper_evaluated_score' => $data['mentor_paper_evaluated_comment'], 'mentor_paper_evaluated_comment' => $data['totalMarks']);
                $this->getMentorAssignPaperTable()->updateData($paperAssignId, $dataAssignPaper);
            }

            return new ViewModel(array(
                'paperQuestionAnswer' => $paperQuestion,
                'paperName' => $paperName,
                'paperMarks' => $paperMarks,
                'paperClass' => $paperClass,
                'paperSubject' => $paperSubject,
                'paperAssignId' => $paperAssignId,
                'paperEvaluatedScore' => $paperEvaluatedScore,
                'paperEvaluatedComment' => $paperEvaluatedComment,
                'paperSource' => $paperSource,
                'paperInstruction' => $paperInstruction,
                'paperTime' => $paperTime
            ));
        }
    }

    public function lmsquestionsAction() {
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $service_obj = $this->getServiceLocator()->get('lms_container_service');

            $layout = $this->layout();
            $layout->setTemplate('layout/paper');
            $boardId   = $this->params()->fromRoute('boardId', 0);
            $classId   =  $this->params()->fromRoute('classId', 0);
            $subjectId = $this->params()->fromRoute('subjectId', 0);
            $studentId = $this->params()->fromRoute('studentId', 0);
            $chapters=$service_obj->getChildList($subjectId);
            $resourcemodel   = $this->getServiceLocator()->get("Notification\Model\TTchaterResourceTable");
            
            $subSubject=array();
            foreach($chapters as $key=>$value){
                if($value->getRackType()->getRackTypeId()=='7' ||$value->getRackType()->getRackTypeId()=='8'){
                    $subSubject[]=$value->getRackId();
                }else{
                    $chapterList[]=$value->getRackId();
                }
            }
            if(!empty($subSubject)){
                $subSubject_detail =  $resourcemodel->getsubchecterlist($subSubject); 
                foreach($subSubject_detail as $key=>$subchapters)
                {
                    $chapterList[] = $subchapters->rack_id;
                }
            }
//            foreach($chapters as $key=>$value){
//               $chapterList[]=$value->getRackId();
//           }
           //echo '<pre>'; print_r($array);exit;
            $containerObj = $this->getServiceLocator()->get('lms_container_mapper')->getContainer($classId);
            $parentList = $service_obj->getParentRecursive($containerObj);

            $boardServiceId = $service_obj->getRepoServiceFromContainer($parentList[0]['rack_id'], 'Mentor')->getServiceId();
          //  echo "id===>".$boardServiceId;
            foreach($chapterList as $key=>$value){
                $result[] = $service_obj->getContentList($value, $boardServiceId);
            }
//            echo '<pre>'; print_r($result);
            $questionList = array();
            $contentList['data']=array();
            $i=0;
            
            foreach($result as $key=>$value){
                foreach($value['data'] as $index=>$questionData){
                    $contentList['data'][$i]=$questionData;
                    $i++;
                }
            }
            
            if (count($contentList['data']) > 0) {

                $questionList = $contentList['data'];
                //Only Subjective Type Questions will be fetched
                foreach ($contentList['data'] as $key => $question) {
                    if ($question['questiondata']['questiontype'] == 'Objective')
                        unset($questionList[$key]);
                }
            }
            $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;


            $itemsPerPage = 10;
            $paginator = new Paginator(new paginatorArray($questionList));
            $paginator->setCurrentPageNumber($page)
                    ->setItemCountPerPage($itemsPerPage)
                    ->setPageRange(7);
           // echo '<pre>'; print_r($questionList);exit;
            return new ViewModel(array(
                'questionList' => $questionList,
                'paginator' => $paginator,
                'page' => $page,
                'board_id' => $boardId,
                'class_id' => $classId,
                'subject_id' => $subjectId,
                'studentId'=>$studentId
            ));
        }
    }

    private function getChildList($containerId) {
        
    }

    public function selfquestionsAction() {

        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $layout = $this->layout();
            $layout->setTemplate('layout/paper');

            return new ViewModel(array(
                'source' => 'self',
            ));
        }
    }

    public function uploadquestionsAction() {
//      if(isset($_SESSION['user']['userId']) && $_SESSION['user']['userId']!=""){
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $layout = $this->layout();
            $layout->setTemplate('layout/paper');
            return new ViewModel(array(
                'source' => 'self',
            ));
        }
    }

    public function viewpaperAction() {
//        if(isset($_SESSION['user']['userId']) && $_SESSION['user']['userId']!=""){
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $layout = $this->layout();
            $layout->setTemplate('layout/test');

            // getting the paper Id
            $paperId = $this->params()->fromRoute('paperId', 0);
            $paperList = $this->getMentorAssignPaperTable()->getMentorAssignedPaperList('', '', '', $paperId);
//             echo '<pre>';print_r($paperList);die;
            foreach ($paperList as $paper) {
                $paperId = $paper->paperId;
                $paperName = $paper->paperName;
                $paperMarks = $paper->paperMarks;
                $paperClass = $paper->paperClass;
                $paperSubject = $paper->paperSubject;
                $paperSource = $paper->paperSource;
                $paperInstruction = $paper->paperInstruction;
                $paperTime = $paper->paperTime;
            }
            if ($paperSource == 'upload') {
                // getting the paper questions
                $paperQuestion = $this->getMentorPaperTable()->getPaperQuestion($paperId, $paperSource);
            } else {
                $counter = 0;
                // getting the paper questions
                $paperQuestionId = $this->getMentorPaperQuestionTable()->getPaperQuestionId($paperId);
                foreach ($paperQuestionId as $question) {
                    if ($question->mentorPaperQuestionsource == 'self') {
                        $questionDetails = $this->getMentorQuestionTable()->getQuestionDetails($question->questionId);
                        foreach ($questionDetails as $qDetail) {
                            $paperQuestion[$counter]['question'] = $qDetail->mentorQuestion;
                            $paperQuestion[$counter]['questionMarks'] = $qDetail->mentorQuestionMarks;
                            $counter++;
                        }
                    } else if ($question->mentorPaperQuestionsource == 'lms') {
                        $questionDetails = $this->getQuestionTable()->fetchAll($question->questionId);
                        foreach ($questionDetails as $qDetail) {
                            $paperQuestion[$counter]['question'] = $qDetail->question;
                            $paperQuestion[$counter]['questionMarks'] = $question->mentorPaperQuestionMarks;
                            $counter++;
                        }
                    }
                }
            }
//echo '<pre>';print_r($paperAssignId);echo '</pre>';die('Macro Die');

            return new ViewModel(array(
                'paperQuestion' => $paperQuestion,
                'paperName' => $paperName,
                'paperMarks' => $paperMarks,
                'paperClass' => $paperClass,
                'paperSubject' => $paperSubject,
                'paperAssignId' => $paperId,
                'paperSource' => $paperSource,
                'paperInstruction' => $paperInstruction,
                'paperTime' => $paperTime
            ));
        }
    }

    public function previewpaperAction() {
//       if(isset($_SESSION['user']['userId']) && $_SESSION['user']['userId']!=""){
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $layout = $this->layout();
            $layout->setTemplate('layout/test');

            $data = $this->getRequest()->getPost();
//      echo '<pre>';print_r($data);echo '</pre>';die('Macro Die');
            $x = 0;
            if ($data['papersource'] == 'add') {
                $quesData = array();

                foreach ($data['ques'] as $ques) {
                    $quesData[$x]['questionsource'] = $ques['questionsource'];
                    if ($ques['questionsource'] == 'self') {
                        $quesData[$x]['question'] = $ques['ques'];
                        $quesData[$x]['marks'] = $ques['marks'];
                    } else if ($ques['questionsource'] == 'lms') {
                        // getting LMS Question details
                        $questionList = $this->getQuestionTable()->fetchAll($ques['questionId']);
                        if (count($questionList) > 0) {
                            foreach ($questionList as $qlist) {
                                $quesData[$x]['question'] = $qlist->question;
                            }
                        }

                        $quesData[$x]['marks'] = $ques['marks'];
                    }

                    $x++;
                }
            }


            return new ViewModel(array(
                'paperQuestion' => $quesData,
                'paperName' => $data['mentor_paper_name'],
                'paperMarks' => $data['mentor_paper_marks'],
                'paperTime' => $data['mentor_paper_hour'] . ':' . $data['mentor_paper_minute'],
                'paperClass' => $data['className'],
                'paperSubject' => $data['subjectName'],
                'paperInstruction' => $data['mentor_paper_instruction'],
                'paperSource' => $data['papersource'],
            ));
        }
    }

    /* Modify by:Mohit
     * Modify data: 9-sep-14
     * Data: Download file with output file function, define in account 's model.     *
     */

    public function downloadquestionAction() {
//      if(isset($_SESSION['user']['userId']) && $_SESSION['user']['userId']!=""){
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $file = $this->params()->fromRoute('filename', 0);
            //chdir('live_site/public/mentorquestionfiles/');
            $filename = 'public/uploads/mentorquestionfiles/' . $file;
            //$filename = "http://10.1.9.99/uploads/mentorquestionfiles/".$file;
            //$response = new \Zend\Http\Response\Stream();
            //$response->setStream(fopen($filename, 'r'));
            //$response->setStatusCode(200);

            $uploaddownload = $this->getServiceLocator()->get('Assessment\Model\TuploadsdownloadsTable');
//            echo '<pre>';print_r($file);echo '</pre>';die('Macro Die');
            $uploaddownload->output_file($filename, $file, '');
        }
    }

    public function checkpapernameAction() {

        $classId = $this->params()->fromRoute('classId', 0);
        $subjectId = $this->params()->fromRoute('subjectId', 0);
        $paperName = $this->params()->fromRoute('paperName', 0);
        $mentorPaperList = $this->getMentorAssignPaperTable()->getMentorPaper($_SESSION['user']['userId'], $classId, $subjectId, $paperName);
        echo $mentorPaperList;
        die;
    }

    public function previewstudentattemptAction() {
        $paperAssignId = $this->params()->fromRoute('paperAssignId', 0);
//         if(isset($_SESSION['user']['userId']) && $_SESSION['user']['userId']!=""){
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $layout = $this->layout();
            $layout->setTemplate('layout/test');

            $data = $this->getRequest()->getPost();
            $paperAnswer = array();
            $answerCounter = 0;

            foreach ($data['ques'] as $key => $answer) {
                $keyArray = explode('_', $key);
                $paperAnswer[$keyArray[1]] = $answer;
            }

            $paperList = $this->getMentorAssignPaperTable()->getMentorAssignedPaperList('', '', $paperAssignId);

            foreach ($paperList as $paper) {
                $paperId = $paper->paperId;
                $paperName = $paper->paperName;
                $paperMarks = $paper->paperMarks;
                $paperClass = $paper->paperClass;
                $paperSubject = $paper->paperSubject;
                $paperSource = $paper->paperSource;
                $paperInstruction = $paper->paperInstruction;
                $paperTime = $paper->paperTime;
            }

            if ($paperSource == 'upload') {
                // getting the paper questions
                $paperQuestion = $this->getMentorPaperTable()->getPaperQuestion($paperId, $paperSource);
            } else {
                $counter = 0;
                // getting the paper questions
                $paperQuestionId = $this->getMentorPaperQuestionTable()->getPaperQuestionId($paperId);
                foreach ($paperQuestionId as $question) {

                    if ($question->mentorPaperQuestionsource == 'self') {
                        $questionDetails = $this->getMentorQuestionTable()->getQuestionDetails($question->questionId);
                        foreach ($questionDetails as $qDetail) {
                            $paperQuestion[$counter]['mentorPaperQuestionId'] = $question->mentorPaperQuestionId;
                            $paperQuestion[$counter]['question'] = $qDetail->mentorQuestion;
                            $paperQuestion[$counter]['questionMarks'] = $qDetail->mentorQuestionMarks;
                            $counter++;
                        }
                    } else if ($question->mentorPaperQuestionsource == 'lms') {
                        $questionDetails = $this->getQuestionTable()->fetchAll($question->questionId);
                        foreach ($questionDetails as $qDetail) {
                            $paperQuestion[$counter]['mentorPaperQuestionId'] = $question->mentorPaperQuestionId;
                            $paperQuestion[$counter]['question'] = $qDetail->question;
                            $paperQuestion[$counter]['questionMarks'] = $question->mentorPaperQuestionMarks;
                            $counter++;
                        }
                    }
                }
            }
            return new ViewModel(array(
                'paperQuestion' => $paperQuestion,
                'paperName' => $paperName,
                'paperMarks' => $paperMarks,
                'paperClass' => $paperClass,
                'paperSubject' => $paperSubject,
                'paperAssignId' => $paperAssignId,
                'paperSource' => $paperSource,
                'paperInstruction' => $paperInstruction,
                'paperTime' => $paperTime,
                'paperAnswer' => $paperAnswer
            ));
        }
    }

    /* for mentor questions answer */

    public function getquestionanswerAction() {

        $question_id = $this->params()->fromRoute('question_id', 0);
//        $answerObj = $this->getServiceLocator()->get('lms_container_service')->getAnswerForQues($question_id);
//        echo json_encode($answerObj->getAnswer());
//        exit;

        $questionAnswer = $this->getAnswerTable()->getquestionAnswer($question_id);

        $viewModel = new ViewModel();
        $viewModel->setVariables(array(
                    'questionAnswer' => $questionAnswer
                ))
                ->setTerminal(true);
        return $viewModel;
    }

    public function checkmentorAction() {
//        echo '<pre>';print_r($_POST);echo '</pre>';die('Macro Die');
        if (isset($_POST)) {
            $mentor = '';
            $subjectsDropDown = '';
            $mentorMobile = array();
//            echo '<pre>';print_r($_POST);echo '</pre>';die('Macro Die');
//            $table = $this->getServiceLocator()->get('ZfcUser\Model\TuserTable');
            if (isset($_POST['mentorId']) && $_POST['mentorId'] != '') {
                // This function check the mentor exists or not with posted mentor id and name and get the subjects of particular mentor.
                $mentorDetails = $table->checkMentor($_POST['mentorId'], $_POST['mentorName']);
                if (count($mentorDetails) != 0) {
                    foreach ($mentorDetails as $mentors) {
                        if ($mentors->board_class_subject_id != '') {
                            $subjectsDropDown = '<option value="' . $mentors->board_class_subject_id . '">' . $mentors->subject_name . '</option>';
                        }
                        $mentorEmail = $mentors->email_id;
                        if ($mentors->mobile != '') {
                            $mentorMobile1 = $mentors->mobile;
                            $mentorMobile = explode('-', $mentorMobile1);
                            $mentorMobile0 = $mentorMobile[0];
                            $mentorMobile1 = $mentorMobile[1];
                        } else {
                            $mentorMobile0 = '';
                            $mentorMobile1 = '';
                        }
                        $mentorType = $mentors->name;
                        $typeId = $mentors->user_type_id;
                    }
                    $result = new JsonModel(array(
                        'output' => 'success',
                        'mentorEmail' => $mentorEmail,
                        'mentorMobile1' => $mentorMobile0,
                        'mentorMobile' => $mentorMobile1,
                        'mentorType' => $mentorType,
                        'typeId' => $typeId,
                        'smubjectsDropDown' => $subjectsDropDown,
                    ));
                } else {
                    $result = json_encode(array('output' => 'notSuccess'));
//                    $result = new JsonModel(array(
//                        'output' => 'notSuccess',
//                    ));
                }
            } else {
//                json
                $result = json_encode(array('output' => 'notSuccess'));
//                $result = new JsonModel(array(
//                    'output' => 'notSuccess',
//                ));
            }
            echo $result;
            exit;
        }
    }

    public function addStudentDetailsAction() {

        global $mentorregSubject;
        global $mentorregMessage;
        global $activementorsubject;
        global $activementorMessage;
        $user          = array();
        $newSubjectIds = array();
        $userDetails   = array();
        $addedMentor   = array();
        $user_details  = array();
        $user_info     = array();
        //$mentor_add_details = $this->getServiceLocator()->get('Assessment\Model\TmentordetailsTable');
        $event = $this->getEvent();
        $requestURL = $event->getRequest();
        $router = $event->getRouter();
        $uri = $router->getRequestUri();
        $baseUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
        $mentor_add_details = $this->getServiceLocator()->get('Assessment\Model\TmentordetailsFactory');
      $Checkarray  =array("Mentor_Email"=>$_POST['Mentor_Email'],"Mentor_Name"=>$_POST['Mentor_Name']);   
      $postArray   = $this->escaper($Checkarray);
      $validemail  = $this->validateEmail($_POST['Mentor_Email']);
      if($validemail == "Valid") {
            $table   = $this->getServiceLocator()->get('Assessment\Model\UserTable');
            $records = $table->checkEmail($_POST['Mentor_Email']);            
            if($records->count() > 0) {
                $userObj   = $this->zfcUserAuthentication()->getIdentity();
                $studentOb = $records->current();
                $loggedUser = $userObj->getEmail();
                if(trim($loggedUser) == trim($_POST['Mentor_Email'])) {
                    echo json_encode(array('output' => 'self'));
                    exit;
                }
                $table     = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
                $count     = 0;
                foreach ($this->escaper($_POST['mentorSubject']) as $subjectId) {
                    // This function check the mentor and student relation ship between loged student and selected mentor with different subjects.
                    $checkRelation = $table->checkRelation($userObj->getId(), $studentOb->user_id, $subjectId);
                    if ($checkRelation == 0) {
                        $newSubjectIds[$count] = $subjectId;
                        $count++;
                    }
                }
               $subject_detail='';
               $service_obj = $this->getServiceLocator()->get('lms_container_service');
                if (isset($newSubjectIds) && count($newSubjectIds) != 0) {
                    $username = ucfirst($studentOb->firstName);
                    foreach ($newSubjectIds as $subjectId) {
                        $allSubjectParents = $service_obj->getParentList($subjectId);
                        foreach($allSubjectParents as $parent) { 
                        }
                        $subject_detail .= $parent['rack_name'].',';
                        $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                        $customboardId = $comMapperObj->getcustomboardPrimaryId($_POST['boardId'],$subjectId);
                        // This function add the mentor and student relation ship between loged student and selected mentor with different subjects.
                        $addRelation = $table->addRelation($studentOb->user_id, $subjectId, 'student', $userObj,NULL,$customboardId);

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
                    // add notification for  mentor request
                    $notifydata = array(
                        'notification_text' => $userObj->getDisplayname().'&nbsp; sent Request to be your mentor&nbsp;',
                        'userid' => $studentOb->user_id,
                        'type_id' => '3',    // mentor
                        'relation_id'=> $addRelation,
                        'notification_url' => 'mymentor',
                        'created_by' => $userObj->getId(),
                        'notification_uuid' => $this->getApiService()->generateUuid(),
                        'created_date'      => date('Y-m-d H:i:s'),    
                        );
             
                    $notificationtable->insertnotification($notifydata);
                  }
                  
                    $to = $studentOb->emailId;
                    // This function get the loged user details
                    $table = $this->getServiceLocator()->get('Assessment\Model\TuserTable');
                    //$user_details = $table->getuserdetails('',$userObj->getId());
                    //$user_details = $table->getuserdetailsById($userObj->getId());
                    $user_details = $table->getuserdetailsById($studentOb->user_id);
                    
                    $user_info = $user_details->current();
                    
                    $studentname = ucfirst($user_info->firstName);
                    $boardname = $user_info->board_name;
                    $classname = $user_info->class_name;
                    $schoolname = $user_info->school_name;
                    $emailaddress = $user_info->emailId;
                    //echo '<pre>'; print_r($user_info); exit;
                    if(!empty($_POST['classId'])) {
                        $classId = $_POST['classId'];
                    } else {
                        $classId = $user_info->classId;
                    }
                    // This function check the selected user mentor or not based on particular board class and subject.
                    $checkMentor = $mentor_add_details->checkMentor($userObj->getId(), $userObj->getBoardId(),$userObj->getClassId(), $subjectId);
                    $lastsubjectId=0;
                    if (count($checkMentor->buffer()) == 0) {
                        foreach ($newSubjectIds as $subjectId) {
                            // This function add the mentor details with different subjects.
                            $mentor_details = $mentor_add_details->addMentorDetails($userObj->getId(), $userObj->getBoardId(), $userObj->getClassId(), $subjectId, $place = 'from_student');
                        }
                    }
                    if ($user_info->gender == 'Male') {
                        $typeofUser = 'his';
                    } else if ($user_info->gender == 'Female') {
                        $typeofUser = 'her';
                    } else {
                        $typeofUser = 'his/her';
                    }
                    if(!empty($subjectId)) {
                        $board_name = '';
                        $class_name = '';
                        $service_obj = $this->getServiceLocator()->get('lms_container_service');
                        $allSubjectParents = $service_obj->getParentList($subjectId);
                        foreach($allSubjectParents as $parent) {
                            //echo '<pre>'; print_r($parent); exit;
                            $board_name = $parent['rack_name'];
                            if(isset($parent['class'])) {
                                foreach($parent['class'] as $parentclass) {
                                    //echo $parentclass['rack_id'].' == '.$classId.'<br>';
                                    if($parentclass['rack_id']==$classId) {
                                        $class_name = $parentclass['rack_name'];    
                                    }
                                }
                            }
                            if(empty($classname)) {
                                $classname = !empty($class_name)?$class_name:'N/A';
                            }
                            if(empty($boardname)) {
                                $boardname = !empty($board_name)?$board_name:'N/A';
                            }
                        }
                    }
                    $filepath= __DIR__ . '../../../../view/mailer/';
                    $filepath = $filepath.'addexternalstudentsubjects.html';
                    $activementorMessage = file_get_contents($filepath);
                    $mentorName = $userObj->getDisplayname();
                    
                    $activementorMessage = str_replace("<FULLNAME>", "$mentorName", $activementorMessage);
                    //$activementorMessage = str_replace("<ACTIVATIONLINK>", $baseUrl, $activementorMessage);
                    $activementorMessage = str_replace("<TYPE>", "$typeofUser", $activementorMessage);
                    $activementorMessage = str_replace("<EMILADDRESS>", "$emailaddress", $activementorMessage);
                    $activementorMessage = str_replace("<STUDENTNAME>", "$studentname", $activementorMessage);
                    $activementorMessage = str_replace("<BOARDNAME>", "$boardname", $activementorMessage);
                    $activementorMessage = str_replace("<CLASSNAME>", "$classname", $activementorMessage);
                    $activementorMessage = str_replace("<SCHOOLNAME>", "$schoolname", $activementorMessage);
                    $activementorMessage = str_replace("{SITE_URL}", $baseUrl, $activementorMessage);
                    $activementorMessage = str_replace("{BASE_URL}", $baseUrl, $activementorMessage);
                    //echo $activementorMessage; exit;
                    $table = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
                    $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent');
                    $activementorsubject='Add Student Request';
                    $emailData = array("email_id" => $to, 'subject' => $activementorsubject, 'message' => $activementorMessage, 'mail_type' => 'useractivities', 'status' => 1);
                    if ($mailContentTable->addEmailContent($emailData)) {
                        /*$result = new JsonModel(array(
                            'output' => '1',
                        ));*/
                    }
                    $subjectDetail = trim($subject_detail,",");
                    $config=$this->getServiceLocator()->get('config');
                    $defaultstates = isset($config['msg_engine'])?$config['msg_engine']:'';
                    if(isset($defaultstates['status']) && $defaultstates['status'] == 'ON') {
                        $tableuserother = $this->getServiceLocator()->get('Assessment\Model\UserOtherDetailsTable');
                        $userRegOtherDetails = $tableuserother->getUserOtherDetailsByKey($userObj->getId() , 'register_by', Null);
                        foreach($userRegOtherDetails as $otherdetail) {
                            $register_by = $otherdetail->value;
                        }
                        if($register_by=='mobile') {
                                $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                                $msglog  = $this->getServiceLocator()->get('Package\Model\TmsgLogTable');
                                $msgTxt="$mentorName has sent you an invitation to become a Student in the ($subjectDetail) on Extramarks Smart Study Program for ($boardname)-($classname)
                        To accept the invitation click <a href='www.extramarks.com'>here</a>";
                                $studentMobile = $user_info->mobile;
                                $mobile     = explode("-", $studentMobile);
                                $mob_number = $mobile[1];
                                if($mobile[1]) {
                                    $smsArr = array('to_mobile_number'=>$mob_number,
                                        'msg_txt' => $msgTxt,
                                        'user_id' => $userObj->getId(),
                                        'mobile_number' => $user_info->mobile,
                                        'sms_type' => 'add student request'
                                    );
                                $data = $comMapperObj->smssendprocess($smsArr);
                                $result = $msglog->addlog($data);
                           }
                        }
                    }
                    
                    echo json_encode(array('output' => 1));
                    exit;
               } else {
                    echo json_encode(array('output' => 0));
                    exit;
                }
                return $result;
            } else {
                // invite learner functionality
                $invitationFromId = $this->zfcUserAuthentication()->getIdentity()->getId();
                $loginDisplayName = $this->zfcUserAuthentication()->getIdentity()->getDisplayName();
                $tableExternalEmail = $this->getServiceLocator()->get('Assessment\Model\InviteexternalemailTable');
                $alreadyInvited = $tableExternalEmail->checkDuplicateInvite($invitationFromId,$_POST['Mentor_Email'],1);
                
                if($alreadyInvited == '0'){
                    $userObj   = $this->zfcUserAuthentication()->getIdentity();
                    foreach ($_POST['mentorSubject'] as $subjectId) {
                        // This function add the mentor details with different subjects.
                        $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                        $getcustomboard = $comMapperObj->getcustomboardPrimaryId($_POST['boardId'],$_POST['classId']);
                        $boardIdArray = explode("_",$_POST['boardId']);
                        $boardRackIds=$this->getService()->getTaggedBoardList($boardIdArray[0]);
                        $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                        $newBoardId = $comMapperObj->getContainerRackId($boardRackIds,$_POST['classId']);
                        $mentor_details = $mentor_add_details->addMentorDetails($userObj->getId(), $userObj->getBoardId(), $userObj->getClassId(), $subjectId, $place = 'from_student');
                        $user = $tableExternalEmail->inviteRelation($invitationFromId,$_POST['Mentor_Email'],1,$newBoardId,$_POST['classId'],$subjectId,$getcustomboard);
                    }
                    

                    if ($user != "") {
                        $filepath= __DIR__ . '../../../../../../vendor/zf-commons/zfc-user/view/mailer/';
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
                        $emailData = array("email_id" => $_POST['Mentor_Email'], 'subject' => $regSubject, 'message' => $regMessage, 'mail_type' => 'useractivities', 'status' => 1);

                        $mailContentTable->addEmailContent($emailData);

                        $redirect_url = '';
                        $_SESSION['redirecturl'] = $_SERVER['HTTP_REFERER'];
                        if (isset($_SESSION['redirecturl']) && $_SESSION['redirecturl'] != '') {
                            $redirect_url = $_SESSION['redirecturl'];
                        }
                        echo json_encode(array(
                            'output' => 'successInvitation',
                            'message' => 'Learner has been invited successfully',
                            'redirect_url' => $redirect_url
                        ));
                        exit;
                    }else {
                        echo json_encode(array(
                            'output' => 'failed',
                        ));
                        exit;
                    }
                }else{
                    echo json_encode(array(
                        'output' => 'duplicateinvite',
                    ));
                    exit;
                }
            
            }
        }else{
            
            echo json_encode(array('output' => 'notexist'));
            exit;
        }
    }
    public function addSubjectForInvitedLearnerAction() {
        $student_mentor_id = $_POST['student_mentor_id'];
        $table     = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
        $student_id = $table->getstudentId($student_mentor_id);
        
        $newSubjectIds = array();
        
        $mentor_add_details = $this->getServiceLocator()->get('Assessment\Model\TmentordetailsFactory');
        
        $userObj   = $this->zfcUserAuthentication()->getIdentity();
        
        $count     = 0;
        foreach ($this->escaper($_POST['mentorSubject']) as $subjectId) {
            // This function check the mentor and student relation ship between loged student and selected mentor with different subjects.
            $checkRelation = $table->checkRelation($userObj->getId(), $student_id, $subjectId);
            if ($checkRelation == 0) {
                $newSubjectIds[$count] = $subjectId;
                $count++;
            }
        }

        if (isset($newSubjectIds) && count($newSubjectIds) != 0) {
            foreach ($newSubjectIds as $subjectId) {
                // This function add the mentor and student relation ship between loged student and selected mentor with different subjects.
                $table->addInviteLearnerRelation($student_id, $subjectId,$userObj);

          }
            // This function check the selected user mentor or not based on particular board class and subject.
            $checkMentor = $mentor_add_details->checkMentor($userObj->getId(), $userObj->getBoardId(),$userObj->getClassId(), $subjectId);
            $lastsubjectId=0;
            if (count($checkMentor->buffer()) == 0) {
                foreach ($newSubjectIds as $subjectId) {
                    $mentor_details = $mentor_add_details->addMentorDetails($userObj->getId(), $userObj->getBoardId(), $userObj->getClassId(), $subjectId, $place = 'from_student');
                }
            }

            echo json_encode(array('output' => 1));
            exit;
       } else {
            echo json_encode(array('output' => 0));
            exit;
        }
        return $result;
            
        
    }

    public function getSubjectlistAction() {
        $request = $this->getRequest();
        $post = $request->getPost();
        $classId = $post->classid;
        $subjecttype = '';

        $allsubsubjectdetails = array();
        if (isset($post->subjecttype)) {
            $subjecttype = $post->subjecttype;
        }

        $subjectIds = '';
        $serviceObj = $this->getServiceLocator()->get('lms_container_service');
        $userObj = $this->zfcUserAuthentication()->getIdentity();
        if ($subjecttype == 'subscribed') {

            $packagetable = $this->getServiceLocator()->get('Assessment\Model\TuserpackageTable');
            $userpackagessubjects = $packagetable->getPackagesubjects($userObj->getId());
//             echo '<pre>';print_r($userpackagessubjects->count());die;           
            if ($userpackagessubjects->count()) {
                foreach ($userpackagessubjects as $userpackagessubject) {
                    $explode = explode(',', $userpackagessubject->syllabus_id);
                    foreach ($explode as $val) {
//                             if($val)
                        $subjectIds .= "" . $val . "" . ",";
                        //$usersubjects[]=$val;
                    }
                }
            }
        }

        $allsubjectdetails = array();
        $subjectDeatils = array();
        if ($subjectIds != '') {
            $subject_id_array = explode(',', trim($subjectIds, ','));
            $allsubsubjectdetails = $serviceObj->getContainerList($subject_id_array);
        }

        if ($classId != 0 && $subjecttype == 'all')
            $allsubsubjectdetails = $serviceObj->getChildList($classId);



        $output = '';
//        $suboutput ='';
        if (count($allsubsubjectdetails) > 0) {

            foreach ($allsubsubjectdetails as $subjectdetail) {
                if ($subjectdetail->getParent()->getRackId() == $classId)
                    $output.='<option value="' . $subjectdetail->getRackId() . '">' . $subjectdetail->getRackName()->getName() . '</option>';
            }
        }
        if ($output == '') {
            if ($subjecttype == 'subscribed') {
                $output.='<option value="">No Subscribed Subject</option>';
            } else {
                $output .='<option value="">No Subject</option>';
            }
        }

        echo $output;
        exit;
    }
    
    public function getSubjectListByClassAction() {
        $request = $this->getRequest();
        $post = $request->getPost();
        $classId = $post->classid;

        $allsubsubjectdetails = array();

        $serviceObj = $this->getServiceLocator()->get('lms_container_service');

        if ($classId != 0){
            $allsubsubjectdetails = $serviceObj->getChildList($classId);
        }
        $result=array();
        if (count($allsubsubjectdetails) > 0) {

            foreach ($allsubsubjectdetails as $subjectdetail) {
                if ($subjectdetail->getParent()->getRackId() == $classId)
                    $result[$subjectdetail->getRackId()]=$subjectdetail->getRackName()->getName();
            }
            
            echo json_encode(array('output'=>1,'subjectlist'=>$result));
            exit;
        }
        
        echo json_encode(array('output' => 0));
        exit;
    }
    
    
 public function addMentorDetailsAction()
  { 
   $baseUrls   = $this->getServiceLocator()->get('config');
   $serviceObj = $this->getServiceLocator()->get('lms_container_service');
   $baseUrlArr = @$baseUrls['urls'];
   $baseUrl    = @$baseUrlArr['baseUrl'];
   
   $user          = array();
   $newSubjectIds = array();
   $userDetails   = array();
   $addedMentor   = array();
   $user_details  = array();
   $user_info     = array();
   $userObj       = $this->zfcUserAuthentication()->getIdentity();
   
   $postArr    = array('Mentor_Email'=>$_POST['Mentor_Email'],'Mentor_Name'=>$_POST['Mentor_Name']);  
   $mentorPost = $this->escaper($postArr);
   $validemail = $this->validateEmail($_POST['Mentor_Email']);
   $service_obj = $this->getServiceLocator()->get('lms_container_service');
   if($validemail == 'Valid'){
   if(isset($_POST['Mentor_Email'])) {
      $table   = $this->getServiceLocator()->get('Assessment\Model\UserTable');
      $records = $table->checkEmail($_POST['Mentor_Email']);
      //echo '<pre>'; print_r($_POST); exit;
      $firstSubjectId = $_POST['mentorSubject'][0];
      
      
      //  echo '<pre>'; print_r($_POST); exit;
      if ($records->count() > 0) 
      {
        $userObj   = $this->zfcUserAuthentication()->getIdentity();
        $currentstudentOb = $records->current();
        $loggedUser = $userObj->getEmail();
        
        if(trim($loggedUser) == trim($_POST['Mentor_Email'])) {
            echo json_encode(array('output' => 'self'));
            exit;
        }
         $lastsubjectId = 0;
         $mentorOb = $records->current();
         $table = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
         $count = 0;
         
            foreach ($this->escaper($_POST['mentorSubject']) as $subjectId) {
                $lastsubjectId = $subjectId;
                //This function check the mentor and student relation ship between loged student and selected mentor with different subjects.
                $checkRelation = $table->checkRelation($mentorOb->user_id, $userObj->getId(), $subjectId);
                
                if($checkRelation == 0) {
                    $newSubjectIds[$count] = $subjectId;
                    $count++;
                }
            }
                $subject_detail = '';
                if (count($newSubjectIds) != 0) {
                    foreach ($newSubjectIds as $subjectId) {
                        $allSubjectParents = $service_obj->getParentList($subjectId);
                        foreach($allSubjectParents as $parent) { 
                        }
                        $subject_detail .= $parent['rack_name'].',';
                        $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                        $customboardId = $comMapperObj->getcustomboardPrimaryId($_POST['BoardId'],$subjectId);
                        
                        $addRelation = $table->addRelation($mentorOb->user_id, $subjectId, 'mentor', $userObj,NULL,$customboardId);
                        // add notification
                      $relationId[] = $addRelation;
                        // if notification pre for current relation Id delete it
                   $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
                   $notification = $notificationtable->getnotification('',$addRelation);
                   if(count($notification)>0){
                      foreach($notification as $notify){
                       $data = array(
                           'notification_status' => '2',
                           'notification_uuid' => $this->getApiService()->generateUuid(),
                           'modified_date' => date('Y-m-d h:i:s'),
                           'modified_by' => $mentorOb->user_id,
                        );
                       $notificationtable->updateStatus($notify->notification_id,$data);
                      }
                   }                    
                // add notification for  mentor request
                  $notifydata = array(
                    'notification_text' => $userObj->getDisplayname().'&nbsp; sent Request for mentor&nbsp;',
                    'userid' => $mentorOb->user_id,
                    'type_id' => '3',    // mentor
                     'relation_id'=> $addRelation,
                      'notification_uuid' => $this->getApiService()->generateUuid(),
                     'notification_url' => 'my-students',
                     'created_by' => $userObj->getId(),
                      'created_date'      => date('Y-m-d H:i:s'),    
                  );             
                        $notificationtable->insertnotification($notifydata);                        
                    }
                    
                    $to       = $mentorOb->emailId;
                    $username = $mentorOb->firstName; 
                    $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
             //        This function get the loged user details
                    $user_details  = $table->getuserdetails($userObj->getId());
                    $user_info     = $user_details->current();
                    $studentname   = ucfirst($user_info->firstName);
                    $boardname     = trim($user_info->board_name);
                    $classname     = trim($user_info->class_name);
                    $schoolname    = $user_info->school_name?$user_info->school_name:'N/A';
                    $emailaddress  = $user_info->emailId;
                    if(empty($_POST['classId'])) {
                        $classId = $user_info->classId;
                    } else {
                        $classId = $_POST['classId'];
                    }
                    $boardId = $user_info->boardId;
                    
                    if(empty($classname)) {
                        $classname = !empty($classname)?$classname:'N/A';
                    }
                    if(empty($boardname)) {
                        $boardname = !empty($boardname)?$boardname:'N/A';
                    }                    
                    //$classdetails = $serviceObj->getChildList($_POST['BoardId']);
                    $subjectdetails = $serviceObj->getParentList($firstSubjectId);
                    $boardName = '';
                    $className = '';
                      foreach ($subjectdetails as $key => $subjectArr) {
                          $board_name = $subjectArr['rack_name'];
                          if($boardName ==''){
                              $boardName = $board_name;
                          }
                          if(isset($subjectArr['class'])) {
                              foreach($subjectArr['class'] as $classArr) {
                                  if($classArr['rack_id']==$_POST['classId']){
                                      if($className=='') {
                                          $className = $classArr['rack_name'];
                                      }
                                  }
                              }
                          }
                      }
                      //echo $boardName.' '.$className; exit;
                    $mentor_add_details = $this->getServiceLocator()->get('Assessment\Model\TmentordetailsFactory');
                    // This function check the selected user mentor or not based on particular board class and subject.
                    $checkMentor = $mentor_add_details->checkMentor($userObj->getId(), $userObj->getBoardId(),$userObj->getClassId(), $subjectId);
                    if (count($checkMentor->buffer()) == 0) {
                        foreach ($newSubjectIds as $subjectId) {
                            // This function add the mentor details with different subjects.
                            $mentor_details = $mentor_add_details->addMentorDetails($mentorOb->user_id, $userObj->getBoardId(), $userObj->getClassId(), $subjectId, $place = 'from_student');
                        }
                    }
                    
                    if ($userObj->getGender() == 'Male') {
                        $typeofUser = 'his';
                    } else if ($userObj->getGender() == 'Female') {
                        $typeofUser = 'her';
                    } else {
                        $typeofUser = 'his/her';
                    }
                    $event = $this->getEvent();
                    $requestURL = $event->getRequest();
                    $router = $event->getRouter();
                    $uri = $router->getRequestUri();
                    $siteUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
                    
                    $filepath= __DIR__ . '../../../../view/mailer/';
                    $filepath = $filepath.'addexternalmentorsubject.html';
                    $activementorMessage = file_get_contents($filepath);
                    $registerUrl = $siteUrl.'/user/register';
                    $activementorMessage = str_replace("[FULLNAME]", "$username", $activementorMessage);
                    $activementorMessage = str_replace("[SUBJECTDETAIL]", trim($subject_detail,","), $activementorMessage);
                    $activementorMessage = str_replace("[REGISTRATIONLINK]", $registerUrl, $activementorMessage);
                    $activementorMessage = str_replace("[TYPE]", "$typeofUser", $activementorMessage);
                    $activementorMessage = str_replace("[EMILADDRESS]", "$emailaddress", $activementorMessage);
                    $activementorMessage = str_replace("[STUDENTNAME]", "$studentname", $activementorMessage);
                    $activementorMessage = str_replace("[BOARDNAME]", "$boardName", $activementorMessage);
                    $activementorMessage = str_replace("[CLASSNAME]", "$className", $activementorMessage);
                    //$activementorMessage = str_replace("[SCHOOLNAME]", "$schoolname", $activementorMessage);
                    $activementorMessage = str_replace("{SITE_URL}", "$siteUrl", $activementorMessage);
                    $activementorMessage = str_replace("{BASE_URL}", "$siteUrl", $activementorMessage);
                    $mailContentTable    = $this->getServiceLocator()->get('Package\Model\TmailContent');
                    $activementorsubject = 'Add Mentor Request';
                    $emailData = array("email_id" => $to, 'subject' => $activementorsubject, 'message' => $activementorMessage, 'mail_type' => 'useractivities', 'status' => 1);

                    if ($mailContentTable->addEmailContent($emailData)) {
                        
                    }
                    $subjectDetail = trim($subject_detail,",");
                    $config=$this->getServiceLocator()->get('config');
                    $defaultstates = isset($config['msg_engine'])?$config['msg_engine']:'';
                    if(isset($defaultstates['status']) && $defaultstates['status'] == 'ON') {
                        $tableuserother = $this->getServiceLocator()->get('Assessment\Model\UserOtherDetailsTable');
                        $userRegOtherDetails = $tableuserother->getUserOtherDetailsByKey($userObj->getId() , 'register_by', Null);
                        foreach($userRegOtherDetails as $otherdetail) {
                            $register_by = $otherdetail->value;
                        }
                        if($register_by=='mobile') {
                                $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                                $msglog  = $this->getServiceLocator()->get('Package\Model\TmsgLogTable');
                                $msgTxt="$username has sent you an invitation to become a Mentor in the ($subjectDetail) on Extramarks Smart Study Program for ($boardName)-($className)
                        To accept the invitation click <a href='www.extramarks.com'>here</a>";
                                $studentMobile = $user_info->mobile;
                                $mobile     = explode("-", $studentMobile);
                                $mob_number = $mobile[1];
                                if($mobile[1]) {
                                    $smsArr = array('to_mobile_number'=>$mob_number,
                                        'msg_txt' => $msgTxt,
                                        'user_id' => $userObj->getId(),
                                        'mobile_number' => $user_info->mobile,
                                        'sms_type' => 'add mentor request'
                                    );
                                $data = $comMapperObj->smssendprocess($smsArr);
                                $result = $msglog->addlog($data);
                           }
                        }
                    }
                    
                    $result = array('output' => '1','response_type'=>'success');
                    echo json_encode($result);
                    exit;
                } else {
                    $result = array('output' => 0);
                }
                echo json_encode($result);
                    exit;
            } else {
                
                $boardId=$_POST['BoardId'];
                $classID=$_POST['classId'];
                $mentorName=$_POST['Mentor_Name'];
                $count = 0;
                //$userEmail = explode("@", $mentorPost['Mentor_Email']);
                $to = $_POST['Mentor_Email'];
                // if user id not exist send registration mail to the user

                $Invitetable = $this->getServiceLocator()->get('Assessment\Model\InviteexternalemailTable');
                // check if request has been send to user

                foreach ($this->escaper($_POST['mentorSubject']) as $subjectId) {
                    $requestlistCount = $Invitetable->checkDuplicateInvite($userObj->getId(),$_POST['Mentor_Email'],3,$subjectId);
                    if ($requestlistCount == 0) {
                        $newSubjectIds[$count] = $subjectId;
                        $count++;
                    }
                }
                
                if ($count == 0) {
                    $result = array('output' => 0 ,'response_type'=>'requestalreadysent');
                        echo json_encode($result);
                        exit;
                } else {
                    
                    $table = $this->getServiceLocator()->get('Assessment\Model\TuserTable');
                    //This function get the loged user details
                    $user_details = $table->getprofilebyid($userObj->getId());
                    $user_info = $user_details->current();
                    
                    $studentname = ucfirst($user_info->firstName);
                    $boardname = $user_info->board_name;
                    $classname = $user_info->class_name;
                    $schoolname = $user_info->school_name;
                    $emailaddress = $user_info->emailId;
                    $usertype = $user_info->user_type_id;
                    $userprofile = $baseUrl . "/myprofile/?id=" . $userObj->getId();
                        if ($user_info->gender == 'Male') {
                            $typeofUser = 'his';
                        } else if ($user_info->gender == 'Female') {
                            $typeofUser = 'her';
                        } else {
                            $typeofUser = 'his/her';
                        }
                    // entry in temp group table ;
                    if (count($newSubjectIds) != 0) {
                        foreach ($newSubjectIds as $subjectId) {
                            $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                            $getcustomboard = $comMapperObj->getcustomboardPrimaryId($boardId,$classID);
                            $boardIdArray = explode("_",$boardId);
                            $boardRackIds=$this->getService()->getTaggedBoardList($boardIdArray[0]);
                            $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                            $newBoardId = $comMapperObj->getContainerRackId($boardRackIds,$classID);
                            // This function add the mentor and student relation ship between loged student and selected mentor with different subjects.
                               $Invitetable->inviteRelation($userObj->getId(),$_POST['Mentor_Email'],3,$newBoardId,$classID,$subjectId,$getcustomboard);   
                        }     
                            //mail containing registration link
                              $event = $this->getEvent();
                              $requestURL = $event->getRequest();
                              $router = $event->getRouter();
                              $uri = $router->getRequestUri();
                            
                              $siteUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
                              $filepath= __DIR__ . '../../../../view/mailer/';
                              $filepath = $filepath.'addexternalmentor.html';
                              $addexternalmentorMessage = file_get_contents($filepath);
                              $registrationlink = $siteUrl.'/user/register';
                              $addexternalmentorsubject="Add Mentor Request";
                              $addexternalmentorMessage = str_replace("<FULLNAME>", "$mentorName", $addexternalmentorMessage);
                              //$addexternalmentorMessage = str_replace("<TYPEOFUSER>", "$usertype", $addexternalmentorMessage);
                              // $addexternalmentorMessage = str_replace("<TYPE>", "$typeofUser", $addexternalmentorMessage);
                              $addexternalmentorMessage = str_replace("<STUDENTNAME>", "$studentname", $addexternalmentorMessage);
                              //$addexternalmentorMessage = str_replace("<EMILADDRESS>", "$emailaddress", $addexternalmentorMessage);
                              $addexternalmentorMessage = str_replace("<BOARDNAME>", "$boardname", $addexternalmentorMessage);
                              $addexternalmentorMessage = str_replace("<CLASSNAME>", "$classname", $addexternalmentorMessage);
                              $addexternalmentorMessage = str_replace("<SCHOOLNAME>", "$schoolname", $addexternalmentorMessage);
                              $addexternalmentorMessage = str_replace("<REGISTRATIONLINK>", "$registrationlink", $addexternalmentorMessage);

                              $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent');
                              $emailData = array("email_id" => $to, 'subject' => $addexternalmentorsubject, 'message' => $addexternalmentorMessage, 'mail_type' => 'useractivities', 'status' => 1);
                              $mailContentTable->addEmailContent($emailData);              
                    }

                    $result = array('output' => '1','response_type'=>'success');

                    echo json_encode($result);
                    exit;
//                     return $result;
                }
            }
          }
        }else{
            $result = array('output' => 1,'response_type'=>'invaild_email');

                    echo json_encode($result);
                    exit;
        }
    }

    public function mentorRequestsAction() {
        $addedMentor = array();
        $subjectArray = array();
        if (isset($_POST['ids'])) {          
            $idss = $_POST['ids'];
            $table = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');

            $subjectid = $table->getsubjectId($idss);

//            $sbjectstable = $this->getServiceLocator()->get('Container\Model\TmainFactory');
//                          $subjectLists = $sbjectstable->getSubjectById($subjectid);
//                         
//                       $subjectList = $subjectLists->buffer();
//                          foreach($subjectList as $subject){
//                              
//                           $boardname = $subject->board_name;
//                           $classname =  $subject->class_name;
//                           $subjectname = $subject->subject_name;
//                       }
//           
//           
//           
               // if notification pre for current relation Id delete it
                   $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
                   $notification = $notificationtable->getnotification('',$idss);
                   if(count($notification)>'0'){
                      foreach($notification as $notify){
                       $data = array(
                    'notification_status' => '2',
                    );
                       $notificationtable->updateStatus($notify->notification_id,$data);
                      }
                   }
                    
            if ($_POST['actortype'] == 'Student') {
                $notificationurl = 'my-students';
            }
            if ($_POST['actortype'] == 'Mentor') {
                $notificationurl = 'mymentor';
            }
            if ($_POST['actiontype'] == 'request') {
                //accept
                if ($_POST['type'] == 'active') {
                    // add notification for  group request   &nbsp;'.$boardname.'-'.$classname.'-'.$subjectname.'&nbsp;as '.$boardname.'-'.$classname.'-'.$subjectname.'&nbsp;as
                 $data = array(
                    'notification_text' => $this->zfcUserAuthentication()->getIdentity()->getUsername().'&nbsp;has accepted mentor request for  &nbsp;'.$_POST['actortype'],
                    'userid' => $_POST['id'],
                    'type_id' => '1',    // group
                     'relation_id'=> $idss,
                    'notification_url' => $notificationurl,
                     'created_by' => $this->zfcUserAuthentication()->getIdentity()->getId(),
                     'created_date'      => date('Y-m-d H:i:s'),    
                );
                } else if ($_POST['type'] == 'delete') {
                    //reject
                     $data = array(
                    'notification_text' => $this->zfcUserAuthentication()->getIdentity()->getUsername().'&nbsp;has declined mentor request for &nbsp;'.$_POST['actortype'],
                    'userid' => $_POST['id'],
                    'type_id' => '1',    // group
                     'relation_id'=> $idss,
                     'notification_url' => $notificationurl,
                     'created_by' => $this->zfcUserAuthentication()->getIdentity()->getId(),
                         'created_date'      => date('Y-m-d H:i:s'),    
                );
                }
            }
            if ($_POST['actiontype'] == 'relation') {
                // deleted &nbsp;'.$boardname.'-'.$classname.'-'.$subjectname.'&nbsp;as
                 $data = array(
                    'notification_text' => $this->zfcUserAuthentication()->getIdentity()->getDisplayName().'&nbsp;has deleted mentor for &nbsp;'.$_POST['actortype'],
                    'userid' => $_POST['id'],
                    'type_id' => '1',    // group
                     'relation_id'=> $idss,
                     'notification_url' => $notificationurl,
                     'created_by' => $this->zfcUserAuthentication()->getIdentity()->getId(),
                     'created_date'      => date('Y-m-d H:i:s'),            
                );
            }


            if ($_POST['type'] == 'delete') {
                // This function delete the mentor and student relation ship between particular mentors and students
                //$addRelation = $table->deleteOperation($ids);
                //  foreach ($id as $idss) {
                // This function delete the mentor and student relation ship between particular mentors and students by setting status 2
                $addRelation = $table->changeStatus($idss, '2');
                //}
            } else if ($_POST['type'] == 'active') {
                // foreach ($id as $idss) {
                // This function active the mentor and student relation ship between particular mentors and students
                $addRelation = $table->changeStatus($idss, 1);
                // }
            } else if ($_POST['type'] == 'deActive') {
                // foreach ($id as $idss) {
                // This function deactive the mentor and student relation ship between particular mentors and students
                $addRelation = $table->changeStatus($idss, 0);
                // }
            }

//               $notificationtable->insertnotification($data);
            // This function get the all mentors of loged student
            $addedMentor = $table->getAll($this->zfcUserAuthentication()->getIdentity()->getId(), 'student');
            $addedStudents = $addedMentor->buffer();
            foreach ($addedStudents as $addedStudentss) {
                $subjectArray[$addedStudentss->id]['subjectId'] = $addedStudentss->subject_id;
            }
            $row = array();
            if (count($addedMentor) != 0) {
                $result = new \Zend\View\Model\JsonModel(array(
                    'addedStudents' => $addedStudents,
                    'subjectArray' => $subjectArray,
                ));
            } else {
                $result = new \Zend\View\Model\JsonModel(array(
                    'output' => 'noRecords',
                ));
            }
            
            return $result;
        }
    }

    public function tempmentorrequestAction() {

        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $temptable = $this->getServiceLocator()->get('Assessment\Model\TtempgroupsTable');
            $temptable->changeStatus($id, '0');
            $result = new JsonModel(array(
                'output' => '1',
            ));
        }

        $result->setTerminal(true);
        return $result;
    }


    //public function commentsAction add comments on services group conversations based on chapterId
    //public function myGroupsAction creates group ,get active status group members details based on userName .It returns view page
    public function myGroupsAction() 
    {
      $userdetails = array();
      $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
      if($auth->hasIdentity()) 
       {
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
            $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
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
            
            // get my group active member count 
            $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
            $activefriends = $table->getAllActivefriends($userId);

            $activefriendcount = count($activefriends);

            if ((count($studentNames) > 0) || (count($getyourRequests) > 0)) {
                $viewModel = new ViewModel(array(
                    'getstudentdetails' => $studentNames,
                    'getyourRequests' => $getyourRequests,
                    'getuserdetails' => $userdetails,
                    // 'boards' => $boarddetails,
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
                ));
            }
            return $viewModel;
        }else{
            return $this->redirect()->toRoute('home');
        }
    }

    //public function commentsAction add comments on services group conversations based on chapterId
    public function commentsAction() {
        
     $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
     if($auth->hasIdentity()) {
         $userObj   = $auth->getIdentity();
         $userId    = $userObj->getId();
         $useremail = $userObj->getEmail();
        //Add comment to a service group conversation        
         $commentTable = $this->getServiceLocator()->get('Assessment\Model\UserQuestionTable');
         
           if(isset($_POST) && $_POST != "") {
                
                if(isset($_POST['questionaskedtoarray'])) {
                    foreach ($_POST['questionaskedtoarray'] as $askedto) {
                         $id = explode("_", $askedto);
                        $_POST['groupownerId'] = $id['0'];
                        $_POST['questionaskedto'] = $id['1'];
                        $addcomment = $commentTable->savecomment($_POST,$userId);
                      }
                } else {
                    $comMapperObj                  = $this->getServiceLocator()->get("com_mapper");
                    $postdata                      = $comMapperObj->escapMessage($_POST['commenttext']);
                    $post['groupownerId']          = $comMapperObj->escaperids($_POST['groupownerId']);
                    if(isset($_POST['questionaskedto'])) {
                        $type = $post['questionaskedto'] = $_POST['questionaskedto'];                         
                    }
                    $addcomment    = $commentTable->savecomment($post,$userId,$postdata);
                  }
                  
                if ($addcomment > 0) {                   
                    $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
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
                    if ($tIds != '') {
                        if(isset($_POST['chapter_id']) && $_POST['chapter_id'] != '') {
                                $countComments = $commentTable->countgroupComments($tIds, $_POST['chapter_id'], $type = 'container',$userId);
                                $currentPage = 1;
                                $resultsPerPage = 5;
                                $paginator = $commentTable->getComments($tIds, $_POST['chapter_id'], $type = 'container', $currentPage, $resultsPerPage,$userId);                       
                        } else {
                                $countComments = $commentTable->countgroupComments($tIds, '0', $type = 'group',$userId);
                                $currentPage = 1;
                                $resultsPerPage = 5;
                                $paginator = $commentTable->getComments($tIds, '0', $type = 'group', $currentPage, $resultsPerPage,$userId);
                        }
                    }
                        $countComments = sizeof($paginator);
                        $get_comment = $paginator->toArray();
                        
                  if(isset($_POST['groupownerId']) && $_POST['groupownerId'] != '') 
                   {
                      echo json_encode(array
                             (  'get_comments'   => $get_comment,
                                'currentPage'    => $currentPage,
                                'resultsPerPage' => $resultsPerPage,
                                'countComments'  => $countComments,
                                'countposts'     => $countComments,
                                // 'currentchapter_details' => $currentchapter_details,
                                'output'          => 'success',
                             )
                      );
                     exit;
                  }
                }else{
                 echo json_encode(array('get_comments' => $get_comment,
                                'currentPage' => $currentPage,
                                'resultsPerPage' => $resultsPerPage,
                                'countComments' => $countComments,
                                'countposts' => $countComments,
                      ));
                exit;
            }
        }
     }

 }

    public function replyAnswersAction() {
        
            $auth = $this->getServiceLocator()->get('zfcuser_auth_service');

        if ($auth->hasIdentity()) {
            $userObj = $auth->getIdentity();
            $userId = $userObj->getId();
            $useremail = $userObj->getEmail();
        }
        
        if (isset($_POST['questionid']) && $_POST['questionid'] != "") {
            $replyonquestion = $this->getServiceLocator()->get('Notification\Model\TreplyonquestionTable');
            // Get all replys of questions.
            $getReplys = $replyonquestion->getReplys($_POST);
         
            // Get all total count of replys of questions.
            $count_answers = $replyonquestion->countReplys($_POST);
            //echo '<pre>'; print_r($getReplys); die;
            $result = new ViewModel(array(
                'getreply' => $getReplys,
                'count_reply' => $count_answers,
                'userId' => $userId,
            ));

            $result->setTerminal(true);
            return $result;
        }
    }
    
        public function changequestionreplystatusAction() {
     
              $auth = $this->getServiceLocator()->get('zfcuser_auth_service');

        if ($auth->hasIdentity()) {
            $userObj = $auth->getIdentity();
            $userId = $userObj->getId();
            $useremail = $userObj->getEmail();
            if (isset($_POST)) {
                $Id = $_POST['replyId'];
                $data['reply_status'] = $_POST['replyStatus'];
                $data['deleted_by'] = $userId;
                $table=$this->getServiceLocator()->get('Assessment\Model\TreplyonquestionTable');
                $updatestatus = $table->updateStatus($Id, $data);
                if ($updatestatus != '') {
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

    public function paginationQuestionsAction() {
        
            $auth = $this->getServiceLocator()->get('zfcuser_auth_service');

        if ($auth->hasIdentity()) {
            $userObj = $auth->getIdentity();
            $userId = $userObj->getId();
            $useremail = $userObj->getEmail();
            $userTypeId = $userObj->getUserTypeId();
        }
        $userTypetable = $this->getServiceLocator()->get('Assessment\Model\TusertypeTable');;
        $userTypeRow = $userTypetable->getusertypename($userTypeId);
        $userType = $userTypeRow->name;
        $getQuestions = "";
        $questionaskedto = "";
        $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
        //get group owner id's of loged user
        $groupownerids = $table->getgroupowner($userId);
        //get group members of loged user
        $groupmemberids = $table->getAllActivefriends($userId);
        //get group list of all     
        $groupusers = $table->getAllgroupfriends($userId);
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
        
        $assignTable = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
        if (@$_POST['questionaskedto'] == 'mentor') {
            $questionaskedto = @$_POST['questionaskedto'];
            //$tIds = $_POST['id'];
            $ids .= "'" . $_POST['id'] . "'" . ",";
            $groupid = $_POST['relationId'];
            $userRowSet = $assignTable->getAll($userId, 'mentor');
                   foreach($userRowSet as $userRow){
                       $ids .= "'" . $userRow->mentor_id . "'" . ",";
                       //echo '<pre>'; print_r($userRow); exit;                   
                   }
            $tIds = rtrim($ids, ",");
        }


        $commentTable = $this->getServiceLocator()->get('Assessment\Model\UserQuestionTable');
        // Get total count of comments
        $totalCount = $commentTable->totalCount($tIds, @$_POST['group_name'], @$_POST['board'], @$_POST['classid'], @$_POST['subjectid'], @$_POST['chapterid'], '', '', rtrim($groupid, ','), $questionaskedto);
        // Get total questins and comments
        $paginator = $commentTable->getQestions($tIds, @$_POST['group_name'], @$_POST['board'], @$_POST['classid'], @$_POST['subjectid'], @$_POST['chapterid'], @$_POST['currentPage'], @$_POST['resultsPerPage'], rtrim($groupid, ','), $questionaskedto);
        
     $result = new ViewModel(array(
            'getQuestions' => $paginator,
            'countComments' => $paginator,
            'countposts' => $totalCount,
            'currentPage' => $_POST['currentPage'],
            'resultsPerPage' => $_POST['resultsPerPage'],
            'userId' => $userId,
        ));
        $result->setTerminal(true);
        return $result;
    }

    public function replyAddAction() {
        
          $auth = $this->getServiceLocator()->get('zfcuser_auth_service');

        if ($auth->hasIdentity()) {
            $userObj = $auth->getIdentity();
            $userId = $userObj->getId();
            $useremail = $userObj->getEmail();
        }
        
        
        
        if (isset($_POST['questionid']) && $_POST['questionid'] != "") {
            $replyonquestion      = $this->getServiceLocator()->get('Assessment\Model\TreplyonquestionTable');
            $comMapperObj         = $this->getServiceLocator()->get("com_mapper");
            $remesage             = $comMapperObj->escapMessage($_POST['replymessage']);
            $_POST['questionid']  = $comMapperObj->escaperids($_POST['questionid']);
            $addReply             = $replyonquestion->addReply($_POST,$userId,$remesage);
            $count_answers        = $replyonquestion->countReplys($_POST);
            if ($addReply > 0) {
              
                $result = new \Zend\View\Model\JsonModel(array(
                    'output' => 'success',
                    'count_reply' => $count_answers,
                ));
                return $result;
            }
        }
    }
    
    

    public function changegroupstatusAction() {
        //global $groupmemberdeletedMessage;
        //global $groupmemberdeletedSubject;


        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');

        if ($auth->hasIdentity()) {
            $userObj = $auth->getIdentity();
            $userId = $userObj->getId();

            if (isset($_POST)) {
                $groupId = (int) $_POST['groupId'];
                // if notification pre for current relation Id delete it
                 $notificationtable = $this->getServiceLocator()->get('notification\Model\NotificationTable');

                  $notification = $notificationtable->getnotification('', $groupId);
                  if (count($notification) > '0') {
                  foreach ($notification as $notify) {
                  $notifydata = array(
                  'notification_status' => '2',
                      'notification_uuid' => $this->getApiService()->generateUuid(),
                    );
                    $notificationtable->updateStatus($notify->notification_id, $notifydata);
                     }
                  } 

                $deletedby = '0';
                $rejectedby = '0';
                $groupstatus = $_POST['groupStatus'];
                if ($groupstatus == '2') {
                    //Remove user
                      if (urldecode($_POST['type']) == 'Remove user') {
                      $notificationdata = array(
                      'notification_text' => $userObj->getDisplayname() . '&nbsp;has removed you from group',
                      'userid' => $_POST['friendId'],
                      'type_id' => '1', // group
                      'relation_id' => $groupId,
                          'notification_uuid' => $this->getApiService()->generateUuid(),
                      'notification_url' => 'my-groups/1',
                      'created_by' => $userId,
                      'created_date' => date('Y-m-d H:i:s'),
                      );
                      }

                    if ($_POST['type'] == 'Leave group') {
                         $notificationdata = array(
                          'notification_text' => $userObj->getDisplayname() . '&nbsp;has left group',
                          'userid' => $_POST['friendId'],
                          'type_id' => '1', // group
                          'relation_id' => $groupId,
                             'notification_uuid' => $this->getApiService()->generateUuid(),
                          'notification_url' => 'my-groups/1',
                          'created_by' => $userId,
                          'created_date' => date('Y-m-d H:i:s'),
                          ); 
                    }


                    if ($_POST['type'] == 'Delete') {
                         $notificationdata = array(
                          'notification_text' => $userObj->getDisplayname() . '&nbsp;has Deleted you',
                          'userid' => $_POST['friendId'],
                          'type_id' => '1', // group
                          'relation_id' => $groupId,
                             'notification_uuid' => $this->getApiService()->generateUuid(),
                          'notification_url' => 'my-groups/2',
                          'created_by' => $userId,
                          'created_date' => date('Y-m-d H:i:s'),
                          ); 
                    }
                    $deletedby = $userId;
                }
                if ($groupstatus == '3') {

                    if ($_POST['type'] == 'Reject') {
                          $notificationdata = array(
                          'notification_text' => $userObj->getDisplayname() . '&nbsp;has rejected your group request',
                          'userid' => $_POST['friendId'],
                          'type_id' => '1', // group
                          'relation_id' => $groupId,
                              'notification_uuid' => $this->getApiService()->generateUuid(),
                          'notification_url' => 'my-groups/1',
                          'created_by' => $userId,
                          'created_date' => date('Y-m-d H:i:s'),
                          ); 
                    }

                    $rejectedby = $userId;
                }

                if ($groupstatus == '1') {

                    if ($_POST['type'] == 'Accept') {
                          $notificationdata = array(
                          'notification_text' => $userObj->getDisplayname() . '&nbsp;has accepted your group request',
                          'userid' => $_POST['friendId'],
                          'type_id' => '1', // group
                          'relation_id' => $groupId,
                          'notification_url' => 'my-groups/1',
                           'notification_uuid' => $this->getApiService()->generateUuid(),
                          'created_by' => $userId,
                          'created_date' => date('Y-m-d H:i:s'),
                          ); 
                    }
                }
                $data = array(
                    'group_status' => $_POST['groupStatus'],
                    'deleted_by' => $deletedby,
                    'rejected_by' => $rejectedby
                );
                 $notificationtable = $this->getServiceLocator()->get('notification\Model\NotificationTable');
                 $notificationtable->insertnotification($notificationdata);


                if ($_POST['actortype'] == 'owner') {
                     $to = $_POST['useremail'];
                      $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                      // Get user details of loged user
                      $user_details = $table->getuserdetails($userId);
                      $user_info = $user_details->current();
                      $groupownername = ucfirst($user_info->firstName);
//                      $groupmemberdeletedMessage = str_replace("<GROUPOWNER>", "$groupownername", $groupmemberdeletedMessage);
//                      $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent');
//                      $emailData = array("email_id" => $to, 'subject' => $groupmemberdeletedSubject, 'message' => $groupmemberdeletedMessage, 'mail_type' => 'useractivities', 'status' => 1);
//                      $mailContentTable->addEmailContent($emailData); 
                }
                $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
                $updatestatus = $table->updateStatus($groupId, $data);

                if ($updatestatus != '0') {
                    echo json_encode(array('output' => 'sucusses'));
                    exit;
                } else {
                    echo json_encode(array('output' => 'notsucusses'));
                    exit;
                }
            }
        } else {

          //  $this->redirect()->toRoute('zfcuser/signup');
        }
    }

 
    public function linkparentAction() {
        $userEmail = array();
        
        if (isset($_POST['linkParentUserId']) && $_POST['linkParentUserId'] != '') {
            
            $userObj = $this->zfcUserAuthentication()->getIdentity();
            $childId = $userObj->getId();
            $childName = $userObj->getDisplayName();
            //echo $childName; exit;
            //echo '<pre>'; print_r($userObj); exit;
            $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
            $_POST['linkParentUserId'] = strip_tags($_POST['linkParentUserId']);
            $validemail                 = $this->validateEmail($_POST['linkParentUserId']);
           
          if($validemail == 'Valid'){
            // Check username is already exists or not
            $records = $table->checkEmail($_POST['linkParentUserId'], 'user');
            //echo '<pre>';print_r($records);echo '</pre>';die('Macro');
            if ($records->count() >0) {
                $parentdetail = $table->getuserid($_POST['linkParentUserId']);
                $parentId = $parentdetail->user_id;
                
                if($userObj->getEmail() == $_POST['linkParentUserId']){
                    echo json_encode(array(
                        'output' => 'userself',
                    ));
                    die;
                }
                if ($parentdetail->user_type_id != '1') { // request not to a user of student type
                    // $user = $table->linkChildParent($_POST);
                    //$updateId = $table->updateparentID($childId, $parentId);
                    $table = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
                    //add parent child relation request in t_parent_child
                    $user = $table->addrelation($parentId, $childId, 'parent');


                    if ($user != "") {
                        if ($user != '0') {
                            $redirect_url = '';
                            echo json_encode(array(
                                'output' => 'success',
                                'message' => 'Parent has been linked successfully',
                                'redirect_url' => $redirect_url
                            ));
                            
                            // remove if existing relation notification
                            //$notificationtable = $this->getServiceLocator()->get('ZfcUser\Model\NotificationTable');
                            $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
                            
                            $notification = $notificationtable->getnotification('', $user);
                            if (count($notification) > '0') {
                                foreach ($notification as $notify) {
                                    $notifydata = array(
                                        'notification_status' => '2',
                                        'notification_uuid' => $this->getApiService()->generateUuid(),
                                    );
                                    $notificationtable->updateStatus($notify->notification_id, $notifydata);
                                }
                            }
                            
                            $notificationdata = array(
                                'notification_text' => $childName . '&nbsp;has sent you request to be his parent',
                                'userid' => $parentId,
                                'type_id' => '2', // group
                                'relation_id' => $user,
                                'notification_url' => 'my-child/2',
                                'created_by' => $childId,
                                'notification_uuid' => $this->getApiService()->generateUuid(),
                                'created_date' => date('Y-m-d H:i:s'),
                            );

                            $notificationtable->insertnotification($notificationdata);
                        } else {


                           echo json_encode(array(
                                'output' => 'exists',
                            ));
                        }
                    } else {
                        echo json_encode(array(
                            'output' => 'notsuccess',
                        ));
                    }
                } else {
                    echo json_encode(array(
                        'output' => 'userstudent',
                    ));
                }
            } else {
                echo json_encode(array(
                    'output' => 'emailNotExists',
                ));
            }
         }else{
              echo json_encode(array(
                    'output' => 'EmailNotproper',
                ));
          
         }   
            
        }
        //return $result;
        die;
    }
 

function deleteparentchildAction(){
          
    $userObj = $this->zfcUserAuthentication()->getIdentity();
    $loginuserId         = $userObj->getId();
            $userid      = $_REQUEST['UId'];
            $status      = $_REQUEST['status'];
            $relation_id = $_REQUEST['relationId'];
            $table       = $this->getServiceLocator()->get('Assessment\Model\UserTable');
            $table->updateparentchildstatus($userid,$status);
            // parent has deleted you
            if(!empty($relation_id))
            {
                $table = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
                $data  = array();
                $data['status'] = $status;
                $data['modified_date'] = new \Zend\Db\Sql\Expression("NOW()");
                $table->updateStatus($relation_id,$data);
            }
              $data = array(
                    'notification_text' =>  $userObj->getDisplayName().'&nbsp;has deleted you as his child',
                    'userid' => $userid,
                    'type_id' => '1',    
                    'notification_url' => 'my-parent',
                    'created_by'       => $loginuserId,
                    'created_date'      => date('Y-m-d H:i:s'),
                  'notification_uuid' => $this->getApiService()->generateUuid(),
                );
                $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
                 $notificationtable->insertnotification($data);
                
            
             $result = new \Zend\View\Model\JsonModel(array(
                 'output'=>'success',
                 'message'=>'Data deleted successfully.',
             ));
            
             $result->setTerminal(true);
            return $result;
        }

     public function changeparentstatusAction() 
     {
         $userObj = $this->zfcUserAuthentication()->getIdentity();
         $userId = $userObj->getId();
         
         if($userId !=''){
            if (isset($_POST)) {
                $userDisplayName = $userObj->getDisplayName();
                $table          = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
                $checkvaliduser = $table->getrelation((int)$_POST['parentID'], $userId);
                if($checkvaliduser == '0')
                {
                     $result = new \Zend\View\Model\JsonModel(array(
                        'output' => 'notsucusses',
                    ));
                } 
                
                $Id = $_POST['Id'];
                $data = array(
                    'status' => $_POST['Status'],
                );
                
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
                
                 
               if($_POST['Status'] == '1') 
                {
                   $notificationdata = array(
                     'notification_text' => $userDisplayName.'&nbsp;has accepted your request to be his/her parent',
                     'userid' => $_POST['parentID'],
                     'type_id' => '1',    // group
                     'relation_id'=> $Id,
                     'notification_url' => 'my-child/1',
                       'notification_uuid' => $this->getApiService()->generateUuid(),
                     'created_by' =>$userId,
                     'created_date'  	=> date('Y-m-d H:i:s'),	
                     );
               }else if($_POST['Status'] == '2'){
                      $notificationdata = array(
                      'notification_text' =>$userDisplayName.'&nbsp;has declined your request to be his/her parent',
                       'userid' => $_POST['parentID'],
                       'type_id' => '1',    // group
                       'relation_id'=> $Id,
                       'notification_url' => 'my-child/1',
                       'created_by' => $userId,
                        'notification_uuid' => $this->getApiService()->generateUuid(),
                       'created_date'  	=> date('Y-m-d H:i:s'),	
                    );
                 } 
               $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
               $notificationtable->insertnotification($notificationdata);
                
                //Status
                $table          = $this->getServiceLocator()->get('Assessment\Model\TparentandchildTable');
                $usertable = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                if ($_POST['Status'] == '1') {
                    $ParentData = $usertable->getParentData($userId)->current();
                  //  $Parent_ids =  $ParentData->user_id;
                    $childDetail = $usertable->parentchilddetail($userId);
                  //    echo "<pre />"; print_r($childDetail);exit;
                    $data2 = array('status' => '2',); 
                    foreach($childDetail as $allchild)
                    {
                        $updatestatus  = $table->updateStatus($allchild->t_parent_child_id, $data2);
                                
                         $notificationdata = array(
                             'notification_text' =>$userDisplayName.'&nbsp;has remove you as his/her parent',
                             'userid' => $allchild->parent_id,
                             'type_id' => '1',    // group
                             'relation_id'=> $allchild->t_parent_child_id,
                             'notification_url' => 'my-child/1',
                             'created_by' => $userId,
                             'notification_uuid' => $this->getApiService()->generateUuid(),
                              'created_date'  	=> date('Y-m-d H:i:s'),	
                            );       
                          $notificationtable->insertnotification($notificationdata);
                    }
               }                  
               $updatestatus  = $table->updateStatus($Id, $data);

                //if activating set parent Id in t_user
                $updateId = $userId;
                if ($_POST['Status'] == '1') {
                    $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                    $updateId = $table->updateparentID($userId, $_POST['parentID']);
                    //$updateId = $table->updateparentID($userId, NULL);
                }
                if ($updatestatus != '0' && @$updateId != '0') {
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
     
public function checkEmailAction() {
   $userDetails = array();
    if (isset($_POST)) {        
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        if($auth->hasIdentity()) {
                $userObj = $auth->getIdentity();
                $userId = $userObj->getId();
            }
        $_POST['email'] = strip_tags($_POST['email']);
        if($this->validateEmail($_POST['email']) == 'Not_valid' ) {                   
             echo json_encode(array('output' => 'Invalid email address'));
             exit;
            }
             
            if(isset($_POST['flag']) && ($_POST['flag'] == 'verify')) {
                $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                // This function get user id based on email
                $getfriendid = $table->getuserid($_POST['email']);                
                if (isset($getfriendid->user_id) && $getfriendid->user_id != '') {
                    $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
                    // This function check the user exists or not based on user id
                    $userDetails = $table->checkEmail($getfriendid->user_id, $userId);
                } else {
                    $userDetails = 0;
                }
            } else if (isset($_POST['flag']) && ($_POST['flag'] == 'true')) {
                $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                // This function get user id based on email
                $getfriendid = $table->getuserid($_POST['email']);
                if (isset($getfriendid->user_id) && $getfriendid->user_id != '') {
                    $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
                    // This function check the user exists or not based on user id
                    $userDetails = $table->checkEmail($getfriendid->user_id, $userId);
                } else {
                    $userDetails = 0;
                }
            } else {
                $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                // This function check the user exists or not based on email
                $userDetails = $table->checkEmailType($_POST['email'], 'account');
                $userDetails = count($userDetails);
            }
              if ($userDetails != 0) {
              echo json_encode(array('output' => 'sucusses'));
              exit;
              } else {
              echo json_encode(array('output' => 'notSuccess'));
              exit;
              } 

        }
    }

//public function addGroupDetailsAction before add first checks the emailId exists or not ,if not there add group and fill all details of groupmemebers returns view Page
public function addGroupDetailsAction() 
{ 
    //ini_set('display_startup_errors',1);
    //ini_set('display_errors',1);
    //error_reporting(E_ALL);
    $_POST['emailid'] = strip_tags($_POST['emailid']);
    if($this->validateEmail($_POST['emailid']) == 'Not_valid' ) {                  
         echo json_encode(array('error' => 'true','message' => 'Invalid email address'));
          exit;
    }
 
   if(isset($_POST['emailid']) && $_POST['emailid'] != "") {
     $studentNames = array();
     $getfriendid  = array();
     $useremail    = $_POST['emailid'];
     $groupstatus  = $_POST['groupstatus'];

     $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
      if($auth->hasIdentity()) {
            $userObj = $auth->getIdentity();
            $userId = $userObj->getId();
     }else{
        return new JsonModel(array('error' => 'true',
            'message' => 'User should be logged in'
                )
        );
    }   
    $table       = $this->getServiceLocator()->get('Assessment\Model\UserTable');
    $getfriendid = $table->getuserid($useremail);
    
   if(isset($getfriendid->user_id) && $getfriendid->user_id != '') {
        $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
           // Add user into group member of loged user
        $addfriend_status = $table->addfriends($getfriendid->user_id, $groupstatus, $userId);

              // if notification pre for current relation Id delete it
        $notificationtable = $this->getServiceLocator()->get('notification\Model\NotificationTable');
        $notification = $notificationtable->getnotification('',$addfriend_status);
        if(count($notification)>'0'){
          foreach($notification as $notify)
           {
             $data = array('notification_status' => '2',);
                      $notificationtable->updateStatus($notify->notification_id,$data);
            }
         }
         //add notification for  group request
        $data = array(
                'notification_text' => $userObj->getDisplayname().'&nbsp; sent a group member request',
                'userid' => $getfriendid->user_id,
                'type_id' => '1',    // group
                'relation_id'=> $addfriend_status,
                'notification_url' => 'my-groups/2',
                'notification_uuid' => $this->getApiService()->generateUuid(),
                'created_by' => $userId,
                'created_date'  	=> date('Y-m-d H:i:s'),
                );

                $notificationtable->insertnotification($data); 
                $event      = $this->getEvent();
                $requestURL = $event->getRequest();
                $router     = $event->getRouter();
                $uri        = $router->getRequestUri();
                $baseUrl    = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
                  
                $username1 = $getfriendid->firstName;
                $username = ucfirst($userObj->getEmail());
                $to = $useremail;
                $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                  // Get user details of loged user
                $user_details = $table->getprofilebyid($userId);
                $user_info = $user_details->current();
                $studentname = ucfirst($user_info->firstName);
                $boardname = $user_info->boardName;
                $classname = $user_info->className;
                $schoolname = $user_info->school_name;
                $type_of_user = ($user_info->user_type_id == '1' ? 'Student' : ($user_info->user_type_id == '2' ? 'Parent' : 'Mentor'));
                $emailaddress = $user_info->emailId;
                  
                if($user_info->gender == 'Male') {
                    $type = 'his';
                  } else if($user_info->gender == 'Female') {
                  $type = 'her';
                  } else {
                  $type = 'his/her';
                  }
                  
                  $requestedEmail  = $getfriendid->emailId;
                  //$addgroupsubject=$username."    adding a group";
                  
                  $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent');
                  
                  $registrationlink = $baseUrl.'/user/register';
                  $filepath= __DIR__ . '../../../../view/mailer/';
                  $filepath = $filepath.'addgroupmentor.html';
                  $file_content = file_get_contents($filepath);
                  
                      $addgroupsubject = $username."    adding a group";
                      $file_content = str_replace("[FULLNAME]", "$username1", $file_content);
                      $file_content = str_replace("[TYPEOFUSER]", "$type_of_user", $file_content);
                      $file_content = str_replace("[TYPE]", "$type", $file_content);
                      $file_content = str_replace("[STUDENTNAME]", "$studentname", $file_content);
                      $file_content = str_replace("[EMILADDRESS]", "$emailaddress", $file_content);
                      $file_content = str_replace("[BOARDNAME]", "$boardname", $file_content);
                      $file_content = str_replace("[CLASSNAME]", "$classname", $file_content);
                      $file_content = str_replace("[SCHOOLNAME]", "$schoolname", $file_content);
                      $file_content = str_replace("[REGISTRATIONLINK]", "$registrationlink", $file_content);
                      $file_content = str_replace('{SITE_URL}', $baseUrl, $file_content);
                      $file_content = str_replace('{BASE_URL}', $baseUrl, $file_content);
                  
                  $emailData = array("email_id" => $to, 'subject' => $addgroupsubject, 'message' => $file_content, 'mail_type' => 'useractivities', 'status' => 1);
                  
                  if ($mailContentTable->addEmailContent($emailData)) {
                  $userId = $userId;
                  $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
                  $studentNames = $table->getAllfriends($userId);
                  
                  $result =  new \Zend\View\Model\JsonModel(array(
                  'getstudentdetails' => $studentNames,
                  ));
                  } 


                $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
                $studentNames = $table->getAllfriends($userId);


                $result = new \Zend\View\Model\JsonModel(array(
                    'getstudentdetails' => $studentNames,
                ));
                $result->setTerminal(true);
                return $result;
            } else {

                // if user id not exist send registration mail to the user 

                $temptable = $this->getServiceLocator()->get('Assessment\Model\TtempgroupsTable');
                // check if request has been send to user 
                $requestlist = $temptable->checktemprequest($useremail, 'group', $userId);


                if ($requestlist > 0) {
                    echo json_encode(array('output' => 'exist'));
                    exit;
                } else {
                      $to = $useremail;
                      $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                      // Get loged user details
                      $username1    = '';
                      $user_details = $table->getprofilebyid($userId);
                      //echo '<pre>'; print_r($user_details); exit;
                      $user_info = $user_details->current();
                      $studentname = ucfirst($user_info->firstName);
                      $boardname = $user_info->boardName;
                      $classname = $user_info->className;
                      $schoolname = $user_info->school_name;
                      // $userprofile = $baseUrl . "/user-profile/?id=" . $_SESSION['user']['userId'];
                      
                      if ($user_info->gender == 'Male') {
                      $typeofUser = 'his';
                      } else if ($user_info->gender == 'Female') {
                      $typeofUser = 'her';
                      } else {
                      $typeofUser = 'his/her';
                      }
                      $event = $this->getEvent();
                      $requestURL = $event->getRequest();
                      $router = $event->getRouter();
                      $uri = $router->getRequestUri();
                      $baseUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
                      $registrationlink = $baseUrl.'/user/register';
                      $filepath= __DIR__ . '../../../../view/mailer/';
                      $filepath = $filepath.'addgroupmentor.html';
                      $addexternalgroupMessage = file_get_contents($filepath);
                      
                      
                      $addexternalgroupsubject=$username."    adding a group";
                      $addexternalgroupMessage = str_replace("[FULLNAME]", "$username1", $addexternalgroupMessage);
                      $addexternalgroupMessage = str_replace("[TYPEOFUSER]", "$type_of_user", $addexternalgroupMessage);
                      $addexternalgroupMessage = str_replace("[TYPE]", "$type", $addexternalgroupMessage);
                      $addexternalgroupMessage = str_replace("[STUDENTNAME]", "$studentname", $addexternalgroupMessage);
                      $addexternalgroupMessage = str_replace("[EMILADDRESS]", "$emailaddress", $addexternalgroupMessage);
                      $addexternalgroupMessage = str_replace("[BOARDNAME]", "$boardname", $addexternalgroupMessage);
                      $addexternalgroupMessage = str_replace("[CLASSNAME]", "$classname", $addexternalgroupMessage);
                      $addexternalgroupMessage = str_replace("[SCHOOLNAME]", "$schoolname", $addexternalgroupMessage);
                      $addexternalgroupMessage = str_replace("[REGISTRATIONLINK]", "$registrationlink", $addexternalgroupMessage);
                      $addexternalgroupMessage = str_replace('{SITE_URL}', $baseUrl, $addexternalgroupMessage);
                      $addexternalgroupMessage = str_replace('{BASE_URL}', $baseUrl, $addexternalgroupMessage);
                      
                       $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent');
                       $emailData = array("email_id" => $to, 'subject' => $addexternalgroupsubject, 'message' => $addexternalgroupMessage, 'mail_type' => 'useractivities', 'status' => 1);
                       $mailContentTable->addEmailContent($emailData);
                       
                        $config=$this->getServiceLocator()->get('config');
                        $defaultstates = isset($config['msg_engine'])?$config['msg_engine']:'';
                        if(isset($defaultstates['status']) && $defaultstates['status'] == 'ON') {
                            $tableuserother = $this->getServiceLocator()->get('Assessment\Model\UserOtherDetailsTable');
                            $userRegOtherDetails = $tableuserother->getUserOtherDetailsByKey($userObj->getId() , 'register_by', Null);
                            foreach($userRegOtherDetails as $otherdetail) {
                                $register_by = $otherdetail->value;
                            }
                            if($register_by=='mobile') {
                                    $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                                    $msglog  = $this->getServiceLocator()->get('Package\Model\TmsgLogTable');
                                    $msgTxt = "Dear $studentname,<br>
                                    $username1 has sent you an invitation to join Extramarks Smart study group for ($boardname)-($classname)<br>
                                    To join, click <a href='www.extramarks.com'>here</a>";
                                    
                                    $usermobile = $user_info->mobile;
                                    $mobile     = explode("-", $studentMobile);
                                    $mob_number = $mobile[1];
                                    if($mobile[1]) {
                                        $smsArr = array('to_mobile_number'=>$mob_number,
                                            'msg_txt' => $msgTxt,
                                            'user_id' => $userObj->getId(),
                                            'mobile_number' => $user_info->mobile,
                                            'sms_type' => 'add mentor to group'
                                        );
                                    $data = $comMapperObj->smssendprocess($smsArr);
                                    $result = $msglog->addlog($data);
                               }
                            }
                        }

                      // entry in temp group table ; 


                    if ($temptable->addtemprequest($useremail, 'group', $userObj)) {
                        // $userId = $_SESSION['user']['userId'];
                        $table = $this->getServiceLocator()->get('Assessment\Model\TusergroupsTable');
                        // Get all group members names
                        $studentNames = $table->getAllfriends($userId);
                        $result = new \Zend\View\Model\JsonModel(array(
                            'getstudentdetails' => $studentNames,
                        ));
                    }
                }
                
                $result->setTerminal(true);
                return $result;
            }
        }
    }

     public function changequestionstatusAction() 
             {
     
         $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
            if ($auth->hasIdentity()) {
                $userObj = $auth->getIdentity();
                $userId = $userObj->getId();
            
            if (isset($_POST)) {
                $Id = $_POST['questionId'];
                $data['c_status'] = $_POST['questionStatus'];
                $data['deleted_by'] = $userId;
                $table=$this->getServiceLocator()->get('Assessment\Model\UserQuestionTable');
                 $updatestatus = $table->updateStatus($Id, $data);
                 
                 if(isset($_POST['url'])){
                     if (strpos($_POST['url'],'group') !== false) {
                               $redirect = 'group';     
                        }
                        elseif(strpos($_POST['url'],'ajax') !== false) {
                                $redirect = 'ajax';         
                        }else{
                            $redirect = '';        
                        }
                     
                 }
                 
             if ($updatestatus != '0') {
                    $result = array(
                        'output' => 'sucusses',
                        'redirect' => $redirect
                    );
                } else {
                    $result =array(
                        'output' => 'notsucusses',
                    );
                }
                
                 echo json_encode($result);
                    exit;
                
            }
        }
    }
    
 public function searchResultAction()
 {
    if(class_exists('Memcached')) 
    {       
        $cache = $this->getServiceLocator()->get('config');
        $memcached = StorageFactory::factory($cache['memcached']);
    }
    if (isset($_GET['text']) && $_GET['text'] != '') {
        $searchTable = $this->getServiceLocator()->get('Notification\Model\TTchaterResourceTable');
        $_GET['text'] = strip_tags($_GET['text']);
        if(class_exists('Memcached')){
                 if (($searchResult  = $memcached->getItem('GetSearchResult-'.$_GET['text'])) == FALSE) {
                      $searchResult  =  $searchTable->getSearchResult($_GET['text']);
                      $memcached->setItem('GetSearchResult-'.$_GET['text'], $searchResult);
                 }
             }else{
                     $searchResult = $searchTable->getSearchResult($_GET['text']);
             }
           $_SESSION['user']['searchtext'] = $_GET['text'];
   
    }

    $result = new ViewModel(array(
                'searchResult' => $searchResult,
            ));
    return $result;
       
    }
    
 public function myStudentsFeedbackAction() {
        //$baseUrls = $this->getServiceLocator()->get('config');
        //$baseUrlArr = @$baseUrls['urls'];
        //$baseUrl = @$baseUrlArr['baseUrl'];
        //$baseUrl='http://localhost/school_lms/public';
        $error = '';
        $table = $this->getServiceLocator()->get('Assessment\Model\TmentorfeedbackTable');
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        
            if ($auth->hasIdentity()) {
                $userObj = $auth->getIdentity();
                $userId = $userObj->getId();
                $mentorName = $userObj->getDisplayName();
            }  
            $getParams = $this->params()->fromRoute('id', 0);
            $getParams = explode("-", $getParams);
            //echo '<pre>'; print_r($getParams); exit;
            if(isset($getParams[3])) {
                $BoardId=$getParams[3];
            } else {
                $BoardId = '';
            }
            if(isset($getParams[4])) {
                $className=$getParams[4];
            } else {
                $className = '';
            }
            
            if(isset($getParams[5])) {
                $classIds=$getParams[5];
            } else {
                $classIds = '';
            }
            
            
        $containerservice   = $this->getServiceLocator()->get('lms_container_service');    
        // Below if condition shows the mentor send the only coverage feed back from progress page.
        //echo '<pre>'; print_r($_POST); exit;
        if (isset($_POST['feedback'])) { 
            if ($_POST['getCoverage'] == 1) {
                // This function check the coverage feed back already send or not particular student with loged mentor.
                $feedback = $table->getfeedbackComment($_POST['hidstudent_id'], 0, '', $_POST['type'], $_POST['subjectId'],$userId);
                if ($feedback->count()) {
                    return new JsonModel(array(
                        'output' => 1,
                        'comment' => $feedback->buffer()->current()->coverage_comment,
                        'feedbackId' => $feedback->buffer()->current()->feedback_id,
                    ));
                } else {
                    return new JsonModel(array(
                        'output' => 0,
                    ));
                }
            } else {
                
                $stable = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable'); 
                // This function check the student and mentor relationship between particular student and loged mentor 
                $studentId = isset($_POST['studentId'])?$_POST['studentId']:$_POST['hidstudent_id'];
                
                $checkMentorSubject = $stable->checkRelation($_SESSION['user']['userId'], $studentId, $_POST['subjectId']);
                if(isset($_POST['studentId']) && $_POST['studentId']!=''){
                    $userTable = $this->getServiceLocator()->get('Assessment\Model\TusertypeTable');
                    $userDetails = $userTable->getUserbyUserId($_POST['studentId']);                   
                    if($userDetails->parent_id != '' && $userDetails->parent_id == $userId)
                        $checkMentorSubject = 1;                     
                }
                if ($checkMentorSubject == 0) {
                    return new JsonModel(array(
                        'output' => 0,
                    ));
                } else {
                    $commentMentor = array();
                    $commentMentor['selectDate'] = $_POST['selectDate'];
                    $commentMentor['feedbackCoverage'] = $_POST['feedbackCoverage'];
                    $commentMentor['hidFeedCId'] = $_POST['hidFeedCId'];
                    if(isset($_POST['studentId']) && !empty($_POST['studentId'])){
                        $commentMentor['hidstudent_id'] = $_POST['studentId'];
                    } else{
                        $commentMentor['hidstudent_id'] = $_POST['hidstudent_id'];
                    }
                    $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                    $customBoardId = $comMapperObj->getcustomboardPrimaryId($BoardId,$_POST['subjectId']); 
                    $commentMentor['custom_board_rack_id'] = $customBoardId;
                    // This function add the feedback coverage and schdule comments.
                    $feedbackId = $table->addComments($commentMentor, $commentMentor['hidFeedCId'], 'popUp',$userId);
                    $table = $this->getServiceLocator()->get('Assessment\Model\TfeedbackchaptersTable');
                    // This function add the comments of mutiple subjects and multiple chapters
                    $addComments = $table->addComments($_POST['subjectId'], $_POST['hidFeedCId'], $feedbackId, 'popUp',$userId);
                    return new JsonModel(array(
                        'output' => 1,
                    ));
                }
            }
        } else {
            // Below else code shows the mentor feed back send from myStudentfeedback page.
            if (isset($_POST['feedbackFormSubmit'])) {
                $i = 0;
                $comment = array();
                $commentMentor = array();
                $commentMentor['selectDate'] = $_POST['selectDate'];
                $commentMentor['feedbackCoverage'] = $_POST['feedbackCoverage'];
                $commentMentor['feedbackSchedule'] = $_POST['feedbackSchedule'];
                $commentMentor['feedback'] = $_POST['overallfeedback'];
                $commentMentor['feedback_type'] = 'all';
                $commentMentor['subject_id'] = $_POST['hidsubject_id'];
                $commentMentor['hidFeedCId'] = $_POST['hidFeedCId'];
                $commentMentor['hidstudent_id'] = $_POST['hidstudent_id'];
                
                $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                $customBoardId = $comMapperObj->getcustomboardPrimaryId($BoardId,$_POST['hidsubject_id']); 
                $commentMentor['custom_board_rack_id'] = $customBoardId;
                
                if(isset($_POST['feedbackSubject0']) && !empty($_POST['feedbackSubject0'])) {
                    $subject_id = $_POST['feedbackSubject0'];
                    if($subject_id!=0) {
                    $chapterDetails = $containerservice->getChildList($subject_id);
                        $chapterdetail = array();
                        foreach($chapterDetails as $key => $chdetail) {
                            $chapterdetail[$key]['chapter_name'] = $chdetail->getRackName()->getName();
                            $chapterdetail[$key]['id']           = $chdetail->getRackId();
                            $chapterdetail[$key]['rack_type_id'] = $chdetail->getRackType()->getRackTypeId();
                        }
                    }
                }
                
                if(isset($_POST['feedbackSubjectiveSubject0'])) {
                    $sub_subject_id = $_POST['feedbackSubjectiveSubject0'];
                    if($sub_subject_id!=0) {
                        $chapter_Details = $containerservice->getChildList($sub_subject_id);
                        $subjectivechapterdetail = array();
                        foreach($chapter_Details as $key => $chdetail) {
                            $subjectivechapterdetail[$key]['chapter_name'] = $chdetail->getRackName()->getName();
                            $subjectivechapterdetail[$key]['id']           = $chdetail->getRackId();
                            $subjectivechapterdetail[$key]['rack_type_id'] = $chdetail->getRackType()->getRackTypeId();
                        }
                    }
                }
                
                $countArr=0;
                if(!empty($_POST['hiddenfeedbackChapter0']) && $_POST['hiddenfeedbackChapter0'] == 'all') {
                    
                       $_POST['feedbackChapter0'] = 'all'; 
                       $subject_id = $_POST['feedbackSubject0'];
                       //echo $subject_id;
                       if($subject_id!=0) {
                            $chapterDetails = $containerservice->getChildList($subject_id);
                            $countArr = count($chapterDetails);
                       } else {
                           $countArr=0;
                       }
                       ///echo $countArr;
                    
                }
                $countsubArr =0;
                if(!empty($_POST['hiddenfeedbackSubjectiveChapter0']) && $_POST['hiddenfeedbackSubjectiveChapter0'] == 'all') {
                    //if($_POST['feedbackSubjectiveChapter0'] != 'all') {
                       $_POST['feedbackSubjectiveChapter0'] = 'all'; 
                       $sub_subject_id = $_POST['feedbackSubjectiveSubject0'];
                       if($sub_subject_id!=0){
                        $chapter_Details = $containerservice->getChildList($sub_subject_id);
                        $countsubArr = count($chapter_Details);
                       } else {
                           $countsubArr = 0;
                       }
                }
                
                $mcqComment = 0;
                $subjectiveComment = 0;
                for ($i = 0; $i < $_POST['totalChapters']; $i++) {
                    //echo $_POST['feedbackTests' . $i];
                    //if (isset($_POST['feedbackTests' . $i]) && $_POST['feedbackTests' . $i] != '') {
                        if(isset($_POST['feedbackTests0']) && empty($_POST['feedbackTests0'])){
                                $_POST['feedbackTests0'] = 'No Comment';
                        }
                        if(isset($_POST['feedbackChapter0']) && $_POST['feedbackChapter0']=='all') {
                            if( isset($chapterdetail[$i]['id'])) {
                                 if(isset($_POST['feedbackTests0']) && !empty($_POST['feedbackTests0'])) {
                                    $chapterId = $chapterdetail[$i]['id'];
                                    $endmess = $_POST['feedbackTests0'];
                                    $feedback_subject_id = $_POST['feedbackSubject0'];
                                    $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                                    $customBoardId = $comMapperObj->getcustomboardPrimaryId($BoardId,$feedback_subject_id);
                                    $comment['mcq' . $i]['feedbackSubject'] = $feedback_subject_id;
                                    $comment['mcq' . $i]['feedbackChapter'] = $chapterId;
                                    $comment['mcq' . $i]['commentText'] = $endmess;
                                    $comment['mcq' . $i]['commentType'] = 'mcq';
                                    $comment['mcq' . $i]['hidFeedCId'] = $_POST['hidFeedCId'];
                                    $comment['mcq' . $i]['selectDate'] = $_POST['selectDate'];
                                    $comment['mcq' . $i]['totalQuestions'] = 0;
                                    $comment['mcq' . $i]['rightAnswers'] = 0;
                                    $comment['mcq' . $i]['totalScore'] = 0;
                                    $comment['mcq' . $i]['custom_board_rack_id'] =$customBoardId;
                                    $mcqComment++;
                                 }
                            }
                        } else {
                            if(isset($_POST['feedbackTests' . $i]) && !empty($_POST['feedbackTests' . $i])) {
                                if($_POST['feedbackSubject' . $i] != 0 && $_POST['feedbackChapter' . $i] != 0) {
                                    $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                                    $customBoardId = $comMapperObj->getcustomboardPrimaryId($BoardId,$_POST['feedbackSubject' . $i]);
                                    if($_POST['feedbackChapter' . $i] != 'all') {
                                        $comment['mcq' . $i]['feedbackSubject'] = $_POST['feedbackSubject' . $i];
                                        $comment['mcq' . $i]['feedbackChapter'] = $_POST['feedbackChapter' . $i];
                                        $comment['mcq' . $i]['commentText'] = $_POST['feedbackTests' . $i];
                                        $comment['mcq' . $i]['commentType'] = 'mcq';
                                        $comment['mcq' . $i]['hidFeedCId'] = $_POST['hidFeedCId'];
                                        $comment['mcq' . $i]['selectDate'] = $_POST['selectDate'];
                                        $comment['mcq' . $i]['totalQuestions'] = 0;
                                        $comment['mcq' . $i]['rightAnswers'] = 0;
                                        $comment['mcq' . $i]['totalScore'] = 0;
                                        $comment['mcq' . $i]['custom_board_rack_id'] =$customBoardId;
                                        $mcqComment++;
                                    }
                                }
                            }
                        }    
                        if(isset($_POST['feedbackSubjective0']) && empty($_POST['feedbackSubjective0'])){
                                $_POST['feedbackSubjective0'] = 'No Comment';
                        }
                        //if (isset($_POST['feedbackSubjective' . $i]) && $_POST['feedbackSubjective' . $i] != '') {
                        if(isset($_POST['feedbackSubjectiveChapter0']) && $_POST['feedbackSubjectiveChapter0']=='all') {
                            if(isset($subjectivechapterdetail[$i]['id'])) {
                                if(isset($_POST['feedbackSubjective0']) && !empty($_POST['feedbackSubjective0'])) {
                                    $chapterId = $subjectivechapterdetail[$i]['id'];
                                    $end_mess = $_POST['feedbackSubjective0'];
                                    $subjective_subject_id = $_POST['feedbackSubjectiveSubject0'];
                                    $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                                    $customBoardId = $comMapperObj->getcustomboardPrimaryId($BoardId,$subjective_subject_id);
                                    $comment['subjective' . $i]['selectDate'] = $_POST['selectDate'];
                                    $comment['subjective' . $i]['feedbackSubject'] = $subjective_subject_id;
                                    $comment['subjective' . $i]['feedbackChapter'] = $chapterId;
                                    $comment['subjective' . $i]['commentText'] = $end_mess;
                                    $comment['subjective' . $i]['totalQuestions'] = 0;
                                    $comment['subjective' . $i]['rightAnswers'] = 0;
                                    $comment['subjective' . $i]['totalScore'] = 0;
                                    $comment['subjective' . $i]['commentType'] = 'subjective';
                                    $comment['subjective' . $i]['hidFeedCId'] = $_POST['hidFeedCId'];
                                    $comment['subjective' . $i]['custom_board_rack_id'] = $customBoardId;
                                    $subjectiveComment++;
                                }    
                            }
                        } else {
                            if(isset($_POST['feedbackSubjective' . $i]) && !empty($_POST['feedbackSubjective' . $i])) {
                                if($_POST['feedbackSubjectiveSubject' . $i] != 0 && $_POST['feedbackSubjectiveChapter' . $i] != 0) {
                                    $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                                    $customBoardId = $comMapperObj->getcustomboardPrimaryId($BoardId,$_POST['feedbackSubjectiveSubject' . $i]);
                                    if($_POST['feedbackSubjectiveChapter' . $i] != 'all') {
                                        $comment['subjective' . $i]['selectDate'] = $_POST['selectDate'];
                                        $comment['subjective' . $i]['feedbackSubject'] = $_POST['feedbackSubjectiveSubject' . $i];
                                        $comment['subjective' . $i]['feedbackChapter'] = $_POST['feedbackSubjectiveChapter' . $i];
                                        $comment['subjective' . $i]['commentText'] = $_POST['feedbackSubjective' . $i];
                                        $comment['subjective' . $i]['totalQuestions'] = 0;
                                        $comment['subjective' . $i]['rightAnswers'] = 0;
                                        $comment['subjective' . $i]['totalScore'] = 0;
                                        $comment['subjective' . $i]['commentType'] = 'subjective';
                                        $comment['subjective' . $i]['hidFeedCId'] = $_POST['hidFeedCId'];
                                        $comment['subjective' . $i]['custom_board_rack_id'] = $customBoardId;
                                        $subjectiveComment++;
                                    }
                                }
                            }
                        }
                    //}
                }
                //echo count($comment); echo '<pre>'; print_r($comment); exit;
                
                //echo '<pre>'; print_r($comment); exit;
               //|| $mcqComment==0 || $subjectiveComment==0 
               if(empty($comment) || count($comment) == 0) {
                   $error = 'All Fields are required.';
                   $_SESSION['feedback_error'] = $error;
               } else {
                if(isset($_POST['sent_email_to_parent']) && $_POST['sent_email_to_parent']=='1') {
                    $studentIdAndName = $this->params()->fromRoute('id', 0);
                    $studentIdAndNames = explode("-", $studentIdAndName);
                    $studentId = $studentIdAndNames[1];
                    $studentName = $studentIdAndNames[0];
                    $tableuser = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                    $ParentData = $tableuser->getParentData($studentId);    
                    $parentall = array();
                    
                    $filepath= __DIR__ . '../../../../view/mailer/';
                    $event = $this->getEvent();
                    $requestURL = $event->getRequest();
                    $router = $event->getRouter();
                    $uri = $router->getRequestUri();
                    $baseUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
                    
                    foreach ($ParentData as $parent) {
                        $parentArr = (array)$parent;
                        //$parentall[] = $parent;
                        $parentName = $parentArr['firstName'];
                        $feedbackType = 'OVER ALL';
                        $to = $parentArr['emailId'];
                        if(!empty($to)) {
                            $feedbackComment= $commentMentor['feedback'];
                            $filepath = $filepath.'childfeedbacktoparent.html';
                            $file_content = file_get_contents($filepath);
                            $regMessage = str_replace('{CHILD_NAME}', $studentName, $file_content);
                            $regMessage = str_replace('{PARENT_NAME}', $parentName, $regMessage);
                            $regMessage = str_replace('{MENTOR_NAME}', $mentorName, $regMessage);
                            $regMessage = str_replace('{FEEDBACK_TYPE}', $feedbackType, $regMessage);
                            $regMessage = str_replace('{FEEDBACK_COMMENT}', $feedbackComment, $regMessage);
                            $regMessage = str_replace('{FEEDBACK_DATE}', date('Y-m-d'), $regMessage);
                            $regMessage = str_replace('{SITE_URL}', $baseUrl , $regMessage);
                            $regSubject= "Child Feedback";
                            //$to = 'baljeet.singh@extramarks.com';
                            $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent');
                            $emailData = array("email_id" => $to, 'subject' => $regSubject, 'message' => $regMessage, 'mail_type' => 'useractivities', 'status' => 1);
                            $mailContentTable->addEmailContent($emailData);
                        }
                    }
                }
                //echo '<pre>'; print_r($comment); exit;
                
                // This function add the feedback coverage and schdule comments.
                if(empty($commentMentor['hidFeedCId'])) {
                    $_SESSION['feedback_success'] = 'Feedback is sent successfully';
                } else {
                    //$_SESSION['feedback_success'] = 'Feedback is updated successfully';
                    $_SESSION['feedback_success'] = 'Feedback is sent successfully';
                }
                $commentMentor['hidFeedCId']='';
                //echo $userId.'<pre>'; print_r($commentMentor).' === '.$commentMentor['hidFeedCId'].' '.$userId; exit;
                
                $feedbackId = $table->addComments($commentMentor, $commentMentor['hidFeedCId'],'',$userId);
                $updatecount=0;
                //echo $feedbackId; exit;
                foreach ($comment as $feedback) {
                    if(trim($feedback['commentText'])!='') {
                        $table = $this->getServiceLocator()->get('Assessment\Model\TfeedbackchaptersTable');
                        // This function add the comments of mutiple subjects and multiple chapters
                        if(empty($feedback['hidFeedCId'])) {
                            $feedback['hidFeedCId'] = $feedbackId;
                        }
                        //$feedback['hidFeedCId']='';
                        //$feedbackId='';
                        $addComments = $table->addComments($feedback, $feedback['hidFeedCId'], $feedbackId,'',$userId,$updatecount);
                        $updatecount++;
                    }
                }
                } //comment loop commented
            }
            
            $html = '';
            if (isset($_POST['student_id']) && $_POST['student_id'] != '') {
                $table = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
                // This function get the all students subjects of loged mentor
                $subjectsByStudents = $table->getAllStudents($userId, 'mentor', $_POST['student_id']);
                foreach ($subjectsByStudents as $subjects) {
                    //echo '<pre>'; print_r($subjects); exit;
                    $relationId  = $subjects->id;
                    $subjectId   = ($subjects->sub_id !='')?$subjects->sub_id:$subjects->subject_id;
                    $subjectname = $subjects->subject_name;
                    $classId     = ($subjects->class_ids !='')?$subjects->class_ids:$subjects->class_id;
                    $html.='<option value="' . $subjectname . '-/' . $subjectId . '-/' . $classId . '">' . $subjectname . '</option>';
                }
                echo $html;
                exit;
            } else {
                $addedStudentsss = array();
                $studentIdAndName = $this->params()->fromRoute('id', 0);
                $studentIdAndNames = explode("-", $studentIdAndName);
                $studentId = $studentIdAndNames[1];
                $boardName = $studentIdAndNames[2];
                $boardId = $studentIdAndNames[3];
                $className = $studentIdAndNames[4];
                $classId = $studentIdAndNames[5];
                $studentSubjectId = $studentIdAndNames[6];
                $tabtype  = @$studentIdAndNames[7];
                $url = $boardName . '-' . $boardId . '-' . $className . '-' . $classId;
                $table = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
                // This function get the all students of loged mentor
                $addedStudents = $table->getAllStudents($userId, 'mentor');
                
                foreach ($addedStudents as $key => $addedStudentss) {
                    
                    $subjectId = ($addedStudentss->subject_id !='')?$addedStudentss->subject_id:$addedStudentss->sub_id;
                    $subjectname = $addedStudentss->subject_name;
                    $classId = ($addedStudentss->class_id !='')?$addedStudentss->class_id:$addedStudentss->class_ids;
                    if (array_key_exists(str_replace(' ', '', $addedStudentss->name) . '-' . $addedStudentss->name, $addedStudentsss)) {
                        $addedStudentsss[str_replace(' ', '', $addedStudentss->name) . '-' . $addedStudentss->student_id][$key] = $subjectname . '-/' . $subjectId . '-/' . $classId;
                    } else {
                        $addedStudentsss[str_replace(' ', '', $addedStudentss->name) . '-' . $addedStudentss->student_id][$key] = $subjectname . '-/' . $subjectId . '-/' . $classId;
                    }
                    if($subjectId==$studentSubjectId && $addedStudentss->student_id==$studentIdAndNames[1]){
                        $relationId = $addedStudentss->id;
                    }
                }
                $userdetails = array();
                $userid = $userId;
                $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
                // This function get the child user details of loged user
                $user = $table->getuserdetails($userid);
                $user->buffer();
                foreach ($user as $userChildName) {
                    $userdetails = $userChildName;
                }
                //echo $relationId.' ';
                //echo '<pre>'; print_r($userdetails); exit;
                return new ViewModel(array(
                    'relationId' => $relationId,
                    'addedStudents' => $addedStudentsss,
                    'studentNameId' => $studentIdAndNames[0] . '-' . $studentIdAndNames[1],
                    'getuserdetails' => $userdetails,
                    'subjectId' => $studentSubjectId,
                    'url' => $url,
                    'className' => $className,
                    'tabtype'=>$tabtype,
                    'boardId'=>$boardId,
                    'classId'=>$classIds,
                    'errors' => $error
                ));
            }
        }
    } 
   
  public function mymentorsfeedbackAction() {
        
        $html = '';
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
            if ($auth->hasIdentity()) {
                $userObj = $auth->getIdentity();
                $userId = $userObj->getId();
            }  
           // echo '<pre>';print_r($_POST);die;
        if (isset($_POST['mentor_id']) && $_POST['mentor_id'] != '') {
            $table = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
            // This function get the all mentors of loged student.
            $subjectsByStudents = $table->getAllStudents($userId, 'student', $_POST['mentor_id']);
           
            foreach ($subjectsByStudents as $subjects) {
                //echo '<pre>'; print_r($subjects); exit;
                //$relationId = $addedMentorss->id;
                $relationId = $subjects->id;
                $subjectId = $subjects->sub_id;
                if(empty($subjectId)) {
                    $subjectId = $subjects->subject_id;
                }
                $subjectname = $subjects->subject_name;
                if(isset($subjects->class_ids)) {
                    $classId = $subjects->class_ids;
                } else {
                    $classId = $subjects->board_class_id;
                }
                $subject_id='';
                if(isset($_POST['subject_id'])) {
                    $subject_id = $_POST['subject_id'];
                }
                $selected='';
                if($subject_id==$subjectId) {
                    $selected="Selected";
                }
                $html.='<option '.$selected.' value="' . $subjectname . '-/' . $subjectId . '-/' . $classId . '">' . $subjectname . '</option>';
            }
            echo $html;
            exit;
        } else {
            $addedMentorsss = array();
            $mentorIdAndName = $this->params()->fromRoute('id', 0);
            $mentorIdAndNames = explode("-", $mentorIdAndName);
            
            $mentorId = $mentorIdAndNames[1];
            $customBoardId= $mentorIdAndNames[2];
            $mentorClass_id = $mentorIdAndNames[3];
            $mentorSubjectId = $mentorIdAndNames[4];
            $table = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
            // This function get the all mentors of loged student.
            $addedMentors = $table->getAllStudents($userId, 'student', '', $mentorSubjectId);
           //echo '<pre>';print_r($addedMentors);die;
            foreach ($addedMentors as $key => $addedMentorss) {
              //  echo "<pre />"; print_r($addedMentorss);
                $relationId  = $addedMentorss->id;
                
                $subjectId   = ($addedMentorss->sub_id !='')?$addedMentorss->sub_id:$addedMentorss->subject_id;
                $subjectname = $addedMentorss->subject_name;
                $classId     = ($addedMentorss->class_ids !='')?$addedMentorss->class_ids:$addedMentorss->class_id;
                 
                if (array_key_exists(str_replace(' ', '', $addedMentorss->name) . '-' . $addedMentorss->stud_mentor_id, $addedMentorsss)) {
                    $addedMentorsss[str_replace(' ', '', $addedMentorss->name) . '-' . $addedMentorss->stud_mentor_id][$key] = $subjectname . '-/' . $subjectId . '-/' . $classId;
                } else {
                    $addedMentorsss[str_replace(' ', '', $addedMentorss->name) . '-' . $addedMentorss->stud_mentor_id][$key] = $subjectname . '-/' . $subjectId . '-/' . $classId;
                }
            }
            
            //echo "<pre />"; print_r($addedMentorsss); exit;
            $userdetails = array();
            $userid = $userId;
            $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
            // This function get the child user detailes of loged user
            $user = $table->getuserdetails($userid);
            $user->buffer();
            foreach ($user as $userChildName) {
                $userdetails = $userChildName;
            }
           //echo "ddd===>". $relationId;exit;
            
            $viewModel = new ViewModel();
            $template = 'assessment/index/mymentorsfeedback.phtml';
            $viewModel->setTemplate($template)
                      ->setVariables(array(
                            'relationId' => $relationId,
                            'addedMentors' => $addedMentorsss,
                            'mentorNameId' => $mentorIdAndNames[0] . '-' . $mentorIdAndNames[1],
                            'getuserdetails' => $userdetails,
                            'subjectId' => $mentorSubjectId,
                            'classId' => $mentorClass_id,
                            'boardId'=>$customBoardId,
                          'subjectName' =>$subjectname,
                          'userId'=>$userId
                    ));
            return $viewModel;    
        }
    }
  
  public function ajaxfeedbackstudentAction()
  {
    $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
    $containerservice   = $this->getServiceLocator()->get('lms_container_service');
    if ($auth->hasIdentity()) {
        $userObj = $auth->getIdentity();
        $userId = $userObj->getId();
    }     
    $subjectdetails = array();
    $chapterdetails = array();
    $date = '';
    
        if($_POST['personType'] == 'mentor') {
            $mid = @$userId;
            $sid = $_POST['id'];
        }else{
            $sid = @$userId;
            $mid = $_POST['id'];
        }
       $tableuser = $this->getServiceLocator()->get('Assessment\Model\UserTable');
       $ParentData = $tableuser->getParentData($sid);  
       $totalParent = 0;
       foreach($ParentData as $parent) {
           $totalParent++;
       }
       
       $subjectDetails = $containerservice->getChildList($_POST['class_id']); 
       $allChapters = array();
        foreach($subjectDetails as $key=>$chapter)
        {
            $subjectdetails[$key]['subject_name'] = $chapter->getRackName()->getName();
            $subjectdetails[$key]['id']           = $chapter->getRackId();
            $subjectdetails[$key]['rack_type_id'] = $chapter->getRackType()->getRackTypeId();
            $chaptersbysubject = $containerservice->getChaptersFromSubject($chapter->getRackId());
            $allChapters[$chapter->getRackId()] = $chaptersbysubject;
        }
        //echo $subids;exit;
        if(isset($_POST['subjectId'])) {
            $chaptersbysubject = $containerservice->getChaptersFromSubject($_POST['subjectId']);
            if(!isset($allChapters[$_POST['subjectId']])) {
                $allChapters[$_POST['subjectId']] = $chaptersbysubject;
            }
        }
        
        $count = 0;
        $class_id   = $_POST['class_id'];
        if(isset($_POST['className'])) {
            $className   = $_POST['className'];
        } else {
            $className = '';
        }
        if(isset($_POST['boardId'])) {
            $boardId = $_POST['boardId'];
        } else {
            $boardId = 124;
        }
        $boardClasses = $containerservice->getCustomClassList($boardId);
        $i=1;
        $lowerClass = false;
        foreach($boardClasses as $class) {
            //if($class['id']==$class_id && $className==$class['name']) {
            if($class['id']==$class_id) {
                $lowerClass = true;
            }
            if($i==5){
                break;
            }
            $i++;
        }
        
        if(isset($_POST['subjectId']) && !empty($_POST['subjectId'])) {
            $subject_id = $_POST['subjectId'];
        } else {
            $subject_id = $subjectdetails[0]['id'];
        }
        // echo '<pre>'; print_r($allChapters); exit;
        // This function get the chapters of defalut first subject of above all subjects.
        $chapterDetails = $containerservice->getChildList($subject_id);
        $subChapters = array();
        $subids='';
        $chapters = array();
        $groupbyset = true;
        $chapterAndSubChapters = array();
        foreach($chapterDetails as $key=>$chdetail)
        {
            $chapterdetail[$key]['chapter_name'] = $chdetail->getRackName()->getName();
            $chapterdetail[$key]['id']           = $chdetail->getRackId();
            $chapterdetail[$key]['rack_type_id'] = $chdetail->getRackType()->getRackTypeId();
            $subchapterDetails = $containerservice->getChildList($chdetail->getRackId());
            $newarray = array();
            $subChaptersArr = array();
            foreach($subchapterDetails as $keyy => $subchdetail) {
               $newarray[$keyy]['chapter_name'] = $subchdetail->getRackName()->getName();
               $newarray[$keyy]['id'] = $subchdetail->getRackId();
               $newarray[$keyy]['rack_type_id'] = $subchdetail->getRackType()->getRackTypeId();
               $chapters[] = $subchdetail->getRackId();
               $subChaptersArr[] = $subchdetail->getRackId();
            }
            $chapterAndSubChapters[$chdetail->getRackId()] = $subChaptersArr;
            if(!empty($newarray)) {
                $subChapters[$chdetail->getRackId()] = $newarray;
            }
            $subids .= $chdetail->getRackId().',';
            $chapters[] = $chdetail->getRackId();
        }
        $mcqChapters = array();
        $mcqAllChapters = $allChapters;
        $mcqTestChapterIds = array();
        if(isset($_REQUEST['personType']) && $_REQUEST['personType']=='mentor') {
            $lmsService = $this->getServiceLocator()->get('lms_container_service');
            $testPerformance = $lmsService->getTestPerformance($sid, $chapters, 'single', true, $groupbyset = true);
            //$testPerformance = $lmsService->getTestPerformance($sid, $chapters, "", true);
            $testPerformanceArr = array();
            foreach ($testPerformance as $testK => $testV) {
                $chapterId = $testV[0]['chapterSetId'][0]['chapterId'];
                //echo $chapterId; exit;
                foreach($chapterdetail as $key => $chapter) {
                    if($chapter['id']==$chapterId) {
                        $mcqTestChapterIds[]=$chapterId;
                        $chapterdetail[$key]['enable']=1;
                    } else if(in_array($chapterId,$chapterAndSubChapters[$chapter['id']])) {
                        $mcqTestChapterIds[]=$chapterId;
                        $chapterdetail[$key]['enable']=1;
                    }
                }
                foreach($mcqAllChapters[$_POST['subjectId']] as $mcqKey => $mcqchapter) {
                    if($mcqchapter['rackId']==$chapterId) {
                        $mcqTestChapterIds[]=$chapterId;
                        $mcqAllChapters[$_POST['subjectId']][$mcqKey]['enable']=1;
                    } else if(in_array($chapterId,$chapterAndSubChapters[$mcqchapter['rackId']])) {
                        $mcqTestChapterIds[]=$chapterId;
                        $mcqAllChapters[$_POST['subjectId']][$mcqKey]['enable']=1;
                    }
                }
            }
            
            foreach($mcqAllChapters[$_POST['subjectId']] as $mcqKey => $mcqchapter) {
                if(!isset($mcqAllChapters[$_POST['subjectId']][$mcqKey]['enable'])) {
                    $mcqAllChapters[$_POST['subjectId']][$mcqKey]['enable']=0;
                }
            }
            
            foreach($chapterdetail as $key => $chapter) {
                if(!isset($chapter['enable'])) {
                    $chapterdetail[$key]['enable']=0;
                }
            }
            $studentPaperList = $this->getMentorAssignPaperTable()->getMentorAssignedPaperList('', $sid);
            $subjectivechapterdetail = $chapterdetail;
            $subjectivePaper = false;
            foreach($studentPaperList as $paper) {
                if($subject_id==$paper->paperSubjectId && $class_id==$paper->paperClassId && $paper->paperAttemptStatus=='yes') {
                    $subjectivePaper = true;
                    break;
                }
            }
            foreach($subjectivechapterdetail as $subjectiveKey => $subjectiveChapter) {
                if($subjectivePaper) {
                    $subjectivechapterdetail[$subjectiveKey]['enable']=1;
                } else {
                    $subjectivechapterdetail[$subjectiveKey]['enable']=0;
                }
            }
            $subjectiveAllChapters = $allChapters;
            if(isset($subjectiveAllChapters[$_POST['subjectId']])) {
                foreach($subjectiveAllChapters[$_POST['subjectId']] as $kchapter => $chapter) {
                    if($subjectivePaper) {
                        $subjectiveAllChapters[$_POST['subjectId']][$kchapter]['enable']=1;
                    } else {
                        $subjectiveAllChapters[$_POST['subjectId']][$kchapter]['enable']=0;
                    }
                }
            }
            
        }
        
        $value = '';
        $table = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
        // This function check the status of student and mentor request.
        $count = $table->checkStatus($sid, $mid);
        if ($count->count()) {
            $table = $this->getServiceLocator()->get('Assessment\Model\TmentorfeedbackTable');
            $totalDatess = array();
            
            $totalCommentsDates = $table->gettotalCommentsDates($_POST['id'], $_POST['personType'], $_POST['subjectId'],$userId,'all');
            
            foreach ($totalCommentsDates as $key => $totalDates) {
                $newDate = date('Y-n-j', strtotime($totalDates->capture_date));
                $totalDatess[] = $newDate;
            }
            //echo '<pre>'; print_r($totalDatess); exit;
            if(isset($_POST['value'])) {
                $value = date('Y-m-d', strtotime($totalDatess[$_POST['value']]));
             } else {
                $value = 0;
             }
            $feedbackComment = array();
            $mcqAndSubjective = array();
            $mcqAndSubChapters = array();
            $getfeedbackComment = '';
            $getfeedbackCommentStatus = '';
            $getfeedbackCommentFeedId = '';
            $totalComments = '';
            $changeStatus = 0;
            if(isset($_POST['date'])){
                $feedbackDate = $_POST['date'];
            } else {
                $feedbackDate = date('Y-m-d');
            }
            $mentortable = $this->getServiceLocator()->get('Assessment\Model\TmentorfeedbackTable');
            $feedbackRow = $mentortable->getfeedbackRow($_POST['id'],$feedbackDate,'all',$_POST['personType'],$_POST['subjectId'],$userId);
            //echo '<pre>'; print_r($feedbackRow); exit;
            $feedbackId=0;
            foreach($feedbackRow as $feed) {
                $feedbackRow = (array)$feed;
                $feedbackId = $feedbackRow['feedback_id'];
                break;
            }
            if (isset($_POST['date'])) {
                $date = date('Y-m-d', strtotime($_POST['date']));
                if (count((array) $subjectdetails) != 1) {
                    $sId = '';
                    foreach ($subjectdetails as $subjectdetailss) {
                        
                        if ($subjectdetailss['rack_type_id'] == 7) {
                            $sId.=$subjectdetailss['id'] . ',';
                        } else {
                            $sId.=$subjectdetailss['id'] . ',';
                        }
                    }
                    if(!empty($subids)) {
                        $sId = $subids.$_POST['subjectId'];
                    }
                    
                    $feedbackComments = $table->getfeedbackComment($_POST['id'], $date, 'value', $_POST['personType'], rtrim($sId, ','),$userId,$feedbackId);
                        
                } else {
                    // This function get the feed back of student based on particular date.
                    $feedbackComments = $table->getfeedbackComment($_POST['id'], $date, 'date', $_POST['personType'], $_POST['subjectId'],$userId,$feedbackId);
                }
            } else {
                if (count((array) $subjectdetails) != 1) {
                    $sId = '';
                    foreach ($subjectdetails as $subjectdetailss) {
                        if ($subjectdetailss['id'] == 7) {
                            $sId.=$subjectdetailss['id'] . ',';
                        } else {
                            $sId.=$subjectdetailss['id'] . ',';
                        }
                    }
                    // This function get the feed back of student based on previous and next dates.
                    if(!empty($subids)) {
                        $sId = $subids.$_POST['subjectId'];
                    }
                    
                    $feedbackComments = $table->getfeedbackComment($_POST['id'], $value, 'value', $_POST['personType'], rtrim($sId, ','),$userId,$feedbackId);
                    
                    //echo '<pre>'; print_r($feedbackComments); exit;
                } else {
                    // This function get the feed back of student based on previous and next dates.
                    $feedbackComments = $table->getfeedbackComment($_POST['id'], $value, 'value', $_POST['personType'], $_POST['subjectId'],$userId,$feedbackId);
                    
                }
            }
            
            //echo '<pre>'; print_r($feedbackComments); exit;
            if ($feedbackComments->count()) {
                $mcqAndSubjective = $feedbackComments->buffer();
                $feedbackComment = $feedbackComments->current();
                if ($_POST['personType'] == 'student') {
                    // This function change the status of notification feed back after see the student. 
                    $changeStatus = $table->changeStatus($feedbackComment->feedback_id);
                }
            }
            //echo count($feedbackComments); echo '<pre>'; print_r($feedbackComment); exit;
            if ($feedbackComments->count()) {
                $ctable = $this->getServiceLocator()->get('Assessment\Model\TfeedbackscommentsTable');
                //This function get the student comment on mentor feed back.
                $getfeedbackComments = $ctable->getComment($feedbackComments->current()->feedback_id);
                if ($getfeedbackComments->count()) {
                    $curgetfeedbackComments = $getfeedbackComments->current();                   
                    $getfeedbackComment = $curgetfeedbackComments->comment_text;
                    $getfeedbackCommentStatus = $curgetfeedbackComments->status;
                    $getfeedbackCommentFeedId = $curgetfeedbackComments->feed_comment_id;
                }
            }
            //echo '<pre>'; print_r($getfeedbackComments); exit;
            if ($_POST['personType'] == 'mentor') {
                    
                    $chaptersTotal = count((array)$chapterdetail);
                    
                //}
            } else {
                $chaptersTotal = 0;
            }
            if ($_POST['personType'] == 'mentor') {
                if (count($mcqAndSubjective) != 0) {
                    //$chaptertable = $this->getServiceLocator()->get('Container\Model\TmainFactory');
                    $chaptertable = array();
                }
            }
            //echo '<pre>'; print_r($chapterdetail); exit;
           //echo '<pre>';print_r($subChapters); exit;
            
            if(!empty($subChapters) && count($subChapters) > 0) {
                $subjectdetails = $chapterdetail;
                $mcqsubjectdetails = $chapterdetail;
                if(isset($subjectivechapterdetail)) {
                    $subjectivesubjectdetails = $subjectivechapterdetail;
                }
                $chapterdetail = $subChapters;
                $chep=0;
                //$chepterlength = 0;
                
                foreach($chapterdetail as $ckey => $chaptersDetail){
                    foreach($chaptersDetail as $chapter){
                        if($chep==0){
                            //$chepterlength++;
                        }
                    }
                    $chep++;
                }
                $chapterCount = 0;
                    foreach($chapterdetail as $chapter) { 
                        $cCount = count($chapter);
                        if($chapterCount < $cCount) {
                           $chapterCount = $cCount;
                        }
                    }
                    //echo $chapterCount; exit;
                if(empty($feedbackComment)) {
                    
                    //echo '<pre>'; print_r($subjectdetails); exit;
                    if(isset($subjectdetails) && !empty($subjectdetails)) {
                        foreach($subjectdetails as $skey => $subjectdetail) {
                                $subjectname = $subjectdetail['chapter_name'];
                                $subjectId = $subjectdetail['id'];
                                if($skey==0){
                                    $subSubjectId = $subjectId;
                                    $chapterdetail = $chapterdetail[$subjectId];
                                }
                        }                       
                    }
                    //$chaptersTotal = count($chapterdetail);
                } 
                $chaptersTotal = $chapterCount;
                
                $subjectivechapterdetail = $chapterdetail;
                foreach($subjectivechapterdetail as $subjectiveKey => $subjectiveChapter) {
                    if(isset($subjectivePaper) && $subjectivePaper) {
                        $subjectivechapterdetail[$subjectiveKey]['enable']=1;
                    } else {
                        $subjectivechapterdetail[$subjectiveKey]['enable']=0;
                    }
                }
                foreach($chapterdetail as $kchap => $chap) {
                    if(isset($chap['id'])) {
                        if(isset($chap['id']) && in_array($chap['id'],$mcqTestChapterIds)){
                            $chapterdetail[$kchap]['enable'] = 1;
                        } else{
                            $chapterdetail[$kchap]['enable'] = 0;
                        }
                    } else {
                        foreach($chap as $ckey => $chapter) {
                        //echo $chapter['id'].'<pre>'; print_r($chapter); exit;
                            if(isset($chapter['id']) && in_array($chapter['id'],$mcqTestChapterIds)){
                                $chapterArr=array('chapter_name'=>$chapter['chapter_name'],'enable'=>1,'rack_type_id'=>$chapter['rack_type_id'],'id'=>$chapter['id']);
                                $chapterdetail[$kchap][$ckey] = $chapterArr;
                            } else {
                                $chapterArr=array('chapter_name'=>$chapter['chapter_name'],'enable'=>0,'rack_type_id'=>$chapter['rack_type_id'],'id'=>$chapter['id']);
                                $chapterdetail[$kchap][$ckey] = $chapterArr;
                            }
                        }
                    }
                }
            } else {
                $subjectdetails = array();
            }
            //echo '<pre>'; print_r($chapterdetail); exit;
            if(empty($subjectiveAllChapters)) {
                $subjectiveAllChapters=$allChapters;
            }
            if(empty($subjectivechapterdetail)) {
                $subjectivechapterdetail = $chapterdetail;
            }
            
            if($lowerClass === true) {
                foreach($chapterdetail as $chapKey => $chapter) {
                    $chapterdetail[$chapKey]['enable']=0;
                }
            }
            /*foreach($subjectdetails as $subject) {
                echo '<pre>'; print_r($subject); exit;
            }*/
            if(empty($mcqsubjectdetails)) {
                $mcqsubjectdetails = $subjectdetails;
            }
            if(empty($subjectivesubjectdetails)) {
                $subjectivesubjectdetails = $subjectdetails;
            }
            
            //echo '<pre>'; print_r($_POST); exit;
            if(count($mcqAndSubjective) >0){
                foreach($mcqAndSubjective as $mcqAndsubjective) {
                    //echo '<pre>'; print_r($mcqAndsubjective); exit;
                    $select_subject_id = $mcqAndsubjective->subject_id;
                }
                if(isset($subjectivechapterdetail[$select_subject_id])) {
                    $subjectivechapterdetail = $subjectivechapterdetail[$select_subject_id];
                    foreach($subjectivechapterdetail as $subjectiveKey => $subjectiveChapter) {
                        if(isset($subjectivePaper) && $subjectivePaper) {
                            $newsubchapter = array('chapter_name'=>$subjectiveChapter['chapter_name'],'id'=>$subjectiveChapter['id'],'rack_type_id'=>$subjectiveChapter['rack_type_id'],'enable'=>'1');
                            $subjectivechapterdetail[$subjectiveKey]=$newsubchapter;
                        } else {
                            $newsubchapter = array('chapter_name'=>$subjectiveChapter['chapter_name'],'id'=>$subjectiveChapter['id'],'rack_type_id'=>$subjectiveChapter['rack_type_id'],'enable'=>'0');
                            $subjectivechapterdetail[$subjectiveKey]=$newsubchapter;
                        }
                    }
                }
                
                if(isset($chapterdetail[$select_subject_id])) {
                    $chapterdetail = $chapterdetail[$select_subject_id];
                    if(isset($testPerformance) && count($testPerformance) >0){
                        foreach ($testPerformance as $testK => $testV) {
                            $chapterId = $testV[0]['chapterSetId'][0]['chapterId'];
                            foreach($chapterdetail as $key => $chapter) {
                                if($chapter['id']==$chapterId) {
                                    $chapterdetail[$key]['enable']=1;
                                }
                            }
                        }
                    } else {
                        foreach($chapterdetail as $key => $chapter) {
                            if(isset($chapterdetail[$key]['enable']) && $chapterdetail[$key]['enable']=='1'){
                                $newchapter = array('chapter_name'=>$chapter['chapter_name'],'id'=>$chapter['id'],'rack_type_id'=>$chapter['rack_type_id'],'enable'=>'1');
                                $chapterdetail[$key]=$newchapter;
                            } else {
                                $newchapter = array('chapter_name'=>$chapter['chapter_name'],'id'=>$chapter['id'],'rack_type_id'=>$chapter['rack_type_id'],'enable'=>'0');
                                $chapterdetail[$key]=$newchapter;
                            }
                        }
                    }
                }
            } else {
                $select_subject_id = $_POST['subjectId'];
                if(isset($subjectivechapterdetail[$select_subject_id])) {
                    $subjectivechapterdetail = $subjectivechapterdetail[$select_subject_id];
                    foreach($subjectivechapterdetail as $subjectiveKey => $subjectiveChapter) {
                        if(isset($subjectivePaper) && $subjectivePaper) {
                            $newsubchapter = array('chapter_name'=>$subjectiveChapter['chapter_name'],'id'=>$subjectiveChapter['id'],'rack_type_id'=>$subjectiveChapter['rack_type_id'],'enable'=>'1');
                            $subjectivechapterdetail[$subjectiveKey]=$newsubchapter;
                        } else {
                            $newsubchapter = array('chapter_name'=>$subjectiveChapter['chapter_name'],'id'=>$subjectiveChapter['id'],'rack_type_id'=>$subjectiveChapter['rack_type_id'],'enable'=>'0');
                            $subjectivechapterdetail[$subjectiveKey]=$newsubchapter;
                        }
                    }
                } else {
                    foreach($subjectivechapterdetail as $subjectiveKey => $subjectiveChapter) {
                        if(isset($subjectivePaper) && $subjectivePaper) {
                            $newsubchapter = array('chapter_name'=>$subjectiveChapter['chapter_name'],'id'=>$subjectiveChapter['id'],'rack_type_id'=>$subjectiveChapter['rack_type_id'],'enable'=>'1');
                            $subjectivechapterdetail[$subjectiveKey]=$newsubchapter;
                        } else {
                            $newsubchapter = array('chapter_name'=>$subjectiveChapter['chapter_name'],'id'=>$subjectiveChapter['id'],'rack_type_id'=>$subjectiveChapter['rack_type_id'],'enable'=>'0');
                            $subjectivechapterdetail[$subjectiveKey]=$newsubchapter;
                        }
                    }
                }
                
                if(isset($chapterdetail[$select_subject_id])) {
                    $chapterdetail = $chapterdetail[$select_subject_id];
                    if(isset($testPerformance) && count($testPerformance) >0){
                        foreach ($testPerformance as $testK => $testV) {
                            $chapterId = $testV[0]['chapterSetId'][0]['chapterId'];
                            foreach($chapterdetail as $key => $chapter) {
                                if($chapter['id']==$chapterId) {
                                    $chapterdetail[$key]['enable']=1;
                                }
                            }
                        }
                    } else {
                        foreach($chapterdetail as $key => $chapter) {
                            if(isset($chapterdetail[$key]['enable']) && $chapterdetail[$key]['enable']=='1'){
                                $newchapter = array('chapter_name'=>$chapter['chapter_name'],'id'=>$chapter['id'],'rack_type_id'=>$chapter['rack_type_id'],'enable'=>'1');
                                $chapterdetail[$key]=$newchapter;
                            } else {
                                $newchapter = array('chapter_name'=>$chapter['chapter_name'],'id'=>$chapter['id'],'rack_type_id'=>$chapter['rack_type_id'],'enable'=>'0');
                                $chapterdetail[$key]=$newchapter;
                            }
                        }
                    }
                } else {
                    if(isset($testPerformance) && count($testPerformance) >0){
                        foreach ($testPerformance as $testK => $testV) {
                            $chapterId = $testV[0]['chapterSetId'][0]['chapterId'];
                            foreach($chapterdetail as $key => $chapter) {
                                if($chapter['id']==$chapterId) {
                                    $chapterdetail[$key]['enable']=1;
                                }
                            }
                        }
                    } else {
                        foreach($chapterdetail as $key => $chapter) {
                            if(isset($chapterdetail[$key]['enable']) && $chapterdetail[$key]['enable']=='1'){
                                $newchapter = array('chapter_name'=>$chapter['chapter_name'],'id'=>$chapter['id'],'rack_type_id'=>$chapter['rack_type_id'],'enable'=>'1');
                                $chapterdetail[$key]=$newchapter;
                            } else {
                                $newchapter = array('chapter_name'=>$chapter['chapter_name'],'id'=>$chapter['id'],'rack_type_id'=>$chapter['rack_type_id'],'enable'=>'0');
                                $chapterdetail[$key]=$newchapter;
                            }
                        }
                    }
                }
            }
            //echo '<pre>'; print_r($chapterdetail); exit;
            $result = new ViewModel(array(
                'feedbackComment' => $feedbackComment,
                'mcqAndSubjective' => $mcqAndSubjective,
                'totalComments' => count($totalDatess),
                'getfeedbackComment' => $getfeedbackComment,
                'getfeedbackCommentStatus' => $getfeedbackCommentStatus,
                'getfeedbackCommentFeedId' => $getfeedbackCommentFeedId,
                'value' => $value,
                'page' => $_POST['page'],
                'totalDates' => json_encode($totalDatess),
                'mcqSubjectDetails' => $mcqsubjectdetails,
                'subjectiveSubjectDetails' => $subjectivesubjectdetails,
                'subjectdetails' => $subjectdetails,
                'subjectivechapterdetails' => $subjectivechapterdetail,
                'mcqchapterdetails' => $chapterdetail,
                'chapterdetails' => $chapterdetail,
                'postDate' => $date,
                'totalChapters' => $chaptersTotal,
                'changeStatus' => $changeStatus,
                //'mcqAndSubChapters' => $mcqAndSubChapters,
                'mcqAllChapters' => $mcqAllChapters,
                'subjectiveAllChapters' => $subjectiveAllChapters,
                'allChapters' => $allChapters,
                'subject' => $_POST,
                'parentCount' => $totalParent
            ));
            $result->setTerminal(true);
            return $result;
        } else {
            $result = new \Zend\View\Model\JsonModel(array(
                'output' => 'noRelation',
            ));
            return $result;
        }
   }
   
    public function ajaxFeedbackStudentCommentsAction() {
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        $containerservice   = $this->getServiceLocator()->get('lms_container_service');
        if ($auth->hasIdentity()) {
            $userObj = $auth->getIdentity();
            $userId = $userObj->getId();
            $_SESSION['user']['userId'] = $userId;
        } 
        if (isset($_POST['feedId']) && $_POST['feedId'] != '') {
            $ctable = $this->getServiceLocator()->get('Assessment\Model\TfeedbackscommentsTable');
            // This function change the status of comment(student send the comment to mentors feed back(notification))  
            $changeStatus = $ctable->changeStatus($_POST['feedId']);
        } else if (isset($_POST['feedback_id']) && $_POST['feedback_id'] != '') {
            $table = $this->getServiceLocator()->get('Assessment\Model\TfeedbackscommentsTable');
              //update alredy existing comment for this feedback           
            $updatecomment = $table->update($_POST['feedback_id'],'2');
            
            // This function add the student comment on metors feed back.
            $addfeedbackComment = $table->addComment($_POST['feedback_id'], $_POST['comment']);
        }
        $result = new \Zend\View\Model\JsonModel(array(
            'output' => 'sucusses',
        ));
        return $result;
    }
   
   public function ajaxstudentmentorqueryAction()
   {
     $relationId= '';
     $commentTable = $this->getServiceLocator()->get('Notification\Model\TquestionTable');
     $assignTable = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
     $currentPage = 1;
     $resultsPerPage = 2;
     $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
     if($auth->hasIdentity()) 
     {
         $userObj = $auth->getIdentity();
         $userId = $userObj->getId();
      }     
     $ids = '';
            if( $_POST['personType'] == 'mentor') {
                   // get id using student mentor relation 
                   $ids .= "'" . $_POST['id'] . "'" . ",";
                   $ids .= "'" . $userId . "'" . ",";   
                   $queryuserId = $_POST['id'];
                   $type = $_POST['personType'];
                   $userRowSet = $assignTable->getAll($userId, $type);
                   foreach($userRowSet as $userRow){
                       $ids .= "'" . $userRow->mentor_id . "'" . ",";                   
                   }
                   $tIdss = rtrim($ids, ","); 
            }
            if($_POST['personType'] == 'student') {
                $ids .= "'" . $userId . "'" . ",";
                $ids .= "'" . $_POST['id'] . "'" . ",";
                $queryuserId = $userId;
                $userRowSet = $assignTable->getAll($userId, $_POST['personType']);
                foreach($userRowSet as $userRow){
                       $ids .= "'" . $userRow->mentor_id . "'" . ",";             
                }
                $tIdss = rtrim($ids, ",");
            }
            $relationId = $_POST['relationId'];            
            $totalCount = $commentTable->totalCount($tIdss,'','','','','','','',$relationId,'mentor');                
            // Get total comments and total questions based on All group members of loged user
            $paginator = $commentTable->getQestions($tIdss, '', '', '', '', '', $currentPage, $resultsPerPage,$relationId,'mentor');
	
        
            $paginator->buffer();
            
            
            $viewModel = new ViewModel();
            $template = 'assessment/index/ajax-student-mentor-query.phtml';
            $viewModel->setTemplate($template)
                      ->setVariables(array(
                        'persontype'   => $_POST['personType'],
                        'getQuestions' => $paginator,
                        'countComments' => $paginator,
                        'countposts' => $totalCount,
                        'currentPage' => $currentPage,
                        'resultsPerPage' => $resultsPerPage,
                        'relationId' =>  $relationId,
                        'queryuserId' => $queryuserId,  // user whose query to b checked
                    ));
            $viewModel->setTerminal(true);
            return $viewModel;
 
          
        }  
 
   public function ajaxUploadsDownloadsAction() 
   {
        $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
        $containerservice   = $this->getServiceLocator()->get('lms_container_service');
        if($auth->hasIdentity()) 
         {
           $userObj = $auth->getIdentity();
           $userId = $userObj->getId();
         } 
      
        $upSubId = '';
        if ($_POST['personType'] == 'mentor') {
            $mid = $userId;
            $sid = $_POST['id'];
            $upSubId = $sid;
        } else {
            $sid = $userId;
            $mid = $_POST['id'];
            $upSubId = $mid;
        }
        $allUploadsAndDown = array();
        $table = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
        // This function check the mentor and student relation ship between loged mentor or student and particular student or mentor
        $count = $table->checkStatus($sid, $mid);
        if (count($count->buffer()) != 0) {
            $allUploads = array();
            $alldownloads = array();
            $subjectdetails = array();
            $chapterdetails = array();
            if ($_POST['personType'] == 'student') {
                $id = $_POST['id'];
            } else {
                $id = $userId;
            }
            //$sbjectstable = $this->getServiceLocator()->get('Container\Model\TmainFactory');
           
               // $subjectdetailss = $sbjectstable->getSubjectsByClassId($_POST['class_id'], $_POST['subjectName'])->toArray();
              //  $subjectdetails = $this->arrayToObject($subjectdetailss);
            $subjectDetails = $containerservice->getChildList($_POST['class_id']); 
           
            foreach($subjectDetails as $key=>$chapter){
                 $subjectdetails[$key]['subject_name'] = $chapter->getRackName()->getName();
                 $subjectdetails[$key]['id']           = $chapter->getRackId();
                 $subjectdetails[$key]['rack_type_id'] = $chapter->getRackType()->getRackTypeId();
            }
           
             $count = 0;
             $subject_id = $subjectdetails[0]['id'];
               // This function get the chapters of defalut first subject of above all subjects.
             $chapterDetails = $containerservice->getChildList($subject_id);   
            foreach($chapterDetails as $key=>$chdetail)
            {
                $chapterdetail[$key]['chapter_name'] = $chdetail->getRackName()->getName();
                $chapterdetail[$key]['id']           = $chdetail->getRackId();
                $chapterdetail[$key]['rack_type_id'] = $chdetail->getRackType()->getRackTypeId();
            }
           
            if (count($subjectdetails)) {
                $subId = '';
                foreach ($subjectdetails as $subjectdetailss) {
                    if ($subjectdetailss['rack_type_id'] == 7) {
                        $subId.=$subjectdetailss['id'] . ',';
                    } else {
                        $subId.=$subjectdetailss['id'] . ',';
                    }
                }
                $table = $this->getServiceLocator()->get('Assessment\Model\TuploadsdownloadsTable');
                // This function get the all uploads based on subject id.
                $allUploadsAndDown = $table->getall($_POST['personType'], $_POST['type'], $upSubId, rtrim($subId, ','),$userId);
            } else {
                // This function get the all uploads based on subject id.
                $allUploadsAndDown = $table->getall($_POST['personType'], $_POST['type'], $upSubId, $_POST['subjectId'],$userId);
            }
            if ($_POST['type'] == 'up') {
                $allUploads = $allUploadsAndDown;
            } else {
                $alldownloads = $allUploadsAndDown;
            }
            //echo "<pre />";  print_r($allUploads); exit;      
            $result = new ViewModel(array(
                'allUploads' => $allUploads,
                'alldownloads' => $alldownloads,
                'type' => $_POST['type'],
                'personType' => $_POST['personType'],
                'subjectdetails' => $subjectdetails,
                'chapterdetails' => $chapterdetail,
                'boardId' => $_POST['boardId'],
            ));
            $result->setTerminal(true);
            return $result;
        } else {
            $result = new JsonModel(array(
                'output' => 'noRelation',
            ));
            return $result;
        }
    }
    
  public function ajaxUploadsAction() 
  {
            $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
            $containerservice   = $this->getServiceLocator()->get('lms_container_service');
            if($auth->hasIdentity()) 
            {
                    $userObj = $auth->getIdentity();
                    $userId = $userObj->getId();
            }
            $allUploads = array();
            $id = '';
            if(isset($_POST['uploadchapters']) && $_POST['uploadchapters'] != "") {
            
            if($_POST['hidpersonType'] == 'mentor') {
                   $mid = $userId;
                   $sid = $_POST['hid_id'];
                   $id = $mid . '-' . $sid;
                   $upSubId = $sid;
            } else {
               $sid = $userId;
               $mid = $_POST['hid_id'];
               $id = $sid . '-' . $mid;
               $upSubId = $mid;
            }        
            $event = $this->getEvent();
            $requestURL = $event->getRequest();
            $router = $event->getRouter();
            $uri = $router->getRequestUri();
            $baseUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
            
            $table = $this->getServiceLocator()->get('Assessment\Model\TuploadsdownloadsTable');
            if (isset($_FILES['myfile']['name']) && $_FILES['myfile']['name'] != '') {
                if(strlen($_FILES['myfile']['name']) > 100) {
                    echo 'strlen'; exit; 
                }
                // This function check the uploaded file already exists or not
                $checkDetailes = $table->checkDetailes($_POST, $_FILES,$userId);
                
                if (count($checkDetailes->buffer()) == 0) {
                    // This function add the uploded file after checking.
                    $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                    $customBoardRackId = $comMapperObj->getcustomboardPrimaryId($_POST['boardId'],$_POST['uploadchapters']); 
                    $_POST['customBoardRackID']=$customBoardRackId;
                    $extention = explode('.', str_replace(' ', '', $_FILES['myfile']['name']));           
                    $addedStudents = $table->addDetails($_POST, $_FILES['myfile']['name'],$userId);
                    $uniqueId = $addedStudents;
                    $name = $uniqueId . '.' . $extention[1];
                    //$path = $_SERVER['DOCUMENT_ROOT']."/uploads/downloads/" . $id . "/";
                    //$path = $baseUrl."/uploads/downloads/" . $id . "/";
                    $path = "/uploads/downloads/";
                    //$path = "http://10.1.9.99/uploads/";
                    //$baseUrl.
                    
                    //echo $_FILES['myfile']['tmp_name'].' ========== '.$path.' ================== '.$id.' ========= '.$name; exit;
                    $fileUploaded = $this->ftpFileUploaded($_FILES['myfile']['tmp_name'],$path,$id,$name);
                } else {
                    echo 'exist'; exit;
                }
            } else {
                echo 'nofile'; exit;
            }
           
                // This function get the subject(s) based on class id and parent subject name
                $subjectDetails = $containerservice->getChildList($_POST['classIdHid']); 
           
            foreach($subjectDetails as $key=>$chapter){
                 $subjectdetails[$key]['subject_name'] = $chapter->getRackName()->getName();
                 $subjectdetails[$key]['id']           = $chapter->getRackId();
                 $subjectdetails[$key]['rack_type_id'] = $chapter->getRackType()->getRackTypeId();
            }
            if(count($subjectdetails)) {
                $subId = '';
                foreach ($subjectdetails as $subjectdetailss) {
                    if ($subjectdetailss['rack_type_id'] == 7) {
                        $subId.=$subjectdetailss['id'] . ',';
                    } else {
                        $subId.=$subjectdetailss['id'] . ',';
                    }
                }
               
                // This function get the all uploads based on subject id.
                $allUploads = $table->getall($_POST['hidpersonType'], 'up', $upSubId, rtrim($subId, ','),$userId);
            } else {
                // This function get the all uploads based on subject id.
                $allUploads = $table->getall($_POST['hidpersonType'], 'up', $upSubId, $_POST['uploadsubject'],$userId);
            }
            //echo "<pre />"; print_r($allUploads->current());exit;
            if(isset($_FILES['myfile']['name']) && $_FILES['myfile']['name'] != '') 
            {
                if (count($checkDetailes->buffer()) == 0) {
                    $result = new ViewModel(array(
                        'allUploads' => $allUploads,
                        'uploadcount' => count($checkDetailes->buffer()),
                    ));
                } else {
                    $result = new ViewModel(array(
                        'allUploads' => $allUploads,
                        'uploadcount' => count($checkDetailes->buffer()),
                    ));
                }
           } else {
                $result = new ViewModel(array(
                    'allUploads' => $allUploads,
                ));
            }
            $result->setTerminal(true);
            return $result;
        }
    }
    
   public function downloadFileAction() {
        set_time_limit(0);
        $file = $this->params()->fromRoute('id', 0); 
        $files = explode("-", $file);        
        $file_path = 'public/uploads/downloads/' . $files[2] . '-' . $files[3] . '/' . $files[4] . '.' . $files[0];        
        $this->output_file($file_path, '' . $files[4] . '.' . $files[0] . '', '');
    }

    function output_file($file, $name, $mime_type = '') { 
        if (!is_readable($file))
            die('File not found or inaccessible!');
        $size = filesize($file);
        $name = rawurldecode($name);        
        $known_mime_types = array(
            "pdf" => "application/pdf",
            "txt" => "text/plain",
            "html" => "text/html",
            "htm" => "text/html",
            "exe" => "application/octet-stream",
            "zip" => "application/zip",
            "doc" => "application/msword",
            "xls" => "application/vnd.ms-excel",
            "xlsx" => "application/vnd.ms-excel",
            "ppt" => "application/vnd.ms-powerpoint",
            "gif" => "image/gif",
            "png" => "image/png",
            "jpeg" => "image/jpg",
            "jpg" => "image/jpg",
            "php" => "text/plain"
        );
       
        if ($mime_type == '') {            
            $file_extension = strtolower(substr(strrchr($file, "."), 1));            
            $mime_type = $known_mime_types[$file_extension];
            if($mime_type != '')
            {
                $mime_type = "application/force-download";
             }
        
        @ob_end_clean();
        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: attachment; filename="' . $name . '"');
        header("Content-Transfer-Encoding: binary");
        header('Accept-Ranges: bytes');
        header("Cache-control: private");
        header('Pragma: private');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        if (isset($_SERVER['HTTP_RANGE'])) {
            list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
            list($range) = explode(",", $range, 2);
            list($range, $range_end) = explode("-", $range);
            $range = intval($range);
            if (!$range_end) {
                $range_end = $size - 1;
            } else {
                $range_end = intval($range_end);
            }
            $new_length = $range_end - $range + 1;
            header("HTTP/1.1 206 Partial Content");
            header("Content-Length: $new_length");
            header("Content-Range: bytes $range-$range_end/$size");
        } else {
            $new_length = $size;
            header("Content-Length: " . $size);
        }
        $chunksize = 1 * (1024 * 1024);
        $bytes_send = 0;
        if ($file = fopen($file, 'r')) {
            if (isset($_SERVER['HTTP_RANGE']))
                fseek($file, $range);

            while (!feof($file) &&
            (!connection_aborted()) &&
            ($bytes_send < $new_length)
            ) {
                $buffer = fread($file, $chunksize);
                print($buffer);
                flush();
                $bytes_send += strlen($buffer);
            }
            fclose($file);
        }
        else
            die('Error - can not open file.');
        die();
    }
    } 
    
    
    
  public function getChaptersAction() 
  {
    $html = '';
    $auth = $this->getServiceLocator()->get('zfcuser_auth_service');
    $containerservice   = $this->getServiceLocator()->get('lms_container_service');
   if($auth->hasIdentity()) 
    {
           $userObj = $auth->getIdentity();
           $userId = $userObj->getId();
    }
    // Get all chapters based on subject id    
    if(isset($_POST['subject_id']) && $_POST['subject_id']!=0) {
        $subjectDetails = $containerservice->getChildList($_POST['subject_id']); 
        foreach($subjectDetails as $key=>$chdetail)
         {
             $chapterdetail[$key]['chapter_name'] = $chdetail->getRackName()->getName();
             $chapterdetail[$key]['id'] = $chdetail->getRackId();
             $chapterdetail[$key]['rack_type_id'] = $chdetail->getRackType()->getRackTypeId();
         }
    }
    
   $html.='<option value="0">Select Chapter</option>';
   
   if(isset($_POST['subject_id']) && $_POST['subject_id']!=0) {
        $html .='<option value="all">General(All)</option>';
        foreach ($chapterdetail as $chapternames) {
          if ($chapternames['chapter_name'] != '') {
                     $html.='<option value="'. $chapternames['id'].'">'.$chapternames['chapter_name'].'</option>';
               }
          }
   }
        $result = new \Zend\View\Model\JsonModel(array(
            'output' => 'success',
            'success' => true,
            'chapters' => $html,
        ));
        return $result;
    } 
  
    public function getChaptersListAction() 
  {
    
    $containerservice   = $this->getServiceLocator()->get('lms_container_service');
    $resourcemodel   = $this->getServiceLocator()->get("Notification\Model\TTchaterResourceTable");
    // Get all chapters based on subject id    
    $subjectDetails = $containerservice->getChildList($_POST['subject_id']); 
    $subSubject=array();
    if(count($subjectDetails)>0){
        foreach($subjectDetails as $key=>$chdetail)
            {
                if($chdetail->getRackType()->getRackTypeId()=='7' ||$chdetail->getRackType()->getRackTypeId()=='8'){
                    $subSubject[]=$chdetail->getRackId();
                }else{
                    $chapterdetail[$chdetail->getRackId()] = $chdetail->getRackName()->getName();
                }
            }
            
            if(!empty($subSubject)){
                $subSubject_detail =  $resourcemodel->getsubchecterlist($subSubject); 
                foreach($subSubject_detail as $key=>$subchapters)
                {
                    $chapterdetail[$subchapters->rack_id] = $subchapters->chapter_name;
                } 
            }
  
        echo json_encode(array('output'=>'success','chapterList'=>$chapterdetail));
        exit;
    }
    
    echo json_encode(array('output'=>'false'));
    exit;
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
        
/**End of Class**/    
}
?>
