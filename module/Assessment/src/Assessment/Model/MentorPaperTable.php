<?php
namespace Assessment\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql;
use Zend\Db\Sql\Expression;
/**
 *
 * @author extramarks
 *     */
class MentorPaperTable extends TableGateway
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
    
	//public function insertdata-This function insert self questions
    public function insertdata($data)
    {
          $this->tableGateway->insert($data);
          $id = $this->tableGateway->lastInsertValue;
          return $id;
    }
    
	//public function getPaperQuestion-This function get mentor creation question paper based on paperId
    public function getPaperQuestion($paperId, $paperSource)
    { 
        $select = $this->tableGateway->getSql()->select();
               if($paperSource == 'self')
               { 
                        $select->join(array('mq'=>'mentor_question'), new Expression("mentor_paper.mentor_paper_id = mq.mentor_paper_id"), array('mentor_question_id', 'mentor_question', 'mentor_question_marks'),'left');
               }
               else if($paperSource == 'upload')
               { 
                     $select->join(array('mpuf'=>'mentor_paper_upload_file'), "mpuf.mentor_paper_id = mentor_paper.mentor_paper_id", array('*'),'left');   
               }
               else
               { 
                    $select->join(array('mpq'=>'mentor_paper_questions'), "mpq.mentor_paper_id = mentor_paper.mentor_paper_id", array('*'),'left'); 
                    $select->join(array('ques'=>'questions'), ("ques.question_id = mpq.question_id"), array('question'),'left');        
               }
               
   		$select->where("mentor_paper.mentor_paper_id=$paperId") ;       
           $mentorPaperList = $this->tableGateway->selectWith($select);
       
        return $mentorPaperList;
    }
    
	//public function getPaperUserQuestionAnswer-This function get user question and answer paper based on paperId
    public function getPaperUserQuestionAnswer($paperId, $paperSource)
    {
        $select = $this->tableGateway->getSql()->select();
               if($paperSource == 'self')
               { 
                        $select->join(array('mq'=>'mentor_question'), new Expression("mentor_paper.mentor_paper_id = mq.mentor_paper_id"), array('mentor_question_id', 'mentor_question', 'mentor_question_marks'),'left');
                        $select->join(array('mpua'=>'mentor_paper_user_ans'), "mpua.mentor_paper_question_id = mq.mentor_question_id", array('*'),'left');
               }
               else if($paperSource == 'upload')
               { 
                     $select->join(array('mpuf'=>'mentor_paper_upload_file'), "mpuf.mentor_paper_id = mentor_paper.mentor_paper_id", array('*'),'left');   
               }
               else
               { 
                    $select->join(array('mpq'=>'mentor_paper_questions'), "mpq.mentor_paper_id = mentor_paper.mentor_paper_id", array('*'),'left'); 
                    $select->join(array('ques'=>'questions'), "ques.question_id = mpq.question_id", array('question'),'left');     
                    $select->join(array('mpua'=>'mentor_paper_user_ans'), "mpua.mentor_paper_question_id = mpq.mentor_paper_question_id", array('*'),'left');   
               }
               
   	        /*$select->join(array('mpq'=>'mentor_paper_questions'), "mpq.mentor_paper_id = mentor_paper.mentor_paper_id", array('*'),'left');
		$select->join(array('mq'=>'mentor_question'), new Expression("mq.mentor_question_id = mpq.mentor_question_id and (mpq.mentor_paper_question_source = 'self' or mpq.mentor_paper_question_source = 'upload')"), array('mentor_question', 'mentor_question_marks'),'left');
		
		
		$select->join(array('ques'=>'questions'), new Expression("ques.question_id = mpq.mentor_question_id and mpq.mentor_paper_question_source = 'lms'"), array('question', 'meta_marks'),'left');
		$select->join(array('mpua'=>'mentor_paper_user_ans'), "mpua.mentor_paper_question_id = mpq.mentor_paper_question_id", array('*'),'left');*/
		
   		$select->where("mentor_paper.mentor_paper_id=$paperId") ; 
   		
           $mentorPaperList = $this->tableGateway->selectWith($select);
          // echo '<pre>';print_r($mentorPaperList);die;      
        return $mentorPaperList;
    }
    
    //public function updateData-This function update user answers
    public function updateData($id, $data)
    {  
       if($id != null)
       { 
           // update the existing record
           
           $updateArray = array('mentor_paper_id' => $id);
           $this->tableGateway->update($data, $updateArray); 
       }
    }
    
    public function getMentorNameByPaperId($paperId){
        $select = $this->tableGateway->getSql()->select();		
        $select->where('mentor_paper_id="'.$paperId.'"');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        return $row->mentorId;
        
    }
    
}


