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

class TemsstudentsTable
{
    protected $tableGateway;
    protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	
	
    public function addEmsStudents($data)
    {	
        if($data['source'] == '2'){
            $alreadyExistsCheck = $this->checkAlreadyExist($data['user_id']);
            if($alreadyExistsCheck !== FALSE){
                return 0;
            }
        }
        $this->tableGateway->insert($data);	
        $id = $this->tableGateway->lastInsertValue;
        return $id;
    }

    
    public function totalSchoolInfo($params = array()) {  
        
        $where_query = "user.school_id IS NOT NULL ";
    
        if(!empty($params['school_code'])){
            $where_query .=  " AND ( school.school_code = '" . $params['school_code']. "')"; 
        }
        if(!empty($params['emp_code'])){
            $where_query .=  " AND ( tablet_ems_students.emp_code LIKE '%" . $params['emp_code']. "-%') ";
        }
        if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( tablet_ems_students.registration_date >= '".$params['fromDate']." 00:00:00' AND tablet_ems_students.registration_date <= '".$params['toDate'] . " 23:59:59')";   
        }
        if(!empty($params['source'])){
            $where_query .=  " AND ( source = '" . $params['source']. "')"; 
        }
        
        $select = $this->tableGateway->getSql()->select()->columns(array('id','user_id','registration_date'));
        $select->join(array("user" => 'user'), "tablet_ems_students.user_id = user.user_id", array('student_email'=> 'email', ), 'left')
                ->join(array("school" => 'sales_school_details'), "user.school_id = school.school_id", array('school_name' ,'school_code', 'area_name'), 'left')
                ->where($where_query);
        if(!empty($params['group_by']) == '1')
            $select->group('school.school_code');
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
    }
    
    
    public function totalStudentsInfo($params = array()) {  
        $where_query = "user.school_id IS NOT NULL ";
    
        if(!empty($params['school_code'])){
            $where_query .=  " AND ( school.school_code = '" . $params['school_code']. "')"; 
        }
        
        if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( tablet_ems_students.creation_date >= '".$params['fromDate']." 00:00:00' AND tablet_ems_students.creation_date <= '".$params['toDate'] . " 23:59:59')";   
        }
        if(!empty($params['source'])){
            $where_query .=  " AND ( source = '" . $params['source']. "')"; 
        }
        $select = $this->tableGateway->getSql()->select()->columns(array('id','user_id'));
        $select->join(array("user" => 'user'), "tablet_ems_students.user_id = user.user_id", array('student_email'=> 'email', ), 'left')
                ->join(array("school" => 'sales_school_details'), "user.school_id = school.school_id", array('school_name' , 'area_name'), 'left')
                ->where($where_query);
        //$select->group('school.school_code');
        $resultSet = $this->tableGateway->selectWith($select);
        //echo '<pre>';print_r ($resultSet);echo '</pre>';die('vikash');
        return $resultSet;
    }

    
    public function updateStatus($id,$data)
    {	 $row = $this->tableGateway->update($data, array('user_id' => $id));
        return $row;
    }
    
    public function fetchuserdetail($userId)
    {
       $select =  $this->tableGateway->getSql()->select()->columns(array('id','user_id'));
       $select->where('user_id="'.$userId.'"');
       $select->where('user_package_id IS NULL');
       $resultSet = $this->tableGateway->selectWith($select)->current();
       return $resultSet;
    }      
   
    public function checkAlreadyExist($userId)
    {
       $select =  $this->tableGateway->getSql()->select()->columns(array('id','user_id'));
       $select->where('user_id="'.$userId.'"');
       $resultSet = $this->tableGateway->selectWith($select)->current();
       return $resultSet;
    }      

}
