<?php
namespace Notification\V1\Rest\Notifications;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

class NotificationsResource extends AbstractResourceListener
{
    protected $service, $_notificationService, $_apiService;
    
    public function __construct($service) {
        $this->service = $service;
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
    
    public function getApiService() {
        if (!$this->_apiService) {
            $this->_apiService = $this->service->get('api_service');
        }
        return $this->_apiService;
    }
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $data = (array) $data;
        $res = array('error'=>true,'detail'=>'','error-code'=>604);
        $res = $this->getApiService()->validateFields('notification-add', $data);
        
        if($res['error']){
            return new ApiProblem($res['error-code'], $res['detail']);
        }
        
        //validate if request is proper
        $res = $this->getApiService()->saveApiData($data);
        
        if(!$res)
            return new ApiProblem(607, "Checksum error. Please try again!");
        else{
            $retArr = array();
            $retArr['output'] = 'true';
            $retArr['message'] = 'Data inserted successfully';
            return $retArr;
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
        $res = array('error'=>true,'detail'=>'record not found','error-code'=>604);
        $paramsArr = array();
        $notificationId = $paramsArr['notificationId'] = $params->get('notificationId');
        $createdDate = $datefrom = $paramsArr['datefrom'] = $params->get('datefrom');
        $timefrom = $paramsArr['time'] = $params->get('time');
        $userId = $paramsArr['userId'] = $params->get('userId');
        $paramsArr['apiKey'] = $params->get('apiKey');
//        $dateto = $params['dateto'] = $request->getQuery('dateto', false);
        $res = $this->getApiService()->validateFields('notification-get', $paramsArr);
        
        if($res['error']){
            return new ApiProblem($res['error-code'], $res['detail']);
        }
        
        if($datefrom){
           $datefrom = str_replace('/','-',$datefrom);
           $datefrom = explode('-',$datefrom);
           $datefrom = date('Y-m-d',strtotime($datefrom[1]."-".$datefrom[0]."-".$datefrom[2]));
           
           if($timefrom){
               $createdDate = "$datefrom $timefrom";
           }
        }
        
        $result = $this->getNotificationService()->getnotification($userId, '', $notificationId, $createdDate);
        
        if(count($result) == 0){
            return new ApiProblem(610, 'Notification record is empty');
        }
        return $result;
        
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
        $data = (array) $data;
        $res = array('error'=>true,'detail'=>'','error-code'=>604);
        $param = array();
        $param['apiKey'] = $data['apiKey'];
        $param['apiType'] = $data['apiType'];
        $param['dataValue'] = $data['dataValue'];
        $param['createdBy'] = $data['createdBy'];
        
        //validate if request is proper
        $res = $this->getApiService()->validateFields('notification-update', $param);
        
        if($res['error']){
            return new ApiProblem($res['error-code'], $res['detail']);
        }
        
        $res = $this->getApiService()->saveApiData($param);
        
        if(!$res)
            return new ApiProblem(607, "Checksum error. Please try again!");
        else{
            $retArr = array();
            $retArr['output'] = 'true';
            $retArr['message'] = 'Data updated successfully';
            return $retArr;
        }
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
