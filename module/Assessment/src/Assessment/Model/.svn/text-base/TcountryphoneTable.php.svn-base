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
class TcountryphoneTable
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
        $select->where("country_id=$countryId");
        //$select->group("bcv.board_class_subject_id");
        return $countryDetails=$this->tableGateway->selectWith($select);
    }

    public function getAllCountryPhoneDetails() {
            $select = $this->tableGateway->getSql()->select();
            $select->where("Status=1");
            return $countryDetails = $this->tableGateway->selectWith($select);
    }	
        
    public function getCountryByCountryCode($code) {
        $select = $this->tableGateway->getSql()->select();
	$select->where("country_id=$code");
        //$select->where("phone_digits=$digits");
    	$countryDetails = $this->tableGateway->selectWith($select);
        return $countryDetails->count();
    }    
    
    public function addDigitsForCountry($countryCode,$digits_lower,$digits_upper) {
        $data = array(
            'country_id' => $countryCode,
            'lower_phone_digit_limit' => $digits_lower,
            'upper_phone_digit_limit' => $digits_upper,
            'created_date' => date('Y-m-d h:i:s'),
            'Status'  => 1,				
        );
        $result=$this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue;
    }
    
    public function updateDigitsForCountry($countryCode,$digits_lower,$digits_upper) {
        $data = array(
            'lower_phone_digit_limit' => $digits_lower,
            'upper_phone_digit_limit' => $digits_upper,
            'created_date' => date('Y-m-d h:i:s'),
            'Status'  => 1,				
        );
        $where = array('country_id' => $countryCode);
        $result = $this->tableGateway->update($data,$where);
        return $this->tableGateway->lastInsertValue;
    }
	
}
