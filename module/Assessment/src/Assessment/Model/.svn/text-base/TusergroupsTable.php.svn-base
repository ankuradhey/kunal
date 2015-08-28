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

class TusergroupsTable {

    protected $tableGateway;
    protected $select;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
        $this->select = new Select();
    }

    //public function getAllfriends-This function get group friends details (rackboard,rackname...) based on groupId
    public function getAllfriends($userId) {

        $select = $this->tableGateway->getSql()->select();
        $select->join('user', 't_user_groups.friend_id=user.user_id', array('*'), 'left');
        $select->join(array("cbr" => 'custom_board_rack'), 'cbr.custom_board_rack_id=user.custom_board_rack_id', array("board_rack_id"=>'custom_board_id'), 'left');
        $select->join(array("r2" => 'resource_rack'), 'r2.rack_id=user.class_id', array("class_rack_id"=>'rack_id'), 'left');
        $select->join(array("cb" => 'custom_board'), 'cb.custom_board_id=cbr.custom_board_id', array('board_name' => 'board_name'), 'left');
        $select->join(array("rc2" => 'rack_name'), 'rc2.rack_name_id=r2.rack_name_id', array('class_name' => 'name'), 'left');

        /* $select->join(array("rackboard"=>'resource_rack'), 't_user.board_id=rackboard.rack_id',array('*'),'left');
          $select	->join(array("rackboardname"=>'rack_name'), 'rackboardname.rack_name_id=rackboard.rack_name_id',array('board_name' => new Expression('rackboardname.name')),'left');
          $select	->join(array("rackclass"=>'resource_rack'), 't_user.class_id=rackclass.rack_id',array('*'),'left');
          $select	->join(array("rackclassname"=>'rack_name'), 'rackclassname.rack_name_id=rackclass.rack_name_id',array('class_name' => new Expression('rackclassname.name')),'left'); */
        $select->where('t_user_groups.user_id="' . $userId . '"');
        //$select->where->OR->equalTo('t_user_groups.friend_id', $userId);
        $select->where('t_user_groups.group_status!="2"');
        $row = $this->tableGateway->selectWith($select);
        return $row;
    }

    //public function getAllfriendsrequests-This function get friends requset whome you send the request,based on status
    public function getAllfriendsrequests($userId) {
        
        $select = $this->tableGateway->getSql()->select();
        $select->join('user', 't_user_groups.user_id=user.user_id', array('*'), 'left');
      //  $select->join('board_content_view', new Expression('board_content_view.board_id=t_user.board_id And board_content_view.board_class_id=t_user.class_id'), array('board_class_parent_subject_id', 'parent_subject_name', 'subject_name', 'chapter_name', 'board_name', 'class_name'), 'left');
        $select->join(array("r1" => 'resource_rack'), 'r1.rack_id=user.board_id', array("board_rack_id"=>'rack_id'), 'left');
        $select->join(array("r2" => 'resource_rack'), 'r2.rack_id=user.class_id', array("class_rack_id"=>'rack_id'), 'left');
        $select->join(array("rc1" => 'rack_name'), 'rc1.rack_name_id=r1.rack_name_id', array('board_name' => 'name'), 'left');
        $select->join(array("rc2" => 'rack_name'), 'rc2.rack_name_id=r2.rack_name_id', array('class_name' => 'name'), 'left');

        $select->where('t_user_groups.friend_id="' . $userId . '"');
        $select->where('t_user_groups.group_status !="2"');
        $select->group('t_user_groups.group_id');
        $row = $this->tableGateway->selectWith($select);

        return $row;
    }

    //public function checkEmail-This function checks email when you send your friend request it checks Userid exists or not in db
    public function checkEmail($friendid,$userId) {
       
        $select = $this->tableGateway->getSql()->select()
                ->where("friend_id='" . $friendid . "'")
                ->where("user_id='" . $userId . "'");
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->count();
        return $row;
    }

    //public function verifyEmail-This function verifies after added friends emailId's based on userId
    public function verifyEmail($friendid,$userId) {
      
        $select = $this->tableGateway->getSql()->select()
                ->where("user_id='" . $userId . "'")
                ->where("friend_id='" . $friendid . "'");
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->count();
        return $row;
    }

    //public function addfriends-This function added friend details(userId,friendId,date and status)
    public function addfriends($friendid, $groupstatus,$userId) {
      
        $data = array(
            'user_id' => $userId,
            'friend_id' => $friendid,
            'view_group_member_status' => $groupstatus,
            'added_date' => date('Y-m-d H:i:s'),
            'group_status' => 0,
        );
        $result = $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }

    //public function deleteOperation-This function deletes groups based on groupId
    public function deleteOperation($ids) {
        $this->tableGateway->delete(array('(group_id IN (' . $ids . '))'));
        return $this->tableGateway->lastInsertValue;
    }

    //pubilc function changeStatus-This function changes the group status
    public function changeStatus($ids, $value) {
        $data = array(
            'group_status' => "$value",
        );
        // echo '<pre>'; print_r($data); die;
        $row = $this->tableGateway->update($data, array('group_id' => $ids));
        return $row;
    }

    public function updateStatus($ids, $data) {

        $row = $this->tableGateway->update($data, array('group_id' => $ids));
        return $row;
    }

    //public function getallrecords-This function get all group members records based on groupId
    public function getallrecords($userId, $is_friend = '') {
        $select = $this->tableGateway->getSql()->select();
        $select->where('t_user_groups.group_status="1"');
        if ($is_friend != '') {
            $select->where('t_user_groups.user_id IN (' . $userId . ') or t_user_groups.friend_id IN (' . $userId . ')');
            $select->where("friend_id!='" . $is_friend . "'");
        } else {
            $select->where('t_user_groups.user_id IN (' . $userId . ')');
        }
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    //public function checkGroup-This function checks friend groupId based on userId
    public function checkGroup($friendid) {
        if (isset($_SESSION['user']['userId'])) {
            $userId = $_SESSION['user']['userId'];
        } else {
            $userId = '';
        }
        $select = $this->tableGateway->getSql()->select()
                ->where("friend_id='" . $friendid . "'")
                ->where("user_id='" . $userId . "'");
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet;
        return $row;
    }

    //public function countNoactive-This function checks User's have group or not in db based group status
    public function countNoactive($id) {
        $select = $this->tableGateway->getSql()->select();
        $select->join('user', 'user.user_id=t_user_groups.user_id', array('*'), 'left')
                ->where("t_user_groups.friend_id='" . $id . "'")
                ->where("t_user_groups.group_status='0'");
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet;
        return $row;
    }

    public function checkStatusOfGroupIds($id,$UserId) {
        $result = $this->checkStatusOfGroupIdsTwo($id);
        if ($result != 0) {
            $select = $this->tableGateway->getSql()->select()
                    ->where("t_user_groups.friend_id='" . $UserId . "'")
                    ->where("t_user_groups.user_id='" . $id . "'")
                    ->where("t_user_groups.group_status='1'");
            $resultSet = $this->tableGateway->selectWith($select);
            $row = $resultSet->count();
        } else {
            $row = 0;
        }
        return $row;
    }

    public function checkStatusOfGroupIdsTwo($id) {
        $select = $this->tableGateway->getSql()->select()
                ->where("t_user_groups.user_id='" . @$_SESSION['user']['userId'] . "'")
                ->where("t_user_groups.friend_id='" . $id . "'")
                ->where("t_user_groups.group_status='1'");
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->count();
        return $row;
    }

    public function getAllActivefriends($userId) {

        $select = $this->tableGateway->getSql()->select();
        $select->join('user', 't_user_groups.friend_id=user.user_id', array('*'), 'left');
        $select->join(array("r1" => 'resource_rack'), 'r1.rack_id=user.board_id',array("board_rack_id"=>'rack_id'), 'left');
        $select->join(array("r2" => 'resource_rack'), 'r2.rack_id=user.class_id',array("class_rack_id"=>'rack_id'), 'left');
        $select->join(array("rc1" => 'rack_name'), 'rc1.rack_name_id=r1.rack_name_id', array('board_name' => 'name'), 'left');
        $select->join(array("rc2" => 'rack_name'), 'rc2.rack_name_id=r2.rack_name_id', array('class_name' => 'name'), 'left');
   $select->where('t_user_groups.user_id="' . $userId . '"');
        //$select->where->OR->equalTo('t_user_groups.friend_id', $userId);
        $select->where('t_user_groups.group_status="1"');
        $row = $this->tableGateway->selectWith($select);
       
        return $row;
    }

    //list ggroup owner of all groups a user belongs to
    public function getgroupowner($userId) {
        $select = $this->tableGateway->getSql()->select();
        $select->where('t_user_groups.group_status="1"');
        $select->where('t_user_groups.user_id IN (' . $userId . ') or t_user_groups.friend_id IN (' . $userId . ')');
        $select->group('t_user_groups.user_id');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    // get all group members of loged user (requested i.e. not admin)
    public function getAllgroupfriends($userId) {
       
        $select = $this->tableGateway->getSql()->select();
        $select->join('user', 't_user_groups.user_id=user.user_id', array('*'), 'left');
        
         $select->join(array("r1" => 'resource_rack'), 'r1.rack_id=user.board_id',array("board_rack_id"=>'rack_id'), 'left');
        $select->join(array("r2" => 'resource_rack'), 'r2.rack_id=user.class_id', array("class_rack_id"=>'rack_id'), 'left');
        $select->join(array("rc1" => 'rack_name'), 'rc1.rack_name_id=r1.rack_name_id', array('board_name' => 'name'), 'left');
        $select->join(array("rc2" => 'rack_name'), 'rc2.rack_name_id=r2.rack_name_id', array('class_name' => 'name'), 'left');

       // $select->join('board_content_view', new Expression('board_content_view.board_id=t_user.board_id And board_content_view.board_class_id=t_user.class_id'), array('board_class_parent_subject_id', 'parent_subject_name', 'subject_name', 'chapter_name', 'board_name', 'class_name'), 'left');
        $select->where('t_user_groups.friend_id="' . $userId . '"');
        $select->where('t_user_groups.group_status="1"');
        $select->group('t_user_groups.group_id');
        $row = $this->tableGateway->selectWith($select);
        return $row;
    }

    // this function is to get deleted request as well as deleted membership of loged user 
    public function countdeletedgroup($id, $type = null, $status = null) {
        $select = $this->tableGateway->getSql()->select();

        if ($type == 'membership') {
            $select->join('user', 'user.user_id=t_user_groups.user_id', array('*'), 'left');
            $select->where("t_user_groups.friend_id='" . $id . "'");
        } if ($type == 'group') {
            $select->join('user', 'user.user_id=t_user_groups.friend_id', array('*'), 'left');
            $select->where("t_user_groups.user_id='" . $id . "'");
        }
        if ($status != '') {
            $select->where("t_user_groups.group_status='" . $status . "'");
        }

        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet;
        return $row;
    }

    public function addgrouprequest($friendid, $userId) {

        $data = array(
            'user_id' => $userId,
            'friend_id' => $friendid,
            'view_group_member_status' => 1,
            'added_date' => date('Y-m-d H:i:s'),
            'group_status' => 0,
        );
        $result = $this->tableGateway->insert($data);
        return $result;
    }
    
    public function getfrienddetailsbyfriendId($friendId) {
        $select = $this->tableGateway->getSql()->select();
        $select->join('user', 't_user_groups.friend_id=user.user_id', array('*'), 'left');
        $select->join(array("r1" => 'resource_rack'), 'r1.rack_id=user.board_id', array("board_rack_id"=>'rack_id'), 'left');
        $select->join(array("r2" => 'resource_rack'), 'r2.rack_id=user.class_id', array("class_rack_id"=>'rack_id'), 'left');
        $select->join(array("rc1" => 'rack_name'), 'rc1.rack_name_id=r1.rack_name_id', array('board_name' => 'name'), 'left');
        $select->join(array("rc2" => 'rack_name'), 'rc2.rack_name_id=r2.rack_name_id', array('class_name' => 'name'), 'left');
        $select->where('t_user_groups.friend_id="' . $friendId . '"');
        $select->where('t_user_groups.group_status!="2"');
        $row = $this->tableGateway->selectWith($select);
        return $row;
    }

}