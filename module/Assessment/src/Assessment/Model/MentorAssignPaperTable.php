<?php
namespace Assessment\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql;
/**
 *
 * @author extramarks
 *     */
class MentorAssignPaperTable extends TableGateway
{
    // TODO - Insert your code here
    protected $tableGateway;
    /**
     *
     * @param string $table            
     *
     * @param Adapter $adapter            
     *
     * @param Feature\AbstractFeature|Feature\FeatureSet|Feature\AbstractFeature[] $features            
     *
     * @param ResultSetInterface $resultSetPrototype            
     *
     * @param Sql $sql            
     *
     * @throws Exception\InvalidArgumentException
     *
     */
    
    public function __construct(TableGateway $tableGateway){
    	$this->tableGateway=$tableGateway;
    }
    
	//public function insertdata-This function insert mentor assign papers
    public function insertdata($data)
    {
          $this->tableGateway->insert($data);
          $id = $this->tableGateway->lastInsertValue;
          return $id;
    }
    
	//public function getMentorAssignedPaperList-This function get mentor created papers that paperList assigned to user based on paperAssignId
    public function getMentorAssignedPaperList($mentorId=null, $studentId=null, $paperAssignId=null, $paperId=null,$classId=null)
    {   
        $sql = $this->tableGateway->getSql();
        $select=$this->tableGateway->getSql()->select();
   		$select->join(array('mp'=>'mentor_paper'), "mp.mentor_paper_id = mentor_assign_paper.mentor_paper_id", array('*'),'left');
                $select->join(array('user1'=>'user'), "user1.user_id = mentor_assign_paper.mentor_paper_assigned_to", array('student_name'=>'display_name'),'left');
                $select->join(array('user2'=>'user'), "user2.user_id = mentor_assign_paper.mentor_paper_assigned_by", array('mentor_name'=>'display_name'),'left');
		$select->join(array('rr1'=>'resource_rack'), "rr1.rack_id = mp.mentor_paper_subject", array('*'),'left');
                $select->join(array('rr2'=>'resource_rack'), "rr2.rack_id = mp.mentor_paper_class", array('*'),'left');
		$select->join(array('rack_name1'=>'rack_name'), "rr1.rack_name_id = rack_name1.rack_name_id",array('paperSubject'=>'name','paperSubjectId'=>'rack_name_id'),'left');
                $select->join(array('rack_name2'=>'rack_name'), "rr2.rack_name_id = rack_name2.rack_name_id",array('paperClass'=>'name'),'left');
		$select->order('mp.mentor_paper_id DESC');
   		$select->group('mentor_assign_paper.mentor_paper_assign_id');
                if($mentorId != '')
   		{
   		   $select->join(array('usr'=>'user'), "usr.user_id = mentor_assign_paper.mentor_paper_assigned_to", 
   		   array("display_name", "email"),'left');
   		   $select->where("mentor_paper_assigned_by=$mentorId");
		}
		if($studentId != '')
   		{
   		   $select->join(array('usr'=>'user'), "usr.user_id = mentor_assign_paper.mentor_paper_assigned_by", 
   		   array("display_name", "email"),'left');
   		   $select->where("mentor_paper_assigned_to=$studentId");
		}
		if($paperAssignId != '')
		{
		       $select->where("mentor_paper_assign_id=$paperAssignId");
		}
		if($paperId != '')
		{
		       $select->where("mp.mentor_paper_id=$paperId");
		}
		if($classId != '')
		{
		       $select->where("mp.mentor_paper_class=$classId");
		}
                
                //echo $sql->getSqlstringForSqlObject($select); die;
           $mentorPaperList = $this->tableGateway->selectWith($select);
        return $mentorPaperList->buffer();;
    }
    
    public function getPaperListByUserIdAndSubjectIdAndClassId($studentId=null, $subjectId=null, $classId=null)
    {   
        $sql = $this->tableGateway->getSql();
        $select=$this->tableGateway->getSql()->select();
   		$select->join(array('mp'=>'mentor_paper'), "mp.mentor_paper_id = mentor_assign_paper.mentor_paper_id", array('*'),'left');
                $select->join(array('user1'=>'user'), "user1.user_id = mentor_assign_paper.mentor_paper_assigned_to", array('student_name'=>'display_name'),'left');
                $select->join(array('user2'=>'user'), "user2.user_id = mentor_assign_paper.mentor_paper_assigned_by", array('mentor_name'=>'display_name'),'left');
		$select->join(array('rr1'=>'resource_rack'), "rr1.rack_id = mp.mentor_paper_subject", array('*'),'left');
                $select->join(array('rr2'=>'resource_rack'), "rr2.rack_id = mp.mentor_paper_class", array('*'),'left');
		$select->join(array('rack_name1'=>'rack_name'), "rr1.rack_name_id = rack_name1.rack_name_id",array('paperSubject'=>'name'),'left');
                $select->join(array('rack_name2'=>'rack_name'), "rr2.rack_name_id = rack_name2.rack_name_id",array('paperClass'=>'name'),'left');
		$select->order('mentor_paper_evaluate_date DESC');
   		$select->group('mentor_assign_paper.mentor_paper_assign_id');
                
		if($studentId != '')
   		{
                    $select->join(array('usr'=>'user'), "usr.user_id = mentor_assign_paper.mentor_paper_assigned_by", 
                    array("first_name"=>"display_name", "email"),'left');
                    $select->where("mentor_paper_assigned_to=$studentId");
		}
		if($subjectId != '')
		{
		    $select->where("mp.mentor_paper_subject=$subjectId");
		}
                if($classId != '')
            	{
            	    $select->where("mp.mentor_paper_class=$classId");
            	}
                $select->where("mentor_paper_evaluated_comment!=''");
                //echo $sql->getSqlstringForSqlObject($select); die;
            $mentorPaperList = $this->tableGateway->selectWith($select);
            return $mentorPaperList->buffer();;
    }
    
    //public function getMentorPaper-This function user got the mentor sended testpaper by classId,subjectId and paperName
    public function getMentorPaper($mentorId, $classId, $subjectId, $paperName)
    {
        $select=$this->tableGateway->getSql()->select();
   		$select->join(array('mp'=>'mentor_paper'), "mp.mentor_paper_id = mentor_assign_paper.mentor_paper_id", array('*'),'left');
		$select->join(array('bcv'=>'board_content_view'), "bcv.board_class_subject_id = mp.mentor_paper_subject", array('*'),'left');
		$select->order('mp.mentor_paper_id DESC');
   		$select->group('mentor_assign_paper.mentor_paper_assign_id');
                if($mentorId != '')
   		{
   		   $select->join(array('usr'=>'user'), "usr.user_id = mentor_assign_paper.mentor_paper_assigned_to", 
   		   array("first_name", "last_name", "email_id"),'left');
   		   $select->where("mentor_paper_assigned_by=$mentorId");
		}
		
		if($classId != '')
		{
		       $select->where("mp.mentor_paper_class=$classId");
		}
		if($paperId != '')
		{
		       $select->where("mp.mentor_paper_subject=$subjectId");
		}
                if($paperName != '')
		{
		       $select->where("mp.mentor_paper_name LIKE '$paperName'");
		}
                
           $mentorPaperList = $this->tableGateway->selectWith($select);
          
           if(count($mentorPaperList) > 0)
           {
               return 'exists';
           }
           else
           {
               return 'notexists';
           }
    }
    
     //public function updateData-This function updates the mentor assign papers
    public function updateData($id, $data)
    {
        if($id != null)
        {
            $updateArray = array('mentor_paper_assign_id' => $id);
            $this->tableGateway->update($data, $updateArray); 
        }
    }
    
    /*public function getPaperAssignedToTotalStudent($paperId) {
        $sql = $this->tableGateway->getSql();
        $select=$this->tableGateway->getSql()->select();
        //$select->columns(array('paperCount' => new Expression('COUNT(mentor_paper_assign_id)')));
        $select->where("mentor_paper_id=$paperId");
        //echo $sql->getSqlstringForSqlObject($select); die;
        $result = $this->tableGateway->selectWith($select);
        //return count($rerult);
        return  $result->count();
    }*/
    

}


