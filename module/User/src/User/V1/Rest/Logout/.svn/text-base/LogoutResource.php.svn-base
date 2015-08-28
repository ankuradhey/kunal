<?php
namespace User\V1\Rest\Logout;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

class LogoutResource extends AbstractResourceListener
{


    protected $service;


    /*
     * Product Name
     * @This is name which is manage by the admin
     */
    public $productName='logout';


    public function __construct($service){
        $this->service = $service;
    }


    /*
     * Author: Pradeep Kumar
     * Description: This is API Service that provide the validtaion of the API
     * with the product name, if the product name is not valid then API will be not work
     * 
     */
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

        
        if($action=='logout'){
           $userId=$get_value->details['userid'];
           $sessionid=$get_value->details['sessionid'];
           //Check If this user is logged with same session id
           $userLogObj = $this->service->get("lms_container_mapper");
           $res=$userLogObj->isLoggedUser($userId,$sessionid);
           if(!empty($res)){
            //print_r($res);
            //Update the table of log User and logout the user form the system
                $data = $userLogObj->updateSession($userId);
                $return['status']=1;
                $return['message']="User Succssfully Logout of from the system";
                return $return;

           }else{
                $return['status']=0;
                $return['message']="Not found such user with this session ID";
                return $return;
           }
           exit;
           

        }else{
            $return['status']=0;
            $return['message']="Invalid parameters.";
            return $return;
        }
        /*stdClass Object
(
    [action] => logout
    [details] => Array
        (
            [userid] => 914976
            [sessionid] => eu7dutnop7i5puogapf13kl1o6
        )

)


         */
        return new ApiProblem(405, 'The POST method has not been defined');
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
