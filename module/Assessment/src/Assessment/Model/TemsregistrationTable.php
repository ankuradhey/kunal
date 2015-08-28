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

class TemsregistrationTable
{
    protected $tableGateway;
    protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	
	
    public function addEms($data)
    {		
        $this->tableGateway->insert($data);	
        $id = $this->tableGateway->lastInsertValue;
        $data = array('emp_code' => "EMS".$id);
        $row = $this->tableGateway->update($data, array('id' => $id));
        return "EMS".$id ;
    }
    public function changeEmpStatus($id,$status)
    {	
        $data = array('status' => $status);
        $row = $this->tableGateway->update($data, array('id' => $id));
        return $row;
    }
    public function addEmsOffline($data)
    {		
        $this->tableGateway->insert($data);	
        $id = $this->tableGateway->lastInsertValue;
        return $id;
    }
    public function checkEmailExists($email)
    {		
        $select = $this->tableGateway->getSql()->select();
        $select->where('email="'.stripslashes($email).'"');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        return $row;
    }
    public function checkCodeExists($code)
    {		
        $select = $this->tableGateway->getSql()->select();
        $select->where('emp_code="'.stripslashes($code).'"');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        return $row;
    }

    public function getAllEmployeeData($params = array()) {
        $where_query = "1 = 1";
        if (!empty($params['school_name'])) {
            $where_query .= " and (name LIKE '%" . $params['school_name'] . "%' ) ";
        }
        if (!empty($params['email'])) {
            $where_query .= " AND (email LIKE '%" . $params['email'] . "%' ) ";
        }
        if (!empty($params['erp_id'])) {
            $where_query .= " AND (emp_code LIKE '%" . $params['erp_id'] . "%' ) ";
        }

        $select = $this->tableGateway->getSql()->select();
        
        $select->where($where_query);
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
        
    }
    
    public function getEmsByEmail($emailId){
        $select = $this->tableGateway->getSql()->select();
        $select->where('email="'.stripslashes($emailId).'"');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        return $row;
    }
    
    public function getEmsByCode($code){
        $select = $this->tableGateway->getSql()->select();
        $select->where('emp_code="'.stripslashes($code).'"')
                ->where('status="1"');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        return $row;
    }
}
