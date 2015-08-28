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
class TuserpackageTable
{
    protected $tableGateway;
	protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	
	//public function deleteData-This function delete data from userPackageTable
	public function deleteData()
    {	
	 $select = $this->tableGateway->getSql()->delete();			
		$resultSet = $this->tableGateway->delete($select);
		return $resultSet;		
	}
	
	//public function getPackages-This function 
	public function getPackages($userId='',$packageId,$count, $product=null,$offset=0){
            //echo $count;
            //echo $offset;exit;
//            echo '<pre>';print_r($userId);echo '</pre>';die('Macro Die');
		$select = $this->tableGateway->getSql()->select();
                $select->columns(array('user_id','valid_till','valid_from', 'status','user_package_id','package_name','is_switched','package_id'));
                $select	->join('t_package_syllabi', 't_user_package.package_id=t_package_syllabi.id',array('package_syllabi_id'=>'id'),'left');
		$select	->join('t_package', 't_package_syllabi.package_id=t_package.package_id',array('actual_package_id'=>'package_id','price'=>'price','group_name','class','board','days','is_active','created_time','class_id','board_id','package_type','package_category','subject_added','package_image','allowed_subject_count'),'left');
                $select	->join('t_user_transaction', 't_user_transaction.transaction_id=t_user_package.transaction_id',array('transaction_id' , 'purchase_date', 'pkg_payment_type',  'order_id' , 'transaction_status' => 'status','transaction_product_type'=>'transaction_product_type', 'code_assign_id'),'left');
		$select	->join('t_currency', new Expression('t_currency.currency_type=t_user_package.currency_type and t_currency.package_id=t_package_syllabi.package_id'),array(),'left');
		$select	->join('user', new Expression('user.user_id=t_user_package.user_id '),array('display_name'),'left');
                $select	->join(array("Tuser"=>'user'), 'Tuser.user_id=t_user_transaction.purchaser_id',array('purchaser'=>'display_name'),'left');
		
              if($userId != ''){
                  if($product == 'all')
                  {
                      $select->where('t_user_package.status != 0 AND (t_user_package.user_id="'.$userId.'" OR t_user_transaction.purchaser_id="'.$userId.'") ' );
                  }elseif($product == 'all_active_inactive'){
                       
                      $select->where('t_user_package.status IN(1,2,3) AND (t_user_package.user_id="'.$userId.'" OR t_user_transaction.purchaser_id="'.$userId.'") ' );
                     // $select->where('CASE WHEN t_package.package_category=\'combo_package\' THEN t_user_package.status IN (1,3)  ELSE TRUE END  ' );
                  }
                  else
                  {
                    $select->where('t_user_package.status= 1 AND (t_user_package.user_id="'.$userId.'" OR t_user_transaction.purchaser_id="'.$userId.'") ' );
                  }  
                }
                $select->order('t_user_package.user_package_id');
                if($packageId!='all' && $packageId!='one'){
			$select->where('t_user_package.user_package_id="'.$packageId.'"');
		}else if($packageId=='one'){
			$select->order('t_user_package.user_id DESC')
                                ->limit($count)
                                ->offset($offset);
                        
		}else{
			$select->order('t_user_package.user_id DESC');
		}
               
                $select->group('t_user_package.user_package_id');
              
		$resultSet = $this->tableGateway->selectWith($select);
                //$sql->getSqlStringForSqlObject($select);
//               echo '<pre>';print_r($resultSet);die;
		return $resultSet;
	}
        
        public function getInactivePackages($transaction_id,$packageid=''){
//            echo '<pre>';print_r($userId);echo '</pre>';die('Macro Die');
		$select = $this->tableGateway->getSql()->select();
                $select	->join('t_package_syllabi', 't_user_package.package_id=t_package_syllabi.id',array('package_syllabi_id'=>'id'),'left');
		$select	->join('t_package', 't_package_syllabi.package_id=t_package.package_id',array('actual_package_id'=>'package_id','price'=>'price','package_name','group_name','class','board','days','is_active','created_time','class_id','board_id','package_type','package_category','subject_added','package_image','allowed_subject_count'),'left');
                $select	->join('t_user_transaction', 't_user_transaction.transaction_id=t_user_package.transaction_id',array('*'),'left');
		$select	->join('t_currency', new Expression('t_currency.currency_type=t_user_package.currency_type and t_currency.package_id=t_package_syllabi.package_id'),array(),'left');
		$select	->join('user', new Expression('user.user_id=t_user_package.user_id '),array('display_name'),'left');
                $select	->join(array("Tuser"=>'user'), 'Tuser.user_id=t_user_transaction.purchaser_id',array('purchaser'=>'display_name'),'left');
                $select->where('t_user_package.status = 0  ' );
                $select->where('t_user_package.transaction_id = '.$transaction_id );
                $select->where("t_user_package.is_switched ='yes' " );
                $select->where("t_package.package_id =".$packageid );
             
		$select->order('t_user_package.user_id DESC');
		
               
                $select->group('t_user_package.user_package_id');
              
		$resultSet = $this->tableGateway->selectWith($select);
//               echo '<pre>';print_r($resultSet);die;
		return $resultSet;
	}
	public function getPackagesubjects($userId){
		$select = $this->tableGateway->getSql()->select();
		$select	->join('t_package_syllabi', 't_package_syllabi.id=t_user_package.package_id',array('package_id','syllabus_id'),'left');
		$select	->join('t_package', 't_package_syllabi.package_id=t_package.package_id',array('board'),'left');
		$select->where('t_user_package.user_id="'.$userId.'"')
				->where('t_user_package.status=1')
                                ->where('DATE_FORMAT(t_user_package.valid_from,"%Y-%m-%d")<="'.date('Y-m-d').'"')
                                ->where('DATE_FORMAT(DATE_ADD(t_user_package.valid_till,INTERVAL 1 DAY),"%Y-%m-%d")>="'.date('Y-m-d').'"')				
				//->where('t_user_package.valid_from<="'.date('Y-m-d H:i:s').'"')
				//->where('t_user_package.valid_till>="'.date('Y-m-d H:i:s').'"')
				->group('t_package_syllabi.syllabus_id');
                 $select->where('t_user_package.status= 1');
		$resultSet = $this->tableGateway->selectWith($select);
		//echo "<pre>";print_r($resultSet);exit;
		return $resultSet;
	}
        
        
	public function getUserPackages($userId){
		$select = $this->tableGateway->getSql()->select();
		$select->where('t_user_package.user_id="'.$userId.'"')
				->where('t_user_package.status=1')
				->where('t_user_package.valid_from<="'.date('Y-m-d H:i:s').'"')
				->where('t_user_package.valid_till>="'.date('Y-m-d H:i:s').'"')
				->group('t_user_package.pakage_id'); 
                    $select->where('t_user_package.status= 1');
                
		$resultSet = $this->tableGateway->selectWith($select);
		//echo "<pre>";print_r($resultSet);exit;
		return $resultSet;
	}

	public function adduserpackages($user_id,$transaction_id,$package_id,$package_details,$discount_amount='')
	{
		$valid_till = date('Y-m-d H:i:s', strtotime($package_details['days'].' days'));
		if($discount_amount=='')
		{
			$discount_amount=0;
		}
		$data = array(
			'valid_till'  		=> 	$valid_till,
			'valid_from'  		=> 	date('Y-m-d H:i:s'),
			'pakage_id'  		=> 	$package_id,
			'user_id'			=>	$user_id, 
			'transaction_id'	=> 	$transaction_id,
			'status'			=> 	1,
			'currency_type'     =>  $_SESSION['currencyType'],
			'discount_amount'   => $discount_amount,
                        'status'   => '0',
		);
		$this->tableGateway->insert($data);	
		return $this->tableGateway->lastInsertValue;
	}
	
	//public function getPackageSubjectsByClassId-This function user buy packages that subscribed packages get  based on classId.
	public function getPackageSubjectsByClassId($class_name,$type,$subject_name='',$board_name='')
	{
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array('pakage_id'));
		
		$select->join('t_package_syllabi', 't_user_package.pakage_id=t_package_syllabi.id',array('syllabus_id'),'left');
                $select->join('t_package', 't_package_syllabi.package_id=t_package.package_id',array('package_id'),'left');
		$select->where('t_user_package.user_id="'.$_SESSION['user']['userId'].'"');
		if($board_name!='')
		{
			$select->where('t_package.board="'.$board_name.'"');
		}
		$select->where('t_package.class="'.$class_name.'"'); 
                $select->where('t_user_package.status= 1');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	public function getPackageSubjectsByClassName($class_name,$type,$subject_name='',$boardId)
	{
		$select = $this->tableGateway->getSql()->select();
		if($type=='subscribe'){
		$select->join('t_package_syllabi', 't_user_package.pakage_id=t_package_syllabi.package_id',array('*'),'left');
		$select->join('board_content_view', 'board_content_view.board_class_subject_id=t_package_syllabi.syllabus_id',array('*'),'left');
		$select->where('t_user_package.user_id="'.$_SESSION['user']['userId'].'"');
		}
		$select->where('board_content_view.class_name="'.$class_name.'"');
		$select->where('board_content_view.board_id="'.$boardId.'"');
		if($subject_name=='')
		{
		$select->where('board_content_view.subject_name!=""');
		$select->group('board_content_view.subject_name');
		$select->order(array('board_content_view.subject_order','board_content_view.subject_name'));
		}else
		{
			$select->where('board_content_view.subject_name="'.$subject_name.'"');
			$select->group('board_content_view.parent_subject_name');
			$select->order(array('board_content_view.parent_subject_order','board_content_view.parent_subject_name'));
		}
                         $select->where('t_user_package.status= 1');
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	//public function checkSubjectsByClassId-This function get package syllabus,board subject based on user packageId,board,classId
	public function checkSubjectsByClassId($class_id,$subjectId)
	{
		$select = $this->tableGateway->getSql()->select();
		$select->join('t_package_syllabi', 't_user_package.pakage_id=t_package_syllabi.package_id',array('*'),'left');
		$select->join('board_content_view', 'board_content_view.board_class_subject_id=t_package_syllabi.syllabus_id',array('*'),'left');
		$select->where('t_user_package.user_id="'.$_SESSION['user']['userId'].'"');
		$select->where('board_content_view.board_class_id="'.$class_id.'"');
		$select->where('board_content_view.board_class_subject_id="'.$subjectId.'"');
		$select->group('board_content_view.board_class_subject_id');
		$select->order(array('board_content_view.subject_order','board_content_view.subject_name'));  
		         $select->where('t_user_package.status= 1');
                $resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
        
        public function getUserPackageSubjectByClassBoard($classId,$boardId){
            $select = $this->tableGateway->getSql()->select();
		$select	->join('t_package_syllabi', 't_package_syllabi.id=t_user_package.pakage_id',array('syllabus_id'),'left');
		$select	->join('t_package', 't_package_syllabi.package_id=t_package.package_id',array('board'),'left');
		$select->where('t_user_package.user_id="'.$_SESSION['user']['userId'].'"')
				->where('t_user_package.status=1')
				->where("t_package.class_id='$classId'")
				->where("t_package.board_id='$boardId'")
				->group('t_package_syllabi.syllabus_id');
                         $select->where('t_user_package.status= 1');
		$resultSet = $this->tableGateway->selectWith($select);
                return $resultSet;
        }
        
        public function updateuserpackages($transaction_id,$package_id, $status)
	{
		$data = array(
			'status'			=> 	$status,
		);
               //echo '<pre>';print_r($data);die;
		$resultSet=$this->tableGateway->update($data, array('transaction_id' => $transaction_id , 'pakage_id' =>$package_id));
		 return $this->tableGateway->lastInsertValue;
	}
          public function checkIfPkgIsUsed($pid)
	{
              $select = $this->tableGateway->getSql()->select();
//		$select->columns(array('pakage_id'));
		
		$select->join('t_package_syllabi', 't_user_package.package_id=t_package_syllabi.id',array('syllabus_id'),'left');
                $select->join('t_package', 't_package_syllabi.package_id=t_package.package_id',array('package_id'),'left');
		$select->where('t_user_package.package_id="'.$pid.'"');
                $resultSet = $this->tableGateway->selectWith($select);
                return $resultSet;
	}
        
        
        
}