<?php
namespace Notification\V1\Rest\Notificationupdate;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

class NotificationupdateResource extends AbstractResourceListener
{
    protected $service, $_notificationService, $_apiService;
    public $productName='notificationupdate';


    public function __construct($service) {
        $this->service = $service;
    }

    public function getApiService() {
        $this->apiService = $this->service->get('api_service');
        return $this->apiService;
    }


     /*
     * Author: ankit
     * Description: model used as service to get access to all add, update model methods
     */
    public function getNotificationService() {
        if (!$this->_notificationService) {
            $this->_notificationService = $this->service->get('notification\Model\NotificationTable');
        }
        return $this->_notificationService;
    }
    


    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $suucess=false;
        

        $get_value=$data;
        $apiKey=$get_value->apiKey;
        $apiSalt=$get_value->salt;

        //Call Service to validate the api key with salt and product
        $result=$this->getApiService()->isValidApiSalt($apiKey,$apiSalt,$this->productName);
        if(!empty($result)){
            return $result;
        }


        if(!empty($data->notification)){
            foreach($data->notification as $notificationData){
                $updateData=array();
                if($notificationData['userid']!='' && $notificationData['notification_uuid']!='' && $notificationData['notification_status']!='' ){
                    $updateData['notification_status']=$notificationData['notification_status'];
                    //$updateData['notification_text']="Fast-Track";
                    $uuid=$notificationData['notification_uuid'];
                    $res=$this->getNotificationService()->updateNotificationFromUuid($uuid,$updateData);
                    if($res){
                        $suucess=true;
                    }
                }
            }

            if($suucess){
                $returnArray['status']=1;
                $returnArray['message']="Notification is updated.";
                return $returnArray;
            }else{
                $returnArray['status']=0;
                $returnArray['message']="Notification is not updated.";
                return $returnArray;
            }

        }else{
            $returnArray['status']=0;
            $returnArray['message']="Json Format Error";
            return $returnArray;

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
