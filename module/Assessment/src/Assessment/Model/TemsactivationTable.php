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

class TemsactivationTable
{
    protected $tableGateway;
    protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	
	
    public function addEmsActivation($data)
    {		
        $empCodeCount = $this->getEmpCodeCount($data['emp_code']);
        $this->tableGateway->insert($data);	
        $id = $this->tableGateway->lastInsertValue;
        $dataNew = array('activation_code' => $data['emp_code']."-".$data['school_code']."-".($empCodeCount+1) );
        $row = $this->tableGateway->update($dataNew, array('id' => $id));
        return $data['emp_code']."-".$data['school_code']."-".($empCodeCount+1) ;
    }

    public function getEmpCodeCount($emp_code){
        $select = $this->tableGateway->getSql()->select();
        $select->where("emp_code = '" .$emp_code."' ");
        $resultSet = $this->tableGateway->selectWith($select);
        return count($resultSet);
    }
    
    public function getEmsByMacAndCode($mac_address,$emp_code,$school_code){
        $select = $this->tableGateway->getSql()->select();
        $select->where('mac_address="'.stripslashes($mac_address).'"')
                ->where("emp_code = '" .$emp_code."' ")
                ->where("school_code = '" .$school_code."' ")
                ->where("status = '1' ");
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        return $row;
    }
    
    public function getEmsByActivationCode($activationCode){
        $select = $this->tableGateway->getSql()->select();
        $select->where('activation_code="'.stripslashes($activationCode).'"');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        return $row;
    }
    
    public function deleteEmsActivation($activationCode){
        $select = $this->tableGateway->getSql()->select();
        $select->where('activation_code="'.stripslashes($activationCode).'"');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        if($row === false)
            return 0;
        $dataNew = array("status" => "0");
        $row = $this->tableGateway->update($dataNew, array('activation_code' => $activationCode));
        return 1;
    }
    

    public function emsapiReportData($params = array()) {  
        $where_query = "1";
    
        if(!empty($params['school_code'])){
            $where_query .=  " AND (tablet_ems_activation.school_code = '" . $params['school_code']. "')"; 
        }
        if(!empty($params['emp_code'])){
            $where_query .=  " AND (tablet_ems_activation.emp_code = '" . $params['emp_code']. "')"; 
        }
        if(!empty($params['fromDate']) && !empty($params['toDate'])){
//        $where_query .=  " AND (DATE_FORMAT(tut.purchase_date,'%Y-%m-%d') >= '".$params['fromDate']."' AND DATE_FORMAT(tut.purchase_date,'%Y-%m-%d') <= '".$params['toDate'] . "')";   
            $where_query .=  " AND ( stu.creation_date >= '".$params['fromDate']." 00:00:00' AND stu.creation_date <= '".$params['toDate'] . " 23:59:59')";   
        }
        
        $select = $this->tableGateway->getSql()->select()->columns(array('id','school_code', 'emp_code', 'activation_code','mac_address','ems_activation_start_date','ems_activation_end_date','days'));
        $select->join(array("reg" => 'tablet_ems_registration'), "tablet_ems_activation.emp_code = reg.emp_code", array('email','name','phone'), 'left')
                ->join(array("stu" => 'tablet_ems_students'), "tablet_ems_activation.activation_code = stu.activation_code", array('user_id' , 'student_registered_date' => 'creation_date'), 'left')
                ->join(array("school" => 'sales_school_details'), "tablet_ems_activation.school_code = school.school_code", array('school_name' , 'area_name'), 'left')
                ->join(array("user" => 'user'), "stu.user_id = user.user_id", array('student_email'=> 'email', ), 'left')
                ->where($where_query);
        $select->order("tablet_ems_activation.id"); 
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
}
