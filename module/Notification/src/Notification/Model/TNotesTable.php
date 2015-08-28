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
class TNotesTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }

	//public function saveNotes-This function after enter the notes it saved in table 
 public function saveNotes($containerId,$notes,$chapter_id,$userId,$customBoardRackId)
    {		         
         
		$data = array(
			'user_id'  	=> $userId,
			'container_id'  => $containerId,
			'chapter_id'  	=> $chapter_id,
			'note'		=> $notes,
			'added_date'  	=> date('Y-m-d H:i:s'),
			'updated_date'	=> date('Y-m-d H:i:s'),
			'status'	=> 1, 			
			'custom_board_rack_id'	=> $customBoardRackId, 			
		);
		$result=$this->tableGateway->insert($data);
		return $this->tableGateway->lastInsertValue;			        
    }
	
	//public function getNotes-This function get the notes based on userid,board-class-subject-id
	public function getNotes($user_id,$container_id='',$subjectId='')
    {	
		$select = $this->tableGateway->getSql()->select();
		//$select->join('board_content_view', new Expression('board_content_view.service_id=t_notes.container_id AND board_content_view.board_class_subject_chapter_id=t_notes.chapter_id'),array('name' =>new Expression('board_content_view.chapter_name')),'left');
                $select->join(array("r1" => 'resource_rack'),'r1.rack_id=t_notes.chapter_id', array("chapter_rack_id"=>'rack_id'), 'left');
                $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_name_id',  array('chapter_name'=>'name'), 'left');
		//$select->join('main_content', 'main_content.service_id=t_notes.container_id',array("*"),'left');
                $select->join(array("r2" => 'resource_rack'),'r2.rack_id=r1.rack_container_id', array("subject_rack_id"=>'rack_id'), 'left');
                $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_name_id',  array('chapter_rack_name'=>'name'), 'left');
                $select->join(array("tud" => 't_uploads_downloads'),'t_notes.note_id=tud.download_relation_id',  array('pdf_file_name'=>'pdf_file_name','up_down_id'=>'up_down_id','file_name'=>'file_name'), 'left');
                
		$select->where('t_notes.user_id="'.$user_id.'"');
                $select->where('t_notes.status=1');
                $select->where('r2.rack_id ="'.$subjectId.'"');
		//$select->where('(board_class_subject_id="'.$subjectId.'" or board_class_parent_subject_id="'.$subjectId.'")');
		$select->group('t_notes.note_id');
		$resultSet = $this->tableGateway->selectWith($select);
                //echo "<pre />"; print_r($resultSet); exit;
 		return $resultSet;		
	}
	
	//public function gettotalNotes-This function get the all notes based on userid,chapterid,containerid and subjectid
	public function gettotalNotes($user_id,$container_id,$subjectId,$chapterId)
    {	
		$select = $this->tableGateway->getSql()->select();//AND board_content_view.board_class_subject_chapter_id=t_notes.chapter_id
                $select->join(array("r1" => 'resource_rack'),'r1.rack_id=t_notes.chapter_id', array("board_rack_id"=>'rack_id'), 'left');
                $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_name_id',  array('chapter_name'=>'name'), 'left');
		$select->join('main_content', 'main_content.service_id=t_notes.container_id',array("*"),'left');
                $select->join(array("r2" => 'resource_rack'),'r2.rack_id=r1.rack_container_id', array("subject_rack_id"=>'rack_id'), 'left');
                $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_name_id',  array('chapter_rack_name'=>'name'), 'left');
                
		$select->where('t_notes.user_id="'.$user_id.'"');
		$select->where('t_notes.chapter_id="'.$chapterId.'"');
                $select->where('t_notes.status="1"');
                if($container_id !=''){
		  $select->where('t_notes.container_id="'.$container_id.'"');
                 }
                $select->where('r2.rack_id ="'.$subjectId.'"');
		//$select->where('(board_class_subject_id="'.$subjectId.'" or board_class_parent_subject_id="'.$subjectId.'")');
		$select->group('t_notes.note_id');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet->count();		
	}
	
	//public function getNotesPrevAndNext-This function display all notes with next and previous clicks based on userid,chapterid,containerid and subjectid
	public function getNotesPrevAndNext($user_id,$notes,$type=''){
                  
		$select = $this->tableGateway->getSql()->select();
		//$select->join('board_content_view', new Expression('board_content_view.service_id=t_notes.container_id AND board_content_view.board_class_subject_chapter_id=t_notes.chapter_id'),array('name' =>new Expression('board_content_view.chapter_name')),'left');
		$select->join(array("r1" => 'resource_rack'),'r1.rack_id=t_notes.chapter_id', array("chapter_rack_id"=>'rack_id'), 'left');
                $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_name_id',  array('chapter_name'=>'name'), 'left');
                $select->join(array("r2" => 'resource_rack'),'r2.rack_id=r1.rack_container_id', array("subject_rack_id"=>'rack_id'), 'left');
                $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_name_id',  array('chapter_rack_name'=>'name'), 'left');
                $select->join('main_content', 'main_content.service_id=t_notes.container_id',array("service_id"),'left'); 
                 $select->join(array("tud" => 't_uploads_downloads'),new Expression('t_notes.note_id=tud.download_relation_id AND tud.download_for="3"'),array('pdf_file_name'=>'pdf_file_name','up_down_id'=>'up_down_id','file_name'=>'file_name'), 'left');
                if($type ==''){
                      $offset = (int)$notes["value"];
			$select->where('r2.rack_id ="'.$notes['subject_id'].'"');
			$select->where('t_notes.user_id="'.$user_id.'"');
			$select->where('t_notes.chapter_id="'.$notes['chapter_id'].'"');
                        if(!empty($notes['containerId'])){
			  $select->where('t_notes.container_id="'.$notes['containerId'].'"');
                        }
                       // echo "aa==>".$offset;exit;
			 $select->group('t_notes.note_id')->limit(1)->offset($offset);
                         $select->where('t_notes.status="1"');
		}else if($type =='delete'){
			$select->where('r2.rack_id ="'.$notes['subject_id'].'"');
			$select->where('t_notes.user_id="'.$user_id.'"');
                        if(!empty($notes['delChapterId'])){
			$select->where('t_notes.chapter_id="'.$notes['delChapterId'].'"');
                        }
			$select->where('t_notes.container_id="'.$notes['delcontainerId'].'"');
			$select->group('t_notes.note_id')->limit(1)->offset(0);
                        $select->where('t_notes.status="1"'); 
                        
		}else{
			$select->where('r2.rack_id ="'.$notes['subject_id'].'"');
			$select->where('t_notes.user_id="'.$user_id.'"');
			$select->where('t_notes.note_id="'.$notes['notes_id'].'"');
                        $select->where('t_notes.status="1"'); 
		}  
		$resultSet = $this->tableGateway->selectWith($select);
                return $resultSet;	
	}
	
	//public function getAllChapterNotes-This function get all chapter notes based on userId,subjectId,chapterId
	public function getAllChapterNotes($user_id,$notes){
		$select = $this->tableGateway->getSql()->select();
		$select->join('board_content_view', new Expression('board_content_view.service_id=t_notes.container_id AND board_content_view.board_class_subject_chapter_id=t_notes.chapter_id'),array('name' =>new Expression('board_content_view.chapter_name')),'left');
		$select->where('(board_class_subject_id="'.$subjectId.'" or board_class_parent_subject_id="'.$notes['subject_id'].'")');
		$select->where('t_notes.user_id="'.$user_id.'"');
		$select->where('t_notes.chapter_id="'.$notes['chapterId'].'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;	
	}
	
	//public function updateNotes-This function updates notes and updatedate added based on noteId
	public function updateNotes($notes)
    {	
		$data = array(
			'note'					=>	$notes['notes'],
			'updated_date'			=> 	date('Y-m-d H:i:s'),	
		);
		$result=$this->tableGateway->update($data, array('note_id' => $notes['note_id']));
		
		$select = $this->tableGateway->getSql()->select();
		$select->where('note_id="'.$notes['note_id'].'"');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
    }
	
	//public function deleteNotes-This function deletes the notes based on noteId
	public function deleteNotes($notesIds)
    {
		if(isset($notesIds['delNoteid']) && $notesIds['delNoteid']!=''){
                    $data = array('status'=>'0');
			//$this->tableGateway->delete(array('note_id'=>$notesIds['delNoteid']));			
                    $this->tableGateway->update($data,array('note_id'=>$notesIds['delNoteid']));			
		}else{
		$data = array('status'=>'0');	
                    //$this->tableGateway->delete(array('container_id'=>$notesIds['delcontainerId'],'chapter_id'=>$notesIds['delChapterId']));			
                $this->tableGateway->update($data,array('container_id'=>$notesIds['delcontainerId'],'chapter_id'=>$notesIds['delChapterId']));			
		}
		return $this->tableGateway->lastInsertValue;		
	}
	
	
	public function getchapterNotes(){
		$select = $this->tableGateway->getSql()->select();
	}
}