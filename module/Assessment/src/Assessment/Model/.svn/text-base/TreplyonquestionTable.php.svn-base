<?php

namespace Assessment\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;

class TreplyonquestionTable {

    protected $tableGateway;
    protected $select;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->select = new Select();
    }

    //public function getReplys-This function  user send question to mentors ,mentors replyed the question those replys get based on questionId
    public function getReplys($replys) {
        $select = $this->tableGateway->getSql()->select();
        $select->join('user', 't_reply_on_question.user_id=user.user_id', array('userid' => 'user_id', 'first_name' => 'display_name', 'user_photo' => 'user_photo', 'school_name' => 'school_name' ,'email_id'=>'email' ,'address'=>'address','mobile'=>'phone'), 'left');
        $select->join('t_user_type', 'user.user_type_id=t_user_type.user_type_id', array('*'), 'left');
        $select->join(array("rackboard" => 'resource_rack'), 'user.board_id=rackboard.rack_id', array('boardid' => 'rack_id'), 'left');
        $select->join(array("rackboardname" => 'rack_name'), 'rackboardname.rack_name_id=rackboard.rack_name_id', array('board_name' => new Expression('rackboardname.name')), 'left');
        $select->join(array("rackclass" => 'resource_rack'), 'user.class_id=rackclass.rack_id', array('classid' => 'rack_id'), 'left');
        $select->join(array("rackclassname" => 'rack_name'), 'rackclassname.rack_name_id=rackclass.rack_name_id', array('class_name' => new Expression('rackclassname.name')), 'left');
        $select	->join(array("Tuser"=>'user'), 'Tuser.user_id=t_reply_on_question.deleted_by',array('deleted_user'=>'display_name'),'left');
		      
        
//$select	->join('t_question', 't_reply_on_question.reply_on_question=t_question.question_id',array('*'),'left');
        //$select->join(array("subjectname"=>'board_class_subject_chapters'),'subjectname.board_class_subject_id=t_question.subject_id',array('board_class_subject_id'=>'board_class_subject_id','subject_name'=>'subject_name'),'left');
        //$select->join(array("chaptername"=>'board_class_subject_chapters'),'chaptername.board_class_subject_chapter_id=t_question.chapter_id',array('board_class_subject_chapter_id'=>'board_class_subject_chapter_id','chapter_name'=>'chapter_name'),'left');
        $select->where('t_reply_on_question.reply_on_question IN (' . $replys['questionid'] . ')');
        $select->order("t_reply_on_question.reply_id DESC");
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    //public function addReply-This function add replys metors give the replys ,those details added
    public function addReply($replys,$userId,$remesage) {
      
        $data = array(
            'reply_on_question' => $replys['questionid'],
            'user_id' => $userId,
            'reply_message' => $remesage,
            'reply_date' => date('Y-m-d H:i:s'),
            'reply_status' => 0,
        );
        $result = $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }

    //public function countReplys-This function get counts total replys from each question based on questionId
    public function countReplys($replys) {
        $select = $this->tableGateway->getSql()->select();
        $select->where('reply_on_question="' . $replys['questionid'] . '"');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet->count();
    }

    public function updateStatus($ids, $data) {
        $row = $this->tableGateway->update($data, array('reply_id' => $ids));
        return $row;
    }

}