<?php
namespace Assessment\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql;
/**
 *
 * @author extramarks
 *     */
class MentorPaperQuestionTable extends TableGateway
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
    
	//public function inserdata-This function insert mentor paper question
    public function insertdata($data)
    {
          $this->tableGateway->insert($data);
          $id = $this->tableGateway->lastInsertValue;
          return $id;
    }
    
	//public function getPaperQuestionId-This function get paper questions by paperId
    public function getPaperQuestionId($paperId)
    { 
        $select = $this->tableGateway->getSql()->select();
               
   		$select->where("mentor_paper_id=$paperId") ;       
           $mentorPaperQuestionId = $this->tableGateway->selectWith($select);
           
        return $mentorPaperQuestionId;
    }
   
	//public function getPaperQuestionAnswer-This function get paper question and answer by paperId 
    public function getPaperQuestionAnswer($paperId)
    { 
        $select = $this->tableGateway->getSql()->select();
            $select->join(array('mpua'=>'mentor_paper_user_ans'), "mpua.mentor_paper_question_id=mentor_paper_questions.mentor_paper_question_id", array('*'),'left');
   		$select->where("mentor_paper_questions.mentor_paper_id=$paperId") ;       
           $mentorPaperQuestionId = $this->tableGateway->selectWith($select);
         
        return $mentorPaperQuestionId;
    } 
    
    
}


