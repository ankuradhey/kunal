<?php
namespace User\V1\Rest\Forgotpassword;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

class ForgotpasswordResource extends AbstractResourceListener
{



    protected $service;
    public $productName='forgotpassword';


    public function __construct($service){
        $this->service = $service;
    }


    public function getApiService() {
        $this->apiService = $this->service->get('api_service');
        return $this->apiService;
    }

    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $get_value=$data;

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


        $logindetails=(object)$get_value->login_details;
        $email_address=$logindetails->email_address;
        global $forgotPasswordSubject;
        global $frogotPasswordMessage;
        $event = $this->getEvent();
        $requestURL = $event->getRequest();
        $baseUrl= $requestURL->getBaseUrl();
          if($email_address==''){
		 $result = array(
                    'status' =>0,
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
            $zfcuserMapperObj = $this->service->get("zfcuser_user_mapper");
            $getUserdetais = $zfcuserMapperObj->findByEmail($email_address);
            if(is_object($getUserdetais)){
            $username = ucfirst($getUserdetais->getDisplayName());
            $userid = $getUserdetais->getId();

            $to = $email_address;
            $token = '';
            $token = $this->getUniqueCode('15');
            $date=date("d/m/Y");
            $time=date("h:i a");
            $tokenTime=date("Y-m-d H:i:s");
            $event = $this->getEvent();
            $requestURL = $event->getRequest();
            $baseUrl= $requestURL->getBaseUrl();

            //Get User Details
            $tableuser = $this->service->get('Assessment\Model\UserTable');
            $userDetails=$tableuser->getUserByEmailAddress($email_address);
            $user_id=$userDetails->user_id;
            $displayName=$userDetails->display_name;
            $email_id=$userDetails->emailId;
            $resetlinkurl = $baseUrl."/user/resetforgetpass?token=".$token;

            $filepath='vendor/zf-commons/zfc-user/view/mailer/';
            $filepath = $filepath.'forgotpasswordmessage.html';
            $frogotPasswordMessage = file_get_contents($filepath);
            $frogotPasswordMessage = str_replace("{FULLNAME}", "$username", $frogotPasswordMessage);
            $frogotPasswordMessage = str_replace("{ACTIVATIONLINK}", "$resetlinkurl", $frogotPasswordMessage);
            $frogotPasswordMessage = str_replace("{DATE}", "$date", $frogotPasswordMessage);
            $frogotPasswordMessage = str_replace("{TIME}", "$time", $frogotPasswordMessage);
            $frogotPasswordMessage = str_replace("{BASE_URL}", "$baseUrl", $frogotPasswordMessage);

            $mailContentTable = $this->service->get('Package\Model\TmailContent');
            $emailData = array("email_id" => $email_address, 'subject' => $forgotPasswordSubject, 'message' => $frogotPasswordMessage, 'mail_type' => 'useractivities', 'status' => 1);
            $mailContentTable->addEmailContent($emailData);
            $result = array(
                        'status' =>1,
                        'message' => "Email Sent to registred Email address",
                       );
             }else{
                $result = array(
                        'status' =>0,
                        'message' => "Invalid email address.",
                       );

            }
            }
            return $result;
            return new ApiProblem(405, 'The POST method has not been defined');
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
