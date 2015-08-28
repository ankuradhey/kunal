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

class NotificationMasterTable
{
    
   protected $tableGateway;
   protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
        $this->select = new Select();
    }
    
    
   public function getAllnotificationmaster(){
        $select = $this->tableGateway->getSql()->select();
        $select->order('notification_type_id DESC');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
        
    } 
    
  public function insernotification($data){
        
        $select = $this->tableGateway->insert($data);
    } 
    
  public function existnotification($notification) {
    $select = $this->tableGateway->getSql()->select()->where("notification_type_name = '$notification'");
    $resultSet = $this->tableGateway->selectWith($select);
    return $resultSet;
  }  
    
  public function changenotificationstatus($id,$data){  
         $result = $this->tableGateway->update($data, array('notification_type_id' => $id));
         return $result;
         
    }  
  
}
?>
