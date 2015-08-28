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
class TfeedbackchaptersTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	
	//public function addComments-This function after compelete the test stored total questions ,correct answers and grade in Db
    public function addComments($data,$hidFeedId,$freedbackId,$type='',$userId,$updatecount)
    {
                $update_date = date('Y-m-d',strtotime($data['selectDate']));
		if(isset($hidFeedId) && $hidFeedId!=0) {
			$feedId = $hidFeedId;
			$subject_id  = $data['feedbackSubject'];
			$chapter_id  = $data['feedbackChapter'];
                        if($updatecount==0 || empty($updatecount)) {
                            $deletedata = array(
				'status_feedback'  => 1,				
                            );
                            
                            $where = array('feedbackc_id' => $hidFeedId,'create_date'=>$update_date);
                            //$this->tableGateway->delete(array('(feedbackc_id IN ('.$hidFeedId.'))'));
                            $newrow=$this->tableGateway->update($deletedata, $where);
                            //echo 'asd fasd updated====='.$hidFeedId; exit;
                        }
                        $updatedata = array(
				'comment_text'  	=> $data['commentText'],
				//'comment_type' 		=> $data['commentType'],
				'total_questions'  	=> $data['totalQuestions'],
				'correct_answers'  	=> $data['rightAnswers'],
				'grade_score'  		=> $data['totalScore'],
				'create_date'  		=> date('Y-m-d',strtotime($data['selectDate'])),
				'status_feedback'  	=> 0,				
			);
                        //,'create_date'=>$update_date
                        $where = array('feedbackc_id' => $hidFeedId,'subject_id' => $subject_id,'chapter_id' => $chapter_id,'comment_type'=>$data['commentType'],'create_date'=>$update_date);
			//$this->tableGateway->delete(array('(feedbackc_id IN ('.$hidFeedId.'))'));
                        //$row = $this->tableGateway->update($updatedata, $where);
                        $row='';
                        if(!$row || empty($row)) {
                                if(empty($freedbackId)) {
                                    $freedbackId = $hidFeedId;
                                }
                                //echo $freedbackId; exit;
                                if($type!='') {
                                        $data = array(
                                                'feedbackc_id'		=> $freedbackId,
                                                'subject_id'  		=> $data,
                                                'create_date'  		=> date('Y-m-d'),
                                                'status_feedback'  	=> 0,				
                                        );
                                }else{
                                        $data = array(
                                                'feedbackc_id'		=> $freedbackId,
                                                'subject_id'  		=> $data['feedbackSubject'],
                                                'chapter_id'  		=> $data['feedbackChapter'],
                                                'comment_text'  	=> $data['commentText'],
                                                'comment_type' 		=> $data['commentType'],
                                                'total_questions'  	=> $data['totalQuestions'],
                                                'correct_answers'  	=> $data['rightAnswers'],
                                                'grade_score'  		=> $data['totalScore'],
                                                'custom_board_rack_id'  => $data['custom_board_rack_id'],
                                                'create_date'  		=> date('Y-m-d',strtotime($data['selectDate'])),
                                                'status_feedback'  	=> 0,				
                                        );
                                }
                                $result=$this->tableGateway->insert($data);
                        }
			return $this->tableGateway->lastInsertValue;
		}else{
			$feedId=$freedbackId;
                        if($type!=''){
			$data = array(
				'feedbackc_id'		=> $feedId,
				'subject_id'  		=> $data,
				'create_date'  		=> date('Y-m-d'),
				'status_feedback'  	=> 0,				
			);
                        }else{
                                $data = array(
                                        'feedbackc_id'		=> $freedbackId,
                                        'subject_id'  		=> $data['feedbackSubject'],
                                        'chapter_id'  		=> $data['feedbackChapter'],
                                        'comment_text'  	=> $data['commentText'],
                                        'comment_type' 		=> $data['commentType'],
                                        'total_questions'  	=> $data['totalQuestions'],
                                        'correct_answers'  	=> $data['rightAnswers'],
                                        'grade_score'  		=> $data['totalScore'],
                                        'custom_board_rack_id'  => $data['custom_board_rack_id'],
                                        'create_date'  		=> date('Y-m-d',strtotime($data['selectDate'])),
                                        'status_feedback'  	=> 0,				
                                );
                        }
                        $result=$this->tableGateway->insert($data);
                        return $this->tableGateway->lastInsertValue;
		}
					        
    }
}