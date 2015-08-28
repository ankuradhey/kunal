<?php
namespace Notification\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
class TquestionTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }	
	
	//public function savecomment-This function get save the comments(give comment on question)based on userId
	public function savecomment($comment,$userId)
        {	
            
         if(isset($userId)){
			$userId=$userId;
		}else{
			$userId='';
		}	
		$data = array(
			'user_id'  	=> 	$userId,
			'board_id'  	=>      $comment['boardid'] ? $comment['boardid']: '',
			'class_id'  	=> 	$comment['classid'] ? $comment['classid']: '',
			'subject_id'	=>	$comment['subjectId']?$comment['subjectId']:'',
			'chapter_id'	=>	$comment['chapter_id']?$comment['chapter_id']:'',
			'question'	 =>	$comment['commenttext']?$comment['commenttext']:'',
                        'question_asked_to'  => $comment['questionaskedto']? $comment['questionaskedto'] :'group',
                        'group_owner_id' =>     $comment['groupownerId']?$comment['groupownerId']:$userId,
			'added_date'  	=> 	date('Y-m-d H:i:s'),			
                        'custom_board_rack_id' => $comment['customBoardRackID'],			
			'c_status'	 =>	0, 			
		);
                
		$result=$this->tableGateway->insert($data);
                return $this->tableGateway->lastInsertValue;			        
      }
     
	//public function getComments-This function get comments on each question by desending order base on userId,chapterid
	public function getComments($friendids,$chapter_id,$type,$currentPage,$resultsPerPage,$userId)
	{		
		if(isset($userId)){
			$userId = $userId;
		}else{
			$userId='';
		}		
		$select = $this->tableGateway->getSql()->select();
		$select	->join('user', 't_question.user_id=user.user_id',array('userid'=>'user_id','first_name'=>'display_name','user_photo'=>'user_photo','school_name'=>'school_name','email_id'=>'email','mobile'=>'phone','address'),'left');		
		 $select	->join('t_user_type', 'user.user_type_id=t_user_type.user_type_id',array('*'),'left');
		$select	->join(array("Tuser"=>'user'), 'Tuser.user_id=t_question.deleted_by',array('deleted_user'=>'display_name'),'left');
               
		$select	->join(array("rackboard"=>'resource_rack'), 'user.board_id=rackboard.rack_id',array('boardid'=>'rack_id'),'left');
		$select	->join(array("rackboardname"=>'rack_name'), 'rackboardname.rack_name_id=rackboard.rack_name_id',array('boardname' => new Expression('rackboardname.name')),'left');
		$select	->join(array("rackclass"=>'resource_rack'), 'user.class_id=rackclass.rack_id',array('classid'=>'rack_id'),'left');
		$select	->join(array("rackclassname"=>'rack_name'), 'rackclassname.rack_name_id=rackclass.rack_name_id',array('classname' => new Expression('rackclassname.name')),'left');
		  /*$select->join(array("subjectname"=>'board_class_subject_chapters'),'subjectname.board_class_subject_id=t_question.subject_id',array('board_class_subject_id'=>'board_class_subject_id','subject_name'=>'subject_name'),'left');
		$select->join(array("chaptername"=>'board_class_subject_chapters'),'chaptername.board_class_subject_chapter_id=t_question.chapter_id',array('board_class_subject_chapter_id'=>'board_class_subject_chapter_id','chapter_name'=>'chapter_name'),'left');
		*/
		//$select	->join('board_content_view', 'board_content_view.board_class_subject_chapter_id=t_question.chapter_id',array('*'),'left');
		
		//$select->join('t_likes', new Expression('t_question.comment_id=t_likes.comment_id AND t_likes.user_id="'.$_SESSION['user']['userId'].'"'),array('userLike' =>new Expression('t_likes.like_on')),'left');
		$select->join('t_reply_on_question', 't_question.question_id=t_reply_on_question.reply_on_question',array('countReplys' =>new Expression('COUNT(distinct t_reply_on_question.reply_id)')),'left');
		$select->join(array("tud" =>'t_uploads_downloads'),new Expression('t_question.question_id=tud.download_relation_id AND tud.download_for="1"'),array('pdf_file_name'=>'pdf_file_name','up_down_id'=>'up_down_id','file_name'=>'file_name'), 'left');
                $select->where('t_question.user_id IN ('.$userId.','.$friendids .')');		
		if($type=='group'){
			//$select->where('t_question.subject_id IN ('.$chapter_id.')');
			$select->group('t_question.question_id');
			$select->order("t_question.question_id DESC")->limit($resultsPerPage)->offset(($currentPage-1)*$resultsPerPage);
			$resultSet = $this->tableGateway->selectWith($select);
			return $resultSet;
		}else{
			$select->where('t_question.chapter_id IN ('.$chapter_id.')');
			$select->group('t_question.question_id');
			$select->order("t_question.question_id DESC")->limit($resultsPerPage)->offset(($currentPage-1)*$resultsPerPage);
			$resultSet = $this->tableGateway->selectWith($select);
			return $resultSet;
		}		
	}
	
	//public function countComments-This function counts the comments on questions based on chapterid
	public function countComments($friendids,$chapter_id,$type,$userId)
	{		
		if(isset($userId)){
			$userId=$userId;
		}else{
			$userId='';
		}		
		$select = $this->tableGateway->getSql()->select();
		$select	->join('user', 't_question.user_id=user.user_id',array('userid'=>'user_id','first_name'=>'display_name','user_photo'=>'user_photo','school_name'=>'school_name'),'left');		
		$select	->join('t_user_type', 'user.user_type_id=t_user_type.user_type_id',array('*'),'left');
		$select	->join(array("Tuser"=>'user'), 'Tuser.user_id=t_question.deleted_by',array('deleted_user'=>'display_name'),'left');
                /*
		$select	->join(array("rackboard"=>'resource_rack'), 't_user.board_id=rackboard.rack_id',array('boardid'=>'rack_id'),'left');
		$select	->join(array("rackboardname"=>'rack_name'), 'rackboardname.rack_name_id=rackboard.rack_name_id',array('board_name' => new Expression('rackboardname.name')),'left');
		$select	->join(array("rackclass"=>'resource_rack'), 't_user.class_id=rackclass.rack_id',array('classid'=>'rack_id'),'left');
		$select	->join(array("rackclassname"=>'rack_name'), 'rackclassname.rack_name_id=rackclass.rack_name_id',array('class_name' => new Expression('rackclassname.name')),'left');
		$select ->join(array("subjectname"=>'board_class_subject_chapters'),'subjectname.board_class_subject_id=t_question.subject_id',array('board_class_subject_id'=>'board_class_subject_id','subject_name'=>'subject_name'),'left');
		$select ->join(array("chaptername"=>'board_class_subject_chapters'),'chaptername.board_class_subject_chapter_id=t_question.chapter_id',array('board_class_subject_chapter_id'=>'board_class_subject_chapter_id','chapter_name'=>'chapter_name'),'left');
		*/
                
            $select->join(array('r1' => 'resource_rack'), 'r1.rack_id=t_question.chapter_id', array("subject_id"=>'rack_id'), 'left');
            $select->join(array('rn1' => 'rack_name'), 'rn1.rack_name_id=r1.rack_name_id', array('subject_name' => 'name'), 'left');
            $select->join(array('r2' => 'resource_rack'), 'r2.rack_id=r1.rack_container_id', array("class_ids"=>"rack_id"), 'left');
            $select->join(array('rn2' => 'rack_name'), 'rn2.rack_name_id=r2.rack_name_id', array('class_name' => 'name'), 'left');
            $select->join(array('r3' => 'resource_rack'), 'r3.rack_id=r2.rack_container_id', array("board_ids"=>"rack_id"), 'left');
            $select->join(array('rn3' => 'rack_name'), 'rn3.rack_name_id=r3.rack_name_id', array('board_name' => 'name'), 'left');
                
                
                
		//$select	->join('board_content_view', 'board_content_view.board_class_subject_chapter_id=t_question.chapter_id',array('*'),'left');
		$select->join('t_reply_on_question', 't_question.question_id=t_reply_on_question.reply_on_question',array('countReplys' =>new Expression('COUNT(distinct t_reply_on_question.reply_id)')),'left');
		$select->where('t_question.user_id IN ('.$userId.','.$friendids .')');		
		if($type=='group'){
			//$select->where('t_question.subject_id IN ('.$chapter_id.')');
			$select->group('t_question.question_id');
			$select->order("t_question.question_id DESC");
			$resultSet = $this->tableGateway->selectWith($select);
			return $resultSet->count();
		}else{
			$select->where('t_question.chapter_id IN ('.$chapter_id.')');
			$select->group('t_question.question_id');
			$select->order("t_question.question_id DESC");
			$resultSet = $this->tableGateway->selectWith($select);
			return $resultSet->count();
		}		
	}
	
	//public function getQuestions-This function get questions from question table based on userid,subjectid,chapterid,boardid and classid
	public function getQestions($friendids,$uid='',$bid='',$cid='',$sid='',$chid='',$currentPage='',$resultsPerPage='',$groupownerId='',$questionaskedto='')
	{			
		$select = $this->tableGateway->getSql()->select();
		$select	->join('user', 't_question.user_id=user.user_id',array('userid'=>'user_id','first_name'=>'display_name','user_photo'=>'user_photo','school_name'=>'school_name','email','phone','address'),'left');		
		$select	->join('t_user_type', 'user.user_type_id=t_user_type.user_type_id',array('*'),'left');
		$select	->join(array("Tuser"=>'user'), 'Tuser.user_id=t_question.deleted_by',array('deleted_user'=>'display_name'),'left');
		
                $select->join(array("cbr" => 'custom_board_rack'), 'user.custom_board_rack_id=cbr.custom_board_rack_id', array('boardid' => 'custom_board_id'), 'left');
                $select->join(array("cb" => 'custom_board'), 'cbr.custom_board_id=cb.custom_board_id', array('boardname' => 'board_name'), 'left');
                $select->join(array("rackclass" => 'resource_rack'), 'user.class_id=rackclass.rack_id', array('classid' => 'rack_id'), 'left');
                $select->join(array("rackclassname" => 'rack_name'), 'rackclassname.rack_name_id=rackclass.rack_name_id', array('classname' => new Expression('rackclassname.name')), 'left');
                $select->join(array("r3"=>'resource_rack'), 'r3.rack_id= t_question.chapter_id',array('chapter_id'=>'rack_id'),'left');
		$select->join(array("rackchept"=>'rack_name'), 'rackchept.rack_name_id=r3.rack_name_id',array('chapter_name' => new Expression('rackchept.name')),'left');
          
                $select->join(array("tud" =>'t_uploads_downloads'),new Expression('t_question.question_id=tud.download_relation_id AND tud.download_for="1"'),array('pdf_file_name'=>'pdf_file_name','up_down_id'=>'up_down_id','file_name'=>'file_name'), 'left');
                $select->join('t_reply_on_question', 't_question.question_id=t_reply_on_question.reply_on_question',array('countReplys' =>new Expression('COUNT(distinct t_reply_on_question.reply_id)')),'left');
		
                $select->where('t_question.user_id IN ('.$friendids .')');
		if($uid!=''){
			$select->where('t_question.user_id IN ('.$uid.')');
		}
		if($bid!=''){
                        $select->join(array("cbr1"=>'custom_board_rack'),new Expression('cbr1.custom_board_rack_id=t_question.custom_board_rack_id AND cbr1.custom_board_id='.$bid.''),array('*'),'inner');
		}
		if($cid!=''){
			$select->where('t_question.class_id IN ('.$cid.')');
		}
		if($sid!=''){
			$select->where('t_question.subject_id IN ('.$sid.')');
		}
		if($chid!=''){
			$select->where('t_question.chapter_id IN ('.$chid.')');
		} 
                if($groupownerId!=''){
                    $select->where('t_question.group_owner_id IN ('.$groupownerId.')');
                }
                if($questionaskedto!=''){
                     $select->where('t_question.question_asked_to ="'.$questionaskedto.'"');
                }else{
                    $select->where('t_question.question_asked_to ="group"');
                }
		$select->group('t_question.question_id');
		$select->order("t_question.question_id DESC")->limit((int) $resultsPerPage)->offset(((int) $currentPage-1)*(int) $resultsPerPage);
                $resultSet = $this->tableGateway->selectWith($select);
                //echo '<pre>';print_r($resultSet);echo '</pre>';die('Macro');
                
		return $resultSet;	
	}
	
	
	//public function totalCount-This function counts the total questions by descending order based on userid,subjectid,chapterid,classid and boardid
	public function totalCount($friendids,$uid='',$bid='',$cid='',$sid='',$chid='',$currentPage='',$resultsPerPage='',$groupownerId='',$questionaskedto='')
	 {	
			
		$select = $this->tableGateway->getSql()->select();
		$select	->join('user', 't_question.user_id=user.user_id',array('userid'=>'user_id','first_name'=>'display_name','user_photo'=>'user_photo','school_name'=>'school_name'),'left');		
		$select	->join('t_user_type', 'user.user_type_id=t_user_type.user_type_id',array('*'),'left');
		$select	->join(array("Tuser"=>'user'), 'Tuser.user_id=t_question.deleted_by',array('deleted_user'=>'display_name'),'left');
		
                
		$select	->join(array("rackboard"=>'resource_rack'), 'user.board_id=rackboard.rack_id',array('boardid'=>'rack_id'),'left');
		$select	->join(array("rackboardname"=>'rack_name'), 'rackboardname.rack_name_id=rackboard.rack_name_id',array('board_name' => new Expression('rackboardname.name')),'left');
		$select	->join(array("rackclass"=>'resource_rack'), 'user.class_id=rackclass.rack_id',array('classid'=>'rack_id'),'left');
		$select	->join(array("rackclassname"=>'rack_name'), 'rackclassname.rack_name_id=rackclass.rack_name_id',array('class_name' => new Expression('rackclassname.name')),'left');
                
                $select	->join(array("r3"=>'resource_rack'), 'r3.rack_id= t_question.chapter_id',array('chapter_id'=>'rack_id'),'left');
		$select	->join(array("rackchept"=>'rack_name'), 'rackchept.rack_name_id=r3.rack_name_id',array('chapter_name' => new Expression('rackclassname.name')),'left');
                
		$select->join('t_reply_on_question', 't_question.question_id=t_reply_on_question.reply_on_question',array('countReplys' =>new Expression('COUNT(distinct t_reply_on_question.reply_id)')),'left');
		$select->where('t_question.user_id IN ('.$friendids .')');
		if($uid!=''){
			$select->where('t_question.user_id IN ('.$uid.')');
		}
		if($bid!=''){
			$select->join(array("cbr"=>'custom_board_rack'),new Expression('cbr.custom_board_rack_id=t_question.custom_board_rack_id AND cbr.custom_board_id='.$bid.''),array('*'),'inner');
		}
		if($cid!=''){
			$select->where('t_question.class_id IN ('.$cid.')');
		}
		if($sid!=''){
			$select->where('t_question.subject_id IN ('.$sid.')');
		}
		if($chid!=''){
			$select->where('t_question.chapter_id IN ('.$chid.')');
		}
                if($groupownerId!=''){
                    $select->where('t_question.group_owner_id IN ('.$groupownerId.')');
                }
                if($questionaskedto!=''){
                     $select->where('t_question.question_asked_to ="'.$questionaskedto.'"');
                }else{
                    $select->where('t_question.question_asked_to ="group"');
                }
		$select->group('t_question.question_id');
		$select->order("t_question.question_id DESC");
		$resultSet = $this->tableGateway->selectWith($select);
               
              // echo '<pre>';print_r($resultSet);echo '</pre>';die('Macro'); 
		return $resultSet->count();	
	}
        
       public function updateStatus($ids,$data){ 
              
		$row=$this->tableGateway->update($data, array('question_id' => $ids));
                return $row;	
	}
        
}


