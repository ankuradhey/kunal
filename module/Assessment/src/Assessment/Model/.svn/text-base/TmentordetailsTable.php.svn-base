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
class TmentordetailsTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	
	//public function addMentorDetails-This function add mentor details(mentorId,boardId,classId,subjectId,qualification,experience and addeDate) stored in Db
	public function addMentorDetails($mentor_id,$board_id,$class_id,$subjectid,$place)
    {		
		if($place=='from_student'){
			$data = array(
				'mentor_id'  		    => 	$mentor_id,
				'board_id'  			=> 	$board_id,
				'class_id'  			=> 	$class_id,
				'subject_id'  			=> 	$subjectid,
				'qualification'  		=> 	null,
				'experience'  			=> 	null,
				'added_date'  			=> 	date('Y-m-d H:i:s'),			
							
			);	
		}		
		$this->tableGateway->insert($data);
		return $this->tableGateway->lastInsertValue;			        
    }
	
	//public function getMentorSubjects-This function gets mentor subject based on mentorId ,here two tables joined beacuse board_content table t_mentor_details table
	public function getMentorSubjects($mentorId,$subjectId='')
	{
		 $select = $this->tableGateway->getSql()->select();
		 $select->join('board_content_view', 'board_content_view.board_class_subject_id=t_mentor_details.subject_id',array('*'),'left');
		 $select->where(array('mentor_id'=>$mentorId));
		 if($subjectId!=''){
		 $select->where(array('subject_id'=>$subjectId));
		 }
		 $select->where(array('t_mentor_details.board_id'=>$_SESSION['user']['boardId']));
		 $select->where(array('t_mentor_details.class_id'=>$_SESSION['user']['classId']));
		 $select->group('board_content_view.subject_name');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	//public function becomeMentor-This function already users become a mentor based mentorDetails  
	public function becomeMentor($mentordetais)
    {
		$data = array(
			'mentor_id'  		    => 	$mentordetais['hiduserid'],
			'board_id'  			=> 	$mentordetais['boardmentor'],
			'class_id'  			=> 	$mentordetais['classnamesmentor'],
			'subject_id'  			=> 	$mentordetais['subjectsbyclass'],
			'qualification'  		=> 	$mentordetais['qualificationmentor'],
			'experience'  			=> 	$mentordetais['experience'],
			'added_date'  			=> 	date('Y-m-d H:i:s'),			
						
		);			
		$result=$this->tableGateway->insert($data);
		return $result;			        
    }
	
	//public function getMentordetails-This function get mentor details(mentor videos,feedbacks,comments,upload,board and subject)based on mentorId
	public function getMentordetails($mentorId){
		 $select = $this->tableGateway->getSql()->select();
		 $select->join('board_content_view', new Expression('board_content_view.board_id=t_mentor_details.board_id AND board_content_view.board_class_id=t_mentor_details.class_id AND board_content_view.board_class_subject_id=t_mentor_details.subject_id'),array('*'),'left');
		 $select->join('t_mentor_videos', 't_mentor_videos.mentor_id=t_mentor_details.mentor_id',array('*'),'left');
		 $select->where(array('t_mentor_details.mentor_id'=>$mentorId));
		 $select->group('board_class_subject_id');
		 $resultSet = $this->tableGateway->selectWith($select);		 
		return $resultSet;
	}
	
	//public function deleteSubjects-This function delete mentorId based on subjectId
	public function deleteSubjects($mentorId){
		$this->tableGateway->delete(array('(mentor_id IN ('.$mentorId.'))'));
		return $this->tableGateway->lastInsertValue;
	}
	//public function checkMentor-This function checks mentorId exisits or not
	public function checkMentor($mentorId,$boardId,$classId,$subjectId){
		 $select = $this->tableGateway->getSql()->select();
		 $select->where(array('t_mentor_details.mentor_id'=>$mentorId,'t_mentor_details.board_id'=>$boardId,'t_mentor_details.class_id'=>$classId,'t_mentor_details.subject_id'=>$subjectId));
		 $resultSet = $this->tableGateway->selectWith($select);		 
		return $resultSet;
	}
}