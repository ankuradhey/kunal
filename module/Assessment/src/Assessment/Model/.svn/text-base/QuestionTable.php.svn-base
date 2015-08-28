<?php
namespace Assessment\Model;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql;


/**
 *
 * @author extramarks
 *     */
class QuestionTable extends TableGateway
{
    // TODO - Insert your code here
    protected $tableGateway;
    /**
     *
     * @param string $table            
     *
     * @param Adapter $adapter            
     *
     * @param Feature\AbstractFeature|Feature\FeatureSet|Feature\AbstractFeature[] $features            
     *
     * @param ResultSetInterface $resultSetPrototype            
     *
     * @param Sql $sql            
     *
     * @throws Exception\InvalidArgumentException
     *
     */
    
    public function __construct(TableGateway $tableGateway){
    	$this->tableGateway=$tableGateway;
    }
	
	//public function getUserDetails-This function gather userdetails from tables(these tables joined here those table fields grouped in one table i e board-class-subject) based userId
    public function getUserDetails($studentId=null, $mentorId=null){ 
           	$select=$this->tableGateway->getSql()->select();
   		$select->columns(array("email_id","first_name", "last_name"));
   		$select->join(array('tsam'=>'t_student_and_mentor'), "tsam.student_id = user.user_id", array('*'),'left');
		$select->join(array('tmd'=>'t_mentor_details'), "tsam.subject_id = tmd.subject_id", array('*'),'left');
		$select->join(array('bcv'=>'board_content_view'), "bcv.board_class_subject_id = tmd.subject_id", array('*'),'left');
   		$select->where("tsam.student_id=$studentId");
   		$select->where("tmd.mentor_id=$mentorId");
   		$select->group("bcv.board_class_subject_id");
           $userDetails=$this->tableGateway->selectWith($select);
           
    	return $userDetails;
    }
    
	//public function fetchAllSubjectQuestions-This function fetch all questions based on boardId,classId,subjectId order by ascending order
    public function fetchAllQuestions($leafNodearray,$limit=null){ 
//       $leafNodearray=array(243);
                  $select = $this->tableGateway->getSql()->select();         
                  $select->join(array("cqm"=>'content_question_media'), "main_content.content_id = cqm.content_id" );
                  $select->join(array("ser"=>'services'), "main_content.service_id = ser.service_id" );
                  $select->join(array("ques"=>'questions'), "cqm.question_id = ques.question_id" );
                 
                  $select->where("ser.service_name = 'Mentor'");// for the new added mentor service id
		 
		  $select->where(array('main_content.container_id'=>$leafNodearray));
                
                  $select->where("ques.question_type_master_id=2");
//                  $select->where("qc_flag = 'Yes'");
                  $select->order("ques.question_id ASC");
//                  echo '<pre>';print_r();echo '</pre>';die('Macro Die')
                 // $select->limit($limit);
                
       $questionList = $this->tableGateway->selectWith($select);
       $questionList->buffer();
      
       return $questionList;
    }
    
    //public function getChapterQuestions-This function get chapters questions(get each chapter questions) based on boardId,classId,subjectId order by ascending order
    public function getChapterQuestions($boardId, $classId, $subjectId, $questionId=null){ 
       
       $select = $this->tableGateway->getSql()->select();
                  $select->join(array("cqm"=>'content_question_media'), "board_content_view.content_id = cqm.content_id" );
                  $select->join(array("ques"=>'questions'), "cqm.question_id = ques.question_id" );
                  $select->columns(array("board_class_id", "board_class_subject_id", "chapter_name","board_class_subject_chapter_id"));
                  
                  $select->where("board_content_view.service_name = 'Mentor'");// for the new added mentor service id
                  
		  if($boardId != null){		
   		  $select->where("board_id=$boardId"); }
   		  
   		  if($classId != null){	
		  $select->where("board_class_id=$classId"); }
		  
		  if($subjectId != null){
		  $select->where("board_class_subject_id=$subjectId"); }
		  
                  $select->where("question_type=2");
                  $select->where("qc_flag = 'Yes'");
                  $select->order("ques.question_id ASC");
                  $select->limit(10);
                 if($questionId != null)
                 {
                       $select->where("ques.question_id=$questionId");  
                 }
        //echo '<pre>';print_r($select);die;
        $questionList = $this->tableGateway->selectWith($select);
        // echo '<pre>';print_r($questionList);die;
        return $questionList;
    }
}


