<?php
namespace User\V1\Rest\Profileupdate;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

class ProfileupdateResource extends AbstractResourceListener
{

    protected $service;

    public function __construct($service){
        $this->service = $service;
    }


    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        if(!empty($data)){
            $name=$data->userinfo['name'];
            $user_id=$data->userinfo['user_id'];
            $gender=$data->userinfo['gender'];
            $postalcode=$data->userinfo['postalcode'];
            $mobile            = (isset($data->userinfo['phonecode']))?$data->userinfo['phonecode'].'-'.$data->userinfo['phonenumber']:$data->userinfo['phonenumber'];
            $city             = $data->userinfo['city'];
            $board_id         = $data->userinfo['board_id'];
            $class_id         = $data->userinfo['class_id'];
            $school_name      = strip_tags($data->userinfo['school_name']);
            $state_id         = $data->userinfo['state_id'];
            $country_id         = $data->userinfo['country_id'];
            
            

            
            $table = $this->service->get('Assessment\Model\UserTable');
            $user_details  = $table->getuserdetails($user_id);
            $user_info     = $user_details->current();
            
            
            //Check If borad Id is find into the database
            if($board_id>0){
               $serviceBoard = $this->service->get('lms_container_service');
               //$res=$serviceBoard->isOprhan($board_id);
               //print_r(var_dump($res)); exit;
               $returnArray['status']=0;
               $returnArray['message']="Board Id is not found, please try with different board id";
               //return $returnArray;
            }

            /*
            $data['display_name']     = strip_tags($user['username']);
            $data['mobile']            = (isset($user['phonecode']))?$user['phonecode'].'-'.$user['phonenumber']:$user['phonenumber'];
            $data['user_type_id']     = $user['usertype'];
            $data['country_id']       = $user['ucountries'];
            $data['state_id']         = $user['states'];
            $data['city']             = $user['other_city'];
            $data['gender']           = $user['sex'];
            $data['board_id']         = $user['board'];
            $data['class_id']         = $user['classnames'];
            $data['school_name']      = strip_tags($user['userschool']);
            $data['user_photo']       = $imageName;
            $data['dob']              = '';
            $data['postalcode']       = strip_tags($user['postalcode']);
            $data['allowschedule']    = $user['allowschedule'];
            */
            
            $userId=$user_id;
            $updateData['display_name']=$name;
            $updateData['gender']          = $gender;
            $updateData['postalcode']          = $postalcode;
            $updateData['mobile']          = $mobile;
            $updateData['city']             = $city;

            if($board_id!=""){
                $updateData['board_id']         = $board_id;
            }
            
            if($class_id!=""){
                $updateData['class_id']         = $class_id;
            }
            $updateData['school_name']      = strip_tags($school_name);
            
            if($state_id!=""){
                $updateData['state_id']         = $state_id;
            }
            
            if($country_id!=""){
                $updateData['country_id']         = $country_id;
            }
            
            
            $res=$table->updateUserAddress($updateData,$userId);
            if($res){
                $returnArray['status']=1;
                $returnArray['message']="success";
                return $returnArray;
            }else{
                $returnArray['status']=0;
                $returnArray['message']="Profile is not updated, please try after some time";
                return $returnArray;
            }

        }else{
            $returnArray['status']=0;
            $returnArray['message']="Falied";
            return $returnArray;

            
        }

        
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
        echo "Test"; die;
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
