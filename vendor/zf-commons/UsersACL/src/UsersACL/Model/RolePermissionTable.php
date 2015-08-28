<?php
namespace UsersACL\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class RolePermissionTable extends AbstractTableGateway
{

    public $table = 'role_permission';

    /**
     * Constructor for RolePermission
     * 
     * @author Kaushal Kishore <kaushal.rahuljaiswal@gmail.com>
     * @param Adapter $adapter            
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAY);
        $this->initialize();
    }

    /**
     * Function for getting role permissions
     * 
     * @author Kaushal Kishore <kaushal.rahuljaiswal@gmail.com>
     * @return unknown
     */
    public function getRolePermissions()
    {
        $sql = new Sql($this->getAdapter());
        
        $select = $sql->select()
            ->from(array(
            't1' => 'role'
        ))
            ->columns(array(
            'role_name'
        ))
            ->join(array(
            't2' => $this->table
        ), 't1.rid = t2.role_id', array(), 'left')
            ->join(array(
            't3' => 'permission'
        ), 't3.id = t2.permission_id', array(
            'permission_name'
        ), 'left')
            ->join(array(
            't4' => 'resource'
        ), 't4.id = t3.resource_id', array(
            'resource_name'
        ), 'left')
            ->where('t3.permission_name is not null and t4.resource_name is not null and t2.status=1')
            ->order('t1.rid');
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $this->resultSetPrototype->initialize($statement->execute())
            ->toArray();
        return $result;
    }
    
    public function invoicesstudy() {
        $sql = new Sql($this->getAdapter());
        $select = $sql->select()
            ->from(array(
            't1' => 'invoices_study'
        ))
        ->where("Date(t1.created_date) >= '2015-04-01' ");
        //echo $select->getSqlString(); exit;
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $this->resultSetPrototype->initialize($statement->execute())
            ->toArray();
        return $result;
    }
    
    public function invoicestab() {
        $sql = new Sql($this->getAdapter());
        $select = $sql->select()
            ->from(array(
            't1' => 'invoices_tablet'
        ))
        ->where("Date(t1.created_date) >= '2015-04-01' ");
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $this->resultSetPrototype->initialize($statement->execute())
            ->toArray();
        return $result;   
    }
    
    public function invoicessdcard() {
        $sql = new Sql($this->getAdapter());
        $select = $sql->select()
            ->from(array(
            't1' => 'invoices_sdcard'
        ))
        ->where("Date(t1.created_date) >= '2015-04-01' ");
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $this->resultSetPrototype->initialize($statement->execute())
            ->toArray();
        return $result;   
    }
    
    public function insertInvoices($data)
    {

        $query = 'TRUNCATE TABLE invoices';
        $stmt = $this->adapter->query($query); 
        $stmt->execute();
        
        $firstRow = array('id'=>'1','created_date'=>'2015-03-31 11:04:06.000000','order_id'=>'e40ad8-12065','financialyear'=>'14-15','invoicenumber'=>'14-15/0001');
        $secondRow = array('id'=>'2','created_date'=>'2015-03-31 23:19:01.000000','order_id'=>'1d6d0f-12071','financialyear'=>'14-15','invoicenumber'=>'14-15/0002');
        
        $sel = new Sql($this->adapter);
        $s = $sel->insert('invoices');
        
        $s->values($firstRow);
        $statement = $sel->prepareStatementForSqlObject($s);
        $result = $statement->execute();
        
        $s->values($secondRow);
        $statement = $sel->prepareStatementForSqlObject($s);
        $result = $statement->execute();
        
        $i=3;
        foreach ($data as $row) {
            if(($row['id']=='6141' && $row['order_id'] =='e40ad8-12065') || ($row['id']=='6142' && $row['order_id'] =='1d6d0f-12071')) {
                continue;
            }
            $row['id'] = '';
            $str='';
            if($i <= 9){
                $str=$row['financialyear'].'/000'.$i;
            } else if($i <= 99) {
                $str=$row['financialyear'].'/00'.$i;
            } else if($i <= 999) {
                $str=$row['financialyear'].'/0'.$i;
            } else if($i <= 9999) {
                $str=$row['financialyear'].'/'.$i;
            }
            $i++;
            $row['invoicenumber']=$str;
            //echo '<pre>'; print_r($row); exit;
            
            $s->values($row);
            $statement = $sel->prepareStatementForSqlObject($s);
            $result = $statement->execute();      
        }
        return $result;
    }
    
}
