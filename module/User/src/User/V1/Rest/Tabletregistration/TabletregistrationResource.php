<?php namespace User\V1\Rest\Tabletregistration;
use Zend\Http\Request;
use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Zend\Soap\Client;
use Zend\Soap\Server;
use Zend\Soap\AutoDiscover;

use Zend\Session\Container; // We need this when using sessions
use Zend\Cache\StorageFactory;
use Zend\Session\SaveHandler\Cache;
use Zend\Session\SessionManager;
use Common\Mapper\CommonMapper as CommonMapper;
use ZfcUser\Service\User as UserService;
use ScnSocialAuth\Mapper\UserProviderInterface;
use ScnSocialAuth\Options\ModuleOptions;


class TabletregistrationResource extends AbstractResourceListener
{
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */



    protected $service;
    public $productName='tabletregistration';
    protected $userProviderMapper = 'ScnSocialAuth-UserProviderMapper';
    public function __construct($service){
        $this->service = $service;
    }



    public function getApiService() {
        $this->apiService = $this->service->get('api_service');
        return $this->apiService;
    }

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
            $this->setUserProviderMapper($this->service->get('ScnSocialAuth-UserProviderMapper'));
        }

        return $this->userProviderMapper;
    }
    /*
     * Created By: Pradeep Kumar [E3312]
     * Created Date: 10 Feb 2015 using Apigilitys
     * Create Function for making tabletregistration as well as user go line
     */
    
    public function create($data)
    {
       $Apivalidkey = array(
            'AO8U86PS8vcnTVegN4fe16Fk4dHBg2AY' =>'12extramarks!@',
            'DEMO8U86PS8vcnTVegN4fe16Fk4d21DO' =>'12extramarks!@',
            'FuK5S18XRy209k3J8hc57T0ISoGJs0dd' =>'extramark123',
            '5VDK9q94GDLpbMxXA0cY6Sov13Z7ldsr' =>'extra@123',
        );
        $apikeyarr =  array_keys($Apivalidkey);
	$get_value=$data;
	$action=$get_value->action;

        //API Details
        if(array_key_exists('api_details',$get_value)){
            $api_details=$get_value->api_details;
            //Validate Api Key and Salt
            $apiKey=$api_details['apikey'];
            $apiSalt=$api_details['salt'];
        }else{
            $apiKey="";
            $apiSalt="";
        }


        //Call Service to validate the api key with salt and product
        $result=$this->getApiService()->isValidApiSalt($apiKey,$apiSalt,$this->productName);
        if(!empty($result)){
            return $result;
        }

       // print_r($get_value);die;exit;
        if($action=='login'){
            return $this->userLoginAPI($get_value);
        }else if($action=='forgot_password'){
             return $this->forgotPasswordAPI($get_value);
        }else if($action=='registration'){
            return $this->newUserRegistrationAPI($get_value);
        }else if($action=='tablet'){
            return $this->tabletRegistrationAPI($get_value);
        }else{
            $result=array('status'=>"failed","message"=>"Invalid action parameters.");
            return $result;
        }

    }
    
    function erpCaseLoginRegistration($get_value){
        $logindetails=(object)$get_value->login_details;
        $email_address=strtolower($logindetails->email_address);
	$password=$logindetails->password;
	$gender=$logindetails->gender;
	$name=$logindetails->name;
	$source=$logindetails->source;
	$unique_id=$logindetails->unique_id;
        if($email_address==''){
            $result = array(
                'status' =>0,
                'message' => "Email Id is missing.",
            );
            return $result;
        }
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        if (!preg_match($regex, $email_address)) {
                 $result = array(
                   'status' =>0,
                   'message' => "Invalid Email",
               );
            return $result;
        }
        $tableuser = $this->service->get('Assessment\Model\UserTable');
        $userDetails=$tableuser->getUserByEmailAddress($email_address);
        if($userDetails === FALSE){
            $logindetails=$get_value->login_details;
            $get_value->login_details['user_type_id'] = 2;
            $this->newUserRegistrationAPI($get_value);
            $result = $this->userLoginAPI($get_value);
            $user_id = $result['content']['user_id'];
            if($result['status'] == "1"){
                $result['message'] = "Parent Registered and successfully login";
                $erpMapperUser = $this->service->get('Assessment\Model\TerpusermappedTable');
                $data = array('user_id' => $user_id ,'source' =>$source, 'uniquekey' => $unique_id, 'type' => 'new');
                $erpMappingId = $erpMapperUser->addErpMapping($data);
                $result['website_mapping_id'] = $this->getUniqueCode(5)."-".$erpMappingId;
                
                $userLogObj = $this->service->get("lms_container_mapper");
                $userLogResult = $userLogObj->getAllRecordUserLogs($user_id);
                $userLogId = $userLogResult->getLogId();
                
                $event = $this->getEvent();
                $requestURL = $event->getRequest();
                $baseUrl= $requestURL->getBaseUrl();
                $basePath = $_SERVER['HTTP_HOST'].$baseUrl;
                $result['redirect_url'] = $basePath."/user/user-dashboard/".$userLogId."/welcome";
                
            }
            return $result;
        }
        else{
            $user_id = $userDetails->user_id;
            $erpMapperUser = $this->service->get('Assessment\Model\TerpusermappedTable');
            $erpMappingData = $erpMapperUser->checkErpMapping($user_id,$source);
            if($erpMappingData === FALSE){
                
            }else{
                $passwordFromWebsite = $userDetails->password;
                $get_value->login_details['password_from_website'] = $passwordFromWebsite;
            }
            $result = $this->userLoginAPI($get_value);
            
            if($result['status'] == "1"){
                if($erpMappingData === FALSE){
                    $data = array('user_id' => $user_id ,'source' =>$source, 'uniquekey' => $unique_id, 'type' => 'old');
                    $erpMappingId = $erpMapperUser->addErpMapping($data);
                }else{
                    $erpMappingId = $erpMappingData->id;
                }
                $result['website_mapping_id'] = $this->getUniqueCode(5)."-".$erpMappingId;
                
                $userLogObj = $this->service->get("lms_container_mapper");
                $userLogResult = $userLogObj->getAllRecordUserLogs($user_id);
                $userLogId = $userLogResult->getLogId();
                
                $event = $this->getEvent();
                $requestURL = $event->getRequest();
                $baseUrl= $requestURL->getBaseUrl();
                $basePath = $_SERVER['HTTP_HOST'].$baseUrl;
                $result['redirect_url'] = $basePath."/user/user-dashboard/".$userLogId;
            }else{
                $result['message'] = "Please enter correct website Password";
            }
              
            return $result;
        }
    }


    /**
     * Tablet Registration API
     * Author:: Pradeep Kumar
     * Created Date:: 03 March 2015
     * Description:: This API will validated all kinds of tablets
     * @object |
     * "tablet_details":{
     *              "app_version_code":1,
     *              "app_version_name":"1.5.0.0",
     *              "model":"D1001C",
     *              "manufacturer":"iNet",
     *              "operating_system":"Linux"
     *              },
     * "license_details":{
     *              "license_id":"13082277834",
     *              "buffer_days":"0",
     *              "email_id":"test123@extra.com",
     *              "project_name":"140523CbX",
     *              "expiry_date":"26/2/2015",
     *              "tablet_id":"cc:d2:9b:d8:d3:53",
     *              "start_date":"11-09-2014"},
     * "checksum":"4db8f67f3055a814efc05705497e22cd",
     * "api_key":"DO286PH8vcdKVegN4fr18Fk3dDWm2AY"
     * }
     *
     */
    function tabletRegistrationAPI($get_value){
        /*********************************Website Login Ends Here*******************************/
        $tabletdetails = (object)$get_value->tablet_details;
        $licensedetails = (object)$get_value->license_details;
        $license_id = $licensedetails->license_id;
        $tablet_id = $licensedetails->tablet_id;

        //$activation_key = $licensedetails->activation_key;
        //print_r($licensedetails); exit;
        $email_id = strtolower($licensedetails->email_id);
        $start_date = $licensedetails->start_date;
        $expiry_date = $licensedetails->expiry_date;
        $buffer_days = $licensedetails->buffer_days;

        $manufacturer = $tabletdetails->manufacturer;
        $model = $tabletdetails->model;
        $application_ver = $tabletdetails->app_version_name;
        $app_version_code = $tabletdetails->app_version_code;
        $operating_system = $tabletdetails->operating_system;

         $api_details=$get_value->api_details;
         $apiKey=$api_details['apikey'];
         $apiSalt=$api_details['salt'];
           
         $api_key = $apiKey;
         $checksum = $get_value->checksum;


        if(isset($licensedetails->password) ){
            $password = $licensedetails->password;
        }else{
            $password='';
        }

        //check whether liecnse_id is present or not
        // return 0, to provide license key
        // If license_id is present then put into array for save into the database
        if ($license_id == '') {
            $result = array(
                'status' => 0,
                'message' => "Please provide license id.",
            );
            return $result;
        } else {
            $insert_data['license_id'] = $licensedetails->license_id;
        }


        //check whether tablet_id is present or not
        // return 0, to provide tablet id
        // If tablet_id is present then put into array for save into the database

        if ($tablet_id == '') {
            $result = array(
                'status' => 0,
                'message' => "Please provide  tablet id.",
            );
            return $result;
        } else {
            $insert_data['tablet_id'] = $licensedetails->tablet_id;
        }


        //check whether manufacturer is present or not
        // return 0, to provide tablet manufacturer
        // If manufacturer is present then put into array for save into the database
        if ($manufacturer == '') {
            $result = array(
                'status' => 0,
                'message' => "Please provide tablet manufacturer.",
            );
            return $result;
        } else {
            $insert_data['manufacturer'] = $tabletdetails->manufacturer;
        }



        //check whether application version is present or not
        // return 0, to provide application version
        // If application version is present then put into array for save into the database
        if ($application_ver == '') {
            $result = array(
                'status' => 0,
                'message' => "Please provide application version.",
            );
            return $result;
        } else {
            $insert_data['app_version_name'] = $tabletdetails->app_version_name;
        }
        if ($checksum == '') {
            $result = array(
                'status' => 0,
                'message' => "Please provide checksum value",
            );
            return $result;
        }


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
        //Custom Salt Key
        //$salt = $Apivalidkey[$api_key];
        /*
        if (!in_array($api_key, $apikeyarr)) {
                 $result = array(
                    'status' => 0,
                   'message'=> "Invalid api key.",
                );
                return $result;
            }
            */
        //$salt = $Apivalidkey[$api_key];
        $salt=$apiSalt;

        // Create checksum  data for check valid data sting /////////
        $newchecksum = md5($salt . $manufacturer . $model . $license_id . $tablet_id . $start_date . $expiry_date . $app_version_code);


         //// CHECK API IS VALID OR NOT FOR THIS  WEBSERVICE
         /*if($api_key!=''){
                $webservices_URL = 'http://tablic.extramarks.com/wsTrinfinWeb/service.asmx?wsdl';
                //$webservices_URL = 'http://115.112.128.13/wsTrinfinWeb/service.asmx?wsdl';
                $client = new Client($webservices_URL);
                $params = array(
                    'LicenseId' => $license_id,
                    'TabletId' => $tablet_id
                );
                $results = $client->GetLicenseDetails($params);

                if ($results->GetLicenseDetailsResult == 'INVALID_LICENSEID') {
                    $result = array(
                                'status' => 0,
                                'message'=> "Your license id is invalid",
                              );
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
                    //print_r($results);exit;

                 }
        }else{
                    $project_name='';
                    $startdate = $start_date;
                    $exprirydate = $expiry_date;
                    $activation_type = 'Online';
                    $buffer_days = $buffer_days;
        }*/
            $project_name='';
                    $startdate = $start_date;
                    $exprirydate = $expiry_date;
                    $activation_type = 'Online';
                    $buffer_days = $buffer_days;
        //Checked license_id is present or not into the database with this user_id
        $license = $this->service->get('Assessment\Model\TuserlicensedetailTable');
        $license_detail = $license->GetuserBylicenceID($license_id);
        //print_r($license_detail); exit;
        $baseUrl='';

        $redirecturl = $baseUrl."/user/go-online/";


        ///////  When user insert password for confirm account /////////
         if(isset($password) || $password==''){
                if(!empty($license_detail)){
                    $license->updatemappedstatus($license_detail->id,'0');
               }
          }
        if(isset($password) && $password!=''){
         if($email_id!=''){
                    $user['userEmail'] = $email_id;
                }else {
                    $user['userEmail'] = $license_id;
                }
                $user['userPassword']= $password;
                $usertable = $this->service->get('Assessment\Model\UserTable');
                $user_details = $usertable->checkLogin($user);
            $password = md5($password);
            if (empty($user_details)) {
                $license->updatemappedstatus($license_detail->id, '0');
                $result = array(
                    'status' => 0,
                    'message' => "Your password did not match ,Please insert valid password",
                );
                return $result;
            }else {
                $license->updatemappedstatus($license_detail->id, '1');
                 $result = array(
                    'status' => 1,
                    'message' => "Your Account has been merged on this account",
                    'email_id' =>$email_id,
                    'username' =>$license_id,
                    //'userid' => $userid
                     

                );
                return $result;
            }
        }
        /////////////////// END /////////////////////////////////////

       if($email_id!=''){
       //            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
       //                if (!preg_match($regex, $email_id)) {
       //                   $result = array(
       //                      'status' => 0,
       //                     'message' => "Invalid  Email Address",
       //                    );
       //                   return  $result;
       //                }
       //
       //// User already registered. Activation again after deactivation tablet.////
       if(!empty($license_detail)){
                            $userid = $license_detail->user_id;
                            $usertable = $this->service->get('Assessment\Model\TuserTable');
                            $user_details = $usertable->getuserdetailsById($userid);
                            //echo '<pre>';print_r($user_details->current());echo '</pre>';die('Macro Die');
                             $email_id =$user_details->current()->emailId;
                             $username =$user_details->current()->username;
                             if(!empty($user_details)){
                                $result = array(
                                'status'=>1,
                                'message'=>'Activation successfully with same license id',
                                'redirecturl'=>$redirecturl,
                                'email_id' =>$email_id,
                                'username' =>$username,
                                'userid' =>$userid,
                                'ask_for_password' =>1,
                            );
                                return $result;
                          }
                   }
                /////////////// END Already registered ///////////////


                $usertable = $this->service->get('Assessment\Model\TuserTable');
                $user_details = $usertable->getuserid($email_id);
                        //////////// if user enter email id , This user is register on website.///////////
                         if(!empty($user_details)){

                            if(empty($license_detail)){
                                 /////// Add license details ////////////
                                $data = array('user_id' => $user_details->user_id, 'license_id' => $license_id, 'tablet_id' => $tablet_id, 'manufacturer' => $manufacturer, 'application_ver' => $application_ver, 'project_name' => $project_name, 'start_date' => $startdate, 'expiration_date' => $exprirydate, 'buffer_days' => $buffer_days, 'activation_type' => $activation_type);
                                        $user_license_id = $license->addUserlicence($data);
                            }
                            $result = array(
                             'status'=> 1,
                             'message' => 'Your email Id is exist. Please insert password ',
                             'ask_for_password' =>1,
                            );
                            return $result;
                         }else {

                              ////// if user registered one tablet and  active with another tablet /////////////
                                 if(!empty($license_detail)){

                                    $userid = $license_detail->user_id;
                                    $usertable = $this->service()->get('Assessment\Model\TuserTable');
                                    $user_details = $usertable->getuserdetailsById($userid);

                                       $email_id =$user_details->current()->emailId;
                                       $username =$user_details->current()->username;
                             if($license_detail->license_id==$license_id && $license_detail->tablet_id==$tablet_id){
                                        $result = array(
                                        'status'=>1,
                                        'message'=>'Activation successfully with same license id on same tablet',
                                        'redirecturl'=>$redirecturl,
                                        'email_id' =>$email_id,
                                        'userid' => $userid
                                    );
                                        return $result;
                                    }else{
                                        $data = array('user_id' => $userid, 'license_id' => $license_id, 'tablet_id' => $tablet_id, 'manufacturer' => $manufacturer, 'application_ver' => $application_ver, 'project_name' => $project_name, 'start_date' => $startdate, 'expiration_date' => $exprirydate, 'buffer_days' => $buffer_days, 'activation_type' => $activation_type,'operating_system'=>$operating_system,'model_number'=>$model_number,'tablet_brand'=>$tablet_brand,'tablet_config'=>$tablet_config,'tablet_mapped'=>1);
                                $user_license_id = $license->addUserlicence($data);
                                        $result = new JsonModel(array(
                                               'status'=>1,
                                                'message'=>'New tablet activation successfully with same license id',
                                                'redirecturl'=>$redirecturl,
                                                'email_id' =>$email_id,
                                                'userid' => $userid
                                            ));
                                                return $result;
                                    }



                                 }else{
                                       $userEmail = explode("@", $email_id);
                                       $table = $this->service->get('Assessment\Model\TuserTable');
                                       // Add user details to user table.........
                                       $password = md5($userEmail[0]);
                                       $userdata = array('email'=>$email_id,'password'=>$password,'gender'=>'Male','user_type_id'=>1,'username'=>Null);
                                       $user = $table->addUser($userdata);
                                       $last_id = $user;

                                if(empty($license_detail)){
                                //// Some field is not in database.....
                                $data = array('user_id' => $last_id, 'license_id' => $license_id, 'tablet_id' => $tablet_id, 'manufacturer' => $manufacturer, 'application_ver' => $application_ver, 'project_name' => $project_name, 'start_date' => $startdate, 'expiration_date' => $exprirydate, 'buffer_days' => $buffer_days, 'activation_type' => $activation_type,'operating_system'=>$operating_system,'model_number'=>$model_number,'tablet_brand'=>$tablet_brand,'tablet_config'=>$tablet_config,'tablet_mapped'=>1);

                                $user_license_id = $license->addUserlicence($data);
                                         }
                                         $result = array(
                                             'status' => 1,
                                             'message' => "Your account  has been created successfully.you can login with email id",
                                             'ask_for_password' => 0,
                                             'redirecturl'=>$redirecturl,
                                             'emailid' =>$email_id,
                                              'userid' => $last_id,
                                           );
                                 return $result;
                               }


                            }
        }else{  /// WHEN USER NOT ENTER EMAIL ID FOR ACTIVATION TABLET....
                ////// when user already register in one tablet, it use another tablet.
                      if(!empty($license_detail)){
                          $userid = $license_detail->user_id;
                          $usertable = $this->service->get('Assessment\Model\TuserTable');
                          $user_details = $usertable->getuserdetailsById($userid);
                          //echo '<pre>';print_r($user_details->current());echo '</pre>';die('Macro Die');
                            if($license_detail->license_id==$license_id && $license_detail->tablet_id==$tablet_id){

                                     $result = array(
                                            'status'=>1,
                                            'message'=>'Activation successfully with same license id',
                                            'redirecturl' => $redirecturl,
                                             'username' =>$license_id,
                                             'ask_for_password' =>1,
                                              'userid' => $userid,
                                        );
                                        return $result;

                                     }else{
                                        $data = array('user_id' => $userid, 'license_id' => $license_id, 'tablet_id' => $tablet_id, 'manufacturer' => $manufacturer, 'application_ver' => $application_ver, 'project_name' => $project_name, 'start_date' => $startdate, 'expiration_date' => $exprirydate, 'buffer_days' => $buffer_days, 'activation_type' => $activation_type,'operating_system'=>$operating_system,'model_number'=>$model_number,'tablet_brand'=>$tablet_brand,'tablet_config'=>$tablet_config,'tablet_mapped'=>1);
                                $user_license_id = $license->addUserlicence($data);

                                $result = array(
                                       'status'=>1,
                                        'message'=>'New tablet activation successfully with same license id',
                                        'redirecturl' => $redirecturl,
                                        'username' =>$license_id,
                                        'userid' => $userid,
                                    
                                    );
                                        return $result;
                                    }


                      }else {
                            //////// New user tablet activation //////
                            $userid = $license_id;
                            $password = md5($license_id);
                            $userdata['username'] = $license_id;
                            $userdata['password'] = $password;
                            $userdata['user_type_id'] = '1';
                            //print_r($userdata); exit;
                            //Again verify of the user, if this license id is present ot not
                            $usertable = $this->service->get('Assessment\Model\UserTable');
                            //$table = $this->getServiceLocator()->get('Assessment\Model\TuserTable');
                            $user_details = $usertable->getuserbyusername($userid);
                           if (empty($user_details)) {

                                //////// Add user details in user table.........
                                $userdata = array(
                                    'email'=>Null,
                                    'password'=>$password,
                                    'gender'=>'Male',
                                    'user_type_id'=>1,
                                    'username'=>$license_id,
                                    'user_id' => $userid
                                    );
                                //print_r($userdata); exit;
                                $user = $usertable->addUser($userdata);
                                $lastuser = $usertable->getuserbyusername($userid);
                                $last_id = $lastuser->user_id;
                                ////////// Add license details .............
                                if (empty($license_detail)) {
                                    $data = array('user_id' => $last_id, 'license_id' => $license_id, 'tablet_id' => $tablet_id, 'manufacturer' => $manufacturer, 'application_ver' => $application_ver, 'project_name' => $project_name, 'start_date' => $start_date, 'expiration_date' => $expiry_date, 'buffer_days' => $buffer_days, 'activation_type' => $activation_type, 'operating_system' => $operating_system, 'model_number' => $model_number, 'tablet_brand' => $tablet_brand, 'tablet_config' => $tablet_config,'tablet_mapped'=>1);

                                    $user_license_id = $license->addUserlicence($data);
                                    // Login user go Online with login details
                                     //$session = new Container('tabletuser');
                                     //$session->email=$user_details->emailId;
                                     //$session->user_name=$user_details->username;
                                }
                                $result = array(
                                    'status' => 1,
                                    'message' => "Your account  has been created successfully. You can login with license id.",
                                    'redirecturl' => $redirecturl,
                                    'username' => $license_id,
                                    'userid' => $last_id,
                                );
                                return $result;
                            } else {
                                $lastuser = $usertable->getuserbyusername($userid);
                                $last_id = $lastuser->user_id;
                                if (empty($license_detail)) {
                                    ////////// Add license details .............
                                    $data = array('user_id' => $last_id, 'license_id' => $license_id, 'tablet_id' => $tablet_id, 'manufacturer' => $manufacturer, 'application_ver' => $application_ver, 'project_name' => $project_name, 'start_date' => $startdate, 'expiration_date' => $exprirydate, 'buffer_days' => $buffer_days, 'activation_type' => $activation_type, 'operating_system' => $operating_system, 'model_number' => $model_number, 'tablet_brand' => $tablet_brand, 'tablet_config' => $tablet_config,'tablet_mapped'=>1);

                                    $user_license_id = $license->addUserlicence($data);
                                }
                                $result = array(
                                    'status' => 1,
                                    'message' => "Your account  has been created successfully. You can login with license id.",
                                    'redirecturl' => $redirecturl,
                                    'username' => $license_id,
                                    'userid' => $userid,
                                );
                                return $result;
                            }
                        }
        }
 
    }



    /**
     * Author:: Pradeep Kumar
     * Created Date:: 03 March 2015
     * Description:: API for Login of the User with logs
     * @pass $get_value | Object
     *
     */
   function userLoginAPI($get_value){
        $logindetails=(object)$get_value->login_details;
      	//$name=$logindetails->name;
        $email_address=strtolower($logindetails->email_address);
        if(isset($logindetails->password_from_website)){
            $password = $logindetails->password_from_website;
        }else{
            $password = $logindetails->password;
        }
        $acessType     = $logindetails->access_type;
        $otherdetail   = array('latitude'=>$logindetails->latitude,'longitude'=>$logindetails->longitude);
	//$web_view=$logindetails->web_view;
	$web_view=1;
        //check email Address is empty or not
        //return $this->validateEmailAddress($email_address);
        //return $this->validatePassword($password);

        if($password!='' && $email_address!=''){
                //check this email address is valid or not
                $pos = strpos($email_address,'@');
                if($pos){
                $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
                 if (!preg_match($regex, $email_address)) {
                      $result = array(
                        'status' =>0,
                        'message' => "Invalid Email Address",
                       );
                      return $result;
                  }
                }
                //Login Authentication goes here
                $username=$email_address;
                $pass=$password;
                $request = new Request();
                $request->setMethod(Request::METHOD_POST);
                $request->getPost()->set('identity',$email_address);
                $request->getPost()->set('credential',$password);
                if(isset($logindetails->password_from_website)){
                    $request->getPost()->set('md5_credential',$password);
                }
                $zfcUserService = $this->service->get('ControllerPluginManager')->get('zfcUserAuthentication');
                $adapter = $zfcUserService->getAuthAdapter();
                $adapter->prepareForAuthentication($request);
                $auth = $zfcUserService->getAuthService()->authenticate($adapter);
                
//                $erpUserSessionArr = new Container('erpUserSession');
//                $erpUserSessionArr->getManager()->getStorage()->clear('erpUserSession');
                
                if($auth->isValid()){
                $result=$auth->getIdentity();
                
//                $erpUserSessionArr = new Container('erpUserSession');
//                $erpUserSessionArr->Email = $email_address;
//                if(isset($logindetails->password_from_website)){
//                    $erpUserSessionArr->user_from_zf2_erp_md5_pwd = $password;
//                }else{
//                    $erpUserSessionArr->user_from_zf2_erp_md5_pwd = $password;
//                }
                    
                //$storage = $auth->getStorage()->read();
                
                $userData = $this->getusercollection($result,$otherdetail);
                
               
                //$userLogObj->addNewSessionUserLog($userData['user_id'],$usersession_id);

                $result = array(
                    'status' => 1,
                    'message' => "You have successfully login.",
                    'content' =>$userData,
                );
		//if($web_view==1){
		//	return header('location:www.google.com');
		//return $this->redirect()->toRoute('zfcuser/myprofile');					
		//}
                }else{
                  $result = array(
                    'status' => 0,
                    'message' => "Invalid Username or passowrd.",
                    'content' =>array(),
                 );
                }
                return $result;


            }elseif($acessType != ''){
                $accessId      = $logindetails->accessId;
                $tableuser = $this->service->get('Assessment\Model\UserTable');
                
                $pos = strpos($email_address,'@');
                if($pos){
                $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
                 if (!preg_match($regex, $email_address)) {
                      $result = array(
                        'status' =>0,
                        'message' => "Invalid Email Address",
                       );
                      return $result;
                  }
                }

                $checkproviderdetail  = $this->getUserProviderMapper()->findUserByProviderId($accessId,$acessType);
                
                if(!is_object($checkproviderdetail))
                {
                    $userDetails = $tableuser->getUserByEmailAddress($email_address);
                    $user_id    = $userDetails->user_id;

                    $localUserProvider = clone($this->getUserProviderMapper()->getEntityPrototype());
                    $localUserProvider->setUserId($user_id)
                                      ->setProviderId($accessId)
                                       ->setProvider($acessType);
                   $this->getUserProviderMapper()->insert($localUserProvider);
                  $userId = $user_id;
                }else{
                    $userId = $checkproviderdetail->getUserId();
                }
                $zfcuserMapperObj =$this->service->get("zfcuser_user_mapper");
                $userObj = $zfcuserMapperObj->findById($userId);
                $password = $userObj->getPassword();
                
                $request = new Request();
                $request->setMethod(Request::METHOD_POST);
                $request->getPost()->set('identity',$email_address);
                $request->getPost()->set('credential',$password);
                $request->getPost()->set('md5_credential',$password);
               
                $zfcUserService = $this->service->get('ControllerPluginManager')->get('zfcUserAuthentication');
                $adapter = $zfcUserService->getAuthAdapter();
                $adapter->prepareForAuthentication($request);
                $auth = $zfcUserService->getAuthService()->authenticate($adapter);
                
                 if($auth->isValid()){
                    $result=$auth->getIdentity();
                    $userData = $this->getusercollection($result,$otherdetail);  
                    $result = array(
                        'status' => 1,
                        'message' => "You have successfully login.",
                        'content' =>$userData,
                    );
                }else{
                  $result = array(
                    'status' => 0,
                    'message' => "Invalid Username or passowrd.",
                    'content' =>array(),
                 );
                }
              return $result;
         }else{
                $result = array(
                    'status' => 0,
                    'message' => "Please try after sometime.",
                );
                return $result;
            }

    }
    
  public function getusercollection($result,$otherdetaildata)
  {
    $tableuser = $this->service->get('Assessment\Model\UserTable');
    $userDetails=$tableuser->getprofilebyid($result);
    // Get gloabl constant here from global file
    $configArray=$this->service->get('Config');
    $profileImageSitePath=$configArray['constant']['webhost'].'/uploads/profileimages/';
    foreach($userDetails as $data){
        if($data->user_type_id==1){
            $userType='Student';
        }elseif($data->user_type_id==2){
            $userType='Parent';
        }elseif($data->user_type_id==3){
            $userType='Teacher';
        }else{
            $userType='-NA-';
        }
        $usersession_id=session_id();
        //Get Country Code from Mobile Number
        $mobileNumber = "";
        $countryCode = "";
        //echo "<pre>";
        //print_r($data);
        if($data->mobile){
            $mobileNumber=explode('-',$data->mobile);
            $countryCode=$mobileNumber[0];
            if(isset($mobileNumber[1]))
            $mobileNumber=$mobileNumber[1];
        }
        $userData['user_id']=$data->user_id;
        $userData['firstName']=($data->firstName!='')?$data->firstName:'-NA-';
        $userData['email']=$data->emailId;
        $userData['username']=$data->username;
        $userData['display_name']=($data->display_name!='')?$data->display_name:'-NA-';
        $userData['gender']=($data->gender!='')?$data->gender:'-NA-';
        $userData['country']=($data->country!='')?$data->country:'-NA-';
        $userData['country_id']=($data->country_id!='')?$data->country_id:'-NA-';
        $userData['state_id']=($data->state_id!='')?$data->state_id:'-NA-';
        $userData['state']=($data->state!='')?$data->state:'-NA-';
        $userData['city']=($data->city!='')?$data->city:'-NA-';
        $userData['boardId']=($data->boardId!='')?$data->boardId:'-NA-';
        $userData['boardName']=($data->boardName!='')?$data->boardName:'-NA-';
        $userData['classId']=($data->classId!='')?$data->classId:'-NA-';
        $userData['class_name']=($data->className!='')?$data->className:'-NA-';
        $userData['user_type']=($userType!='')?$userType:'-NA-';
        $userData['postalcode']=($data->postalcode!='')?$data->postalcode:'-NA-';
        $userData['school_name']=($data->school_name!='')?$data->school_name:'-NA-';
        $userData['mobile']=($mobileNumber!='')?$mobileNumber:'-NA-';
        $userData['county_code']=($countryCode!='')?$countryCode:'-NA-';
        $userData['user_photo']=$profileImageSitePath.$data->user_photo;
        $userData['session_id']=$usersession_id;


    }
    //Log Here once user successfully login into the system
    $userLogObj = $this->service->get("lms_container_mapper");
    $userLogObj->addUserLog($userData['user_id']);
    
    $otherdetail = $this->service->get('Assessment\Model\UserOtherDetailsTable');
        foreach($otherdetaildata as $key=>$valuePair){
        $data = array(
            "user_id"=>$userData['user_id'],
            "key_name"=>$key,
            "value"=>$valuePair
        );
       $otherdetailadded = $otherdetail->InsertOtherDetail($data);
     }
    
    return $userData;
}       

    /**
     * New User Registration API
     * Author:: Pradeep Kumar
     * Created Date:: 03 March 2015
     * Description:: API for New User Registration
     */
    function newUserRegistrationAPI($get_value){
        
        $logindetails  = (object)$get_value->login_details;
        
        $name          = $logindetails->name;
        $email_address = $logindetails->email_address;
	$password      = $logindetails->password;
        $acessType     = $logindetails->access_type;
        $accessId      = $logindetails->accessId;
        //$otherdetail   = array('latitude'=>$logindetails->latitude,'longitude'=>$logindetails->longitude);
        
        
        if(isset($logindetails->user_type_id)){
            $user_type_id = $logindetails->user_type_id;
        }else{
            $user_type_id = 1;
        }
        
	 //return $this->validateName($name);
        if($name==''){
		 $result = array(
                    'status' => 0,
                    'message' => "Name is missing.",
                );
            return $result;
        }

        if($email_address==''){
		 $result = array(
                    'status' => 0,
                    'message' => "Email address is missing.",
                );
                return $result;
        }else{
            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
                 if (!preg_match($regex, $email_address)) {
                      $result = array(
                        'status' =>0,
                        'message' => "Invalid Email Address",
                       );
                      return $result;
                  }
        }
       if( $acessType ==''){
            if($password==''){
		$result = array(
                    'status' => 0,
                    'message' => "Password is missing.",
                );
               return $result;

            }
        }
       
       if($acessType != ''){
           $passwordvalidate    = explode("@", $email_address);
           $passwordvalidate = $passwordvalidate[0];
       }else{
           $passwordvalidate=$password;
         }
       
        //return $this->validateEmailAddress($email_address);
        //return $this->validatePassword($password);
        if($passwordvalidate!='' && $email_address!=''){
            //check If this user with same email id is already present into the database
            $username=strtolower($email_address);
            
            $pass=$passwordvalidate;
           

            //check If this Email Address is alredy present into the database
            $tableuser = $this->service->get('Assessment\Model\UserTable');
            $userDetails=$tableuser->getUserByEmailAddress($email_address);
            if(!empty($userDetails)){
                $result = array(
                    'status' => 0,
                    'message' => "This email address already registred with us, please try with different email address.",
                );
               return $result;

            }

            $comMapperObj = $this->service->get("com_mapper");
            $countryData = $comMapperObj->getAllCountries();
            if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
                $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
                $ip_address = $_SERVER['REMOTE_ADDR'];
            }
            //$ip_address='117.55.243.22';
            $tableIpCountry = $this->service->get('Assessment\Model\IpCountryTable');
            $ipResultSet = $tableIpCountry->ipRange($ip_address);
            $ipCaptureDetails = $comMapperObj->useripcapturefunction($ipResultSet,$ip_address);

            $stateName=$ipCaptureDetails['state'];
            //this will return all the states name and ids
            if($ipCaptureDetails['country']){
                $countaryidDetails = $comMapperObj->getCountryIdByCountryName($ipCaptureDetails['country']);
                if(count($countaryidDetails) >0){
                    $countaryidDetailsNew = $countaryidDetails[0];
                    $_SESSION['statelist'] = $comMapperObj->getCountarybystate($countaryidDetailsNew->getCountryId());
                }
            }

            $stateDetails = $comMapperObj->getStateIdByStateName($stateName);


            $saveData['country_id']=$countaryidDetails[0]->getCountryId();
            $saveData['state_id']=$stateDetails[0]->getStateId();
            $saveData['city']=$ipCaptureDetails['city'];
            $saveData['ip_address']=$ip_address;

            $saveData['name']=$name;
            $saveData['email_id']=$username;
            $saveData['password']=$password;


            //set all the entity for the user
            $userMapper = $this->service->get('zfcuser_user_mapper');
            $options = $this->service->get('zfcuser_module_options');
            $class = $options->getUserEntityClass();
            $user = new $class;

            $countryClass = 'Common\Entity\Country';
            $countryObj = new $countryClass;
            $countryObj->setCountryId($saveData['country_id']);
            $user->setCountry($countryObj);

            // state
            $stateClass = 'Common\Entity\State';
            $stateObj = new $stateClass;
            $stateObj->setStateId($saveData['state_id']);
            $user->setState($stateObj);

            $user->setDisplayName($saveData['name']);
            $user->setOtherCity($saveData['city']);
            $user->setEmail($saveData['email_id']);
            $user->setUsername($saveData['email_id']);
            $user->setPassword(md5($saveData['password']));
            $user->setAllowSchedule('0');
            $user->setUserTypeId($user_type_id);
            $user->setIp($ip_address);
            $userRes=$userMapper->insert($user);
            //Check If User has been created here
             
             
            if(!empty($acessType) && !empty($accessId))
            {
                $userDetails = $tableuser->getUserByEmailAddress($userRes->getEmail());
                $user_id    = $userDetails->user_id;
                
                $localUserProvider = clone($this->getUserProviderMapper()->getEntityPrototype());
                $localUserProvider->setUserId($user_id)
                                ->setProviderId($accessId)
                                ->setProvider($acessType);
                $this->getUserProviderMapper()->insert($localUserProvider);
                
                
               
            }
            $userId = $userRes->getEmail();
            
            
            //print_r($userRes);
                if($userId!=''){
                  $result = array(
                        'status' => 1,
                        'message' => "User register successfully with email address: ".$userId,
                   );

                 //Sending Email to User Email Address
                 if($user_type_id == '1'){
                     $filepath='vendor/zf-commons/zfc-user/view/mailer/';
                     $filepath = $filepath.'welcomemailstudent.html';
                     $file_content = file_get_contents($filepath);
                 }
                 else if($user_type_id == '2'){
                     $filepath='vendor/zf-commons/zfc-user/view/mailer/';
                     $filepath = $filepath.'welcomemailparent.html';
                     $file_content = file_get_contents($filepath);
                 }


                $event = $this->getEvent();
                $requestURL = $event->getRequest();
                $baseUrl= $requestURL->getBaseUrl();
                $userDetails=$tableuser->getUserByEmailAddress($userId);
                $user_id=$userDetails->user_id;
                $displayName=$userDetails->display_name;
                $email_id=$userDetails->emailId;
                
                $regMessage = str_replace('{STUDENT_NAME}', $displayName, $file_content);
                $regMessage = str_replace('{STUDENT_USER ID}', $email_id, $regMessage);
                $regMessage = str_replace('{STUDENT_PASSWORD}', $saveData['password'], $regMessage);
                $regMessage = str_replace('{ACTIVATIONLINK}', $baseUrl . "/user/change-password?id=" . $user_id . "&token=" . $password, $regMessage);
                $regMessage = str_replace('{BASE_URL}', $baseUrl , $regMessage);
                $regSubject= "Registration confirmation";

                $mailContentTable = $this->service->get('Package\Model\TmailContent');
                $emailData = array(
                    "email_id" =>$email_id,
                    'subject' => $regSubject,
                    'message' => $regMessage,
                    'mail_type' => 'registration',
                    'status' => 1);
                 $mailContentTable->addEmailContent($emailData);
                 return $result;
                }
            }else{
                  $result = array(
                        'status' => 0,
                        'message' => "Invalid Parameters.",
                   );
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
    
    
    /**
     * Forgot Password API
     * Author:: Vikash Rajput
     * Created Date:: 25 May 2015
     * Description:: API for Forgot Password
     */
    function forgotPasswordAPI($get_value){
         //echo dirname(__DIR__); exit;
        $forgotDetails =(object)$get_value->forgot_details;
        $email_address=$forgotDetails->email_address;
        
        if($email_address==''){
		 $result = array(
                    'status' => 0,
                    'message' => "Email address is missing.",
                );
                return $result;
        }else{
            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
                 if (!preg_match($regex, $email_address)) {
                      $result = array(
                        'status' =>0,
                        'message' => "Invalid Email Address",
                       );
                      return $result;
                  }
        }
        
        if($email_address!=''){
            //check If this user with same email id is already present into the database
            $username=strtolower($email_address);
            
            //check If this Email Address is alredy present into the database
            $tableuser = $this->service->get('Assessment\Model\UserTable');
            $userDetails=$tableuser->getUserByEmailAddress($email_address);
            
            if(empty($userDetails)){
                $result = array(
                    'status' => 0,
                    'message' => "This email address not registred with us, please try with different email address.",
                );
               return $result;

            }

            $filepath='vendor/zf-commons/zfc-user/view/mailer/';
            $filepath = $filepath.'forgotpasswordmessage.html';
            $frogotPasswordMessage = file_get_contents($filepath);
            $forgotPasswordSubject = "Request for new password on Extramarks";


            $event = $this->getEvent();
            $requestURL = $event->getRequest();
            $baseUrl= $requestURL->getBaseUrl();
            $basePath = $_SERVER['HTTP_HOST'].$baseUrl;
            

            $username = ucfirst($userDetails->firstName);
            $userid = $userDetails->user_id;
            $to = $email_address;
            $token = '';
            $token = $this->getUniqueCode('15');
            date_default_timezone_set('Asia/Kolkata');
            $date=date("d/m/Y");
            $time=date("h:i a");
            $tokenTime=date("Y-m-d H:i:s");

            $resetlinkurl = $baseUrl."/user/resetforgetpass?token=".$token; 
            $frogotPasswordMessage = str_replace("{FULLNAME}", "$username", $frogotPasswordMessage);
            $frogotPasswordMessage = str_replace("{ACTIVATIONLINK}", "$resetlinkurl", $frogotPasswordMessage);
            $frogotPasswordMessage = str_replace("{DATE}", "$date", $frogotPasswordMessage);
            $frogotPasswordMessage = str_replace("{TIME}", "$time", $frogotPasswordMessage);
            $frogotPasswordMessage = str_replace("{BASE_URL}", "$baseUrl", $frogotPasswordMessage);
          //$frogotPasswordMessage = str_replace("<EMILADDRESS>", "$to", $frogotPasswordMessage);            
             
            $mailContentTable = $this->service->get('Package\Model\TmailContent');
            $emailData = array("email_id" => $to, 'subject' => $forgotPasswordSubject, 'message' => $frogotPasswordMessage, 'mail_type' => 'useractivities', 'status' => 1);
            $mailContentTable->addEmailContent($emailData);
             // Add unique token for user in forgot password table
            $tokentable = $this->service->get('Psychometric\Model\TforgotpasswordtokensTable');     
            $addToken = $tokentable->addToken($id = '', $userid, $to, $token,$update='',$tokenTime);
              
            if ($addToken > 0) {
                  $result = array(
                        'status' =>1,
                        'message' => "Email sent successfully",
                       );
                  return $result;
            } else {
                  $result = array(
                        'status' =>0,
                        'message' => "Some unknown error occurred",
                       );
                  return $result;
            }
            
            

        }else{
              $result = array(
                    'status' => 0,
                    'message' => "Invalid Parameters.",
              );
              return $result;
        }
    }

    
     


    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        echo "dasd"; die;
        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
        return new ApiProblem(405, 'The GET method has not been defined for collections');
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
