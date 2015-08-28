<?php

namespace Assessment\Model;

use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;
use Zend\Paginator\Paginator;

class TreferreduserdetailsTable {

    protected $tableGateway;
    protected $select;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->select = new Select();
    }

    public function insertData($data) {
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }

    public function selectData($cond) {
        $select = $this->tableGateway->getSql()->select();
        $select->where($cond);
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
    public function findByEmail($email_id) {
        $select = $this->tableGateway->getSql()->select();
        $select->where('email="' . trim($email_id) . '"');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        return $resultSet;
    }

    public function updateData($data, $cond) {
        $this->tableGateway->update($data, $cond);
        return $this->tableGateway->lastInsertValue;
    }

}
