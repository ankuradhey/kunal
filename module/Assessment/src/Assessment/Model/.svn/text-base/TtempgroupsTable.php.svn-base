<?php

namespace Assessment\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;
use Zend\Paginator\Paginator;

class TtempgroupsTable {

    protected $tableGateway;
    protected $select;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->select = new Select();
    }

    //public function addfriends-This function added friend details(userId,friendId,date and status)
    public function addtemprequest($emailId,$type,$loggedInUserObj) {
        
        $data = array(
            'email_id' => $emailId,
            'requested_by' => $loggedInUserObj->getId(),
            'created_date' => date('Y-m-d H:i:s'),
            'created_by' => $loggedInUserObj->getId(),
            'status' => 1,
            'request_type' =>$type,
        );
        $result = $this->tableGateway->insert($data);
        return $result;
    }

    
    	public function checktemprequest($emailId,$type='',$userId)
       {	
		
                
		$select = $this->tableGateway->getSql()->select()			
				->where("email_id='".$emailId."'")
				->where("requested_by='".$userId."'")
                 ->where('status="1"');	
               /* if($type!=''){
                    $select->where("request_type='".$type."'");	
                }*/
		$resultSet = $this->tableGateway->selectWith($select);		
		$row = $resultSet->count();			
		return $row;
		
	}
        
        public function pendingrequest($emailId='',$type='',$requested_by=''){
		
		$select = $this->tableGateway->getSql()->select()
                        	->join('user', 'user.user_id=temp_group_request.requested_by',array('first_name'),'left')
                                ->where('temp_group_request.status="1"');
                 if($emailId!=''){
                    $select->where("temp_group_request.email_id='".$emailId."'");
                }
                  if($type!=''){
                    $select->where("temp_group_request.request_type='".$type."'");	
                }
                 if($requested_by!=''){
                    $select->where("temp_group_request.requested_by='".$requested_by."'");	
                }
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
        }
        
    public function changeStatus($ids, $value) {
         
        $data = array(
            'status' => $value,
        );
        $row = $this->tableGateway->update($data, array('id' => $ids));
        return $row;
    }

    public function updateStatus($ids, $data) {

        $row = $this->tableGateway->update($data, array('id' => $ids));
        return $row;
    }

}