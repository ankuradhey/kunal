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
class UserQuestionTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }	
	
	//public function savecomment-This function get save the comments(give comment on question)based on userId
	public function savecomment($comment,$userId,$postdata=NULL)
        {	
            
            
            $data = array(
			'user_id'  	    =>  $userId,
			'board_id'  	    =>  (isset($comment['boardid'])) ? $comment['boardid']: '',
			'class_id'  	    =>  (isset($comment['classid'])) ? $comment['classid']: '',
			'subject_id'	    =>  (isset($comment['subjectId']))?$comment['subjectId']:'',
			'chapter_id'	    =>  (isset($comment['chapter_id']))?$comment['chapter_id']:'',
			'question'	    =>  ($postdata !='')?$postdata:$comment['commenttext'],
                        'question_asked_to' =>  (isset($comment['questionaskedto']))? $comment['questionaskedto'] :'group',
                        'group_owner_id'    =>  $comment['groupownerId']?$comment['groupownerId']:$userId,
			'added_date'        => 	date('Y-m-d H:i:s'),			
			'c_status'	    =>	0, 			
		);
                
                $result=$this->tableGateway->insert($data);
                return $this->tableGateway->lastInsertValue;			        
      }
     
	//public function getComments-This function get comments on each question by desending order base on userId,chapterid
	public function getComments($friendids,$chapter_id,$type,$currentPage,$resultsPerPage,$userId)
	{

            $sql = $this->tableGateway->getSql();		
            $select = $this->tableGateway->getSql()->select();
             $select->columns(array('question_id' => 'question_id','user_id'=>'user_id','subject_id'=>'subject_id','chapter_id'=>'chapter_id','question'=>'question','question_asked_to'=>'question_asked_to','c_status'=>'c_status','group_owner_id'=>'group_owner_id'));
            $select->join('user', 't_question.user_id=user.user_id',array('userid'=>'user_id','first_name'=>'display_name','user_photo'=>'user_photo','school_name'=>'school_name','email','phone'),'left');		
            $select->join('t_user_type', 'user.user_type_id=t_user_type.user_type_id',array('*'),'left');
            $select->join(array("Tuser"=>'user'), 'Tuser.user_id=t_question.deleted_by',array('deleted_user'=>'display_name'),'left');

            $select->join(array("r1" => 'resource_rack'),'r1.rack_id=user.board_id',     array('board_id'=>'rack_id'), 'left');
            $select->join(array("r2" => 'resource_rack'),'r2.rack_id=user.class_id',     array('class_id'=>'rack_id'), 'left');
            $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_name_id', array('board_name'=>'name'), 'left');
            $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_name_id', array('class_name'=>'name'), 'left');
            $select->join('t_reply_on_question', 't_question.question_id=t_reply_on_question.reply_on_question',array('countReplys' =>new Expression('COUNT(distinct t_reply_on_question.reply_id)')),'left');
            $select->where('t_question.user_id IN ('.$userId.','.$friendids .')');
                
		if($type=='group'){
			//$select->where('t_question.subject_id IN ('.$chapter_id.')');
			$select->group('t_question.question_id');
			$select->order("t_question.question_id DESC")->limit($resultsPerPage)->offset(($currentPage-1)*$resultsPerPage);
			//echo $sql->getSqlstringForSqlObject($select); die ;
                        $resultSet = $this->tableGateway->selectWith($select);
			return $resultSet;
		} else {
                        $select->where('t_question.chapter_id IN ('.$chapter_id.')');
			$select->group('t_question.question_id');
			$select->order("t_question.question_id DESC")->limit($resultsPerPage)->offset(($currentPage-1)*$resultsPerPage);
			$resultSet = $this->tableGateway->selectWith($select);
			//echo $sql->getSqlstringForSqlObject($select); die ;
                        return $resultSet;
		}		
	}
	
	//public function countComments-This function counts the comments on questions based on chapterid
	public function countComments($friendids,$chapter_id,$type,$userId)
	{		
		$sql = $this->tableGateway->getSql();		
		$select = $this->tableGateway->getSql()->select();
		$select	->join('user', 't_question.user_id=user.user_id',array('userid'=>'user_id','first_name'=>'display_name','user_photo'=>'user_photo','school_name'=>'school_name'),'left');		
		$select	->join('t_user_type', 'user.user_type_id=t_user_type.user_type_id',array('*'),'left');
		$select	->join(array("Tuser"=>'user'), 'user.user_id=t_question.deleted_by',array('deleted_user'=>'display_name'),'left');
                
                $select->join(array("r1" => 'resource_rack'),'r1.rack_id=user.board_id',  array('board_rack_id'=>'rack_id'), 'left');
                $select->join(array("r2" => 'resource_rack'),'r2.rack_id=user.class_id',  array('class_rack_id'=>'rack_id'), 'left');
                $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_id',  array('board_name'=>'name'), 'left');
                $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_id',  array('class_id'=>'name'), 'left');
    
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
        
        
        public function countgroupComments($friendids,$chapter_id,$type,$userId)
	{		
            $sql = $this->tableGateway->getSql();		
            $select = $this->tableGateway->getSql()->select();
            $select->join('user', 't_question.user_id=user.user_id',array('userid'=>'user_id'),'left');		
            //$select->join('t_user_type', 'user.user_type_id=t_user_type.user_type_id',array('user_type_id'),'left');
           // $select->join(array("Tuser"=>'user'), 'user.user_id=t_question.deleted_by',array('deleted_user'=>'display_name'),'left');

//            $select->join(array("r1" => 'resource_rack'),'r1.rack_id=user.board_id',  array('board_rack_id'=>'rack_id'), 'left');
//            $select->join(array("r2" => 'resource_rack'),'r2.rack_id=user.class_id',  array('class_rak_id'=>'rack_id'), 'left');
//            $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_id',  array('board_name'=>'name'), 'left');
//            $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_id',  array('class_name'=>'name'), 'left');

 //           $select->join('t_reply_on_question', 't_question.question_id=t_reply_on_question.reply_on_question',array('countReplys' =>new Expression('COUNT(distinct t_reply_on_question.reply_id)')),'left');
            $select->where('t_question.user_id IN ('.$userId.','.$friendids .')');
                
            if($type=='group'){	
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
		$select	->join('user', 't_question.user_id=user.user_id',array('userid'=>'user_id','first_name'=>'display_name','user_photo'=>'user_photo','school_name'=>'school_name','email_id'=>'email','mobile'=>'phone','address'),'left');		
		$select	->join('t_user_type', 'user.user_type_id=t_user_type.user_type_id',array('*'),'left');
		$select	->join(array("Tuser"=>'user'), 'Tuser.user_id=t_question.deleted_by',array('deleted_user'=>'display_name'),'left');
		
        $select->join(array("r1" => 'resource_rack'),'r1.rack_id=user.board_id',  array("board_rack_id"=>'rack_id'), 'left');
        $select->join(array("r2" => 'resource_rack'),'r2.rack_id=user.class_id',  array("class_rack_id"=>'rack_id'), 'left');
        $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_name_id',  array('board_name'=>'name'), 'left');
        $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_name_id',  array('class_name'=>'name'), 'left');
     	$select->join('t_reply_on_question', 't_question.question_id=t_reply_on_question.reply_on_question',array('countReplys' =>new Expression('COUNT(distinct t_reply_on_question.reply_id)')),'left');
	$select->join(array("tud" =>'t_uploads_downloads'),new Expression('t_question.question_id=tud.download_relation_id AND tud.download_for="1"'),array('pdf_file_name'=>'pdf_file_name','up_down_id'=>'up_down_id','file_name'=>'file_name'), 'left');	
        
        $select->where('t_question.user_id IN ('.$friendids .')');
		if($uid!=''){
			$select->where('t_question.user_id IN ('.$uid.')');
		}
		if($bid!=''){
			$select->where('t_question.board_id IN ('.$bid.')');
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
                $currentPage    = (int)$currentPage;
                $resultsPerPage = (int)$resultsPerPage;
		$select->group('t_question.question_id');
		$select->order("t_question.question_id DESC")->limit($resultsPerPage)->offset(($currentPage-1)*$resultsPerPage);
		$resultSet = $this->tableGateway->selectWith($select);
                return $resultSet;	
	}
	
	
	//public function totalCount-This function counts the total questions by descending order based on userid,subjectid,chapterid,classid and boardid
	public function totalCount($friendids,$uid='',$bid='',$cid='',$sid='',$chid='',$currentPage='',$resultsPerPage='',$groupownerId='',$questionaskedto='')
	 {	
			
		$select = $this->tableGateway->getSql()->select();
		$select	->join('user', 't_question.user_id=user.user_id',array('userid'=>'user_id','first_name'=>'display_name','user_photo'=>'user_photo','school_name'=>'school_name'),'left');		
		$select	->join('t_user_type', 'user.user_type_id=t_user_type.user_type_id',array('*'),'left');
		$select	->join(array("Tuser"=>'user'), 'Tuser.user_id=t_question.deleted_by',array('deleted_user'=>'display_name'),'left');
		
                $select->join(array("r1" => 'resource_rack'),'r1.rack_id=user.board_id',  array('rack_board_id'=>'rack_id'), 'left');
                $select->join(array("r2" => 'resource_rack'),'r2.rack_id=user.class_id',  array('rack_class_id'=>'rack_id'), 'left');
                $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_id',  array('board_name'=>'rack_name_id'), 'left');
                $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_id',  array('class_name'=>'rack_name_id'), 'left');
     
		$select->join('t_reply_on_question', 't_question.question_id=t_reply_on_question.reply_on_question',array('countReplys' =>new Expression('COUNT(distinct t_reply_on_question.reply_id)')),'left');
		$select->where('t_question.user_id IN ('.$friendids .')');
		if($uid!=''){
			$select->where('t_question.user_id IN ('.$uid.')');
		}
		if($bid!=''){
			$select->where('t_question.board_id IN ('.$bid.')');
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
               
		return $resultSet->count();	
	}
        
       public function updateStatus($ids,$data){ 
              
		$row=$this->tableGateway->update($data, array('question_id' => $ids));
                return $row;	
	}
        
}


