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


class UserOtherDetailsTable 
{
   protected $tableGateway;
   protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
    
    public function insertUserOtherDetails($data){
         $sessionRes = $this->getUserOtherDetailsByKey($data['user_id'],$data['key_name']);
         if($sessionRes->count() == 0)
            $select = $this->tableGateway->insert($data);
    }
    
   
    public function getUserOtherDetailsByKey($user_id , $key, $status=Null){
        $select = $this->tableGateway->getSql()->select()
                ->where("user_id = '" .$user_id."' ")
                ->where("key_name = '" .$key."' ");
        if($status !='')
        {
            $select->where("value = '" .$status."' ");
        } 
        $resultSet =$this->tableGateway->selectWith($select);
        return $resultSet;
    }
    
    public function updatetMetaTags($data,$id){
        $result = $this->tableGateway->update($data, array('meta_id' => $id));
         return $result;
    }
    
    public function updatetUserOtherDetail($data,$id){
        $result = $this->tableGateway->update($data, array('id' => $id));
         return $result;
    }
    
    public function addpackageLogs($data){
          $select = $this->tableGateway->insert($data);
    }
    
     public function InsertOtherDetail($data){
          $select = $this->tableGateway->insert($data);
}
    
}
?>
