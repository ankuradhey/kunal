<?php
namespace Assessment\Model;

use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;
use Zend\Paginator\Paginator;
/**
 *
 * @author extramarks
 *     */
class UserTable 
{
    // TODO - Insert your code here

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
    
    protected $tableGateway;
    protected $select;
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
		$this->select = new Select();
    }
	
	//public function getUserDetails-This function gather userdetails from tables(these tables joined here those table fields grouped in one table i e board-class-subject) based userId
    public function getUserDetails($studentId=null, $mentorId=null){ 
        $sql   = $this->tableGateway->getSql();
        $select=$sql->select();
        $select->columns(array("user_id","username","email","display_name", "gender","phone","postalcode","school_name"));
        $select->join(array('tsam'=>'t_student_and_mentor'), "tsam.student_id = user.user_id", array('id'=>'id','student_id'=>'student_id','mentor_id'=>'mentor_id','status'=>'status'),'left');
        $select->join(array('tmd'=>'t_mentor_details'), "tsam.subject_id = tmd.subject_id", array('mentor_detail_id'=>'subject_id','subject_id'=>'subject_id'),'left');
        $select->join(array("r1" => 'resource_rack'),'r1.rack_id=user.board_id',  array("board_rack_id"=>'rack_id'), 'left');
        $select->join(array("r2" => 'resource_rack'),'r2.rack_id=user.class_id',  array("class_rack_id"=>'rack_id'), 'left');
        
        $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_name_id',  array('board_name'=>'name'), 'left');
        $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_name_id',  array('class_name'=>'name'), 'left');
        
        if($studentId !=''){ 
          $select->where("tsam.student_id=$studentId");
       }if($mentorId !=''){
         $select->where("tmd.mentor_id=$mentorId");
       }
       
        $userDetails=$this->tableGateway->selectWith($select);
    	return $userDetails;
    }

    
   public function getprofilebyid($userid)
   {
        $sql   = $this->tableGateway->getSql();
        $select=$sql->select();		
        $select	->join('t_user_type', 'user.user_type_id=t_user_type.user_type_id',array('*'),'left');

        $select->join(array("r1" => 'resource_rack'),'r1.rack_id=user.board_id',  array("board_rack_id"=>'rack_id'), 'left');
        $select->join(array("r2" => 'resource_rack'),'r2.rack_id=user.class_id',  array("class_rack_id"=>'rack_id'), 'left');

        $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_name_id',  array('board_name'=>'name'), 'left');
        $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_name_id',  array('class_name'=>'name','customClassName'=>'name',"custom_class_id"=>"rack_name_id"), 'left');
        
        $select	->join('t_country', 'user.country_id=t_country.country_id',array('*'),'left');
        $select	->join('t_state', 'user.state_id=t_state.state_id',array('*'),'left');
        $select	->join('t_cities', 'user.city=t_cities.city_id',array('*'),'left');
        $select->join(array('cbr' => 'custom_board_rack'), 'cbr.custom_board_rack_id=user.custom_board_rack_id', array("board_ids"=>"custom_board_id"), 'left');
        $select->join(array('cb' => 'custom_board'), 'cb.custom_board_id=cbr.custom_board_id', array("customBoardName"=>"board_name","custom_board_id"), 'left');
        $select	->group('user.class_id');

        if(isset($_SESSION['user']['user_type_name']) && $_SESSION['user']['user_type_name']!='Student'){
                $select->where('user.user_id="'.$userid.'"');
        }else{
                $select->where('user.user_id="'.$userid.'"');
        }
        $row = $this->tableGateway->selectWith($select);
        //echo '<pre>'; print_r($row); exit;
        return $row;
    } 
    
    public function getstudentprofilebyid($userid,$mentorId=NULL)
   {
        $sql   = $this->tableGateway->getSql();
        $select=$sql->select();		
        $select	->join('t_user_type', 'user.user_type_id=t_user_type.user_type_id',array('*'),'left');
        $select->join(array("r1" => 'resource_rack'),'r1.rack_id=user.board_id',  array("board_rack_id"=>'rack_id'), 'left');
        $select->join(array("r2" => 'resource_rack'),'r2.rack_id=user.class_id',  array("class_rack_id"=>'rack_id'), 'left');
        $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_name_id',  array('board_name'=>'name'), 'left');
        $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_name_id',  array('class_name'=>'name'), 'left');
        $select	->join('t_country', 'user.country_id=t_country.country_id',array('*'),'left');
        $select	->join('t_state', 'user.state_id=t_state.state_id',array('*'),'left');
        $select	->join('t_cities', 'user.city=t_cities.city_id',array('*'),'left');
        $select->join(array('tsam' => 't_student_and_mentor'), 'tsam.student_id=user.user_id', array("student_id","mentor_id"), 'left');
        $select->join(array('r3' => 'resource_rack'), 'r3.rack_id=tsam.subject_id', array("sub_id"=>'rack_id'), 'left');
        $select->join(array('r4' => 'resource_rack'), 'r4.rack_id=r3.rack_container_id', array("class_ids"=>"rack_id"), 'left');
        $select->join(array('rn2' => 'rack_name'), 'rn2.rack_name_id=r4.rack_name_id', array('customClassName' => 'name'), 'left');
        $select->join(array('cbr' => 'custom_board_rack'), 'cbr.custom_board_rack_id=tsam.custom_board_rack_id', array("board_ids"=>"custom_board_id"), 'left');
        $select->join(array('cb' => 'custom_board'), 'cb.custom_board_id=cbr.custom_board_id', array("customBoardName"=>"board_name"), 'left');
        $select->group('user.class_id');

        if(isset($_SESSION['user']['user_type_name']) && $_SESSION['user']['user_type_name']!='Student'){
                $select->where('user.user_id="'.$userid.'"');
        }else{
            if(!empty($mentorId)) {
                $select->where('user.user_id="'.$userid.'" && tsam.mentor_id="'.$mentorId.'"');
            } else {
                $select->where('user.user_id="'.$userid.'"');
                //$select->where('user.user_id="'.$userid.'" && tsam.status=0');
        }
        }
        /*if(!empty($boardId)) {
            $select->where('cbr.custom_board_id="'.$boardId.'"');
        }
        if(!empty($classId)){
            $select->where('r3.rack_container_id="'.$classId.'"');
        }*/
        $select->order('tsam.id DESC');
        //$select->limit(1);

        //echo $sql->getSqlstringForSqlObject($select); die ;
        $row = $this->tableGateway->selectWith($select);
        
        return $row;
    } 
       
    
 public function checkEmail($email)
  {	
	    $select = $this->tableGateway->getSql()->select()->where('email="'.$email.'"');	
            $select->join(array('rr'=>'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'),'left');
            $select->join(array('rr2'=>'resource_rack'), "rr2.rack_id = user.class_id", array('class_id'=>'rack_id'),'left');
            $resultSet = $this->tableGateway->selectWith($select);
            return $resultSet;		
   }
 public function SearchEmailbystring($email)
 {	
        $select = $this->tableGateway->getSql()->select()
                        ->columns(array('user_id' => 'user_id','email'=>'email','display_name'=>'display_name'))
                       ->where(new \Zend\Db\Sql\Predicate\Like('email', '%'.$email.'%'));	
            
            $resultSet = $this->tableGateway->selectWith($select);
            return $resultSet;		
    } 
    
 public function UserMinDetailByID($userid)
   {	
        $select = $this->tableGateway->getSql()->select()
                       ->columns(array('user_id' => 'user_id','email'=>'email','display_name'=>'display_name'));
                 $select->where('user_id="' . $userid . '"');
                 $select->group('user_id');
            
            $resultSet = $this->tableGateway->selectWith($select);
            return $resultSet->current();		
    }    
    
     public function ispasswordcorrect($userid,$currentpwd)
   {	
        $password = md5($currentpwd); 
        $select = $this->tableGateway->getSql()->select()
                       ->columns(array('user_id' => 'user_id','email'=>'email','display_name'=>'display_name'));
                 $select->where('user_id="' . $userid . '"');
                 $select->where('password="' . $password . '"');
                 $select->group('user_id');
            
            $resultSet = $this->tableGateway->selectWith($select);
            return $resultSet->count();		
    } 
  public function getuserdetailsForSubscription($userid, $userTypeId) 
  {
    $select = $this->tableGateway->getSql()->select();
    $select->join('t_user_type', 'user.user_type_id=t_user_type.user_type_id',array('*'), 'left');
    $select->join(array("r1" => 'resource_rack'),'r1.rack_id=user.board_id',  array("board_rack_id"=>'rack_id'), 'left');
    $select->join(array("r2" => 'resource_rack'),'r2.rack_id=user.class_id',  array("class_rack_id"=>'rack_id'), 'left');
        
    $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_name_id',  array('board_name'=>'name'), 'left');
    $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_name_id',  array('class_name'=>'name'), 'left');
        
    $select->join('t_country', 'user.country_id=t_country.country_id', array('country_id','country_name','iso'), 'left');
    $select->join('t_state', 'user.state_id=t_state.state_id', array('*'), 'left');
//        $select->join('t_cities', 'user.other_city=t_cities.city_id', array('*'), 'left');

    $select->group('user.class_id');
     if(isset($userTypeId) && $userTypeId != '1') {
           $select->where('user.user_id="' . $userid . '"');
        }else{
            $select->where('user.user_id="' . $userid . '"');
        }
        $row = $this->tableGateway->selectWith($select);
        return $row;
    }
    
  public function getChildData($userId){
            $returnData=array();
            if($userId>0){
                $select = $this->tableGateway->getSql()->select();
                $select->columns(array("user_id","username","email","display_name", "gender","phone","postalcode","school_name","user_photo","dob","allowschedule","custom_board_rack_id"));
                $select->join(array("r1" => 'resource_rack'),'r1.rack_id=user.board_id',array("board_id"=>'rack_id'), 'left');
                $select->join(array("r2"=>'resource_rack'), 'r2.rack_id = user.class_id',array("class_id"=>'rack_id'), 'left');
                $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_name_id',  array('board_name'=>'name'), 'left');
                $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_name_id',  array('class_name'=>'name'), 'left');
                $select->where('user.parent_id="'.$userId.'"');
                $select->group('user.user_id');
                
                $resultSet = $this->tableGateway->selectWith($select);
                //echo '<pre>'; print_r($resultSet); exit;
                $returnData = array();
                foreach($resultSet as $val){
                    $returnData[] = $val;
                 }
             }
           
            return $returnData;
        }   
        
        public function countChildData($userId) {
            $returnData=array();
            if($userId>0) {
                $select = $this->tableGateway->getSql()->select();
                $select->columns(array('user_id' => 'user_id'));
                $select->where('user.parent_id="'.$userId.'"');
                $select->group('user.user_id');                
                $resultSet  = $this->tableGateway->selectWith($select);
                $returnData = $resultSet->count();                
            }
            
            return $returnData;
        }
        
        public function getuserdetailsChild($userid) {
		$select = $this->tableGateway->getSql()->select();
		$select	->join('t_student_and_mentor', 'user.user_id=t_student_and_mentor.student_id',array('nameChild' =>new Expression('user.display_name')),'left');
		$select->where('t_student_and_mentor.mentor_id="'.$userid.'"');
		$select->where('t_student_and_mentor.status=1');
		$select->group('user.user_id');
		$row = $this->tableGateway->selectWith($select);		
                return $row;
	}   
        
        public function updatemobile($user,$userid) {
            $data['mobile'] = (isset($user['phonecode']))?$user['phonecode'].'-'.$user['phonenumber']:$user['phonenumber'];
            $row=$this->tableGateway->update($data, array('user_id' => $userid));
            return $row;
        }
    
    public function updateprofile($user,$imageName,$userid)
    {
        $dobMonth = $user['dob_month'];
        $dobDay = $user['dob_day'];
        $dobYear = $user['dob_year'];
        if(!empty($dobMonth) && !empty($dobDay) && !empty($dobYear)) {
            if(strlen($dobMonth)==1) {
                $dobMonth = '0'.$dobMonth;
            }
            if(strlen($dobDay)==1) {
                $dobDay = '0'.$dobDay;
            }
            $dob = $dobDay.'-'.$dobMonth.'-'.$dobYear;
        } else {
            $dob = '';
        }
        //$data['username']       = $user['emailid'];
        $data['display_name']     = strip_tags($user['username']);
        $data['mobile']           = (isset($user['phonecode']))?$user['phonecode'].'-'.$user['phonenumber']:$user['phonenumber'];
        $data['user_type_id']     = $user['usertype'];
	$data['country_id']       = $user['ucountries'];
	$data['state_id']         = $user['states'];
	$data['city']             = $user['other_city'];
	$data['gender']           = $user['sex'];
        $data['email']           = $user['useremail'];
        if($user['resource_board_id']) {
            $data['board_id'] = $user['resource_board_id'];
        } else {
            $data['board_id'] = $user['board'];
        }
	$data['class_id']         = $user['classnames'];
	$data['school_name']      = strip_tags($user['userschool']);
	$data['user_photo']       = $imageName;
	$data['dob']              = '';
	$data['postalcode']       = strip_tags($user['postalcode']);
        $data['allowschedule']    = $user['allowschedule'];
	$data['dob']              = $dob;
        $data["custom_board_rack_id"]= $user['customboardId'];
         $row=$this->tableGateway->update($data, array('user_id' => $user['hiduserid']));
        return $row; 
    }
    
    public function updatepkgprofile($user,$userid)
    {
       $data['mobile'] = (isset($user['phcode']))?$user['phcode'].'-'.$user['phone']:$user['phone']; 
       $data['gender'] = (isset($user['gender']))?$user['gender']:'';
       $data['dob']    = $user['dob'];
       $row=$this->tableGateway->update($data, array('user_id' => $userid));
       return $row; 
    }
    
    public function updateprofileOffline($mobileCustomer,$addressCustomer,$userId)
    {
        $data['mobile']           = $mobileCustomer;
        $data['address']          = $addressCustomer;
	
        $row=$this->tableGateway->update($data, array('user_id' => $userId));
        return $row; 
    }
    public function updateUserAddress($data,$userId)
    {
        $row=$this->tableGateway->update($data, array('user_id' => $userId));
        return $row; 
    }
    
    public function updatepassworddetail($conformpwd,$hintpassword,$userId)
    {
        $data['password']       = md5($conformpwd);
        //$data['password_hint']  = $hintpassword;
	
        $row=$this->tableGateway->update($data, array('user_id' => $userId));
        return $row; 
    }
    
    public function getParentData($userId) {

        $select = $this->tableGateway->getSql()->select();
        $select->where('user_id="' . $userId . '"');
        $row = $this->tableGateway->selectWith($select);
        foreach ($row as $val) {
            //echo "<pre />"; print_r($val);
            $parentId = $val->parent_id;
        }

        if ($parentId != '' || $parentId != '0') {
            $select = $this->tableGateway->getSql()->select();
            $select->where('user_id="' . $parentId . '"');
            $row = $this->tableGateway->selectWith($select);
            return $row;
        } else {
            return null;
        }
    }
    
     public function updateparentchildstatus($userId,$status) 
     {
        if ($userId != '') {
            $data = array('parent_id'=>NULL,'user_status'=>$status);
            $row  = $this->tableGateway->update($data, array('user_id' => $userId));
            return $row;
        }
    }
    
 public function updateBounceEmail($data)
 {
    $updateData = array('valid_email'=>5);
    $row  = $this->tableGateway->update($updateData, array('email' => $data));
 }
 
 public function updateBounceEmailVaild($data)
 {
    $updateData = array('valid_email'=>NULL);
    $row  = $this->tableGateway->update($updateData, array('email' => $data));
 }
 
 public function updateSubscribeEmail($data)
 {
    $updateData = array('subscribe_me'=>'n');
    $row  = $this->tableGateway->update($updateData, array('email' => $data));
 }
 
        //public function checkEmail-This function get type(student,teacher and parent) based on email_id
	public function checkEmailType($email,$module)
        {	
	  $select = $this->tableGateway->getSql()->select()			
			->where('email_id="'.$email.'"');					 
		$resultSet = $this->tableGateway->selectWith($select);
		if($module=='account'){
			$row = $resultSet;
		}else{
			$row = $resultSet->count();
		}
		return $row;		
	}
	
           public function getUser($userid) {
        $select = $this->tableGateway->getSql()->select();
        //$select->quantifier('DISTINCT');
        $select->join('t_user_type', 'user.user_type_id=t_user_type.user_type_id', array('*'), 'left');
        $select->join(array("r1" => 'resource_rack'),'r1.rack_id=user.board_id',  array('*'), 'left');
        $select->join(array("r2" => 'resource_rack'),'r2.rack_id=user.class_id',  array('*'), 'left');
        $select->join(array("rc1" => 'rack_name'),'rc1.rack_name_id=r1.rack_id',  array('board_name'=>'name'), 'left');
        $select->join(array("rc2" => 'rack_name'),'rc2.rack_name_id=r2.rack_id',  array('class_name'=>'name'), 'left');
        
        $select->join('t_country', 'user.country_id=t_country.country_id', array('*'), 'left');
      //  $select->join('t_state', 'user.state=t_state.state_id', array('*'), 'left');
      //  $select->join('t_cities', 'user.city=t_cities.city_id', array('*'), 'left');
        
        $row = $this->tableGateway->selectWith($select);
        return $row;
    }

  public function groupNames($userids){
		$select = $this->tableGateway->getSql()->select()				
				->where(array('(user_id IN ('.$userids.'))'));
		$resultSet = $this->tableGateway->selectWith($select);		
		return $resultSet;
	}
	
    //public function getuserid-This function get the emailId current login emailId
	public function getuserid($emailid){
		$select = $this->tableGateway->getSql()->select();		
		$select->where('email="'.stripslashes($emailid).'"');
		$resultSet = $this->tableGateway->selectWith($select);
               $row = $resultSet->current();
        return $row;
	}
        public function getuserdetailsById($userid){
		$select = $this->tableGateway->getSql()->select();
		$select->where('user_id="'.$userid.'"');
                
		$row = $this->tableGateway->selectWith($select);		
        return $row;
	}
   
 public function updateparentID($ID, $parentID) {

        $data = array(
            'parent_id' => $parentID,
        );
        $updateuserid = $this->tableGateway->update($data, array('user_id' => $ID));

        return $updateuserid;
    }       

    public function getuserdetailsByRegisteredDate($reg_date) {
		$select = $this->tableGateway->getSql()->select();
		$select->where('create_time LIKE "'.$reg_date.'%"');
                
		$row = $this->tableGateway->selectWith($select);
                 return $row;
	}
        public function getuserdetailsByIds($userid){
		$select = $this->tableGateway->getSql()->select();
		$select->where('user_id in ('.$userid.')');
                
		$row = $this->tableGateway->selectWith($select);		
        return $row;
	}    
    
    
 public function misReportData($params = array()) {  
    $where_query = "1";
    if(!empty($params['fromDate']) && !empty($params['toDate'])){
//        $where_query .=  " AND (DATE_FORMAT(tut.purchase_date,'%Y-%m-%d') >= '".$params['fromDate']."' AND DATE_FORMAT(tut.purchase_date,'%Y-%m-%d') <= '".$params['toDate'] . "')";   
        $where_query .=  " AND ( tut.purchase_date >= '".$params['fromDate']." 00:00:00' AND tut.purchase_date <= '".$params['toDate'] . " 23:59:59')";   
    }
    
    if(!empty($params['keyword'])){
       $where_query .=  " AND (tu.email LIKE '%" . $params['keyword']. "%' OR ";
       $where_query .=  "tdc.coupon_code LIKE '%" . $params['keyword']. "%' OR ";
       $where_query .=  "tut.order_id LIKE '%" . $params['keyword']. "%') ";       
    }
    
    if(!empty($params['pkg_payment_details'])){
        $where_query .=  " AND (tut.pkg_payment_details = '" . $params['pkg_payment_details']. "')"; 
    }
     
     if(!empty($params['user_id'])){
        $where_query .=  " AND (tu.user_id = '" . $params['user_id']. "')"; 
    }
   
//    //echo 'yoyo'.$where_query;die;
     if(!empty($params['payment_mode'])){
         if($params['payment_mode'] == 'online'){
            $where_query .=  " AND (tut.pkg_payment_type != 'offline')"; 
         }else if($params['payment_mode'] == 'offline'){
             $where_query .=  " AND (tut.pkg_payment_type = 'offline')"; 
         }else{
               //$where_query .=  " AND (tut.pkg_payment_type = 'FREE USER')"; 
         }
    }
     //$where_query .= "AND tu.email not like '%extramarks.com%'";
    // $where_query .= "AND tu.email not like '%extramarks.com%'";
     
    $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'email', 'display_name','mobile', 'address','city','user_type_id','username'));
    $select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array('package_id', 'discount_amount','status','user_package_id','valid_till','paid_price'=> 'paid_amount', 'package_price', 'currency_type', 'is_switched'), 'left')
            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array('syllabus_id'), 'left')
            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_id','package_name','class_name'=>'class','board_name'=>'board', 'price', 'package_category'), 'left')
            ->join(array("tpc" => "t_package_classes"), "tpc.package_id = tp.package_id", array('combo_class_id' => new Expression('GROUP_CONCAT(tpc.class_id)')), 'left')
            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_id','transaction_product_type','order_id','purchase_date'=>'purchase_date','pkg_payment_details','pkg_payment_type','purchaser_id','transaction_status' => 'status','employee_id','transaction_gateway_response', 'ip', 'transaction_discount', 'transaction_amount', 'transaction_total_refund_amount', 'transaction_refund_type','code_assign_id'), 'left')
            ->join(array("ts" => "t_state"), "ts.state_id = user.state_id", array('state_name'), 'left')
            ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent','coupon_type','discount_type'), 'left')           
            ->join(array("tu" => "user"), new Expression("tut.purchaser_id = tu.user_id"), array('parent_name'=>'display_name','parent_email'=>'email','parent_address'=>'address','parent_mobile'=>'mobile', 'parent_user_type_id' => 'user_type_id'), 'left')
            ->join(array("ted" => "t_employee_details"), new Expression("tut.employee_id = ted.employee_code"), array('employee_name'=>'emp_name'), 'left')
            ->join(array("opt" => "offline_payment_transaction"), new Expression("opt.user_transaction_id = tut.transaction_id"), array('payment_mode'=>'payment_mode','login_id'=>'login_id','payment_source'=>'payment_source','dd_cheque_number', 'dd_cheque_date', 'bank', 'deposit_date', 'account_number', 'bank_branch','payment_update_user_id','other_payment_details'), 'left')
            ->join(array('in_study'  => 'invoices_study') , new Expression('tut.order_id=in_study.order_id AND tut.transaction_product_type="study"'),array("study_invoice_id"=>'id'),'left') 
            ->join(array('in_tab'  => 'invoices_tablet') , new Expression('tut.order_id=in_tab.order_id AND tut.transaction_product_type="tablet"'),array("tab_invoice_id"=>'id'),'left') 
            ->join(array('in_sdcard'  => 'invoices_sdcard') , new Expression('tut.order_id=in_sdcard.order_id AND tut.transaction_product_type="sdcard"'),array("sdcard_invoice_id"=>'id'),'left') 
            ->join(array('in_td'  => 'invoices_testing_demo') , new Expression('tut.order_id=in_td.order_id'),array("invoice_id"=>'id'),'left') 
            ->join(array('aca'  => 'activation_code_assignment') , new Expression('aca.code_assign_id=tut.code_assign_id'),array("assign_activation_code", "assign_card_price"),'left') 
            ->join(array('trud'  => 't_referred_user_details') , new Expression('trud.referred_by_user_id=tut.purchaser_id AND trud.transaction_id=tut.transaction_id'),array('refered_ids' => new Expression('GROUP_CONCAT(trud.id)')),'left') 
            ->join(array("tprr" => "t_package_repair_request"), "tprr.transaction_id = tut.transaction_id AND tprr.user_package_id=tup.user_package_id", array('repair_replace_id'=>'id','repair_replace_status'=>'status','request_type'), 'left')
            ->join(array("inv" => "invoices"), "tprr.id = inv.request_id", array('invoice_number'=>'invoicenumber'), 'left')
            ->where($where_query)
            ->where("tut.order_id IS NOT NULL");
        
          if(isset($params['invoice_input']) && $params['invoice_input'] !='')
          {
              $select->where('(in_study.id="'.$params['invoice_input'].'" OR in_tab.id= "'.$params['invoice_input'].'" OR in_sdcard.id= "'.$params['invoice_input'].'" OR in_td.id= "'.$params['invoice_input'].'" )');
          }
            //->where("tut.coupon_id!=1")
           // ->where("tdc.coupon_type!='test'")
            $select->group(array("tup.transaction_id", "tup.user_package_id"));
            
            if(isset($params['amount_paid_input']) && $params['amount_paid_input']!=''){ 
                
               $select->having("IF(tut.transaction_amount IS NULL,tup.paid_amount,tut.transaction_amount)".$params['operator_input']." ".$params['amount_paid_input']);    
            }else{
               //$select->having("paid_price>=0");
            }
            
            $select->order("tut.transaction_id DESC");
//->order("tut.purchase_date")
        
        
        $resultSet = $this->tableGateway->selectWith($select);
        //echo '<pre>';print_r($resultSet);die;
        return $resultSet;
 }
 
 public function misReportDataManagement($params = array()) { 
    $where_query = "1";
    if(!empty($params['fromDate']) && !empty($params['toDate'])){
//        $where_query .=  " AND (DATE_FORMAT(tut.purchase_date,'%Y-%m-%d') >= '".$params['fromDate']."' AND DATE_FORMAT(tut.purchase_date,'%Y-%m-%d') <= '".$params['toDate'] . "')";   
        $where_query .=  " AND ( tut.purchase_date >= '".$params['fromDate']." 00:00:00' AND tut.purchase_date <= '".$params['toDate'] . " 23:59:59')";   
    }
    
    if(!empty($params['keyword'])){
       $where_query .=  " AND (user.email LIKE '%" . $params['keyword']. "%' OR ";
       $where_query .=  "tdc.coupon_code LIKE '%" . $params['keyword']. "%' OR ";
       $where_query .=  "tut.order_id LIKE '%" . $params['keyword']. "%') ";       
    }
    
    if(!empty($params['pkg_payment_details'])){
        $where_query .=  " AND (tut.pkg_payment_details = '" . $params['pkg_payment_details']. "')"; 
    }
     
     if(!empty($params['user_id'])){
        $where_query .=  " AND (user.user_id = '" . $params['user_id']. "')"; 
    }
     if(!empty($params['payment_mode'])){
         if($params['payment_mode'] == 'online'){
            $where_query .=  " AND (tut.pkg_payment_type != 'offline')"; 
         }else if($params['payment_mode'] == 'offline'){
             $where_query .=  " AND (tut.pkg_payment_type = 'offline')"; 
         }else{
               $where_query .=  " AND (tut.pkg_payment_type = 'FREE USER')"; 
         }
    }
     //$where_query .= "AND tu.email not like '%extramarks.com%'";
    // $where_query .= "AND tu.email not like '%extramarks.com%'";
     
    $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'email', 'display_name', 'phone','mobile', 'address','city','user_type_id','parent_id','username'));    
            //->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array('package_id','price' =>'package_price', 'discount_amount','status','user_package_id','valid_till','paid_price'=> new \Zend\Db\Sql\Expression('package_price - discount_amount') , 'package_price'), 'left')
    $select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array('package_id','transaction_product_type'=>'package_product_type', 'price'=>new \Zend\Db\Sql\Expression('SUM(package_price)') , 'discount_amount'=>new \Zend\Db\Sql\Expression('SUM(discount_amount)') ,'status','user_package_id','valid_till', 'currency_type','paid_price'=>new \Zend\Db\Sql\Expression('SUM(paid_amount)')  ), 'left')
            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array('id','syllabus_id'), 'left')
            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name','package_type','class_name'=>'class','board_name'=>'board'), 'left')
            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('order_id','purchase_date'=>'purchase_date','pkg_payment_details','pkg_payment_type','purchaser_id', 'transaction_discount', 'transaction_amount'), 'left')
            ->join(array("ts" => "t_state"), "ts.state_id = user.state_id", array('state_id','state_name'), 'left')
            ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent','coupon_type', 'discount_type'), 'left')
            ->join(array("tu" => "user"), new Expression("tut.purchaser_id = tu.user_id"), array('parent_name'=>'display_name','parent_email'=>'email','parent_address'=>'address','parent_mobile'=>'phone'), 'left')
            ->join(array("opt" => "offline_payment_transaction"), new Expression("opt.user_transaction_id = tut.transaction_id"), array('payment_mode'=>'payment_mode','login_id'=>'login_id','payment_source'=>'payment_source'), 'left')
            ->where($where_query)
            ->where("tut.order_id IS NOT NULL")
            //->where("tut.coupon_id!=1")
           // ->where("tdc.coupon_type!='test'")
            ->where("tut.pkg_payment_details='Processed'")
            ->group("tup.transaction_id")
            ->having("paid_price>=0")
//          ->order("tut.purchase_date")
            ->order("tut.pkg_payment_details DESC"); 
        
        $resultSet = $this->tableGateway->selectWith($select); //echo '<pre>';print_r($resultSet);die;
        return $resultSet;
 }
 
 public function offlineFinanceData($params = array()) { 
    $where_query = "1";
//    echo '<pre>';print_r ($params);echo '</pre>';die('Vikash');
    if(!empty($params['fromDate']) && !empty($params['toDate'])){
//        $where_query .=  " AND (DATE_FORMAT(tut.purchase_date,'%Y-%m-%d') >= '".$params['fromDate']."' AND DATE_FORMAT(tut.purchase_date,'%Y-%m-%d') <= '".$params['toDate'] . "')";   
        $where_query .=  " AND ( tut.purchase_date >= '".$params['fromDate']." 00:00:00' AND tut.purchase_date <= '".$params['toDate'] . " 23:59:59')";   
    }
    
    if(!empty($params['board_id'])){
       $where_query .=  " AND (tp.board_id = '" . $params['board_id']. "') ";  
    }
    if(!empty($params['class_id'])){
       $where_query .=  " AND (tp.class_id = '" . $params['class_id']. "') ";  
    }
    if(!empty($params['package_type'])){
       $where_query .=  " AND (tp.package_type = '" . $params['package_type']. "') ";  
    }
    if(!empty($params['package_category'])){
       $where_query .=  " AND (tp.package_category = '" . $params['package_category']. "') ";  
    }
    
    if(!empty($params['pkg_payment_details'])){
        $where_query .=  " AND (tut.pkg_payment_details = '" . $params['pkg_payment_details']. "')"; 
    }
     
    if(!empty($params['customer_name'])){
       $where_query .=  " AND (tu.email LIKE '%" . $params['customer_name']. "%' OR ";
       $where_query .=  "tu.display_name LIKE '%" . $params['customer_name']. "%') ";    
    }
    if(!empty($params['is_active'])){
        $where_query .=  " AND (opt.payment_collection_status = '" . $params['is_active']. "')"; 
    }else{
//        $where_query .=  " AND (opt.payment_collection_status = 'Pending')"; 
    }
     
     if(!empty($params['payment_mode'])){
         if($params['payment_mode'] == 'online'){
            $where_query .=  " AND (tut.pkg_payment_type != 'offline')"; 
         }else{
             $where_query .=  " AND (tut.pkg_payment_type = 'offline')"; 
         }
    }
     
    $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'email', 'display_name', 'phone','mobile', 'address','city','user_type_id','parent_id','username'));
    $select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array('package_id', 'discount_amount','status','user_package_id','valid_till','paid_price' => 'paid_amount','currency_type','package_price'), 'left')
            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array('id','syllabus_id'), 'left')
            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name','package_type','class_name'=>'class','board_name'=>'board', 'price', 'minimum_selling_price','is_usd_saved','is_sgd_saved'), 'left')
            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_id','transaction_product_type','order_id','purchase_date'=>'purchase_date','pkg_payment_details','pkg_payment_type','purchaser_id', 'employee_id', 'transaction_amount', 'transaction_discount'), 'left')
            ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent','coupon_type'), 'left')
            ->join(array("tu" => "user"), new Expression("tut.purchaser_id = tu.user_id"), array('parent_name'=>'display_name','parent_email'=>'email','parent_address'=>'address','parent_mobile'=>'phone'), 'left')
            ->join(array("opt" => "offline_payment_transaction"), new Expression("opt.user_transaction_id = tut.transaction_id"), array('offline_tran_id'=>'id','payment_mode'=>'payment_mode','login_id'=>'login_id','payment_source'=>'payment_source','payment_collection_status','payment_update_user_id' , 'dd_cheque_number' ,'dd_cheque_date' , 'bank' , 'deposit_date' , 'account_number' , 'bank_branch' ), 'left')
            ->join(array("tc" => "t_currency"), new Expression("tc.package_id = tp.package_id AND tup.currency_type=tc.currency_type"), array('tc_minimum_selling_price' => 'minimum_selling_price'), 'left')
            ->where($where_query)
            ->where("tut.order_id IS NOT NULL")
            ->where("tut.coupon_id!=1")
            ->where("opt.payment_collection_status IS NOT NULL ")
           // ->where("tdc.coupon_type!='test'")
            ->group("tup.transaction_id")
            ->having("paid_price>=0")
//          ->order("tut.purchase_date")
            ->order("tut.transaction_id DESC"); 
        
        $resultSet = $this->tableGateway->selectWith($select); //echo '<pre>';print_r($resultSet);die;
        return $resultSet;
        
        $arrData = array();
        foreach ($resultSet as $result) {
            $arrData[] = $result;
        }
        return $arrData;
 }
 
 
 public function getRegisteredUserByDate($cdate){
     $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'user_type_id'))       
             ->join(array("up" => 'user_provider'), "user.user_id = up.user_id", array('provider'), 'left') 
             ->where("user.create_time >= '" . $cdate . " 00:00:00'")
             ->where("user.create_time <= '" . $cdate . " 23:59:59'");
        $resultSet = $this->tableGateway->selectWith($select);    
        
        $tot_student = 0;
        $tot_mentor = 0;
        $tot_parent = 0;
        
        $tot_facebook = 0;
        $tot_google = 0;
        $tot_twitter = 0;
        
        foreach($resultSet as $result){
            if($result->user_type_id == '1'){
                $tot_student = $tot_student+1;
            }else if($result->user_type_id == '2'){
                $tot_parent = $tot_parent+1;
            } else {
                $tot_mentor = $tot_mentor+1;
            }
            if($result->provider == 'facebook'){
                $tot_facebook = $tot_facebook+1;
            }else if($result->provider == 'google'){
                $tot_google = $tot_google+1;
            } else if($result->provider == 'twitter'){
                $tot_twitter = $tot_twitter+1;
            }
        }
        return $countArray = array(
            'student_cnt'=>$tot_student,
            'mentor_cnt'=>$tot_mentor,
            'parent_cnt'=>$tot_parent,
            'facebook_cnt'=>$tot_facebook,
            'google_cnt'=>$tot_google,
            'twitter_cnt'=>$tot_twitter);
    }
    /// Insert user data when user activation tablet.
    public function addUser($data){
         $this->tableGateway->insert($data);
         $id = $this->tableGateway->lastInsertValue;
        return $id;
    }
 
    public function getuserbyusername($username) {

          $select = $this->tableGateway->getSql()->select();
          $select->where('username="' . $username . '"');
          $resultSet = $this->tableGateway->selectWith($select);
          $row = $resultSet->current();
          return $row;
    }
    
    public function getUserByUserId($user_id) {

          $select = $this->tableGateway->getSql()->select();
          $select->where('user_id="' . $user_id . '"');
          $resultSet = $this->tableGateway->selectWith($select);
          $row = $resultSet->current();
          return $row;
    }
    
    public function getAllUsersForIp() {
          $check_date = "2014-08-01";
          $select = $this->tableGateway->getSql()->select();
          $where_query =  " ip IS NOT NULL AND ip NOT LIKE '%127.0.0.1%' AND ( DATE(create_time) >= '".$check_date."')";   
          $select->where($where_query)
                  ->limit(8000)->offset(6000);  //14000
          $resultSet = $this->tableGateway->selectWith($select);
          //$row = $resultSet->current();
          return $resultSet;
          
//          $arrData = array();
//          foreach ($resultSet as $result) {
//              $arrData[] = $result;
//          }
//          return $arrData;

      }
    public function getAllUsersForIpAgain($offset) {

        $check_date_from = "2014-08-01";
        $check_date_to = "2014-12-18";
        $select = $this->tableGateway->getSql()->select();
        $where_query = " ip IS NOT NULL AND ip NOT LIKE '%127.0.0.1%' AND ( DATE(create_time) >= '" . $check_date_from . "') AND ( DATE(create_time) <= '" . $check_date_to . "')";
        $select->where($where_query)
                ->limit(1000);        
        $select->offset($offset);
        $resultSet = $this->tableGateway->selectWith($select);        
        //return count($resultSet)?$resultSet:false; 
      }
      
      //public function checkLogin-This function checks login based on emailId and password
    public function checkLogin($user) {
        $password = md5($user['userPassword']);
        $selectEmail = $this->tableGateway->getSql()->select()
                ->where('email="' . stripslashes($user['userEmail']) . '" ')
                ->where('password="' . $password . '"');

        $selectUser = $this->tableGateway->getSql()->select()
                ->where('username="' . stripslashes($user['userEmail']) . '"')
                ->where('password="' . $password . '"');

        $resultSetEmail = $this->tableGateway->selectWith($selectEmail);
        $resultSetUser = $this->tableGateway->selectWith($selectUser);
        $rowEmail = $resultSetEmail->current();
        $rowUser = $resultSetUser->current();
        if (isset($rowEmail->user_id))
            return $rowEmail;
        else if (isset($rowUser->user_id))
            return $rowUser;
    }
    public function getUserbyemail($useremail) {
        $select = $this->tableGateway->getSql()->select();
        $select->where("user.email='$useremail'");
        $resultSet = $this->tableGateway->selectWith($select);
        $dataArray = array();
        foreach ($resultSet as $val) {
            $dataArray = $val;
        }
        return $dataArray;
    }
    
    public function getUserByMobile($mobile) {
        $select = $this->tableGateway->getSql()->select();
        $select->where("user.mobile='$mobile'");
        $resultSet = $this->tableGateway->selectWith($select);
        $dataArray = array();
        foreach ($resultSet as $val) {
            $dataArray = $val;
        }
        return $dataArray;
    }
    
    
    /*
     * Author:Suraj
     */
    
    public function getUserList($params,$type='') {
        
        $where_query = "1 ";
        $join = 'left';
        if($params['userType'] == 'paid_user') {
            $join = 'inner';
            $where_query .=  " AND tup.status IN (1,2,3)";  
        } else if($params['userType'] == 'free_user') {
            $join = 'left';
            $where_query .=  " AND (select count(*) from t_user_package tup1 where tup1.user_id=tup.user_id and tup1.status IN(1,2,3)) = 0 ";  
        }           

        if($params['dateType'] == 'purchase_date') {
            if(!empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND ( tut.purchase_date >= '".$params['fromDate']." 00:00:00' AND tut.purchase_date <= '".$params['toDate'] . " 23:59:59')";   
            }
        } else if($params['dateType'] == 'registration_date') {
            if($params['fromDate'] !='' && $params['toDate'] !=''){
                $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
            }
        } else if($params['dateType'] == 'refund_date') {
            if($params['fromDate'] !='' && $params['toDate'] !=''){
                $where_query .=  " AND ( utr.refund_date >= '".$params['fromDate']." 00:00:00' AND utr.refund_date <= '".$params['toDate'] . " 23:59:59')";   
            }
        }
        
        if(!empty($params['keyword'])) {
            if($params['searchType'] == 'case') {
                $where_query .=  " AND (user.email LIKE '%" . $params['keyword']. "%' OR ";
                $where_query .=  "user.display_name LIKE '%" . $params['keyword']. "%' OR ";
                $where_query .=  "user.username LIKE '" . $params['keyword']. "' OR ";
                //$where_query .=  "ssd.school_name LIKE '%" . $params['keyword']. "%' OR ";
                $where_query .=  "tdc.coupon_code LIKE '" . $params['keyword']. "' OR ";
                $where_query .=  "tut.order_id LIKE '" . $params['keyword']. "') "; 
            } else if($params['searchType'] == 'exact') {
                $where_query .=  " AND (user.email = '" . $params['keyword']. "' OR ";
                $where_query .=  "user.display_name = '" . $params['keyword']. "' OR ";
                $where_query .=  "user.username = '" . $params['keyword']. "' OR ";
                //$where_query .=  "ssd.school_name = '" . $params['keyword']. "' OR ";
                $where_query .=  "tdc.coupon_code = '" . $params['keyword']. "' OR ";
                $where_query .=  "tut.order_id = '" . $params['keyword']. "') "; 
            }
        }
        
        if(!empty($params['mobile_number'])) {
            /*if($params['searchType'] == 'case') {
                $where_query .=  "user.mobile = '+91-" . $params['mobile_number']. "' OR ";
            } else if($params['searchType'] == 'exact') {
               
            }*/
            $where_query .=  " AND user.mobile = '+91-" . $params['mobile_number']. "' AND ";
        }
        if(!empty($params['school_name'])) {
            /*if($params['searchType'] == 'case') {
                $where_query .=  "user.school_name LIKE '%" . $params['school_name']. "%' AND ";
            } else if($params['searchType'] == 'exact') {
               
            }*/
            $where_query .=  " AND user.school_name = '" . $params['school_name']. "' AND ";
        }
        $where_query = rtrim($where_query,'OR ');
        $where_query = rtrim($where_query,'AND ');
        if(!empty($params['pkg_payment_details'])){
             $where_query .=  " AND (tut.pkg_payment_details = '" . $params['pkg_payment_details']. "')"; 
        }

        if(!empty($params['subStatusVal'])){
            if($params['subStatusVal'] == 'Active') {
                $where_query .=  " AND (tup.status = 1)"; 
            } else if($params['subStatusVal'] == 'Expired') {
                $where_query .=  " AND tup.valid_till < CURDATE() AND (tup.status = 3)";   
            } else if($params['subStatusVal'] == 'Deactivated') {
                $where_query .=  " AND (tup.status = 0)";   
            }
        }

        if(!empty($params['packageType'])){
             if($params['packageType'] != '0'){
                $where_query .=  " AND (tp.package_type = '" . $params['packageType'] . "')"; 
             }
        }
        
        /*if(!empty($params['packageExpireStatus'])){
             if($params['packageExpireStatus'] != '0'){
                 if($params['packageExpireStatus'] == 'week') {
                     $oneWeekDays = date('Y-m-d', strtotime('+ 7 days'));
                     $where_query .=  " AND DATE(tup.valid_till) <= '" . $oneWeekDays . "' AND tup.valid_till >= CURDATE()"; 
                 } else if($params['packageExpireStatus'] == 'halfmonth') {
                     $halfMonthDays = date('Y-m-d', strtotime('+ 15 days'));
                     $where_query .=  " AND DATE(tup.valid_till) <= '" . $halfMonthDays . "' AND tup.valid_till >= CURDATE()"; 
                 } else if($params['packageExpireStatus'] == 'month') {
                     $aMonthDays = date('Y-m-d', strtotime('+ 30 days'));
                     $where_query .=  " AND DATE(tup.valid_till) <= '" . $aMonthDays . "' AND tup.valid_till >= CURDATE()";
                 } else if($params['packageExpireStatus'] == 'expired') {
                     $where_query .=  " AND tup.valid_till < CURDATE() AND (tup.status = 0)"; 
                 }
             }
        }*/
        
        if($params['fromExDate'] !='' && $params['toExDate'] !=''){
            $where_query .=  " AND ( tup.valid_till >= '".$params['fromExDate']." 00:00:00' AND tup.valid_till <= '".$params['toExDate'] . " 23:59:59')";   
        }
       try{
        $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'email','display_name','mobile','school_name', 'address','city','user_type_id', 'username', 'create_time'));
        $select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array('valid_till','discount_amount','total_package_price'=>'package_price','quantity_ids'=>'user_package_id','paid_price'=> 'paid_amount', 'subscription_status' => 'status', 'is_switched'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array('syllabus_id'), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name','class_name'=>'class','board_name'=>'board','status' => 'is_active', 'package_category','price'), 'left')
                //->join(array("tuup" => "t_user_unsubscribe_package"), "tuup.user_package_id = tup.user_package_id", array('user_package_id','status'), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','order_id','purchase_date'=>new Expression('DATE(purchase_date)'),'transaction_discount','pkg_payment_details','purchaser_id'), 'left')
                ->join(array("ts" => "t_state"), "ts.state_id = user.state_id", array('state_name'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('*'), 'left')
                ->join(array("tu" => "user"), new Expression("tut.purchaser_id = tu.user_id"), array('parent_mobile'=>'phone'), 'left')  
                ->join(array('in_study'  => 'invoices_study') , new Expression('tut.order_id=in_study.order_id AND tut.transaction_product_type="study"'),array("study_invoice_id"=>'id'),'left') 
                ->join(array('in_tab'  => 'invoices_tablet') , new Expression('tut.order_id=in_tab.order_id AND tut.transaction_product_type="tablet"'),array("tab_invoice_id"=>'id'),'left')
                ->join(array('in_sdcard'  => 'invoices_sdcard') , new Expression('tut.order_id=in_sdcard.order_id AND tut.transaction_product_type="sdcard"'),array("sdcard_invoice_id"=>'id'),'left') 
                ->join(array('in_td'  => 'invoices_testing_demo') , new Expression('tut.order_id=in_td.order_id'),array("invoice_id"=>'id'),'left')
                ->join(array('utr'  => 'user_transaction_refund') , new Expression('utr.transaction_id=tut.transaction_id'),array("refund_date"),'left')
                ->join(array('r1' => 'resource_rack'), 'r1.rack_id=user.board_id', array("board_id"=>'rack_id'), 'left')
                ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                ->join(array("cb" => 'custom_board'), "cb.custom_board_id=cbr.custom_board_id", array('board_name' => 'board_name'), 'left');
            $select->join(array('r2' => 'resource_rack'), 'r2.rack_id=user.class_id', array("class_ids"=>"rack_id"), 'left');
            $select->join(array('rn2' => 'rack_name'), 'rn2.rack_name_id=r2.rack_name_id', array('class_name' => 'name'), 'left');
            

        $select->where($where_query);
        if(isset($params['invoice_input']) && $params['invoice_input'] !='')
          {
              $select->where('(in_study.id="'.$params['invoice_input'].'" OR in_tab.id= "'.$params['invoice_input'].'" OR in_sdcard.id= "'.$params['invoice_input'].'" OR in_td.id= "'.$params['invoice_input'].'" )');
          }
        if($type!='export') { 
            $select->group(array("user.user_id", "tup.user_package_id"));  
        } 

        $select->order("tut.transaction_id DESC"); 

        /*
         ->where("tut.order_id IS NOT NULL")
        ->where("tut.coupon_id!=1")
        ->group("tup.transaction_id")
        ->having("paid_price>=0")
//            ->order("tut.purchase_date")
        ->order("tut.transaction_id DESC");
         */
        $sql = $this->tableGateway->getSql();
        //echo $sql->getSqlstringForSqlObject($select); die;
        
        $resultSet = $this->tableGateway->selectWith($select);
        
        //echo '<pre>'; print_r($resultSet); exit;
        //echo '<pre>'; print_r($resultSet); exit;
       }catch(Exception $e){
           echo $e;
       }
//        echo '<pre>';
//        print_r($resultSet);die;
        $resultSet->buffer();
        //echo '<pre>'; print_r($resultSet);
        //echo count($resultSet); die;
        return $resultSet;
    }
    

    public function getcountrywiseRegisteredUser($params){
     
        $createdDate = date('d-m-Y');
        if (!empty($params['fromdate']) && $params['fromdate'] != '' && !empty($params['todate']) && $params['todate'] != '') {
            $fromdate = $params['fromdate'];
            $fromdate = date('Y-m-d', strtotime($fromdate));
            $todate = $params['todate'];
            $todate = date('Y-m-d', strtotime($todate));
            $where = "user.create_time >= '". $fromdate." 00:00:00' AND user.create_time <= '". $todate ." 23:59:59'";
        }else{
            $fromdate = date('Y-m-d', strtotime($createdDate));
            $todate = date('Y-m-d', strtotime($createdDate));
            $where = "user.create_time >= '". $fromdate." 00:00:00' AND user.create_time <= '". $todate." 23:59:59'";
        }

        if (!empty($params['total']) && $params['total'] !='') {
            $limit = $params['total'];
        }else{
            $limit = '';
        }

        $len = $limit;
        $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'state_id','country_id','user_type_id','TotalRecords' => new \Zend\Db\Sql\Expression('COUNT(*)')))    
             ->join(array("up" => 'user_provider'), "user.user_id = up.user_id", array('provider'),'left')
             ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
             ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left') 
             ->where($where)
             ->group("state_id")
             ->order('TotalRecords DESC');
     
        if(!empty ($limit)) {
            $select->limit((int) $len);
        }
        
        $resultSet = $this->tableGateway->selectWith($select);  
        
        //print_r($resultSet); die;
        return $resultSet;
    }
    

    public function getSubscriptionReportData($params = array()) {
        //echo "<pre>"; print_r($params); die;         

        $where_query = "1";

        if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( tut.purchase_date >= '".$params['fromDate']." 00:00:00' AND tut.purchase_date <= '".$params['toDate'] . " 23:59:59')";   
        }
        
        $group_by = 'tut.transaction_id';
        if(array_key_exists('state', $params)) {
            $group_by = array('user.country_id', 'user.state_id', 'tut.transaction_id');
        }
        
        $order_by = 'tut.transaction_id DESC';
        if(array_key_exists('state', $params)) {
            $order_by = array("TotalRecords DESC", "tut.transaction_id DESC");
        }
        
        if(!empty($params['currencyVal'])){
             if($params['currencyVal'] != '0'){
                $where_query .=  " AND (tup.currency_type = '" . $params['currencyVal'] . "')"; 
             }
        }
        
        if(!empty($params['paymentType'])){ 
             if($params['modeType'] != 'offline') {
                 if($params['paymentType'] == 'netbanking') {
                    $where_query .=  " AND (tut.pkg_payment_type IN ('ebs', 'CCAVENUE'))"; 
                 }

                 if($params['paymentType'] == 'creditcard') {
                    $where_query .=  " AND (tut.pkg_payment_type IN ('CCAVENUE', 'migs'))"; 
                 }
                 
                 if($params['paymentType'] == '0') {
                    $where_query .=  " AND (tut.pkg_payment_type IN ('CCAVENUE', 'ebs', 'migs', 'FREE USER', 'cod'))"; 
                 }
             }
        }

        if(!empty($params['modeType'])){
            if($params['modeType'] == 'online') {
                $where_query .=  " AND (tut.pkg_payment_type != 'offline')";
            } else if($params['modeType'] == 'offline') {
                $where_query .=  " AND (tut.pkg_payment_type = 'offline')"; 
            }
        }
        
        $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'TotalRecords' => new \Zend\Db\Sql\Expression('COUNT(*)')));
        $select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array('price'=>new \Zend\Db\Sql\Expression('SUM(package_price)') , 'discount_amount'=>new \Zend\Db\Sql\Expression('SUM(discount_amount)') , 'currency_type','paid_price'=> new \Zend\Db\Sql\Expression('SUM(paid_amount)')  ), 'left')
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array('id'), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_type','class_name'=>'class','board_name'=>'board'), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','pkg_payment_details','pkg_payment_type','transaction_id', 'transaction_amount', 'transaction_discount', 'transaction_total_refund_amount', 'transaction_refund_type'), 'left')
                ->join(array("ts" => "t_state"), "ts.state_id = user.state_id", array('state_id','state_name'), 'left')
                ->join(array("tc" => "t_country"), "tc.country_id = user.country_id", array('country_id','country_name'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('discount_percent', 'coupon_type'), 'left')
                ->join(array("tu" => "user"), new Expression("tut.purchaser_id = tu.user_id"), array('parent_name'=>'display_name'), 'left')
                ->join(array("opt" => "offline_payment_transaction"), new Expression("opt.user_transaction_id = tut.transaction_id"), array('payment_mode'=>'payment_mode'), 'left')
                ->where($where_query)
                ->where("tut.order_id IS NOT NULL")
                //->where("tut.coupon_id!=1")
                ->where("tut.pkg_payment_details='Processed'")
                ->where("((tdc.coupon_type!='test' AND tdc.coupon_type!='demo' AND tdc.coupon_type!='promotional') OR tdc.coupon_type IS NULL)")
                ->where("tup.status IN(1,2,3)")
                ->group($group_by)
                ->having("paid_price>=0")
                ->order($order_by); 
        
        $resultSet = $this->tableGateway->selectWith($select); 
        //echo '<pre>';
        //print_r($resultSet);//die;
        $resultSet->buffer();
        return $resultSet;
    }
    
    public function getSubscriptionData($params = array()) {
        //echo "<pre>"; print_r($params); die;         

        $where_query = "1";

        if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( tut.purchase_date >= '".$params['fromDate']." 00:00:00' AND tut.purchase_date <= '".$params['toDate'] . " 23:59:59')";   
        }
        
        $group_by = 'tut.transaction_id';
        if(array_key_exists('state', $params)) {
            $group_by = array('user.country_id', 'user.state_id', 'tut.transaction_id');
        }
        
        $order_by = 'tut.transaction_id DESC';
        if(array_key_exists('state', $params)) {
            $order_by = array("TotalRecords DESC", "tut.transaction_id DESC");
        }
        
        if(!empty($params['currencyVal'])){
             if($params['currencyVal'] != '0'){
                $where_query .=  " AND (tup.currency_type = '" . $params['currencyVal'] . "')"; 
             }
        }
        
        if(!empty($params['paymentType'])){ 
             if($params['modeType'] != 'offline') {
                 if($params['paymentType'] == 'netbanking') {
                    $where_query .=  " AND (tut.pkg_payment_type IN ('ebs', 'CCAVENUE'))"; 
                 }

                 if($params['paymentType'] == 'creditcard') {
                    $where_query .=  " AND (tut.pkg_payment_type IN ('CCAVENUE', 'migs'))"; 
                 }
                 
                 if($params['paymentType'] == '0') {
                    $where_query .=  " AND (tut.pkg_payment_type IN ('CCAVENUE', 'ebs', 'migs', 'FREE USER', 'cod'))"; 
                 }
             }
        }

        if(!empty($params['modeType'])){
            if($params['modeType'] == 'online') {
                $where_query .=  " AND (tut.pkg_payment_type != 'offline')";
            } else if($params['modeType'] == 'offline') {
                $where_query .=  " AND (tut.pkg_payment_type = 'offline')"; 
            }
        }
        
        $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'email', 'display_name', 'phone', 'address','city','user_type_id','parent_id','username', 'TotalRecords' => new \Zend\Db\Sql\Expression('COUNT(*)')));
        //$select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array('package_id', 'price'=>new \Zend\Db\Sql\Expression('SUM(package_price)') , 'discount_amount'=>new \Zend\Db\Sql\Expression('SUM(discount_amount)') ,'status','user_package_id','valid_till', 'currency_type','paid_price'=>new \Zend\Db\Sql\Expression('SUM(package_price) - SUM(discount_amount)')  ), 'left')
        $select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array('package_id', 'price'=>new \Zend\Db\Sql\Expression('SUM(package_price)') , 'discount_amount'=>new \Zend\Db\Sql\Expression('SUM(discount_amount)') ,'status','user_package_id','valid_till', 'currency_type','paid_price'=>new \Zend\Db\Sql\Expression('SUM(paid_amount)')  ), 'left')
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array('id','syllabus_id'), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name','package_type','class_name'=>'class','board_name'=>'board'), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','order_id','purchase_date'=>'purchase_date','pkg_payment_details','pkg_payment_type','purchaser_id','transaction_id', 'transaction_amount', 'transaction_discount'), 'left')
                ->join(array("ts" => "t_state"), "ts.state_id = user.state_id", array('state_id','state_name'), 'left')
                ->join(array("tc" => "t_country"), "tc.country_id = user.country_id", array('country_id','country_name'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("tu" => "user"), new Expression("tut.purchaser_id = tu.user_id"), array('parent_name'=>'display_name','parent_email'=>'email','parent_address'=>'address','parent_mobile'=>'phone'), 'left')
                ->join(array("opt" => "offline_payment_transaction"), new Expression("opt.user_transaction_id = tut.transaction_id"), array('payment_mode'=>'payment_mode','login_id'=>'login_id'), 'left')
                ->where($where_query)
                ->where("tut.order_id IS NOT NULL")
                //->where("tut.coupon_id!=1")
                ->where("tut.pkg_payment_details='Processed'")
                //->where("tdc.coupon_type!='test'")
                ->where("tup.status IN(1,2,3)")
                ->group($group_by)
                ->having("paid_price>=0")
                ->order($order_by); 
        
        $resultSet = $this->tableGateway->selectWith($select); 
        //echo '<pre>';
        //print_r($resultSet);//die;
        $resultSet->buffer();
        return $resultSet;
    }
    
    public function getPaidUserData($params = array()) {
        //echo "<pre>"; print_r($params); die;         

       $where_query = "1";
       
        $join = 'left';
        if($params['userType'] == 'paid_user') {
            $join = 'inner';
        } else if($params['userType'] == 'free_user') {
            $join = 'left';
        }

        if(!empty($params['dataSchoolCode']) || !empty($params['dataEmail'])) {
            if(!empty($params['dataEmail'])){
                $where_query .=  " AND (user.email = '". $params['dataEmail'] ."')";               
            }

            if(!empty($params['dataSchoolCode'])){
                $where_query .=  " AND (ssd.school_code = '". $params['dataSchoolCode'] ."')";               
            }
        } else {
            if($params['dateSelect'] == 'create' && !empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";               
            }

            if($params['dateSelect'] == 'purchase' && !empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND ( tut.purchase_date >= '".$params['fromDate']." 00:00:00' AND tut.purchase_date <= '".$params['toDate'] . " 23:59:59')";
            }

            if(empty($params['dateSelect']) && !empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND (( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59') OR ( tut.purchase_date >= '".$params['fromDate']." 00:00:00' AND tut.purchase_date <= '".$params['toDate'] . " 23:59:59'))";               
            }
        }
        
        
        
        //echo $where_query; die;
        if($params['dataType'] == 'first') {
         $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";   
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email'));
         $select->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'login_count') {
         $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";   
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email'));
         $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'time_count') {
         $where_query.= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";      
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email'));
         $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('total_time' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'learn_duration') {            
         $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";         
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email'));
         $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('learn_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                 ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Learn'"), array(), 'right')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'practice_duration') {            
         $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";         
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email'));
         $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('practice_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                 ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Practice'"), array(), 'right')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'scheduled_count') {
             $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";         
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL';
             $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email'));
             $select->join(array("tlp" => 't_lesson_plan'), "tlp.user_id=user.user_id", array('schedule_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                    ->join(array("rr1" => 'resource_rack'), "tlp.package_usage_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                    ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                    ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                    ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                    ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                    ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                    ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                    ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                    ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                    ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                    ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                    ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left') 
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');
        }
        
        if($params['dataType'] == 'mentor_count') {
             $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";         
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
             $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
             $select->join(array("tsm" => 't_student_and_mentor'), "tsm.student_id=user.user_id", array('mentor_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                     ->join(array("rr1" => 'resource_rack'), "tsm.subject_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                     ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                     ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                     ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                     ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                     ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                     ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                     ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                     ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                     ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                     ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                     ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')  
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');
        }
        
        if($params['dataType'] == 'group_member_count') {
         $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";            
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email'));
         $select->join(array("tug" => 't_user_groups'), "tug.user_id=user.user_id", array('group_member_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left') 
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'note_count') {
             $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";         
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
             $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
             $select->join(array("tn" => 't_notes'), "tn.user_id=user.user_id", array('notes_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                     ->join(array("rr1" => 'resource_rack'), "tn.chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                     ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                     ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                     ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                     ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                     ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                     ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                     ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                     ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                     ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                     ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                     ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')  
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');
        }
        
        if($params['dataType'] == 'test_attempt_count') {
             $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";         
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
             $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
             $select->join(array("qs" => 'quiz_set'), "qs.user_id=user.user_id", array('set_status','test_attempt_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                     ->join(array("qsc" => 'quiz_set_chapter'), "qs.set_id=qsc.set_id", array('board_classs_subject_chapter_id'), 'left')
                    ->join(array("rr1" => 'resource_rack'), "qsc.board_classs_subject_chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                    ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                    ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                    ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                    ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                    ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                    ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                    ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                    ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                    ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                    ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                    ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')  
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group(array('user.user_id', 'qs.set_status'));
            
        }
        
        if($params['dataType'] == 'location_count') {
             $group_by = array('user.user_id');
             if($params['dateSelect'] == 'purchase') {
                 //$group_by = array('tut.transaction_id');
             }
             $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";         
             $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username'));
             $select->join(array("tc" => "t_country"), "tc.country_id = user.country_id", array('country_id','country_name'), 'inner')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')   
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group($group_by);
        }
        
        $resultSet = $this->tableGateway->selectWith($select); 
        
        $resultSet->buffer();
        return $resultSet;
    }
    
    public function getPaidUserDetailsData($params = array()) {
        
        //echo '<pre>';print_r($params); echo '</pre>';die('macro Die');
        
        $where_query = "1";
        
        $join = 'left';
        if($params['userType'] == 'paid_user') {
            $join = 'inner';
        } else if($params['userType'] == 'free_user') {
            $join = 'left';
        }
        
        if(!empty($params['userIds'])){
            $where_query .=  " AND (user.user_id IN (" . $params['userIds'] . "))";
            //$where_query .=  " (u.user_id IN (" . $params['userIds'] . "))"; 
        }
        
        if(!empty($params['dataSchoolCode']) || !empty($params['dataEmail'])) {
            if(!empty($params['dataEmail'])){
                $where_query .=  " AND (user.email = '". $params['dataEmail'] ."')";               
            }

            if(!empty($params['dataSchoolCode'])){
                $where_query .=  " AND (ssd.school_code = '". $params['dataSchoolCode'] ."')";               
            }
        } else {
        
            if($params['dateSelect'] == 'create' && !empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                //$where_query .=  " AND ( DATE(u.create_time) >= '".$params['fromDate']."' AND DATE(u.create_time) <= '".$params['toDate'] . "')";   
            }

            if($params['dateSelect'] == 'purchase' && !empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND ( tut.purchase_date >= '".$params['fromDate']." 00:00:00' AND tut.purchase_date <= '".$params['toDate'] . " 23:59:59')";
            }
        }        
        
        
        if($params['dataType'] == 'time_count') {
            
            if($params['dateSelect'] == '0' && !empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
            }            
            
         $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";  
         
         if(!empty($params['dataEmail'])){
            if(!empty($params['dataClass'])){
                //$where_query .=  " AND ( tp.class = '".$params['dataClass']."' OR rn1.name = '".$params['dataClass'] . "')";
                $where_query .=  " AND ( rn4.name = '".$params['dataClass']."' OR rn5.name = '".$params['dataClass'] . "' OR rn3.name = '".$params['dataClass'] . "')";
            }

            if(!empty($params['dataBoard'])){
                //$where_query .=  " AND ( tp.board = '".$params['dataBoard']."' OR rn1.name = '".$params['dataBoard'] . "')";
                $where_query .=  " AND ( cb.board_name = '".$params['dataBoard']."' OR rn4.name = '".$params['dataBoard']."' OR rn5.name = '".$params['dataBoard'] . "' OR rn3.name = '".$params['dataBoard'] . "')";
            }
             $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr4.rack_type_id=3 THEN rn4.name WHEN rr5.rack_type_id=3 THEN rn5.name ELSE rn3.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr4.rack_type_id=2 THEN rn4.name WHEN rr5.rack_type_id=2 THEN rn5.name ELSE rn3.name END')));
             $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('total_time' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                    ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                    ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                    ->join(array("rr1" => 'resource_rack'), "pr.board_container_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                    ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                    ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                    ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                    ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                    ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                    ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                    ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                    ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                    ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                    ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                    ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')    
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group(array('user.user_id', 'pr.board_container_id'));    
         } else {
             if(!empty($params['dataClass'])){
                $where_query .=  " AND ( tp.class = '".$params['dataClass']."' OR rn.name = '".$params['dataClass'] . "')";
             }

             if(!empty($params['dataBoard'])){
                $where_query .=  " AND ( cb.board_name = '".$params['dataBoard']."' OR tp.board = '".$params['dataBoard']."' OR rn1.name = '".$params['dataBoard'] . "')";
             }
             $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rn.name IS NULL THEN tp.class ELSE rn.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rn1.name IS NULL THEN tp.board ELSE rn1.name END')));
             $select->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                    ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array(), 'left')
                    ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                    ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                    ->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('total_time' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                    ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                    ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')    
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');
         }
         
         if(!empty($params['dataClass']) && !empty($params['dataBoard'])){
            $select->order("pr.total_time DESC");
         }
            
        }
        
        if($params['dataType'] == 'login_count') {
         $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";     
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rn.name IS NULL THEN rn.name ELSE rn.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')));
         $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                 ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                 ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                 ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array(), 'left')
                 ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                 ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                 ->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')    
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
            
        }
        
        if($params['dataType'] == 'learn_duration') {
            
            if($params['dateSelect'] == '0' && !empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
            }            
            
         $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";  
         
         if(!empty($params['dataEmail'])){
            if(!empty($params['dataClass'])){
                $where_query .=  " AND ( rn4.name = '".$params['dataClass']."' OR rn5.name = '".$params['dataClass'] . "' OR rn3.name = '".$params['dataClass'] . "')";
            }

            if(!empty($params['dataBoard'])){
                $where_query .=  " AND ( cb.board_name = '".$params['dataBoard']."' OR rn4.name = '".$params['dataBoard']."' OR rn5.name = '".$params['dataBoard'] . "' OR rn3.name = '".$params['dataBoard'] . "')";
            }
             $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr4.rack_type_id=3 THEN rn4.name WHEN rr5.rack_type_id=3 THEN rn5.name ELSE rn3.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr4.rack_type_id=2 THEN rn4.name WHEN rr5.rack_type_id=2 THEN rn5.name ELSE rn3.name END')));
             $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('learn_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                    ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                    ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Learn'"), array(), 'right')
                    ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                    ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                    ->join(array("rr1" => 'resource_rack'), "pr.board_container_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                    ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                    ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                    ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                    ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                    ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                    ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                    ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                    ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                    ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                    ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                    ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')    
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group(array('user.user_id', 'pr.board_container_id'));    
         } else {
             
             if(!empty($params['dataClass'])){
                $where_query .=  " AND ( tp.class = '".$params['dataClass']."' OR rn.name = '".$params['dataClass'] . "')";
             }

             if(!empty($params['dataBoard'])){
                $where_query .=  " AND ( cb.board_name = '".$params['dataBoard']."' OR tp.board = '".$params['dataBoard']."' OR rn1.name = '".$params['dataBoard'] . "')";
             }
             $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rn.name IS NULL THEN rn.name ELSE rn.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rn1.name IS NULL THEN tp.board ELSE rn1.name END')));
             $select->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                     ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array(), 'left')
                     ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                     ->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('learn_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                    ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                     ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Learn'"), array(), 'right')
                    ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                    ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')     
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');
         }
         
         if(!empty($params['dataClass']) && !empty($params['dataBoard'])){
            $select->order("pr.learn_duration DESC");
         }
            
        }
        
        if($params['dataType'] == 'practice_duration') {
            
            if($params['dateSelect'] == '0' && !empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
            }
            
          $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";  
          
          if(!empty($params['dataEmail'])){
              
            if(!empty($params['dataClass'])){
                $where_query .=  " AND ( rn4.name = '".$params['dataClass']."' OR rn5.name = '".$params['dataClass'] . "' OR rn3.name = '".$params['dataClass'] . "')";
            }

            if(!empty($params['dataBoard'])){
                $where_query .=  " AND ( cb.board_name = '".$params['dataBoard']."' OR rn4.name = '".$params['dataBoard']."' OR rn5.name = '".$params['dataBoard'] . "' OR rn3.name = '".$params['dataBoard'] . "')";
            }
             $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr4.rack_type_id=3 THEN rn4.name WHEN rr5.rack_type_id=3 THEN rn5.name ELSE rn3.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr4.rack_type_id=2 THEN rn4.name WHEN rr5.rack_type_id=2 THEN rn5.name ELSE rn3.name END')));
             $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('practice_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                    ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                    ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Practice'"), array(), 'right')
                    ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                    ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                    ->join(array("rr1" => 'resource_rack'), "pr.board_container_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                    ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                    ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                    ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                    ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                    ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                    ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                    ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                    ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                    ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                    ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                    ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')    
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group(array('user.user_id', 'pr.board_container_id'));    
         } else {
             
             if(!empty($params['dataClass'])){
                $where_query .=  " AND ( tp.class = '".$params['dataClass']."' OR rn.name = '".$params['dataClass'] . "')";
             }

             if(!empty($params['dataBoard'])){
                $where_query .=  " AND ( cb.board_name = '".$params['dataBoard']."' OR tp.board = '".$params['dataBoard']."' OR rn1.name = '".$params['dataBoard'] . "')";
             }
             
             $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rn.name IS NULL THEN rn.name ELSE rn.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rn1.name IS NULL THEN rn1.name ELSE rn1.name END')));
             $select->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                     ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array(), 'left')
                     ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                     ->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('practice_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                    ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                     ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Practice'"), array(), 'right')
                    ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                    ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')     
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');
         }
         
         if(!empty($params['dataClass']) && !empty($params['dataBoard'])){
            $select->order("pr.practice_duration DESC");
         }
            
        }
        
        if($params['dataType'] == 'scheduled_count') {
          $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";    
         $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tlp.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
         $select->join(array("tlp" => 't_lesson_plan'), "tlp.user_id=user.user_id", array('schedule_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                ->join(array("rr1" => 'resource_rack'), "tlp.package_usage_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                ->join(array("cbr" => 'custom_board_rack'), "tlp.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
            
        }
        
        if($params['dataType'] == 'mentor_count') {
          $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";      
         $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
         $select->join(array("tsm" => 't_student_and_mentor'), "tsm.student_id=user.user_id", array('mentor_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                 ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                 ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                 ->join(array("rr1" => 'resource_rack'), "tsm.subject_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                 ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                 ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                 ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                 ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                 ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                 ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                 ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                 ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                 ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                 ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                 ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                 ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
            
        }
        
        if($params['dataType'] == 'group_member_count') {
           $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";     
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rn.name IS NULL THEN rn.name ELSE rn.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')));
         $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                 ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array(), 'left')
                 ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                 ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                 ->join(array("tug" => 't_user_groups'), "tug.user_id=user.user_id", array('group_member_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                 ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')       
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
            
        }
        
        if($params['dataType'] == 'note_count') {
         $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";    
         $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tn.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
         $select->join(array("tn" => 't_notes'), "tn.user_id=user.user_id", array('notes_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                 ->join(array("cbr" => 'custom_board_rack'), "tn.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                 ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                 ->join(array("rr1" => 'resource_rack'), "tn.chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                 ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                 ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                 ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                 ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                 ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                 ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                 ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                 ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                 ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                 ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                 ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                 ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')       
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
            
        }
        
        if($params['dataType'] == 'test_attempt_count') {
         $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)"; 
         $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
         $select->join(array("qs" => 'quiz_set'), "qs.user_id=user.user_id", array('set_status','test_attempt_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                 ->join(array("qsc" => 'quiz_set_chapter'), "qs.set_id=qsc.set_id", array('board_classs_subject_chapter_id'), 'left')
                ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                ->join(array("rr1" => 'resource_rack'), "qsc.board_classs_subject_chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')        
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group(array('user.user_id', 'qs.set_status'));
            
        }
        
        if($params['dataType'] == 'location_count') {
         
            $group_by = array();
            if(!empty($params['dataState'])){
                $where_query .= ' AND (LOWER(ts.state_name) = "'. strtolower($params['dataState']) .'")';
                $group_by = array('user.user_id');
            } else {
                $group_by = array('user.user_id');
            }
            
         $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)"; 
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username','city', 'TotalRecords' => new \Zend\Db\Sql\Expression('COUNT(*)')));
         $select->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tc" => "t_country"), "tc.country_id = user.country_id", array('country_id','country_name'), 'left')
                ->join(array("ts" => "t_state"), "ts.state_id = user.state_id", array('state_id','state_name'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')        
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group($group_by)
                ->order('TotalRecords DESC');
            
        }
        
        $resultSet = $this->tableGateway->selectWith($select);
        //echo '<pre>';
        //print_r($resultSet);die;
        $resultSet->buffer();
        return $resultSet;
        
    }
    

    
    public function getclasswiseRegisteredUser($params){
        
        $createdDate = date('d-m-Y');
        if (!empty($params['fromdate']) && $params['fromdate'] != '' && !empty($params['todate']) && $params['todate'] != '') {
            $fromdate = $params['fromdate'];
            $fromdate = date('Y-m-d', strtotime($fromdate));
            $todate = $params['todate'];
            $todate = date('Y-m-d', strtotime($todate));
            $where = " user.create_time >= '". $fromdate. " 00:00:00' AND user.create_time <='". $todate. " 23:59:59'";
        }else{
            $fromdate = date('Y-m-d', strtotime($createdDate));
            $todate = date('Y-m-d', strtotime($createdDate));
            $where = " user.create_time >= '". $fromdate." 00:00:00' AND user.create_time <='". $todate." 23:59:59'";
        }
        
        if(!empty($params['school_name']) && $params['school_name'] !="") {
            $where .= ' AND LOWER(user.school_name) = "'. strtolower($params['school_name']) .'"';
        }
        
        if(!empty($params['state_name']) && $params['state_name'] !="") {
            if($params['state_name'] != '-') {
                $where .= ' AND LOWER(ts.state_name) = "'. strtolower($params['state_name']) .'"';
            } else {
                $where .= " AND ts.state_name IS NULL";
            }
        }
        
       $select = $this->tableGateway->getSql()->select()->columns(array('user_type_id', 'TotalRecords' => new \Zend\Db\Sql\Expression('COUNT(*)')));
       $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE crn.name END')), 'left')
             ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
             ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('rack_container_id', 'class'=>'rack_id'), 'left')
             ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('name'), 'left')
             ->join(array("tc" => "t_country"), "tc.country_id = user.country_id", array('country_id','country_name'), 'left')
             ->join(array("ts" => "t_state"), "ts.state_id = user.state_id", array('state_id','state_name'), 'left')
             ->where($where)
             ->group(array('user.class_id','user.user_type_id'))
             ->order('TotalRecords DESC');
        
        $resultSet = $this->tableGateway->selectWith($select);
        
        //print_r($resultSet);   die;     
        
        return $resultSet;
 }


    /*
     * Author: ankit
     * Description: get user with userid and email id - used for validating a user with id and email address
     */
    public function getUserByIdandEmail($userId, $emailId){
        $select = $this->tableGateway->getSql()->select();		
        $select->where('email="'.stripslashes($emailId).'"');
        $select->where('user_id="'.$userId.'"');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        return $row;
        
    }
//    public function getUserBy
    
    public function getSubscriptionDetailsData($params = array()) {
        //echo "<pre>"; print_r($params); die;         

        $where_query = "1";

        if(!empty($params['userIds'])){
            $where_query .=  " AND (user.user_id IN (" . $params['userIds'] . "))";
        }
        
        if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( tut.purchase_date >= '".$params['fromDate']." 00:00:00' AND tut.purchase_date <= '".$params['toDate'] . " 23:59:59')";   
        }
        
        if(!empty($params['dataFilter'])){
            $where_query .=  " AND (LOWER(tp.board) = '" . strtolower($params['dataFilter']) . "')"; 
        }
        
        if(!empty($params['filterType'])){
            $where_query .=  " AND ( tp.package_type = '" . $params['filterType'] . "')"; 
        }
        
        $group_by = 'tut.transaction_id';
        
        $order_by = 'tut.transaction_id DESC';
        
        if(!empty($params['currencyVal'])){
             if($params['currencyVal'] != '0'){
                $where_query .=  " AND (tup.currency_type = '" . $params['currencyVal'] . "')"; 
             }
        }
        
        /*if(!empty($params['paymentType'])){
             if($params['paymentType'] != '0'){
               // $where_query .=  " AND (tut.pkg_payment_type = '" . $params['paymentType'] . "')"; 
             }
        }*/
        
        if(!empty($params['paymentType'])){ 
             if($params['modeType'] != 'offline') {
                 if($params['paymentType'] == 'netbanking') {
                    $where_query .=  " AND (tut.pkg_payment_type IN ('ebs', 'CCAVENUE'))"; 
                 }

                 if($params['paymentType'] == 'creditcard') {
                    $where_query .=  " AND (tut.pkg_payment_type IN ('CCAVENUE', 'migs'))"; 
                 }
                 
                 if($params['paymentType'] == '0') {
                    $where_query .=  " AND (tut.pkg_payment_type IN ('CCAVENUE', 'ebs', 'migs', 'FREE USER', 'paytm'))"; 
                 }
             }
        }

        if(!empty($params['modeType'])){
            if($params['modeType'] == 'online') {
                $where_query .=  " AND (tut.pkg_payment_type != 'offline')"; 
            } else if($params['modeType'] == 'offline') {
                $where_query .=  " AND (tut.pkg_payment_type = 'offline')"; 
            }
        }
        
        $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'email', 'display_name', 'phone', 'address','city','user_type_id','parent_id','username', 'TotalRecords' => new \Zend\Db\Sql\Expression('COUNT(*)')));
        $select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array('package_id', 'price'=>new \Zend\Db\Sql\Expression('SUM(package_price)') , 'discount_amount'=>new \Zend\Db\Sql\Expression('SUM(discount_amount)') ,'status','user_package_id','valid_till', 'currency_type','paid_price'=> new \Zend\Db\Sql\Expression('SUM(paid_amount)')), 'left')
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array('id','syllabus_id'), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name','package_type','class_name'=>'class','board_name'=>'board', 'price'), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','order_id','purchase_date'=>'purchase_date','pkg_payment_details','pkg_payment_type','purchaser_id', 'transaction_amount', 'transaction_discount'), 'left')
                ->join(array("ts" => "t_state"), "ts.state_id = user.state_id", array('state_id','state_name'), 'left')
                ->join(array("tc" => "t_country"), "tc.country_id = ts.country_id", array('country_id','country_name'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("tu" => "user"), new Expression("tut.purchaser_id = tu.user_id"), array('parent_name'=>'display_name','parent_email'=>'email','parent_address'=>'address','parent_mobile'=>'phone'), 'left')
                ->join(array("opt" => "offline_payment_transaction"), new Expression("opt.user_transaction_id = tut.transaction_id"), array('payment_mode'=>'payment_mode','login_id'=>'login_id'), 'left')
                ->where($where_query)
                ->where("tut.order_id IS NOT NULL")
                //->where("tut.coupon_id!=1")
                ->where("tut.pkg_payment_details='Processed'")
                //->where("AND tdc.coupon_type!='test'")
                ->where("tup.status IN(1,2,3)")
                ->group($group_by)
                ->having("paid_price>=0")
                ->order($order_by); 
        
        $resultSet = $this->tableGateway->selectWith($select); 
        //echo '<pre>';
        //print_r($resultSet);die;
        $resultSet->buffer();
        return $resultSet;
    }
       
    public function getUserEngagementReportData($params = array()) {
        //echo "<pre>"; print_r($params); die;
        $where_query = "1";
        
        if($params['dateSelect'] == 'create' && !empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
        }

        if($params['dateSelect'] == 'purchase' && !empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( tut.purchase_date >= '".$params['fromDate']." 00:00:00' AND tut.purchase_date <= '".$params['toDate'] . " 23:59:59')";   
        }
        
        if($params['dateSelect'] == '0' && !empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( report.created_date >= '".$params['fromDate']." 00:00:00' AND report.created_date <= '".$params['toDate'] . " 23:59:59')";   
        }
        
        $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)"; 
        if(!empty($params['filter_type'])){
                
             if($params['filter_type'] == 'all'){ 

                $select = $this->tableGateway->getSql()->select()->columns(array('email'));
                 $select->join(array("tup" => 't_user_package'), new Expression("user.user_id=tup.user_id"), array(), 'left')
                        ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array(), 'left')
                        ->join(array("report" => "progress_report"), "user.user_id = report.user_id", array('total_time' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(report.total_time))/60')), 'inner')
                        ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')         
                        ->where($where_query)
                        ->group('user.user_id')
                        ->order('total_time');
             }

             if($params['filter_type'] == 'paid_users'){                    
               $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)"; 
                $select = $this->tableGateway->getSql()->select()->columns(array('email'));
                 $select->join(array("tup" => 't_user_package'), new Expression("user.user_id=tup.user_id"), array(), 'left')
                        ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array(), 'left')
                        ->join(array("report" => "progress_report"), "user.user_id = report.user_id", array('total_time' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(report.total_time))/60')), 'inner')
                        ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')        
                        ->where('tup.status IN(1,2,3)')
                        ->where($where_query)
                        ->group('user.user_id')
                        ->order('total_time');
             }

             if($params['filter_type'] == 'free_users'){
               $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)"; 
                $select = $this->tableGateway->getSql()->select()->columns(array('email'));
                 $select->join(array("tup" => 't_user_package'), new Expression("user.user_id=tup.user_id"), array(), 'left')
                        ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array(), 'left')
                        ->join(array("report" => "progress_report"), "user.user_id = report.user_id", array('total_time' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(report.total_time))/60')), 'inner')
                        ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')         
                        ->where('(select count(*) from t_user_package tup1 where tup1.user_id=tup.user_id and tup1.status IN(1,2,3)) = 0')
                        ->where($where_query)
                        ->group('user.user_id')
                        ->order('total_time');
             }
        }
        
        $resultSet = $this->tableGateway->selectWith($select);
//      echo '<pre>';
//      print_r($resultSet);die;
        $resultSet->buffer();
        return $resultSet;
    }
    
    
    public function getRegisteredUserReportByDate($cdate){
        $select = $this->tableGateway->getSql()->select()->columns(array('user_type_id','TotalRecords' => new \Zend\Db\Sql\Expression('COUNT(*)'),'create_date' => new \Zend\Db\Sql\Expression('DATE_FORMAT(create_time,"%Y-%m-%d")')))       
             ->join(array("up" => 'user_provider'), "user.user_id = up.user_id", array('provider'), 'left') 
             ->group(array('create_date', 'user_type_id', 'provider'))
             ->where($cdate);
        $resultSet = $this->tableGateway->selectWith($select);    
        
        
        //print_r($resultSet); die;
        $datevalueData = array();
        
        foreach($resultSet as $key=> $data) {
            $datevalueData[$data->create_date][] = array(                
                'user_type_id' => $data->user_type_id,
                'provider' => $data->provider,
                'TotalRecords' => $data->TotalRecords
            );
        }
        
        
        //echo "<pre>"; print_r($userData); die;
        
        $mainData = array();
        
        foreach($datevalueData as $key=>$usrData){
            //echo "<pre>"; print_r($usrData); 
            $tot_student = 0;
            $tot_mentor = 0;
            $tot_parent = 0;
            
            $tot_student_direct = 0;
            $tot_mentor_direct = 0;
            $tot_parent_direct = 0;

            $tot_facebook = 0;
            $tot_google = 0;
            $tot_twitter = 0;
            $totalRecords = 0;
            foreach($usrData as $result){
                //echo "<pre>"; print_r($result);           

                if($result['user_type_id'] == '1'){
                    $tot_student = $tot_student+ $result['TotalRecords'];
                }else if($result['user_type_id'] == '2'){
                    $tot_parent = $tot_parent+ $result['TotalRecords'];
                } else {
                    $tot_mentor = $tot_mentor+ $result['TotalRecords'];
                }
                
                if($result['user_type_id'] == '1' && $result['provider'] != 'facebook' && $result['provider'] != 'google' && $result['provider'] != 'twitter'){
                    $tot_student_direct = $tot_student_direct+ $result['TotalRecords'];
                }else if($result['user_type_id'] == '2'  && $result['provider'] != 'facebook' && $result['provider'] != 'google' && $result['provider'] != 'twitter'){
                    $tot_parent_direct = $tot_parent_direct+ $result['TotalRecords'];
                } else if($result['provider'] != 'facebook' && $result['provider'] != 'google' && $result['provider'] != 'twitter') {
                    $tot_mentor_direct = $tot_mentor_direct+ $result['TotalRecords'];
                }

                if($result['provider'] == 'facebook'){
                    $tot_facebook = $tot_facebook+ $result['TotalRecords'];
                }else if($result['provider'] == 'google'){
                    $tot_google = $tot_google+ $result['TotalRecords'];
                } else if($result['provider'] == 'twitter'){
                    $tot_twitter = $tot_twitter+ $result['TotalRecords'];
                }
                
                $totalRecords = $totalRecords + $result['TotalRecords'];
               
           }
           
           $countArray = array(
                'student_cnt'=>$tot_student,
                'mentor_cnt'=>$tot_mentor,
                'parent_cnt'=>$tot_parent,
                'student_cnt_direct'=>$tot_student_direct,
                'mentor_cnt_direct'=>$tot_mentor_direct,
                'parent_cnt_direct'=>$tot_parent_direct,
                'facebook_cnt'=>$tot_facebook,
                'google_cnt'=>$tot_google,
                'twitter_cnt'=>$tot_twitter,
                'TotalRecords'=> $totalRecords
           );
           
           $mainData[$key]['registation_cout'] = $countArray;
            
        }    
        //echo "<pre>"; print_r($mainData); 
        //die;
        return $mainData;
    }
    
    /*
     * Author: ankit
     * Description: update profile image
     */
    public function updateProfileImage($userId, $filename){
        $data = array();
        $data['user_photo'] = $filename;
        return $this->tableGateway->update($data, array('user_id' => $userId));
    }
    
    /*
     * Author: ajit
     * Description: get the admin users
     */
    public function getAllAdminUsers(){
        $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'email', 'display_name', 'user_status', 'mobile'));
        $select->join(array('ur'=>'user_role'), new Expression("ur.user_id = user.user_id and ur.status='Active'"), array('role_id'),'left');
        $select->join(array('r'=>'role'), "r.rid = ur.role_id", array('role_name'),'left');
        $select->where('user.user_type_id="10"');
        $select->order('email ASC');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;        
    }
    
    /*
     * Author: ajit
     * Description: get the admin users
     */
    public function getAllAdminUsersByEmail($emailIds){
        $emails = "";
        foreach($emailIds as $email) {
            $emails .= "'". $email . "',";
        }
        $emails = substr($emails, 0, strlen($emails)-1);
        $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'email', 'display_name', 'user_status', 'mobile'));
        $select->join(array('ur'=>'user_role'), new Expression("ur.user_id = user.user_id and ur.status='Active'"), array('role_id'),'left');
        $select->join(array('r'=>'role'), "r.rid = ur.role_id", array('role_name'),'left');
        $select->where('user.email IN('. $emails .')');
        $select->order('email ASC');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;        
    }
    
     /*
     * Author: ajit
     * Description: download the report based on type
     */
    public function getDownloadReportData($params, $type, $module, $tableColumns){
        
        //echo $type."===".$module;
        //echo "<pre>"; print_r($params);
        
        if($module == 'consumption') {
            $where_query = "";

            $join = 'left';
            if($params['userType'] == 'paid_user') {
                $join = 'inner';
            } else if($params['userType'] == 'free_user') {
                $join = 'left';
            }          

            if(!empty($params['dataSchoolCode']) || !empty($params['dataEmail'])) {
                if(!empty($params['dataEmail'])){
                    $where_query .=  " (user.email = '". $params['dataEmail'] ."')";               
                }

                if(!empty($params['dataSchoolCode']) && empty($params['dataEmail'])){
                    $where_query .=  " (ssd.school_code = '". $params['dataSchoolCode'] ."')";               
                } else if(!empty($params['dataSchoolCode'])){
                    $where_query .=  " AND (ssd.school_code = '". $params['dataSchoolCode'] ."')";
                }
            } else {
                if($params['dateSelect'] == 'create' && !empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";               
                }

                if($params['dateSelect'] == 'purchase' && !empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " ( tut.purchase_date >= '".$params['fromDate']." 00:00:00' AND tut.purchase_date <= '".$params['toDate'] . " 23:59:59')";
                }
            }            
            
            $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)"; 
            if($type == 'first') {

             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                     ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name END')), 'left')
                     ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                     ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                     ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                     ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                     ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                     ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                     ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                     ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');
            }

            if($type == 'login_count') {

             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)                   
                     ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                     ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name  END')), 'left')
                     ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                     ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                     ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                     ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                     ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                     ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                     ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                     ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')     
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');
            }

            if($type == 'time_count') {

             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('total_time' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                     ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NULL THEN rn.name ELSE cb.board_name END')), 'left')
                     ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                     ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                     ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                     ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                     ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                     ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                     ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                     ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')     
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');
            }

            if($type == 'learn_duration') {            

             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('learn_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                    ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                     ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Learn'"), array(), 'right')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                     ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NULL THEN rn.name ELSE cb.board_name END')), 'left')
                     ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                     ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                     ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                     ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                     ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                     ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                     ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                     ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')     
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');
            }

            if($type == 'practice_duration') {


             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('practice_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                    ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                     ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Practice'"), array(), 'right')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                     ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NULL THEN rn.name ELSE cb.board_name END')), 'left')
                     ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                     ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                     ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                     ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                     ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                     ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                     ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                     ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')     
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');
            }

            if($type == 'scheduled_count') {
                
                 $where_query .= ' AND rr1.rack_type_id IS NOT NULL';
                 $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                 $select->join(array("tlp" => 't_lesson_plan'), "tlp.user_id=user.user_id", array('schedule_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                        ->join(array("cbr" => 'custom_board_rack'), "tlp.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                        ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                        ->join(array("rr1" => 'resource_rack'), "tlp.package_usage_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                        ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tlp.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')), 'left')
                        ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                        ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                        ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                        ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                        ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                        ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                        ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                        ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                        ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                        ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                        ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                        ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                        ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                        ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                        ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                        ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                        ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                        ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                        ->where($where_query)
                        ->group('user.user_id');
            }

            if($type == 'mentor_count') {
                
                 $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
                 $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                 $select->join(array("tsm" => 't_student_and_mentor'), "tsm.student_id=user.user_id", array('mentor_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                         ->join(array("cbr" => 'custom_board_rack'), "tsm.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                         ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                         ->join(array("rr1" => 'resource_rack'), "tsm.subject_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                         ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tsm.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')), 'left')
                         ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                         ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                         ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                         ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                         ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                         ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                         ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                         ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                         ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                         ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                         ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                        ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                        ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                        ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                        ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                        ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                        ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                        ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                        ->where($where_query)
                        ->group('user.user_id');
            }

            if($type == 'group_member_count') {

             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("tug" => 't_user_groups'), "tug.user_id=user.user_id", array('group_member_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                     ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name END')), 'left')
                     ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                     ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                     ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                     ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                     ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                     ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                     ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                     ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')     
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');
            }

            if($type == 'note_count') {
                
                 $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
                 $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                 $select->join(array("tn" => 't_notes'), "tn.user_id=user.user_id", array('notes_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                         ->join(array("cbr" => 'custom_board_rack'), "tn.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                         ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                         ->join(array("rr1" => 'resource_rack'), "tn.chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                         ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tn.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')), 'left')
                         ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                         ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                         ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                         ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                         ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                         ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                         ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                         ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                         ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                         ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                         ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                        ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                        ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                        ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                        ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                        ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                        ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                        ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                        ->where($where_query)
                        ->group('user.user_id');
            }
            
            if($type == 'test_attempt_count') {

                if(!empty($params['dataPass']) && $params['dataPass'] == 'passed') {
                    $where_query .=  " AND (qs.set_status = 'passed')";
                } else {
                    $where_query .=  " AND (qs.set_status IN('passed', 'running', 'failed'))";
                }
                
                 $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
                 $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                 $select->join(array("qs" => 'quiz_set'), "qs.user_id=user.user_id", array('set_status','test_attempt_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                         ->join(array("qsc" => 'quiz_set_chapter'), "qs.set_id=qsc.set_id", array('board_classs_subject_chapter_id'), 'left')
                        ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                        ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                        ->join(array("rr1" => 'resource_rack'), "qsc.board_classs_subject_chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                        ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')), 'left')
                        ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                        ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                        ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                        ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                        ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                        ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                        ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                        ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                        ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                        ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                        ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                        ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                        ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                        ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                        ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                        ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                        ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                        ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                        ->where($where_query)
                        ->group('user.user_id');
            }
            
            if($type == 'location_count') {                
                $group_by = array('user.user_id');
                 if($params['dateSelect'] == 'purchase') {
                     //$group_by = array('tut.transaction_id');
                 }
                if(!empty($params['dataLocation']) && $params['dataLocation'] == 'local') {
                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("tc" => "t_country"), "tc.country_id = user.country_id", array('country_id','country_name'), 'left')
                        ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                        ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                        ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                        ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                        ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name END')), 'left')
                        ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                        ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                         ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                         ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                         ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                        ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                        ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                        ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')         
                        ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                        ->where($where_query)
                        ->group($group_by)
                        ->having('con.country_name = "INDIA"');
                } else {
                
                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("tc" => "t_country"), "tc.country_id = user.country_id", array('country_id','country_name'), 'left')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                             ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                             ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')     
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group($group_by)
                            ->having('con.country_name != "INDIA" AND con.country_name IS NOT NULL');
                }
                
            }
        
        }
        
        if($module == 'consumption_second') {
            
            $where_query = "1";
           $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";         
            $join = 'left';
            if($params['userType'] == 'paid_user') {
                $join = 'inner';
            } else if($params['userType'] == 'free_user') {
                $join = 'left';
            }
            
            $having_query = "class_name IS NOT NULL OR class_name IS NULL";

            if(!empty($params['userIds'])){
                $where_query .=  " AND (user.user_id IN (" . $params['userIds'] . "))"; 
            }
            
            if(!empty($params['dataSchoolCode']) || !empty($params['dataEmail'])) {                
            
                if(!empty($params['dataEmail'])){
                    $where_query .=  " AND (user.email = '". $params['dataEmail'] ."')";               
                }

                if(!empty($params['dataSchoolCode'])){
                    $where_query .=  " AND (ssd.school_code = '". $params['dataSchoolCode'] ."')";
                }
            } else {

                if($params['dateSelect'] == 'create' && !empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";                   
                }

                if($params['dateSelect'] == 'purchase' && !empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " AND ( tut.purchase_date >= '".$params['fromDate']." 00:00:00' AND tut.purchase_date <= '".$params['toDate'] . " 23:59:59')";
                }
            }

            if($type == 'time_count') {
                
                  if(!empty($params['dataEmail'])){
                    if(!empty($params['dateClass'])){
                        $where_query .=  " AND ( rn4.name = '".$params['dateClass']."' OR rn5.name = '".$params['dateClass'] . "' OR rn3.name = '".$params['dateClass'] . "')";
                    }

                    if(!empty($params['dateBoard'])){
                        $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn4.name = '".$params['dateBoard']."' OR rn5.name = '".$params['dateBoard'] . "' OR rn3.name = '".$params['dateBoard'] . "')";
                        $having_query .= "class_name IS NOT NULL AND board_name = '". $params['dateBoard'] ."'";
                    }
                     $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'user_type_id', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr4.rack_type_id=3 THEN rn4.name WHEN rr5.rack_type_id=3 THEN rn5.name ELSE rn3.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr4.rack_type_id=2 THEN rn4.name WHEN rr5.rack_type_id=2 THEN rn5.name ELSE rn3.name END')));
                     $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('practice_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                            ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr1" => 'resource_rack'), "pr.board_container_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                            ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                            ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                            ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                            ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                            ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                            ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                            ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                            ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                            ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                            ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                            ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')    
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group(array('user.user_id', 'pr.board_container_id'))
                            ->having($having_query);
                 } else {
                    if(!empty($params['dateClass'])){
                        $where_query .=  " AND ( tp.class = '".$params['dateClass']."' OR rn.name = '".$params['dateClass'] . "')";
                    }

                    if(!empty($params['dateBoard'])){
                        $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn1.name = '".$params['dateBoard'] . "')";
                        $having_query .= "class_name IS NOT NULL AND board_name = '". $params['dateBoard'] ."'";
                    }
                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('total_time' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                            ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NULL THEN rn1.name ELSE cb.board_name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having($having_query);
                 }

            }

            if($type == 'login_count') {
                
                if(!empty($params['dateClass'])){
                    $where_query .=  " AND ( rn.name = '".$params['dateClass'] . "')";
                }

                if(!empty($params['dateBoard'])){
                    $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn1.name = '".$params['dateBoard'] . "')";
                    $having_query .= "class_name IS NOT NULL AND board_name = '". $params['dateBoard'] ."'";
                }
                
                if(empty($params['dataLogin'])){                   

                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having($having_query);
                     
                } else if($params['dataLogin'] == 'single'){
                    
                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having('login_count = 1');
                    
                } else if($params['dataLogin'] == '2_5'){
                    
                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having('login_count > 1 AND login_count <= 5');
                    
                } else if($params['dataLogin'] == '6_10'){
                    
                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having('login_count >=6 AND login_count <= 10');
                    
                } else if($params['dataLogin'] == '10_plus'){
                    
                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having('login_count > 10');
                    
                } else if($params['dataLogin'] == 'total'){
                    
                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having($having_query);
                    
                } else if($params['dataLogin'] == 'total_without_board_class'){
                    
                     $where_query .= " AND (rn1.name IS NULL) AND (rn.name IS NULL)";
                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having($having_query);
                }

            }

            if($type == 'learn_duration') { 
          
                  if(!empty($params['dataEmail'])){
                      
                    if(!empty($params['dateClass'])){
                         $where_query .=  " AND ( rn4.name = '".$params['dateClass']."' OR rn5.name = '".$params['dateClass'] . "' OR rn3.name = '".$params['dateClass'] . "')";
                    }

                    if(!empty($params['dateBoard'])){
                        $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn4.name = '".$params['dateBoard']."' OR rn5.name = '".$params['dateBoard'] . "' OR rn3.name = '".$params['dateBoard'] . "')";
                        $having_query .= "class_name IS NOT NULL AND board_name = '". $params['dateBoard'] ."'";
                    }
                     $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'user_type_id', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr4.rack_type_id=3 THEN rn4.name WHEN rr5.rack_type_id=3 THEN rn5.name ELSE rn3.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr4.rack_type_id=2 THEN rn4.name WHEN rr5.rack_type_id=2 THEN rn5.name ELSE rn3.name END')));
                     $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('learn_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                            ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                            ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Learn'"), array(), 'right')
                            ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr1" => 'resource_rack'), "pr.board_container_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                            ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                            ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                            ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                            ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                            ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                            ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                            ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                            ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                            ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                            ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                            ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')    
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group(array('user.user_id', 'pr.board_container_id'))
                            ->having($having_query);  
                 } else {
                     
                    if(!empty($params['dateClass'])){
                        $where_query .=  " AND ( tp.class = '".$params['dateClass']."' OR rn.name = '".$params['dateClass'] . "')";
                    }

                    if(!empty($params['dateBoard'])){
                        $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn1.name = '".$params['dateBoard'] . "')";
                        $having_query .= "class_name IS NOT NULL AND board_name = '". $params['dateBoard'] ."'";
                    }
                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('learn_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                            ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                             ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Learn'"), array(), 'right')
                            ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NULL THEN rn1.name ELSE cb.board_name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having($having_query);
                 }

            }

            if($type == 'practice_duration') {
                
                  if(!empty($params['dataEmail'])){
                    if(!empty($params['dateClass'])){
                         $where_query .=  " AND ( rn4.name = '".$params['dateClass']."' OR rn5.name = '".$params['dateClass'] . "' OR rn3.name = '".$params['dateClass'] . "')";
                    }

                    if(!empty($params['dateBoard'])){
                        $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn4.name = '".$params['dateBoard']."' OR rn5.name = '".$params['dateBoard'] . "' OR rn3.name = '".$params['dateBoard'] . "')";
                        $having_query .= "class_name IS NOT NULL AND board_name = '". $params['dateBoard'] ."'";
                    }
                     $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'user_type_id', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr4.rack_type_id=3 THEN rn4.name WHEN rr5.rack_type_id=3 THEN rn5.name ELSE rn3.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr4.rack_type_id=2 THEN rn4.name WHEN rr5.rack_type_id=2 THEN rn5.name ELSE rn3.name END')));
                     $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('practice_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                            ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                            ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Practice'"), array(), 'right')
                            ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr1" => 'resource_rack'), "pr.board_container_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                            ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                            ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                            ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                            ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                            ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                            ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                            ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                            ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                            ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                            ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                            ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')    
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group(array('user.user_id', 'pr.board_container_id'))
                            ->having($having_query);   
                 } else {
                     
                    if(!empty($params['dateClass'])){
                        $where_query .=  " AND ( tp.class = '".$params['dateClass']."' OR rn.name = '".$params['dateClass'] . "')";
                    }

                    if(!empty($params['dateBoard'])){
                        $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn1.name = '".$params['dateBoard'] . "')";
                        $having_query .= "class_name IS NOT NULL AND board_name = '". $params['dateBoard'] ."'";
                    }

                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('practice_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                            ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                             ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Practice'"), array(), 'right')
                            ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NULL THEN rn1.name ELSE cb.board_name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having($having_query);
                 }

            }

            if($type == 'scheduled_count') {
                
                if(!empty($params['dateClass'])){
                    $where_query .=  " AND ( rn5.name = '".$params['dateClass'] . "' OR rn2.name = '".$params['dateClass'] . "' OR rn3.name = '".$params['dateClass'] . "' OR rn4.name = '".$params['dateClass'] . "')";
                }

                if(!empty($params['dateBoard'])){
                    $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn6.name = '".$params['dateBoard'] . "' OR rn3.name = '".$params['dateBoard'] . "' OR rn4.name = '".$params['dateBoard'] . "' OR rn5.name = '".$params['dateBoard'] . "')";
                    $having_query .= "class_name IS NOT NULL AND board_name = '". $params['dateBoard'] ."'";
                }
                
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
             
             //echo $where_query; die;
             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("tlp" => 't_lesson_plan'), "tlp.user_id=user.user_id", array('schedule_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                    ->join(array("cbr" => 'custom_board_rack'), "tlp.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                    ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                    ->join(array("rr1" => 'resource_rack'), "tlp.package_usage_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                    ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tlp.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')), 'left')
                    ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                    ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                    ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                    ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                    ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                    ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                    ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                    ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                    ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                    ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id')
                    ->having($having_query);

            }

            if($type == 'mentor_count') {

                if(!empty($params['dateClass'])){
                    $where_query .=  " AND ( rn5.name = '".$params['dateClass'] . "' OR rn2.name = '".$params['dateClass'] . "' OR rn3.name = '".$params['dateClass'] . "' OR rn4.name = '".$params['dateClass'] . "')";
                }

                if(!empty($params['dateBoard'])){
                    $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn6.name = '".$params['dateBoard'] . "' OR rn3.name = '".$params['dateBoard'] . "' OR rn4.name = '".$params['dateBoard'] . "' OR rn5.name = '".$params['dateBoard'] . "')";
                    $having_query .= "class_name IS NOT NULL AND board_name = '". $params['dateBoard'] ."'";
                }
                
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("tsm" => 't_student_and_mentor'), "tsm.student_id=user.user_id", array('mentor_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                     ->join(array("cbr" => 'custom_board_rack'), "tsm.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                     ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                     ->join(array("rr1" => 'resource_rack'), "tsm.subject_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tsm.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')), 'left')
                     ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                     ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                     ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                     ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                     ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                     ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                     ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                     ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                     ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                    ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id')
                    ->having($having_query);

            }

            if($type == 'group_member_count') {
                
                if(!empty($params['dateClass'])){
                    $where_query .=  " AND ( rn.name = '".$params['dateClass'] . "')";
                }

                if(!empty($params['dateBoard'])){
                    $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn1.name = '".$params['dateBoard'] . "')";
                    $having_query .= "class_name IS NOT NULL AND board_name = '". $params['dateBoard'] ."'";
                }

             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                     ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                     ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                     ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                     ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                     ->join(array("tug" => 't_user_groups'), "tug.user_id=user.user_id", array('group_member_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                     ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id')
                    ->having($having_query);

            }

            if($type == 'note_count') {
                
                if(!empty($params['dateClass'])){
                    $where_query .=  " AND ( rn5.name = '".$params['dateClass'] . "' OR rn2.name = '".$params['dateClass'] . "' OR rn3.name = '".$params['dateClass'] . "' OR rn4.name = '".$params['dateClass'] . "')";
                }

                if(!empty($params['dateBoard'])){
                    $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn6.name = '".$params['dateBoard'] . "' OR rn3.name = '".$params['dateBoard'] . "' OR rn4.name = '".$params['dateBoard'] . "' OR rn5.name = '".$params['dateBoard'] . "')";
                    $having_query .= "class_name IS NOT NULL AND board_name = '". $params['dateBoard'] ."'";
                }
                
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("tn" => 't_notes'), "tn.user_id=user.user_id", array('notes_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                     ->join(array("cbr" => 'custom_board_rack'), "tn.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                     ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                     ->join(array("rr1" => 'resource_rack'), "tn.chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tn.custom_board_rack_id IS NOT NULL THEN cb.baord_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')), 'left')
                     ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                     ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                     ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                     ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                     ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                     ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                     ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                     ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                     ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                    ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id')
                    ->having($having_query);

            }
            
            if($type == 'test_attempt_count') {
         
                if(!empty($params['dateClass'])){
                    $where_query .=  " AND ( rn5.name = '".$params['dateClass'] . "' OR rn2.name = '".$params['dateClass'] . "' OR rn3.name = '".$params['dateClass'] . "' OR rn4.name = '".$params['dateClass'] . "')";
                }

                if(!empty($params['dateBoard'])){
                    $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn6.name = '".$params['dateBoard'] . "' OR rn3.name = '".$params['dateBoard'] . "' OR rn4.name = '".$params['dateBoard'] . "' OR rn5.name = '".$params['dateBoard'] . "')";
                    $having_query .= "class_name IS NOT NULL AND board_name = '". $params['dateBoard'] ."'";
                }
                
                if(!empty($params['dataPass'])){
                    
                    if($params['dataPass'] == 'passed') {
                        $where_query .=  " AND ( qs.set_status = 'passed')";
                    } else {
                        $where_query .=  " AND ( qs.set_status IN ('passed', 'running', 'failed'))";
                    }
                }
                
                 $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
                 $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                 $select->join(array("qs" => 'quiz_set'), "qs.user_id=user.user_id", array('test_attempt_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                         ->join(array("qsc" => 'quiz_set_chapter'), "qs.set_id=qsc.set_id", array('board_classs_subject_chapter_id'), 'left')
                        ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                        ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                        ->join(array("rr1" => 'resource_rack'), "qsc.board_classs_subject_chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                        ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')), 'left')
                        ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                        ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                        ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                        ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                        ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                        ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                        ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                        ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                        ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                        ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                        ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                        ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                        ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                        ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                        ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                        ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                        ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                        ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                        ->where($where_query)
                        ->group(array('user.user_id'))
                        ->having($having_query);
                        //->group(array('user.user_id', 'qs.set_status')

           }
           
           if($type == 'location_count') {
         
                if(!empty($params['dataState']) && $params['dataState'] != 'N/A'){
                    $where_query .=  ' AND ( LOWER(ts.state_name) = "'. strtolower($params['dataState']) . '")';
                }
                
                if(!empty($params['dataState']) && $params['dataState'] == 'N/A'){
                    $where_query .=  " AND ( user.state_id IS NULL )";
                }

                if(!empty($params['dataCity']) && $params['dataCity'] != 'N/A'){
                    $where_query .=  ' AND ( LOWER(user.city) = "'. strtolower($params['dataCity']) . '")';
                }
                
                if($params['dataCity'] == 'N/A'){
                    $where_query .=  " AND ( user.city IS NULL)";
                }
                
                 $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                 $select->join(array("ts" => "t_state"), "ts.state_id = user.state_id", array('state_id','state_name'), 'left')
                        ->join(array("tc" => "t_country"), "tc.country_id = user.country_id", array('country_id','country_name'), 'left')
                        ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                        ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                        ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                        ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                        ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                        ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                        ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                        ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                        ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                        ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                        ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                        ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                        ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                        ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                        ->where($where_query)
                        ->group(array('user.user_id'));

           }
            
        }
        
        if($module == 'engagement') {
            
            $where_query = "";            
            
            if($params['filterType'] == 'all'){
                $where_query .=  "1 ";   
            }
            
            if($params['filterType'] == 'paid_users'){
                $where_query .=  " tup.status IN (1,2,3)";  
            }
            
            if($params['filterType'] == 'free_users'){
                $where_query .=  " (select count(*) from t_user_package tup1 where tup1.user_id=tup.user_id and tup1.status IN(1,2,3)) = 0 ";  
            }
            
            if($params['filterType'] == '60'){
                $where_query .=  " user.create_time <= date_sub(curdate(), interval 60 day) ";  
            }
            
            if($params['filterType'] == '60-45'){
                $where_query .=  " user.create_time between date_sub(curdate(), interval 60 day) and date_sub(curdate(), interval 45 day) ";  
            }
            
            if($params['filterType'] == '30'){
                $where_query .=  " user.create_time between date_sub(curdate(), interval 60 day) and curdate() ";  
            }
        
            if($params['dateSelect'] == 'create' && !empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
            }

            if($params['dateSelect'] == 'purchase' && !empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND ( tut.purchase_date >= '".$params['fromDate']." 00:00:00' AND tut.purchase_date <= '".$params['toDate'] . " 23:59:59')";   
            }
            
            if($params['dateSelect'] == '0' && !empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND ( report.created_date >= '".$params['fromDate']." 00:00:00' AND report.created_date <= '".$params['toDate'] . " 23:59:59')";   
            }
            
            $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";          
            if($params['dataMinuteType'] == 'varing') {
                
                if($type == 'total_engagement'){

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id=tup.user_id"), array('package_id'), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array(), 'left')
                            ->join(array("report" => "progress_report"), "user.user_id = report.user_id", array('TimeSpent_in_minute' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(report.total_time))/60')), 'inner')
                            ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                             ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                             ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                           ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->where($where_query)
                            ->group('user.user_id');
                } else {

                     //$typeArray = explode('-', $type);

                     //$having = "TimeSpent_in_minute >= " . $typeArray[0] . " AND TimeSpent_in_minute <= " . $typeArray[1] . "";

                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("tup" => 't_user_package'), new Expression("user.user_id=tup.user_id"), array('package_id'), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array(), 'left')
                            ->join(array("report" => "progress_report"), "user.user_id = report.user_id", array('total_time' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(report.total_time))/60')), 'inner')
                            ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                             ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                             ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->where($where_query)
                            ->group('user.user_id');
                            //->having($having);
                }
            }
            
            if($params['dataMinuteType'] == 'fixed') {
                
                if($type == '120plus'){               

                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("tup" => 't_user_package'), new Expression("user.user_id=tup.user_id"), array('package_id'), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array(), 'left')
                            ->join(array("report" => "progress_report"), "user.user_id = report.user_id", array('TimeSpent_in_minute' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(report.total_time))/60')), 'inner')
                            ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                             ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                             ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having('TimeSpent_in_minute > 120');
                 }

                 if($type == '90-120'){

                        $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                        $select->join(array("tup" => 't_user_package'), new Expression("user.user_id=tup.user_id"), array('package_id'), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array(), 'left')
                            ->join(array("report" => "progress_report"), "user.user_id = report.user_id", array('TimeSpent_in_minute' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(report.total_time))/60')), 'inner')
                            ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                             ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                             ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                             ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having('TimeSpent_in_minute > 90 AND TimeSpent_in_minute <= 120');
                 }

                 if($type == '60-90'){

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id=tup.user_id"), array('package_id'), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array(), 'left')
                            ->join(array("report" => "progress_report"), "user.user_id = report.user_id", array('TimeSpent_in_minute' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(report.total_time))/60')), 'inner')
                            ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                             ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                             ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                           ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having('TimeSpent_in_minute > 60 AND TimeSpent_in_minute <= 90');
                 }

                 if($type == '30-60'){

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id=tup.user_id"), array('package_id'), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array(), 'left')
                            ->join(array("report" => "progress_report"), "user.user_id = report.user_id", array('TimeSpent_in_minute' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(report.total_time))/60')), 'inner')
                            ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                             ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                             ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                           ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having('TimeSpent_in_minute > 30 AND TimeSpent_in_minute <= 60');
                 }

                 if($type == '<30'){

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id=tup.user_id"), array('package_id'), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array(), 'left')
                            ->join(array("report" => "progress_report"), "user.user_id = report.user_id", array('TimeSpent_in_minute' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(report.total_time))/60')), 'inner')
                            ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                             ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                             ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                           ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having('TimeSpent_in_minute <= 30');
                 }

                 if($type == 'total'){

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id=tup.user_id"), array('package_id'), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array(), 'left')
                            ->join(array("report" => "progress_report"), "user.user_id = report.user_id", array('TimeSpent_in_minute' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(report.total_time))/60')), 'inner')
                            ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                             ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                             ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                           ->join(array("tdc" => "t_discount_coupon"), "tdc.coupon_id=tut.coupon_id", array('coupon_type'), 'left')      
                            ->where($where_query)
                            ->group('user.user_id');
                 }
            }
            
        }
        
        if($module == 'subscription') {
            $where_query = "1";

            if(!empty($params['userIds'])){
                $where_query .=  " AND (user.user_id IN (" . $params['userIds'] . "))";
            }
            
            if(!empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND ( tut.purchase_date >= '".$params['fromDate']." 00:00:00' AND tut.purchase_date <= '".$params['toDate'] . " 23:59:59')";   
            }
            
            if(!empty($params['dataFilter'])){
                $where_query .=  " AND (LOWER(tp.board) = '" . strtolower($params['dataFilter']) . "')"; 
            }
            
            if(!empty($params['dataClass'])){
                $where_query .=  " AND (LOWER(tp.class) = '" . strtolower($params['dataClass']) . "')"; 
            }
            
            if(!empty($params['dataState']) && $params['dataState'] != "NA"){
                $where_query .=  ' AND (LOWER(ts.state_name) = "' . strtolower($params["dataState"]) . '")'; 
            }else if(!empty($params['dataState']) && $params['dataState'] == "NA"){
                    $where_query .=  " AND (ts.state_name IS NULL)"; 
                }
            if(!empty($params['filterType'])){
                $where_query .=  " AND ( tp.package_type = '" . $params['filterType'] . "')"; 
            }

            $group_by = array('tut.transaction_id', 'tup.transaction_id');
            if(!empty($params['state'])){
                $group_by = array('tut.transaction_id');
            }

            $order_by = 'tut.transaction_id DESC';

            if(!empty($params['currencyVal'])){
                 if($params['currencyVal'] != '0'){
                    $where_query .=  " AND (tup.currency_type = '" . $params['currencyVal'] . "')"; 
                 }
            }

            if(!empty($params['paymentType'])){ 
                 if($params['modeType'] != 'offline') {
                     if($params['paymentType'] == 'netbanking') {
                        $where_query .=  " AND (tut.pkg_payment_type IN ('CCAVENUE', 'ebs'))"; 
                     }

                     if($params['paymentType'] == 'creditcard') {
                        $where_query .=  " AND (tut.pkg_payment_type IN ('CCAVENUE','migs'))"; 
                     }

                     if($params['paymentType'] == '0') {
                        $where_query .=  " AND (tut.pkg_payment_type IN ('CCAVENUE', 'ebs', 'migs', 'FREE USER', 'cod'))"; 
                     }
                 }
            }
            
            if($params['modeType'] == 'offline' && !empty($params['paymentMode'])){
                $where_query .=  " AND (LOWER(opt.payment_mode) = '" . strtolower($params['paymentMode']) . "')"; 
            }
            
            if($params['modeType'] == 'online' && !empty($params['paymentMode'])){
                $where_query .=  " AND (LOWER(tut.pkg_payment_type) = '" . strtolower($params['paymentMode']) . "')"; 
            }

            if(!empty($params['modeType'])){
                if($params['modeType'] == 'online') {
                    $where_query .=  " AND (tut.pkg_payment_type != 'offline')"; 
                } else if($params['modeType'] == 'offline') {
                    $where_query .=  " AND (tut.pkg_payment_type = 'offline')"; 
                }
            }
            
            if($type == 'study') {
                $where_query .=  " AND (tp.package_type = 'study')"; 
            } else if($type == 'sdcard') {
                $where_query .=  " AND (tp.package_type = 'sdcard')"; 
            } else if($type == 'tablet') {
                $where_query .=  " AND (tp.package_type = 'tablet')"; 
            }

            $where_query .= " AND ((tdc.coupon_type!='test' AND tdc.coupon_type!='demo' AND tdc.coupon_type!='promotional') OR tdc.coupon_type IS NULL)";         //echo $where_query; die;
            $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
            $select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array('package_price'=>new \Zend\Db\Sql\Expression("SUM(package_price)"), 'package_id', 'discount_amount','status','user_package_id','valid_till', 'currency_type','paid_price'=>'paid_amount'), 'left')
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array('id','syllabus_id'), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name','package_type','class_name'=>'class','board_name'=>'board', 'price'), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','order_id','purchase_date'=>'purchase_date','pkg_payment_details','pkg_payment_type','purchaser_id'), 'left')
                    ->join(array("pu" => "user"), "pu.user_id = tut.purchaser_id", array('employee_name' => 'display_name','user_type_id'), 'left')
                    ->join(array("ts" => "t_state"), "ts.state_id = user.state_id", array('state_id','state_name'), 'left')
                    ->join(array('ssd' => 'sales_school_details'), 'ssd.school_id=user.school_id', array('school_code','school_name'), 'left')
                    ->join(array("tc" => "t_country"), "tc.country_id = user.country_id", array('country_id','country_name'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                    ->join(array("tu" => "user"), new Expression("tut.purchaser_id = tu.user_id"), array(), 'left')
                    ->join(array("opt" => "offline_payment_transaction"), new Expression("opt.user_transaction_id = tut.transaction_id"), array('payment_mode'=>'payment_mode','login_id'=>'login_id'), 'left')
                    ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=tu.board_id", array('rack_name_id'), 'left')
                    ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('board_name' => 'name'), 'left')
                    ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=tu.class_id", array('rack_name_id'), 'left')
                    ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('class_name' => 'name'), 'left')
                    ->where($where_query)
                    ->where("tut.order_id IS NOT NULL")
                    //->where("tut.coupon_id!=1")
                    ->where("tut.pkg_payment_details='Processed'")
                    //->where("tdc.coupon_type!='test'")
                    ->where("tup.status IN(1,2,3)")
                    ->group($group_by)
                    ->having("paid_price>=0")
                    ->order($order_by);
        }
        
        if($module == 'registration') {
            
            if($params['section'] == 'by_date') {
                $where_query = "";

                if(!empty($params['dataDate'])) {
                    $where_query .= "user.create_time >= '". $params['dataDate'] ." 00:00:00' AND user.create_time <='". $params['dataDate'] ." 23:59:59'";
                } else {
                    if (!empty($params['fromDate']) && $params['fromDate'] != '' && !empty($params['toDate']) && $params['toDate'] != '') {
                        $where_query .= "user.create_time >= '". $params['fromDate'] ." 00:00:00' AND user.create_time <='". $params['toDate'] ." 23:59:59'";
                    }
                }
                
                if($type == 'direct_registration') {
                    $where_query .= " AND up.provider IS NULL";
                }
                
                if($type == 'social_registration') {
                    $where_query .= " AND up.provider IN ('google','facebook','twitter')";
                }

                $select = $this->tableGateway->getSql()->select()->columns($tableColumns)
                 ->join(array("up" => 'user_provider'), "user.user_id = up.user_id", array('provider'), 'left')
                 ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                 ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                 ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name END')), 'left')
                 ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                 ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                 ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                 ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                 ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                 ->group(array('user_id'))
                 ->where($where_query);
            }
            
            if($params['section'] == 'by_state') {
                $where_query = "";
                if (!empty($params['fromDate']) && $params['fromDate'] != '' && !empty($params['toDate']) && $params['toDate'] != '') {
                    $where_query .= "user.create_time >= '". $params['fromDate'] ." 00:00:00' AND user.create_time <='". $params['toDate'] ." 23:59:59'";
                }
                
                if(!empty($params['dataState']) && $params['dataState'] != "NA"){
                    $where_query .=  " AND (LOWER(st.state_name) = '" . strtolower($params['dataState']) . "')"; 
                } else if(!empty($params['dataState']) && $params['dataState'] == "NA"){
                    $where_query .=  " AND (st.state_name IS NULL)"; 
                }
                
                $select = $this->tableGateway->getSql()->select()->columns($tableColumns)    
                 ->join(array("up" => 'user_provider'), "user.user_id = up.user_id", array('provider'),'left')
                 ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                 ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                 ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name END')), 'left')
                 ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                 ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                 ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                 ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                 ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                 ->where($where_query)
                 ->group("user_id");
            }
            
            if($params['section'] == 'by_class_board') {
                
               $where_query = "";
                
               if (!empty($params['fromDate']) && $params['fromDate'] != '' && !empty($params['toDate']) && $params['toDate'] != '') {
                   $where_query .= "user.create_time >= '". $params['fromDate'] ." 00:00:00' AND user.create_time <='". $params['toDate'] ." 23:59:59'";
               }
               
               if (!empty($params['dataClass'])) {
                   $where_query .= " AND (crn.name = '". $params['dataClass'] ."')";
               }
               
               if (!empty($params['dataBoard'])) {
                   $where_query .= " AND (cb.board_name = '".$params['dataBoard']."' OR rn.name = '". $params['dataBoard'] ."')";
               }
               
               if(!empty($params['dataState']) && $params['dataState'] != "NA"){
                   $where_query .=  " AND (LOWER(st.state_name) = '" . strtolower($params['dataState']) . "')"; 
               } else if(!empty($params['dataState']) && $params['dataState'] == "NA"){
                   $where_query .=  " AND (st.state_name IS NULL)"; 
               }
               
               if (!empty($params['dataSchool']) && $params['dataSchool'] !='') {
                   $where_query .= ' AND LOWER(user.school_name) = "'. strtolower($params['dataSchool']) .'"';
               }
               
               if (!empty($params['dataType'])) {
                   
                   if($params['dataType'] == 'total_class_board') {
                        $where_query .= " AND user.class_id IS NOT NULL AND user.class_id !=0 ";
                   }
                   
                   if($params['dataType'] == 'total_without_class') {
                        $where_query .= " AND user.class_id IS NULL";
                   }
                   
                   if($params['dataType'] == 'total_without_class_student') {
                       $where_query .= " AND user.class_id IS NULL AND user_type_id=1";
                   }
                   
                   if($params['dataType'] == 'total_without_class_parent') {
                       $where_query .= " AND user.class_id IS NULL AND user_type_id=2";
                   }
                   
                   if($params['dataType'] == 'total_without_class_mentor') {
                       $where_query .= " AND user.class_id IS NULL AND user_type_id=3";
                   }
               }
                
                
               $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
               $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name END')), 'left')
                     ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                     ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                     ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                     ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                     ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                     ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                     ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                     ->where($where_query)
                     ->group(array('user.user_id'));
            }
            
            if($params['section'] == 'by_school') {
                $where_query = "";
                
                if (!empty($params['fromDate']) && $params['fromDate'] != '' && !empty($params['toDate']) && $params['toDate'] != '') {
                    $where_query .= "user.create_time >= '". $params['fromDate'] ." 00:00:00' AND user.create_time <='". $params['toDate'] ." 23:59:59'";
                }
                
                if(!empty($params['dataState']) && $params['dataState'] != "NA"){
                    $where_query .=  ' AND (LOWER(st.state_name) = "' . strtolower($params['dataState']) . '")'; 
                } else if(!empty($params['dataState']) && $params['dataState'] == "NA"){
                    $where_query .=  " AND (st.state_name IS NULL)"; 
                }
                
                if (!empty($params['dataSchoolCode']) && $params['dataSchoolCode'] !='') {
                    $where_query .= ' AND LOWER(ssd.school_code) = "'. strtolower($params['dataSchoolCode']) .'"';
                }

                if (!empty($params['dataCity']) && $params['dataCity'] !='') {
                    $where_query .= ' AND LOWER(user.city) = "'. strtolower($params['dataCity']) .'"';
                }
                
                if (!empty($params['dataSchool']) && $params['dataSchool'] !='') {
                    $where_query .= ' AND LOWER(user.school_name) = "'. strtolower($params['dataSchool']) .'"';
                }
                
                //$where_query .= ' AND user.school_name != ""';
                
                if (!empty($params['dataType'])) {
                   
                   if($params['dataType'] == 'total_without_school') {
                        $where_query .= " AND user_type_id IN (2,3)";
                   }
                   
                   if($params['dataType'] == 'total_without_school_student') {
                       $where_query .= " AND user.school_name IS NULL AND ssd.school_code IS NULL AND user_type_id=1";
                   }
                   
                   if($params['dataType'] == 'total_without_school_parent') {
                       $where_query .= " AND user_type_id=2";
                   }
                   
                   if($params['dataType'] == 'total_without_school_mentor') {
                       $where_query .= " AND user_type_id=3";
                   }
                   
                   if($params['dataType'] == 'school_student') {
                       $where_query .= " AND user.school_name IS NOT NULL AND user_type_id=1";
                   }
               }
        
                $select = $this->tableGateway->getSql()->select()->columns($tableColumns)                 
                     ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left')
                     ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                     ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn.name END')), 'left')
                     ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                     ->join(array("crr" => 'resource_rack'), "user.class_id=crr.rack_id", array('class_id'=>'rack_id'), 'left')
                     ->join(array("crn" => 'rack_name'), "crn.rack_name_id=crr.rack_name_id", array('class_name' => 'name'), 'left')
                     ->join(array("rr" => 'resource_rack'), "rr.rack_id = user.board_id", array('board_id'=>'rack_id'), 'left')
                     ->join(array("rn" => 'rack_name'), "rr.rack_name_id=rn.rack_name_id", array(), 'left')
                     ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                     ->where($where_query)
                     ->group(array("user.user_id"));
            }
        }
        
        if($module == 'consumption_reverse') {
            
            $where_query = "1";
             $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";   
            $group_by = array('user.user_id');

            $join = 'left';
            if($params['userType'] == 'paid_user') {
                $join = 'inner';
            } else if($params['userType'] == 'free_user') {
                $join = 'left';
            }
            
            if(!empty($params['dataEmail'])){
                $where_query .=  " AND (user.email = '". $params['dataEmail'] ."')";               
            }

            if(!empty($params['dataSchoolCode'])){
                $where_query .=  " AND (ssd.school_code = '". $params['dataSchoolCode'] ."')";
            }
            
            /*if(!empty($params['dataUserType'])) {
                if($params['dataUserType'] == 'free_users') {
                    $where_query .=  " AND (tup.package_id IS NULL OR tup.package_id = 0)";
                } else if($params['dataUserType'] == 'paid_users') {
                    $where_query .=  " AND (tup.package_id IS NOT NULL AND tup.package_id != 0)";
                }
            }*/
            
            if(!empty($params['dataUserType'])) {
                if($params['dataUserType'] == 'free_users') {
                    $where_query .=  " AND ((select count(*) from t_user_package tup1 where tup1.user_id=tup.user_id and tup1.status=1 and tup1.status IN (1,2,3)) = 0)";
                } else if($params['dataUserType'] == 'paid_users') {
                    $where_query .=  " AND (tup.status IN (1,2,3))";
                }
            }
            
            if(!empty($params['dataGroup']) && $params['dataGroup'] == 'group'){
                if($params['dataUserType'] == 'free_users') {
                    $group_by = array('user.user_id');
                } else if($params['dataUserType'] == 'paid_users') {
                    if($params['dataFilter'] == 'free_users') {
                        $group_by = array('user.user_id');
                    } else {
                        $group_by = array('tut.transaction_id');
                    }
                }
            }
            
            if(!empty($params['dataDate'])){
                $dateBeforeThreeMonths = date('Y-m-d', strtotime("-3 months", strtotime($params['fromDate'])));        
                $newDateBeforeThreeMonths = date('Y-m', strtotime($dateBeforeThreeMonths));
                if($params['dataUserType'] == 'free_users') {                    
                    $dataDate = date('Y-m', strtotime($params['dataDate']));
                    if($newDateBeforeThreeMonths == $dataDate) {
                        $where_query .=  " AND (user.create_time >= '". $dateBeforeThreeMonths ." 00:00:00' AND user.create_time <= '". $newDateBeforeThreeMonths . "-31 23:59:59')";
                    } else {
                        $where_query .=  " AND (user.create_time >= '". $dataDate ."-01 00:00:00' AND user.create_time <= '". $dataDate . "-31 23:59:59')";
                    }
                } else if($params['dataUserType'] == 'paid_users') {
                    $dataDate = date('Y-m', strtotime($params['dataDate']));
                    if($params['dataFilter'] == 'free_users') {
                        if($newDateBeforeThreeMonths == $dataDate) {
                            $where_query .=  " AND (user.create_time >= '". $dateBeforeThreeMonths ." 00:00:00' AND user.create_time <= '". $newDateBeforeThreeMonths . "-31 23:59:59')";
                        } else {
                            $where_query .=  " AND (user.create_time >= '". $dataDate ."-01 00:00:00' AND user.create_time <= '". $dataDate . "-31 23:59:59')";
                        }
                    } else {
                        if($newDateBeforeThreeMonths == $dataDate) {
                            $where_query .=  " AND (tut.purchase_date >= '". $dateBeforeThreeMonths ." 00:00:00' AND tut.purchase_date <= '". $newDateBeforeThreeMonths . "-31 23:59:59')";
                        } else {
                            $where_query .=  " AND (tut.purchase_date >= '". $dataDate ."-01 00:00:00' AND tut.purchase_date <= '". $dataDate . "-31 23:59:59')";
                        }
                    }
                }
            }
            
            if(!empty($params['dataTotalDate'])){
                $afterThreeMonths = date('Y-m', strtotime("+3 months", strtotime($params['dataTotalDate']))); 
                if($params['dataUserType'] == 'free_users') {                     
                    $where_query .=  " AND (user.create_time >= '".$params['dataTotalDate']." 00:00:00' AND user.create_time <= '".$afterThreeMonths."-31 23:59:59')";
                } else if($params['dataUserType'] == 'paid_users') {
                    if($params['dataFilter'] == 'free_users') {
                        $where_query .=  " AND (user.create_time >= '".$params['dataTotalDate']." 00:00:00' AND user.create_time <= '".$afterThreeMonths."-31 23:59:59')";
                    } else {
                        $where_query .=  " AND (tut.purchase_date >= '".$params['dataTotalDate']." 00:00:00' AND tut.purchase_date <= '".$afterThreeMonths."-31 23:59:59')";
                    }
                }
            }
            
            
            if($type == 'login_count') {

                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " AND (( ul.login >= '".$params['fromDate']." 00:00:00' AND ul.login <= '".$params['toDate'] . " 23:59:59') OR ( ul.logout >= '".$params['fromDate']." 00:00:00' AND ul.logout <= '".$params['toDate'] . " 23:59:59'))";               
                }             
                
                $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                    ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                    ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                    ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                    ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                    ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group($group_by);
            }

            if($type == 'time_count') {

                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
                }            

                 $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                 $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('total_time' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                        ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                        ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                        ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                        ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                        ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                        ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                        ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                        ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NULL THEN rn1.name ELSE cb.board_name END')), 'left')
                        ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                        ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                        ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                        ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                        ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                        ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                        ->where($where_query)
                        ->group($group_by);
            }

            if($type == 'learn_duration') {            

                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
                }
                 $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                 $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('learn_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                        ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                         ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Learn'"), array(), 'right')
                        ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                        ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                        ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                        ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                        ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                        ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                        ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                        ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NULL THEN rn1.name ELSE cb.board_name END')), 'left')
                        ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                        ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                        ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                        ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                        ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                        ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                        ->where($where_query)
                        ->group($group_by);
            }

            if($type == 'practice_duration') {


             if(!empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
             }

             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('practice_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                    ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                     ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Practice'"), array(), 'right')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NULL THEN rn1.name ELSE cb.board_name END')), 'left')
                    ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                    ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                    ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                    ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                    ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group($group_by);
            }

            if($type == 'scheduled_count') {

             if(!empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND (tlp.plan_date >= '".$params['fromDate']." 00:00:00' AND tlp.plan_date <= '".$params['toDate'] . " 23:59:59')";               
             }
             
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL';
             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("tlp" => 't_lesson_plan'), "tlp.user_id=user.user_id", array('schedule_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                    ->join(array("cbr" => 'custom_board_rack'), "tlp.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                    ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                    ->join(array("rr1" => 'resource_rack'), "tlp.package_usage_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                    ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tlp.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')), 'left')
                    ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                    ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                    ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                    ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                    ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                    ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                    ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                    ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                    ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                    ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group($group_by);
            }

            if($type == 'mentor_count') {

             if(!empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND (tmd.added_date >= '".$params['fromDate']." 00:00:00' AND tmd.added_date <= '".$params['toDate'] . " 23:59:59')";               
             }            
             
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("tsm" => 't_student_and_mentor'), "tsm.student_id=user.user_id", array('mentor_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                     ->join(array("tmd" => 't_mentor_details'), "tsm.student_id=tmd.mentor_id", array(), 'inner')
                     ->join(array("cbr" => 'custom_board_rack'), "tsm.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                     ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                     ->join(array("rr1" => 'resource_rack'), "tsm.subject_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tsm.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')), 'left')
                     ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                     ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                     ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                     ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                     ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                     ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                     ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                     ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                     ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                     ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                     ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group($group_by);
            }

            if($type == 'group_member_count') {

             if(!empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND (tug.added_date >= '".$params['fromDate']." 00:00:00' AND tug.added_date <= '".$params['toDate'] . " 23:59:59')";               
             }

             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("tug" => 't_user_groups'), "tug.user_id=user.user_id", array('group_member_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                    ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                    ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                    ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                    ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                    ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group($group_by);
            }

            if($type == 'note_count') {

             if(!empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND (tn.added_date >= '".$params['fromDate']." 00:00:00' AND tn.added_date <= '".$params['toDate'] . " 23:59:59')";               
             }
             
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("tn" => 't_notes'), "tn.user_id=user.user_id", array('notes_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                     ->join(array("cbr" => 'custom_board_rack'), "tn.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                     ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                     ->join(array("rr1" => 'resource_rack'), "tn.chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tn.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')), 'left')
                     ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                     ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                     ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                     ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                     ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                     ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                     ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                     ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                     ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                     ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                     ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group($group_by);
            }
            
            if($type == 'test_attempt_count') {

                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                   $where_query .=  " AND (qs.set_date_added >= '".$params['fromDate']." 00:00:00' AND qs.set_date_added <= '".$params['toDate'] . " 23:59:59')";               
                }
                
                if(!empty($params['dataPass']) && $params['dataPass'] == 'passed') {
                    $where_query .=  " AND (qs.set_status = 'passed')";
                } else {
                    $where_query .=  " AND (qs.set_status IN('passed', 'running', 'failed'))";
                }
                
                $group_by = array('user.user_id');
                if(!empty($params['dataGroup']) && $params['dataGroup'] == 'group'){
                    if($params['dataUserType'] == 'free_users') {
                        $group_by = array('user.user_id', 'qs.set_status');
                    } else if($params['dataUserType'] == 'paid_users') {
                        if($params['dataFilter'] == 'free_users') {
                            $group_by = array('user.user_id', 'qs.set_status');
                        } else {
                            $group_by = array('tut.transaction_id', 'qs.set_status');
                        }
                    }
                }
                
                 $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
                 $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                 $select->join(array("qs" => 'quiz_set'), "qs.user_id=user.user_id", array('set_status','test_attempt_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                         ->join(array("qsc" => 'quiz_set_chapter'), "qs.set_id=qsc.set_id", array('board_classs_subject_chapter_id'), 'left')
                        ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                        ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                        ->join(array("rr1" => 'resource_rack'), "qsc.board_classs_subject_chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                        ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')), 'left')
                        ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                        ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                        ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                        ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                        ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                        ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                        ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                        ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                        ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                        ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                        ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                        ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                        ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                        ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                        ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                        ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                        ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                        ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                        ->where($where_query)
                        ->group($group_by);
            }   
        
        }
        
        
        if($module == 'consumption_second_reverse') {
            
            $where_query = "1";
            $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";   
            $join = 'left';
            if($params['userType'] == 'paid_user') {
                $join = 'inner';
            } else if($params['userType'] == 'free_user') {
                $join = 'left';
            }
            
            if(!empty($params['dataEmail'])){
                $where_query .=  " AND (user.email = '". $params['dataEmail'] ."')";               
            }

            if(!empty($params['dataSchoolCode'])){
                $where_query .=  " AND (ssd.school_code = '". $params['dataSchoolCode'] ."')";
            }

            if(!empty($params['userIds'])){
                $where_query .=  " AND (user.user_id IN (" . $params['userIds'] . "))"; 
            }            
            
            if(!empty($params['dataDate'])){
                $dateBeforeThreeMonths = date('Y-m-d', strtotime("-3 months", strtotime($params['fromDate'])));        
                $newDateBeforeThreeMonths = date('Y-m', strtotime($dateBeforeThreeMonths));
                if($params['dataUserType'] == 'free_users') {                    
                    $dataDate = date('Y-m', strtotime($params['dataDate']));
                    if($newDateBeforeThreeMonths == $dataDate) {
                        $where_query .=  " AND (user.create_time >= '". $dateBeforeThreeMonths ." 00:00:00' AND user.create_time <= '". $newDateBeforeThreeMonths . "-31 23:59:59')";
                    } else {
                        $where_query .=  " AND (user.create_time >= '". $dataDate ."-01 00:00:00' AND user.create_time <= '". $dataDate . "-31 23:59:59')";
                    }
                }
            }

            if($type == 'time_count') {               
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
                }
                
                if(!empty($params['dateClass'])){
                    $where_query .=  " AND ( tp.class = '".$params['dateClass']."' OR rn.name = '".$params['dateClass'] . "')";
                }

                if(!empty($params['dateBoard'])){
                    $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn1.name = '".$params['dateBoard'] . "')";
                }

             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                     ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                     ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                     ->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('total_time' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                    ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NULL THEN rn1.name ELSE cb.board_name END')), 'left')
                    ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');

            }

            if($type == 'login_count') {
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " AND (( ul.login >= '".$params['fromDate']." 00:00:00' AND ul.login <= '".$params['toDate'] . " 23:59:59') OR ( ul.logout >= '".$params['fromDate']." 00:00:00' AND ul.logout <= '".$params['toDate'] . " 23:59:59'))";               
                }
                
                if(!empty($params['dateClass'])){
                    $where_query .=  " AND ( rn.name = '".$params['dateClass'] . "')";
                }

                if(!empty($params['dateBoard'])){
                    $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn1.name = '".$params['dateBoard'] . "')";
                }
                
                if(empty($params['dataLogin'])){                   

                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id');
                     
                } else if($params['dataLogin'] == 'single'){
                    
                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having('login_count = 1');
                    
                } else if($params['dataLogin'] == '2_5'){
                    
                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having('login_count > 1 AND login_count <= 5');
                    
                } else if($params['dataLogin'] == '6_10'){
                    
                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having('login_count >=6 AND login_count <= 10');
                    
                } else if($params['dataLogin'] == '10_plus'){
                    
                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id')
                            ->having('login_count > 10');
                    
                } else if($params['dataLogin'] == 'total'){
                    
                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id');
                    
                } else if($params['dataLogin'] == 'total_without_board_class'){
                    
                     $where_query .= " AND (rn1.name IS NULL) AND (rn.name IS NULL)";
                     $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                     $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                            ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                            ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                             ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                             ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                             ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                             ->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                            ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                            ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                            ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                            ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                            ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                            ->where($where_query)
                            ->group('user.user_id');
                }

            }

            if($type == 'learn_duration') {
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
                }
                
                if(!empty($params['dateClass'])){
                    $where_query .=  " AND ( rn.name = '".$params['dateClass'] . "')";
                }

                if(!empty($params['dateBoard'])){
                    $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn1.name = '".$params['dateBoard'] . "')";
                }

             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                     ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                     ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                     ->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('learn_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                    ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                     ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Learn'"), array(), 'right')
                    ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NULL THEN rn1.name ELSE cb.board_name END')), 'left')
                    ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');

            }

            if($type == 'practice_duration') {
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
                }
                
                if(!empty($params['dateClass'])){
                    $where_query .=  " AND ( rn.name = '".$params['dateClass'] . "')";
                }

                if(!empty($params['dateBoard'])){
                    $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn1.name = '".$params['dateBoard'] . "')";
                }

             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                     ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                     ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                     ->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('practice_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                    ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                     ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Practice'"), array(), 'right')
                    ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NULL THEN rn1.name ELSE cb.board_name END')), 'left')
                    ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');

            }

            if($type == 'scheduled_count') {
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                   $where_query .=  " AND (tlp.plan_date >= '".$params['fromDate']." 00:00:00' AND tlp.plan_date <= '".$params['toDate'] . " 23:59:59')";               
                }
                
                if(!empty($params['dateClass'])){
                    $where_query .=  " AND ( rn5.name = '".$params['dateClass'] . "' OR rn2.name = '".$params['dateClass'] . "' OR rn3.name = '".$params['dateClass'] . "' OR rn4.name = '".$params['dateClass'] . "')";
                }

                if(!empty($params['dateBoard'])){
                    $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn6.name = '".$params['dateBoard'] . "' OR rn3.name = '".$params['dateBoard'] . "' OR rn4.name = '".$params['dateBoard'] . "' OR rn5.name = '".$params['dateBoard'] . "')";
                }
                
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
             
             //echo $where_query; die;
             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("tlp" => 't_lesson_plan'), "tlp.user_id=user.user_id", array('schedule_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                    ->join(array("cbr" => 'custom_board_rack'), "tlp.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                    ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                    ->join(array("rr1" => 'resource_rack'), "tlp.package_usage_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                    ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tlp.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')), 'left')
                    ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                    ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                    ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                    ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                    ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                    ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                    ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                    ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                    ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                    ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');

            }

            if($type == 'mentor_count') {

                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " AND (tmd.added_date >= '".$params['fromDate']." 00:00:00' AND tmd.added_date <= '".$params['toDate'] . " 23:59:59')";               
                }
             
                
                if(!empty($params['dateClass'])){
                    $where_query .=  " AND ( rn5.name = '".$params['dateClass'] . "' OR rn2.name = '".$params['dateClass'] . "' OR rn3.name = '".$params['dateClass'] . "' OR rn4.name = '".$params['dateClass'] . "')";
                }

                if(!empty($params['dateBoard'])){
                    $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn6.name = '".$params['dateBoard'] . "' OR rn3.name = '".$params['dateBoard'] . "' OR rn4.name = '".$params['dateBoard'] . "' OR rn5.name = '".$params['dateBoard'] . "')";
                }
                
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("tsm" => 't_student_and_mentor'), "tsm.student_id=user.user_id", array('mentor_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                     ->join(array("cbr" => 'custom_board_rack'), "tsm.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                     ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                     ->join(array("rr1" => 'resource_rack'), "tsm.subject_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tsm.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')), 'left')
                     ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                     ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                     ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                     ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                     ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                     ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                     ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                     ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                     ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                    ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');

            }

            if($type == 'group_member_count') {
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " AND (tug.added_date >= '".$params['fromDate']." 00:00:00' AND tug.added_date <= '".$params['toDate'] . " 23:59:59')";               
                }             
                
                if(!empty($params['dateClass'])){
                    $where_query .=  " AND ( rn.name = '".$params['dateClass'] . "')";
                }

                if(!empty($params['dateBoard'])){
                    $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn1.name = '".$params['dateBoard'] . "')";
                }

             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array('board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn1.name END')), 'left')
                    ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                    ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                     ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array('class_name' => 'name'), 'left')
                     ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                     ->join(array("tug" => 't_user_groups'), "tug.user_id=user.user_id", array('group_member_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                     ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');

            }

            if($type == 'note_count') {
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " AND (tn.added_date >= '".$params['fromDate']." 00:00:00' AND tn.added_date <= '".$params['toDate'] . " 23:59:59')";               
                }               
                
                if(!empty($params['dateClass'])){
                    $where_query .=  " AND ( rn5.name = '".$params['dateClass'] . "' OR rn2.name = '".$params['dateClass'] . "' OR rn3.name = '".$params['dateClass'] . "' OR rn4.name = '".$params['dateClass'] . "')";
                }

                if(!empty($params['dateBoard'])){
                    $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn6.name = '".$params['dateBoard'] . "' OR rn3.name = '".$params['dateBoard'] . "' OR rn4.name = '".$params['dateBoard'] . "' OR rn5.name = '".$params['dateBoard'] . "')";
                }
                
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
             $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
             $select->join(array("tn" => 't_notes'), "tn.user_id=user.user_id", array('notes_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                     ->join(array("cbr" => 'custom_board_rack'), "tn.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                     ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                     ->join(array("rr1" => 'resource_rack'), "tn.chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tn.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')), 'left')
                     ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                     ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                     ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                     ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                     ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                     ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                     ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                     ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                     ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                    ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                    ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');

            }
            
            if($type == 'test_attempt_count') {
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                   $where_query .=  " AND (qs.set_date_added >= '".$params['fromDate']." 00:00:00' AND qs.set_date_added <= '".$params['toDate'] . " 23:59:59')";               
                }
         
                if(!empty($params['dateClass'])){
                    $where_query .=  " AND ( rn5.name = '".$params['dateClass'] . "' OR rn2.name = '".$params['dateClass'] . "' OR rn3.name = '".$params['dateClass'] . "' OR rn4.name = '".$params['dateClass'] . "')";
                }

                if(!empty($params['dateBoard'])){
                    $where_query .=  " AND ( cb.board_name = '".$params['dateBoard']."' OR rn6.name = '".$params['dateBoard'] . "' OR rn3.name = '".$params['dateBoard'] . "' OR rn4.name = '".$params['dateBoard'] . "' OR rn5.name = '".$params['dateBoard'] . "')";
                }
                
                if(!empty($params['dataPass'])){
                    
                    if($params['dataPass'] == 'passed') {
                        $where_query .=  " AND ( qs.set_status = 'passed')";
                    } else {
                        $where_query .=  " AND ( qs.set_status IN ('passed', 'running', 'failed'))";
                    }
                }
                
                 $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
                 $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                 $select->join(array("qs" => 'quiz_set'), "qs.user_id=user.user_id", array('test_attempt_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                         ->join(array("qsc" => 'quiz_set_chapter'), "qs.set_id=qsc.set_id", array('board_classs_subject_chapter_id'), 'left')
                        ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                        ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                        ->join(array("rr1" => 'resource_rack'), "qsc.board_classs_subject_chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                        ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')), 'left')
                        ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                        ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                        ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                        ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                        ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                        ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                        ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                        ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                        ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                        ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                        ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                        ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                        ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                        ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                        ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') 
                        ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left')
                        ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                        ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                        ->where($where_query)
                        ->group('user.user_id');

           }
            
        }
        
        
        if($module == 'day_wise') {
            
            $where_query = "1";
        
            if(!empty($params['dataSchoolCode'])){
                $where_query .=  " AND (ssd.school_code = '". $params['dataSchoolCode'] ."')";               
            }

            if(!empty($type)){

                 if($type == 'all'){ 

                    if(!empty($params['fromDate']) && !empty($params['toDate'])){
                        $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                    }

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left');
                    $select->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array('class_name' => new \Zend\Db\Sql\Expression('rn1.name'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn2.name END')), 'left');
                    $select->join(array("rr1" => 'resource_rack'), "cbr.rack_id=rr1.rack_id", array(), 'left');
                    $select->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left');
                    $select->join(array("rr2" => 'resource_rack'), "user.board_id=rr2.rack_id", array(), 'left');
                    $select->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array(), 'left');
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id', 'paid_price'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' THEN `tut`.`transaction_amount` ELSE SUM(paid_amount) END"), 'currency_type','discount_amount'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' OR tut.transaction_amount =0 THEN `tut`.`transaction_discount` ELSE SUM(discount_amount) END"), 'subscribed_user_type' => new \Zend\Db\Sql\Expression("CASE WHEN (select count(*) from user as user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id INNER JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE ((tdc1.coupon_type!='test') OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) AND user.user_id=user1.user_id AND tut1.pkg_payment_type=tut.pkg_payment_type AND tup1.package_name=tup.package_name AND tut1.purchase_date=tut.purchase_date) > 0 THEN 'YES' ELSE 'NO' END")), 'left');
                    $select->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left');
                    $select->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left');
                    $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','purchase_date'=>'purchase_date','pkg_payment_type','transaction_discount', 'transaction_amount', 'payment_mode' => new \Zend\Db\Sql\Expression("CASE WHEN tut.pkg_payment_type='offline' THEN 'Offline' WHEN tut.pkg_payment_type != 'offline' THEN 'Online' ELSE '' END")), 'left');
                    $select->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') ;
                    $select->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left');
                    $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') ;
                    $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_name' => new \Zend\Db\Sql\Expression("CASE WHEN user.school_name ='' THEN ssd.school_name ELSE user.school_name END"), 'school_code'), 'left');
                    $select->group('user.user_id');
                    $select->where($where_query);
                 }

                 if($type == 'login_more_twice'){ 

                    if(!empty($params['fromDate']) && !empty($params['toDate'])){
                        $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                        //$where_query .=  " AND (( ul.login >= '".$params['fromDate']." 00:00:00' AND ul.login <= '".$params['toDate'] . " 23:59:59') OR ( ul.logout >= '".$params['fromDate']." 00:00:00' AND ul.logout <= '".$params['toDate'] . " 23:59:59'))";               
                    }

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('(select count(*) from user_logs ul1 where ul1.user_id=user.user_id)')), 'inner');
                    $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left');
                    $select->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array('class_name' => new \Zend\Db\Sql\Expression('rn1.name'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn2.name END')), 'left');
                    $select->join(array("rr1" => 'resource_rack'), "cbr.rack_id=rr1.rack_id", array(), 'left');
                    $select->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left');
                    $select->join(array("rr2" => 'resource_rack'), "user.board_id=rr2.rack_id", array(), 'left');
                    $select->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array(), 'left');
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id', 'paid_price'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' THEN `tut`.`transaction_amount` ELSE SUM(paid_amount) END"), 'currency_type','discount_amount'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' OR tut.transaction_amount =0 THEN `tut`.`transaction_discount` ELSE SUM(discount_amount) END"), 'subscribed_user_type' => new \Zend\Db\Sql\Expression("CASE WHEN (select count(*) from user as user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id INNER JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE ((tdc1.coupon_type!='test') OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) AND user.user_id=user1.user_id AND tut1.pkg_payment_type=tut.pkg_payment_type AND tup1.package_name=tup.package_name AND tut1.purchase_date=tut.purchase_date) > 0 THEN 'YES' ELSE 'NO' END")), 'left');
                    $select->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left');
                    $select->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left');
                    $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','purchase_date'=>'purchase_date','pkg_payment_type','transaction_discount', 'transaction_amount', 'payment_mode' => new \Zend\Db\Sql\Expression("CASE WHEN tut.pkg_payment_type='offline' THEN 'Offline' WHEN tut.pkg_payment_type != 'offline' THEN 'Online' ELSE '' END")), 'left');
                    $select->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') ;
                    $select->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left');
                    $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') ;
                    $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_name' => new \Zend\Db\Sql\Expression("CASE WHEN user.school_name ='' THEN ssd.school_name ELSE user.school_name END"), 'school_code'), 'left');
                    $select->where($where_query);
                    $select->group('user.user_id');
                    $select->where("user.user_id NOT IN (select user1.user_id from user user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id LEFT JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE (tdc1.coupon_type!='test' OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) group by user1.user_id)");
                    $select->having('login_count > 2');
                 }

                 if($type == 'login_less_twice'){ 

                    if(!empty($params['fromDate']) && !empty($params['toDate'])){
                        $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                        //$where_query .=  " AND (( ul.login >= '".$params['fromDate']." 00:00:00' AND ul.login <= '".$params['toDate'] . " 23:59:59') OR ( ul.logout >= '".$params['fromDate']." 00:00:00' AND ul.logout <= '".$params['toDate'] . " 23:59:59'))";               
                    }

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('(select count(*) from user_logs ul1 where ul1.user_id=user.user_id)')), 'inner');
                    $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left');
                    $select->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array('class_name' => new \Zend\Db\Sql\Expression('rn1.name'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn2.name END')), 'left');
                    $select->join(array("rr1" => 'resource_rack'), "cbr.rack_id=rr1.rack_id", array(), 'left');
                    $select->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left');
                    $select->join(array("rr2" => 'resource_rack'), "user.board_id=rr2.rack_id", array(), 'left');
                    $select->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array(), 'left');
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('paid_price'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' THEN `tut`.`transaction_amount` ELSE SUM(paid_amount) END"), 'currency_type','discount_amount'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' OR tut.transaction_amount =0 THEN `tut`.`transaction_discount` ELSE SUM(discount_amount) END"), 'subscribed_user_type' => new \Zend\Db\Sql\Expression("CASE WHEN (select count(*) from user as user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id INNER JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE ((tdc1.coupon_type!='test') OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) AND user.user_id=user1.user_id AND tut1.pkg_payment_type=tut.pkg_payment_type AND tup1.package_name=tup.package_name AND tut1.purchase_date=tut.purchase_date) > 0 THEN 'YES' ELSE 'NO' END")), 'left');
                    $select->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left');
                    $select->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left');
                    $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','purchase_date'=>'purchase_date','pkg_payment_type','transaction_discount', 'transaction_amount', 'payment_mode' => new \Zend\Db\Sql\Expression("CASE WHEN tut.pkg_payment_type='offline' THEN 'Offline' WHEN tut.pkg_payment_type != 'offline' THEN 'Online' ELSE '' END")), 'left');
                    $select->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') ;
                    $select->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left');
                    $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') ;
                    $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_name' => new \Zend\Db\Sql\Expression("CASE WHEN user.school_name ='' THEN ssd.school_name ELSE user.school_name END"), 'school_code'), 'left');
                    $select->where($where_query);
                    $select->group('user.user_id');
                    $select->where("user.user_id NOT IN (select user1.user_id from user user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id LEFT JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE (tdc1.coupon_type!='test' OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3))");
                    $select->having('login_count <= 2');
                 }

                 if($type == 'paid_users'){ 

                    if(!empty($params['fromDate']) && !empty($params['toDate'])){
                        $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                    }

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left');
                    $select->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array('class_name' => new \Zend\Db\Sql\Expression('rn1.name'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn2.name END')), 'left');
                    $select->join(array("rr1" => 'resource_rack'), "cbr.rack_id=rr1.rack_id", array(), 'left');
                    $select->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left');
                    $select->join(array("rr2" => 'resource_rack'), "user.board_id=rr2.rack_id", array(), 'left');
                    $select->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array(), 'left');
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id', 'paid_price'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' THEN `tut`.`transaction_amount` ELSE SUM(paid_amount) END"), 'currency_type','discount_amount'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' OR tut.transaction_amount =0 THEN `tut`.`transaction_discount` ELSE SUM(discount_amount) END"), 'subscribed_user_type' => new \Zend\Db\Sql\Expression("CASE WHEN (select count(*) from user as user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id INNER JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE ((tdc1.coupon_type!='test') OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) AND user.user_id=user1.user_id AND tut1.pkg_payment_type=tut.pkg_payment_type AND tup1.package_name=tup.package_name AND tut1.purchase_date=tut.purchase_date) > 0 THEN 'YES' ELSE 'NO' END")), 'inner');
                    $select->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left');
                    $select->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left');
                    $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','purchase_date'=>'purchase_date','pkg_payment_type','transaction_discount', 'transaction_amount', 'payment_mode' => new \Zend\Db\Sql\Expression("CASE WHEN tut.pkg_payment_type='offline' THEN 'Offline' WHEN tut.pkg_payment_type != 'offline' THEN 'Online' ELSE '' END")), 'left');
                    $select->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') ;
                    $select->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left');
                    $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') ;
                    $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_name' => new \Zend\Db\Sql\Expression("CASE WHEN user.school_name ='' THEN ssd.school_name ELSE user.school_name END"), 'school_code'), 'left');
                    $select->where($where_query);
                    $select->where("(tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)");
                    $select->where("tup.status IN(1,2,3)");
                    $select->group('user.user_id');
                 }

                 if($type == 'expired_in_15'){ 

                    if(!empty($params['fromDate']) && !empty($params['toDate'])){
                       // $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                         $halfMonthDays = date('Y-m-d', strtotime('+ 15 days', strtotime($params['fromDate'])));
                         //$where_query .=  " AND (tup.valid_till >= CURDATE() AND DATE(tup.valid_till) <= '" . $halfMonthDays . "')"; 
                         $fromDays = date('Y-m-d', strtotime($params['fromDate']));
                         $where_query .=  " AND ( DATE(tup.valid_till) >= '" . $fromDays . "') AND ( DATE(tup.valid_till) <= '" . $halfMonthDays . "')"; 
                    }

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left');
                    $select->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array('class_name' => new \Zend\Db\Sql\Expression('rn1.name'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn2.name END')), 'left');
                    $select->join(array("rr1" => 'resource_rack'), "cbr.rack_id=rr1.rack_id", array(), 'left');
                    $select->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left');
                    $select->join(array("rr2" => 'resource_rack'), "user.board_id=rr2.rack_id", array(), 'left');
                    $select->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array(), 'left');
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('valid_till', 'paid_price'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' THEN `tut`.`transaction_amount` ELSE SUM(paid_amount) END"), 'currency_type','discount_amount'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' OR tut.transaction_amount =0 THEN `tut`.`transaction_discount` ELSE SUM(discount_amount) END"), 'subscribed_user_type' => new \Zend\Db\Sql\Expression("CASE WHEN (select count(*) from user as user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id INNER JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE ((tdc1.coupon_type!='test') OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) AND user.user_id=user1.user_id AND tut1.pkg_payment_type=tut.pkg_payment_type AND tup1.package_name=tup.package_name AND tut1.purchase_date=tut.purchase_date) > 0 THEN 'YES' ELSE 'NO' END")), 'inner');
                    $select->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left');
                    $select->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left');
                    $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','purchase_date'=>'purchase_date','pkg_payment_type','transaction_discount', 'transaction_amount', 'payment_mode' => new \Zend\Db\Sql\Expression("CASE WHEN tut.pkg_payment_type='offline' THEN 'Offline' WHEN tut.pkg_payment_type != 'offline' THEN 'Online' ELSE '' END")), 'left');
                    $select->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') ;
                    $select->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left');
                    $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') ;
                    $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_name' => new \Zend\Db\Sql\Expression("CASE WHEN user.school_name ='' THEN ssd.school_name ELSE user.school_name END"), 'school_code'), 'left');
                    $select->where($where_query);
                    $select->where("(tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)");
                    $select->group('user.user_id');
                 }

                 if($type == 'expired_in_30'){ 

                    if(!empty($params['fromDate']) && !empty($params['toDate'])){
                        //$where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                        $aMonthDays = date('Y-m-d', strtotime('+ 30 days', strtotime($params['fromDate'])));
                        //$where_query .=  " AND (tup.valid_till >= CURDATE() AND DATE(tup.valid_till) <= '" . $aMonthDays . "')";
                        $halfPluseMonthDays = date('Y-m-d', strtotime('+ 16 days', strtotime($params['fromDate'])));
                        $where_query .=  " AND ( DATE(tup.valid_till) >= '" . $halfPluseMonthDays . "') AND ( DATE(tup.valid_till) <= '" . $aMonthDays . "')";
                        //$where_query .=  " AND ( DATE(tup.valid_till) <= '" . $aMonthDays . "')";
                    }

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left');
                    $select->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array('class_name' => new \Zend\Db\Sql\Expression('rn1.name'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn2.name END')), 'left');
                    $select->join(array("rr1" => 'resource_rack'), "cbr.rack_id=rr1.rack_id", array(), 'left');
                    $select->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left');
                    $select->join(array("rr2" => 'resource_rack'), "user.board_id=rr2.rack_id", array(), 'left');
                    $select->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array(), 'left');
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('valid_till', 'paid_price'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' THEN `tut`.`transaction_amount` ELSE SUM(paid_amount) END"), 'currency_type','discount_amount'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' OR tut.transaction_amount =0 THEN `tut`.`transaction_discount` ELSE SUM(discount_amount) END"), 'subscribed_user_type' => new \Zend\Db\Sql\Expression("CASE WHEN (select count(*) from user as user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id INNER JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE ((tdc1.coupon_type!='test') OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) AND user.user_id=user1.user_id AND tut1.pkg_payment_type=tut.pkg_payment_type AND tup1.package_name=tup.package_name AND tut1.purchase_date=tut.purchase_date) > 0 THEN 'YES' ELSE 'NO' END")), 'inner');
                    $select->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left');
                    $select->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left');
                    $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','purchase_date'=>'purchase_date','pkg_payment_type','transaction_discount', 'transaction_amount', 'payment_mode' => new \Zend\Db\Sql\Expression("CASE WHEN tut.pkg_payment_type='offline' THEN 'Offline' WHEN tut.pkg_payment_type != 'offline' THEN 'Online' ELSE '' END")), 'left');
                    $select->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') ;
                    $select->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left');
                    $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') ;
                    $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_name' => new \Zend\Db\Sql\Expression("CASE WHEN user.school_name ='' THEN ssd.school_name ELSE user.school_name END"), 'school_code'), 'left');
                    $select->where($where_query);
                    $select->where("(tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)");
                    $select->group('user.user_id');
                 }

                 if($type == 'daywise_consum_5'){ 

                    if(!empty($params['fromDate']) && !empty($params['toDate'])){
                        $where_sub_query =  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                        $where_query .=  $where_sub_query;
                        $where_query .=  " AND ( (DATE(ul.login) >= DATE(user.create_time) AND DATE(ul.login) <= DATE_ADD(user.create_time, INTERVAL 5 day)) OR (DATE(ul.logout) >=DATE(user.create_time) AND DATE(ul.logout) <= DATE_ADD(user.create_time, INTERVAL 5 day)))";   
                    }

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner');
                    $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left');
                    $select->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array('class_name' => new \Zend\Db\Sql\Expression('rn1.name'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn2.name END')), 'left');
                    $select->join(array("rr1" => 'resource_rack'), "cbr.rack_id=rr1.rack_id", array(), 'left');
                    $select->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left');
                    $select->join(array("rr2" => 'resource_rack'), "user.board_id=rr2.rack_id", array(), 'left');
                    $select->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array(), 'left');
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id', 'paid_price'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' THEN `tut`.`transaction_amount` ELSE SUM(paid_amount) END"), 'currency_type','discount_amount'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' OR tut.transaction_amount =0 THEN `tut`.`transaction_discount` ELSE SUM(discount_amount) END"), 'subscribed_user_type' => new \Zend\Db\Sql\Expression("CASE WHEN (select count(*) from user as user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id INNER JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE ((tdc1.coupon_type!='test') OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) AND user.user_id=user1.user_id AND tut1.pkg_payment_type=tut.pkg_payment_type AND tup1.package_name=tup.package_name AND tut1.purchase_date=tut.purchase_date) > 0 THEN 'YES' ELSE 'NO' END")), 'left');
                    $select->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left');
                    $select->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left');
                    $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','purchase_date'=>'purchase_date','pkg_payment_type','transaction_discount', 'transaction_amount', 'payment_mode' => new \Zend\Db\Sql\Expression("CASE WHEN tut.pkg_payment_type='offline' THEN 'Offline' WHEN tut.pkg_payment_type != 'offline' THEN 'Online' ELSE '' END")), 'left');
                    $select->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') ;
                    $select->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left');
                    $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') ;
                    $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_name' => new \Zend\Db\Sql\Expression("CASE WHEN user.school_name ='' THEN ssd.school_name ELSE user.school_name END"), 'school_code'), 'left');
                    $select->where($where_query);
                    $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 6 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 10 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 6 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 10 day)))) GROUP BY user1.user_id)");
                    $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 11 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 15 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 11 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 15 day)))) GROUP BY user1.user_id)");
                    $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 16 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 30 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 16 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 30 day)))) GROUP BY user1.user_id)");
                    $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 31 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 45 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 31 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 45 day)))) GROUP BY user1.user_id)");
                    $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 60 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 60 day)))) GROUP BY user1.user_id)");
                    $select->group('user.user_id');
                 }

                 if($type == 'daywise_consum_10'){ 

                    if(!empty($params['fromDate']) && !empty($params['toDate'])){
                        $where_sub_query =  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                        $where_query .=  $where_sub_query;
                        $where_query .=  " AND ( (DATE(ul.login) >=DATE_ADD(user.create_time, INTERVAL 6 day) AND DATE(ul.login) <= DATE_ADD(user.create_time, INTERVAL 10 day)) OR (DATE(ul.logout) >=DATE_ADD(user.create_time, INTERVAL 6 day) AND (DATE(ul.logout) <= DATE_ADD(user.create_time, INTERVAL 10 day))))";   
                    }

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner');
                    $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left');
                    $select->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array('class_name' => new \Zend\Db\Sql\Expression('rn1.name'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn2.name END')), 'left');
                    $select->join(array("rr1" => 'resource_rack'), "cbr.rack_id=rr1.rack_id", array(), 'left');
                    $select->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left');
                    $select->join(array("rr2" => 'resource_rack'), "user.board_id=rr2.rack_id", array(), 'left');
                    $select->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array(), 'left');
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id', 'paid_price'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' THEN `tut`.`transaction_amount` ELSE SUM(paid_amount) END"), 'currency_type','discount_amount'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' OR tut.transaction_amount =0 THEN `tut`.`transaction_discount` ELSE SUM(discount_amount) END"), 'subscribed_user_type' => new \Zend\Db\Sql\Expression("CASE WHEN (select count(*) from user as user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id INNER JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE ((tdc1.coupon_type!='test') OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) AND user.user_id=user1.user_id AND tut1.pkg_payment_type=tut.pkg_payment_type AND tup1.package_name=tup.package_name AND tut1.purchase_date=tut.purchase_date) > 0 THEN 'YES' ELSE 'NO' END")), 'left');
                    $select->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left');
                    $select->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left');
                    $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','purchase_date'=>'purchase_date','pkg_payment_type','transaction_discount', 'transaction_amount', 'payment_mode' => new \Zend\Db\Sql\Expression("CASE WHEN tut.pkg_payment_type='offline' THEN 'Offline' WHEN tut.pkg_payment_type != 'offline' THEN 'Online' ELSE '' END")), 'left');
                    $select->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') ;
                    $select->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left');
                    $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') ;
                    $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_name' => new \Zend\Db\Sql\Expression("CASE WHEN user.school_name ='' THEN ssd.school_name ELSE user.school_name END"), 'school_code'), 'left');
                    $select->where($where_query);
                    $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 11 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 15 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 11 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 15 day)))) GROUP BY user1.user_id)");
                    $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 16 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 30 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 16 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 30 day)))) GROUP BY user1.user_id)");
                    $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 31 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 45 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 31 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 45 day)))) GROUP BY user1.user_id)");
                    $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 60 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 60 day)))) GROUP BY user1.user_id)");
                    $select->group('user.user_id');
                 }

                 if($type == 'daywise_consum_15'){ 

                    if(!empty($params['fromDate']) && !empty($params['toDate'])){
                        $where_sub_query =  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                        $where_query .=  $where_sub_query;
                        $where_query .=  " AND ( (DATE(ul.login) >=DATE_ADD(user.create_time, INTERVAL 11 day) AND DATE(ul.login) <= DATE_ADD(user.create_time, INTERVAL 15 day)) OR (DATE(ul.logout) >=DATE_ADD(user.create_time, INTERVAL 11 day) AND (DATE(ul.logout) <= DATE_ADD(user.create_time, INTERVAL 15 day))))"; 
                    }

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner');
                    $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left');
                    $select->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array('class_name' => new \Zend\Db\Sql\Expression('rn1.name'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn2.name END')), 'left');
                    $select->join(array("rr1" => 'resource_rack'), "cbr.rack_id=rr1.rack_id", array(), 'left');
                    $select->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left');
                    $select->join(array("rr2" => 'resource_rack'), "user.board_id=rr2.rack_id", array(), 'left');
                    $select->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array(), 'left');
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id', 'paid_price'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' THEN `tut`.`transaction_amount` ELSE SUM(paid_amount) END"), 'currency_type','discount_amount'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' OR tut.transaction_amount =0 THEN `tut`.`transaction_discount` ELSE SUM(discount_amount) END"), 'subscribed_user_type' => new \Zend\Db\Sql\Expression("CASE WHEN (select count(*) from user as user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id INNER JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE ((tdc1.coupon_type!='test') OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) AND user.user_id=user1.user_id AND tut1.pkg_payment_type=tut.pkg_payment_type AND tup1.package_name=tup.package_name AND tut1.purchase_date=tut.purchase_date) > 0 THEN 'YES' ELSE 'NO' END")), 'left');
                    $select->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left');
                    $select->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left');
                    $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','purchase_date'=>'purchase_date','pkg_payment_type','transaction_discount', 'transaction_amount', 'payment_mode' => new \Zend\Db\Sql\Expression("CASE WHEN tut.pkg_payment_type='offline' THEN 'Offline' WHEN tut.pkg_payment_type != 'offline' THEN 'Online' ELSE '' END")), 'left');
                    $select->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') ;
                    $select->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left');
                    $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') ;
                    $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_name' => new \Zend\Db\Sql\Expression("CASE WHEN user.school_name ='' THEN ssd.school_name ELSE user.school_name END"), 'school_code'), 'left');
                    $select->where($where_query);
                    $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 16 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 30 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 16 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 30 day)))) GROUP BY user1.user_id)");
                    $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 31 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 45 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 31 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 45 day)))) GROUP BY user1.user_id)");
                    $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 60 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 60 day)))) GROUP BY user1.user_id)");
                    $select->group('user.user_id');
                 }

                 if($type == 'daywise_consum_30'){ 

                    if(!empty($params['fromDate']) && !empty($params['toDate'])){
                        $where_sub_query =  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                        $where_query .=  $where_sub_query;
                        $where_query .=  " AND ( (DATE(ul.login) >=DATE_ADD(user.create_time, INTERVAL 16 day) AND DATE(ul.login) <= DATE_ADD(user.create_time, INTERVAL 30 day)) OR (DATE(ul.logout) >=DATE_ADD(user.create_time, INTERVAL 16 day) AND (DATE(ul.logout) <= DATE_ADD(user.create_time, INTERVAL 30 day))))"; 
                    }

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner');
                    $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left');
                    $select->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array('class_name' => new \Zend\Db\Sql\Expression('rn1.name'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn2.name END')), 'left');
                    $select->join(array("rr1" => 'resource_rack'), "cbr.rack_id=rr1.rack_id", array(), 'left');
                    $select->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left');
                    $select->join(array("rr2" => 'resource_rack'), "user.board_id=rr2.rack_id", array(), 'left');
                    $select->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array(), 'left');
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id', 'paid_price'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' THEN `tut`.`transaction_amount` ELSE SUM(paid_amount) END"), 'currency_type','discount_amount'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' OR tut.transaction_amount =0 THEN `tut`.`transaction_discount` ELSE SUM(discount_amount) END"), 'subscribed_user_type' => new \Zend\Db\Sql\Expression("CASE WHEN (select count(*) from user as user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id INNER JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE ((tdc1.coupon_type!='test') OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) AND user.user_id=user1.user_id AND tut1.pkg_payment_type=tut.pkg_payment_type AND tup1.package_name=tup.package_name AND tut1.purchase_date=tut.purchase_date) > 0 THEN 'YES' ELSE 'NO' END")), 'left');
                    $select->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left');
                    $select->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left');
                    $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','purchase_date'=>'purchase_date','pkg_payment_type','transaction_discount', 'transaction_amount', 'payment_mode' => new \Zend\Db\Sql\Expression("CASE WHEN tut.pkg_payment_type='offline' THEN 'Offline' WHEN tut.pkg_payment_type != 'offline' THEN 'Online' ELSE '' END")), 'left');
                    $select->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') ;
                    $select->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left');
                    $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') ;
                    $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_name' => new \Zend\Db\Sql\Expression("CASE WHEN user.school_name ='' THEN ssd.school_name ELSE user.school_name END"), 'school_code'), 'left');
                    $select->where($where_query);
                    $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 31 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 45 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 31 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 45 day)))) GROUP BY user1.user_id)");
                    $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 60 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 60 day)))) GROUP BY user1.user_id)");
                    $select->group('user.user_id');
                 }

                 if($type == 'daywise_consum_45'){ 

                    if(!empty($params['fromDate']) && !empty($params['toDate'])){
                        $where_sub_query =  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                        $where_query .=  $where_sub_query;
                        $where_query .=  " AND ( (DATE(ul.login) >=DATE_ADD(user.create_time, INTERVAL 31 day) AND DATE(ul.login) <= DATE_ADD(user.create_time, INTERVAL 45 day)) OR (DATE(ul.logout) >=DATE_ADD(user.create_time, INTERVAL 31 day) AND (DATE(ul.logout) <= DATE_ADD(user.create_time, INTERVAL 45 day))))"; 
                    }

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner');
                    $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left');
                    $select->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array('class_name' => new \Zend\Db\Sql\Expression('rn1.name'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn2.name END')), 'left');
                    $select->join(array("rr1" => 'resource_rack'), "cbr.rack_id=rr1.rack_id", array(), 'left');
                    $select->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left');
                    $select->join(array("rr2" => 'resource_rack'), "user.board_id=rr2.rack_id", array(), 'left');
                    $select->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array(), 'left');
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id', 'paid_price'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' THEN `tut`.`transaction_amount` ELSE SUM(paid_amount) END"), 'currency_type','discount_amount'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' OR tut.transaction_amount =0 THEN `tut`.`transaction_discount` ELSE SUM(discount_amount) END"), 'subscribed_user_type' => new \Zend\Db\Sql\Expression("CASE WHEN (select count(*) from user as user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id INNER JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE ((tdc1.coupon_type!='test') OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) AND user.user_id=user1.user_id AND tut1.pkg_payment_type=tut.pkg_payment_type AND tup1.package_name=tup.package_name AND tut1.purchase_date=tut.purchase_date) > 0 THEN 'YES' ELSE 'NO' END")), 'left');
                    $select->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left');
                    $select->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left');
                    $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','purchase_date'=>'purchase_date','pkg_payment_type','transaction_discount', 'transaction_amount', 'payment_mode' => new \Zend\Db\Sql\Expression("CASE WHEN tut.pkg_payment_type='offline' THEN 'Offline' WHEN tut.pkg_payment_type != 'offline' THEN 'Online' ELSE '' END")), 'left');
                    $select->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') ;
                    $select->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left');
                    $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') ;
                    $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_name' => new \Zend\Db\Sql\Expression("CASE WHEN user.school_name ='' THEN ssd.school_name ELSE user.school_name END"), 'school_code'), 'left');
                    $select->where($where_query);
                    $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 60 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 60 day)))) GROUP BY user1.user_id)");
                    $select->group('user.user_id');
                 }

                 if($type == 'daywise_consum_60'){ 

                    if(!empty($params['fromDate']) && !empty($params['toDate'])){
                        $where_sub_query =  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                        $where_query .=  $where_sub_query;
                        $where_query .=  " AND ( (DATE(ul.login) >=DATE_ADD(user.create_time, INTERVAL 46 day) AND DATE(ul.login) <= DATE_ADD(user.create_time, INTERVAL 60 day)) OR (DATE(ul.logout) >=DATE_ADD(user.create_time, INTERVAL 46 day) AND (DATE(ul.logout) <= DATE_ADD(user.create_time, INTERVAL 60 day))))"; 
                    }

                    $select = $this->tableGateway->getSql()->select()->columns($tableColumns);
                    $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner');
                    $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left');
                    $select->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array('class_name' => new \Zend\Db\Sql\Expression('rn1.name'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name ELSE rn2.name END')), 'left');
                    $select->join(array("rr1" => 'resource_rack'), "cbr.rack_id=rr1.rack_id", array(), 'left');
                    $select->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left');
                    $select->join(array("rr2" => 'resource_rack'), "user.board_id=rr2.rack_id", array(), 'left');
                    $select->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array(), 'left');
                    $select->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id', 'paid_price'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' THEN `tut`.`transaction_amount` ELSE SUM(paid_amount) END"), 'currency_type','discount_amount'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' OR tut.transaction_amount =0 THEN `tut`.`transaction_discount` ELSE SUM(discount_amount) END"), 'subscribed_user_type' => new \Zend\Db\Sql\Expression("CASE WHEN (select count(*) from user as user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id INNER JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE ((tdc1.coupon_type!='test') OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) AND user.user_id=user1.user_id AND tut1.pkg_payment_type=tut.pkg_payment_type AND tup1.package_name=tup.package_name AND tut1.purchase_date=tut.purchase_date) > 0 THEN 'YES' ELSE 'NO' END")), 'left');
                    $select->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left');
                    $select->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left');
                    $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','purchase_date'=>'purchase_date','pkg_payment_type','transaction_discount', 'transaction_amount', 'payment_mode' => new \Zend\Db\Sql\Expression("CASE WHEN tut.pkg_payment_type='offline' THEN 'Offline' WHEN tut.pkg_payment_type != 'offline' THEN 'Online' ELSE '' END")), 'left');
                    $select->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left') ;
                    $select->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left');
                    $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') ;
                    $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_name' => new \Zend\Db\Sql\Expression("CASE WHEN user.school_name ='' THEN ssd.school_name ELSE user.school_name END"), 'school_code'), 'left');
                    $select->where($where_query);
                    $select->group('user.user_id');
                 }
            }
            
        }
        
        
        $resultSet = $this->tableGateway->selectWith($select);
        //echo '<pre>';
        //print_r($resultSet); die;
        $resultSet->buffer();
        return $resultSet;
    }

    public function mentorcount($userId) {
        try {
            $select = $this->tableGateway->getSql()->select()->columns(array('user_id'));
            $select->join(array("tsm" => 't_student_and_mentor'), "tsm.mentor_id=user.user_id", array('mentor_count' => new \Zend\Db\Sql\Expression('count(distinct tsm.mentor_id)')), 'inner')
                    ->where('tsm.student_id=' . $userId)
                    ->where('tsm.status=1');
            $result = $this->tableGateway->selectWith($select);
        } catch (Exception $e) {
            echo $e;
        }
        return $result;
    }

    public function groupcount($userId) {
        try {
            $select = $this->tableGateway->getSql()->select()->columns(array('user_id'));
            $select->join(array("tug" => 't_user_groups'), "tug.user_id=user.user_id", array('group_member_count' => new \Zend\Db\Sql\Expression('count(distinct tug.friend_id)')), 'inner')
                    ->join(array("tup" => 't_user_package'), "tup.user_id=user.user_id", array('package_id'), 'left')
                    ->where('user.user_id=' . $userId)
                    ->where('tug.group_status=1');
            $result = $this->tableGateway->selectWith($select);
        } catch (Exception $e) {
            echo $e;
        }

        return $result;
    }

    public function parentcount($userId) {
        try {
            $select = $this->tableGateway->getSql()->select()->columns(array('user_id'));
            $select->join(array("tpc" => "t_parent_and_child"), "tpc.parent_id=user.user_id", array("parent_count" => new \Zend\Db\Sql\Expression("count(*)")), 'inner')
                    ->where('tpc.child_id=' . $userId)
                    ->where('tpc.status=1');
            $result = $this->tableGateway->selectWith($select); //echo'<pre>';print_r($result);die;
        } catch (Exception $e) {
            echo $e;
        }
        return $result;
    }

    public function childcount($userId) {
        try {
            $select = $this->tableGateway->getSql()->select()->columns(array('user_id'));
            $select->join(array("tpc" => "t_parent_and_child"), "tpc.child_id=user.user_id", array("child_count" => new \Zend\Db\Sql\Expression("count(distinct tpc.child_id)")), 'left')
                   ->where('tpc.parent_id=' . $userId)
                   ->where('tpc.status=1');
            $result = $this->tableGateway->selectWith($select);
        } catch (Exception $e) {
            echo $e;
        }
        return $result;
    }
    
    public function parentchilddetail($user_id)
    {
        $select = $this->tableGateway->getSql()->select()->columns(array('user_id'));
        $select->join(array("tpc" => 't_parent_and_child'),'user.user_id=tpc.child_id',  array('id','parent_id'), 'left');            
        //$select->where('tpc.parent_id="'.$parent_id.'"');
        $select->where('tpc.child_id="'.$user_id.'"');
        $select->where('tpc.status="1"');
        return $resultSet = $this->tableGateway->selectWith($select);
    }
    
    public function learnercount($userId){
        try {
            $select = $this->tableGateway->getSql()->select()->columns(array('user_id'));
            $select->join(array("pr" => "progress_report"), "pr.user_id=user.user_id", array('learn_duration'=> new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')),'inner')
                   ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                   ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Learn'"), array(), 'right')
                   ->where('user.user_id=' . $userId)
                   ->where('pr.status=1');
            $result = $this->tableGateway->selectWith($select); //echo'<pre>';print_r($result);die;
        } catch (Exception $e) {
            echo $e;
        }
        return $result;
    }
    
    public function notescount($userId){
        try {
            $select = $this->tableGateway->getSql()->select()->columns(array('user_id'));
            $select->join(array("tn" => "t_notes"), "tn.user_id=user.user_id", array('notes_count'=> new \Zend\Db\Sql\Expression('count(*)')),'inner')
                   ->where('user.user_id=' . $userId)
                   ->where('tn.status=1');
            $result = $this->tableGateway->selectWith($select); //echo'<pre>';print_r($result);die;
        } catch (Exception $e) {
            echo $e;
        }
        return $result;
    }
    
    public function schedulecount($userId){
        try {
            $select = $this->tableGateway->getSql()->select()->columns(array('user_id'));
            $select->join(array("tlp" => "t_lesson_plan"), "tlp.user_id=user.user_id", array('schedule_count'=> new \Zend\Db\Sql\Expression('count(*)')),'inner')
                   ->where('user.user_id=' . $userId)
                   ->where('tlp.status=1');
            $result = $this->tableGateway->selectWith($select); //echo'<pre>';print_r($result);die;
        } catch (Exception $e) {
            echo $e;
        }
        return $result;
    }
    
    public function getUsersConsumptionCount($userId){
        try {
            $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'group_member_count' => new \Zend\Db\Sql\Expression('(select count(*) from t_user_groups AS tug where tug.user_id=user.user_id)'), 'learn_duration' => new \Zend\Db\Sql\Expression('(select sum(TIME_TO_SEC(total_time))/60 from progress_report pr join mapped_dashboard_group mdg on mdg.service_id=pr.board_service_id join dashboard_group dg on mdg.dashboard_group_id=dg.dashboard_group_id where pr.user_id=user.user_id and dg.group_name="Learn")'), 'notes_count' => new \Zend\Db\Sql\Expression('(select count(*) from t_notes tn where tn.user_id=user.user_id)'), 'schedule_count' => new \Zend\Db\Sql\Expression('(select count(*) from t_lesson_plan tlp where tlp.user_id=user.user_id)')))
                    /*->join(array("tsm" => 't_student_and_mentor'), new Expression("tsm.mentor_id=user.user_id AND tsm.status=1"), array('mentor_count' => new \Zend\Db\Sql\Expression('count(distinct tsm.mentor_id)')), 'left')
                    ->join(array("tug" => 't_user_groups'), new Expression("tug.user_id=user.user_id AND tug.group_status=1"), array('group_member_count' => new \Zend\Db\Sql\Expression('count(distinct tug.friend_id)')), 'left')
                    ->join(array("tpc" => "t_parent_and_child"), new Expression("tpc.parent_id=user.user_id AND tpc.status=1"), array("parent_count" => new \Zend\Db\Sql\Expression("count(distinct tpc.parent_id)")), 'left')
                    ->join(array("tpc1" => "t_parent_and_child"), new Expression("tpc1.child_id=user.user_id AND tpc1.status=1"), array("child_count" => new \Zend\Db\Sql\Expression("count(distinct tpc1.child_id)")), 'left')
                    ->join(array("pr" => "progress_report"), new Expression("pr.user_id=user.user_id AND pr.status=1"), array('learn_duration'=> new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')),'left')
                    ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                    ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Learn'"), array(), 'right')
                    ->join(array("tn" => "t_notes"), new Expression("tn.user_id=user.user_id AND tn.status=1"), array('notes_count'=> new \Zend\Db\Sql\Expression('count(distinct tn.user_id)')),'left')
                    ->join(array("tlp" => "t_lesson_plan"), new Expression("tlp.user_id=user.user_id AND tlp.status=1"), array('schedule_count'=> new \Zend\Db\Sql\Expression('count(distinct tlp.user_id)')),'left')*/
                   ->where('user.user_id=' . $userId)
                   ->group('user.user_id');
            $result = $this->tableGateway->selectWith($select); //echo'<pre>';print_r($result);die;
        } catch (Exception $e) {
            echo $e;
        }
        return $result;
    }
    
    public function getPaidUserReverseData($params = array()) {
        //echo "<pre>"; print_r($params); die;         

       $where_query = "1";
         $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";   
        $join = 'left';
        if($params['userType'] == 'paid_user') {
            $join = 'inner';
        } else if($params['userType'] == 'free_user') {
            $join = 'left';
        }
        
        if(!empty($params['dataEmail'])){
            $where_query .=  " AND (user.email = '". $params['dataEmail'] ."')";               
        }
        
        if(!empty($params['dataSchoolCode'])){
            $where_query .=  " AND (ssd.school_code = '". $params['dataSchoolCode'] ."')";
        }
        
        if($params['dataType'] == 'login_count') {
            
            if(!empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND (( ul.login >= '".$params['fromDate']." 00:00:00' AND ul.login <= '".$params['toDate'] . " 23:59:59') OR ( ul.logout >= '".$params['fromDate']." 00:00:00' AND ul.logout <= '".$params['toDate'] . " 23:59:59'))";               
            }

            $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email'));
            $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')    
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'time_count') {
            
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
        }            
            
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email'));
         $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('total_time' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')            
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'learn_duration') {            
            
        if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
        }
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email'));
         $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('learn_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                 ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Learn'"), array(), 'right')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')     
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'practice_duration') {            
         
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
         }
         
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email'));
         $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('practice_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                 ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Practice'"), array(), 'right')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')     
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'scheduled_count') {
         
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND (tlp.plan_date >= '".$params['fromDate']." 00:00:00' AND tlp.plan_date <= '".$params['toDate'] . " 23:59:59')";               
         }
         
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL';
             $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email'));
             $select->join(array("tlp" => 't_lesson_plan'), "tlp.user_id=user.user_id", array('schedule_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                    ->join(array("rr1" => 'resource_rack'), "tlp.package_usage_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                    ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                    ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                    ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                    ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                    ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                    ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                    ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                    ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                    ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                    ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                    ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')     
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');
        }
        
        if($params['dataType'] == 'mentor_count') {
         
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND (tmd.added_date >= '".$params['fromDate']." 00:00:00' AND tmd.added_date <= '".$params['toDate'] . " 23:59:59')";
         }            
         
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
             $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
             $select->join(array("tsm" => 't_student_and_mentor'), "tsm.student_id=user.user_id", array('mentor_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                     ->join(array("tmd" => 't_mentor_details'), "tsm.student_id=tmd.mentor_id", array(), 'inner')
                     ->join(array("rr1" => 'resource_rack'), "tsm.subject_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                     ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                     ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                     ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                     ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                     ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                     ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                     ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                     ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                     ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                     ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                     ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')     
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');
        }
        
        if($params['dataType'] == 'group_member_count') {
         
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND (tug.added_date >= '".$params['fromDate']." 00:00:00' AND tug.added_date <= '".$params['toDate'] . " 23:59:59')";               
         }
            
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email'));
         $select->join(array("tug" => 't_user_groups'), "tug.user_id=user.user_id", array('group_member_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')     
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'note_count') {
            
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND (tn.added_date >= '".$params['fromDate']." 00:00:00' AND tn.added_date <= '".$params['toDate'] . " 23:59:59')";               
         }
         
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
             $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
             $select->join(array("tn" => 't_notes'), "tn.user_id=user.user_id", array('notes_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                     ->join(array("rr1" => 'resource_rack'), "tn.chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                     ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                     ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                     ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                     ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                     ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                     ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                     ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                     ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                     ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                     ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                     ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                     ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')     
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group('user.user_id');
        }
        
        if($params['dataType'] == 'test_attempt_count') {
            
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND (qs.set_date_added >= '".$params['fromDate']." 00:00:00' AND qs.set_date_added <= '".$params['toDate'] . " 23:59:59')";               
         }
         
             $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
             $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
             $select->join(array("qs" => 'quiz_set'), "qs.user_id=user.user_id", array('set_status','test_attempt_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                     ->join(array("qsc" => 'quiz_set_chapter'), "qs.set_id=qsc.set_id", array('board_classs_subject_chapter_id'), 'left')
                    ->join(array("rr1" => 'resource_rack'), "qsc.board_classs_subject_chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                    ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                    ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                    ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                    ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                    ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                    ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                    ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                    ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                    ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                    ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                    ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                    ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                    ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                    ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                    ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                    ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')     
                    ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                    ->where($where_query)
                    ->group(array('user.user_id', 'qs.set_status'));
             
        }
        
        $resultSet = $this->tableGateway->selectWith($select); 
        
        //echo '<pre>';
        //print_r($resultSet);//die;
        $resultSet->buffer();
        return $resultSet;
    }
    
    public function getConsuptionsReverseDetailsData($params = array()) {
       //echo "<pre>"; print_r($params); die;         

       $where_query = "1";
          $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";   
        $join = 'left';
        if($params['userType'] == 'paid_user') {
            $join = 'inner';
        } else if($params['userType'] == 'free_user') {
            $join = 'left';
        }
        
        if(!empty($params['dataUserType'])) {
            if($params['dataUserType'] == 'free_users') {
                $where_query .=  " AND ((select count(*) from t_user_package tup1 where tup1.user_id=tup.user_id and tup1.status IN (1,2,3)) = 0)";
            } else if($params['dataUserType'] == 'paid_users') {
                $where_query .=  " AND (tup.status IN (1,2,3))";
            }
        }
        
        if(!empty($params['dataEmail'])){
            $where_query .=  " AND (user.email = '". $params['dataEmail'] ."')";               
        }
        
        if(!empty($params['dataSchoolCode'])){
            $where_query .=  " AND (ssd.school_code = '". $params['dataSchoolCode'] ."')";
        }
        
        if($params['dataType'] == 'login_count') {
            
            if(!empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND (( ul.login >= '".$params['fromDate']." 00:00:00' AND ul.login <= '".$params['toDate'] . " 23:59:59') OR ( ul.logout >= '".$params['fromDate']." 00:00:00' AND ul.logout <= '".$params['toDate'] . " 23:59:59'))";               
            }
            
            $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'create_time'));
            $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'time_count') {
            
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
        }            
            
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'create_time'));
         $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('total_time' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'learn_duration') {            
            
        if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
        }
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'create_time'));
         $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('learn_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                 ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Learn'"), array(), 'right')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'practice_duration') {            
         
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
         }
         
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'create_time'));
         $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('practice_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                 ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Practice'"), array(), 'right')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'scheduled_count') {
         
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND (tlp.plan_date >= '".$params['fromDate']." 00:00:00' AND tlp.plan_date <= '".$params['toDate'] . " 23:59:59')";               
         }
        
         $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tlp.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
         $select->join(array("tlp" => 't_lesson_plan'), "tlp.user_id=user.user_id", array('schedule_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                ->join(array("cbr" => 'custom_board_rack'), "tlp.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                ->join(array("rr1" => 'resource_rack'), "tlp.package_usage_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'mentor_count') {
         
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND (tmd.added_date >= '".$params['fromDate']." 00:00:00' AND tmd.added_date <= '".$params['toDate'] . " 23:59:59')";
         }            
         
         $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tsm.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
         $select->join(array("tsm" => 't_student_and_mentor'), "tsm.student_id=user.user_id", array('mentor_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                 ->join(array("tmd" => 't_mentor_details'), "tsm.student_id=tmd.mentor_id", array(), 'inner')
                 ->join(array("cbr" => 'custom_board_rack'), "tsm.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                 ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                 ->join(array("rr1" => 'resource_rack'), "tsm.subject_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                 ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                 ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                 ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                 ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                 ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                 ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                 ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                 ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                 ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                 ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                 ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                 ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'group_member_count') {
         
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND (tug.added_date >= '".$params['fromDate']." 00:00:00' AND tug.added_date <= '".$params['toDate'] . " 23:59:59')";               
         }
            
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'create_time'));
         $select->join(array("tug" => 't_user_groups'), "tug.user_id=user.user_id", array('group_member_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'note_count') {
            
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND (tn.added_date >= '".$params['fromDate']." 00:00:00' AND tn.added_date <= '".$params['toDate'] . " 23:59:59')";               
         }
            
         $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tn.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
         $select->join(array("tn" => 't_notes'), "tn.user_id=user.user_id", array('notes_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                 ->join(array("cbr" => 'custom_board_rack'), "tn.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                 ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                 ->join(array("rr1" => 'resource_rack'), "tn.chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                 ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                 ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                 ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                 ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                 ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                 ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                 ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                 ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                 ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                 ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                 ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                 ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
        }
        
        if($params['dataType'] == 'test_attempt_count') {
            
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( qs.set_date_added >= '".$params['fromDate']." 00:00:00' AND qs.set_date_added <= '".$params['toDate'] . " 23:59:59')";               
         }
         
         $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
         $select->join(array("qs" => 'quiz_set'), "qs.user_id=user.user_id", array('set_status','test_attempt_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                 ->join(array("qsc" => 'quiz_set_chapter'), "qs.set_id=qsc.set_id", array('board_classs_subject_chapter_id'), 'left')
                ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                ->join(array("rr1" => 'resource_rack'), "qsc.board_classs_subject_chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                 ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group('user.user_id');
         
        }
        
        $resultSet = $this->tableGateway->selectWith($select); 
        
        //echo '<pre>';
        //print_r($resultSet); die;
        $resultSet->buffer();
        return $resultSet;
    }
    
    public function getPaidUserReverseDetailsData($params = array()) {
       //echo "<pre>"; print_r($params); die;         

       $where_query = "1";
          $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";   
        $join = 'left';
        if($params['userType'] == 'paid_user') {
            $join = 'inner';
        } else if($params['userType'] == 'free_user') {
            $join = 'left';
        }
        
        if(!empty($params['dataUserType'])) {
            if($params['dataUserType'] == 'free_users') {
                $where_query .=  " AND ((select count(*) from t_user_package tup1 where tup1.user_id=tup.user_id and tup1.status IN (1,2,3)) = 0)";
            } else if($params['dataUserType'] == 'paid_users') { 
                $where_query .=  " AND (tup.status IN (1,2,3))";
            }
        }
        
        if(!empty($params['dataEmail'])){
            $where_query .=  " AND (user.email = '". $params['dataEmail'] ."')";               
        }
        
        if(!empty($params['dataSchoolCode'])){
            $where_query .=  " AND (ssd.school_code = '". $params['dataSchoolCode'] ."')";
        }
        
        if($params['dataType'] == 'login_count') {
            
            if(!empty($params['fromDate']) && !empty($params['toDate'])){
                $where_query .=  " AND (( ul.login >= '".$params['fromDate']." 00:00:00' AND ul.login <= '".$params['toDate'] . " 23:59:59') OR ( ul.logout >= '".$params['fromDate']." 00:00:00' AND ul.logout <= '".$params['toDate'] . " 23:59:59'))";               
            }
            
            $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'create_time'));
            $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group(array('tut.transaction_id'));
        }
        
        if($params['dataType'] == 'time_count') {
            
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
        }            
            
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'create_time'));
         $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('total_time' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group(array('tut.transaction_id'));
        }
        
        if($params['dataType'] == 'learn_duration') {            
            
        if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
        }
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'create_time'));
         $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('learn_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                 ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Learn'"), array(), 'right')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group(array('tut.transaction_id'));
        }
        
        if($params['dataType'] == 'practice_duration') {            
         
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00' AND pr.created_date <= '".$params['toDate'] . " 23:59:59')";               
         }
         
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'create_time'));
         $select->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('practice_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                 ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Practice'"), array(), 'right')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group(array('tut.transaction_id'));
        }
        
        if($params['dataType'] == 'scheduled_count') {
         
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( tlp.plan_date >= '".$params['fromDate']." 00:00:00' AND tlp.plan_date <= '".$params['toDate'] . " 23:59:59')";               
         }
         
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'create_time'));
         $select->join(array("tlp" => 't_lesson_plan'), "tlp.user_id=user.user_id", array('schedule_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group(array('tut.transaction_id'));
        }
        
        if($params['dataType'] == 'mentor_count') {
         
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( tmd.added_date >= '".$params['fromDate']." 00:00:00' AND tmd.added_date <= '".$params['toDate'] . " 23:59:59')";
         }            
            
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'create_time'));
         $select->join(array("tsm" => 't_student_and_mentor'), "tsm.student_id=user.user_id", array('mentor_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                ->join(array("tmd" => 't_mentor_details'), "tsm.student_id=tmd.mentor_id", array(), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group(array('tut.transaction_id'));
        }
        
        if($params['dataType'] == 'group_member_count') {
         
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( tug.added_date >= '".$params['fromDate']." 00:00:00' AND tug.added_date <= '".$params['toDate'] . " 23:59:59')";               
         }
            
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'create_time'));
         $select->join(array("tug" => 't_user_groups'), "tug.user_id=user.user_id", array('group_member_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group(array('tut.transaction_id'));
        }
        
        if($params['dataType'] == 'note_count') {
            
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( tn.added_date >= '".$params['fromDate']." 00:00:00' AND tn.added_date <= '".$params['toDate'] . " 23:59:59')";               
         }
            
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'create_time'));
         $select->join(array("tn" => 't_notes'), "tn.user_id=user.user_id", array('notes_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group(array('tut.transaction_id'));
        }
        
        if($params['dataType'] == 'test_attempt_count') {
            
         if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( qs.set_date_added >= '".$params['fromDate']." 00:00:00' AND qs.set_date_added <= '".$params['toDate'] . " 23:59:59')";               
         }
            
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'create_time'));
         $select->join(array("qs" => 'quiz_set'), "qs.user_id=user.user_id", array('set_status','test_attempt_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group(array('tut.transaction_id', 'qs.set_status'));
        }
        
        $resultSet = $this->tableGateway->selectWith($select); 
        
        //echo '<pre>';
        //print_r($resultSet); die;
        $resultSet->buffer();
        return $resultSet;
    }
    
    public function getPaidUserReversePackageDetailsData($params = array()) {
        
        //echo '<pre>';print_r($params); echo '</pre>';die('macro Die');
        
        $where_query = "1";
           $where_query .= " AND (tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)";   
        $join = 'left';
        if($params['userType'] == 'paid_user') {
            $join = 'inner';
        } else if($params['userType'] == 'free_user') {
            $join = 'left';
        }
        
        if(!empty($params['userIds'])){
            $where_query .=  " AND (user.user_id IN (" . $params['userIds'] . "))";
            //$where_query .=  " (u.user_id IN (" . $params['userIds'] . "))"; 
        }
        
        if(empty($params['dataDate']) && $params['dateSelect'] == 'create' && !empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
            //$where_query .=  " AND ( DATE(u.create_time) >= '".$params['fromDate']."' AND DATE(u.create_time) <= '".$params['toDate'] . "')";   
        }
        
        if(empty($params['dataDate']) && $params['dateSelect'] == 'purchase' && !empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( tut.purchase_date >= '".$params['fromDate']." 00:00:00' AND tut.purchase_date <= '".$params['toDate'] . " 23:59:59')";
        }        
        
        if(!empty($params['dataUserType'])) {
            if($params['dataUserType'] == 'free_users') {
                $where_query .=  " AND ((select count(*) from t_user_package tup1 where tup1.user_id=tup.user_id and tup1.status IN (1,2,3)) = 0)";
            } else if($params['dataUserType'] == 'paid_users') {
                $where_query .=  " AND (tup.status IN (1,2,3))";
            }
        }

        $group_by = array('user.user_id');
        
        if(!empty($params['dataGroup']) && $params['dataGroup'] == 'group'){
            if($params['dataUserType'] == 'free_users') {
                $group_by = array('user.user_id');
            } else if($params['dataUserType'] == 'paid_users') {
                $group_by = array('tut.transaction_id');
            }
        }
        
        if(!empty($params['dataDate'])){
            $dateBeforeThreeMonths = date('Y-m-d', strtotime("-3 months", strtotime($params['fromDate'])));        
            $newDateBeforeThreeMonths = date('Y-m', strtotime($dateBeforeThreeMonths));
            if($params['dataUserType'] == 'free_users') {                    
                $dataDate = date('Y-m', strtotime($params['dataDate']));
                if($newDateBeforeThreeMonths == $dataDate) {
                    $where_query .=  " AND (user.create_time >= '". $dateBeforeThreeMonths ." 00:00:00' AND user.create_time <= '". $newDateBeforeThreeMonths . "-31 23:59:59')";
                } else {
                    $where_query .=  " AND ( user.create_time >= '". $dataDate ."-01 00:00:00' AND user.create_time <= '". $dataDate . "-31 23:59:59')";
                }
            } else if($params['dataUserType'] == 'paid_users') {
                $dataDate = date('Y-m', strtotime($params['dataDate']));
                if($newDateBeforeThreeMonths == $dataDate) {
                    $where_query .=  " AND ( tut.purchase_date >= '". $dateBeforeThreeMonths ." 00:00:00' AND tut.purchase_date <= '". $newDateBeforeThreeMonths . "-31 23:59:59')";
                } else {
                    $where_query .=  " AND ( tut.purchase_date >= '". $dataDate ."-01 00:00:00' AND tut.purchase_date <= '". $dataDate . "-31 23:59:59')";
                }
            }
        }
        
        if(!empty($params['dataEmail'])){
            $where_query .=  " AND (user.email = '". $params['dataEmail'] ."')";               
        }
        
        if(!empty($params['dataSchoolCode'])){
            $where_query .=  " AND (ssd.school_code = '". $params['dataSchoolCode'] ."')";
        }
        
        if($params['dataType'] == 'time_count') {
            
            if(!empty($params['dataClass'])){
                $where_query .=  " AND ( tp.class = '".$params['dataClass']."' OR rn.name = '".$params['dataClass'] . "')";
            }

            if(!empty($params['dataBoard'])){
                $where_query .=  " AND ( tp.board = '".$params['dataBoard']."' OR rn1.name = '".$params['dataBoard'] . "')";
            }
            
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rn.name IS NULL THEN tp.class ELSE rn.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE pr.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rn1.name IS NULL THEN tp.board ELSE rn1.name END')));
         $select->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                 ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array(), 'left')
                 ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                 ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                 ->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('total_time' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name','package_type','class_name'=>'class','board_name'=>'board', 'price'), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                 ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group($group_by);
         
         if(!empty($params['dataClass']) && !empty($params['dataBoard'])){
            $select->order("pr.total_time DESC");
         }
            
        }
        
        if($params['dataType'] == 'login_count') {
            
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rn.name IS NULL THEN rn.name ELSE rn.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rn1.name IS NULL THEN rn1.name ELSE rn1.name END')));
         $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                 ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                 ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                 ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array(), 'left')
                 ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                 ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                 ->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name','package_type','class_name'=>'class','board_name'=>'board', 'price'), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                 ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group($group_by);
            
        }
        
        if($params['dataType'] == 'learn_duration') {
            
            if(!empty($params['dataClass'])){
                $where_query .=  " AND ( tp.class = '".$params['dataClass']."' OR rn.name = '".$params['dataClass'] . "')";
            }

            if(!empty($params['dataBoard'])){
                $where_query .=  " AND ( tp.board = '".$params['dataBoard']."' OR rn1.name = '".$params['dataBoard'] . "')";
            }
            
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rn.name IS NULL THEN rn.name ELSE rn.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rn1.name IS NULL THEN rn1.name ELSE rn1.name END')));
         $select->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                 ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array(), 'left')
                 ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                 ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                 ->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('learn_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                 ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Learn'"), array(), 'right')
                ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                 ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                 ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name','package_type','class_name'=>'class','board_name'=>'board', 'price'), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                 ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group($group_by);
         
         if(!empty($params['dataClass']) && !empty($params['dataBoard'])){
            $select->order("pr.learn_duration DESC");
         }
            
        }
        
        if($params['dataType'] == 'practice_duration') {            
            
            if(!empty($params['dataClass'])){
                $where_query .=  " AND ( tp.class = '".$params['dataClass']."' OR rn.name = '".$params['dataClass'] . "')";
            }

            if(!empty($params['dataBoard'])){
                $where_query .=  " AND ( tp.board = '".$params['dataBoard']."' OR rn1.name = '".$params['dataBoard'] . "')";
            }
            
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rn.name IS NULL THEN rn.name ELSE rn.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN pr.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rn1.name IS NULL THEN rn1.name ELSE rn1.name END')));
         $select->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                 ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array(), 'left')
                 ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                 ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                 ->join(array("pr" => 'progress_report'), "pr.user_id=user.user_id", array('practice_duration' => new \Zend\Db\Sql\Expression('sum(TIME_TO_SEC(total_time))/60')), 'inner')
                ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'inner')
                 ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id and dg.group_name='Practice'"), array(), 'right')
                ->join(array("cbr" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                 ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                 ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name','package_type','class_name'=>'class','board_name'=>'board', 'price'), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                 ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group($group_by);
         
         if(!empty($params['dataClass']) && !empty($params['dataBoard'])){
            $select->order("pr.practice_duration DESC");
         }
            
        }
        
        if($params['dataType'] == 'scheduled_count') {
         
         $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tlp.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
         $select->join(array("tlp" => 't_lesson_plan'), "tlp.user_id=user.user_id", array('schedule_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                ->join(array("cbr" => 'custom_board_rack'), "tlp.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                ->join(array("rr1" => 'resource_rack'), "tlp.package_usage_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name','package_type','class_name'=>'class','board_name'=>'board', 'price'), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                 ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group($group_by);
            
        }
        
        if($params['dataType'] == 'mentor_count') {
            
         $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tsm.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
         $select->join(array("tsm" => 't_student_and_mentor'), "tsm.student_id=user.user_id", array('mentor_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                 ->join(array("cbr" => 'custom_board_rack'), "tsm.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                 ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                 ->join(array("rr1" => 'resource_rack'), "tsm.subject_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                 ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                 ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                 ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                 ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                 ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                 ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                 ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                 ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                 ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                 ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                 ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                 ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name','package_type','class_name'=>'class','board_name'=>'board', 'price'), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                 ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group($group_by);
            
        }
        
        if($params['dataType'] == 'group_member_count') {
            
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rn.name IS NULL THEN rn.name ELSE rn.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rn1.name IS NULL THEN rn1.name ELSE rn1.name END')));
         $select->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                 ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                 ->join(array("rr" => 'resource_rack'), "user.class_id=rr.rack_id", array(), 'left')
                 ->join(array("rn" => 'rack_name'), "rn.rack_name_id=rr.rack_name_id", array(), 'left')
                 ->join(array("rr1" => 'resource_rack'), "user.board_id=rr1.rack_id", array(), 'left')
                 ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                 ->join(array("tug" => 't_user_groups'), "tug.user_id=user.user_id", array('group_member_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                 ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name','package_type','class_name'=>'class','board_name'=>'board', 'price'), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                 ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group($group_by);
            
        }
        
        if($params['dataType'] == 'note_count') {
            
         $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN tn.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
         $select->join(array("tn" => 't_notes'), "tn.user_id=user.user_id", array('notes_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner')
                 ->join(array("cbr" => 'custom_board_rack'), "tn.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                 ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                 ->join(array("rr1" => 'resource_rack'), "tn.chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                 ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                 ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                 ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                 ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                 ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                 ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                 ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                 ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                 ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                 ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                 ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                 ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name','package_type','class_name'=>'class','board_name'=>'board', 'price'), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                 ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group($group_by);
            
        }
        
        if($params['dataType'] == 'test_attempt_count') {
         
         $where_query .= ' AND rr1.rack_type_id IS NOT NULL'; 
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr3.rack_type_id ="8" THEN rn5.name WHEN rr2.rack_type_id ="7" THEN rn4.name WHEN rr1.rack_type_id ="6" THEN rn4.name WHEN rr2.rack_type_id ="4" THEN rn3.name ELSE rn2.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN rr3.rack_type_id ="8" THEN rn6.name WHEN rr3.rack_type_id ="4" THEN rn5.name WHEN rr3.rack_type_id ="6" THEN rn4.name WHEN rr4.rack_type_id ="2" THEN rn4.name ELSE rn3.name END')));
         $select->join(array("qs" => 'quiz_set'), "qs.user_id=user.user_id", array('set_status','test_attempt_count' => new \Zend\Db\Sql\Expression('count(*)')), 'inner')
                 ->join(array("qsc" => 'quiz_set_chapter'), "qs.set_id=qsc.set_id", array('board_classs_subject_chapter_id'), 'left')
                ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                ->join(array("rr1" => 'resource_rack'), "qsc.board_classs_subject_chapter_id=rr1.rack_id", array('rack_type_id1' => 'rack_type_id'), 'left')
                ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_type_id2' => 'rack_type_id'), 'left')
                ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_type_id3' => 'rack_type_id'), 'left')
                ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_type_id4' => 'rack_type_id'), 'left')
                ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_type_id5' => 'rack_type_id'), 'left')
                ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_type_id6' => 'rack_type_id'), 'left')
                ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name','package_type','class_name'=>'class','board_name'=>'board', 'price'), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                 ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group($group_by);
            
        }
        
        if($params['dataType'] == 'location_count') {
         
            $group_by = array();
            if(!empty($params['dataState'])){
                $where_query .= ' AND (LOWER(ts.state_name) = "'. strtolower($params['dataState']) .'")';
                $group_by = array('user.city');
            } else {
                $group_by = array('ts.state_id');
            }
            
         $where_query .= "AND user.state_id is NOT NULL";
         $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'city', 'TotalRecords' => new \Zend\Db\Sql\Expression('COUNT(*)'), 'class_name' => new \Zend\Db\Sql\Expression('CASE WHEN rn.name IS NULL THEN rn.name ELSE rn.name END'), 'board_name' => new \Zend\Db\Sql\Expression('CASE WHEN rn1.name IS NULL THEN rn1.name ELSE rn1.name END')));
         $select->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), $join)
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name','package_type','class_name'=>'class','board_name'=>'board', 'price'), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left')
                ->join(array("ts" => "t_state"), "ts.state_id = user.state_id", array('state_id','state_name'), 'left')
                ->join(array("tc" => "t_country"), "tc.country_id = ts.country_id", array('country_id','country_name'), 'left')
                 ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent', 'coupon_type'), 'left') 
                ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
                ->where($where_query)
                ->group($group_by)
                ->order('TotalRecords DESC');
            
        }
        
        $resultSet = $this->tableGateway->selectWith($select);
        //echo '<pre>';
        //print_r($resultSet);die;
        $resultSet->buffer();
        return $resultSet;
        
    }
    
    public function sendNotificationToAllUser($param=array()) {
        $sql = $this->tableGateway->getSql();
        $select = $this->tableGateway->getSql()->select();
         
        $where_query = "1 ";
        if(!in_array('All',$param->usertype)) {
            if(in_array('1',$param->usertype)) {
                $where_query = "user_type_id = '1' ";
            }
            if(in_array('2',$param->usertype)) {
                if($where_query=="1 ") {
                    $where_query = "user_type_id = '2' ";
                } else {
                    $where_query .= "OR (user_type_id = '2') ";
                }
            }
            if(in_array('3',$param->usertype)) {
                if($where_query=="1 ") {
                    $where_query = "user_type_id = '3' ";
                } else {
                    $where_query .= "OR (user_type_id = '3') ";
                }
            }
        }
        if(in_array('Paid User',$param->usertype) && in_array('FREE USER',$param->usertype)){ 
              $select->join(array("tut" => "t_user_transaction"), "tut.purchaser_id = user.user_id", array('pkg_payment_type'=>'pkg_payment_type','pkg_payment_details'=>'pkg_payment_details'), 'left');
              $where_query .= " AND tut.pkg_payment_details = 'Processed' ";
        } else if(in_array('FREE USER',$param->usertype)) {
             $select->join(array("tut" => "t_user_transaction"), "tut.purchaser_id = user.user_id", array('pkg_payment_type'=>'pkg_payment_type','pkg_payment_details'=>'pkg_payment_details'), 'left');
             $where_query .= " AND tut.pkg_payment_type = 'FREE USER' ";
        } else if(in_array('Paid User',$param->usertype)) {
             $select->join(array("tut" => "t_user_transaction"), "tut.purchaser_id = user.user_id", array('pkg_payment_type'=>'pkg_payment_type','pkg_payment_details'=>'pkg_payment_details'), 'left');             
             $where_query .= " AND tut.pkg_payment_type != 'FREE USER' ";
             $where_query .= " AND tut.pkg_payment_details = 'Processed' ";
        }
         
        /*if($param->userstartdate!='') {
               $startdate =  date('Y-m-d',strtotime($param->userstartdate));
               $where_query .= "AND (create_time >= '" . $startdate . "') ";
        }
        if($param->userenddate!='') {
               $enddate =  date('Y-m-d',strtotime($param->userenddate));
               $where_query .= "AND (create_time <= '" . $enddate . "')";
        }*/
        if($param->registered_user_date=='reg_date') {
            if($param->from_date!='') {
                   $startdate =  date('Y-m-d',strtotime($param->from_date));
                   $where_query .= "AND (DATE(create_time) >= '" . $startdate . "') ";
            }
            if($param->to_date!='') {
                   $enddate =  date('Y-m-d',strtotime($param->to_date));
                   $where_query .= "AND (DATE(create_time) <= '" . $enddate . "')";
            }
        } else if($param->registered_user_date=='paid_date') {
            if($param->from_date!='') {
                   $startdate =  date('Y-m-d',strtotime($param->from_date));
                   $where_query .= "AND (DATE(tut.purchase_date) >= '" . $startdate . "') ";
            }
            if($param->to_date!='') {
                   $enddate =  date('Y-m-d',strtotime($param->to_date));
                   $where_query .= "AND (DATE(tut.purchase_date) <= '" . $enddate . "')";
            }
        } else if($param->registered_user_date=='expiry_date') {
            $select->join(array("tup" => "t_user_package"), "tup.user_id = user.user_id", array('valid_till','valid_from','package_id','package_name'), 'left');
            if($param->from_date!='') {
                   $startdate =  date('Y-m-d',strtotime($param->from_date));
                   $where_query .= "AND (DATE(tup.valid_from) >= '" . $startdate . "') ";
            }
            if($param->to_date!='') {
                   $enddate =  date('Y-m-d',strtotime($param->to_date));
                   $where_query .= "AND (DATE(tup.valid_from) <= '" . $enddate . "')";
            }
            
        }
        
        if(isset($param->package_type) && count($param->package_type) > 0) {
            if($param->registered_user_date=='expiry_date') {
                $select->join(array("tp" => "t_package"), "tp.package_id = tup.package_id", array('package_category'), 'left');
            } else {
                $select->join(array("tup" => "t_user_package"), "tup.user_id = user.user_id", array('valid_till','valid_from','package_id','package_name'), 'left');
                $select->join(array("tp" => "t_package"), "tp.package_id = tup.package_id", array('package_category'), 'left');
            }
            
            if(in_array('3',$param->package_type)) {
                $where_query .= " AND (tp.package_category = '3')";
            }
            if(in_array('2',$param->package_type)) {
                if(in_array('2',$param->package_type)) {
                    $where_query .= " AND (tp.package_category = '2')";
                } else {
                    $where_query .= " OR (tp.package_category = '2')";
                }
            }
            if(in_array('others',$param->package_type)) {
                if(in_array('3',$param->package_type) || in_array('3',$param->package_type)) { 
                    $where_query .= " OR (tp.package_category != '2' AND tp.package_category != '3')";
                } else {
                    $where_query .= " AND (tp.package_category != '2' AND tp.package_category != '3')";
                }
                
            }
        }
        //->limit(20000);
        //$select->offset($offset);        
        
        
        if($where_query=="1 ") {
            return 0;
        } else {
            $select->group('user_id');
            $select->where($where_query);
            //echo $sql->getSqlstringForSqlObject($select); die;
            $resultSet = $this->tableGateway->selectWith($select);
            return $resultSet->count();
        }
        //echo '<pre>';print_r($resultSet);echo '</pre>';die('Macro Die');
        //return ($resultSet)?$resultSet:false; 
      }
    
     public function sendNotificationToUser($offset,$param=array(),$limit) {
         $select = $this->tableGateway->getSql()->select();
         
         $where_query = "1 ";
         if(!in_array('All',$param->usertype)) {
            if(in_array('1',$param->usertype)) {
               $where_query = "user_type_id = '1' ";
            }
            if(in_array('2',$param->usertype)) {
               if($where_query=="1 ") {
                   $where_query = "user_type_id = '2' ";
               } else {
                   $where_query .= "OR (user_type_id = '2') ";
               }
           }
           if(in_array('3',$param->usertype)) {
               if($where_query=="1 ") {
                   $where_query = "user_type_id = '3' ";
               } else {
                   $where_query .= "OR (user_type_id = '3') ";
               }
           }
        }
         /*if($param->student!=''){
             $student = $param->student;
             $where_query = "user_type_id = '" . $student . "' ";
         }        
         if($param->parent!=''){
             $parent = $param->parent;
             $where_query .= "OR (user_type_id = '" . $parent . "') ";
         }
         if($param->mentor!=''){
             $mentor = $param->mentor;
             $where_query .= "OR (user_type_id = '" . $mentor . "')";
         }*/
         //echo '<pre>'; print_r($param->usertype); exit;
        //if($param->freeuser!='' && $param->paiduser!='' ){
        if(in_array('Paid User',$param->usertype) && in_array('FREE USER',$param->usertype)){ 
              $select->join(array("tut" => "t_user_transaction"), "tut.purchaser_id = user.user_id", array('pkg_payment_type'=>'pkg_payment_type','pkg_payment_details'=>'pkg_payment_details'), 'left');
              $where_query .= " AND tut.pkg_payment_details = 'Processed' ";
        } else if(in_array('FREE USER',$param->usertype)) {
             $select->join(array("tut" => "t_user_transaction"), "tut.purchaser_id = user.user_id", array('pkg_payment_type'=>'pkg_payment_type','pkg_payment_details'=>'pkg_payment_details'), 'left');
             $where_query .= " AND tut.pkg_payment_type = 'FREE USER' ";
        } else if(in_array('Paid User',$param->usertype)) {
             $select->join(array("tut" => "t_user_transaction"), "tut.purchaser_id = user.user_id", array('pkg_payment_type'=>'pkg_payment_type','pkg_payment_details'=>'pkg_payment_details'), 'left');             
             $where_query .= " AND tut.pkg_payment_type != 'FREE USER' ";
             $where_query .= " AND tut.pkg_payment_details = 'Processed' ";
        }
        if($param->registered_user_date=='reg_date') {
            if($param->from_date!='') {
                   $startdate =  date('Y-m-d',strtotime($param->from_date));
                   $where_query .= "AND (DATE(create_time) >= '" . $startdate . "') ";
            }
            if($param->to_date!='') {
                   $enddate =  date('Y-m-d',strtotime($param->to_date));
                   $where_query .= "AND (DATE(create_time) <= '" . $enddate . "')";
            }
        } else if($param->registered_user_date=='paid_date') {
            if($param->from_date!='') {
                   $startdate =  date('Y-m-d',strtotime($param->from_date));
                   $where_query .= "AND (DATE(tut.purchase_date) >= '" . $startdate . "') ";
            }
            if($param->to_date!='') {
                   $enddate =  date('Y-m-d',strtotime($param->to_date));
                   $where_query .= "AND (DATE(tut.purchase_date) <= '" . $enddate . "')";
            }
        } else if($param->registered_user_date=='expiry_date') {
            $select->join(array("tup" => "t_user_package"), "tup.user_id = user.user_id", array('valid_till','valid_from','package_id','package_name'), 'left');
            if($param->from_date!='') {
                   $startdate =  date('Y-m-d',strtotime($param->from_date));
                   $where_query .= "AND (DATE(tup.valid_from) >= '" . $startdate . "') ";
            }
            if($param->to_date!='') {
                   $enddate =  date('Y-m-d',strtotime($param->to_date));
                   $where_query .= "AND (DATE(tup.valid_from) <= '" . $enddate . "')";
            }
            
        }
        
        if(isset($param->package_type) && count($param->package_type) > 0) {
            if($param->registered_user_date=='expiry_date') {
                $select->join(array("tp" => "t_package"), "tp.package_id = tup.package_id", array('package_category'), 'left');
            } else {
                $select->join(array("tup" => "t_user_package"), "tup.user_id = user.user_id", array('valid_till','valid_from','package_id','package_name'), 'left');
                $select->join(array("tp" => "t_package"), "tp.package_id = tup.package_id", array('package_category'), 'left');
            }
            
            if(in_array('3',$param->package_type)) {
                $where_query .= " AND (tp.package_category = '3')";
            }
            if(in_array('2',$param->package_type)) {
                if(in_array('3',$param->package_type)) {
                    $where_query .= " AND (tp.package_category = '2')";
                } else {
                    $where_query .= " OR (tp.package_category = '2')";
                }
            }
            if(in_array('others',$param->package_type)) {
                if(in_array('3',$param->package_type) || in_array('3',$param->package_type)) { 
                    $where_query .= " OR (tp.package_category != '2' AND tp.package_category != '3')";
                } else {
                    $where_query .= " AND (tp.package_category != '2' AND tp.package_category != '3')";
                }
            }
        }
        
        $select->group('user_id');
        $select->where($where_query)
                ->limit($limit);  
        $select->offset($offset);        
        $resultSet = $this->tableGateway->selectWith($select);
        //echo '<pre>';print_r($resultSet);echo '</pre>';die('Macro Die');
        return ($resultSet)?$resultSet:false; 
      }
      
      public function updateIpCaptureUser($res)
    {
        // echo '<pre>';print_r ($data);echo '</pre>';die('Vikash');
        $data['country_id']     = $res['countryID'];
	$data['state_id']         = $res['stateID'];
	$data['city']             = $res['cityValue'];
	if(array_key_exists('class_id' , $res) && array_key_exists('board_id' , $res)){
            $data['board_id']         = $res['board_id'];
            $data['class_id']         = $res['class_id'];
        }
        $row=$this->tableGateway->update($data, array('user_id' => $res['userId']));
        return $row; 
    }
    
    public function getChildUserList($params) {
     
        $where_query = "1";
        
        if(!empty($params['userIds'])){
            $where_query .=  " AND (user.user_id IN (" . $params['userIds'] . "))";            
        }

        $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'email', 'display_name', 'phone','mobile', 'address','city','parent_id','username', 'user_type_id'))
                ->join(array("tsam" => 't_student_and_mentor'), new Expression("(tsam.mentor_id = user.user_id AND tsam.student_id=".$params['userId'].") OR (tsam.student_id = user.user_id AND tsam.mentor_id=".$params['userId'].")" ), array('student_id','mentor_id'), 'left')
                ->join(array("tpac" => 't_parent_and_child'), new Expression("(tpac.parent_id = user.user_id AND tpac.child_id=".$params['userId'].") OR (tpac.child_id = user.user_id AND tpac.parent_id=".$params['userId'].")"), array('child_id','super_parent_id'=>'parent_id'), 'left');
        $select->group('user_id');
        $select->where($where_query);
        //echo $this->tableGateway->getSql()->getSqlstringForSqlObject($select); die ;
        $resultSet = $this->tableGateway->selectWith($select); 
        //echo '<pre>'; print_r($resultSet); exit;
        return $resultSet;
     
     
    }
    
    public function getSubscribedStatus($params) {
     
        $where_query = "1";
        
        if(!empty($params['userIds'])){
            $where_query .=  " AND (tup.user_id IN (" . $params['userIds'] . "))";            
            $where_query .=  " AND (tut.pkg_payment_details = 'Processed')";
            $where_query .=  " AND (tup.status IN (1,2,3))";
        }

        $select = $this->tableGateway->getSql()->select()->columns(array('user_id'))
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), 'left')
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name'), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left');
        $select->where($where_query);
        
        $resultSet = $this->tableGateway->selectWith($select);         
        return $resultSet;
     
     
    }
    
    public function getPurchaserSubscribedUser($params) {
     
        $where_query = "1";
        
        if(!empty($params['userIds'])){
            $where_query .=  " AND (tut.purchaser_id IN (" . $params['userIds'] . "))";            
            $where_query .=  " AND (tut.pkg_payment_details = 'Processed')";
            $where_query .=  " AND (tup.status IN (1,2,3))";
        }

        $select = $this->tableGateway->getSql()->select()->columns(array('user_id'))
                ->join(array("tup" => 't_user_package'), new Expression("user.user_id = tup.user_id"), array('package_id'), 'left')
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name'), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('purchase_date'=>'purchase_date'), 'left');
        $select->where($where_query);
        
        $resultSet = $this->tableGateway->selectWith($select);         
        return $resultSet;
     
     
    }
    
    public function getUserListByKeyword($keyword = null, $user_type = 1) {
        $where_query = "1";
        
        if($keyword != "" && !empty($keyword)){
            $where_query .=  " AND (user.email LIKE '%" . $keyword . "%' OR ";       
            $where_query .=  "user.display_name LIKE '%" . $keyword . "%' OR ";
            $where_query .=  "user.username LIKE '%" . $keyword . "%')"; 
        }
        
        $where_query .=  " AND (user.user_type_id ='" . $user_type . "')";
        
        $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'username', 'email', 'mobile', 'display_name', 'city', 'class_id', 'board_id'));
        $select->where($where_query);
        
        $resultSet = $this->tableGateway->selectWith($select);         
        return $resultSet;
     
     
    }
    
    /*
     * Author: ankit
     * Description: function made for report
     */
    public function checkChildData($userId){
            $returnData=array();
            if($userId>0){
                $select = $this->tableGateway->getSql()->select();
                $select->columns(array('user_id' => 'user_id'));
                $select->where('user.parent_id="'.$userId.'"');
                $select->group('user.user_id');
                
                $resultSet = $this->tableGateway->selectWith($select);
                $returnData = array();
                foreach($resultSet as $val){
                    $returnData[] = $val;
                 }
             }
           
            return $returnData;
    }
    
    public function getUserSubscriptions($params = array()) { 
        $where_query = "1";

        if(!empty($params['userIds'])){
           $where_query .=  " AND (user.user_id IN (" . $params['userIds'] . "))";      
        }
        
        $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'email'));
        $select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array('package_id','paid_price'=> 'paid_amount'), 'left')
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array('id','syllabus_id'), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_id','package_name'), 'left')                
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_id'), 'left')
                ->join(array("tu" => "user"), new Expression("tut.purchaser_id = tu.user_id"), array(), 'left')
                ->where($where_query)
                ->where("tut.order_id IS NOT NULL")
                ->group(array("tup.transaction_id"))
                ->having("paid_price>=0")
                ->order("tut.transaction_id DESC");

         $resultSet = $this->tableGateway->selectWith($select); 
         //echo '<pre>';print_r($resultSet);die;
         return $resultSet->count();
     }



     /*
     * Author: Pradeep Kumar
     * Description: get user with email id
     * Used for validating a user with same email address
     * This is also used for validating samer email address are present into the database
     */
    public function getUserByEmailAddress($emailId){
        $select = $this->tableGateway->getSql()->select();
        $select->where('email="'.stripslashes($emailId).'"');
        $resultSet = $this->tableGateway->selectWith($select);
        $row = $resultSet->current();
        return $row;
  }
  
  public function getChildUserDataList($user_id) {
     
        $where_query = "1";
        
        if(!empty($user_id)){
            $where_query .=  " AND (user.parent_id IN (" . $user_id . "))";            
        }

        $select = $this->tableGateway->getSql()->select()->columns(array('user_id'));
        $select->where($where_query);
        
        $resultSet = $this->tableGateway->selectWith($select);         
        return $resultSet;
     
     
    }
    
    public function getParentUserDataList($user_id) {
     
        $where_query = "1";
        
        if(!empty($user_id)){
            $where_query .=  " AND (user.user_id IN (" . $user_id . "))";            
        }

        $select = $this->tableGateway->getSql()->select()->columns(array('parent_id'));
        $select->where($where_query);
        
        $resultSet = $this->tableGateway->selectWith($select);         
        return $resultSet;
     
     
    }

    public function checkuserchooldetail($userId,$school_id)
    {	
        $select = $this->tableGateway->getSql()->select()->columns(array('user_id'))->where('user.user_id="'.$userId.'"');	
        $select->join(array('ul'=>'sales_school_details'), "ul.school_id = user.school_id", array('school_id'=>'school_id'),'left');
        $select->where('ul.school_id="'.$school_id.'"');
        $select->where('ul.school_report_status=4');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;		
    }
    
    public function getWebBrowseTime($userId)
    {	
        $select = $this->tableGateway->getSql()->select()->where('user.user_id="'.$userId.'"');	
        $select->join(array('ul'=>'user_logs'), "ul.user_id = user.user_id", array('login_time'=>'login','logout_time'=>'logout'),'left');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;		
    }
    public function getSchoolWiseRegisteredUser($params) {
        $createdDate = date('d-m-Y');
        $where = "1";
        if (!empty($params['fromdate']) && $params['fromdate'] != '' && !empty($params['todate']) && $params['todate'] != '') {
            $fromdate = $params['fromdate'];
            $fromdate = date('Y-m-d', strtotime($fromdate));
            $todate = $params['todate'];
            $todate = date('Y-m-d', strtotime($todate));
            $where .= " AND user.create_time >= '". $fromdate. " 00:00:00' AND user.create_time <='". $todate. " 23:59:59'";
        }else{
            $fromdate = date('Y-m-d', strtotime($createdDate));
            $todate = date('Y-m-d', strtotime($createdDate));
            $where .= " AND user.create_time >= '". $fromdate." 00:00:00' AND user.create_time <='". $todate." 23:59:59'";
        }
        
        if (!empty($params['school_code']) && $params['school_code'] !='') {
            $where .= ' AND LOWER(ssd.school_code) = "'. strtolower($params['school_code']) .'"';
        }
        
        if (!empty($params['state']) && $params['state'] !='') {            
            if($params['state'] != '-') {
                $where .= " AND LOWER(st.state_name) = '". strtolower($params['state']) ."'";
            } else {
                $where .= " AND st.state_name IS NULL";
            }
        }
        
        if (!empty($params['city']) && $params['city'] !='') {
            $where .= " AND user.city LIKE '%". $params['city'] ."%'";
        }
        
        if (!empty($params['total']) && $params['total'] !='') {
            $limit = $params['total'];
        }else{
             $limit = '';
        }
        
        $len = $limit;
        
        //$where .= ' AND user.school_name != ""';
        
        $select = $this->tableGateway->getSql()->select()->columns(array('school_id','school_name','state_id','city', 'user_type_id', 'TotalRecords' => new \Zend\Db\Sql\Expression('COUNT(*)')))                 
             ->join(array("st" => 't_state'), "user.state_id = st.state_id", array('state_name'), 'left')
             ->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array('school_code'), 'left')
             ->join(array("con" => 't_country'), "user.country_id = con.country_id", array('country_name'), 'left') 
             ->where($where)
             ->group(array("school_id", "school_name", "city", "state_id", "user_type_id"))
             ->order('TotalRecords DESC');
        
        if(!empty ($limit)) {
            $select->limit((int) $len);
        }
        
        $resultSet = $this->tableGateway->selectWith($select);  
        
        //print_r($resultSet); die;
        return $resultSet;
        
    }
    
    public function getTotalSaleData() {        

        $where_query = "1";
        $where_query .= " AND ((tdc.coupon_type !='test' AND tdc.coupon_type !='demo' AND tdc.coupon_type!='promotional') OR tdc.coupon_type IS NULL)";
        $where_query .= " AND user.email not like '%extramarks.com' and user.email not like '%em.com' and user.email not like '%aa.%' and user.email not like '%nn.com' and user.email not like '%qq.com' and user.email not like '%ss.com' and user.email not like '%testing%' and user.email is not NULL";
        
        $select = $this->tableGateway->getSql()->select()->columns(array('email', 'display_name', 'mobile', 'city', 'school_name'=>new \Zend\Db\Sql\Expression("CASE WHEN user.school_name ='' THEN ssd.school_name ELSE user.school_name END")));
        $select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array('package_price'=>new \Zend\Db\Sql\Expression("SUM(package_price)"), 'paid_price'=>new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' THEN tut.transaction_amount ELSE SUM(paid_amount) END") , 'discount_amount'=>new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' OR tut.transaction_amount =0 THEN tut.transaction_discount ELSE SUM(discount_amount) END"), 'currency_type'), 'left')
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array(), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','order_id','purchase_date'), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array(), 'left')
                ->join(array('in_study'  => 'invoices_study') , new Expression('tut.order_id=in_study.order_id AND tut.transaction_product_type="study"'),array('invoice_id'=>new \Zend\Db\Sql\Expression("CASE WHEN in_study.id !='' THEN in_study.id WHEN in_tab.id !='' THEN in_tab.id WHEN in_sdcard.id !='' THEN in_sdcard.id ELSE '' END")),'left') 
                ->join(array('in_tab'  => 'invoices_tablet') , new Expression('tut.order_id=in_tab.order_id AND tut.transaction_product_type="tablet"'),array(),'left') 
                ->join(array('in_sdcard'  => 'invoices_sdcard') , new Expression('tut.order_id=in_sdcard.order_id AND tut.transaction_product_type="sdcard"'),array(),'left') 
                ->join(array('ssd' => 'sales_school_details'), 'ssd.school_id=user.school_id', array(), 'left')
                ->where($where_query)
                ->where("tut.order_id IS NOT NULL")
                ->where("tut.pkg_payment_details='Processed'")
                ->where("tup.status IN(1,2,3)")
                ->group(array('tut.transaction_id'))
                ->having("paid_price>=0")
                ->order(array('tut.transaction_id DESC')); 
        
        $resultSet = $this->tableGateway->selectWith($select); 
        //echo '<pre>';
        //print_r($resultSet);die;
        return $resultSet;
    }
    
    public function getAllStudentsExcelData($params)
    {		
        $where_query = "stu.source='2' ";
        if (!empty($params['school_name'])) {
            $where_query .= " and (display_name LIKE '%" . $params['school_name'] . "%' ) ";
        }
        if (!empty($params['email'])) {
            $where_query .= " AND (email LIKE '%" . $params['email'] . "%' ) ";
        }
        
        $select = $this->tableGateway->getSql()->select();
        $select->join(array('stu'=>'tablet_ems_students'), "stu.user_id = user.user_id", array('user_id'=>'user_id'),'left');
        
        $select->where($where_query);
        $resultSet = $this->tableGateway->selectWith($select);
        
        return $resultSet;
    }
    
    public function getDailyWiseReportData($params = array()) {
        //echo "<pre>"; print_r($params); die;
        $where_query = "1";
        
        if(!empty($params['dataSchoolCode'])){
            $where_query .=  " AND (ssd.school_code = '". $params['dataSchoolCode'] ."')";               
        }
        
        if(!empty($params['dataType'])){
                
             if($params['dataType'] == 'all'){ 
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                }
                
                $select = $this->tableGateway->getSql()->select()->columns(array('TotalRecords' => new \Zend\Db\Sql\Expression('COUNT(*)')));
                $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array(), 'left');
                $select->group('user.user_id');
                $select->where($where_query);
             }
             
             if($params['dataType'] == 'login_more_twice'){ 
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                    //$where_query .=  " AND (( ul.login >= '".$params['fromDate']." 00:00:00' AND ul.login <= '".$params['toDate'] . " 23:59:59') OR ( ul.logout >= '".$params['fromDate']." 00:00:00' AND ul.logout <= '".$params['toDate'] . " 23:59:59'))";               
                }
                
                $select = $this->tableGateway->getSql()->select()->columns(array('user_id'));
                $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'left');
                $select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array(), 'left');
                $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array(), 'left');
                $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array(), 'left');                
                $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array(), 'left');
                $select->where($where_query);
                $select->group('user.user_id');
                $select->where("user.user_id NOT IN (select user1.user_id from user user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id LEFT JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE (tdc1.coupon_type!='test' OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) group by user1.user_id)");
                $select->having('login_count > 2');
             }
             
             if($params['dataType'] == 'login_less_twice'){ 
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                    //$where_query .=  " AND (( ul.login >= '".$params['fromDate']." 00:00:00' AND ul.login <= '".$params['toDate'] . " 23:59:59') OR ( ul.logout >= '".$params['fromDate']." 00:00:00' AND ul.logout <= '".$params['toDate'] . " 23:59:59'))";               
                }
                
                $select = $this->tableGateway->getSql()->select()->columns(array('user_id'));
                $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'left');
                $select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array(), 'left');
                $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array(), 'left');
                $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array(), 'left');                
                $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array(), 'left');
                $select->where($where_query);
                $select->group('user.user_id');
                $select->where("user.user_id NOT IN (select user1.user_id from user user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id LEFT JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE (tdc1.coupon_type!='test' OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) group by user1.user_id)");
                $select->having('login_count <= 2');
             }
             
             if($params['dataType'] == 'paid_users'){ 
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                }
                
                $select = $this->tableGateway->getSql()->select()->columns(array('TotalRecords' => new \Zend\Db\Sql\Expression('COUNT(*)')));
                $select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array(), 'inner');
                $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array(), 'left');
                $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array(), 'left');                
                $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array(), 'left');
                $select->where($where_query);
                $select->where("(tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)");
                $select->where("tup.status IN(1,2,3)");
                $select->group('user.user_id');
             }
             
             if($params['dataType'] == 'expired_in_15'){ 
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    //$where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                     $halfMonthDays = date('Y-m-d', strtotime('+ 15 days', strtotime($params['fromDate'])));
                     //$where_query .=  " AND (tup.valid_till >= CURDATE() AND DATE(tup.valid_till) <= '" . $halfMonthDays . "')"; 
                     $fromDays = date('Y-m-d', strtotime($params['fromDate']));
                     $where_query .=  " AND ( DATE(tup.valid_till) >= '" . $fromDays . "') AND ( DATE(tup.valid_till) <= '" . $halfMonthDays . "')"; 
                }
                
                $select = $this->tableGateway->getSql()->select()->columns(array('TotalRecords' => new \Zend\Db\Sql\Expression('COUNT(*)')));
                $select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array(), 'inner');
                $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array(), 'left');
                $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array(), 'left');                
                $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array(), 'left');
                $select->where($where_query);
                $select->where("(tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)");
                $select->group('user.user_id');
             }
             
             if($params['dataType'] == 'expired_in_30'){ 
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    //$where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                    $aMonthDays = date('Y-m-d', strtotime('+ 30 days', strtotime($params['fromDate'])));
                    //$where_query .=  " AND (tup.valid_till >= CURDATE() AND DATE(tup.valid_till) <= '" . $aMonthDays . "')";
                    $halfPluseMonthDays = date('Y-m-d', strtotime('+ 16 days', strtotime($params['fromDate'])));
                    $where_query .=  " AND ( DATE(tup.valid_till) >= '" . $halfPluseMonthDays . "') AND ( DATE(tup.valid_till) <= '" . $aMonthDays . "')";
                }
                
                $select = $this->tableGateway->getSql()->select()->columns(array('TotalRecords' => new \Zend\Db\Sql\Expression('COUNT(*)')));
                $select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array(), 'inner');
                $select->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array(), 'left');
                $select->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array(), 'left');                
                $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array(), 'left');
                $select->where($where_query);
                $select->where("(tdc.coupon_type!='test' OR tdc.coupon_type IS NULL)");
                $select->group('user.user_id');
             }
             
             if($params['dataType'] == 'daywise_consum_5'){ 
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_sub_query =  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                    $where_query .=  $where_sub_query;
                    $where_query .=  " AND ( (DATE(ul.login) >= DATE(user.create_time) AND DATE(ul.login) <= DATE_ADD(user.create_time, INTERVAL 5 day)) OR (DATE(ul.logout) >=DATE(user.create_time) AND DATE(ul.logout) <= DATE_ADD(user.create_time, INTERVAL 5 day)))";   
                }
                
                $select = $this->tableGateway->getSql()->select()->columns(array());
                $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner');
                $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array(), 'left');
                $select->where($where_query);
                $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 6 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 10 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 6 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 10 day)))) GROUP BY user1.user_id)");
                $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 11 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 15 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 11 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 15 day)))) GROUP BY user1.user_id)");
                $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 16 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 30 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 16 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 30 day)))) GROUP BY user1.user_id)");
                $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 31 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 45 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 31 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 45 day)))) GROUP BY user1.user_id)");
                $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 60 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 60 day)))) GROUP BY user1.user_id)");
                $select->group('user.user_id');
             }
             
             if($params['dataType'] == 'daywise_consum_10'){ 
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_sub_query =  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                    $where_query .=  $where_sub_query;
                    $where_query .=  " AND ( (DATE(ul.login) >=DATE_ADD(user.create_time, INTERVAL 6 day) AND DATE(ul.login) <= DATE_ADD(user.create_time, INTERVAL 10 day)) OR (DATE(ul.logout) >=DATE_ADD(user.create_time, INTERVAL 6 day) AND (DATE(ul.logout) <= DATE_ADD(user.create_time, INTERVAL 10 day))))";   
                }
                
                $select = $this->tableGateway->getSql()->select()->columns(array());
                $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner');
                $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array(), 'left');
                $select->where($where_query);
                $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 11 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 15 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 11 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 15 day)))) GROUP BY user1.user_id)");
                $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 16 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 30 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 16 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 30 day)))) GROUP BY user1.user_id)");
                $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 31 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 45 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 31 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 45 day)))) GROUP BY user1.user_id)");
                $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 60 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 60 day)))) GROUP BY user1.user_id)");
                $select->group('user.user_id');
             }
             
             if($params['dataType'] == 'daywise_consum_15'){ 
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_sub_query =  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                    $where_query .=  $where_sub_query;
                    $where_query .=  " AND ( (DATE(ul.login) >=DATE_ADD(user.create_time, INTERVAL 11 day) AND DATE(ul.login) <= DATE_ADD(user.create_time, INTERVAL 15 day)) OR (DATE(ul.logout) >=DATE_ADD(user.create_time, INTERVAL 11 day) AND (DATE(ul.logout) <= DATE_ADD(user.create_time, INTERVAL 15 day))))"; 
                }
                
                $select = $this->tableGateway->getSql()->select()->columns(array());
                $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner');
                $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array(), 'left');
                $select->where($where_query);
                $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 16 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 30 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 16 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 30 day)))) GROUP BY user1.user_id)");
                $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 31 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 45 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 31 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 45 day)))) GROUP BY user1.user_id)");
                $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 60 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 60 day)))) GROUP BY user1.user_id)");
                $select->group('user.user_id');
             }
             
             if($params['dataType'] == 'daywise_consum_30'){ 
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_sub_query =  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                    $where_query .=  $where_sub_query;
                    $where_query .=  " AND ( (DATE(ul.login) >=DATE_ADD(user.create_time, INTERVAL 16 day) AND DATE(ul.login) <= DATE_ADD(user.create_time, INTERVAL 30 day)) OR (DATE(ul.logout) >=DATE_ADD(user.create_time, INTERVAL 16 day) AND (DATE(ul.logout) <= DATE_ADD(user.create_time, INTERVAL 30 day))))"; 
                }
                
                $select = $this->tableGateway->getSql()->select()->columns(array());
                $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner');
                $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array(), 'left');
                $select->where($where_query);
                $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 31 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 45 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 31 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 45 day)))) GROUP BY user1.user_id)");
                $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 60 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 60 day)))) GROUP BY user1.user_id)");
                $select->group('user.user_id');
             }
             
             if($params['dataType'] == 'daywise_consum_45'){ 
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_sub_query =  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                    $where_query .=  $where_sub_query;
                    $where_query .=  " AND ( (DATE(ul.login) >=DATE_ADD(user.create_time, INTERVAL 31 day) AND DATE(ul.login) <= DATE_ADD(user.create_time, INTERVAL 45 day)) OR (DATE(ul.logout) >=DATE_ADD(user.create_time, INTERVAL 31 day) AND (DATE(ul.logout) <= DATE_ADD(user.create_time, INTERVAL 45 day))))"; 
                }
                
                $select = $this->tableGateway->getSql()->select()->columns(array());
                $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner');
                $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array(), 'left');
                $select->where($where_query);
                $select->where("user.user_id NOT IN (select user1.user_id FROM user user1 INNER JOIN user_logs ul1 on ul1.user_id=user1.user_id WHERE 1 ". $where_sub_query ." AND ( (DATE(ul1.login) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND DATE(ul1.login) <= DATE_ADD(user1.create_time, INTERVAL 60 day)) OR (DATE(ul1.logout) >=DATE_ADD(user1.create_time, INTERVAL 46 day) AND (DATE(ul1.logout) <= DATE_ADD(user1.create_time, INTERVAL 60 day)))) GROUP BY user1.user_id)");
                $select->group('user.user_id');
             }
             
             if($params['dataType'] == 'daywise_consum_60'){ 
                
                if(!empty($params['fromDate']) && !empty($params['toDate'])){
                    $where_sub_query =  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
                    $where_query .=  $where_sub_query;
                    $where_query .=  " AND ( (DATE(ul.login) >=DATE_ADD(user.create_time, INTERVAL 46 day) AND DATE(ul.login) <= DATE_ADD(user.create_time, INTERVAL 60 day)) OR (DATE(ul.logout) >=DATE_ADD(user.create_time, INTERVAL 46 day) AND (DATE(ul.logout) <= DATE_ADD(user.create_time, INTERVAL 60 day))))"; 
                }
                
                $select = $this->tableGateway->getSql()->select()->columns(array());
                $select->join(array("ul" => 'user_logs'), new Expression("ul.user_id=user.user_id"), array('login_count' => new \Zend\Db\Sql\Expression('COUNT(*)')), 'inner');
                $select->join(array("ssd" => 'sales_school_details'), "user.school_id = ssd.school_id", array(), 'left');
                $select->where($where_query);
                $select->group('user.user_id');
             }
        }
        
        $resultSet = $this->tableGateway->selectWith($select);
        //echo '<pre>';
        //print_r($resultSet);die;
        $resultSet->buffer();
        return $resultSet;
    }
    
    public function getBirthdayRecordsForSMS(){
        
        $select = $this->tableGateway->getSql()->select()->columns(array('email','display_name','mobile','dob','user_id'))
                ->where("DATE_FORMAT(dob, '%m-%d')=DATE_FORMAT(NOW(), '%m-%d')")
                ->where("mobile LIKE '+91%' AND (LENGTH(mobile)=13 OR LENGTH(mobile)=14)");
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet->toArray();
    }
    
    public function getBirthdayRecordsForMail(){
        
        $select = $this->tableGateway->getSql()->select()->columns(array('email','display_name','mobile','dob','user_id'))
                ->where("DATE_FORMAT(dob, '%m-%d')=DATE_FORMAT(NOW(), '%m-%d')");
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet->toArray();
    }
    
    public function getUserReportsData($params) {
        $where_query = "1";
        if(!empty($params['fromDate']) && !empty($params['toDate'])){
            $where_query .=  " AND ( user.create_time >= '".$params['fromDate']." 00:00:00' AND user.create_time <= '".$params['toDate'] . " 23:59:59')";   
        }
        
        $select = $this->tableGateway->getSql()->select()->columns(array('create_time', 'display_name' => 'display_name', 'email' => 'email', 'mobile', 'user_type_id', 'city', 'subscribed_user_type' => new \Zend\Db\Sql\Expression("CASE WHEN (select count(*) from user as user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id LEFT JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE (tdc1.coupon_type!='test' OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) AND user.user_id=user1.user_id) > 0 THEN 'YES' ELSE 'NO' END")));
        $select->join(array("up" => 'user_provider'), "user.user_id = up.user_id", array('provider' => new \Zend\Db\Sql\Expression("CASE WHEN up.provider='google' THEN 'Google' WHEN up.provider='facebook' THEN 'Facebook' WHEN up.provider='twitter' THEN 'Twitter' ELSE 'Direct' END")), 'left') 
                ->join(array("pr" => "progress_report"), "pr.user_id = user.user_id", array('subject_name' => new \Zend\Db\Sql\Expression("CASE WHEN rr2.rack_type_id=4 THEN rn2.name WHEN rr3.rack_type_id=4 THEN rn3.name WHEN rr4.rack_type_id=4 THEN rn4.name ELSE rn1.name END"),'chapter_name' => new \Zend\Db\Sql\Expression("rn1.name"),'start_time' => new \Zend\Db\Sql\Expression("SUBTIME(MAX(pr.created_date), total_time)"), 'end_time' => new \Zend\Db\Sql\Expression("MAX(pr.created_date)"), 'total_time' => new \Zend\Db\Sql\Expression("ROUND(sum(TIME_TO_SEC(total_time))/60,2)")) , 'left')
                ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'left')
                ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id"), array(), 'left')
                ->join(array("serv1" => "services"), "pr.board_service_id = serv1.service_id", array('service' => new \Zend\Db\Sql\Expression("CASE WHEN serv2.service_name != '' OR serv2.service_name != NULL THEN serv2.service_name ELSE serv1.service_name END"), 'service_type' => new \Zend\Db\Sql\Expression('dg.group_name')), 'left')
                ->join(array("serv2" => "services"), "serv1.parent_service_id = serv2.service_id", array('board_name' => new \Zend\Db\Sql\Expression("CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN pr.custom_board_rack_id IS NOT NULL THEN cb1.board_name WHEN rr5.rack_type_id=2 THEN rn5.name ELSE rn8.name END"), 'class_name' => new \Zend\Db\Sql\Expression("CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr5.rack_type_id=20 THEN rn5.name WHEN rr3.rack_type_id=3 THEN rn3.name WHEN rr4.rack_type_id=3 THEN rn4.name WHEN rr3.rack_type_id=20 THEN rn3.name ELSE rn7.name END")), 'left')
                ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                ->join(array("cbr1" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr1.custom_board_rack_id", array(), 'left')
                ->join(array("cb1" => 'custom_board'), "cbr1.custom_board_id=cb1.custom_board_id", array(), 'left')
                ->join(array("rr1" => 'resource_rack'), "pr.board_container_id=rr1.rack_id", array('rack_id1' => 'rack_id', 'rack_typ_id1' => 'rack_type_id'), 'left')
                ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array('rack_name1' => 'name'), 'left')
                ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array('rack_id2' => 'rack_id', 'rack_typ_id2' => 'rack_type_id'), 'left')
                ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array('rack_name2' => 'name'), 'left')
                ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array('rack_id3' => 'rack_id', 'rack_typ_id3' => 'rack_type_id'), 'left')
                ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array('rack_name3' => 'name'), 'left')
                ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array('rack_id4' => 'rack_id', 'rack_typ_id4' => 'rack_type_id'), 'left')
                ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array('rack_name4' => 'name'), 'left')
                ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array('rack_id5' => 'rack_id', 'rack_typ_id5' => 'rack_type_id'), 'left')
                ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array('rack_name5' => 'name'), 'left')
                ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array('rack_id6' => 'rack_id', 'rack_typ_id6' => 'rack_type_id'), 'left')
                ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array('rack_name6' => 'name'), 'left')
                ->join(array("rr7" => 'resource_rack'), "cbr.rack_id=rr7.rack_id", array('rack_id7' => 'rack_id', 'rack_typ_id7' => 'rack_type_id'), 'left')
                ->join(array("rn7" => 'rack_name'), "rn7.rack_name_id=rr7.rack_name_id", array('rack_name7' => 'name'), 'left')
                ->join(array("rr8" => 'resource_rack'), "user.board_id=rr8.rack_id", array('rack_id8' => 'rack_id', 'rack_typ_id8' => 'rack_type_id'), 'left')
                ->join(array("rn8" => 'rack_name'), "rn8.rack_name_id=rr8.rack_name_id", array('rack_name8' => 'name'), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id=ssd.school_id", array('school_name' => new \Zend\Db\Sql\Expression("CASE WHEN user.school_name ='' THEN ssd.school_name ELSE user.school_name END"), 'school_code'), 'left')
                ->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array('valid_from', 'valid_till','paid_price'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' THEN `tut`.`transaction_amount` ELSE SUM(paid_amount) END"), 'currency_type','discount_amount'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' OR tut.transaction_amount =0 THEN `tut`.`transaction_discount` ELSE SUM(discount_amount) END")), 'left')
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array('syllabus_id'), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name'), 'left')
                ->join(array("tpc" => "t_package_category"), "tpc.id = tp.package_category", array('package_type' => new \Zend\Db\Sql\Expression("CONCAT(tpc.display_name, ' - ', tp.package_name)"), 'validity_days' => new \Zend\Db\Sql\Expression("DATEDIFF(tup.valid_till, tup.valid_from)")), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','purchase_date'=>'purchase_date','pkg_payment_type','transaction_discount', 'transaction_amount', 'payment_mode' => new \Zend\Db\Sql\Expression("CASE WHEN tut.pkg_payment_type='offline' THEN 'Offline' WHEN tut.pkg_payment_type != 'offline' THEN 'Online' ELSE '' END")), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('discount_percent','coupon_type','discount_type'), 'left')           
                ->join(array("ts" => "t_state"), "ts.state_id = user.state_id", array('state_name'), 'left')
                ->join(array("tc" => "t_country"), "tc.country_id = user.country_id", array('country_name'), 'left')
                ->where($where_query)
                ->group(array('user.user_id', 'pr.board_container_id', 'pr.board_service_id', 'tut.transaction_id'))
                ->order('create_time DESC');
        
        $resultSet = $this->tableGateway->selectWith($select);
        //echo '<pre>';
        //print_r($resultSet);die;
        $resultSet->buffer();
        return $resultSet;
    }
    
    public function getDailyUserReportsData($params) {        
        ini_set('memory_limit', '500M');
        set_time_limit(0);
        //echo '<pre>';print_r($params); echo '</pre>';die('macro Die');
        $subQuery = "";
        $where_query = "1";
        $where_temp_query = "";
        $having_query = "";
        
        if(!empty($params['reportType']) && $params['reportType'] == 'Learn_Practice') {            
            if(!empty($params['fromDate']) && !empty($params['toDate'])){
                $where_temp_query .=  " ( progress_report.created_date >= '".$params['fromDate']." 00:00:00' AND progress_report.created_date <= '".$params['toDate'] . " 23:59:59')";   
            }
            $subQuery .= "select user_id, created_date as session_date from progress_report where" . $where_temp_query . "";
            
            $where_query .=  " AND ( pr.created_date >= '".$params['fromDate']." 00:00:00') AND dg.group_name IS NOT NULL AND serv1.service_name IS NOT NULL";   
            $having_query = "service_type!='Test' AND subject_name IS NOT NULL AND chapter_name IS NOT NULL";
        }
        
        if(!empty($params['reportType']) && $params['reportType'] == 'Test_Attempted') {            
            if(!empty($params['fromDate']) && !empty($params['toDate'])){
                $where_temp_query .=  " ( quiz_set.set_date_added >= '".$params['fromDate']." 00:00:00' AND quiz_set.set_date_added <= '".$params['toDate'] . " 23:59:59')";   
            }
            $subQuery .= "select user_id, set_date_added as session_date from quiz_set where" . $where_temp_query . "";
            $where_query .=  " AND ( t.session_date >= '".$params['fromDate']." 00:00:00') AND dg.group_name IS NOT NULL AND serv1.service_name IS NOT NULL";
            $having_query = "service_type='Test' AND subject_name IS NOT NULL AND chapter_name IS NOT NULL";
        }
        
        if(!empty($params['reportType']) && $params['reportType'] == 'Login_Session') {            
            if(!empty($params['fromDate']) && !empty($params['toDate'])){
                $where_temp_query .=  " (( user_logs.login >= '".$params['fromDate']." 00:00:00' AND user_logs.login <= '".$params['toDate'] . " 23:59:59'))";   
            }
            $subQuery .= "select user_id, login as session_date, logout from user_logs where" . $where_temp_query . "";
            $where_query .=  " AND ( ( t.session_date >= '".$params['fromDate']." 00:00:00'))";   
        }
        
        if(!empty($params['reportType']) && $params['reportType'] == 'Created_Mentor') {            
            if(!empty($params['fromDate']) && !empty($params['toDate'])){
                $where_temp_query .=  " ( t_student_and_mentor.joining_date >= '".$params['fromDate']." 00:00:00' AND t_student_and_mentor.joining_date <= '".$params['toDate'] . " 23:59:59')";   
            }
            $subQuery .= "select request_initiator as user_id, joining_date as session_date from t_student_and_mentor where" . $where_temp_query . "";
            $where_query .=  " AND ( t.session_date >= '".$params['fromDate']." 00:00:00')";
        }
        
        //echo $subQuery; die;

        $query1 = "";
        $select = $this->tableGateway->getAdapter()->query("Create temporary table T ". $subQuery .";");
        $query1= $select->getSql();

        $data = array();
        $stmt = $this->tableGateway->getAdapter()->createStatement($query1);
        //$stmt->prepare();              
        $resultSet = $stmt->execute($data);
        $resultSet->buffer();
        
        $select = $this->tableGateway->getSql()->select()->columns(array('create_time', 'display_name' => 'display_name', 'email' => 'email', 'mobile', 'user_type_id', 'city', 'subscribed_user_type' => new \Zend\Db\Sql\Expression("CASE WHEN (select count(*) from user as user1 INNER JOIN t_user_package AS tup1 ON user1.user_id=tup1.user_id INNER JOIN t_user_transaction AS tut1 ON tut1.transaction_id = tup1.transaction_id LEFT JOIN t_discount_coupon AS tdc1 ON tdc1.coupon_id=tut1.coupon_id WHERE ((tdc1.coupon_type!='test' AND tdc1.coupon_type !='promotional') OR tdc1.coupon_type IS NULL) AND tup1.status IN(1,2,3) AND user.user_id=user1.user_id AND tut1.pkg_payment_type=tut.pkg_payment_type  AND tup1.package_name=tup.package_name AND tut1.purchase_date=tut.purchase_date) > 0 THEN 'YES' ELSE 'NO' END")));
        $select->join(array("t" => 'T'), "t.user_id = user.user_id", array('session_date'), 'inner') 
                ->join(array("up" => 'user_provider'), "user.user_id = up.user_id", array('provider' => new \Zend\Db\Sql\Expression("CASE WHEN up.provider='google' THEN 'Google' WHEN up.provider='facebook' THEN 'Facebook' WHEN up.provider='twitter' THEN 'Twitter' ELSE 'Direct' END")), 'left') 
                ->join(array("pr" => "progress_report"), "pr.user_id = user.user_id", array('subject_name' => new \Zend\Db\Sql\Expression("CASE WHEN rr2.rack_type_id=4 THEN rn2.name WHEN rr3.rack_type_id=4 THEN rn3.name WHEN rr4.rack_type_id=4 THEN rn4.name ELSE rn1.name END"),'chapter_name' => new \Zend\Db\Sql\Expression("rn1.name"),'start_time' => new \Zend\Db\Sql\Expression("SUBTIME(MAX(pr.created_date), SEC_TO_TIME((sum(TIME_TO_SEC(total_time))/60)*60))"), 'end_time' => new \Zend\Db\Sql\Expression("MAX(pr.created_date)"), 'total_time' => new \Zend\Db\Sql\Expression("ROUND(sum(TIME_TO_SEC(total_time))/60,2)")) , 'left')
                ->join(array("mdg" => "mapped_dashboard_group"), "mdg.service_id=pr.board_service_id", array(), 'left')
                ->join(array("dg" => "dashboard_group"), new Expression("mdg.dashboard_group_id=dg.dashboard_group_id"), array(), 'left')
                ->join(array("serv1" => "services"), "pr.board_service_id = serv1.service_id", array('service' => new \Zend\Db\Sql\Expression("CASE WHEN serv2.service_name != '' OR serv2.service_name != NULL THEN serv2.service_name ELSE serv1.service_name END"), 'service_type' => new \Zend\Db\Sql\Expression('dg.group_name')), 'left')
                ->join(array("serv2" => "services"), "serv1.parent_service_id = serv2.service_id", array('board_name' => new \Zend\Db\Sql\Expression("CASE WHEN user.custom_board_rack_id IS NOT NULL THEN cb.board_name WHEN pr.custom_board_rack_id IS NOT NULL THEN cb1.board_name WHEN rr5.rack_type_id=2 THEN rn5.name ELSE rn8.name END"), 'class_name' => new \Zend\Db\Sql\Expression("CASE WHEN rr4.rack_type_id=20 THEN rn4.name WHEN rr5.rack_type_id=20 THEN rn5.name WHEN rr3.rack_type_id=3 THEN rn3.name WHEN rr3.rack_type_id=20 THEN rn3.name WHEN rr4.rack_type_id=3 THEN rn4.name ELSE rn7.name END")), 'left')
                ->join(array("cbr" => 'custom_board_rack'), "user.custom_board_rack_id=cbr.custom_board_rack_id", array(), 'left')
                ->join(array("cb" => 'custom_board'), "cbr.custom_board_id=cb.custom_board_id", array(), 'left')
                ->join(array("cbr1" => 'custom_board_rack'), "pr.custom_board_rack_id=cbr1.custom_board_rack_id", array(), 'left')
                ->join(array("cb1" => 'custom_board'), "cbr1.custom_board_id=cb1.custom_board_id", array(), 'left')
                ->join(array("rr1" => 'resource_rack'), "pr.board_container_id=rr1.rack_id", array(), 'left')
                ->join(array("rn1" => 'rack_name'), "rn1.rack_name_id=rr1.rack_name_id", array(), 'left')
                ->join(array("rr2" => 'resource_rack'), "rr2.rack_id=rr1.rack_container_id", array(), 'left')
                ->join(array("rn2" => 'rack_name'), "rn2.rack_name_id=rr2.rack_name_id", array(), 'left')
                ->join(array("rr3" => 'resource_rack'), "rr3.rack_id=rr2.rack_container_id", array(), 'left')
                ->join(array("rn3" => 'rack_name'), "rn3.rack_name_id=rr3.rack_name_id", array(), 'left')
                ->join(array("rr4" => 'resource_rack'), "rr4.rack_id=rr3.rack_container_id", array(), 'left')
                ->join(array("rn4" => 'rack_name'), "rn4.rack_name_id=rr4.rack_name_id", array(), 'left')
                ->join(array("rr5" => 'resource_rack'), "rr5.rack_id=rr4.rack_container_id", array(), 'left')
                ->join(array("rn5" => 'rack_name'), "rn5.rack_name_id=rr5.rack_name_id", array(), 'left')
                ->join(array("rr6" => 'resource_rack'), "rr6.rack_id=rr5.rack_container_id", array(), 'left')
                ->join(array("rn6" => 'rack_name'), "rn6.rack_name_id=rr6.rack_name_id", array(), 'left')
                ->join(array("rr7" => 'resource_rack'), "cbr.rack_id=rr7.rack_id", array(), 'left')
                ->join(array("rn7" => 'rack_name'), "rn7.rack_name_id=rr7.rack_name_id", array(), 'left')
                ->join(array("rr8" => 'resource_rack'), "user.board_id=rr8.rack_id", array(), 'left')
                ->join(array("rn8" => 'rack_name'), "rn8.rack_name_id=rr8.rack_name_id", array(), 'left')
                ->join(array("ssd" => 'sales_school_details'), "user.school_id=ssd.school_id", array('school_name' => new \Zend\Db\Sql\Expression("CASE WHEN user.school_name ='' THEN ssd.school_name ELSE user.school_name END"), 'school_code'), 'left')
                ->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array('valid_from', 'valid_till','paid_price'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' THEN `tut`.`transaction_amount` ELSE SUM(paid_amount) END"), 'currency_type','discount_amount'=> new \Zend\Db\Sql\Expression("CASE WHEN tut.transaction_amount !='' OR tut.transaction_amount =0 THEN `tut`.`transaction_discount` ELSE SUM(discount_amount) END")), 'left')
                ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array(), 'left')
                ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_name'), 'left')
                ->join(array("tpc" => "t_package_category"), "tpc.id = tp.package_category", array('package_type' => new \Zend\Db\Sql\Expression("CONCAT(tpc.display_name, ' - ', tp.package_name)"), 'validity_days' => new \Zend\Db\Sql\Expression("DATEDIFF(tup.valid_till, tup.valid_from)")), 'left')
                ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_product_type','purchase_date'=>'purchase_date','pkg_payment_type','transaction_discount', 'transaction_amount', 'payment_mode' => new \Zend\Db\Sql\Expression("CASE WHEN tut.pkg_payment_type='offline' THEN 'Offline' WHEN tut.pkg_payment_type != 'offline' THEN 'Online' ELSE '' END")), 'left')
                ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('discount_percent','coupon_type','discount_type'), 'left')           
                ->join(array("ts" => "t_state"), "ts.state_id = user.state_id", array('state_name'), 'left')
                ->join(array("tc" => "t_country"), "tc.country_id = user.country_id", array('country_name'), 'left')
                ->where($where_query)
                ->group(array('user.user_id', 'pr.board_container_id', 'pr.board_service_id', 'tut.transaction_id'));
       
        if(!empty($params['reportType']) && ($params['reportType'] == 'Test_Attempted' || $params['reportType'] == 'Learn_Practice')) {
            $select->having($having_query);
        }
       
       $select->order('create_time DESC');

        $query2 = $select->getSqlString();

        $combineQuey = str_replace('"', '`', $query2);

        $data = array();
        $stmt = $this->tableGateway->getAdapter()->createStatement($combineQuey);
        //$stmt->prepare();        
        $resultSet = $stmt->execute($combineQuey);
        
        //$resultSet = $this->tableGateway->selectWith($select);
        //echo '<pre>';
        //print_r($resultSet);die;
        //$resultSet->buffer();
        return $resultSet;
    }
    
    public function getUsersConsumptionDetails($userId){
        try {
            $select = $this->tableGateway->getSql()->select()->columns(array('last_login' => new \Zend\Db\Sql\Expression('(select login from user_logs ul where ul.user_id=user.user_id order by login DESC limit 1)'), 'login_count' => new \Zend\Db\Sql\Expression('(select count(*) from user_logs ul where ul.user_id=user.user_id)'), 'test_attempt_count' => new \Zend\Db\Sql\Expression('(select count(*) from quiz_set qs where qs.user_id=user.user_id)'), 'learn_duration' => new \Zend\Db\Sql\Expression('(select sum(TIME_TO_SEC(total_time))/60 from progress_report pr join mapped_dashboard_group mdg on mdg.service_id=pr.board_service_id join dashboard_group dg on mdg.dashboard_group_id=dg.dashboard_group_id where pr.user_id=user.user_id and dg.group_name="Learn")'), 'practice_duration' => new \Zend\Db\Sql\Expression('(select sum(TIME_TO_SEC(total_time))/60 from progress_report pr join mapped_dashboard_group mdg on mdg.service_id=pr.board_service_id join dashboard_group dg on mdg.dashboard_group_id=dg.dashboard_group_id where pr.user_id=user.user_id and dg.group_name="Practice")')))
                    ->where('user.user_id=' . $userId)
                   ->group('user.user_id');
            $result = $this->tableGateway->selectWith($select); //echo'<pre>';print_r($result);die;
        } catch (Exception $e) {
            echo $e;
        }
        return $result;
    }
    public function misReportDataForRepair($params = array()) {  
         
    if(!empty($params['user_id'])){
        $where_query =  "tu.user_id = '" . $params['user_id']. "'"; 
    }
    $select = $this->tableGateway->getSql()->select()->columns(array('user_id', 'email', 'display_name','mobile', 'address','city','user_type_id','username'));
    $select->join(array("tup" => 't_user_package'), "user.user_id = tup.user_id", array('package_id', 'discount_amount','status','user_package_id','valid_till','paid_price'=> 'paid_amount', 'package_price', 'currency_type', 'is_switched'), 'left')
            ->join(array("tps" => "t_package_syllabi"), "tup.package_id = tps.id", array('syllabus_id'), 'left')
            ->join(array("tp" => "t_package"), "tp.package_id = tps.package_id", array('package_id','package_name','class_name'=>'class','board_name'=>'board', 'price', 'package_category'), 'left')
            ->join(array("tpc" => "t_package_classes"), "tpc.package_id = tp.package_id", array('combo_class_id' => new Expression('GROUP_CONCAT(tpc.class_id)')), 'left')
            ->join(array("tut" => "t_user_transaction"), "tut.transaction_id = tup.transaction_id", array('transaction_id','transaction_product_type','order_id','purchase_date'=>'purchase_date','pkg_payment_details','pkg_payment_type','purchaser_id','transaction_status' => 'status','employee_id','transaction_gateway_response', 'ip', 'transaction_discount', 'transaction_amount', 'transaction_total_refund_amount', 'transaction_refund_type','code_assign_id'), 'left')
            ->join(array("ts" => "t_state"), "ts.state_id = user.state_id", array('state_name'), 'left')
            ->join(array("tdc" => "t_discount_coupon"), new Expression('tdc.coupon_id = tut.coupon_id'), array('coupon_id','coupon_code','discount_percent','coupon_type','discount_type'), 'left')           
            ->join(array("tu" => "user"), new Expression("tut.purchaser_id = tu.user_id"), array('parent_name'=>'display_name','parent_email'=>'email','parent_address'=>'address','parent_mobile'=>'mobile', 'parent_user_type_id' => 'user_type_id'), 'left')
            ->join(array("ted" => "t_employee_details"), new Expression("tut.employee_id = ted.employee_code"), array('employee_name'=>'emp_name'), 'left')
            ->join(array("opt" => "offline_payment_transaction"), new Expression("opt.user_transaction_id = tut.transaction_id"), array('payment_mode'=>'payment_mode','login_id'=>'login_id','payment_source'=>'payment_source','dd_cheque_number', 'dd_cheque_date', 'bank', 'deposit_date', 'account_number', 'bank_branch','payment_update_user_id','other_payment_details'), 'left')
            ->join(array('in_study'  => 'invoices_study') , new Expression('tut.order_id=in_study.order_id AND tut.transaction_product_type="study"'),array("study_invoice_id"=>'id'),'left') 
            ->join(array('in_tab'  => 'invoices_tablet') , new Expression('tut.order_id=in_tab.order_id AND tut.transaction_product_type="tablet"'),array("tab_invoice_id"=>'id'),'left') 
            ->join(array('in_sdcard'  => 'invoices_sdcard') , new Expression('tut.order_id=in_sdcard.order_id AND tut.transaction_product_type="sdcard"'),array("sdcard_invoice_id"=>'id'),'left') 
            ->join(array('in_td'  => 'invoices_testing_demo') , new Expression('tut.order_id=in_td.order_id'),array("invoice_id"=>'id'),'left') 
            ->join(array('aca'  => 'activation_code_assignment') , new Expression('aca.code_assign_id=tut.code_assign_id'),array("assign_activation_code", "assign_card_price"),'left') 
            ->join(array('trud'  => 't_referred_user_details') , new Expression('trud.referred_by_user_id=tut.purchaser_id AND trud.transaction_id=tut.transaction_id'),array('refered_ids' => new Expression('GROUP_CONCAT(trud.id)')),'left') 
            ->join(array("tprr" => "t_package_repair_request"), "tprr.transaction_id = tut.transaction_id AND tprr.user_package_id=tup.user_package_id", array('repair_replace_id'=>'id','repair_replace_status'=>'status','request_type'), 'left')
            ->join(array("inv" => "invoices"), "tprr.id = inv.request_id", array('invoice_number'=>'invoicenumber'), 'left')
            ->where($where_query)
            ->where("tut.order_id IS NOT NULL");
                 
            $select->group(array("tup.transaction_id", "tup.user_package_id"));
            $select->order("tut.transaction_id DESC"); 
            $resultSet = $this->tableGateway->selectWith($select);// echo '<pre>';print_r($resultSet);die;
            return $resultSet;
 }
    
    
//    
/** End Of Class **/        
}


