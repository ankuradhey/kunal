<?php

namespace Assessment\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql;
use Zend\Db\Sql\Predicate\Expression;

class TstudentandmentorTable extends TableGateway {

    protected $tableGateway;
    protected $select;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->select = new Select();
    }


    //public function addRelation-This function user can add mentor
    public function addRelationForAPI($id, $sId, $additionType = null, $studentId, $requestBy = null) {

        if ($additionType == 'student') {
            $studentId = $id;
            $mentorId = $studentId;
            $requestInitiator = $mentorId;
        } else {
            $studentId = $studentId;
            $mentorId = $id;
            $requestInitiator = $studentId;
        }
        if ($requestBy != '') {
            $studentId = $requestBy;
            $mentorId = $id;
            $requestInitiator = $requestBy;
        }
        $data = array(
            'student_id' => $studentId,
            'mentor_id' => $mentorId,
            'subject_id' => $sId,
            'status' => 0,
            'request_initiator' => $requestInitiator,
        );
        $this->tableGateway->insert($data);
        $select = $this->tableGateway->getSql()->select()
                ->where('student_id="' . $studentId . '"')
                ->where('mentor_id="' . $mentorId . '"');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        return $this->tableGateway->lastInsertValue;
    }




//public function addRelation-This function user can add mentor
    public function addRelation($id, $sId, $additionType = null, $loggedInUserObj, $requestBy = null,$customBoardId=null) {

        if ($additionType == 'student') {
            $studentId = $id;
            $mentorId = $loggedInUserObj->getId();
            $requestInitiator = $mentorId;
        } else {
            $studentId = $loggedInUserObj->getId();
            $mentorId = $id;
            $requestInitiator = $studentId;
        }
        if ($requestBy != '') {
            $studentId = $requestBy;
            $mentorId = $id;
            $requestInitiator = $requestBy;
        }
        $data = array(
            'student_id' => $studentId,
            'mentor_id' => $mentorId,
            'subject_id' => $sId,
            'status' => 0,
            'custom_board_rack_id'=>$customBoardId,
            'request_initiator' => $requestInitiator,
        );
        $this->tableGateway->insert($data);
        $select = $this->tableGateway->getSql()->select()
                ->where('student_id="' . $studentId . '"')
                ->where('mentor_id="' . $mentorId . '"');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        return $this->tableGateway->lastInsertValue;
    }
    
    
    public function addInviteLearnerRelation($id, $sId, $loggedInUserObj, $requestBy = null) {
        
        
        
        $select = $this->tableGateway->getSql()->select();
        $select->where('student_id="' . $id . '"');
        $select->where('mentor_id="' . $loggedInUserObj->getId() . '"');
        $select->where('subject_id IS NULL');
        $select->where('status= "0"');
        $resultSet = $this->tableGateway->selectWith($select);
        if($resultSet->count() == '1'){
            foreach($resultSet as $key => $res){
                $stumentor_id = $res->id;
            }
            $data = array(
                    'subject_id' => $sId
                    );
            $row = $this->tableGateway->update($data, array('id' => $stumentor_id));
            return 1;
            
        }
        
        
        $data = array(
            'student_id' => $id,
            'mentor_id' => $loggedInUserObj->getId(),
            'subject_id' => $sId,
            'status' => 0,
            'request_initiator' => $loggedInUserObj->getId(),
        );
        $this->tableGateway->insert($data);
        $select = $this->tableGateway->getSql()->select()
                ->where('student_id="' . $id . '"')
                ->where('mentor_id="' . $loggedInUserObj->getId() . '"');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        return $this->tableGateway->lastInsertValue;
    }
    
    public function addRelationByInvitation($id, $sId, $additionType, $loggedInUserObj) {

        if ($additionType == 'student') { // invitation from student
            $studentId = $id;
            $mentorId = $loggedInUserObj->getId();
            $requestInitiator = $studentId;
        } else if ($additionType == 'mentor'){ // invitation from mentor
            $studentId = $loggedInUserObj->getId();
            $mentorId = $id;
            $requestInitiator = $mentorId;
        }
        $data = array(
            'student_id' => $studentId,
            'mentor_id' => $mentorId,
            'subject_id' => $sId,
            'status' => 0,
            'request_initiator' => $requestInitiator,
        );
        $this->tableGateway->insert($data);
        $select = $this->tableGateway->getSql()->select()
                ->where('student_id="' . $studentId . '"')
                ->where('mentor_id="' . $mentorId . '"');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        return $this->tableGateway->lastInsertValue;
    }

    //public function changeStatus-This function changes user status
    public function changeStatus($ids, $value) {

        $data = array(
            'status' => $value,
        );
        $row = $this->tableGateway->update($data, array('id' => $ids));
        return $row;
    }

    //public function checkRelation-This function checks studentId and mentorId count their relationships
    public function checkRelation($mId = '', $sId, $subjectId = '') {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array("id"=>"id"));
        $select->where('student_id="' . $sId . '"');
        if ($mId != '') {
            $select->where('mentor_id="' . $mId . '"');
        }
        if ($subjectId != '') {
            $select->where('subject_id="' . $subjectId . '"');
            $select->where('status !="2"');
        }
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet->count();
    }

    //public function getAll-This function get all mentors 
    public function getAll($userId, $type, $status = '') {
        $sql = $this->tableGateway->getSql();
        $select = $this->tableGateway->getSql()->select();
        if ($type == 'student') {
            $select->join('user', 'user.user_id=t_student_and_mentor.mentor_id', array('*'), 'left');
            $select->join('t_user_type', 'user.user_type_id=t_user_type.user_type_id', array('user_type' => 'name'), 'left');
        } else {
            $select->join('user', 'user.user_id=t_student_and_mentor.student_id', array('*'), 'left');
            $select->join('t_user_type', 'user.user_type_id=t_user_type.user_type_id', array('user_type' => 'name'), 'left');
        }
//		$select	->join('user', 'user.user_type_id=t_user.user_type_id',array('*'),'left');

        if ($type == 'student') {
            $select->join(array('r1' => 'resource_rack'), 'r1.rack_id=t_student_and_mentor.subject_id', array("sub_id"=>'rack_id'), 'left');
            $select->join(array('rn1' => 'rack_name'), 'rn1.rack_name_id=r1.rack_name_id', array('subject_name' => 'name'), 'left');
            $select->join(array('r2' => 'resource_rack'), 'r2.rack_id=r1.rack_container_id', array("class_ids"=>"rack_id"), 'left');
            $select->join(array('rn2' => 'rack_name'), 'rn2.rack_name_id=r2.rack_name_id', array('class_name' => 'name'), 'left');
            $select->join(array('cbr' => 'custom_board_rack'), 'cbr.custom_board_rack_id=t_student_and_mentor.custom_board_rack_id', array("board_ids"=>"custom_board_id"), 'left');
            $select->join(array('cb' => 'custom_board'), 'cb.custom_board_id=cbr.custom_board_id', array("board_name"=>"board_name"), 'left');
            $select->where('t_student_and_mentor.student_id="' . $userId . '"');
        } else {
            $select->join(array('r1' => 'resource_rack'), 'r1.rack_id=t_student_and_mentor.subject_id', array("sub_id"=>'rack_id'), 'left');
            $select->join(array('rn1' => 'rack_name'), 'rn1.rack_name_id=r1.rack_name_id', array('subject_name' => 'name'), 'left');
            $select->join(array('r2' => 'resource_rack'), 'r2.rack_id=r1.rack_container_id', array("class_ids"=>"rack_id"), 'left');
            $select->join(array('rn2' => 'rack_name'), 'rn2.rack_name_id=r2.rack_name_id', array('class_name' => 'name'), 'left');
            $select->join(array('cbr' => 'custom_board_rack'), 'cbr.custom_board_rack_id=t_student_and_mentor.custom_board_rack_id', array("board_ids"=>"custom_board_id"), 'left');
            $select->join(array('cb' => 'custom_board'), 'cb.custom_board_id=cbr.custom_board_id', array("board_name"=>"board_name"), 'left');
            $select->where('t_student_and_mentor.mentor_id="' . $userId . '"');
        }
        if ($type == 'mentor') {
            if ($status == 'activestu')
                $select->where('t_student_and_mentor.status ="1"');
            $select->group('t_student_and_mentor.id');
        }else {
            $select->group('t_student_and_mentor.id');
        }
        $select->join(array('ut' => 't_user_type'), 'ut.user_type_id=user.user_type_id', array('ut_name' => 'name'), 'left');
        if ($type == 'mentor') {
            $select->order('user.display_name');
        }
        
        $select->order('t_student_and_mentor.id DESC');
        $select->where('t_student_and_mentor.status !="2"');
        $select->where('t_student_and_mentor.custom_board_rack_id !=""');
        
        //echo $sql->getSqlstringForSqlObject($select); die;
        $resultSet = $this->tableGateway->selectWith($select);
        //echo "<pre />"; print_r($resultSet); exit;
        return $resultSet;
    }

    //public function deleteOperation-This function deleted exists mentors form their list
    public function deleteOperation($ids) {
        $studentId = $_SESSION['user']['userId'];
        $this->tableGateway->delete(array('(id IN (' . $ids . '))'));
        return $this->tableGateway->lastInsertValue;
    }

    //public function checkStatus-This function checks mentor status based on studentId,mentorId and status 
    public function checkStatus($sId, $mId) {
        $select = $this->tableGateway->getSql()->select()
                ->where('student_id="' . $sId . '"')
                ->where('mentor_id="' . $mId . '"')
                ->where('status="1"');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function getsubjectId($Id) {
        $select = $this->tableGateway->getSql()->select()
                ->where('id="' . $Id . '"');

        $resultSet = $this->tableGateway->selectWith($select);
        $subjectid="";
        if ($resultSet != '') {
            foreach ($resultSet as $result) {
                $subjectid = $result->subject_id;
            }
            return $subjectid;
        } else {
            return '0';
        }
    }
    
    public function getstudentId($Id) {
        $select = $this->tableGateway->getSql()->select()
                ->where('id="' . $Id . '"');

        $resultSet = $this->tableGateway->selectWith($select);
        if ($resultSet != '') {
            foreach ($resultSet as $result) {
                $subjectid = $result->student_id;
            }
            return $subjectid;
        } else {
            return '0';
        }
    }
    
    //public function countNoactiveMentors-This function counts the no groups userId's on Db
    public function countNoactiveMentors($id) {
        $select = $this->tableGateway->getSql()->select();
        $select->join('user', 't_user.user_id=t_student_and_mentor.student_id', array('*'), 'left');
        $select->join('board_content_view', 'board_content_view.board_class_subject_id=t_student_and_mentor.subject_id', array('board_id', 'board_name', 'class_name', 'subject_name', 'board_class_id', 'board_class_parent_subject_id', 'parent_subject_name', 'board_class_subject_id'), 'left');
        $select->where("t_student_and_mentor.mentor_id='" . $id . "'");
        $select->where("t_student_and_mentor.status='0'");
        $select->group('t_student_and_mentor.id');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet;
        return $row;
    }

    public function getAllStudents($userId, $type, $id = '', $subjectid = '') {
        $select = $this->tableGateway->getSql()->select();
        if ($type == 'student') {
            $select->columns(array("id"=>"id","student_id","stud_mentor_id"=>"mentor_id","status"=>"status"));
            $select->join('user', 'user.user_id=t_student_and_mentor.mentor_id', array('*'), 'left');
        } else {
            $select->join('user', 'user.user_id=t_student_and_mentor.student_id', array('*'), 'left');
        }
        $select->join('t_user_type', 't_user_type.user_type_id=user.user_type_id', array('*'), 'left');

        if ($type == 'student') {
            $select->join('t_mentor_details', new Expression('t_mentor_details.mentor_id=t_student_and_mentor.mentor_id AND t_mentor_details.subject_id=t_student_and_mentor.subject_id'), array('*'), 'left');
            
            $select->join(array('r1' => 'resource_rack'), 'r1.rack_id=t_student_and_mentor.subject_id', array("sub_id"=>'rack_id'), 'left');
            $select->join(array('rn1' => 'rack_name'), 'rn1.rack_name_id=r1.rack_name_id', array('subject_name' => 'name'), 'left');
            $select->join(array('r2' => 'resource_rack'), 'r2.rack_id=r1.rack_container_id', array("class_ids"=>"rack_id"), 'left');
            $select->join(array('rn2' => 'rack_name'), 'rn2.rack_name_id=r2.rack_name_id', array('class_name' => 'name'), 'left');
            $select->join(array('cbr' => 'custom_board_rack'), 'cbr.custom_board_rack_id=t_student_and_mentor.custom_board_rack_id', array("board_ids"=>"custom_board_id"), 'left');
            $select->join(array('cb' => 'custom_board'), 'cb.custom_board_id=cbr.custom_board_id', array("board_name"=>"board_name"), 'left');
           // $select->join('board_content_view', 'board_content_view.board_class_subject_id=t_mentor_details.subject_id', array('board_id', 'board_name', 'class_name', 'subject_name', 'board_class_id', 'board_class_parent_subject_id', 'parent_subject_name'), 'left');
            $select->where('t_student_and_mentor.student_id="' . $userId . '"');
        } else {
            $select->join('t_mentor_details', new Expression('t_mentor_details.mentor_id=t_student_and_mentor.mentor_id AND t_mentor_details.subject_id=t_student_and_mentor.subject_id'), array('*'), 'left');
          //  $select->join('board_content_view', 'board_content_view.board_class_subject_id=t_mentor_details.subject_id', array('board_id', 'board_name', 'class_name', 'subject_name', 'board_class_id', 'board_class_parent_subject_id', 'parent_subject_name'), 'left');
            $select->join(array('r1' => 'resource_rack'), 'r1.rack_id=t_student_and_mentor.subject_id', array("sub_id"=>'rack_id'), 'left');
            $select->join(array('rn1' => 'rack_name'), 'rn1.rack_name_id=r1.rack_name_id', array('subject_name' => 'name'), 'left');
            $select->join(array('r2' => 'resource_rack'), 'r2.rack_id=r1.rack_container_id', array("class_ids"=>"rack_id"), 'left');
            $select->join(array('rn2' => 'rack_name'), 'rn2.rack_name_id=r2.rack_name_id', array('class_name' => 'name'), 'left');
            $select->join(array('cbr' => 'custom_board_rack'), 'cbr.custom_board_rack_id=t_student_and_mentor.custom_board_rack_id', array("board_ids"=>"custom_board_id"), 'left');
            $select->join(array('cb' => 'custom_board'), 'cb.custom_board_id=cbr.custom_board_id', array("board_name"=>"board_name"), 'left');
            
            $select->where('t_student_and_mentor.mentor_id="' . $userId . '"');
        }
        $select->where('t_student_and_mentor.status="1"');
        if ($type == 'mentor') {
            if ($id != '') {
                $select->where('t_student_and_mentor.student_id="' . $id . '"');
            }
            $select->group('t_student_and_mentor.id');
        } else {
            if ($id != '') {
                $select->where('t_student_and_mentor.mentor_id="' . $id . '"');
            }
            $select->group('t_student_and_mentor.id');
        }
        if ($subjectid != '') {
            $select->where('r1.rack_id="' . $subjectid . '"');
        }
        $select->order('t_student_and_mentor.id DESC');
        //echo $this->tableGateway->getSql()->getSqlStringForSqlObject($select); exit;
        $resultSet = $this->tableGateway->selectWith($select);
       // echo '<pre>'; print_r($resultSet); exit;
        return $resultSet;
    }

    //public function countInactivestudentsMentors-This function counts the Inactive or pending mentor requests user have 

    public function countInactivestudentsMentors($id, $status, $type) {
        $select = $this->tableGateway->getSql()->select();
        $select->join('t_user', 't_user.user_id=t_student_and_mentor.student_id', array('*'), 'left');
        $select->join('board_content_view', 'board_content_view.board_class_subject_id=t_student_and_mentor.subject_id', array('board_id', 'board_name', 'class_name', 'subject_name', 'board_class_id', 'board_class_parent_subject_id', 'parent_subject_name', 'board_class_subject_id'), 'left');
        if ($type == 'student') {
            $select->where("t_student_and_mentor.student_id='" . $id . "'");
        } if ($type == 'mentor') {
            $select->where("t_student_and_mentor.mentor_id='" . $id . "'");
        }
        $select->where("t_student_and_mentor.status='" . $status . "'");
        //$select->where("t_student_and_mentor.status='0'");
        $select->group('t_student_and_mentor.id');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet;
        return $row;
    }

    public function getStudentMentorRelationDetails($studentMentorRelationId) {
        $select = $this->tableGateway->getSql()->select();
        $select->join('user', 'user.user_id=t_student_and_mentor.student_id', array('*'), 'left');
        $select->join('resource_rack', 'resource_rack.rack_id=t_student_and_mentor.subject_id', array('*'), 'left');
        $select->join(array('r1' => 'resource_rack'), 'r1.rack_id=resource_rack.rack_container_id', array('class_id' => 'rack_id'), 'left');
        $select->join(array('cbr' => 'custom_board_rack'), 'cbr.custom_board_rack_id=t_student_and_mentor.custom_board_rack_id', array('board_id' => 'custom_board_id'), 'left');
        $select->join(array('cb' => 'custom_board'), 'cbr.custom_board_id=cb.custom_board_id', array('board_name' => 'board_name'), 'left');
        //$select->join(array('r2' => 'resource_rack'), 'r2.rack_id=r1.rack_container_id', array('board_id' => 'rack_id'), 'left');
        $select->join(array('rack_name1' => 'rack_name'), 'r1.rack_name_id=rack_name1.rack_name_id', array('class_name' => 'name'), 'left');
        //$select->join(array('rack_name2' => 'rack_name'), 'r2.rack_name_id=rack_name2.rack_name_id', array('board_name' => 'name'), 'left');
        $select->join(array('rack_name3' => 'rack_name'), 'resource_rack.rack_name_id=rack_name3.rack_name_id', array('subject_name' => 'name'), 'left');
//            $select->join('board_content_view', 'board_content_view.board_class_subject_id=t_student_and_mentor.subject_id',array('board_id','board_name','class_name','subject_name','board_class_id','board_class_parent_subject_id','parent_subject_name','board_class_subject_id'),'left');
        $select->where("t_student_and_mentor.id='" . $studentMentorRelationId . "'");
        $select->where("t_student_and_mentor.status='1'");
        $select->group("t_student_and_mentor.subject_id");
        $resultSet = $this->tableGateway->selectWith($select);
//            echo '<pre>';print_r($resultSet);echo '</pre>';die('Macro Die');
        return $resultSet;
    }
    
    public function getStudentUserData($userId){
        $select = $this->tableGateway->getSql()->select();
        $select->join(array("user" => 'user'),'user.user_id=t_student_and_mentor.mentor_id',  array('user_id'), 'left');            
        $select->where('t_student_and_mentor.student_id="'.$userId.'"');
        $select->where('t_student_and_mentor.status="1"');
        return $resultSet = $this->tableGateway->selectWith($select);
    }
    
    public function getMentorUserData($userId){
        $select = $this->tableGateway->getSql()->select();
        $select->join(array("user" => 'user'),'user.user_id=t_student_and_mentor.student_id',  array('user_id'), 'left');            
        $select->where('t_student_and_mentor.mentor_id="'.$userId.'"');
        $select->where('t_student_and_mentor.status="1"');
        return $resultSet = $this->tableGateway->selectWith($select);
    }
    
    public function getStudentMentorRelationRows($mentorId,$studentId) {
        $select = $this->tableGateway->getSql()->select();          
        $select->where('mentor_id="'.$mentorId.'"');
        $select->where('student_id="'.$studentId.'"');
        $select->where('status="1"');
        return $resultSet = $this->tableGateway->selectWith($select);
    }

}