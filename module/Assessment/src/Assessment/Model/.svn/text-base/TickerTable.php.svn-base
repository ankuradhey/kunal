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


class TickerTable 
{
   protected $tableGateway;
   protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
    
    public function inserticker($data){
        
        $select = $this->tableGateway->insert($data);
    }
    
    
    /* 
     * Comment:Get All ticker data.
     */
    public function getAllticker(){
        $select = $this->tableGateway->getSql()->select()->where("ticker_type='text'");
        $select->order('ticker_id DESC');
         $resultSet = $this->tableGateway->selectWith($select);
         return $resultSet;
        
    } 
    
    /*Date:18-oct_14
     * Comment: Get all tickers which status is active .
     */
    public function getAllActiveTicker($type){ 
        $select = $this->tableGateway->getSql()->select()->where("status='1'");        
        $select->where("ticker_type='$type'");         
        $resultSet =$this->tableGateway->selectWith($select);
        return $resultSet;       
    }
    
     /*Date:18-oct_14
     * Comment: Get all tickers which status is active .
     */
    public function changetickerstatus($id,$data){  
         $result = $this->tableGateway->update($data, array('ticker_id' => $id));
         return $result;
         
    }
    ///// get active mask listing in admin panel/////
    public function getAllmasks(){
        $select = $this->tableGateway->getSql()->select();
        $select->where("ticker_type!='text'");
        $select->order('ticker_id DESC');
         $resultSet = $this->tableGateway->selectWith($select);
         return $resultSet;
        
    } 
    
    
     public function getAllActivemasks(){
        $select = $this->tableGateway->getSql()->select()->where("status='1'");  
        $select->where("ticker_type!='text'");         
         $resultSet = $this->tableGateway->selectWith($select);
         return $resultSet;
        
    } 
    
    // GET ACTIVE VIDEO MASK FOR CALL IN WIDGET ////
    public function GetVideoMask(){
        $select = $this->tableGateway->getSql()->select()->where("status='1'");  
        $select->where("ticker_type!='text'");
        //$select->limit(1);
        $resultSet = $this->tableGateway->selectWith($select);        
         return $resultSet;
    }
    
    // GET ACTIVE IMAGE MASK FOR CALL IN WIDGET ////
     public function GetImageMask(){
        $select = $this->tableGateway->getSql()->select()->where("status='1'");  
        $select->where("ticker_type='image'");         
        $resultSet = $this->tableGateway->selectWith($select);
         return $resultSet;
    }
    
    public function getmaskbyid($id){
        $select = $this->tableGateway->getSql()->select()->where("status='1'");  
        $select->where("ticker_id='$id'");         
        $resultSet = $this->tableGateway->selectWith($select);
         return $resultSet;
    }
    
    ///// Get Next video for slider ///
    public function getnextdatabyid($id){
        $select = $this->tableGateway->getSql()->select()->where("status='1'");  
        $select->where("ticker_type!='text'");
        $select->where("ticker_id > '$id'");        
        $select->limit(1);
        $resultSet = $this->tableGateway->selectWith($select);         
         return $resultSet;
    }
     ///// Get Prevous video for slider ///
     public function getpredatabyid($id){ 
        $select = $this->tableGateway->getSql()->select()->where("status='1'");
        $select->where("ticker_type!='text'");
        $select->where("ticker_id < '$id'");
        $select->order("ticker_id DESC");
        $select->limit(1);        
        $resultSet = $this->tableGateway->selectWith($select);         
         return $resultSet;
    }
    
     public function previewTicker($type,$ids){ 
        $select = $this->tableGateway->getSql()->select();       
        $select->where("ticker_type='$type'");
        $select->where("ticker_id IN ($ids)");
        $resultSet =$this->tableGateway->selectWith($select);         
        return $resultSet;       
    }
         
    }
?>
