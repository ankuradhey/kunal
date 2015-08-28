<?php
namespace User\V1\Rest\Loginapi;
use Zend\Http\Request;
use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

class LoginapiResource extends AbstractResourceListener
{
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    protected $service;
    public $productName='login';

    public function __construct($service){
        $this->service = $service;
    }



    public function getApiService() {
        $this->apiService = $this->service->get('api_service');
        return $this->apiService;
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
        if($action == "erp"){
            return $this->erpCaseLoginRegistration($get_value);
        }
        else if($action=='login'){
            return $this->userLoginAPI($get_value);
        }else if($action=='registration'){
            return $this->newUserRegistrationAPI($get_value);
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
	//$web_view=$logindetails->web_view;
	$web_view=1;
        //check email Address is empty or not
        //return $this->validateEmailAddress($email_address);
        //return $this->validatePassword($password);

        if($password!='' && $email_address!=''){
                //check this email address is valid or not
                 $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
                 if (!preg_match($regex, $email_address)) {
                      $result = array(
                        'status' =>0,
                        'message' => "Invalid Email Address",
                       );
                      return $result;
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
                $tableuser = $this->service->get('Assessment\Model\UserTable');
                $userDetails=$tableuser->getprofilebyid($result);
                //print_r($userDetails);
                // Get gloabl constant here from global file
                $configArray=$this->service->get('Config');
                $profileImageSitePath=$configArray['constant']['webhost'].'/uploads/profileimages/';
                foreach($userDetails as $data){
                    //print_r($data); exit;
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
                    $userData['state']=($data->state!='')?$data->state:'-NA-';
                    $userData['city']=($data->city!='')?$data->city:'-NA-';
                    $userData['boardId']=($data->boardId!='')?$data->boardId:'-NA-';
                    $userData['boardName']=($data->boardName!='')?$data->boardName:'-NA-';
                    $userData['classId']=($data->classId!='')?$data->classId:'-NA-';
                    $userData['class_name']=($data->classId!='')?$data->classId:'-NA-';
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


            }else{
                $result = array(
                    'status' => 0,
                    'message' => "Please try after sometime.",
                );
                return $result;
            }

    }

    /**
     * New User Registration API
     * Author:: Pradeep Kumar
     * Created Date:: 03 March 2015
     * Description:: API for New User Registration
     */
    function newUserRegistrationAPI($get_value){
         //echo dirname(__DIR__); exit;
        $logindetails=(object)$get_value->login_details;
        $name=$logindetails->name;
        $email_address=$logindetails->email_address;
	$password=$logindetails->password;
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

        if($password==''){
		$result = array(
                    'status' => 0,
                    'message' => "Password is missing.",
                );
               return $result;

       }
        //return $this->validateEmailAddress($email_address);
        //return $this->validatePassword($password);
        if($password!='' && $email_address!=''){
            //check If this user with same email id is already present into the database
            $username=strtolower($email_address);
            $pass=$password;

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
