<?php
namespace Notification\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;
use Zend\Paginator\Paginator;
class TfreechapterTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	
	
	public function addfreechapter($data){
			$result=$this->tableGateway->insert($data);
				return $result;		
		}
                
                
      public function getfreechapterGrid($aColumns,$data,$paginated=false){
          $select = $this->tableGateway->getSql()->select()->where(array('status'=>'1'));
          $select = $select->join('board_class_subject_chapters', 'board_class_subject_chapters.board_class_subject_chapter_id=free_chapter_list.container_id',array('*'),'left');
	 //echo $select->getSqlString();die;
         
               
          // Search
		if(isset($data['sSearch'])){
			if($data['sSearch']!='0' && $data['sSearch']!=''){
			$search[]=new Predicate\Like('subject_name', '%'.$data['sSearch'].'%');
			$search[]=new Predicate\Like('chapter_name', '%'.$data['sSearch'].'%');
			$search[]=new Predicate\Like('board_name', '%'.$data['sSearch'].'%');
				$select->where(array(
						new Predicate\PredicateSet(
							$search,
							Predicate\PredicateSet::COMBINED_BY_OR
						),
					));
				/*$select->where(array(
					new Predicate\PredicateSet(
						array(
						new Predicate\Like('package_name', '%'.$data['sSearch'].'%'),
						),
							Predicate\PredicateSet::COMBINED_BY_OR
					),
				));*/
			}
		}
		// End
		// Sort Order
		
		$sOrder="";
		if(isset($data['iSortCol_0'])){
			for($i=0;$i<intval($data['iSortingCols']);$i++){
				if($data['bSortable_'.intval($data['iSortCol_'.$i])] == "true"){
					$sOrder.=$aColumns[intval($data['iSortCol_'.$i])]." ".$data['sSortDir_'.$i].",";
				}
			}
			$sOrder = substr_replace($sOrder,"", -1);
		}
		if($sOrder!=''){
			$select->order($sOrder);
		}
		
		// End
		// Pagination
		if(isset($data['iDisplayStart']) && $data['iDisplayLength'] != '-1'){
			$start=(int)$data['iDisplayStart'];
			$currentPage = (int)$data['iDisplayLength'];
			$resultsPerPage = (int)$currentPage;
			//echo $start.'--'.$currentPage;exit;
			$select->limit($currentPage)->offset($start);
		}
		// End
                
		$resultSet = $this->tableGateway->selectWith($select);
               return $resultSet;
      } 
      
      
      
      public function getcount($sword='')
	{
		$select = $this->tableGateway->getSql()->select();
                 //$select = $select->join('board_class_subject_chapters', 'board_class_subject_chapters.board_class_subject_chapter_id=free_chapter_list.container_id',array('*'),'left');
		if($sword!='0' && $sword!=''){
			$search[]=new Predicate\Like('subject_name', '%'.$sword.'%');
			$search[]=new Predicate\Like('chapter_name', '%'.$sword.'%');
			$search[]=new Predicate\Like('board_name', '%'.$sword.'%');
				$select->where(array(
						new Predicate\PredicateSet(
							$search,
							Predicate\PredicateSet::COMBINED_BY_OR
						),
					));
				
		}
		$resultSet = $this->tableGateway->selectWith($select);		
		return $resultSet->count();
	}
        
        
        public function changeStatus($freechapter_ids,$value)
	{
		$data = array(
			'status'       =>$value,
		);
		$updateStatus=$this->tableGateway->update($data,array('(id IN ('.$freechapter_ids.'))'));
		return 	$updateStatus;			
	}
        public function deleteFreechapter($ids){		
		$this->tableGateway->delete(array('(id IN ('.$ids.'))'));			
		return $this->tableGateway->lastInsertValue;	
	}
        
        public function getChapterExistByChapterId($chapterIds) {
        $select = $this->tableGateway->getSql()->select()->columns(array('id','container_id'))
                   ->where('container_id IN ('.$chapterIds.')');
        $resultSet = $this->tableGateway->selectWith($select); 
       // echo "<pre />";print_r($resultSet); exit;
        return $resultSet;        
    }
	
}
