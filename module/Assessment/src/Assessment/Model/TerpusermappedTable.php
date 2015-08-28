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

class TerpusermappedTable
{
    protected $tableGateway;
    protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	
	
    public function addErpMapping($data)
    {	
        $this->tableGateway->insert($data);	
        $id = $this->tableGateway->lastInsertValue;
        return $id;
    }
    public function checkErpMapping($user_id,$source)
    {
       $select =  $this->tableGateway->getSql()->select()->columns(array('id','user_id'));
       $select->where('user_id="'.$user_id.'"');
       $select->where('source="'.$source.'"');
       $resultSet = $this->tableGateway->selectWith($select)->current();
       return $resultSet;
    }
          

}
