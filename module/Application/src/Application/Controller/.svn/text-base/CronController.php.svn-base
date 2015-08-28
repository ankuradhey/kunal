<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Session\SaveHandler\Cache;
use Zend\Form\Form;
use Predis\Client;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use SanAuthWithDbSaveHandler\Storage\IdentityManagerInterface;
use Zend\View\Model\JsonModel;
//use ZfcUser\Model\TuserTable;
//use ZfcUser\Model\Tuser;
//use ZfcUser\Model\Torders;
//use ZfcUser\Model\TordersTable;
use Zend\Cache\StorageFactory;

//use ZfcUser\Model\Tconfig;
//use ZfcUser\Model\TconfigTable;

class CronController extends AbstractActionController {

    public function runCronEmailAction() {
        
        $id = $this->params()->fromRoute('id', 0);
        if($id == "dailyBounceEmailApi"){
            $this->dailybounceemailapifunction();
            die;
        }
        if($id == "misReportToManagement"){
            $this->misreporttomanagementfunction();
            die;
        }
        if($id == "sendExpiryReminders"){
            $this->sendExpiryRemindersfunction();
            die;
        }
        if($id == "sendExpirySms"){
            $this->sendExpirySms();
            die;
        }
        if($id == "automail"){
            $this->automail();
            die;
        }
        if($id == "updateSchedule"){
            $this->updateScheduleFunction();
            die;
        }
        if($id == "paymentUpdation"){
            $this->paymentUpdationFunction();
            die;
        }
        if($id == "sendBirthdayMailandSms"){
            $this->sendBirthdayMailandSms();
            die;
        }
        if($id == "userReports"){
            $this->userReportsFunction();
            die;
        }
        $configtable = $this->getServiceLocator()->get('Package\Model\TconfigTable');

        $configval = $configtable->GetValueByConfigKey('MAXLIMIT');
        $maxlimit = $configval->config_value;

        if ($maxlimit > 0) {
            $id = $this->params()->fromRoute('id', 0);
            if ($id == '0') {
                $emailType = "registration";
            } else {
                $emailType = $id;
            }
            
            $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent');
            $emailContent = $mailContentTable->getEmailContent($emailType);

            $ids = array();
            $count = 0;
            $emailContentCount = count($emailContent);
            $mailTypes = array('attach_coupon','registration','subscription','unsubscription','useractivities');
            
            if($emailContentCount > 0) {
            foreach ($emailContent as $content) {
               
                if ($maxlimit == $count) {
                    echo "limit over";
                    break;
                } else {
                    $to = $content->email_id;
                    $subject = $content->subject;
                    $message = $content->message;
                    $attachmentDetails = $content->mail_attachment;
                    $filename = '';
                    $uploadDirectory = "";
                    if (!is_null($attachmentDetails) && $attachmentDetails != '') {
                        
                        $attachment_array = explode("/", $attachmentDetails);

                       $filename = $attachment_array[count($attachment_array) - 1];
                        array_pop($attachment_array);
                        $uploadDirectory = implode("/", $attachment_array);
                        $uploadDirectory=explode(',',$uploadDirectory);
                    }
                    
                    $config = $this->getServiceLocator()->get('config');
                    $mailConfig = $config['mail_config'];
                    if (sendMail($to, $subject, $message, '', '', '','', $uploadDirectory, $filename,$mailConfig)) {
                        $ids[] = $content->id;
                        $mailContentTable->deleteEmailContentByIds($content->id);
                        if(in_array($emailType,$mailTypes)) {
                            $count++;
                            if($count==20) {
                                echo '<meta http-equiv="refresh" content="30" />Only 20 mails wiil be send in one go';
                                die;
                            }
                        }
                }
            }
                }
                
            $remain_limit = $maxlimit - $count;
            $configtable->updateconfig($remain_limit, 'MAXLIMIT');
                
        } else {
                echo '<meta http-equiv="refresh" content="120" />Mail queue finished.';
                die;
            }
        } else {
            echo "You have no limit for send mail";
        }
        if (count($ids)) {
            echo '<meta http-equiv="refresh" content="30" />Mail sent successfully';
        }else {
            echo '<meta http-equiv="refresh" content="120" />Mail queue finished.';
        }
        exit;
    }
    
    public function automail() {
        $userTable = $this->getServiceLocator()->get('Assessment\Model\UserTable');
        $table = $this->getServiceLocator()->get('Assessment\Model\TstudentandmentorTable');
        $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent');
        $msglog  = $this->getServiceLocator()->get('Package\Model\TmsgLogTable');
        $comMapperObj = $this->getServiceLocator()->get("com_mapper");
        
        $event = $this->getEvent();
        $requestURL = $event->getRequest();
        $router = $event->getRouter();
        $uri = $router->getRequestUri();
        $baseUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
        
        $prevforthday = date('Y-m-d', strtotime('-4 days'));
        $prevSecondDay = date('Y-m-d', strtotime('-2 days'));
        $weeklyday = date('Y-m-d', strtotime('-7 days'));
        $weeklydayplusone = date('Y-m-d', strtotime('-8 days'));
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        
        $fourthUserRowSet = $userTable->getuserdetailsByRegisteredDate($prevforthday);
        $weeklydayUserRowSet = $userTable->getuserdetailsByRegisteredDate($weeklyday);
        $weeklydayOnePlusUserRowSet = $userTable->getuserdetailsByRegisteredDate($weeklydayplusone);
        $yesterdayUserRowSet = $userTable->getuserdetailsByRegisteredDate($yesterday);
        $prevSecUserRowSet = $userTable->getuserdetailsByRegisteredDate($prevSecondDay);
        
        /**2 days sms send**/
        foreach($prevSecUserRowSet as $seconddayUser) {
            if($seconddayUser->user_type_id==1) {
                $msgTxt="Lively animations, Study groups, test papers & much more. Get Extramarks Smart Study Pack now .Click on www.extramarks.com/package  to buy or call 18001025301";
            } else if($seconddayUser->user_type_id==2) {
                $msgTxt="Track, monitor & supervise your child’s performance. Get Extramarks Smart Study Pack now. Click on www.extramarks.com/package  to buy or call 18001025301";
            } else if($seconddayUser->user_type_id==3) {
                $msgTxt="Track ,evaluate & improve your student’s learning progress  with Extramarks Smart Study Pack. Click on www.extramarks.com/package  to buy or call 18001025301";
            } else {
                continue;
            }
            $config=$this->getServiceLocator()->get('config');
            $defaultstates = isset($config['msg_engine'])?$config['msg_engine']:'';
            if(isset($defaultstates['status']) && $defaultstates['status'] == 'ON') {
                $usermobile = $seconddayUser->mobile;
                $userId = $seconddayUser->user_id;
                $mobile     = explode("-", $usermobile);
                $mob_number = str_replace('+','',$mobile[0]).$mobile[1];
                if(isset($mobile[1]) && !empty($mobile[1])) {
                    $smsArr = array('to_mobile_number'=>$mob_number,
                        'msg_txt' => $msgTxt,
                        'user_id' => $userId,
                        'mobile_number' => $usermobile,
                        'sms_type' => 'registration'
                    );
                    $data = $comMapperObj->smssendprocess($smsArr);
                    $result = $msglog->addlog($data);
                }
            }
        }
        
        /**4 days sms send**/
        foreach($fourthUserRowSet as $effectUserRow) {
            $activementorMessage='';
            $filepath = __DIR__ . '../../../../view/mailer/';
            //if($effectUserRow->user_type_id==2 || $effectUserRow->user_type_id==1) { continue; }
            if($effectUserRow->user_type_id==1) {
                $msgTxt="Get benefits of Extramarks Smart Study Pack. Learn, Practice and Test concepts at your own pace. Click on www.extramarks.com/package  to buy or call 18001025301";
                $filepath = $filepath.'studenteffectiveuse.html';
                $activementorsubject='Student Effective Use';
            } else  if($effectUserRow->user_type_id==2) {
                $msgTxt="Let your child access concepts of Extramarks Smart Study Pack like Learn, Practice and Test. Click on www.extramarks.com/package  to buy or call 18001025301";
                $filepath = $filepath.'parenteffectiveuse.html';
                $activementorsubject='Parent Effective Use';
            } else if($effectUserRow->user_type_id==3) {
                $msgTxt="Let your student access concepts of Extramarks Smart Study Pack like Learn, Practice and Test. Click on www.extramarks.com/package  to buy or call 18001025301";
                $filepath = $filepath.'mentoreffectiveuse.html';
                $activementorsubject='Mentor Effective Use';
            }
            $config=$this->getServiceLocator()->get('config');
            $defaultstates = isset($config['msg_engine'])?$config['msg_engine']:'';
            if(isset($defaultstates['status']) && $defaultstates['status'] == 'ON') {
                $usermobile = $effectUserRow->mobile;
                $userId = $effectUserRow->user_id;
                $mobile     = explode("-", $usermobile);
                $mob_number = str_replace('+','',$mobile[0]).$mobile[1];
                if(isset($mobile[1]) && !empty($mobile[1])) {
                    $smsArr = array('to_mobile_number'=>$mob_number,
                        'msg_txt' => $msgTxt,
                        'user_id' => $userId,
                        'mobile_number' => $usermobile,
                        'sms_type' => 'registration'
                    );
                    $data = $comMapperObj->smssendprocess($smsArr);
                    $result = $msglog->addlog($data);
                }
            }
            
            $userName = $effectUserRow->firstName;
            $to = $effectUserRow->emailId;
            $activementorMessage = file_get_contents($filepath);
            $activementorMessage = str_replace("{USER_NAME}", "$userName", $activementorMessage);
            //$activementorMessage = str_replace("<TYPE>", "$typeofUser", $activementorMessage);
            $activementorMessage = str_replace("{SITE_URL}", $baseUrl, $activementorMessage);
            $activementorMessage = str_replace("{BASE_URL}", $baseUrl, $activementorMessage);
            //echo $activementorMessage; exit;
            $emailData = array("email_id" => $to, 'subject' => $activementorsubject, 'message' => $activementorMessage, 'mail_type' => 'useractivities', 'status' => 1);
            $mailContentTable->addEmailContent($emailData);
        } 
        
        /**7 days sms send**/
        foreach($weeklydayUserRowSet as $weeklydayUserRow) {
            if($weeklydayUserRow->user_type_id==1) {
                $msgTxt="Don’t miss out benefits of Extramarks Smart Study Pack that make learning easy and effective. Click on www.extramarks.com/package  to buy or call 18001025301";
            } else if($weeklydayUserRow->user_type_id==2) {
                $msgTxt="Don’t miss out benefits of Extramarks Smart Study Pack & help your child top the exams. Click on www.extramarks.com/package  to buy or call 18001025301";
            } else if($weeklydayUserRow->user_type_id==3) {
                $msgTxt="Don’t miss out benefits of Extramarks Smart Study Pack & help your student top the exams. Click on www.extramarks.com/package  to buy or call 18001025301";
            } else {
                continue;
            }
            $config=$this->getServiceLocator()->get('config');
            $defaultstates = isset($config['msg_engine'])?$config['msg_engine']:'';
            if(isset($defaultstates['status']) && $defaultstates['status'] == 'ON') {
                $usermobile = $weeklydayUserRow->mobile;
                $userId = $weeklydayUserRow->user_id;
                $mobile     = explode("-", $usermobile);
                $mob_number = str_replace('+','',$mobile[0]).$mobile[1];
                if(isset($mobile[1]) && !empty($mobile[1])) {
                    $smsArr = array('to_mobile_number'=>$mob_number,
                        'msg_txt' => $msgTxt,
                        'user_id' => $userId,
                        'mobile_number' => $usermobile,
                        'sms_type' => 'registration'
                    );
                    $data = $comMapperObj->smssendprocess($smsArr);
                    $result = $msglog->addlog($data);
                }
            }
        }
        
        foreach($weeklydayUserRowSet as $weeklydayUserRow) {
            $activementorMessage='';
            $filepath = __DIR__ . '../../../../view/mailer/';
            //if($weeklydayUserRow->user_type_id==1 || $weeklydayUserRow->user_type_id==2) { continue; }
            if($weeklydayUserRow->user_type_id==1) {
                $filepath = $filepath.'studentreminder.html';
                $activementorsubject='Student Reminder';
            } else {
                continue;
            }
            $userName = $weeklydayUserRow->firstName;
            $to = $weeklydayUserRow->emailId;
            //$to = 'baljeet.singh@extramarks.com';
            $activementorMessage = file_get_contents($filepath);
            $activementorMessage = str_replace("{USER_NAME}", "$userName", $activementorMessage);
            //$activementorMessage = str_replace("<TYPE>", "$typeofUser", $activementorMessage);
            $activementorMessage = str_replace("{SITE_URL}", $baseUrl, $activementorMessage);
            $activementorMessage = str_replace("{BASE_URL}", $baseUrl, $activementorMessage);
            //echo $activementorMessage; exit;
            
            $emailData = array("email_id" => $to, 'subject' => $activementorsubject, 'message' => $activementorMessage, 'mail_type' => 'useractivities', 'status' => 1);
            $mailContentTable->addEmailContent($emailData);
        }
        
        foreach($weeklydayOnePlusUserRowSet as $weeklydayUserRow) {
            //$userId = $weeklydayUserRow->user_id;
            $activementorMessage='';
            $filepath = __DIR__ . '../../../../view/mailer/';
            //if($weeklydayUserRow->user_type_id==1 || $weeklydayUserRow->user_type_id==2) { continue; }
            if($weeklydayUserRow->user_type_id==2) {
                $filepath = $filepath.'parentreminder.html';
                $activementorsubject='Parent Reminder';
            } else if($weeklydayUserRow->user_type_id==3) {
                $filepath = $filepath.'mentorreminder.html';
                $activementorsubject='Mentor Reminder';
            } else {
                continue;
            }
            $userName = $weeklydayUserRow->firstName;
            $to = $weeklydayUserRow->emailId;
            
            //$to = 'baljeet.singh@extramarks.com';
            $activementorMessage = file_get_contents($filepath);
            $activementorMessage = str_replace("{USER_NAME}", "$userName", $activementorMessage);
            //$activementorMessage = str_replace("<TYPE>", "$typeofUser", $activementorMessage);
            $activementorMessage = str_replace("{SITE_URL}", $baseUrl, $activementorMessage);
            $activementorMessage = str_replace("{BASE_URL}", $baseUrl, $activementorMessage);
            //echo $activementorMessage; exit;
            
            $emailData = array("email_id" => $to, 'subject' => $activementorsubject, 'message' => $activementorMessage, 'mail_type' => 'useractivities', 'status' => 1);
            $mailContentTable->addEmailContent($emailData);
        }
        
        foreach($yesterdayUserRowSet as $yesterdayUserRow) {
            $activementorMessage='';
            $filepath = __DIR__ . '../../../../view/mailer/';
            //if($yesterdayUserRow->user_type_id==1 || $yesterdayUserRow->user_type_id==2) { continue; }
            if($yesterdayUserRow->user_type_id==1) {
                $filepath = $filepath.'feature-mail-student.html';
                $activementorsubject='Student Feature Mail';
            } else  if($yesterdayUserRow->user_type_id==2) {
                $filepath = $filepath.'feature-mail-parent.html';
                $activementorsubject='Parent Feature Mail';
            } else if($yesterdayUserRow->user_type_id==3) {
                $filepath = $filepath.'feature-mail-mentor.html';
                $activementorsubject='Mentor Feature Mail';
            }
            $userName = $yesterdayUserRow->firstName;
            $to = $yesterdayUserRow->emailId;
            
            //$to = 'baljeet.singh@extramarks.com';
            $activementorMessage = file_get_contents($filepath);
            $activementorMessage = str_replace("{USER_NAME}", "$userName", $activementorMessage);
            //$activementorMessage = str_replace("<TYPE>", "$typeofUser", $activementorMessage);
            $activementorMessage = str_replace("{SITE_URL}", $baseUrl, $activementorMessage);
            $activementorMessage = str_replace("{BASE_URL}", $baseUrl, $activementorMessage);
            //echo $activementorMessage; exit;
            
            $emailData = array("email_id" => $to, 'subject' => $activementorsubject, 'message' => $activementorMessage, 'mail_type' => 'useractivities', 'status' => 1);
            $mailContentTable->addEmailContent($emailData);
        }
        echo 'Email Sent Successfully'; exit;
    }
    
    public function falconideapiAction() { 
        $transId = $_REQUEST['TRANSID'];
        $response = $_REQUEST['RESPONSE'];
        $event = $_REQUEST['EVENT'];
        $rcptId = $_REQUEST['RCPTID'];
        $email = $_REQUEST['EMAIL'];
        
        $APIHEADER = null;
        $dumpHeader = "";
        if(isset($_REQUEST['X-APIHEADER'])){
            $APIHEADER = $_REQUEST['X-APIHEADER'];
            $dumpHeader = "APIHEADER:".$APIHEADER;
        }
        
        $timestamp = $_REQUEST['TIMESTAMP'];
        $valueDump = "TRANSID: ".$transId.", RESPONSE: ".$response." ,EVENT: ".$event." ,RCPTID: ".$rcptId.", EMAIL: ".$email." ,TIMESTAMP: ".$timestamp.",".$dumpHeader;
        $tableuser = $this->getServiceLocator()->get('Assessment\Model\UserTable');
        
        
        if($event == 'unsubscribed'){
            $tableuser->updateSubscribeEmail($email);
        }else{
            if($event == "opened"){
                $tableuser->updateBounceEmailVaild($email);
            }else if($event == "bounced" || $event == "abuse" || $event == "dropped" || $event == "invalid"){
                $tableuser->updateBounceEmail($email);
            }
        }
        $logsTable = $this->serviceLocator->get('Assessment\Model\AdminLogDetailsTable');   
        
        $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");
        
        $emailData = $zfcuserMapperObj->findByEmail($email);
        if(is_object($emailData) && count($emailData) > 0){
            $loginId = $emailData->getId();  
            $params['created_date'] = date('Y-m-d h:i:s');
            $params['value'] = $valueDump;
            $params['login_id'] = $loginId;
            $params['key_name'] = "falconideapi";
            $params['modified_table'] = "user";
            $params['modified_primary_id'] = $loginId;
            $logsTable->addpackageLogs($params);   
            die();
        }
        
    }
    
    /*
     * Daily Cron function for executing bounce Email API
     */
    public function dailybounceemailapifunction(){
        $config = $this->getServiceLocator()->get('config');
        $emailBounceCron = $config['email_bounce'];
        if($emailBounceCron['daily_cron_flag']){
            // Email Bounce/Block/Invalid functionalities. APIs from SendGrid Web
            $this->apiCall('bounce',$emailBounceCron);
            $this->apiCall('block',$emailBounceCron);
            $this->apiCall('invalid',$emailBounceCron);
            $this->apiCall('unsubscribe',$emailBounceCron);
            die('Daily Cron for Invalid/Unsubscribe Email executed');
        }else{
            die('Daily Cron Flag not set');
        }
        
    }
    
    private function apiCall($param,$emailBounceCron){
        $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");
        $tableuser = $this->getServiceLocator()->get('Assessment\Model\UserTable');
        
        if($param == 'bounce')
            $url = "https://api.sendgrid.com/api/bounces.get.json?api_user=".$emailBounceCron['api_user']."&api_key=".$emailBounceCron['api_password']."&days=2";
        else if($param == 'block')
            $url = "https://api.sendgrid.com/api/blocks.get.json?api_user=".$emailBounceCron['api_user']."&api_key=".$emailBounceCron['api_password']."&days=2";
        else if($param == 'invalid')
            $url = "https://api.sendgrid.com/api/invalidemails.get.json?api_user=".$emailBounceCron['api_user']."&api_key=".$emailBounceCron['api_password']."&days=2";
        else if ($param == 'unsubscribe')
            $url = "https://api.sendgrid.com/api/unsubscribes.get.json?api_user=".$emailBounceCron['api_user']."&api_key=".$emailBounceCron['api_password']."&days=2";
        
        $dataJson = file_get_contents($url);
        $decodeData = json_decode($dataJson);
        
        if ($param != 'unsubscribe'){
            foreach($decodeData as $key=>$val){
                $emailData = $zfcuserMapperObj->findByEmail($val->email);
                if(is_object($emailData) && count($emailData) > 0){
                    if($emailData->getValidEmail() != '5'){
                        $tableuser->updateBounceEmail($val->email);
                    }
                }
            }
        }else if ($param == 'unsubscribe'){
            foreach($decodeData as $key=>$val){
                $emailData = $zfcuserMapperObj->findByEmail($val->email);
                if(is_object($emailData) && count($emailData) > 0){
                    if($emailData->getSubscribeMe() !='n'){
                        $tableuser->updateSubscribeEmail($val->email);
                    }
                }
            }
        }
    }
    
    public function misreporttomanagementfunction(){
        
        //ini_set('display_errors', E_ALL);
        $currentDate = date('d-m-Y');
        $createdDate = date('d-m-Y', strtotime('-1 day', strtotime($currentDate)));
        //$createdDate = date('d-m-Y', strtotime($currentDate));
        
        $createdDate = date('Y-m-d', strtotime($createdDate));
        $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');
        $totalRegistration = $table->getRegisteredUserByDate($createdDate);
        
        $params['fromDate'] = $createdDate;
        $params['toDate'] = $createdDate;
        $params['pkg_payment_details'] = "Processed";
        $usersData = $table->getSubscriptionData($params);
        
        
        $totalSale = 0;$totalINRSale = 0;$totalUSDSale=0;$totalSGDSale=0;
        $totalSubscriptions = 0;$totalINRSubscriptions = 0;$totalUSDSubscriptions=0;$totalSGDSubscriptions=0;
        $totalINROfflineSale = 0;$totalINROfflineSubscriptions=0;$totalINROnlineSale=0;$totalINROnlineSubscriptions=0;
        $totalUSDOfflineSale = 0;$totalUSDOfflineSubscriptions=0;$totalUSDOnlineSale=0;$totalUSDOnlineSubscriptions=0;
        $totalSGDOfflineSale=0;$totalSGDOfflineSubscriptions=0;$totalSGDOnlineSale=0;$totalSGDOnlineSubscriptions=0;
        $totalSubscribedPackage = 0; 
        $totalSubscribedStudy = 0; $totalStudySale = 0; $totalSubscribedINRStudy = 0; $totalSubscribedUSDStudy = 0; $totalSubscribedSGDStudy = 0; $totalINRStudy = 0; $totalUSDStudy = 0; $totalSGDStudy = 0;
        $totalSubscribedTablet = 0; $totalTabletSale = 0; $totalSubscribedINRTablet = 0; $totalSubscribedUSDTablet = 0; $totalSubscribedSGDTablet = 0; $totalINRTablet = 0; $totalUSDTablet = 0; $totalSGDTablet = 0;
        $totalSubscribedSDcard = 0; $totalSDcardSale = 0; $totalSubscribedINRSDcard = 0; $totalSubscribedUSDSDcard = 0; $totalSubscribedSGDSDcard = 0; $totalINRSDcard = 0; $totalUSDSDcard = 0; $totalSGDSDcard = 0;
        foreach($usersData as $data) { 
           //echo "<pre>"; print_r($data); 
           //echo $data->coupon_type;
           if($data->coupon_type != 'test' && $data->coupon_type != 'demo' && $data->coupon_type !='promotional'){
               
               $totalSale+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
               $totalSubscriptions++;
               if($data->currency_type == 'INR'){
                   $totalINRSale+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                   $totalINRSubscriptions++;
                   if($data->pkg_payment_type == 'offline'){
                       $totalINROfflineSale+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                       $totalINROfflineSubscriptions++;
                   }
                   
                   if($data->pkg_payment_type != 'offline'){
                       $totalINROnlineSale+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                       $totalINROnlineSubscriptions++;
                   }
                   
                   if($data->package_type == 'study'){
                       $totalINRStudy+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                       $totalSubscribedINRStudy++;
                   }
                   
                   if($data->package_type == 'tablet'){
                       $totalINRTablet+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                       $totalSubscribedINRTablet++;
                   }
                   
                   if($data->package_type == 'sdcard'){
                       $totalINRSDcard+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                       $totalSubscribedINRSDcard++;
                   }
               }
               if($data->currency_type == 'USD'){
                   $totalUSDSale+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                   $totalUSDSubscriptions++;
                   if($data->pkg_payment_type == 'offline'){
                       $totalUSDOfflineSale+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                       $totalUSDOfflineSubscriptions++;
                   } 
                   
                   if($data->pkg_payment_type != 'offline'){
                       $totalUSDOnlineSale+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                       $totalUSDOnlineSubscriptions++;
                   }
                   
                   if($data->package_type == 'study'){
                       $totalUSDStudy+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                       $totalSubscribedUSDStudy++;
                   } 
                   
                   if($data->package_type == 'tablet'){
                       $totalUSDTablet+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                       $totalSubscribedUSDTablet++;
                   } 
                   
                   if($data->package_type == 'sdcard'){
                       $totalUSDSDcard+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                       $totalSubscribedUSDSDcard++;
                   } 
                   
                   
               }
               if($data->currency_type == 'SGD'){
                   $totalSGDSale+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                   $totalSGDSubscriptions++;
                   if($data->pkg_payment_type == 'offline'){
                       $totalSGDOfflineSale+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                       $totalSGDOfflineSubscriptions++;
                   }
                   
                   if($data->pkg_payment_type != 'offline'){
                       $totalSGDOnlineSale+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                       $totalSGDOnlineSubscriptions++;
                   }
                   
                   if($data->package_type == 'study'){
                       $totalSGDStudy+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                       $totalSubscribedSGDStudy++;
                   }
                   
                   if($data->package_type == 'tablet'){
                       $totalSGDTablet+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                       $totalSubscribedSGDTablet++;
                   }
                   
                   if($data->package_type == 'sdcard'){
                       $totalSGDSDcard+= ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                       $totalSubscribedSGDSDcard++;
                   }                
                   
               }
            
           }
        }
        $totalOnlineSubscribtions = $totalINROnlineSubscriptions + $totalUSDOnlineSubscriptions + $totalSGDOnlineSubscriptions;
        $totalOnlineSale = $totalINROnlineSale + $totalUSDOnlineSale + $totalSGDOnlineSale;
        
        $totalOfflineSubscribtions = $totalINROfflineSubscriptions + $totalUSDOfflineSubscriptions + $totalSGDOfflineSubscriptions;
        $totalOfflineSale = $totalINROfflineSale + $totalUSDOfflineSale + $totalSGDOfflineSale;
        
        $totalSubscribedStudy = $totalSubscribedINRStudy + $totalSubscribedUSDStudy + $totalSubscribedSGDStudy;
        $totalStudySale = $totalINRStudy + $totalUSDStudy + $totalSGDStudy;
        
        $totalSubscribedTablet = $totalSubscribedINRTablet + $totalSubscribedUSDTablet + $totalSubscribedSGDTablet;
        $totalTabletSale = $totalINRTablet + $totalUSDTablet + $totalSGDTablet;
        
        $totalSubscribedSDcard = $totalSubscribedINRSDcard + $totalSubscribedUSDSDcard + $totalSubscribedSGDSDcard;
        $totalSDcardSale = $totalINRSDcard + $totalUSDSDcard + $totalSGDSDcard;
       
        $filepath= __DIR__ . '../../../../../Admin/view/mailer/';
        $filepath = $filepath.'misreporttoadmin.html';
                
        $file_content = file_get_contents($filepath);
        
        $totalReg = $totalRegistration['student_cnt'] + $totalRegistration['parent_cnt'] + $totalRegistration['mentor_cnt'];
        
        $socialReg = ($totalRegistration['facebook_cnt'] + $totalRegistration['google_cnt'] + $totalRegistration['twitter_cnt']) ;
        $directReg = ($totalReg) - ($socialReg) ;
        
        
        $regMessage = str_replace('{TOTAL_REG}', $totalReg, $file_content);
        $regMessage = str_replace('{REG_DATE}', date('d F, Y', strtotime('-1 day', strtotime($currentDate))), $regMessage);
        $regMessage = str_replace('{STUDENT_REG}', $totalRegistration['student_cnt'], $regMessage);
        $regMessage = str_replace('{PARENT_REG}', $totalRegistration['parent_cnt'], $regMessage);
        $regMessage = str_replace('{MENTOR_REG}', $totalRegistration['mentor_cnt'], $regMessage);
        $regMessage = str_replace('{SOCIAL_REG}', $socialReg, $regMessage);
        $regMessage = str_replace('{FACEBOOK_REG}', $totalRegistration['facebook_cnt'], $regMessage);
        $regMessage = str_replace('{GOOGLE_REG}', $totalRegistration['google_cnt'], $regMessage);
        $regMessage = str_replace('{TWITTER_REG}', $totalRegistration['twitter_cnt'], $regMessage);
        $regMessage = str_replace('{DIRECT_REG}', $directReg, $regMessage);
        
        //$regMessage = str_replace('{TOTAL_SALE}', $totalSale."(".$totalSubscriptions.")", $regMessage);
        $regMessage = str_replace('{TOTAL_SALE}', $totalSubscriptions, $regMessage);
        
        $regMessage = str_replace('{INR_SALE}', $this->number_format_clean($totalINRSale, 2, '.', '') ."(".$totalINRSubscriptions.")", $regMessage);
        $regMessage = str_replace('{USD_SALE}', $this->number_format_clean($totalUSDSale, 2, '.', '') ."(".$totalUSDSubscriptions.")", $regMessage);
        $regMessage = str_replace('{SGD_SALE}', $this->number_format_clean($totalSGDSale, 2, '.', '') ."(".$totalSGDSubscriptions.")", $regMessage);
        
        //$regMessage = str_replace('{ONLINE_SALE}', $totalOnlineSale."(".$totalOnlineSubscribtions.")", $regMessage);
        $regMessage = str_replace('{ONLINE_SALE}', $totalOnlineSubscribtions, $regMessage);
        $regMessage = str_replace('{ONLINE_INR}', $this->number_format_clean($totalINROnlineSale, 2, '.', '') ."(".$totalINROnlineSubscriptions.")", $regMessage);
        $regMessage = str_replace('{ONLINE_USD}', $this->number_format_clean($totalUSDOnlineSale, 2, '.', '') ."(".$totalUSDOnlineSubscriptions.")", $regMessage);
        $regMessage = str_replace('{ONLINE_SGD}', $this->number_format_clean($totalSGDOnlineSale, 2, '.', '') ."(".$totalSGDOnlineSubscriptions.")", $regMessage);
        
        //$regMessage = str_replace('{OFFLINE_SALE}', $totalOfflineSale."(".$totalOfflineSubscribtions.")", $regMessage);
        $regMessage = str_replace('{OFFLINE_SALE}', $totalOfflineSubscribtions, $regMessage);
        $regMessage = str_replace('{OFFLINE_INR}', $this->number_format_clean($totalINROfflineSale, 2, '.', '') ."(".$totalINROfflineSubscriptions.")", $regMessage);
        $regMessage = str_replace('{OFFLINE_USD}', $this->number_format_clean($totalUSDOfflineSale, 2, '.', '') ."(".$totalUSDOfflineSubscriptions.")", $regMessage);
        $regMessage = str_replace('{OFFLINE_SGD}', $this->number_format_clean($totalSGDOfflineSale, 2, '.', '') ."(".$totalSGDOfflineSubscriptions.")", $regMessage);
        
        //$regMessage = str_replace('{STUDY_SALE}', $totalStudySale."(".$totalSubscribedStudy.")", $regMessage);
        $regMessage = str_replace('{STUDY_SALE}', $totalSubscribedStudy, $regMessage);
        $regMessage = str_replace('{STUDY_INR}', $this->number_format_clean($totalINRStudy, 2, '.', '') ."(".$totalSubscribedINRStudy.")", $regMessage);
        $regMessage = str_replace('{STUDY_USD}', $this->number_format_clean($totalUSDStudy, 2, '.', '') ."(".$totalSubscribedUSDStudy.")", $regMessage);
        $regMessage = str_replace('{STUDY_SGD}', $this->number_format_clean($totalSGDStudy, 2, '.', '') ."(".$totalSubscribedSGDStudy.")", $regMessage);
        
        //$regMessage = str_replace('{TABLET_SALE}', $totalTabletSale."(".$totalSubscribedTablet.")", $regMessage);
        $regMessage = str_replace('{TABLET_SALE}', $totalSubscribedTablet, $regMessage);
        $regMessage = str_replace('{TABLET_INR}', $this->number_format_clean($totalINRTablet, 2, '.', '') ."(".$totalSubscribedINRTablet.")", $regMessage);
        $regMessage = str_replace('{TABLET_USD}', $this->number_format_clean($totalUSDTablet, 2, '.', '') ."(".$totalSubscribedUSDTablet.")", $regMessage);
        $regMessage = str_replace('{TABLET_SGD}', $this->number_format_clean($totalSGDTablet, 2, '.', '') ."(".$totalSubscribedSGDTablet.")", $regMessage);
        
        //$regMessage = str_replace('{SDCARD_SALE}', $totalSDcardSale."(".$totalSubscribedSDcard.")", $regMessage);
        $regMessage = str_replace('{SDCARD_SALE}', $totalSubscribedSDcard, $regMessage);
        $regMessage = str_replace('{SDCARD_INR}', $this->number_format_clean($totalINRSDcard, 2, '.', '') ."(".$totalSubscribedINRSDcard.")", $regMessage);
        $regMessage = str_replace('{SDCARD_USD}', $this->number_format_clean($totalUSDSDcard, 2, '.', '') ."(".$totalSubscribedUSDSDcard.")", $regMessage);
        $regMessage = str_replace('{SDCARD_SGD}', $this->number_format_clean($totalSGDSDcard, 2, '.', '')."(".$totalSubscribedSGDSDcard.")", $regMessage);
        
        
        $message = "Dear Sir,<br><br>  Please find below the Registration Info and User subscriptions summary for ".date('d-m-Y', strtotime('-1 day', strtotime($currentDate)))." on EmLive.<br /> Also, please find attached excel of yesterday's purchased package details:<br/><br/><br/>".$regMessage;
        //echo $message; die;
        
        // generating csv file
        
        $this->dailymiscsv();
        
        $file_path = "public/uploads/admin/MisReport/";
        $file_name = 'Sale_Details_'. date('Y-m-d', strtotime('-1 day', strtotime($currentDate))).'.xls';
        $xlsFilePath  = $file_path.$file_name;
        
        
        // entry in email content table
        $subject = "Daily MIS Report";
        
        $config = $this->getServiceLocator()->get('config');
        $recipient = $config['daily_report_to'];// get the daily mis report receipient config data
        //echo $recipient; die;
        
        $to = $recipient;
        $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent');
        $emailData = array("email_id" => $to, 'subject' => $subject, 'message' => $message, 'mail_type' => 'internal_tracking', 'status' => 1, 'mail_attachment' => $xlsFilePath);
        $res = $mailContentTable->addMultiEmailContent($emailData);
        die('Daily MIS Report Email generation completed');
        exit;
    } 
    
    private function number_format_clean($number,$precision=0,$dec_point='.',$thousands_sep=',')
    {
        if($number == '0') {
            return $number;
        } else if ( strpos( $number, "." ) !== false ) {
            return number_format($number,$precision,$dec_point,$thousands_sep);
        } else {
            return $number;
        }
    }
    
    public function dailymiscsv() {
        
        $search_array = array();
        
        
        $currentDate = date('d-m-Y');
        $createdDate = date('d-m-Y', strtotime('-1 day', strtotime($currentDate)));
        $createdDate = date('Y-m-d', strtotime($createdDate));
        
        $search_array['fromDate'] = $createdDate;
        $search_array['toDate'] = $createdDate;
        

        $table = $this->getServiceLocator()->get('Assessment\Model\UserTable');

        $paginator = $table->misReportDataManagement($search_array);
        $userLogObj = $this->getServiceLocator()->get("lms_container_mapper");
        $result['rResult'] = array();
        foreach ($paginator as $results) {
            if($results->coupon_type != 'test' && $results->coupon_type != 'demo' && $results->coupon_type !='promotional') {
                $result['rResult'][] = $results;
                if (trim($results->package_name) != 'All Subject' || $results->package_name != 'All Subjects') {
                    $subjectName = '';

                    $subjectIds = explode(',', $results->syllabus_id);
                    foreach ($subjectIds as $key => $val) {
                        $subjectObj = $userLogObj->getContainer($val);
                        if (is_object($subjectObj)) {
                            $subjectName .= $subjectObj->getRackName()->getName() . ' , ';                        
                        }
                    }
                    $subject[$results->order_id] = ' (' . $subjectName . ')';
                }
                if ($results->user_id == $results->purchaser_id) {
                    $purchasedBy[$results->order_id] = '<b>Purchased for: </b>Self';
                } else {
                    $parent_details = isset($results->parent_name) ? $results->parent_name : $results->parent_email;
                    $child_details = !empty($results->username) ? $results->username : $results->display_name;
                    $purchasedBy[$results->order_id] = "<b>Purchased by: </b>" . $parent_details . '<br/>' . $results->parent_mobile . '<br />' . $results->parent_address . '<br/><b>Purchased for User Id: </b>' . $child_details;
                }
            }
        }

        $columns = array('First Name', 'Email','Address','User Type','Board', 'Order Id', 'Payment Mode', 'Package Name', 'Order Date', 'Full price', 'Discount code', 'Discount Detail', 'Paid Price', 'Status', 'Transction Type', 'Purchased By', 'Quantity'); //, 'Order Date', 'Full price', 'Discount code', '% discount', 'Paid Price','Status','Transction Type','Purchased By','Quantity'
        // Create a new PHPExcel object 
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getActiveSheet()->setTitle('List of Users');

        // Field names in the first row        
        $col = 0;
        foreach ($columns as $field) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }

        $fields = array('first_name','email','address', 'user_type','board_name', 'order_id','pkg_payment_type', 'package_name', 'purchase_date', 'price', 'coupon_code', 'discount_percent', 'paid_price', 'pkg_payment_details', 'transaction_product_type', 'purchased_by', 'quantity');
        // Fetching the table data
        $row = 2;
        $failureRowStarts = false;
        foreach ($result['rResult'] as $data) {
            if($data->pkg_payment_details == 'Failed' && $failureRowStarts == false){
                $row++;
                $failureRowStarts = true;
            }
            $usertype = 'NA';
            if ($data->user_type_id == 1) {
                $usertype = 'Student';
            } else if ($data->user_type_id == 2) {
                $usertype = 'Parent';
            }
             if ($data->user_id == $data->purchaser_id) {
                $purchaseduserBy[$data->order_id] = 'Self';
            } else {
                $parent_details = isset($data->parent_name) ? $data->parent_name : $data->parent_email;
                $child_details = !empty($data->username) ? $data->username : $data->display_name;
                $purchaseduserBy[$data->order_id] = $parent_details."("."Parent".")";
            }
        //echo '<pre>';print_r($purchaseduserBy[$data->order_id]);
            $col = 0;
            foreach ($fields as $field) {
                $firstCol = '';
                if ($field == 'first_name') {
                    
//                   $firstCol = $data->display_name."\n".$data->emailId."\n".$data->address."\n".$data->mobile."\n".$location.$purchasedBy[$data->order_id];                                      
                    $firstCol = $data->display_name;

                    $data->$field = $firstCol;
                }
                 if ($field == 'email') {
                      $data->$field = $data->emailId;
                 }
                  if ($field == 'address') {
                      $address = isset($data->address) ? $data->address : $data->parent_address;
                      //$address =$data->address;
                      $location = isset($data->city) ? $data->city : $data->state_name;
                      $data->$field = $address.''.$location;
                 }
                 if($field=='user_type'){
                     $data->$field =  $usertype;
                 }
                if ($field == 'board_name') {
                    $secondCol = $data->board_name . '-' . $data->class_name;
                    $data->$field = $secondCol;
                }
                if ($field == 'package_name') {
                    $thirdCol = $data->package_name . $subject[$data->order_id];
                    $data->$field = $thirdCol;
                }
                if ($field == 'purchased_by') {
                    $fourCol = $purchaseduserBy[$data->order_id];
                    $data->$field = $fourCol;
                }
                if ($field == 'quantity') {
                    $data->$field = '1';
                }
                
                if ($field == 'discount_percent') {
                    $discount = "";
                    if($data->discount_type == 'percent')
                        $discount = $data->discount_percent. "%";
                    else if($data->discount_type == 'fixed')
                        $discount = $data->discount_percent;
                    $data->$field = $discount;
                }
                
                if ($field == 'paid_price') {
                    $paid_price = ($data->transaction_amount != "" ? $data->transaction_amount : $data->paid_price);
                    $data->$field = $paid_price;
                }
                
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
                $col++;
            }

            $row++;
        }

        // Freeze pane so that the heading line won't scroll 
        $objPHPExcel->getActiveSheet()->freezePane('A2');
        
        // Save as an Excel BIFF (xls) file 
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $tmpFilename = tempnam('./temp', 'tmp');
        
        $objWriter->save($tmpFilename);
        $file_name = 'Sale_Details_'. date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
        $this->ftpFileUploaded($tmpFilename, 'uploads/admin/MisReport/'.$file_name.'.xls');
//         echo '<pre>';print_r ();echo '</pre>';die('Vikash');       
//        $objWriter->save('public/uploads/admin/MisReport/'.date('Y-m-d').'.xls');

    }
    
    public function ftpFileUploaded($sourcePath, $targetPath) {
        $config = $this->getServiceLocator()->get('config');
        $ftpDetails = $config['ftp_config'];
        $conn_id = ftp_connect($ftpDetails['FTP_SERVER']);        // set up basic connection        
        $login_result = ftp_login($conn_id, $ftpDetails['FTP_USERNAME'], $ftpDetails['FTP_PASSWORD']); // ftp login     
        if ($login_result) {
            $upload = ftp_put($conn_id, $targetPath, $sourcePath, FTP_BINARY);  // upload the file
            if (!$upload) {  // check upload status
                $fileStatus = 'error';
            } else {
                $fileStatus = 'success';
            }
        } else {
            $fileStatus = 'error';
        }
        ftp_close($conn_id); // close the FTP stream     
        //echo "<pre />"; print_r($fileStatus);exit;
        return $fileStatus;
    }
     public function sendExpiryRemindersfunction() { 
        $ToBeExpiredUserPackages = $this->getServiceLocator()->get('package_service')->getPackagesForExpiryMailReminders();
        //echo '<pre>';print_r($ToBeExpiredUserPackages);die;
        foreach ($ToBeExpiredUserPackages as $key => $up) {
            if ($up['daysLeft'] >= 1 && $up['emailStartDate']!='') {// print_r($up);
                $start_date = date('Y-m-d', strtotime($up['emailStartDate']));
                    $today_date = date('Y-m-d');
                    $valid_till = $up['validTill'];
                    $valid_till->format('Y-m-d');
                   // while ($start_date <= $valid_till) {
                        $expiry_date = date_create($valid_till->format('Y-m-d'));
                        $todaydate = date_create($today_date);
                        $diff = date_diff($expiry_date, $todaydate);
                        $days = $diff->d; 
                        if ($days % 7 == 0) {
                            $this->insertExpiryMailContent($up, $up['daysLeft']);
                            $this->insertExpiryNotificationContent($up, $up['daysLeft']);
                           // break;
                        }//echo $start_date;
                       // $time = strtotime($start_date) + (7 * 24 * 60 * 60);
                    //echo     $start_date = date('Y-m-d', $time);//die;
                   // }//die;
            } elseif ($up['daysLeft'] == 0) {
                $this->insertExpiryMailContent($up, $up['daysLeft']);
                $this->insertExpiryNotificationContent($up, $up['daysLeft']);
                //  $this->deactivatePackage($up['userId'], $up['userPackageId']);
            } elseif ($up['daysLeft'] < 0 && ($up['daysLeft'] % 7) == 0) {
                $this->insertExpiryMailContent($up, $up['daysLeft']);
                $this->insertExpiryNotificationContent($up, $up['daysLeft']);
            } else {
                echo '<pre>';
                print_r('No Mail to send');
                echo '</pre>';
                //die('Macro Die');
            }
        }
        
        $this->insertExpiryReminder();
        $this->deactivatePackage();
        $this->deactivateTestPackage();
        die;
    }

    private function insertExpiryMailContent($packageDetails, $daysLeft) { //echo '<pre>';print_r($packageDetails);echo '</pre>';die('Macro Die');
        $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent');
        $validityDate = (array)$packageDetails['validTill'];
            
        // $preExpiryMailContent = file_get_contents('/index/expiry-mail.html'); //filePath
        //$preExpiryMailContent = file_get_contents(__DIR__ . '../../../../../Webpackage/view/webpackage/index/expiry-mail.html');
        $filepath= __DIR__ . '../../../../view/mailer/';
        $preExpiryMailContent = file_get_contents($filepath.'expiry-mail.html');
        $user_data = $this->getEmailFromUserId($packageDetails['userId']);
        $mailType = 'package_expiration';
        $to=$user_data['email'];

        $event = $this->getEvent();
        $requestURL = $event->getRequest();
        $router = $event->getRouter();
        $uri = $router->getRequestUri();
        $basepath = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
        
        $preExpiryMailContent = str_replace('{USER_NAME}', $user_data['display_name'], $preExpiryMailContent);
        $preExpiryMailContent = str_replace('{PACKAGE_NAME}', $packageDetails['packageName'], $preExpiryMailContent);
        $preExpiryMailContent = str_replace('{BOARD_NAME}', $packageDetails['board'], $preExpiryMailContent);
        $preExpiryMailContent = str_replace('{CLASS_NAME}', $packageDetails['class'], $preExpiryMailContent);
        $preExpiryMailContent = str_replace('{VALIDITY}', $validityDate['date'], $preExpiryMailContent);
        $preExpiryMailContent = str_replace('{SITE_URL}', $basepath, $preExpiryMailContent);
        $validTill = $packageDetails['validTill'];

       // $preExpiryMailContent = str_replace('{validTill}', $validTill->format('d M Y'), $preExpiryMailContent);
        $preExpiryMailContent = str_replace('{buy_package_link}', $basepath . '/package', $preExpiryMailContent);
       // $preExpiryMailContent = str_replace('{SUBCRIPTION_MESSAGE}', "<b>" . $packageDetails['packageName'] .'('.$packageDetails['board'].'-'. $packageDetails['class']. ')' . "</b>", $preExpiryMailContent);
        switch (true) {
            case $daysLeft > 0:
                $ordersubject = "Your Package Expiring Soon";
                $subcription_message='Your subscription for the '.$packageDetails['packageName'].' for class '.$packageDetails['class'].', Board '.$packageDetails['board'].' will expire on '.$validTill->format('d M Y').'. ';
                $preExpiryMailContent = str_replace('{SUBCRIPTION_MESSAGE}',  $subcription_message , $preExpiryMailContent);
                $message = $preExpiryMailContent;
                $emailData = array("email_id" => $to, 'subject' => $ordersubject, 'message' => $message, 'mail_type' => $mailType, 'status' => 1);

                break;
            case $daysLeft < 0:
                $ordersubject = "Your Package has been Expired";
                $subcription_message='Your subscription for the '.$packageDetails['packageName'].' for class '.$packageDetails['class'].', Board '.$packageDetails['board'].' has been expired on '.$validTill->format('d M Y').'. ';
                $preExpiryMailContent = str_replace('{SUBCRIPTION_MESSAGE}',  $subcription_message , $preExpiryMailContent);
                $message = $preExpiryMailContent;
                $emailData = array("email_id" => $to, 'subject' => $ordersubject, 'message' => $message, 'mail_type' => $mailType, 'status' => 1);

                break;
            case $daysLeft == 0:
                $ordersubject = "Your Package Expiring today";
                $subcription_message='Your subscription for the '.$packageDetails['packageName'].' for class '.$packageDetails['class'].', Board '.$packageDetails['board'].' has been expired on '.$validTill->format('d M Y').'. ';
                $preExpiryMailContent = str_replace('{SUBCRIPTION_MESSAGE}',  $subcription_message , $preExpiryMailContent);
                $message = $preExpiryMailContent;
                $emailData = array("email_id" => $to, 'subject' => $ordersubject, 'message' => $message, 'mail_type' => $mailType, 'status' => 1);

                break;
        } //echo $message;die;
        $emailData['created_time']=date('Y-m-d H:i:s');
        $mailContentTable->addEmailContent($emailData);
    }

    private function insertExpiryNotificationContent($packageDetails, $daysLeft) {
        if ($daysLeft == 0) {
            $daysLeft = 'today';
        } elseif ($daysLeft == 1) {
            $daysLeft = 'tomorrow';
        } else {
            $daysLeft = 'in ' . $daysLeft . ' days';
        }
        $event = $this->getEvent();
        $requestURL = $event->getRequest();
        $router = $event->getRouter();
        $uri = $router->getRequestUri();
        $basepath = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
        $buyurl= $basepath.'/package';
        $validTill = $packageDetails['validTill'];
        $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
        $notificationdata = array(
            'notification_text' => 'Your Subscription-<b>' . $packageDetails['packageName'].'('.$packageDetails['board'].'-'. $packageDetails['class']. ')</b> is going to expire ' . $daysLeft . '. Click <a href="'.$buyurl.'"><b>here</b> </a> if you want to continue the subscription. &nbsp;&nbsp;',
            'userid' => $packageDetails['userId'],
            'type_id' => 5, // package_expiry
            'relation_id' => '',
            'notification_url' => '',
            'created_by' => $packageDetails['userId'],
            'created_date' => date('Y-m-d H:i:s'),
        );
        $notificationtable->insertnotification($notificationdata);
    }

    private function deactivatePackage() {
        $packagetable = $this->getServiceLocator()->get('Package\Model\TuserpackageTable');
        $packagetable->deactivatepackage();
    }
    private function deactivateTestPackage() {
        $packagetable = $this->getServiceLocator()->get('Package\Model\TuserpackageTable');
        $packagetable->deactivatetestpackage();
    }
    private function insertExpiryReminder(){
        // $expiryMailContent = file_get_contents(__DIR__ . '../../../../../Webpackage/view/webpackage/index/expiry-notification.html');//die;
        $filepath= __DIR__ . '../../../../view/mailer/';
        $expiryMailContent = file_get_contents($filepath.'expiry-notification.html');
        $packagetable = $this->getServiceLocator()->get('Package\Model\TuserpackageTable');
        $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent');
        $userpackage=$packagetable->getuserPackagetobeExpire(); 

        $event = $this->getEvent();
        $requestURL = $event->getRequest();
        $router = $event->getRouter();
        $uri = $router->getRequestUri();
        $basepath = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
        
      $msg='<table border="1" cellspacing="0" cellpadding="0" width="100%">';
        
      $i=1;
      if(count($userpackage)>0 && !empty($userpackage)){
          $msg.='<tr><td><b>Sr. No.</b></td><td><b>Name</b></td><td><b>Email</b></td><td><b>Package Name</b></td><td><b>Valid Till</b></td></tr>';
      foreach($userpackage as $key=>$value){
          $msg .='<tr><td>'.$i.'</td><td>'. $value->first_name.'</td><td>'. $value->email.'</td><td>'.$value->package_name.'</td><td>'.date('d-m-Y',strtotime($value->valid_till)).'</td></tr>';
                $i++;
            }
      }else{
            $msg .='<tr><td colspan="5">No package expired</td></tr>';
        }
        $msg .='</table>';

        $expiryMailContent = str_replace('{UserList}', $msg, $expiryMailContent);
        $expiryMailContent = str_replace('{BASE_URL}', $basepath, $expiryMailContent);
        $expiryMailContent; //die;
        $subject = 'Expired Package List';
        $mailType = 'internal_tracking';
        $to = 'adil.saleem@extramarks.com,neha.dixit@extramarks.com,alok.vishwakarma@extramarks.com';
        $emailData = array("email_id" => $to, 'subject' => $subject, 'message' => $expiryMailContent, 'mail_type' => $mailType, 'status' => 1);
        $mailContentTable->addMultiEmailContent($emailData);
    }
    
    private function getEmailFromUserId($userid){
      
                    $usertable = $this->getServiceLocator()->get('Assessment\Model\userTable');
                    $userData = $usertable->getuserdetailsById($userid);
                    $userDataArray = $userData->current();
                if ($userDataArray->user_type_id== 1 && filter_var($userDataArray->emailId, FILTER_VALIDATE_EMAIL)) {
                    $userArray['email'] = $userDataArray->emailId;
                    $userArray['display_name'] = $userDataArray->display_name;
                } else if ($userDataArray->user_type_id== 1 && $userDataArray->parent_id!= '') {
                    $parent_id = $userDataArray->parent_id;
                    $parentData = $usertable->getuserdetailsById($parent_id);
                    $parentDataArray = $parentData->current(); //print_r($parentDataArray);die;
                    $userArray['email'] = $parentDataArray->emailId;
                    $userArray['display_name'] = $parentDataArray->display_name;
                } else {
                    $userArray['email'] =$userDataArray->emailId;
                    $userArray['display_name'] =$userDataArray->display_name;
                }
                
                return  $userArray;
    }
    
    public function datediffInWeeks($date1, $date2)
    {
        $startDateUnix = strtotime($date1);
        $endDateUnix = strtotime($date2);
        $currentDateUnix = $startDateUnix;
        $weekNumbers = array();
        while ($currentDateUnix < $endDateUnix) {
            $weekNumbers[] = date('W', $currentDateUnix);
            $currentDateUnix = strtotime('+1 week', $currentDateUnix);
        }
        return count($weekNumbers);
    }
    
    public function datediffInMonths($date1, $date2)
    {
        $startDateUnix = strtotime($date1);
        $endDateUnix = strtotime($date2);
        $currentDateUnix = $startDateUnix;
        $monthsDates = array();
        while ($currentDateUnix < $endDateUnix) {;
            $monthsDates[] = date("Y-m-d",$currentDateUnix);
            $currentDateUnix = strtotime('+1 month', $currentDateUnix);
        }
        //echo '<pre>'; print_r($monthsDates); exit;
        return count($monthsDates);
    }
    
    public function datediffInYears($date1, $date2)
    {
        $startDateUnix = strtotime($date1);
        $endDateUnix = strtotime($date2);
        $currentDateUnix = $startDateUnix;
        $yearsDates = array();
        while ($currentDateUnix < $endDateUnix) {;
            $yearsDates[] = date("Y-m-d",$currentDateUnix);
            $currentDateUnix = strtotime('+1 year', $currentDateUnix);
        }
        //echo '<pre>'; print_r($monthsDates); exit;
        return count($yearsDates);
    }
    
    public function datediffInDays($date1, $date2)
    {
        $startDateUnix = strtotime($date1);
        $endDateUnix = strtotime($date2);
        $currentDateUnix = $startDateUnix;
        $yearsDates = array();
        while ($currentDateUnix < $endDateUnix) {;
            $yearsDates[] = date("Y-m-d",$currentDateUnix);
            $currentDateUnix = strtotime('+1 day', $currentDateUnix);
        }
        //echo '<pre>'; print_r($monthsDates); exit;
        return count($yearsDates);
    }
    
    public function notificationcronAction() {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300);
        
        $offset=0;
        $limit=2000;
        $notificationService = $this->getServiceLocator()->get('notification\Model\NotificationTable');
        $allNotificationCount = $notificationService->getAllSeenNotificationOfAdminCount();
        if($allNotificationCount > 0) {
            while($offset <= $allNotificationCount) {
                $allNotificationRowSet = $notificationService->getAllSeenNotificationOfAdmin($offset,$limit);
                $offset=$offset+$limit;
                foreach($allNotificationRowSet as $notification) {
                    echo '<pre>'; print_r($notification); exit;
                    if($notification->notify_appear_type =='all_day') {
                        $notification_start_date = date("Y-m-d",strtotime($notification->notification_start_date));
                        $notification_end_date = date("Y-m-d",strtotime($notification->notification_end_date));
                        if($notification_start_date <= date('Y-m-d') && $notification_end_date >= date('Y-m-d')) {
                            if($notification->seen == 1) {
                                $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                $notificationService->updateStatus($notification->notification_id, $data);
                            }
                        }
                    } else if($notification->notify_appear_type =='recur') {
                        
                        if($notification->notification_occurrence_type == 'occurrence_no') {
                            $notification_start_date = date("Y-m-d",strtotime($notification->notification_start_date));
                            $notification_occurrence_no = $notification->notification_occurrence_no;

                            if($notification->notification_recurrence_on=='weekly') {
                                $finalWeekDate = date( "Y-m-d" , strtotime("$notification_start_date +".$notification_occurrence_no.' Weeks'));
                                if($finalWeekDate >= date("Y-m-d")) {
                                    for($i=0;$i<$notification_occurrence_no;$i++) {
                                        // block to send email for add learner in case of mentor
                                        if($notification->notification_name=='add learner') {
                                            // add learner weekly notification code block
                                        }
                                        if($i==0) {
                                            if($notification_start_date == date('Y-m-d')) {
                                                if($notification->seen==1){
                                                    $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                    $notificationService->updateStatus($notification->notification_id, $data);
                                                }
                                            }
                                        } else {
                                            $notification_week_date = date( "Y-m-d" , strtotime("$notification_start_date +".$i.' Weeks'));
                                            if($notification_week_date == date('Y-m-d')) {
                                                if($notification->seen==1) {
                                                    $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                    $notificationService->updateStatus($notification->notification_id, $data);
                                                }
                                            }
                                        }
                                    }
                                }
                            } else if($notification->notification_recurrence_on=='monthly'){
                                $finalMonthDate = date( "Y-m-d" , strtotime("$notification_start_date +".$notification_occurrence_no.' months'));
                                if($finalMonthDate >= date("Y-m-d")) {
                                    for($i=0;$i<$notification_occurrence_no;$i++) {
                                        if($i==0) {
                                            if($notification_start_date == date('Y-m-d')) {
                                                if($notification->seen==1){
                                                    $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                    $notificationService->updateStatus($notification->notification_id, $data);
                                                }
                                            }
                                        } else {
                                            $notification_month_date = date( "Y-m-d" , strtotime("$notification_start_date +".$i.' months'));
                                            if($notification_month_date == date('Y-m-d')) {
                                                if($notification->seen==1) {
                                                    $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                    $notificationService->updateStatus($notification->notification_id, $data);
                                                }
                                            }
                                        }
                                    }
                                }
                            } else if($notification->notification_recurrence_on=='yearly'){
                                $finalYearDate = date( "Y-m-d" , strtotime("$notification_start_date +".$notification_occurrence_no.' years'));
                                if($finalYearDate >= date("Y-m-d")) {
                                    for($i=0;$i<$notification_occurrence_no;$i++) {
                                        if($i==0) {
                                            if($notification_start_date == date('Y-m-d')) {
                                                if($notification->seen==1){
                                                    $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                    $notificationService->updateStatus($notification->notification_id, $data);
                                                }
                                            }
                                        } else {
                                            $notification_year_date = date( "Y-m-d" , strtotime("$notification_start_date +".$i.' years'));
                                            if($notification_year_date == date('Y-m-d')) {
                                                if($notification->seen==1) {
                                                    $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                    $notificationService->updateStatus($notification->notification_id, $data);
                                                }
                                            }
                                        }
                                    }
                                }
                            } else if($notification->notification_recurrence_on=='daily'){
                                $finalDateDate = date( "Y-m-d" , strtotime("$notification_start_date +".$notification_occurrence_no.' days'));
                                if($finalDateDate >= date("Y-m-d")) {
                                    for($i=0;$i<$notification_occurrence_no;$i++) {
                                        if($i==0) {
                                            if($notification_start_date == date('Y-m-d')) {
                                                if($notification->seen==1){
                                                    $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                    $notificationService->updateStatus($notification->notification_id, $data);
                                                }
                                            }
                                        } else {
                                            $notification_day_date = date( "Y-m-d" , strtotime("$notification_start_date +".$i.' days'));
                                            if($notification_day_date == date('Y-m-d')) {
                                                if($notification->seen==1) {
                                                    $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                    $notificationService->updateStatus($notification->notification_id, $data);
                                                }
                                            }
                                        }
                                    }
                                }
                            } else if($notification->notification_recurrence_on=='weekdays') {
                                $finalWeekDate = date( "Y-m-d" , strtotime("$notification_start_date +".$notification_occurrence_no.' Weeks'));
                                $startTime = strtotime($notification_start_date); // or your date as well
                                $endTime = strtotime($finalWeekDate);
                                $datediff = $startTime - $endTime;
                                $totalDays = floor($datediff/(60*60*24));

                                if($finalWeekDate >= date("Y-m-d")) {
                                    for($i=0;$i<$totalDays;$i++) {
                                        if($i==0) {
                                            if($notification_start_date == date('Y-m-d')) {
                                                if($notification->seen==1){
                                                    if(date('N', strtotime($notification_start_date)) < 6) {
                                                        $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                        $notificationService->updateStatus($notification->notification_id, $data);
                                                    }
                                                }
                                            }
                                        } else {
                                            $notification_week_date = date( "Y-m-d" , strtotime("$notification_start_date +".$i.' Weeks'));
                                            if($notification_week_date == date('Y-m-d')) {
                                                if($notification->seen==1) {
                                                    if(date('N', strtotime($notification_week_date)) < 6) {
                                                        $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                        $notificationService->updateStatus($notification->notification_id, $data);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            } else if($notification->notification_recurrence_on=='week-off'){
                                $finalWeekDate = date( "Y-m-d" , strtotime("$notification_start_date +".$notification_occurrence_no.' Weeks'));
                                $startTime = strtotime($notification_start_date); // or your date as well
                                $endTime = strtotime($finalWeekDate);
                                $datediff = $startTime - $endTime;
                                $totalDays = floor($datediff/(60*60*24));

                                if($finalWeekDate >= date("Y-m-d")) {
                                    for($i=0;$i<$totalDays;$i++) {
                                        if($i==0) {
                                            if($notification_start_date == date('Y-m-d')) {
                                                if($notification->seen==1){
                                                    if(date('N', strtotime($notification_start_date)) >= 6) {
                                                        $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                        $notificationService->updateStatus($notification->notification_id, $data);
                                                    }
                                                }
                                            }
                                        } else {
                                            $notification_week_date = date( "Y-m-d" , strtotime("$notification_start_date +".$i.' Weeks'));
                                            if($notification_week_date == date('Y-m-d')) {
                                                if($notification->seen==1) {
                                                    if(date('N', strtotime($notification_week_date)) >= 6) {
                                                        $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                        $notificationService->updateStatus($notification->notification_id, $data);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else if($notification->notification_occurrence_type == 'specific_date') {
                            $notification_start_date = date("Y-m-d",strtotime($notification->notification_start_date));
                            $notification_end_date = date("Y-m-d",strtotime($notification->notification_end_date));

                            if($notification->notification_recurrence_on=='weekly'){
                                $notification_occurrence_no = $this->datediffInWeeks($notification_start_date, $notification_end_date);
                                //$finalWeekDate = date( "Y-m-d" , strtotime("$notification_start_date +".$notification_occurrence_no.' Weeks'));
                                if($notification_end_date >= date("Y-m-d")) {
                                    for($i=0;$i<$notification_occurrence_no;$i++) {
                                        if($i==0) {
                                            if($notification_start_date == date('Y-m-d')) {
                                                if($notification->seen==1){
                                                    $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                    $notificationService->updateStatus($notification->notification_id, $data);
                                                }
                                            }
                                        } else {
                                            $notification_week_date = date( "Y-m-d" , strtotime("$notification_start_date +".$i.' Weeks'));
                                            if($notification_week_date == date('Y-m-d')) {
                                                if($notification->seen==1) {
                                                    $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                    $notificationService->updateStatus($notification->notification_id, $data);
                                                }
                                            }
                                        }
                                    }
                                }
                            } else if($notification->notification_recurrence_on=='monthly') {
                                $notification_occurrence_no = $this->datediffInMonths($notification_start_date, $notification_end_date);
                                if($notification_end_date >= date("Y-m-d")) {
                                    for($i=0;$i<$notification_occurrence_no;$i++) {
                                        if($i==0) {
                                            if($notification_start_date == date('Y-m-d')) {
                                                if($notification->seen==1){
                                                    $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                    $notificationService->updateStatus($notification->notification_id, $data);
                                                }
                                            }
                                        } else {
                                            $notification_month_date = date( "Y-m-d" , strtotime("$notification_start_date +".$i.' months'));
                                            if($notification_month_date == date('Y-m-d')) {
                                                if($notification->seen==1) {
                                                    $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                    $notificationService->updateStatus($notification->notification_id, $data);
                                                }
                                            }
                                        }
                                    }
                                }
                            } else if($notification->notification_recurrence_on=='yearly'){
                                $notification_occurrence_no = $this->datediffInYears($notification_start_date, $notification_end_date);
                                if($notification_end_date >= date("Y-m-d")) {
                                    for($i=0;$i<$notification_occurrence_no;$i++) {
                                        if($i==0) {
                                            if($notification_start_date == date('Y-m-d')) {
                                                if($notification->seen==1){
                                                    $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                    $notificationService->updateStatus($notification->notification_id, $data);
                                                }
                                            }
                                        } else {
                                            $notification_year_date = date( "Y-m-d" , strtotime("$notification_start_date +".$i.' years'));
                                            if($notification_year_date == date('Y-m-d')) {
                                                if($notification->seen==1) {
                                                    $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                    $notificationService->updateStatus($notification->notification_id, $data);
                                                }
                                            }
                                        }
                                    }
                                }
                            } else if($notification->notification_recurrence_on=='daily'){
                                $notification_occurrence_no = $this->datediffInDays($notification_start_date, $notification_end_date);
                                if($notification_end_date >= date("Y-m-d")) {
                                    for($i=0;$i<$notification_occurrence_no;$i++) {
                                        if($i==0) {
                                            if($notification_start_date == date('Y-m-d')) {
                                                if($notification->seen==1){
                                                    $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                    $notificationService->updateStatus($notification->notification_id, $data);
                                                }
                                            }
                                        } else {
                                            $notification_day_date = date( "Y-m-d" , strtotime("$notification_start_date +".$i.' days'));
                                            if($notification_day_date == date('Y-m-d')) {
                                                if($notification->seen==1) {
                                                    $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                    $notificationService->updateStatus($notification->notification_id, $data);
                                                }
                                            }
                                        }
                                    }
                                }
                            } else if($notification->notification_recurrence_on=='weekdays'){
                                $finalWeekDate = $notification_end_date;
                                $startTime = strtotime($notification_start_date); // or your date as well
                                $endTime = strtotime($finalWeekDate);
                                $datediff = $startTime - $endTime;
                                $totalDays = floor($datediff/(60*60*24));

                                if($finalWeekDate >= date("Y-m-d")) {
                                    for($i=0;$i<$totalDays;$i++) {
                                        if($i==0) {
                                            if($notification_start_date == date('Y-m-d')) {
                                                if($notification->seen==1){
                                                    if(date('N', strtotime($notification_start_date)) < 6) {
                                                        $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                        $notificationService->updateStatus($notification->notification_id, $data);
                                                    }
                                                }
                                            }
                                        } else {
                                            $notification_week_date = date( "Y-m-d" , strtotime("$notification_start_date +".$i.' Weeks'));
                                            if($notification_week_date == date('Y-m-d')) {
                                                if($notification->seen==1) {
                                                    if(date('N', strtotime($notification_week_date)) < 6) {
                                                        $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                        $notificationService->updateStatus($notification->notification_id, $data);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            } else if($notification->notification_recurrence_on=='week-off'){
                                $finalWeekDate = $notification_end_date;
                                $startTime = strtotime($notification_start_date); // or your date as well
                                $endTime = strtotime($finalWeekDate);
                                $datediff = $startTime - $endTime;
                                $totalDays = floor($datediff/(60*60*24));

                                if($finalWeekDate >= date("Y-m-d")) {
                                    for($i=0;$i<$totalDays;$i++) {
                                        if($i==0) {
                                            if($notification_start_date == date('Y-m-d')) {
                                                if($notification->seen==1){
                                                    if(date('N', strtotime($notification_start_date)) >= 6) {
                                                        $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                        $notificationService->updateStatus($notification->notification_id, $data);
                                                    }
                                                }
                                            }
                                        } else {
                                            $notification_week_date = date( "Y-m-d" , strtotime("$notification_start_date +".$i.' Weeks'));
                                            if($notification_week_date == date('Y-m-d')) {
                                                if($notification->seen==1) {
                                                    if(date('N', strtotime($notification_week_date)) >= 6) {
                                                        $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                                        $notificationService->updateStatus($notification->notification_id, $data);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }    
                            }
                        }
                    } else if($notification->notify_appear_type =='one_time') {
                        $notification_start_date = date("Y-m-d",strtotime($notification->notification_start_date));
                        $notification_end_date = date("Y-m-d",strtotime($notification->notification_end_date));
                        if($notification_start_date <= date('Y-m-d') && $notification_end_date >= date('Y-m-d')) {
                            if($notification->seen == 1 && $notification->modified_date == '0000-00-00 00:00:00') {
                                $data = array('seen' => '0','modified_date' => date('Y-m-d h:i:s'));
                                $notificationService->updateStatus($notification->notification_id, $data);
                            }
                        }
                    }
                }
            }
        }
        die('Notifications Updated'); exit;
    }
    
    public function baseUrl(){
        $event = $this->getEvent();
        $requestURL = $event->getRequest();
        $router = $event->getRouter();
        $uri = $router->getRequestUri();
        $basepath = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
        return $basepath;
    }
    
    private function sendExpirySms() { 
        $packagetable = $this->getServiceLocator()->get('Package\Model\TuserpackageTable');
        $packageArray=$packagetable->getPackageForSMS();
        $baseUrl = $this->baseUrl();
        $marginal_limit=500;
        
        if(count($packageArray)<=$marginal_limit && count($packageArray)>=1){
            $totalSms=0;
            foreach($packageArray as $value){
           if($value->mobile!='' && strlen(substr($value->mobile,-10))==10){
           $mobileNo=$value->mobile;
           }else{
           $mobileArray=$this->getMobileFromUserId($value->user_id);
           $mobileNo=$mobileArray['mobile'];
           }
           $mobileArr = explode('-',$mobileNo);
           $mobile_number = $mobileArr[1];
           $smsDataArray['mobile']=$mobileNo;
           $smsDataArray['board']=$value->board;
           $smsDataArray['class']=$value->class;
           $smsDataArray['user_id']=$value->user_id;
           if(($value->package_category=='2' && $value->class=='XII') || ($value->package_category=='1' && $value->class=='XI')){
              continue;
           }else{ 
                if(preg_match('/^\d{10}$/',$mobile_number)) {
                    if($value->date_diff==15) {
                        $smsDataArray['msg'] = 'Your class '.$value->class.' '.$value->package_name.' will expire in less than 15 days. Log on to http://www.extramarks.com/package and renew now.';
                        $this->sendSms($smsDataArray);
                    } else if($value->date_diff==7) {
                        $smsDataArray['msg']='Continue enjoying uninterrupted Extramarks services by renewing your '.$value->package_name.'. Log on to http://www.extramarks.com/package and Validity expires in 7 days.';
                        $this->sendSms($smsDataArray);
                    } else if($value->date_diff==0) {
                        $smsDataArray['msg']='The validity of your '.$value->package_name.' for Class '.$value->class.' will expire today. Call now at 1800-102-5301 to renew subscription.';
                        $this->sendSms($smsDataArray);
                    }else if($value->date_diff==-1) {
                        $smsDataArray['msg']='The validity of your '.$value->package_name.' for Class '.$value->class.' expired yesterday. Call now at 1800-102-5301 to renew subscription.';
                        $this->sendSms($smsDataArray);
                        }
                } else {
                    continue;
                }
           }
           $totalSms++;
           $smsReportArray['display_name'][]=$value->first_name;
           $smsReportArray['mobile'][]=$smsDataArray['mobile'];
           $smsReportArray['board'][]=$smsDataArray['board'];
           $smsReportArray['class'][]=$smsDataArray['class'];
           $smsReportArray['text'][]=$smsDataArray['msg'];
           $smsReportArray['flag'][]='off';
        }
        $this->insertSMSreport($smsReportArray);
       }elseif(count($packageArray)>$marginal_limit){
           $smsReportArray['text'][]='Limit exceeded for sending sms';
           $smsReportArray['flag'][]='on';
           $smsReportArray['total_records'][] = count($packageArray);
           $smsReportArray['marginal_limit'][] = $marginal_limit;
           $this->insertSMSreport($smsReportArray);
      }else{
           $smsReportArray['text'][]='No sms to send';
           $smsReportArray['flag'][]='on';
           $this->insertSMSreport($smsReportArray);
      }
        echo 'msg sent';die;
        //echo '<pre>';print_r($packageArray);echo '</pre>';die('Macro Die');
    }
    
    private function sendSms($smsDataArray) {
         $config=$this->getServiceLocator()->get('config');
                $defaultstates = $config['msg_engine'];// get the msg config data
                if($defaultstates['status'] == 'ON'){
                   $msglog  = $this->getServiceLocator()->get('Package\Model\TmsgLogTable');         
                                   
                   $msgTxt = $smsDataArray['msg'];
                   $usermobile = $smsDataArray['mobile'];
                   $mobile     = explode("-", $usermobile);
                   $mob_number = str_replace('+','',$mobile[0]).$mobile[1];
                   $smsArr = array('to_mobile_number'=>$mob_number,
                        'msg_txt' => $msgTxt,
                        'user_id' => $smsDataArray['user_id'],
                        'mobile_number' => $usermobile,
                        'sms_type' => 'package_expiry'
                    );
                   $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                   $data = $comMapperObj->smssendprocess($smsArr);
                   /*$urltohit = "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=347144&username=9811816077&password=agmtj&To=".$mob_number."&Text=".urlencode($msgTxt); 
                   $datas = file_get_contents($urltohit);
                   $data = array('user_id'=>$smsDataArray['user_id'],'mobile_number'=>$usermobile,'api_response'=>$datas,'sms_type'=>'package_expiry');
                   */
                   $result = $msglog->addlog($data); 
                   //$result   = $this->get_content($urltohit,$fields_string);
                }
    }
    private function getMobileFromUserId($userid){
                 $usertable = $this->getServiceLocator()->get('Assessment\Model\userTable');
                    $userData = $usertable->getuserdetailsById($userid);
                    $userDataArray = $userData->current();
                if ($userDataArray->mobile!= '' && strlen(substr($userDataArray->mobile,-10))==10) {
                    $userArray['mobile'] = $userDataArray->mobile;
                } else if ($userDataArray->user_type_id== 1 && $userDataArray->parent_id!= '') {
                    $parent_id = $userDataArray->parent_id;
                    $parentData = $usertable->getuserdetailsById($parent_id);
                    $parentDataArray = $parentData->current(); //print_r($parentDataArray);die;
                    $userArray['mobile'] = $parentDataArray->mobile;
                }else{
                    $userArray['mobile'] = $userDataArray->mobile;
                } 
                return  $userArray;
    }
    private function insertSMSreport($smsReportArray) {
        $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent');
        $filepath= __DIR__ . '../../../../view/mailer/';
        $smsMailContent = file_get_contents($filepath.'expiry-sms-report.html');
        $event = $this->getEvent();
        $requestURL = $event->getRequest();
        $router = $event->getRouter();
        $uri = $router->getRequestUri();
        $basepath = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
        $msg = '<table border="1" cellspacing="0" cellpadding="0" width="100%">';
        //print_r( $smsReportArray['flag']);die;
        if($smsReportArray['flag'][0]=='off'){
        $msg.='<tr><td><b>Sr. No.</b></td><td><b>Name</b></td><td><b>Mobile No</b></td><td><b>Board</b></td><td><b>Class</b></td><td><b>Message</b></td></tr>';
        for ($i=0;$i<count($smsReportArray['display_name']);$i++) {
            $msg .='<tr><td>' . ($i+1) . '</td><td>' . $smsReportArray['display_name'][$i] . '</td><td>' . $smsReportArray['mobile'][$i] . '</td><td>' . $smsReportArray['board'][$i] . '</td><td>' . $smsReportArray['class'][$i] . '</td><td>' . $smsReportArray['text'][$i] . '</td></tr>';
        }
        }else{
            $msg .= '<tr><td colspan="2"><center>' . $smsReportArray['text'][0] . '</center></td></tr>';
            $msg .= '<tr><td>Allowed Limit</td><td>' . $smsReportArray['marginal_limit'][0] . '</td></tr>';
            $msg .= '<tr><td>Total SMS to be send</td><td>' . $smsReportArray['total_records'][0] . '</td></tr>';
            $msg .= '<tr><td>Exceed Limit</td><td>' . ($smsReportArray['total_records'][0] -  $smsReportArray['marginal_limit'][0]) . '</td></tr>';
        }
        $msg .='</table>';
        $smsMailContent = str_replace('{UserList}', $msg, $smsMailContent);
        $smsMailContent = str_replace('{BASE_URL}', $basepath, $smsMailContent);
        $subject = 'Expired SMS List';
        $mailType = 'internal_tracking';
        $to = 'adil.saleem@extramarks.com,neha.dixit@extramarks.com,alok.vishwakarma@extramarks.com';
        $emailData = array("email_id" => $to, 'subject' => $subject, 'message' => $smsMailContent, 'mail_type' => $mailType, 'status' => 1);
        $mailContentTable->addMultiEmailContent($emailData);
    }
    
    public function updateScheduleFunction(){
        
        //ini_set('display_errors', E_ALL);
        $currentDate = date('Y-m-d H:i:s');
        $scheduleDateFrom = date('Y-m-d H:i:s', strtotime('-1 day', strtotime($currentDate)));
        $scheduleDateTo = date('Y-m-d H:i:s');
        
        $pkgCatTable = $this->getServiceLocator()->get('Package\Model\TpackagecategoryTable');
        $updateScheduleTable = $this->getServiceLocator()->get('Admin\Model\UpdateScheduleTable');
        $updateScheduleDetailsTable = $this->getServiceLocator()->get('Admin\Model\UpdateScheduleDetailsTable');
        $packageTable = $this->getServiceLocator()->get('Package\Model\TpackageTable');
        $currencyTable = $this->getServiceLocator()->get('Package\Model\TcurrencyTable');
        
        $updateScheduleData = $updateScheduleTable->getUpdateScheduleDataForCron($scheduleDateFrom, $scheduleDateTo);
        
        $i = 1;
        foreach($updateScheduleData as $data) {
           $updateScheduleId = $data->update_schedule_id;
           $updateScheduleDetailsData = $updateScheduleDetailsTable->getUpdateScheduledDetails($updateScheduleId);
           $updatePackageData = array();
           $currencyUpdateData = array();
           $changesData = array();
           $currencyChangesDetails = array();
           $previousPackageDetails = array();
           $currencyDetails = array();
           $currencyUSDAutomatic = true;
           $currencySGDAutomatic = true;
           if(count($updateScheduleDetailsData)) {
               foreach($updateScheduleDetailsData as $updateDetailData) {
                   //echo "<pre>"; print_r($updateDetailData);
                   $packageId = $updateDetailData->package_id;
                   $packageObj = $packageTable->getPackageById($packageId)->current();
                   //echo "<pre>"; print_r($packageObj);

                   $previousPackageDetails[$packageId] = array(
                       'package_name' => $packageObj->package_name,
                       'price' => $packageObj->price,
                       'tax' => $packageObj->tax,
                       'is_offer' => $packageObj->is_offer,
                       'offer_quote' => $packageObj->offer_quote,
                       'days' => $packageObj->days,
                       'valid_date' => $packageObj->valid_date,
                       'display_validity_date' => $packageObj->display_validity_date,
                       'is_active' => $packageObj->is_active,
                       'is_usd_saved' => $packageObj->is_usd_saved,
                       'is_sgd_saved' => $packageObj->is_sgd_saved,
                   );

                   $isOffer = false;
                   $changedOfferVal = "";
                   $changedTax = "";
                   $originalPrice = 0;
                   $unitPrice = 0;
                   $discount = 0;
                   $changedOfferValArray = array();
                   if($updateDetailData->update_key == 'package_offer') {
                       $isOffer = true;
                       $changedOfferVal = $updateDetailData->changed_value;
                       $changedOfferValArray = explode('#', $changedOfferVal);
                   }           

                   if($updateDetailData->update_key == 'package_name') {
                       $changedNameVal = $updateDetailData->changed_value;
                       $updatePackageData[$packageId]['package_name'] = $changedNameVal;                   
                   }

                   if($updateDetailData->update_key == 'package_status') {
                       $changedStatusVal = $updateDetailData->changed_value;
                       $updatePackageData[$packageId]['is_active'] = $changedStatusVal;
                   }
                   
                   if($updateDetailData->update_key == 'package_price') {
                       $changedPriceVal = $updateDetailData->changed_value;
                       $priceArray = explode('-', $changedPriceVal);
                       $usdPriceArray = array();
                        if(isset($priceArray[1])) {
                            $usdPriceArray = explode('#', $priceArray[1]);
                        }
                        
                        $sgdPriceArray = array();
                        if(isset($priceArray[1])) {
                            $sgdPriceArray = explode('#', $priceArray[2]);
                        }
                        //echo "<pre>"; print_r($usdPriceArray);
                        //echo "<pre>"; print_r($sgdPriceArray);
                        if(isset($usdPriceArray[1]) && $usdPriceArray[1] == 'm') {
                            $currencyUSDAutomatic = false;
                            $updatePackageData[$packageId]['is_usd_saved'] = 'yes';
                        } else if(isset($usdPriceArray[1]) && $usdPriceArray[1] == 'a') {
                            $updatePackageData[$packageId]['is_usd_saved'] = 'no';
                        } else {
                            $updatePackageData[$packageId]['is_usd_saved'] = $packageObj->is_usd_saved;
                        }
                        
                        if(isset($sgdPriceArray[1]) && $sgdPriceArray[1] == 'm') {
                            $currencySGDAutomatic = false;
                            $updatePackageData[$packageId]['is_sgd_saved'] = 'yes';
                        } else if(isset($sgdPriceArray[1]) && $sgdPriceArray[1] == 'a') {
                            $updatePackageData[$packageId]['is_sgd_saved'] = 'no';
                        } else {
                            $updatePackageData[$packageId]['is_sgd_saved'] = $packageObj->is_sgd_saved;
                        }
                   }
                   
                   if($updateDetailData->update_key != 'package_status' && $updateDetailData->update_key != 'package_validity') {
                   
                       $currencyPricesPkgData = $currencyTable->getCurrencyPricesByPackagIdScheduleId($packageId, $updateScheduleId);
                       if(count($currencyPricesPkgData)) {
                           foreach($currencyPricesPkgData as $currencyData) {

                               if($updateDetailData->update_key == 'package_tax') {
                                   $changedTax = $currencyData->tax;
                               } else if($currencyData->currency_type == 'INR') {
                                   $changedTax = $packageObj->tax;
                               } else {
                                   $currentCurrencyPkgData = $currencyTable->getCurrencyPricesByPackagIdCurrency($packageId, $currencyData->currency_type);
                                   if(count($currentCurrencyPkgData)) {
                                        $changedTax = $currentCurrencyPkgData->current()->tax;
                                   }
                               }

                               $currenyPrice = $currencyData->price;

                               if($isOffer) {

                                   if($currencyData->price == 0 && $updateDetailData->update_key == 'package_offer') {
                                       if($currencyData->currency_type == 'INR') {
                                            $currenyPrice = $packageObj->price;
                                       } else {
                                            $currentCurrencyPkgData = $currencyTable->getCurrencyPricesByPackagIdCurrency($packageId, $currencyData->currency_type);
                                            if(count($currentCurrencyPkgData)) {
                                                $currenyPrice = $currentCurrencyPkgData->current()->price;
                                            }
                                       }
                                   }

                                   $discount = ($currenyPrice*$changedOfferValArray[0])/100;
                                   $originalPrice = $currenyPrice + $discount;
                               }


                               if($changedTax != "") {                               
                                   if($currencyData->price == 0 && $updateDetailData->update_key == 'package_tax') {
                                       if($currencyData->currency_type == 'INR') {
                                            $currenyPrice = $packageObj->price;
                                       } else {
                                            $currentCurrencyPkgData = $currencyTable->getCurrencyPricesByPackagIdCurrency($packageId, $currencyData->currency_type);
                                            if(count($currentCurrencyPkgData)) {
                                                $currenyPrice = $currentCurrencyPkgData->current()->price;
                                            }
                                       }
                                   }

                                   $unitPriceAddition = ($currenyPrice*$changedTax)/100;
                                   $unitPrice = ($currenyPrice - $unitPriceAddition);
                               }

                               if($updateDetailData->update_key == 'package_price') {
                                   if($currencyData->currency_type == 'INR') {
                                        $updatePackageData[$packageId]['price'] = $currencyData->price;                               
                                   }
                                   $currencyUpdateData[$packageId][$currencyData->currency_type]['price'] = $currencyData->price;
                               } else if($updateDetailData->update_key != 'package_offer'){
                                   if($updateDetailData->update_key == 'package_tax') {
                                       if($currencyData->currency_type == 'INR') {
                                            $updatePackageData[$packageId]['price'] = $currenyPrice;
                                            $currencyUpdateData[$packageId][$currencyData->currency_type]['price'] = $currenyPrice;
                                       } else {
                                            $currentCurrencyPkgData = $currencyTable->getCurrencyPricesByPackagIdCurrency($packageId, $currencyData->currency_type);
                                            if(count($currentCurrencyPkgData)) {
                                                //$priceforOtherCurrAddition = ($currenyPrice*$currencyData->tax)/100;
                                                //$priceforOtherCurr = $currenyPrice - $priceforOtherCurrAddition;
                                                $currencyUpdateData[$packageId][$currencyData->currency_type]['price'] = $currenyPrice;
                                            }
                                       }
                                   }
                               }

                               if($updateDetailData->update_key == 'package_tax') {
                                   if($currencyData->currency_type == 'INR') {
                                        $updatePackageData[$packageId]['tax'] = $currencyData->tax;
                                        $updatePackageData[$packageId]['unit_price'] = $unitPrice;
                                   }
                                   $currencyUpdateData[$packageId][$currencyData->currency_type]['tax'] = $currencyData->tax;
                                   $currencyUpdateData[$packageId][$currencyData->currency_type]['unit_price'] = $unitPrice;
                               } else if($currencyData->currency_type == 'INR' && $updateDetailData->update_key != 'package_offer') {
                                   $updatePackageData[$packageId]['unit_price'] = $unitPrice;
                                   $currencyUpdateData[$packageId][$currencyData->currency_type]['unit_price'] = $unitPrice;
                               } else if($updateDetailData->update_key != 'package_offer'){
                                   $currencyUpdateData[$packageId][$currencyData->currency_type]['unit_price'] = $unitPrice;
                               }

                               if($updateDetailData->update_key == 'package_offer') {
                                   if($currencyData->currency_type == 'INR') {
                                        $updatePackageData[$packageId]['orginal_price'] = $originalPrice;
                                   }
                                   $updatePackageData[$packageId]['is_offer'] = $currencyData->is_offer;
                                   $updatePackageData[$packageId]['offer_quote'] = $currencyData->offer_quote;
                                   $currencyUpdateData[$packageId][$currencyData->currency_type]['original_price'] = $originalPrice;
                                   $currencyUpdateData[$packageId][$currencyData->currency_type]['is_offer'] = $currencyData->is_offer;
                                   $currencyUpdateData[$packageId][$currencyData->currency_type]['offer_quote'] = $currencyData->offer_quote;
                               }
                           }                   
                       }
                   }

                   if($updateDetailData->update_key == 'package_validity') {
                        $changedStatusVal = $updateDetailData->changed_value;
                        if (preg_match('/-/', $changedStatusVal)) {
                            $updatePackageData[$packageId]['valid_date'] = $changedStatusVal;
                            $updatePackageData[$packageId]['days'] = 0;
                            $updatePackageData[$packageId]['display_validity_date'] = '';
                        } else {
                            $changedStatusValArray = explode('#', $changedStatusVal);
                            $updatePackageData[$packageId]['days'] = (isset($changedStatusValArray[0]) ? $changedStatusValArray[0] : '');
                            $updatePackageData[$packageId]['display_validity_date'] = (isset($changedStatusValArray[1]) ? $changedStatusValArray[1] : '');
                            $updatePackageData[$packageId]['valid_date'] = '0000-00-00';
                        }                    
                   }               
               }
           }
           
           //echo "=========================Update schedule (" . $data->update_name . ") - " . $i . " ==================================";
           //echo "<pre>"; print_r($updatePackageData);
           //echo "--------------------------------------------------------------------------------------";
           //echo "<pre>"; print_r($currencyUpdateData);
           
           // update package function here
           if(count($updatePackageData)) {
               foreach($updatePackageData as $pkgIdKey => $pkgUpdateData) {
                   $currentPackageObj = $packageTable->getPackageById($pkgIdKey)->current();
                   $is_usd_saved = $pkgUpdateData['is_usd_saved'];
                   $is_sgd_saved = $pkgUpdateData['is_sgd_saved'];
                   
                   $currentCurrencyPricesUsdPkgData = $currencyTable->getCurrencyPricesByPackagIdCurrency($pkgIdKey, 'USD');
                   $currentCurrencyPricesSgdPkgData = $currencyTable->getCurrencyPricesByPackagIdCurrency($pkgIdKey, 'SGD');
                   
                   // collecting prev currency details in case of not updating the other currencies                   
                   if(count($currentCurrencyPricesUsdPkgData)) {
                       $currencyPricesPkgUsdDataArray = $currentCurrencyPricesUsdPkgData->current();
                       $currencyDetails[$pkgIdKey]['USD']['tax'] = $currencyPricesPkgUsdDataArray->tax;
                       $currencyDetails[$pkgIdKey]['USD']['price'] = $currencyPricesPkgUsdDataArray->price;
                   }
                   if(count($currentCurrencyPricesSgdPkgData)) {
                       $currencyPricesPkgSgdDataArray = $currentCurrencyPricesSgdPkgData->current();
                       $currencyDetails[$pkgIdKey]['SGD']['tax'] = $currencyPricesPkgSgdDataArray->tax;
                       $currencyDetails[$pkgIdKey]['SGD']['price'] = $currencyPricesPkgSgdDataArray->price;
                   }
                   
                   if(count($currencyUpdateData)) {
                       if((isset($currencyUpdateData[$pkgIdKey]) && array_key_exists('USD', $currencyUpdateData[$pkgIdKey]))) {
                       //if(($currentCurrencyPricesUsdPkgData->count() > 0 && $currentCurrencyPricesUsdPkgData->current()->price > 0)) {
                           $is_usd_saved = 'yes';
                       }
                       if((isset($currencyUpdateData[$pkgIdKey]) && array_key_exists('SGD', $currencyUpdateData[$pkgIdKey]))) {
                       //if(($currentCurrencyPricesSgdPkgData->count() > 0 && $currentCurrencyPricesSgdPkgData->current()->price > 0)) {
                           $is_sgd_saved = 'yes';
                       }
                   }
                   $pkgUpdateData['is_usd_saved'] = $is_usd_saved;
                   $pkgUpdateData['is_sgd_saved'] = $is_sgd_saved;
                   //echo "<pre>"; print_r($pkgUpdateData);
                   $packageTable->updatePackageViaSchedule($pkgUpdateData, $pkgIdKey);
               }
           }
           
           // changes in package collecting the values               
           $changesData = $updatePackageData;
           
           // update currency details to t_currency table           
           if(count($currencyUpdateData)) {
               foreach($currencyUpdateData as $pkgIdKey => $currUpData) {
                   foreach($currUpData as $currencyKey => $updateCurrencyData) {
                       $currentCurrencyPricesPkgData = $currencyTable->getCurrencyPricesByPackagIdCurrency($pkgIdKey, $currencyKey);
                       if (count($currentCurrencyPricesPkgData)) {
                           $currentCurrencyPricesPkgDataArray = $currentCurrencyPricesPkgData->current();
                           
                           $updateCurrencyData['modified_by'] =  $data->created_by;
                           $currencyTable->updateCurrencyPricesViaSchedule($updateCurrencyData, $pkgIdKey, $currencyKey);
                           
                           // collecting prev currency details
                           $currencyDetails[$pkgIdKey][$currencyKey]['tax'] = $currentCurrencyPricesPkgDataArray->tax;
                           $currencyDetails[$pkgIdKey][$currencyKey]['price'] = $currentCurrencyPricesPkgDataArray->price;
                       } else {
                           $updateCurrencyData['created_date'] =  date('Y-m-d H:i:s');
                           $currencyTable->addCurrencyPricesViaSchedule($updateCurrencyData);
                       }
                       
                       // collecting the changes against of currency
                       $currencyChangesDetails[$pkgIdKey][$currencyKey] = $updateCurrencyData;
                   }
               }
           }
           
           // update status 2 as executed of update schedule
           $dataUpdateSchedule = array();
           $dataUpdateSchedule['status'] = 2;
           $dataUpdateSchedule['modified_by'] = $data->created_by; 
           $updateScheduleTable->updateStatusScheduled($dataUpdateSchedule, $updateScheduleId);
           $dataUpdateDetails = array();
           $dataUpdateDetails['status'] = 2;
           $dataUpdateDetails['modified_by'] = $data->created_by;
           $updateScheduleDetailsTable->updateStatusScheduledDetails($dataUpdateDetails, $updateScheduleId);
           
           //echo "<pre>"; print_r($changesData);
           //echo "<pre>"; print_r($currencyChangesDetails);
           //echo "<pre>"; print_r($currencyDetails);
           //echo "<pre>"; print_r($previousPackageDetails);
           
           // created changes message for email confirmation 
           // if changes done by update schedule
           if(count($changesData)) {
               $j = 1;
               //$packagesMsgDetails = "<table border='1'>";       
               $packagesMsgDetails = ""; 
               foreach($changesData as $pkgKey => $changedData) {
                   //echo "<pre>"; print_r($changedData);
                   $packageObj = $packageTable->getPackageById($pkgKey)->current();
                   
                   $packagesMsgDetails .= "<tr>";
                   
                   $prevPackageDetails = "";
                   $newPackageDetails = "";
                   $prevPackageDetailsOriginal = "";
                   $srNo = "";
                   
                   $srNo .= "<td style='border: 1px solid #999;padding:5px 5px;'>". $j ."</td>";                                
                   $prevPackageDetailsOriginal .= "<td style='font-size:13px; border: 1px solid #999;padding:5px 5px;'> 
                       <strong>Package Name:</strong> " . $previousPackageDetails[$pkgKey]['package_name'] ."<br/>
                       <strong>Package Price :</strong> INR&nbsp;" . $previousPackageDetails[$pkgKey]['price'] . 
                         " USD&nbsp;" . $currencyDetails[$pkgKey]['USD']['price'] .
                         " SGD&nbsp;" . $currencyDetails[$pkgKey]['SGD']['price'] . "<br/>
                       <strong>Automatic Convert to :</strong> " . 
                        (($previousPackageDetails[$pkgKey]['is_usd_saved'] != 'yes') ? " USD Automatic," : " USD Manual,") . 
                        (($previousPackageDetails[$pkgKey]['is_sgd_saved'] != 'yes') ? " SGD Automatic" : " SGD Manual") .  "<br/>
                       <strong>Tax :</strong> INR - " . $previousPackageDetails[$pkgKey]['tax'] . '%' . 
                         " USD - " . $currencyDetails[$pkgKey]['USD']['tax'] . '%' .
                         " SGD - " . $currencyDetails[$pkgKey]['SGD']['tax'] . '%' . "<br/>
                       <strong>Offer Availability :</strong> " . (!empty($previousPackageDetails[$pkgKey]['is_offer']) ? $previousPackageDetails[$pkgKey]['is_offer'] : 'no') . "<br/>
                       <strong>Offer Quotes :</strong> " . $previousPackageDetails[$pkgKey]['offer_quote'] . "<br/>
                       <strong>Package Valid till :</strong> " . (($previousPackageDetails[$pkgKey]['days'] == 0) ? $previousPackageDetails[$pkgKey]['valid_date'] : $previousPackageDetails[$pkgKey]['display_validity_date']) . "<br/>
                       </td>";
                   
                   // prev package details for mail
                   $prevPackageDetails .= '<td style="font-size:13px; border: 1px solid #999;padding:5px 5px;">';                   
                   
                   if(array_key_exists('package_name', $changedData)) {
                        $prevPackageDetails .= "<strong>Package Name:</strong> " . $previousPackageDetails[$pkgKey]['package_name']. "<br/>";
                   }
                   
                   if(array_key_exists('price', $changedData)) {
                       $prevPackageDetails .= "<strong>Package Price :</strong> INR&nbsp;" . $previousPackageDetails[$pkgKey]['price'] . 
                             (isset($currencyDetails[$pkgKey]['USD']['price']) ? " USD&nbsp;" . $currencyDetails[$pkgKey]['USD']['price'] : "") .
                             (isset($currencyDetails[$pkgKey]['SGD']['price']) ? " SGD&nbsp;" . $currencyDetails[$pkgKey]['SGD']['price'] : "") . "<br/>";
                       
                       $prevPackageDetails .= "<strong>Automatic Convert to :</strong> " . 
                             (($previousPackageDetails[$pkgKey]['is_usd_saved'] != 'yes') ? " USD Automatic" : " USD Manual") . 
                             (($previousPackageDetails[$pkgKey]['is_sgd_saved'] != 'yes') ? " SGD Automatic" : " SGD Manual") .  "<br/>";
                   }
                   
                   if(array_key_exists('tax', $changedData)) {
                       $prevPackageDetails .= "<strong>Tax :</strong> INR - " . $previousPackageDetails[$pkgKey]['tax'] . '%' . 
                             (isset($currencyDetails[$pkgKey]['USD']['tax']) ? " USD - " . $currencyDetails[$pkgKey]['USD']['tax'] : "") . '%' .
                             (isset($currencyDetails[$pkgKey]['SGD']['tax']) ? " SGD - " . $currencyDetails[$pkgKey]['SGD']['tax'] : "") . '%' . "<br/>";
                   }
                   
                   if(array_key_exists('is_active', $changedData)) {
                       $prev_package_status = "";
                        if($previousPackageDetails[$pkgKey]['is_active'] == 1) {
                            $prev_package_status = "Activate";
                        } else {
                            $prev_package_status = "Deactivate";
                        }
                        $prevPackageDetails .= "<strong>Package Status :</strong> " . $prev_package_status . "<br/>";                       
                   }
                   
                   if(array_key_exists('offer_quote', $changedData)) {
                       $prevPackageDetails .= "<strong>Offer Availability :</strong> " . $previousPackageDetails[$pkgKey]['is_offer'] . "<br/>";
                       if($previousPackageDetails[$pkgKey]['is_offer'] == 'yes') {
                            $prevPackageDetails .= "<strong>Offer Quotes :</strong> " . $previousPackageDetails[$pkgKey]['offer_quote'] . "<br/>";
                       }
                   }
                   
                   if(array_key_exists('days', $changedData)) {
                        $prevPackageDetails .= "<strong>Package valid for :</strong> Days<br/>";
                        $prevPackageDetails .= "<strong>Number of days :</strong> " . $previousPackageDetails[$pkgKey]['days'] . "<br/>";
                   }
                   
                   if(array_key_exists('valid_date', $changedData) && $changedData['valid_date'] != '0000-00-00') {
                        $prevPackageDetails .= "<strong>Package valid for :</strong> Valid Date<br/>";
                        $prevPackageDetails .= "<strong>Valid Date :</strong> " . (($previousPackageDetails[$pkgKey]['days'] == 0) ? $previousPackageDetails[$pkgKey]['valid_date'] : $previousPackageDetails[$pkgKey]['display_validity_date']) . "<br/>";
                   }
                   
                   $prevPackageDetails .= "</td>";
                   
                   // new changes of package for mail
                   $newPackageDetails .= '<td style="font-size:13px; border: 1px solid #999;padding:5px 5px;">';
                   if(array_key_exists('package_name', $changedData)) {
                        $newPackageDetails .= "<strong>New Package Name :</strong> " . $changedData['package_name'] . "<br/>";
                   }

                   if(array_key_exists('price', $changedData)) {
                        $newPackageDetails .= "<strong>New Package Price :</strong> INR&nbsp;" . $changedData['price'] . 
                                (isset($currencyChangesDetails[$pkgKey]['USD']['price']) ? " USD&nbsp;" . $currencyChangesDetails[$pkgKey]['USD']['price'] : "") . 
                                (isset($currencyChangesDetails[$pkgKey]['SGD']['price']) ? " SGD&nbsp;" . $currencyChangesDetails[$pkgKey]['SGD']['price'] : "") . "<br/>";
                        
                        $newPackageDetails .= "<strong>Automatic Convert to :</strong> " . 
                                ((isset($currencyChangesDetails[$pkgKey]['USD']['price'])) ? " USD Manual" : " USD Automatic") . 
                                ((isset($currencyChangesDetails[$pkgKey]['SGD']['price'])) ? " SGD Manual" : " SGD Automatic") .  "<br/>";
                   }

                   if(array_key_exists('tax', $changedData)) {
                        $newPackageDetails .= "<strong>New Tax :</strong> INR&nbsp;" . $changedData['tax'] . 
                                (isset($currencyChangesDetails[$pkgKey]['USD']['tax']) ? " USD&nbsp;" . $currencyChangesDetails[$pkgKey]['USD']['tax'] : "") . 
                                (isset($currencyChangesDetails[$pkgKey]['SGD']['tax']) ? " SGD&nbsp;" . $currencyChangesDetails[$pkgKey]['SGD']['tax'] : "") . "<br/>";
                        
                        $newPackageDetails .= "<strong>New Package Unit Price :</strong> INR&nbsp;" . $changedData['unit_price'] . 
                                (isset($currencyChangesDetails[$pkgKey]['USD']['unit_price']) ? " USD&nbsp;" . $currencyChangesDetails[$pkgKey]['USD']['unit_price'] : "") . 
                                (isset($currencyChangesDetails[$pkgKey]['SGD']['unit_price']) ? " SGD&nbsp;" . $currencyChangesDetails[$pkgKey]['SGD']['unit_price'] : "") . "<br/>";
                   }

                   if(array_key_exists('is_active', $changedData)) {
                        $package_status = "";
                        if($changedData['is_active'] == 1) {
                            $package_status = "Activate";
                        } else {
                            $package_status = "Deactivate";
                        }
                        $newPackageDetails .= "<strong>Change Package Status :</strong> " . $package_status . "<br/>";
                    }

                   if(array_key_exists('offer_quote', $changedData)) {
                        
                        $newPackageDetails .= "<strong>New Original Price :</strong> INR&nbsp;" . $changedData['orginal_price'] . 
                                (isset($currencyChangesDetails[$pkgKey]['USD']['original_price']) ? " USD&nbsp;" . $currencyChangesDetails[$pkgKey]['USD']['original_price'] : "") . 
                                (isset($currencyChangesDetails[$pkgKey]['SGD']['original_price']) ? " SGD&nbsp;" . $currencyChangesDetails[$pkgKey]['SGD']['original_price'] : "") . "<br/>";
                        
                        $newPackageDetails .= "<strong>New Discount :</strong> INR&nbsp;" . ($changedData['orginal_price'] - (isset($changedData['price']) ? $changedData['price'] : $previousPackageDetails[$pkgKey]['price'])) . 
                                ((isset($currencyChangesDetails[$pkgKey]['USD']['original_price']) && isset($currencyChangesDetails[$pkgKey]['USD']['price'])) ? " USD&nbsp;" . ($currencyChangesDetails[$pkgKey]['USD']['original_price'] - $currencyChangesDetails[$pkgKey]['USD']['price']) : "") . 
                                ((isset($currencyChangesDetails[$pkgKey]['SGD']['original_price']) && isset($currencyChangesDetails[$pkgKey]['SGD']['price'])) ? " SGD&nbsp;" . ($currencyChangesDetails[$pkgKey]['SGD']['original_price'] - $currencyChangesDetails[$pkgKey]['SGD']['price']) : "") . "<br/>";
                        
                        $newPackageDetails .= "<strong>Text for offer :</strong> " . $changedData['offer_quote'] . "<br/>";
                   }

                   if(array_key_exists('days', $changedData)) {
                        $newPackageDetails .= "<strong>Package valid for :</strong> Days<br/>";
                        $newPackageDetails .= "<strong>Number of days :</strong> " . $changedData['days'] . "<br/>";
                   }
                   
                   if(array_key_exists('valid_date', $changedData) && $changedData['valid_date'] != '0000-00-00') {
                        $newPackageDetails .= "<strong>Package valid for :</strong> Valid Date<br/>";
                        $newPackageDetails .= "<strong>Valid Date :</strong> " . $changedData['valid_date'] . "<br/>";
                   }
                   $newPackageDetails .= "</td>";
                   
                   $packagesMsgDetails .= $srNo. $prevPackageDetailsOriginal. $prevPackageDetails . $newPackageDetails;
                   
                   $packagesMsgDetails .= "</tr>";
                   
                   $j++;
               }
           
               //$packagesMsgDetails .= "</table>";
               //echo $packagesMsgDetails;

               $filepath= __DIR__ . '../../../../../Admin/view/mailer/';
               $filepath = $filepath.'updatescheduleconfirmmail.html';

               $file_content = file_get_contents($filepath);

               $regMessage = str_replace('{{DYNAMIC_UPDATE_CONTENT}}', $packagesMsgDetails, $file_content);

               $requestedUserData = $this->getEmailFromUserId($data->created_by);

               $message = "Hi,<br><br>  Please find below the Packages Info which are updated on ". date('d-m-Y H:i:s', strtotime($data->schedule_at))." via automated cron job on EmLive.<br/><br/><br/>".$regMessage;
               //echo $message; die;

               // entry in email content table
               $subject = "Update packages via Update Schedule Cron";

               $config = $this->getServiceLocator()->get('config');
               $recipient = $config['daily_report_to'];// get the daily mis report receipient config data
               $recipient = $recipient .",". $requestedUserData['email'];
               //echo $recipient; die;

               $to = $recipient;
               $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent');
               $emailData = array("email_id" => $to, 'subject' => $subject, 'message' => $message, 'mail_type' => 'internal_tracking', 'status' => 1);
               $res = $mailContentTable->addMultiEmailContent($emailData);
           }
           
           $i++;
        }
        die('Update Scheduled has been completed');
        exit;
    }
    
    private function null2unknown($map, $key) {
        if (array_key_exists($key, $map)) {
            if (!is_null($map[$key])) {
                return $map[$key];
            }
        } 
        return "No Value Returned";
    } 
    public function paymentUpdationFunction() {
       $packagetable = $this->getServiceLocator()->get('Package\Model\TuserpackageTable');
       $dataArray=$packagetable->getPackageNotUpdated();

       $config = $this->getServiceLocator()->get('config');
       $migsConfig = $config['payment_config'];
       $i=1;
       $reportingMailTr = '';
       // echo '<pre>';print_r ($migsConfig);echo '</pre>';die('vikash');
       foreach($dataArray as $key=>$value){
           // echo '<pre>';print_r ($value);echo '</pre>';die('vikash');
           if($value->currency_type == 'INR'){
               $AccessCode = $migsConfig['migs_vpc_AccessCode'];
               $Merchant = $migsConfig['migs_vpc_Merchant'];
           }
           else if($value->currency_type == 'SGD'){
               $AccessCode = $migsConfig['migs_vpc_AccessCode_SGD'];
               $Merchant = $migsConfig['migs_vpc_Merchant_SGD'];
           }
           else if($value->currency_type == 'USD'){
               $AccessCode = $migsConfig['migs_vpc_AccessCode_USD'];
               $Merchant = $migsConfig['migs_vpc_Merchant_USD'];
           }
           
           // migs api to know transaction status
           // add the start of the vpcURL querystring parameters
            $vpcURL = "https://migs.mastercard.com.au/vpcdps";

            // create a variable to hold the POST data information and capture it
            $postData = "";

            $ampersand = "";
            // If input is null, returns string "No Value Returned", else returns value corresponding to given key
            
            
            $apiData = array();
            $apiData['vpc_Version'] = 1;
            $apiData['vpc_Command'] = 'queryDR';
            $apiData['vpc_AccessCode'] = $AccessCode;
            $apiData['vpc_Merchant'] = $Merchant;
            $apiData['vpc_MerchTxnRef'] = $value->order_id;
            $apiData['vpc_User'] = 'emapi';
            $apiData['vpc_Password'] = 'Extra@123';
            
            
            //  ----------------------------------------------------------------------------
            foreach($apiData as $keyApi => $valueApi) {
                // create the POST data input leaving out any fields that have no value
                if (strlen($valueApi) > 0) {
                    $postData .= $ampersand . urlencode($keyApi) . '=' . urlencode($valueApi);
                    $ampersand = "&";
                }
            }
            
            ob_start();

            // initialise Client URL object
            $ch = curl_init();

            // set the URL of the VPC
            curl_setopt ($ch, CURLOPT_URL, $vpcURL);
            curl_setopt ($ch, CURLOPT_POST, 1);
            curl_setopt ($ch, CURLOPT_POSTFIELDS, $postData);

            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);


            // connect
            curl_exec ($ch);

            // get response
            $response = ob_get_contents();
            
            // turn output buffering off.
            ob_end_clean();

            // set up message paramter for error outputs
            $message = "";

            // serach if $response contains html error code
            if(strchr($response,"<html>") || strchr($response,"<html>")) {;
                $message = $response;
            } else {
                // check for errors from curl
                if (curl_error($ch))
                      $message = "%s: s". curl_errno($ch) . "<br/>" . curl_error($ch);
            }

            // close client URL
            curl_close ($ch);

            // Extract the available receipt fields from the VPC Response
            // If not present then let the value be equal to 'No Value Returned'
            $map = array();

            // process response if no errors
            if (strlen($message) == 0) {
                $pairArray = split("&", $response);
                foreach ($pairArray as $pair) {
                    $param = split("=", $pair);
                    $map[urldecode($param[0])] = urldecode($param[1]);
                }
                $message         = $this->null2unknown($map, "vpc_Message");
            } 
            
            $txnResponseCode = $this->null2unknown($map, "vpc_TxnResponseCode");
            
            if($txnResponseCode =='0'){
                //activatepackage, txn, invoice generate, mail to user
                
                $transaction_id = $value->transaction_id;
                $txnProductType = $value->transaction_product_type;
                $couponType = $value->coupon_type;
                // 1. user_transaction table
                $tabletranstion = $this->getServiceLocator()->get('Package\Model\TusertransactionTable');
                $tabletranstion->updateresponse($response, $transaction_id);
                // 1. user_package table
                $packagetable->updatePackageToActive($value->user_package_id);
                
                $OrderId = $value->order_id;
                //echo '<pre>';print_r ($OrderId);echo '</pre>';die('vikash');
                $invoiceData = array('order_id' => $OrderId);
                if (!empty($couponType) && ( $couponType == 'demo' || $couponType == 'test')) {
                    $invoiceTestingDemotable = $this->getServiceLocator()->get('Package\Model\InvoicesTestingDemoTable');
                    $invoiceNumber = $invoiceTestingDemotable->getInvoiceNumber($OrderId);
                    if (!$invoiceNumber) {
                        $invoiceData['product_type'] = $txnProductType;
                        $invoiceData['payment_mode_type'] = $couponType;
                        $invoiceTestingDemotable->addInvoice($invoiceData);
                    }
                } else {
                    if ($txnProductType == 'study') {
                        $invoiceStudytable = $this->getServiceLocator()->get('Package\Model\InvoicesstudyTable');
                        $invoiceNumber = $invoiceStudytable->getInvoiceNumber($OrderId);
                        if (!$invoiceNumber)
                            $invoiceStudytable->addInvoice($invoiceData);
                    }
                    if ($txnProductType == 'sdcard') {
                        $invoiceSdcardtable = $this->getServiceLocator()->get('Package\Model\InvoicessdcardTable');
                        $invoiceNumber = $invoiceSdcardtable->getInvoiceNumber($OrderId);
                        if (!$invoiceNumber)
                            $invoiceSdcardtable->addInvoice($invoiceData);
                    }
                    if ($txnProductType == 'tablet') {
                        $invoiceTablettable = $this->getServiceLocator()->get('Package\Model\InvoicestabletTable');
                        $invoiceNumber = $invoiceTablettable->getInvoiceNumber($OrderId);
                        if (!$invoiceNumber)
                            $invoiceTablettable->addInvoice($invoiceData);
                    }
                }
                
                // block to send sms
                $defaultstates = $config['msg_engine'];// get the msg config data
                if($defaultstates['status'] == 'ON'){
                   $msglog  = $this->getServiceLocator()->get('Package\Model\TmsgLogTable');
                   $currency_type = $value->currency_type;
                   $responseAmount = $value->transaction_amount;
                   $pkg_catogry   = ($value->package_category=='3')?'Fast track package':($value->package_category=='1'?'combo package':($value->package_category=='2'?'Annual Package':'Test package'));
                 
                   $msgTxt = "Thank you for subscribing to Extramarks LIVE. We have received a payment of ".$currency_type.' '.$responseAmount." for the purchase of ".$pkg_catogry.". You can now enjoy uninterrupted service."; 
                   $usermobile = $value->mobile;
                   $mobile     = explode("-", $usermobile);
                   $mob_number = str_replace('+','',$mobile[0]).$mobile[1];
                   if($mobile[1]) {
                        $smsArr = array('to_mobile_number'=>$mob_number,
                            'msg_txt' => $msgTxt,
                            'user_id' => $value->user_id,
                            'mobile_number' => $usermobile,
                            'sms_type' => 'subscription'
                        );
                        $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                        $data = $comMapperObj->smssendprocess($smsArr);
                        $result = $msglog->addlog($data);
                   }
                }
                
                $this->generateInvoicePdf($refernce);
                $this->generateemailcontent($transaction_id, $user_id);
                
                
                // Reporting mail content
                $reportingMailTr .='<tr><td>'.$i.'</td><td>'. $value->first_name.'</td><td>'. $value->email.'</td><td>'. $value->order_id.'</td><td>'.$value->package_name.'</td><td>'.$value->currency_type.'</td><td>'.$value->transaction_amount.'</td><td>'.$value->transaction_discount.'</td></tr>';
                $i++;
                
            }
       }
       
       $this->emailReportAutomatePaymentUpdation($reportingMailTr);
       
       echo 'Payment updated';die;
    }
    
    private function emailReportAutomatePaymentUpdation($reportingMailTr){
        // $expiryMailContent = file_get_contents(__DIR__ . '../../../../../Webpackage/view/webpackage/index/expiry-notification.html');//die;
        $filepath= __DIR__ . '../../../../view/mailer/';
        $expiryMailContent = file_get_contents($filepath.'payment_automate_updation.html');
        
        $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent');
        

        $event = $this->getEvent();
        $requestURL = $event->getRequest();
        $router = $event->getRouter();
        $uri = $router->getRequestUri();
        $basepath = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
        
        $msg='<table border="1" cellspacing="0" cellpadding="0" width="100%">';
        
        
        if($reportingMailTr !=''){
            $msg.='<tr><td><b>Sr. No.</b></td><td><b>Name</b></td><td><b>Email</b></td><td><b>Order Id</b></td><td><b>Package Name</b></td><td><b>Currency</b></td><td><b>Transaction Amount</b></td><td><b>Transaction Discount</b></td></tr>';
            $msg .=$reportingMailTr;
        }else{
            $msg .='<tr><td colspan="5">No Transaction Updated</td></tr>';
        }
        $msg .='</table>';

        $expiryMailContent = str_replace('{UserList}', $msg, $expiryMailContent);
        $expiryMailContent = str_replace('{BASE_URL}', $basepath, $expiryMailContent);
        $expiryMailContent; //die;
        $subject = 'MIGS Transactions updated via API';
        $mailType = 'internal_tracking';
        $to = 'adil.saleem@extramarks.com,vikash.v@extramarks.com,neha.dixit@extramarks.com,alok.vishwakarma@extramarks.com';
        $emailData = array("email_id" => $to, 'subject' => $subject, 'message' => $expiryMailContent, 'mail_type' => $mailType, 'status' => 1);
        $mailContentTable->addMultiEmailContent($emailData);
    }
    
    public function generateInvoicePdf($orderId) {
        ///  Generate invoice html on route....
        $htmlpath = $this->url()->fromRoute('generateinvoicehtml');

        $path = 'http://' . $_SERVER['HTTP_HOST'] . $htmlpath;

        //// Generate pdf form html ////         
        $cmd = 'xvfb-run --server-args="-screen 0, 1024x768x24" wkhtmltopdf ' . $path . '/' . $orderId . ' /tmp/' . $orderId . '.pdf';

        $ret = shell_exec($cmd);

        $this->ftpFileUploaded('/tmp/' . $orderId . '.pdf', 'uploads/invoice/' . $orderId . '.pdf');
        //ob_end_clean();
    }

    public function generateemailcontent($transactionId, $userId) {
        $containerservice = $this->getServiceLocator()->get('lms_container_service');
        $userLogObj = $this->getServiceLocator()->get("lms_container_mapper");
        $event = $this->getEvent();
        $requestURL = $event->getRequest();
        $router = $event->getRouter();
        $uri = $router->getRequestUri();
        $basepath = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
        

        $transactiontable = $this->getServiceLocator()->get('Package\Model\TusertransactionTable');
        $transactionDetails = $transactiontable->getTransactionDetails($transactionId);
        $transactionAllDetails = $transactiontable->getTransactionDetailsByOrderId($transactionDetails->order_id);
        $boardName = $transactionAllDetails->board;
        $className = $transactionAllDetails->class;
        $paidAmount = $transactionAllDetails->paid_amount;
        $package_name = $transactionAllDetails->package_name;
        $package_id = $transactionAllDetails->package_id;

        $userId = $transactionDetails->purchaser_id;
        $zfcuserMapperObj = $this->getServiceLocator()->get("zfcuser_user_mapper");
        $customerDetailsObj = $zfcuserMapperObj->findById($userId);

        $refernce = $transactionDetails->order_id;
        $pdf_file_path = "uploads/invoice/";
        $pdf_file_name = $refernce . ".pdf";
        $transactionProductType = $transactionDetails->transaction_product_type;

        $packagetable = $this->getServiceLocator()->get('Package\Model\TuserpackageTable');
        $invoiceData = array('order_id' => $refernce);
        if ($transactionProductType == 'study') {
            $invoiceStudytable = $this->getServiceLocator()->get('Package\Model\InvoicesstudyTable');
            $invoiceNumber = $invoiceStudytable->getInvoiceNumber($refernce);
            if (!$invoiceNumber)
                $invoiceStudytable->addInvoice($invoiceData);
        }
        if ($transactionProductType == 'sdcard') {
            $invoiceSdcardtable = $this->getServiceLocator()->get('Package\Model\InvoicessdcardTable');
            $invoiceNumber = $invoiceSdcardtable->getInvoiceNumber($refernce);
            if (!$invoiceNumber)
                $invoiceSdcardtable->addInvoice($invoiceData);
        }
        if ($transactionProductType == 'tablet') {
            $invoiceTablettable = $this->getServiceLocator()->get('Package\Model\InvoicestabletTable');
            $invoiceNumber = $invoiceTablettable->getInvoiceNumber($refernce);
            if (!$invoiceNumber)
                $invoiceTablettable->addInvoice($invoiceData);
        }
        $address = $customerDetailsObj->getAddress() . ", " . $customerDetailsObj->getOtherCity() . " - " . $customerDetailsObj->getPostalCode();
        $condArray = array('user_id' => $userId, 'transaction_id' => $transactionId);
        $userpkgData = $packagetable->getUserPackage($condArray);

        $host = ($_SERVER['HTTP_HOST'] == "localhost") ? 'emsite/' : '';
        $downloadfilepath = 'http://' . $_SERVER['HTTP_HOST'] . "/" . $host . $pdf_file_path . $pdf_file_name;
        $invoiceFilePath = $pdf_file_path . $pdf_file_name;
        $filepath = __DIR__ . '../../../../../Package/view/mailer/';

        if ($transactionProductType == 'study') {
            $packagemailfilepath = $filepath . 'newpackageordermail.html';
            $packageconfrimation_html = file_get_contents($packagemailfilepath);

            $packageRow = '';
            $i = 1;
            foreach ($userpkgData as $value) {
                $subIdsArray = explode(',', $value->syllabus_id);
                if (count($subIdsArray) == 1) {
                    $subjectDataObj = $userLogObj->getContainer($subIdsArray[0]);
                    if (is_object($subjectDataObj)) {
                        if (is_object($subjectDataObj->getRackName())) {
                            $subject_name = $subjectDataObj->getRackName()->getName();
                        }
                    }
                    $packagename = $value->package_name;
                } else {
                    $packagename = $value->package_name;
                }
                $valid_till = date('d F, Y', strtotime($value->valid_till));
                  
                $subject_name='';
                $syllabusIds = $value->syllabus_id;
                $syllabusIdsArr = explode(',',$syllabusIds);
                $subject_name='';
                if(count($syllabusIdsArr) > 1) {
                    foreach($syllabusIdsArr as $syllabusId) {
                        $subjectparent = $containerservice->getParentList($syllabusId);
                        $subjectName = $subjectparent[2]['rack_name'];
                        $subject_name .= $subjectName.',<br/>';
                    }
                } else {
                  $subjectparent = $containerservice->getParentList($syllabusIds);
                  $subject_name = $subjectparent[2]['rack_name'];
                }
                
        
                $dataArr = array('id' => 1,'board_name' => $value->board,
                'class_name' => $value->class,
                'package_hidden_name' => $packagename,
                'valid_till'=> $valid_till,
                'subject_string' => $subject_name);
                $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                $packageRow .= $comMapperObj->newpackageordermailcontent($dataArr);

                $i++;
            }
            $studyData = array('website_html'=>$packageconfrimation_html,
                    'site_url' => $basepath,
                    'student_name' => $customerDetailsObj->getDisplayName(),
                    'download_pdf' => '<a href="' . $downloadfilepath . '" target="_blank">Click here to download invoice</a>',
                    'package_details' => $packageRow);

            $packageconfrimation_html = $comMapperObj->htmltemplateofwebsite($studyData);
            
            
            $newordermessage = $packageconfrimation_html;
        }
        //echo '<pre>';print_r($newordermessage);echo '</pre>';die('Macro Die');
        if ($transactionProductType == 'sdcard') {
            //global $newsdcardpurchasemail;

            $sdcardmailfilepath = $filepath . 'newsdcardpurchasemail.html';
            $sdcardorder_html = file_get_contents($sdcardmailfilepath);
            
            $sdcardData = array('sdcardhtml' => $sdcardorder_html,
                'site_url' => $basepath,
                'student_name' => $customerDetailsObj->getDisplayName(),
                'class_name' => $className,
                'price' => $paidAmount,
                'address' => $address,
                'download_pdf' => $downloadfilepath
            );
            $sdcardorder_html = $comMapperObj->htmltemplateofsdcard($sdcardData);
            
            
            $newordermessage = $sdcardorder_html;
        }
        if ($transactionProductType == 'tablet') {
            //global $newtabletpurchasemail;

            $tabletmailfilepath = $filepath . 'newtabletpurchasemail.html';
            $tabletorder_html = file_get_contents($tabletmailfilepath);
            $tabletData = array('tablethtml' => $tabletorder_html,
                'site_url' => $basepath,
                'student_name' => $customerDetailsObj->getDisplayName(),
                'class_name' => $className,
                'package_name' => $package_name,
                'price' => $paidAmount,
                'address' => $address,
                'download_pdf' => $downloadfilepath
            );
            $tabletorder_html = $comMapperObj->htmltemplateoftablet($tabletData);
            
            $newordermessage = $tabletorder_html;
        }

        if ($customerDetailsObj->getUserTypeId() == 1 && filter_var($customerDetailsObj->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $email = $customerDetailsObj->getEmail();
        } else if ($customerDetailsObj->getUserTypeId() == 1 && $customerDetailsObj->getParentId() != '') {
            $parent_id = $customerDetailsObj->getParentId();
            $usertable = $this->getServiceLocator()->get('Assessment\Model\userTable');
            $parentData = $usertable->getuserdetailsById($parent_id);
            $parentDataArray = $parentData->current(); //print_r($parentDataArray);
            $email = $parentDataArray->emailId;
        } else {
            $email = $customerDetailsObj->getEmail();
        }

        /////////////// End //////////////////

        $to = $email;

        $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent');

        $emailData = array("email_id" => $to, 'subject' => "Extramarks package order details", 'message' => $newordermessage, 'mail_type' => 'subscription', 'status' => 1, 'mail_attachment' => $invoiceFilePath);
        $mailContentTable->addEmailContent($emailData);

        // Notify Block

        $notifydata = array(
            'notification_text' => 'You have subscribed to a  <b>' . $userpkg->package_name . '</b>&nbsp;package of&nbsp;  ' . $boardName . '-' . $className . '&nbsp;',
            'userid' => $userId,
            'type_id' => '2', // package
            'relation_id' => '',
            'notification_url' => 'my-subscriptions',
            'created_by' => $customerDetailsObj->getId(),
            'created_date' => date('Y-m-d H:i:s'),
        );
        $notificationtable = $this->getServiceLocator()->get('Notification\Model\NotificationTable');
        $notificationtable->insertnotification($notifydata);

        // notify ends here
    }
    
    public function sendBirthdayMailandSms(){
        
        $userTable=$this->getServiceLocator()->get('Assessment\Model\UserTable');
        $smsResult= $userTable->getBirthdayRecordsForSMS();
        $mailResult=$userTable->getBirthdayRecordsForMail();
        $mailContentTable = $this->getServiceLocator()->get('Package\Model\TmailContent');
        $filepath= __DIR__ . '../../../../view/mailer/';
        $birthdaymailfilepath = $filepath . 'birthday-mail.html';
        $birthdayMailContent = file_get_contents($birthdaymailfilepath);
        $subject='Wishing you a very Happy Birthday !!!';  
        $mailType='birthday_mail';
        $config=$this->getServiceLocator()->get('config');
        $msglog  = $this->getServiceLocator()->get('Package\Model\TmsgLogTable');
        $defaultstates = $config['msg_engine'];
        
        $event = $this->getEvent();
        $requestURL = $event->getRequest();
        $router = $event->getRouter();
        $uri = $router->getRequestUri();
        $baseUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $requestURL->getBaseUrl());
        
        foreach($smsResult as $key=>$value){
          
            if($defaultstates['status'] == 'ON' && $value['mobile']!=''){
                $mobile     = explode("-", $value['mobile']);
                if(!isset($mobile[1])){
                    $mobile[1]='';
                }
                $mob_number = str_replace('+','',$mobile[0]).$mobile[1];
                $smsArr = array('to_mobile_number'=>$mob_number,
                            'msg_txt' => '"Happy birthday! We hope that this new year of your life will be a launch pad for a very bright future. – Extramarks"',
                            'user_id' => $value['user_id'],
                            'mobile_number' => $value['mobile'],
                            'sms_type' => 'birthday_sms'
                );
                $comMapperObj = $this->getServiceLocator()->get("com_mapper");
                $data = $comMapperObj->smssendprocess($smsArr);
                $result = $msglog->addlog($data);
            }
        }
        
        foreach($mailResult as $key=>$value){
            
            $birthdayMailContent = str_replace("{STUDENT}", $value['display_name'], $birthdayMailContent);
            $birthdayMailContent = str_replace("{SITE_URL}", $baseUrl, $birthdayMailContent);
            $emailData = array("email_id" => $value['emailId'], 'subject' => $subject, 'message' => $birthdayMailContent, 'mail_type' => $mailType, 'status' => 1);
            $emailData['created_time']=date('Y-m-d H:i:s');
            $mailContentTable->addEmailContent($emailData);
            
        }
        die('Birthday SMS sent successfully');
        exit;
    }
    
    public function userReportsFunction(){
        
        //ini_set('display_errors', E_ALL);
        $currentDate = date('Y-m-d');
        $createdDate = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
        
        $createdDate = date('Y-m-d 00:00:00', strtotime($createdDate));
        $userReportObj = $this->getServiceLocator()->get('Admin\Model\UserReportsTable');
        
        $params['fromDate'] = $createdDate;
        $params['toDate'] = date('Y-m-d 23:59:59');
        
        $userTableObj = $this->getServiceLocator()->get('Assessment\Model\UserTable');
        
        $userData = $userTableObj->getUserReportsData($params);
        
        if(count($userData)) {
            
            foreach($userData as $uData) {
                $data = array();
                //echo "<pre>"; print_r($uData);
                $user_type = "";
                if($uData->user_type_id == 1) {
                    $user_type = 'Student';
                } else if($uData->user_type_id == 2) {
                    $user_type = 'Parent';
                } else {
                    $user_type = 'Mentor';
                }
                
                $data['session_date'] = date('Y-m-d', strtotime($uData->create_time));
                $data['name'] = $uData->display_name;                
                $data['email'] = $uData->emailId;
                $data['phon_no'] = $uData->mobile;
                $data['stakeholder'] = $user_type;
                $data['source'] = $uData->provider;
                $data['country'] = $uData->country;
                $data['state'] = $uData->state;
                $data['city'] = $uData->city;
                $data['school'] = $uData->school_name;
                $data['school_code'] = $uData->school_code;
                $data['board'] = $uData->board_name;
                $data['class'] = $uData->class_name;
                $data['subject'] = $uData->subject_name;
                $data['chapter'] = $uData->chapter_name;
                $data['service_type'] = $uData->service_type;
                $data['service'] = $uData->service;
                $data['start_time'] = $uData->start_time;
                $data['end_time'] = $uData->end_time;
                $data['duration'] = $uData->total_time;
                $data['registration_date'] = date('Y-m-d H:i:s', strtotime($uData->create_time));
                $data['subscribed'] = $uData->subscribed_user_type;
                $data['subscription_date'] = (!empty($uData->valid_from) ? date('Y-m-d H:i:s', strtotime($uData->valid_from)) : null);
                $data['package_type'] = $uData->package_type;
                $data['package_name'] = $uData->package_name;
                $data['delivery_platform'] = $uData->transaction_product_type;
                $data['purchase_date'] = (!empty($uData->purchase_date) ? date('Y-m-d H:i:s', strtotime($uData->purchase_date)) : null);         
                $data['expiry_date'] = (!empty($uData->valid_till) ? date('Y-m-d H:i:s', strtotime($uData->valid_till)) : null);  
                $data['validity_days'] = $uData->validity_days;  
                $data['currency_type'] = $uData->currency_type;  
                $data['amount_paid'] = $uData->currency_type ." ". $uData->paid_price;  
                $data['discount_applied'] = $uData->discount_amount;  
                $data['payment_method'] = $uData->payment_mode;  
                $data['payment_type'] = $uData->pkg_payment_type;  
                $data['created_date'] = date('Y-m-d H:i:s');
                $data['status'] = 1;
                //inser the data into user_reports table.
                $userReportObj->insertUserReports($data);
            }            
        }
        
        die('User reports has been inserted sucessfully');
        exit;
    }

}

