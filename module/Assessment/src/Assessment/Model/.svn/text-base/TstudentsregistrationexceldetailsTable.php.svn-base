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

class TstudentsregistrationexceldetailsTable
{
    protected $tableGateway;
    protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	
	
    public function addUploadDetails($data)
    {		
        $this->tableGateway->insert($data);	
        $id = $this->tableGateway->lastInsertValue;
        return $id;
    }
    
    
    public function updateUploadDetails($data,$id){
       $row=$this->tableGateway->update($data, array('id' => $id));
       return $row;	
    }
    public function getAllStudentsUploadedData($params)
    {		
        $where_query = "1=1";
        
        if (!empty($params['email_uploader'])) {
            $where_query .= " AND (uploaded_by IN  (" . $params['email_uploader'] . ")  OR uploaded_for IN  (" . $params['email_uploader'] . ") ) ";
        }
        if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( updation_date >= '".$params['fromDate']." 00:00:00' AND updation_date <= '".$params['toDate'] . " 23:59:59')";   
        }
        $select = $this->tableGateway->getSql()->select();
        
        $select->where($where_query);
        $select->order("updation_date DESC"); 
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
    }
    public function totalExcelUploadInfo($params)
    {		
        $where_query = "correct_uploads > '0' ";
        
        if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( updation_date >= '".$params['fromDate']." 00:00:00' AND updation_date <= '".$params['toDate'] . " 23:59:59')";   
        }
        
        $select = $this->tableGateway->getSql()->select();
        //$select->join(array('u'=>'user'), "student_registration_excel_details.uploaded_by = u.user_id", array('user_id'=>'user_id','uploader_email'=>'email'),'left');
        
        $select->where($where_query);
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
    }

}
