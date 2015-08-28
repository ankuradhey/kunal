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


class MetaTagsTable 
{
   protected $tableGateway;
   protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
    
    public function insertMetaTags($data){
        $select = $this->tableGateway->insert($data);
    }
    
    
    /* 
     * Comment:Get All MetaTags data.
     */
    public function getAllMetaTags($params= array()){
    //echo '<pre>';print_r($params);echo '</pre>';die('Macro Die');
        $where_query = "1";       
    if(!empty($params['page_url'])){        
          $where_query .=  " AND (`page_url` LIKE '%" . str_replace($params['baseURL'], "", trim($params['page_url'])) . "%' ) ";
    }
    if(!empty($params['meta_title'])){       
         $where_query .=  " AND (`meta_tag` LIKE '%" . $params['meta_title']. "%' ) ";
    }if(!empty($params['meta_keyword'])){        
        $where_query .=  " AND (`meta_keyword` LIKE '%" . $params['meta_keyword']. "%' ) ";
    }
    if(!empty($params['metadate'])){
         $date =  date('Y-m-d',strtotime($params['metadate']));
        $where_query .=  " AND (DATE(`created_date`) = '" . $date. "' ) ";
    } 
      if(isset($params['status']) && $params['status']!=''){            
         $where_query .=  " AND (`status` = '" . $params['status']. "' ) "; 
      }      
        $select = $this->tableGateway->getSql()->select();
        $select->where($where_query);
        $select->order('meta_id DESC');
         $resultSet = $this->tableGateway->selectWith($select);        
         return $resultSet;
        
    } 
    
    /*Date:18-oct_14
     * Comment: Get all MetaTags which status is active .
     */
    public function getAllActiveMetaTags(){
        $select = $this->tableGateway->getSql()->select()->where("status='1'");
        $resultSet =$this->tableGateway->selectWith($select);
        return $resultSet;       
    }
    
     /*Date:18-oct_14
     * Comment: Get all MetaTags which status is active .
     */
    public function changeMetaTagstatus($id,$data){
         $result = $this->tableGateway->update($data, array('meta_id' => $id));
         return $result;
         
    }
    
    /*Date : 27-Oct-14
     * Comment : GET MATA DATA BY URL
     * Created By : Mohit verma 
     */
  
    Public function GetMetaTagByUrl($url){     
        $select = $this->tableGateway->getSql()->select();
        $select->where("status='1'");
        $url = trim($url);
        $select->where("page_url = '$url'");       
        $result = $this->tableGateway->selectWith($select);  
        return $result;
    }
    
    /*Date : 5-Nov-14
     * Comment : GET MATA DATA BY Id for edit
     * Created By : Mohit verma 
     */
    public function GetMetaTagById($id){
         $select = $this->tableGateway->getSql()->select()->where("meta_id=$id");
         //$select->where("status='1'");
        $resultSet =$this->tableGateway->selectWith($select);
        return $resultSet;
    }
    
    public function updatetMetaTags($data,$id){
        $result = $this->tableGateway->update($data, array('meta_id' => $id));
         return $result;
    }
}
?>
