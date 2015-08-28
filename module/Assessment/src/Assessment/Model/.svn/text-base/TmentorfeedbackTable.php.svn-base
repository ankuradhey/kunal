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
class TmentorfeedbackTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
	$this->select = new Select();
    }
	
	//public function addComments-This function add comment stored in db when adding comment stored details(mentorId,studentId,Comment,commenting date,status) based on 
    public function addComments($data,$hidFeedId,$type='',$userid)
    {
                //$_POST['subjectId'], $_POST['hidFeedCId'], $feedbackId, 'popUp',$userId
                $newDate = date("Y-m-d",strtotime($data['selectDate'])).' '.date('H:i:s');
                $updateDateTime = date("Y-m-d H:i:s",strtotime($newDate));
		if($type=='popUp'){
			$data = array(
				'mentor_id'         => $userid,
				'student_id'        => $data['hidstudent_id'],
				'coverage_comment'  => $data['feedbackCoverage'],	
				'capture_date'      => $updateDateTime,
                                'feedback_type'     => $data['feedback_type'],
				'status'            => 0,				
			);
			if(isset($hidFeedId) && $hidFeedId!=0){
				$row=$this->tableGateway->update($data, array('feedback_id' => $hidFeedId));
				return $this->tableGateway->lastInsertValue;				
			}else{
				$result=$this->tableGateway->insert($data);
				return $this->tableGateway->lastInsertValue;	
			}
		}else{
                        
                        $studentId = $data['hidstudent_id'];
                        $subjectId = $data['subject_id'];
                        $feedback_type = $data['feedback_type'];
                        $captureDate = date("Y-m-d",strtotime($data['selectDate']));
                        $pesonType='mentor';
                        /*if(isset($hidFeedId) && trim($hidFeedId)=="") {
                            $feedbackRowSet = $this->getfeedbackRow($studentId,$captureDate,$feedback_type,$pesonType,$subjectId,$userid);
                            if(count($feedbackRowSet)=='1') {
                                foreach($feedbackRowSet as $feedback) {
                                    $hidFeedId = $feedback->feedback_id;
                                }
                            }
                        }*/
			//echo $hidFeedId.'==='.count($feedbackRowSet); exit;
                        if(isset($hidFeedId) && trim($hidFeedId)!="") {
                            
                                $data = array(
                                    'mentor_id'  				=> 	$userid,
                                    'student_id'  				=> 	$data['hidstudent_id'],
                                    'coverage_comment'  		=>          $data['feedbackCoverage'],	
                                    'schedule_comment'  		=> 	$data['feedbackSchedule'],
                                    'feedback_type'                 =>      $data['feedback_type'],
                                    'feedback'  		=>              $data['feedback'],
                                    'capture_date'  			=> 	$updateDateTime,
                                    'custom_board_rack_id'      =>      $data['custom_board_rack_id'],
                                    'subject_id'            =>              $data['subject_id'],
                                    'status'  					=> 	1,				
                                );
                                //echo $hidFeedId.'update<pre>'; print_r($data); exit;
				//$this->tableGateway->delete(array('(feedback_id IN ('.$hidFeedId.'))'));
                                $row=$this->tableGateway->update($data, array('feedback_id' => $hidFeedId));
                                return $hidFeedId;
				//echo $this->tableGateway->lastInsertValue; exit;
			} else {
                            $data = array(
                                    'mentor_id'  				=> 	$userid,
                                    'student_id'  				=> 	$data['hidstudent_id'],
                                    'coverage_comment'  		=>          $data['feedbackCoverage'],	
                                    'schedule_comment'  		=> 	$data['feedbackSchedule'],
                                    'feedback_type'                 =>      $data['feedback_type'],
                                    'feedback'  		=>              $data['feedback'],
                                    'capture_date'  			=> 	$updateDateTime,
                                    'custom_board_rack_id'      =>      $data['custom_board_rack_id'],
                                    'subject_id'            =>              $data['subject_id'],
                                    'status'  					=> 	1,				
                            );
                            //echo 'add<pre>'; print_r($data); exit;
                            $result=$this->tableGateway->insert($data);
                            return $this->tableGateway->lastInsertValue;
                        }
		}		        
    }
	
	//public function checkFeedback-This function checks feedback list based on studentId,feedbackcreated Date
	public function checkFeedback($data){
		$select = $this->tableGateway->getSql()->select();
		$select->join('t_feedback_chapters', 't_feedback_chapters.feedbackc_id=t_mentor_feedback.feedback_id',array('*'),'left');
		if(isset($data['type']) && $data['type']=='mentor'){
			$select->where(array('student_id'=>$data['hidstudent_id'],'mentor_id'=>$_SESSION['user']['userId']));
		}else{
			$select->where(array('student_id'=>$data['hidstudent_id']));
		}
		$select->where(array('capture_date'=>date('Y-m-d')));
		$select->where(array('(t_feedback_chapters.subject_id IN ('.$data['subjectId'].'))'));
		$select->group('feedback_chapter_id');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	//public function getfeedbackComment-This function get the feedback comment,who will recive the feedback he can reply to them so here two tables joined
	public function getfeedbackComment($id,$valuee,$type,$pesonType,$subjectId,$userId,$feedbackId)
	{
		$value='';
                if($valuee==0){
			$value=date('Y-m-d');
		}else{
			$value=$valuee;
		}
		$select = $this->tableGateway->getSql()->select();
		$select->join('t_feedback_chapters', 't_feedback_chapters.feedbackc_id=t_mentor_feedback.feedback_id',array('*'),'left');
		//$select->join('board_content_view', 'board_content_view.board_class_subject_chapter_id=t_feedback_chapters.chapter_id',array('board_class_parent_subject_id','parent_subject_name','subject_name','chapter_name','board_class_subject_chapter_id','board_class_id'),'left');
		
                 $select->join(array("r1" => 'resource_rack'),'r1.rack_id=t_feedback_chapters.chapter_id',array("board_class_subject_chapter_id"=>'rack_id'), 'left');
                 $select->join(array("r2"=>'resource_rack'), 'r2.rack_id = r1.rack_container_id',array("subject_rack_id"=>'rack_id'), 'left');
                 $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_name_id',  array('chapter_name'=>'name'), 'left');
                 $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_name_id',  array('subject_name'=>'name'), 'left');
                
                if($pesonType=='student'){
			//$select->where(array('student_id'=>$userId,'mentor_id'=>$id));
                        $select->where('student_id='.$userId);
                        $select->where('mentor_id='.$id);
		}else if($pesonType=='studentCoverage'){
			$select->where(array('student_id'=>$userId));
		}else{
			//$select->where(array('student_id'=>$id,'mentor_id'=>$userId));
                        $select->where('student_id='.$id);
                        $select->where('mentor_id='.$userId);
		}
		$select->where('capture_date LIKE '."'".$value."%'");
                
                //if($type=='popUp') {
                    $select->where('t_feedback_chapters.create_date='."'".$value."'");
                    $select->where(array('(t_feedback_chapters.subject_id IN ('.$subjectId.'))'));
                    $select->where('t_feedback_chapters.status_feedback=0');
                    //$select->where('t_feedback_chapters.feedbackc_id=0');
                //}
                    if($feedbackId !=0 && !empty($feedbackId)){
                         $select->where('feedback_id='.$feedbackId);
                    }
		$select->group('feedback_chapter_id');
		$select->order('t_mentor_feedback.capture_date DESC');
		$resultSet = $this->tableGateway->selectWith($select);
                //echo '<pre>';print_r($resultSet);echo '</pre>';die('Macro');
		return $resultSet;
	}
        
        public function getfeedbackRow($id,$valuee,$feedback_type,$pesonType,$subjectId,$userId)
	{
                $sql = $this->tableGateway->getSql();
                //echo $id.' '.$valuee.' '.$feedback_type.' '.$pesonType.' '.$subjectId.' '.$userId; exit;    
		$value='';
                if($valuee==0){
			$value=date('Y-m-d');
		}else{
			$value=$valuee;
		}
		$select = $this->tableGateway->getSql()->select();
		if($pesonType == 'student') {
			$select->where('student_id='.$userId);
                        $select->where('mentor_id='.$id);
		} else if($pesonType=='studentCoverage') {
			$select->where(array('student_id'=>$userId));
		} else {
			//$select->where(array('student_id'=>$id,'mentor_id'=>$userId));
                        $select->where('student_id='.$id);
                        $select->where('mentor_id='.$userId);
		}
                $select->where('feedback_type='."'$feedback_type'");
                $select->where('subject_id='.$subjectId);
		$select->where('capture_date LIKE '."'".$value."%'");
                $select->order('feedback_id DESC');
                $select->limit(1);
                //echo $sql->getSqlstringForSqlObject($select); die ;
		//$select->order('t_mentor_feedback.capture_date DESC');
		$resultSet = $this->tableGateway->selectWith($select);
                //echo '<pre>';print_r($resultSet);echo '</pre>';die('Macro');
		return $resultSet;
	}
	
        public function getfeedbackComments($id,$pesonType,$subjectId,$userId)
	{
		$select = $this->tableGateway->getSql()->select();
		$select->join('t_feedback_chapters', 't_feedback_chapters.feedbackc_id=t_mentor_feedback.feedback_id',array('*'),'left');
		//$select->join('board_content_view', 'board_content_view.board_class_subject_chapter_id=t_feedback_chapters.chapter_id',array('board_class_parent_subject_id','parent_subject_name','subject_name','chapter_name','board_class_subject_chapter_id','board_class_id'),'left');
		
                 $select->join(array("r1" => 'resource_rack'),'r1.rack_id=t_feedback_chapters.chapter_id',array("board_class_subject_chapter_id"=>'rack_id'), 'left');
                 $select->join(array("r2"=>'resource_rack'), 'r2.rack_id = r1.rack_container_id',array("subject_rack_id"=>'rack_id'), 'left');
                 $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_name_id',  array('chapter_name'=>'name'), 'left');
                 $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_name_id',  array('subject_name'=>'name'), 'left');
                
                if($pesonType=='student'){
			$select->where('student_id='.$userId);
                        $select->where('mentor_id='.$id);
		} else if($pesonType=='studentCoverage') {
			$select->where(array('student_id'=>$userId));
		} else if($pesonType=='mentor'){
                        $select->where('student_id='.$id);
                } else {
                        $select->where('student_id='.$id);
                        $select->where('mentor_id='.$userId);
                }
                
                //$select->where(array('(t_feedback_chapters.subject_id IN ('.$subjectId.'))'));
                $select->where('t_mentor_feedback.subject_id='.$subjectId);
                $select->where('t_feedback_chapters.status_feedback=0');
		$select->group('feedback_chapter_id');
		$select->order('t_mentor_feedback.capture_date DESC');
		$resultSet = $this->tableGateway->selectWith($select);
                //echo '<pre>';print_r($resultSet);echo '</pre>';die('Macro');
		return $resultSet;
	}
        
	//public function gettotalComments-This function get all feedback reponse comments  based on studentId
	public function gettotalComments($id,$pesonType)
	{
		$select = $this->tableGateway->getSql()->select();
		if($pesonType=='student'){
			$select->where(array('student_id'=>$_SESSION['user']['userId'],'mentor_id'=>$id));
		}else{
			$select->where(array('student_id'=>$id,'mentor_id'=>$_SESSION['user']['userId']));
		}
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->count();
	}
	
	//public function getfeedbackCommentOne-This function get only one feedback comment(whom recieved feedback ,his given comment)based on feedbackId
	public function getfeedbackCommentOne($feedback_id)
	{
		$select = $this->tableGateway->getSql()->select();
		$select->join('board_class_subject_chapters', 'board_class_subject_chapters.board_class_subject_chapter_id=t_mentor_feedback.chapter_id',array('*'),'left');
		$select->where(array('feedback_id'=>$feedback_id));
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	//public function gettotalCommentsDates-This function get total comments dates(whom recieved feedback ,his given comment that comment data stored in Db) based on studentId
	public function gettotalCommentsDates($id,$pesonType,$subjectId,$userId,$feedback_type)
	{
          
		$select = $this->tableGateway->getSql()->select();
		$select->join('t_feedback_chapters', 't_feedback_chapters.feedbackc_id=t_mentor_feedback.feedback_id',array('*'),'left');
		
                //$select->join('board_content_view', 'board_content_view.board_class_subject_chapter_id=t_feedback_chapters.chapter_id',array('board_class_parent_subject_id','parent_subject_name','subject_name','chapter_name','board_class_subject_chapter_id','board_class_id'),'left');
		$select->join(array("r1" => 'resource_rack'),'r1.rack_id=t_feedback_chapters.chapter_id',array("board_class_subject_chapter_id"=>'rack_id'), 'left');
                $select->join(array("r2"=>'resource_rack'), 'r2.rack_id = r1.rack_container_id',array("subject_id"=>'rack_id'), 'left');
                //$select->join(array("r3"=>'resource_rack'), 'r3.rack_id = r1.rack_container_id',array("subject_id"=>'rack_id'), 'left');
                 $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_name_id',  array('chapter_name'=>'name'), 'left');
                 $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_name_id',  array('subject_name'=>'name'), 'left');
                
                if($pesonType=='student'){
			$select->where('student_id='.$userId);
                        $select->where('mentor_id='.$id);
		}else{
                    $select->where('student_id='.$id);
                    $select->where('mentor_id='.$userId);
			//$select->where(array('student_id'=>$id,'mentor_id'=>$userId));
		}
                //echo $subjectId; exit;
                //if($subjectId ==''){
        		//$select->where('t_feedback_chapters.subject_id IN ('.$subjectId.')');
                //}
                $select->where('t_mentor_feedback.subject_id='.$subjectId);
                $select->where('t_mentor_feedback.feedback_type='."'$feedback_type'");
                $select->where('t_feedback_chapters.status_feedback=0');
		$select->group('t_mentor_feedback.capture_date');
		$select->order('t_mentor_feedback.capture_date DESC');
		$resultSet = $this->tableGateway->selectWith($select);
		//echo '<pre>'; print_r($resultSet); exit;
		return $resultSet;
	}
	
	//public function getfeedbackCommentCount-This function get each feedback comment count studentId and status
	public function getfeedbackCommentCount($id)
	{
		$select = $this->tableGateway->getSql()->select();
		$select	->join('user', 'user.user_id=t_mentor_feedback.mentor_id',array('*'),'left');
		$select->join('t_feedback_chapters', 't_feedback_chapters.feedbackc_id=t_mentor_feedback.feedback_id',array('*'),'left');
		$select->join('board_content_view', 'board_content_view.board_class_subject_chapter_id=t_feedback_chapters.chapter_id',array('board_class_parent_subject_id','parent_subject_name','subject_name','chapter_name','board_class_subject_chapter_id','board_class_id'),'left');
		$select->where(array('t_mentor_feedback.student_id'=>$id,'t_mentor_feedback.status'=>0));
		$select->group('t_feedback_chapters.feedbackc_id');
		$select->order('t_mentor_feedback.capture_date DESC');
		$resultSet = $this->tableGateway->selectWith($select);
		//echo '<pre>'; print_r($resultSet); exit;
		return $resultSet;
	}
	
	//public function countComments-This function count comments based on feedback mentorId and feedback status
	public function countComments($id)
	{
		$select = $this->tableGateway->getSql()->select();
		$select	->join('user', 'user.user_id=t_mentor_feedback.student_id',array('*'),'left');
		$select	->join('t_feedbacks_comments', 't_mentor_feedback.feedback_id=t_feedbacks_comments.feedback_id',array('*'),'left');
		$select->join('t_feedback_chapters', 't_feedback_chapters.feedbackc_id=t_feedbacks_comments.feedback_id',array('*'),'left');
		$select->join('board_content_view', 'board_content_view.board_class_subject_chapter_id=t_feedback_chapters.chapter_id',array('board_class_parent_subject_id','parent_subject_name','subject_name','chapter_name','board_class_subject_chapter_id','board_class_id'),'left');
		$select->where(array('t_mentor_feedback.mentor_id'=>$id,'t_feedbacks_comments.status'=>0));
		$select->group('t_mentor_feedback.capture_date');
		$select->order('t_mentor_feedback.capture_date DESC');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	//public function changeStatus-This function changes the status of feedback based on feedbackId
	public function changeStatus($feedback_id)
    {	
		$data = array(
			'status'  					=> 	1,				
		);
		$result=$this->tableGateway->update($data, array('feedback_id' => $feedback_id));
		return $result;			        
    }
    
    //public function getfeedbackCommentByUserId-This function get all by feedback of the logged in user
    public function getfeedbackCommentByUserId($user_id, $user_type) {
        $select = $this->tableGateway->getSql()->select();        
        if ($user_type == 3 || $user_type == 2) {
            $select->where(array('mentor_id' => $user_id));
        } else {
            $select->where(array('student_id' => $user_id));
        }
        $select->order("feedback_id desc");
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
}