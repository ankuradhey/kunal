<?php
namespace Assessment\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
class TparentandchildTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }	
	
	//public function addRelation-This function user can add mentor
	public function addRelation($parentId,$childId,$requestfor=null){
		
                if($requestfor == 'child'){
                    $UserId = $parentId;
                } if($requestfor == 'parent'){
                    $UserId = $childId;
                }
                 $data=array(
			'parent_id'	=>	$parentId,
			'child_id'	=>    $childId,
			'requested_by'	=>	$UserId,
			'status'	=> 0,
                        'created_date'  =>date('Y-m-d H:i:s'),
		);
                
                
		$select = $this->tableGateway->getSql()->select()
				->where('parent_id="'.$parentId.'"')
				->where('child_id="'.$childId.'"')
                                 ->where('status != 2');				
		$resultSet = $this->tableGateway->selectWith($select);
                $row = $resultSet->current();
               
                if($row == ''){
                      $result=$this->tableGateway->insert($data);
		      return $this->tableGateway->lastInsertValue;
                }else{
                    return '0';
                }
                
    }
    
    
    public function addRelationStudentExcelUpload($parentId,$childId,$requestfor=null){
		
                if($requestfor == 'child'){
                    $UserId = $parentId;
                } if($requestfor == 'parent'){
                    $UserId = $childId;
                }
                 $data=array(
			'parent_id'	=>	$parentId,
			'child_id'	=>    $childId,
			'requested_by'	=>	$UserId,
			'status'	=> 1,
                        'created_date'  =>date('Y-m-d H:i:s'),
		);
                
                
		$select = $this->tableGateway->getSql()->select()
				->where('parent_id="'.$parentId.'"')
				->where('child_id="'.$childId.'"')
                                 ->where('status != 2');				
		$resultSet = $this->tableGateway->selectWith($select);
                $row = $resultSet->current();
               
                if($row == ''){
                      $result=$this->tableGateway->insert($data);
		      return $this->tableGateway->lastInsertValue;
                }else{
                    return '0';
                }
                
    }
    
    // get requests (Parent as well child)
    /* Modify by mohit 29-july-14 
     * Data : Add join board class subject from get board name and class name.
     */
    public function getpendingrequest($userId,$usertype, $requesttype=null, $requeststatus=null){ 
        $UserId=$userId;

        $select = $this->tableGateway->getSql()->select();
                if($usertype=='parent'){
                        $select->where('t_parent_and_child.parent_id="'.$UserId.'"');   
                        $select->join('user', 't_parent_and_child.child_id=user.user_id',array('*'),'left');
                        
                        //$select->join('board_class_subjects', 'board_class_subjects.board_class_id = user.class_id', array('board_name', 'class_name'), 'left');
                       
                        $select->join(array("r1" => 'resource_rack'),'r1.rack_id=user.board_id', array("board_rack_id"=>'rack_id'), 'left');
                        $select->join(array("r2" => 'resource_rack'),'r2.rack_id=user.class_id', array("class_rack_id"=>'rack_id'), 'left');
                        $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_name_id', array('board_name'=>'name'), 'left');
                        $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_name_id', array('class_name'=>'name'), 'left');
                        $select->group('t_parent_and_child.id');
                  }
                if($usertype=='child'){
                   $select->where('t_parent_and_child.child_id="'.$UserId.'"'); 
                   $select->join('user', 't_parent_and_child.parent_id=user.user_id',array('*'),'left');
                   
                   }
                   if($requesttype == 'sent'){
                      $select->where('t_parent_and_child.requested_by = "'.$UserId.'"'); 
                   }
                    if($requesttype == 'receive'){
                      $select->where('t_parent_and_child.requested_by != "'.$UserId.'"'); 
                   }
                     if($requeststatus != ''){
                       $select->where('t_parent_and_child.status = "'.$requeststatus.'"');
                   }else{
                       $select->where('t_parent_and_child.status = "0"');
                   }
                
                $row = $this->tableGateway->selectWith($select);
                return $row;
    }

      public function getrelation($parentId='', $childId='',$status=''){
            $select = $this->tableGateway->getSql()->select();
//            echo '<pre>';print_r($parentId);print_r($childId);print($status);echo '</pre>';die('Macro Die');
            if($parentId != ''){
                       $select->where('t_parent_and_child.parent_id="'.$parentId.'"');   ;
                   }
                   if($childId != ''){
                         $select->where('t_parent_and_child.child_id="'.$childId.'"'); 
                   }
             if($status != ''){
                       $select->where('t_parent_and_child.status = "'.$status.'"');
                   }
                  
                $resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->count();
            
      }
      
      public function getrelationdetail($parentId='', $childId='',$status=''){
            $select = $this->tableGateway->getSql()->select();
//            echo '<pre>';print_r($parentId);print_r($childId);print($status);echo '</pre>';die('Macro Die');
            if($parentId != ''){
                       $select->where('t_parent_and_child.parent_id="'.$parentId.'"');   ;
                   }
                   if($childId != ''){
                         $select->where('t_parent_and_child.child_id="'.$childId.'"'); 
                   }
             if($status != ''){
                 $select->where('t_parent_and_child.status = "'.$status.'"');
             }
             return $resultSet = $this->tableGateway->selectWith($select);     
      }
    
    
    
	//public function changeStatus-This function changes user status
	  public function updateStatus($ids,$data){
              
                if(!isset($data['modified_date']))
                {
                    $data['modified_date'] = new \Zend\Db\Sql\Expression("NOW()");
                }
               $row=$this->tableGateway->update($data, array('id' => $ids));
                return $row;	
	}
	 public function getChildData($userId){
            $returnData=array();
            if($userId>0)
            {
                $select = $this->tableGateway->getSql()->select();
                $select->join(array("user" => 'user'),'user.user_id=t_parent_and_child.child_id',  array('*'), 'left');
                $select->join(array("r1" => 'resource_rack'),'r1.rack_id=user.board_id', array("class_rack_id"=>'rack_id'), 'left');
                $select->join(array("r2" => 'resource_rack'),'r2.rack_id=user.class_id', array("class_rack_id"=>'rack_id'), 'left');
                $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_name_id',  array('board_name'=>'name'), 'left');
                $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_name_id',  array('class_name'=>'name'), 'left');
                $select->where('t_parent_and_child.parent_id="'.$userId.'"');
                $resultSet = $this->tableGateway->selectWith($select);

                $returnData = array();
                foreach($resultSet as $val) {
                    $returnData[] = $val;
                }                        
            }
            return $returnData;
        }
        
        public function countChildparentData($userId) {
            $returnData=array();
            if($userId>0) {
                $sql = $this->tableGateway->getSql();
                $select = $this->tableGateway->getSql()->select();
                $select->columns(array('user_id' => 'child_id'));
                $select->where('t_parent_and_child.parent_id="'.$userId.'"');
                //echo $sql->getSqlstringForSqlObject($select); die ;
                $resultSet  = $this->tableGateway->selectWith($select);
                $returnData = $resultSet->count();                
            }
            return $returnData;
        }
        
        public function getChildUserData($userId){
            $select = $this->tableGateway->getSql()->select();
            $select->join(array("user" => 'user'),'user.user_id=t_parent_and_child.child_id',  array('user_id'), 'left');            
            $select->where('t_parent_and_child.parent_id="'.$userId.'"');
            $select->where('t_parent_and_child.status="1"');
            return $resultSet = $this->tableGateway->selectWith($select);
        }
        
        /*
         * Author: ankit
         * Description: used for report
         */
        public function checkChildData($userId){
            $returnData=array();
            if($userId>0)
            {
                $select = $this->tableGateway->getSql()->select();
                $select->join(array("user" => 'user'),'user.user_id=t_parent_and_child.child_id',  array('*'), 'left');
                $select->where('t_parent_and_child.parent_id="'.$userId.'"');
                $resultSet = $this->tableGateway->selectWith($select);

                $returnData = array();
                foreach($resultSet as $val){
                        $returnData[] = $val;
                 }                        
            }
            return $returnData;
        }
        
        public function getParentUserData($userId){
            $select = $this->tableGateway->getSql()->select();
            $select->join(array("user" => 'user'),'user.user_id=t_parent_and_child.parent_id',  array('user_id'), 'left');            
            $select->where('t_parent_and_child.child_id="'.$userId.'"');
            $select->where('t_parent_and_child.status="1"');
            return $resultSet = $this->tableGateway->selectWith($select);
        }
}