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

class TuserlicensedetailTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	
	
	
	//public function 
	public function addUserlicence($data)
    {		
		$this->tableGateway->insert($data);	
		 $id = $this->tableGateway->lastInsertValue;
                return $id ;
	}
	
	
	/**
	 * Created BY : Mohit verma
	 * Created date  : 30-april-14 
	 * Modify date :NA
	 * Modify BY : NA
	 * */		
		
	public function GetuserBylicenceID($id){		
		$select = $this->tableGateway->select(array('license_id="'.$id.'"'));	
		$row = $select->current();		
                return $row;
		
		}
		
		
	public function GetUserbylicenseIdandtabletId($lid,$tid){
                $select = $this->tableGateway->getSql()->select();
                $select->where('license_id="'.$lid.'"');
                $select->where('tablet_id="'.$tid.'"');
                $license_data = $this->tableGateway->selectWith($select);
                $row = $license_data->current();
                return  $row;
		}	
		
		
	public function updatemappedstatus($license_id,$value){
            $data = array(
                'tablet_mapped' =>$value,
            );
             $result = $this->tableGateway->update($data,array('id' => $license_id));
             return $result;
        }	 
	
	
	public function updatemappedstatusbyuserid($user_id,$value){
            $data = array(
                'tablet_mapped' =>$value,
            );
             $result = $this->tableGateway->update($data,array('user_id' => $user_id));
             return $result;
        }
}
