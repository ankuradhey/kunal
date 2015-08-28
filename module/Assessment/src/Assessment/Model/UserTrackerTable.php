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


class UserTrackerTable 
{
   protected $tableGateway;
   protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
    
    public function insertUserTracker($data){
        // $sessionRes = $this->getUserTrackerBySession($data['session']);
        $pos = strpos($data['page_url'], 'uploads');
        $pos1 = strpos($data['page_url'], 'css');
        $pos2 = strpos($data['page_url'], 'images');
        $pos3 = strpos($data['page_url'], 'loggedinzf1');
        $pos4 = strpos($data['page_url'], 'fonts');
        $pos5 = strpos($data['page_url'], '/js/');
        $pos6 = strpos($data['page_url'], 'favicon.ico');
        if($pos === false && $pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false && $pos5 === false && $pos6 === false)
            $select = $this->tableGateway->insert($data);
    }
    
   
    public function getUserTrackerBySession($session){
        
         $select = $this->tableGateway->getSql()->select()->where("session = '$session' ");
         //$select->where("status='1'");
        $resultSet =$this->tableGateway->selectWith($select);
        return $resultSet;
    }
    
    public function updatetMetaTags($data,$id){
        $result = $this->tableGateway->update($data, array('meta_id' => $id));
         return $result;
    }
    
    /**
     * @Ashutosh(27/3/15 2:43 PM)
     */
    public function getTrackerById($userId){
      $select = $this->tableGateway->getSql()->select()->where("user_id = '$userId' ")->order('id DESC')->limit(1);
      //$select->where("status='1'");
      $resultSet = $this->tableGateway->selectWith($select);
      return $resultSet;
    }
}
?>
 