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

class tabletupdatedappdetailsTable
{
    protected $tableGateway;
    protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }

    
    public function checkAppExists($app_name)
    {		
        $select = $this->tableGateway->getSql()->select();
        $select->where('app_name="'.stripslashes($app_name).'"');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        return $row;
    }
    
    
}
