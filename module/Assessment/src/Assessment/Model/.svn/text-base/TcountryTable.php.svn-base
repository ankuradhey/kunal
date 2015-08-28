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
class TcountryTable
{
    protected $tableGateway;
    protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
	$this->select = new Select();
    }
    
    public function getCountryDetailsByCountryId($countryId=null) { 
        $select = $this->tableGateway->getSql()->select();
        //$select->columns(array("email_id","first_name", "last_name"));
        /*$select->join(array('tsam'=>'t_student_and_mentor'), "tsam.student_id = t_user.user_id", array('*'),'left');
        $select->join(array('tmd'=>'t_mentor_details'), "tsam.subject_id = tmd.subject_id", array('*'),'left');
        $select->join(array('bcv'=>'board_content_view'), "bcv.board_class_subject_id = tmd.subject_id", array('*'),'left');
        $select->where("tsam.student_id=$studentId");*/
        $select->where("country_id=$countryId");
        //$select->group("bcv.board_class_subject_id");
        $countryDetails=$this->tableGateway->selectWith($select);   
    	return $countryDetails->current();
    }
    
    public function getAllCountriesDetails() { 
        $select = $this->tableGateway->getSql()->select();
        return $countryDetails=$this->tableGateway->selectWith($select);   
    	
    }
    
    
}