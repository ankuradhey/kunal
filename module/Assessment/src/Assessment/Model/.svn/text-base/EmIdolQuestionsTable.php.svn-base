<?php
namespace EmIdol\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;

class EmIdolQuestionsTable
{
    protected $tableGateway;
    protected $select;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
	$this->select = new Select();
    }
    
    public function saveQuestions($data)
    {            
        //echo '<pre>'; print_r($data); exit;
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;			        
    }
    
    public function updateStatus($id,$data) {      
        $row=$this->tableGateway->update($data, array('question_id' => $id));
        return $row;	
    }
    
    public function getQuestionByQuestionId($question_id) {
        $select = $this->tableGateway->getSql()->select();
        $select->where('question_id="'.$question_id.'"');
        $rowSet = $this->tableGateway->selectWith($select);
        $rowArr = array();
        foreach($rowSet as $row) {
            $rowArr[] = (array)$row;
        }
        return $rowArr;
    }
    
    public function getAllQuestionsSetCount($className) {
        $select = $this->tableGateway->getSql()->select();
        $select->where('class="'.$className.'"');
        $rowSet = $this->tableGateway->selectWith($select);
        return count($rowSet);
    }
    
    public function getAllQuestionsIdsAsString($className) {
        $quesIdStr = '';
        $select = $this->tableGateway->getSql()->select();
        $select->where('class="'.$className.'"');
        $rowSet = $this->tableGateway->selectWith($select);
        foreach($rowSet as $row) {
            $quesIdStr .= $row->question_id.',';
        }
        $quesIdStr = rtrim($quesIdStr,',');
        return $quesIdStr;
    }
    
    public function getAllQuestionsSet(Array $filterArr, $contentFlag = NULL) {
        $filterArr = $filterArr + array('totalQuestion'=>20);
        if(isset($filterArr['className'])) {
            $className = $filterArr['className'];
        }
        
        $select = $this->tableGateway->getSql()->select()
                ->join(array('cqm'=>'content_question_media'),"cqm.question_id=em_idol_questions.question_id",array('*'))
                ->join(array('mc'=>'main_content'),"mc.content_id=cqm.content_id",array('*'))
                ->join(array('q'=>'questions'),"q.question_id=em_idol_questions.question_id",array('*'))
                ->join(array('qtm'=>'question_type_master'),"qtm.question_type_master_id=q.question_type_master_id",array('*'))
                ->join(array('a'=>'answers'),"a.question_id=q.question_id",array('*'))
                ->join(array('qa'=>'question_answer'),"qa.question_id=a.question_id",array('*'));
        $select->where('mc.status="1"');
        $select->where('qtm.question_type="Objective"');
        if(isset($filterArr['quesDifficulty'])) {
            $difficultyLevel = $filterArr['quesDifficulty'];
            $select->where('q.difficulty_master_id="'.$difficultyLevel.'"');
        }
        $select->where('em_idol_questions.class="'.$className.'"');
        $rowSet = $this->tableGateway->selectWith($select);
        $allQuestionSet = array();
        foreach($rowSet as $row) {
            $allQuestionSet[] = (array)$row;
        }
        //echo 'baljeet <pre>'; print_r($allQuestionSet); exit;
        return $allQuestionSet;
    }
    
    public function getAllAnswersByQuestionId($ques_id) {
        $select = $this->tableGateway->getSql()->select()
                ->join(array('a'=>'answers'),"a.question_id=em_idol_questions.question_id",array('*'),'Left');
        $select->where('em_idol_questions.question_id="'.$ques_id.'"');
        $rowSet = $this->tableGateway->selectWith($select);
        $allAnsSet = array();
        foreach($rowSet as $row) {
            $allAnsSet[] = (array)$row;
        }
        return $allAnsSet;
    }
        
}


