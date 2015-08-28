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
class TchaterResourceTable
{
    protected $tableGateway;
    protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }

  public function getsubchecterlist($pchater_ids)
  {
       $chepter_idstring = implode(",",$pchater_ids);
       $select = $this->tableGateway->getSql()->select();       
       $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=resource_rack.rack_name_id',  array('chapter_name'=>'name'), 'left');
       $select->join(array("r3" => 'rack_type'),'r3.rack_type_id= resource_rack.rack_type_id',  array("rack_type_id"=>'rack_type_id','type_name'=>'type_name'), 'left');
       $select->where("resource_rack.rack_container_id IN ($chepter_idstring)");
       $select->group(array('resource_rack.rack_id'));
       
       $resultSet = $this->tableGateway->selectWith($select)->buffer();
       return $resultSet;       
  }
  public function getsubjectById($subjectArr)  
  {
     $subjectArr =  rtrim($subjectArr,',');
     $select = $this->tableGateway->getSql()->select();
     $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=resource_rack.rack_name_id',  array('chapter_name'=>'name'), 'left');
     $select->where("resource_rack.rack_id IN ($subjectArr)");
     
     $resultSet = $this->tableGateway->selectWith($select)->toArray();
     return $resultSet;
  }
  public function getsubjectcolors($subject_id)
  {
       
        $select = $this->tableGateway->getSql()->select();
        $select->join(array("cd" => 'container_details'),'cd.rack_id=resource_rack.rack_id',  array('col_key'=>'col_key','value'=>'value'),'left');
        $select->where('cd.rack_id="'.$subject_id.'"');
        $select->where('cd.col_key="color"');
        $select->group(array('resource_rack.rack_id'));
       
        $resultSet = $this->tableGateway->selectWith($select)->current();
        return $resultSet;
  }    
  public function getsubjecticons($subject_id)
  {
       
        $select = $this->tableGateway->getSql()->select();
        $select->join(array("cd" => 'container_details'),'cd.rack_id=resource_rack.rack_id',  array('col_key'=>'col_key','value'=>'value'),'left');
        $select->where('cd.rack_id="'.$subject_id.'"');
        $select->where('cd.col_key="icon"');
        $select->group(array('resource_rack.rack_id'));
       
        $resultSet = $this->tableGateway->selectWith($select)->current();
        return $resultSet;
  }    
  
  /*
   * Author:: Pradeep Kumar
   * Descripton: Search Result for all the promotional videos
   * @pass:: board_id as oard_container_id
   * @class_id::  Optional fields
   */
  public function getPromotionalSearchResult($text,$board_id=null,$class_id=null)
  {
     $select = $this->tableGateway->getSql()->select();
     if($board_id!=''){
         $boards_ids = $board_id;
     }else{
        $boards_ids = '5554,5556';
     }
     $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=resource_rack.rack_name_id',  array('chapter_name'=>'name'), '');

     $select->join(array('r2'  => 'resource_rack'), 'r2.rack_id=resource_rack.rack_container_id', array("subject_id"=>"rack_id"), 'left');
     $select->join(array('rn1' => 'rack_name'), 'rn1.rack_name_id=r2.rack_name_id', array('subject_name' => 'name'), 'left');
     $select->join(array('rc2' => 'resource_rack'), 'rc2.rack_id=r2.rack_container_id', array("class_id"=>"rack_id"), 'left');
     $select->join(array('rn2' => 'rack_name'), 'rn2.rack_name_id=rc2.rack_name_id', array('class_name' => 'name'), 'left');
     $select->join(array('rc3' => 'resource_rack'), 'rc3.rack_id=rc2.rack_container_id', array("board_id"=>"rack_id"), 'left');
     $select->join(array('rn3' => 'rack_name'), 'rn3.rack_name_id=rc3.rack_name_id', array('board_name' => 'name'), 'left');
     $select->join(array('rrack'  => 'resource_rack') , new Expression('resource_rack.rack_id=rrack.rack_container_id AND rrack.rack_type_id="6"'),array("alt_chapter_id"=>'rack_id',"alt_type_id"=>'rack_type_id'),'left');
     $select->join(array('rname'  => 'rack_name') , 'rrack.rack_name_id=rname.rack_name_id',array("alt_chapter_name"=>'name'),'left');

     $select->where('resource_rack.rack_name_id!=""');//board_class_subject_chapter_id
     $select->where('resource_rack.rack_type_id!="6"');
     $select->where('resource_rack.rack_type_id!="7"');
     $select->where('rc3.rack_id IN ('.$boards_ids.')');
     $select->where("rc3.rack_id !=''");
     $select->where("resource_rack.client_status=1");
     $select->where("resource_rack.status=1");
     $search[]=new Predicate\Like('rc1.name', '%'.$text.'%'); //chapter_name
     $select->where(array(new Predicate\PredicateSet($search,Predicate\PredicateSet::COMBINED_BY_OR ),));
     $select->group('resource_rack.rack_name_id');//board_class_subject_chapter_id

     $resultSet      = $this->tableGateway->selectWith($select)->toArray();
     $subsubjectlist = $this->getsearchBysubSubject($text)->toArray();

     $returnArray  = array_merge($resultSet, $subsubjectlist/*, $arrayN, $arrayN*/);
     $returnArray2 = $resultSet+$subsubjectlist;
     //print_r($returnArray2);
     return $returnArray;
  }



  public function getSearchResult($text)
  {
     $select = $this->tableGateway->getSql()->select();
     $boards_ids = '5554,5556';
     $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=resource_rack.rack_name_id',  array('chapter_name'=>'name'), '');
     
     $select->join(array('r2'  => 'resource_rack'), 'r2.rack_id=resource_rack.rack_container_id', array("subject_id"=>"rack_id"), 'left');
     $select->join(array('rn1' => 'rack_name'), 'rn1.rack_name_id=r2.rack_name_id', array('subject_name' => 'name'), 'left');
     $select->join(array('rc2' => 'resource_rack'), 'rc2.rack_id=r2.rack_container_id', array("class_id"=>"rack_id"), 'left');
     $select->join(array('rn2' => 'rack_name'), 'rn2.rack_name_id=rc2.rack_name_id', array('class_name' => 'name'), 'left');
     $select->join(array('rc3' => 'resource_rack'), 'rc3.rack_id=rc2.rack_container_id', array("board_id"=>"rack_id"), 'left');
     $select->join(array('rn3' => 'rack_name'), 'rn3.rack_name_id=rc3.rack_name_id', array('board_name' => 'name'), 'left');
     $select->join(array('rrack'  => 'resource_rack') , new Expression('resource_rack.rack_id=rrack.rack_container_id AND rrack.rack_type_id="6"'),array("alt_chapter_id"=>'rack_id',"alt_type_id"=>'rack_type_id'),'left');
     $select->join(array('rname'  => 'rack_name') , 'rrack.rack_name_id=rname.rack_name_id',array("alt_chapter_name"=>'name'),'left');

     $select->where('resource_rack.rack_name_id!=""');//board_class_subject_chapter_id
     $select->where('resource_rack.rack_type_id!="6"');
     $select->where('resource_rack.rack_type_id!="7"');
     $select->where('rc3.rack_id IN ('.$boards_ids.')');
     $select->where("rc3.rack_id !=''");
     $select->where("resource_rack.client_status=1");
     $select->where("resource_rack.status=1");
     $search[]=new Predicate\Like('rc1.name', '%'.$text.'%'); //chapter_name
     $select->where(array(new Predicate\PredicateSet($search,Predicate\PredicateSet::COMBINED_BY_OR ),));
     $select->group('resource_rack.rack_name_id');//board_class_subject_chapter_id
     
     $resultSet      = $this->tableGateway->selectWith($select)->toArray();
     $subsubjectlist = $this->getsearchBysubSubject($text)->toArray();
     
     $returnArray  = array_merge($resultSet, $subsubjectlist/*, $arrayN, $arrayN*/);
     $returnArray2 = $resultSet+$subsubjectlist;
     //print_r($returnArray2);
     return $returnArray;
  }

public function getsearchBysubSubject($text) 
{
    $select = $this->tableGateway->getSql()->select();
    $select->columns(array('type_id'=>'rack_type_id','main_id'=>'rack_id'));
    $boards_ids = '5554,5556';
    $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=resource_rack.rack_name_id',  array('sub_chapter_name'=>'name'), '');
    $select->join(array('r2'  => 'resource_rack'), 'r2.rack_id=resource_rack.rack_container_id', array("subject_id"=>"rack_id"), 'left');
    
    $select->join(array('rn1' => 'rack_name'), 'rn1.rack_name_id=r2.rack_name_id', array('subject_name' => 'name'), 'left');
    $select->join(array('rc2' => 'resource_rack'), 'rc2.rack_id=r2.rack_container_id', array("class_id"=>"rack_id"), 'left');
    $select->join(array('rn2' => 'rack_name'), 'rn2.rack_name_id=rc2.rack_name_id', array('class_name' => 'name'), 'left');
    $select->join(array('rc3' => 'resource_rack'), 'rc3.rack_id=rc2.rack_container_id', array("board_id"=>"rack_id"), 'left');
    $select->join(array('rn3' => 'rack_name'), 'rn3.rack_name_id=rc3.rack_name_id', array('board_name' => 'name'), 'left');

    $select->join(array('rc4' => 'resource_rack'), 'rc4.rack_container_id=resource_rack.rack_id', array("rack_id"=>"rack_id","rack_type_id"=>"rack_type_id" ,"rack_container_id"=>"rack_container_id"), 'left');
    $select->join(array('rn4' => 'rack_name'), 'rn4.rack_name_id=rc4.rack_name_id', array('chapter_name'=>'name'), 'left');
    
    $select->where('resource_rack.rack_name_id!=""');//board_class_subject_chapter_id
    $select->where('resource_rack.rack_type_id!="5"');
    $select->where('rc3.rack_id IN ('.$boards_ids.')');
    $select->where("rc3.rack_id !=''");
    $select->where("rc4.client_status=1");
    $select->where("rc4.status=1");
    $search[]=new Predicate\Like('rn4.name', '%'.$text.'%'); //chapter_name
    $select->where(array(new Predicate\PredicateSet($search,Predicate\PredicateSet::COMBINED_BY_OR ),));
    $select->group('rc4.rack_name_id');
     
    $resultSet      = $this->tableGateway->selectWith($select);
    return $resultSet;
}
/**End OF Class here**/
}