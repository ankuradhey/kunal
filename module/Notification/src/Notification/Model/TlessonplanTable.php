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
class TlessonplanTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
	$this->select = new Select();
    }	
    
    public function getLessons($value,$type,$id='',$custom='')
 {	
    //$date=date('Y-m-d');
    $select = $this->tableGateway->getSql()->select();
    $select->join(array("r2" => 'resource_rack'),'r2.rack_id=t_lesson_plan.package_usage_id',array("board_class_subject_chapter_id"=>'rack_id'), 'left');
    $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r2.rack_name_id',  array('chapter_name'=>'name'), 'left');
    $select->join(array("r3" => 'resource_rack'),'r3.rack_id= r2.rack_container_id',  array("board_class_subject_id"=>'rack_id'), 'left');
    $select->join(array("rc3" => 'rack_name'),'rc3.rack_name_id=r3.rack_name_id',  array('subject_name'=>'name'), 'left');
    $select->join(array("r4" => 'resource_rack'),'r4.rack_id= r3.rack_container_id',  array("class_id"=>'rack_id'), 'left');
    $select->join('resource_rack', new Expression('resource_rack.rack_id=r4.rack_container_id AND r3.rack_type_id="7"'),array("alt_class_id"=>'rack_id'),'left');

    $select->where('t_lesson_plan.user_id="'.$id.'"');
    $select->where('t_lesson_plan.status="1"');
    if($value=='0') {
        $select->where('t_lesson_plan.is_completed="0"');
        //$select->where('t_lesson_plan.plan_date<"'.$date.'"');
        $select->where('DATE(t_lesson_plan.plan_date) < DATE(NOW())');
    }else if($value=='2') {
        $select->where('DATE(t_lesson_plan.plan_date) > DATE(NOW())');
    }else{
        if($type=='progress'){
            $select->where('DATE(t_lesson_plan.plan_date) = DATE(NOW())');
            //$select->where('t_lesson_plan.plan_date="'.$date.'"');
        }
    }
    if($custom == 'cplan')
     {
          $select->where('type!="custom"');
     }
            //$select->where('type!="noType"');
        $select->group('plan_id');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
        
    }
	
	//public function addLessons-This function adds lessons only subscribed lesson added(chapters are schedule date wise so that details(lesson) are stored in db )
 public function addLessons($event,$fDate,$eDate,$lessonName,$type,$tabType,$id='',$isCompleted='',$userid,$post_user_id,$assign_type,$uuid,$customboard=NULL)
 {
        if($eDate==0){
                $endDate=$fDate;
        }else{
                $endDate=$eDate;
        }
        if($isCompleted==''){
                $completed='0';
        }else{
                $completed=$isCompleted;
        }
        $data = array(
                'created_on'	    => date('Y-m-d H:i:s'),
                'is_completed'	    => $completed,			
                'is_reminded'	    => '0',
                'is_started'	    => '0',
                'plan_date'	    => $fDate,
                'end_date'	    => $endDate,
                'node_id'	    => '0',
                'user_id'	    => $userid,
                'package_usage_id'  => $event,
                'lesson_name'	    => $lessonName,
                'type'		    => $tabType,
                'assign_user_id'    => $post_user_id,
                'assign_type'      => $assign_type, 
                'uuid'      =>$uuid,
            'custom_board_rack_id'=>$customboard,
        );
        if($type=='insert'){
                $row=$this->tableGateway->insert($data);
        }else{
                $row=$this->tableGateway->update($data, array('plan_id' => $id));
        }

        $value = (!empty($id)) ? $id:$this->tableGateway->lastInsertValue;
        $select = $this->tableGateway->getSql()->select();
        $select->where('plan_id="'.$value.'"');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet->current();	
    }
    
    public function getlessonById($value)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where('plan_id="'.$value.'"');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet->current();
    }
    
   public function updatelessons($type,$data,$lession_id) 
   {
      if($type=='update'){ 
        $row=$this->tableGateway->update($data, array('plan_id' => $lession_id));
        $select = $this->tableGateway->getSql()->select();
        $select->where('plan_id="'.$lession_id.'"');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet->current();
       
      } 
   }	
	//public function checkLessons-This function checks lesson available or not based on packageUsageId,type,planDate,endDate,userId
    public function checkLessons($event,$fDate,$eDate,$type,$userid,$assignuserId){
		if($eDate==0){
			$endDate=$fDate;
		}else{
			$endDate=$eDate;
		}
		$select = $this->tableGateway->getSql()->select();
		$select->where('package_usage_id="'.$event.'"');
		$select->where('type="'.$type.'"');
		$select->where('plan_date <= "'.$fDate.'"');
		$select->where('end_date >= "'.$endDate.'"');
		$select->where('user_id="'.$userid.'"');
                $select->where('status != "5" ');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	//public function checkLessonsDrag-This function drag lessons one date to other date checks lesson available or not based on packageUsageId,type,planDate,endDate,userId
	public function checkLessonsDrag($event,$fDate,$eDate,$type,$userId){
		if($eDate==0){
			$endDate=$fDate;
		}else{
			$endDate=$eDate;
		}
		$select = $this->tableGateway->getSql()->select();
		$select->where('package_usage_id="'.$event.'"');
		$select->where('type="'.$type.'"');
		$select->where('plan_date > "'.$fDate.'"');
		$select->where('plan_date <= "'.$endDate.'"');
		$select->where('user_id="'.$userId.'"');
                 $select->where('status="1"');
		$resultSet = $this->tableGateway->selectWith($select);
		//echo '<pre>'; print_r($resultSet); exit;
		return $resultSet;
	}
	
	//public function checkStatus-This function check lesson status based on planId 
	public function checkStatus($pid){
		$select = $this->tableGateway->getSql()->select();
		$select->where('plan_id="'.$pid.'"');
                $select->where('status="1"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	//public function updateLesson-This function update lesson means drag lesson one date to other dates lesson updated
	public function updateLesson($event,$userId)
    {
		if($event['type']=='1'){
			$data = array(
				'is_completed'		=> 	'1',			
			);
		}else if($event['type']=='eventDrag'){
			if($event['endDate']==0){
				$endDate=$event['fdate'];
			}else{
				$endDate=$event['endDate'];
			}
			$data = array(
				'plan_date'		=> 	$event['fdate'],
				'end_date'		=> 	$endDate,				
			);
		}
		$result=$this->tableGateway->update($data, array('plan_id' => $event['lessionId']));
		return $result;
    }
	
	//public function updateLessonProgress-This function update lesson progress based on planId
	public function updateLessonProgress($fdate,$endDate,$lessionId)
    {
		$data = array(
			'plan_date'		=> 	$fdate,
			'end_date'		=> 	$endDate,				
		);
		$result=$this->tableGateway->update($data, array('plan_id' => $lessionId));
		return $result;
    }
	
	//public function deleteLesson-This function deletes the lesson from schedule based on planId
	public function deleteLesson($lesson,$status)
    {
            $data = array('status'=>$status);
            $this->tableGateway->update($data,array('plan_id'=>$lesson['lessionId']));			
		return $this->tableGateway->lastInsertValue;		
	}
	
	//public function getReportSubjects-This function select the board and chapter get report from lesson based on userId and planDate
	public function getReportSubjects($calReportType,$boardId,$classId,$subjectId)
    {	
		$fromDate=date('Y-m-d');
		$endDate=date('Y-m-d', strtotime("+30 days"));
		$select = $this->tableGateway->getSql()->select();
		if($subjectId==''){
		$select->join('board_content_view', 'board_content_view.board_class_subject_chapter_id=t_lesson_plan.package_usage_id',array('total' => new Expression('COUNT(package_usage_id)'),'subject_name'=>new Expression('board_content_view.subject_name'),'board_class_subject_id'=>new Expression('board_content_view.board_class_subject_id')),'left');
		}else{
		$select->join('board_content_view', 'board_content_view.board_class_subject_chapter_id=t_lesson_plan.package_usage_id',array('total' => new Expression('COUNT(board_content_view.board_class_subject_chapter_id)'),'chapter_name'=>new Expression('board_content_view.chapter_name')),'left');
		}
		if($subjectId!=''){
		$select->where('board_content_view.board_class_subject_id="'.$subjectId.'"');
		}
		$select->where('board_content_view.board_id="'.$boardId.'"');
		$select->where('board_content_view.board_class_id="'.$classId.'"');
		$select->where('user_id="'.$_SESSION['user']['userId'].'"');
		$select->where('plan_date<="'.$endDate.'"');
		$select->where('plan_date>="'.$fromDate.'"');
		if($subjectId==''){
		$select->group(array('board_class_subject_id','plan_date'));
		}else{
		$select->group(array('board_class_subject_id','plan_date'));
		}
		$resultSet = $this->tableGateway->selectWith($select);
		//echo '<pre>'; print_r($resultSet); exit;
		return $resultSet;		
	}
	
	//public function getTotalSessions-This function get 30 days schedule lesson based on userId,planDate
	public function getTotalSessions($boardId,$classId){
		$fromDate=date('Y-m-d');
		$endDate=date('Y-m-d', strtotime("+30 days"));
		$select = $this->tableGateway->getSql()->select();
		$select->join('board_content_view', 'board_content_view.board_class_subject_chapter_id=t_lesson_plan.package_usage_id',array('total' => new Expression('COUNT(package_usage_id)')),'left');
		$select->where('board_content_view.board_id="'.$boardId.'"');
		$select->where('board_content_view.board_class_id="'.$classId.'"');
		$select->where('user_id="'.$_SESSION['user']['userId'].'"');
		$select->where('plan_date<="'.$endDate.'"');
		$select->where('plan_date>="'.$fromDate.'"');
		$select->group('plan_date');
		$resultSet = $this->tableGateway->selectWith($select);
		//echo '<pre>'; print_r($resultSet); exit;
		return $resultSet;	
	}
        
        // public function to get the chapter list via user_id
        
         public function getChapterlistByUserId($user_id,$completed=NULL){
            $select = $this->tableGateway->getSql()->select();
            //$select->join('board_content_view', 'board_content_view.board_class_subject_chapter_id=t_lesson_plan.package_usage_id',array('subject_name'),'left');
            $select->where('user_id="'.$user_id.'"');
            $select->where('t_lesson_plan.status="1"');
           if($completed =='1'){
                 $select->where('t_lesson_plan.is_completed IN (0,1) ');
            }else{
                $select->where('t_lesson_plan.is_completed="0"');
            }
           // $select->group('board_content_view.board_class_subject_id');
            $resultSet = $this->tableGateway->selectWith($select);
            return $resultSet;
        }
}