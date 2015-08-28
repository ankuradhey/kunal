<?php

namespace Assessment\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql;
use Zend\Db\Sql\Predicate\Expression;

class InviteexternalemailTable extends TableGateway {

    protected $tableGateway;
    protected $select;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->select = new Select();
    }

    //public function addRelation-This function user can add mentor
    public function inviteRelation($login_id, $email, $additionType, $boardId = NULL, $classID = NULL, $subjectId = NULL, $customboardid = NULL) {

        $data = array(
            'invitation_from_id' => $login_id,
            'invited_email' => $email,
            'invitation_for' => $additionType,
            'board_id' => $boardId,
            'class_id' => $classID,
            'subject_id' => $subjectId,
            'custom_board_rack_id' => $customboardid
        );
        $this->tableGateway->insert($data);
        $select = $this->tableGateway->getSql()->select()
                ->where('invitation_from_id="' . $login_id . '"')
                ->where('invited_email="' . $email . '"')
                ->where('invitation_for="' . $additionType . '"');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        return $this->tableGateway->lastInsertValue;
    }
    
    public function checkDuplicateInvite($login_id, $email, $additionType,$subjectId=NULL){
        $select = $this->tableGateway->getSql()->select();
        $select->where('invitation_from_id="' . $login_id . '"');
        $select->where('invited_email="' . $email . '"');
        $select->where('invitation_for="' . $additionType . '"');
        if($subjectId){
            $select->where('subject_id="' . $subjectId . '"');
        }
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet->count();
    }
    
    public function checkVaildInvitationLearner($user,$additionType,$status){
        $select = $this->tableGateway->getSql()->select();
        $select->where('invited_email="' . $user->getEmail() . '"');
        $select->where('invitation_for="' . $additionType . '"');
        $select->where('status="' . $status . '"');
        $resultSet = $this->tableGateway->selectWith($select);
        $countRes = $resultSet->count();
        if ($countRes != '0') {
            foreach ($resultSet as $result) {
                $id = $result->id;
            }
            $data = array('status' => 1);
            $row = $this->tableGateway->update($data, array('id' => $id));
            return $result;
        }else {
            return '0';
        }
    }
    public function checkVaildInvitationLearnerCount($user,$additionType,$status){
        $select = $this->tableGateway->getSql()->select();
        $select->where('invited_email="' . $user->getEmail() . '"');
        $select->where('invitation_for="' . $additionType . '"');
        $select->where('status="' . $status . '"');
        $resultSet = $this->tableGateway->selectWith($select);
        $countRes = $resultSet->count();
        return $countRes;
    }


}