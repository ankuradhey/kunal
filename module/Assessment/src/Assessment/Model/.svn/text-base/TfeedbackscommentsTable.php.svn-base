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
class TfeedbackscommentsTable
{
    protected $tableGateway;
    protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	
	//public function addComment-This function  add comment whom send feedback his given to them stored in Db
	public function addComment($feedback_id,$comment)
    {
            //$this->tableGateway->delete(array('(feedback_id IN ('.$feedback_id.'))'));
		$data = array(
			'feedback_id'  		=> 	$feedback_id,
			'student_id'  		=> 	$_SESSION['user']['userId'],
			'comment_text'  	=> 	$comment,
		      'posted_date'  		=> 	date('Y-m-d H:i:s'),
			'status'  			=> 	0,						
		);	
		$result=$this->tableGateway->insert($data);
		return $this->tableGateway->lastInsertValue;			        
    }
	
	//public function getComment-This function get comment based on feedbackId
	public function getComment($feedback_id)
	{
		$select = $this->tableGateway->getSql()->select();
		$select->where(array('feedback_id'=>$feedback_id));
                //$select->where(array('status'=>'0'));
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	//public function changeStatus-This function changes status on comment based on feedbackId
	public function changeStatus($feed_comment_id)
    {	
		$data = array(
			'status'  					=> 	1,				
		);
		$result=$this->tableGateway->update($data, array('feed_comment_id' => $feed_comment_id));
		return $result;			        
    }
    
     public function update($feedbackID,$status)
    {	
		$data = array(
			'status'  					=> 	$status,				
		);
		$result=$this->tableGateway->update($data, array('feedback_id' => $feedbackID));
		return $result;			        
    }
}
    
    
         
