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
class TusertypeTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	
	//public function addUsertype-This function adding member ,
	public function addUsertype($user)
    {
		$password=md5($token);
		$data = array(
			'name'				    => 	null,  		
			'added_by'  			=> 	null,
			'updated_by'  			=> 	null,
		);		
		$this->tableGateway->insert($data);		
    }
	
	//public function getuserTypes-This function get the user details
	public function getuserTypes()
    {	
		$select = $this->tableGateway->getSql()->select();
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;		
	}
	
	//public function getAll-This function get the user details based on user_type_id
	public function getAll()
    {	
		$select = $this->tableGateway->getSql()->select();
		//->where('user_type_id!="1"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;		
	}
	
	//public function getusertypename -This function get the username based on user_type_id
	public function getusertypename($usertypeid)
    {	
		$select = $this->tableGateway->getSql()->select()
		->where("user_type_id='".$usertypeid."'");
		$resultSet = $this->tableGateway->selectWith($select);
		$row = $resultSet->current();		
        return $row;		
	}
}