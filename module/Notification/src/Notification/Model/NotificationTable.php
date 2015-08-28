<?php
namespace Notification\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
class NotificationTable
{
    protected $tableGateway;
    protected $select;
    protected $adapter;
    protected $serviceManager;
    
    public function __construct(TableGateway $tableGateway,$serviceManager)
    {
        $this->tableGateway = $tableGateway;
        $this->serviceManager=$serviceManager;
        $this->select = new Select();
    }	
	
	
    public function insertallnotification($dataSet,$notifyType) {
        //$query = "INSERT INTO notification (notification_text,notification_url,userid,type_id,seen,notification_status,created_by,created_date,notification_uuid) VALUES";
        if($notifyType=='recur') {
            $query = "INSERT INTO notification (notification_text,notification_url,userid,type_id,seen,notification_status,created_by,created_date,notification_uuid,notification_name,notification_start_date,notification_end_date,notification_activity_type,notify_appear_type,notification_occurrence_type,notification_recurrence_on,notification_occurrence_no,notification_mail,notification_based_on) VALUES";
            foreach($dataSet as $values) {
                if(!isset($values['notification_end_date'])){
                    $values['notification_end_date']='0000-00-00';
                }
                if(!isset($values['notification_occurrence_no'])) {
                    $values['notification_occurrence_no']='0';
                }
                $query .="('".$values['notification_text']."','".$values['notification_url']."','".$values['userid']."','".$values['type_id']."','".$values['seen']."','".$values['notification_status']."','".$values['created_by']."','".$values['created_date']."','".$values['notification_uuid']."','".$values['notification_name']."','".$values['notification_start_date']."','".$values['notification_end_date']."','".$values['notification_activity_type']."','".$values['notify_appear_type']."','".$values['notification_occurrence_type']."','".$values['notification_recurrence_on']."','".$values['notification_occurrence_no']."','".$values['notification_mail']."','".$values['notification_based_on']."'),";
                //$query .="('".$values['notification_text']."','".$values['notification_url']."','".$values['userid']."','".$values['type_id']."','".$values['seen']."','".$values['notification_status']."','".$values['created_by']."','".$values['created_date']."','".$values['notification_uuid']."'),";
            }
        } else {
            $query = "INSERT INTO notification (notification_text,notification_url,userid,type_id,seen,notification_status,created_by,created_date,notification_uuid,notification_name,notification_start_date,notification_end_date,notification_activity_type,notify_appear_type,notification_mail,notification_based_on) VALUES";
            foreach($dataSet as $values) {
                $query .="('".$values['notification_text']."','".$values['notification_url']."','".$values['userid']."','".$values['type_id']."','".$values['seen']."','".$values['notification_status']."','".$values['created_by']."','".$values['created_date']."','".$values['notification_uuid']."','".$values['notification_name']."','".$values['notification_start_date']."','".$values['notification_end_date']."','".$values['notification_activity_type']."','".$values['notify_appear_type']."','".$values['notification_mail']."','".$values['notification_based_on']."'),";
                //$query .="('".$values['notification_text']."','".$values['notification_url']."','".$values['userid']."','".$values['type_id']."','".$values['seen']."','".$values['notification_status']."','".$values['created_by']."','".$values['created_date']."','".$values['notification_uuid']."'),";
            }
        }
        
        $query = rtrim($query,",").';';
        //echo $query; exit;
        $data = array();
        $stmt = $this->tableGateway->getAdapter()->createStatement($query);
        $stmt->prepare();      
        return $resultSet = $stmt->execute($data);
        //$statement = $this->adapter->query($query); 
        //var_dump($resultSet); die();
        //$result = $this->tableGateway->insert($data);
        //return $result;	
    }
    
    public function insertnotification($data) {
        
        if(!isset($data['notification_uuid'])){
            $apiservice =  $this->serviceManager->get('api_service');
            $nuuid = $apiservice->generateUuid($type=NULL);
            $data['notification_uuid']=$nuuid;
        } else if(empty($data['notification_uuid'])){
            $apiservice =  $this->serviceManager->get('api_service');
            $nuuid = $apiservice->generateUuid($type=NULL);
            $data['notification_uuid']=$nuuid;
        }
        $result = $this->tableGateway->insert($data);
        return $result;	
    }
	 
        
        public function getnotification($userId='',$relationId='',$notificationId = '',$createdDate= '' ){
            	$select = $this->tableGateway->getSql()->select();
                if($userId != ''){
                    	$select->where("userid='".$userId."'");
                }
                if($relationId != ''){
                    	$select->where("relation_id='".$relationId."'");
                }
                if($notificationId != ''){
                    $select->where("notification_id= '$notificationId'");
                }
                if($createdDate != ''){
                    $select->where(" createdDate >= '$createdDate' ");
                }
                $select->where('notification_status="1"');
                $select->where('seen="0"');
                $select->order("notification_id DESC");
                //$select->order("type_id DESC");
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
        }
        
        public function getnotificationsByCreator($creatorId,$currentPage='',$resultsPerPage='') {
                $sql = $this->tableGateway->getSql();
            	$select = $this->tableGateway->getSql()->select();
                $select->where("created_by='".$creatorId."'");
                $select->where('notification_name!=""');
                $select->where("notification_activity_type='admin'");
                //$select->where('notification_status="1"');
                //$select->where('seen="0"');
                $select->order("notification_id DESC");
                //$select->group('notification_name');
                $select->group('notification_name');
                //$select->order("type_id DESC");
		$select->limit((int) $resultsPerPage)->offset(((int) $currentPage-1)*(int) $resultsPerPage);
                //echo $sql->getSqlstringForSqlObject($select); die ;
                $resultSet = $this->tableGateway->selectWith($select);
                //echo "<pre />"; print_r($resultSet); exit;
                $resultSet->buffer();
		return $resultSet;
        }
        
        public function getnotificationsByCreatorCount($creatorId) {
                $sql = $this->tableGateway->getSql();
            	$select = $this->tableGateway->getSql()->select();
                $select->where("created_by='".$creatorId."'");
                $select->where('notification_name!=""');
                $select->where("notification_activity_type='admin'");
                $select->order("notification_id DESC");
                $select->group('notification_name');
                $resultSet = $this->tableGateway->selectWith($select);
                return $resultSet->count();
        }
        
        public function getnotificationsByNotificationId($notification_id) {
            	$select = $this->tableGateway->getSql()->select();
                $select->where("notification_id='".$notification_id."'");
                $select->where('notification_name!=""');
                $select->where("notification_activity_type='admin'");
                $resultSet = $this->tableGateway->selectWith($select);
                $resultSet->buffer();
		return $resultSet;
        }
        
        public function getNotificationsByNotificationName($notificationName) {
            $select = $this->tableGateway->getSql()->select();
            $select->where("notification_name='".$notificationName."'");
            $select->where("notification_activity_type='admin'");
            $select->order("notification_id DESC");
            $select->group('notification_name');
            $resultSet = $this->tableGateway->selectWith($select);
            return $resultSet->count();
        }
        
        public function getUsersByNotificationName($notificationName,$seenStatus) {
            $sql = $this->tableGateway->getSql();
            $select = $this->tableGateway->getSql()->select();
            $select->join(array("u" => "user"), "u.user_id = userid", array('*'), 'left');
            $select->where("notification_name='".$notificationName."'");
            $select->where("notification_activity_type='admin'");
            if($seenStatus==1) {
                $select->where("seen='1'");
            }
            $select->order("notification_id DESC");
            //echo $sql->getSqlstringForSqlObject($select); die;
            return $resultSet = $this->tableGateway->selectWith($select); 
            //echo '<pre>'; print_r($resultSet); exit;
        }
        
        public function getAllNotificationsByNotificationName($notificationName) {
            $select = $this->tableGateway->getSql()->select();
            $select->where("notification_name='".$notificationName."'");
            $select->where("notification_activity_type='admin'");
            $select->order("notification_id DESC");
            //$select->group('notification_name');
            $resultSet = $this->tableGateway->selectWith($select);
            return $resultSet->count();
        }
        
        public function getAllViewedNotificationsByNotificationName($notificationName) {
            $select = $this->tableGateway->getSql()->select();
            $select->where("notification_name='".$notificationName."'");
            $select->where("seen='1'");
            $select->order("notification_id DESC");
            //$select->group('notification_name');
            $resultSet = $this->tableGateway->selectWith($select);
            return $resultSet->count();
        }
        
        public function getAllNotificationOfAdmin() {
            $select = $this->tableGateway->getSql()->select();
            $select->where("notify_appear_type!=''");
            $select->where("notification_activity_type='admin'");
            return $resultSet = $this->tableGateway->selectWith($select);
        }
        
        public function getAllNotificationOfAdminByNotifyType($notify_type,$offset,$limit) {
            $select = $this->tableGateway->getSql()->select();
            $select->where("notify_appear_type!=''");
            $select->where("seen='1'");
            $select->where("notification_activity_type='admin'");
            $select->where("notify_appear_type='".$notify_type."'");
            $select->limit($limit);  
            $select->offset($offset); 
            return $resultSet = $this->tableGateway->selectWith($select);
        }
        
        public function getAllSeenNotificationOfAdmin($offset,$limit) {
            $select = $this->tableGateway->getSql()->select();
            $select->where("notify_appear_type!=''");
            $select->where("seen='1'");
            $select->where("notification_activity_type='admin'");
            $select->limit($limit);  
            $select->offset($offset); 
            return $resultSet = $this->tableGateway->selectWith($select);
        }
        
        public function getAllNotificationOfAdminByNotifyTypeCount($notify_type) {
            $select = $this->tableGateway->getSql()->select();
            $select->where("notify_appear_type!=''");
            $select->where("seen='1'");
            $select->where("notification_activity_type='admin'");
            $select->where("notify_appear_type='".$notify_type."'");
            $resultSet = $this->tableGateway->selectWith($select);
            return $resultSet->count();
        }
        
        public function getAllSeenNotificationOfAdminCount() {
            $select = $this->tableGateway->getSql()->select();
            $select->where("notify_appear_type!=''");
            $select->where("seen='1'");
            $select->where("notification_activity_type='admin'");
            $resultSet = $this->tableGateway->selectWith($select);
            return $resultSet->count();
        }
        
        public function updateStatus($ids, $data) {
            $row = $this->tableGateway->update($data, array('notification_id' => $ids));
            return $row;
        }
     
    /*
     * Author: ankit
     * Description: update notifications using uuid - `notification_uuid` field
     */
    public function updateNotificationFromUuid($uuid, Array $params){
        return $row = $this->tableGateway->update($params, array('notification_uuid' =>$uuid));
    }
}