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

class EmIdolQuizSetTable
{
    protected $tableGateway;
    protected $select;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    	$this->select = new Select();
    }
    
    public function fetchAll($userID=null)
    {
        $select=$this->tableGateway->getSql()->select();
        $select->order('level DESC');
        $select->order('set_id DESC');
        if($userID != '')
        {
            $select->where("user_id=$userID");
        }
        $quizList = $this->tableGateway->selectWith($select);
        return $quizList;
    }
    
    public function getUserLastAttemptedSets($userID, $round, $level)
    {
        $quizSetID = array();
        $select=$this->tableGateway->getSql()->select();
        if(!empty($level))
        $select->where("level in ($level)");
        $select->where("round='$round'");
        $select->order('set_id DESC');
        if($userID != '') {
            $select->where("user_id=$userID");
        }
        $setList = $this->tableGateway->selectWith($select);
        foreach($setList as $set) {
            $quizSetID[] = $set->set_id;
        }
        return $quizSetID;
    }
    
    function saveQuizSet($userId, Array $params) {
        $level = $params['level'];
        $round = $params['round'];
        $mode='website';
        if ($mode == 'website') {
            $totalSetQuestion = 10;
        } else {
            $totalSetQuestion = 10;
        }
        $testduration = 20;
        $dataSet = array();
        $dataSet['user_id'] = $userId;
        $dataSet['level'] = $level;
        $dataSet['round'] = $round;
        $dataSet['total_ques'] = $totalSetQuestion;
        $dataSet['set_time'] = $testduration;
        $dataSet['set_status'] = 'running';
        //echo '<pre>'; print_r($dataSet); exit;
        $setID = $this->tableGateway->insert($dataSet);
        return array('setID' => $setID, 'totalSetQuestion' => $totalSetQuestion);
        //return $setID;
    }
        
}