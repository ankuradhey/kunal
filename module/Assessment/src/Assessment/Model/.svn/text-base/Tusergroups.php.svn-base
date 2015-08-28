<?php
namespace Assessment\Model;

class Tusergroups
{
	public $group_id;
	public $user_id;
	public $friend_id;
	public $added_date;
	public $group_status;
	public $first_name;
	public $board_name;
	public $class_name;
	public $email_id;
	public $school_name;
        public $view_group_member_status;
	
	public function exchangeArray($data)
	{
		$this->group_id	  		= (isset($data['group_id']))  		? $data['group_id']    		: null;
        $this->user_id 	  		= (isset($data['user_id'])) 		? $data['user_id'] 			: null; 
		$this->friend_id   		= (isset($data['friend_id']))  		? $data['friend_id']		: null;	
        $this->added_date   	= (isset($data['added_date']))  	? $data['added_date']		: null;	
		$this->group_status   	= (isset($data['group_status']))  	? $data['group_status']		: null;	
		$this->first_name   	= (isset($data['display_name']))  	? $data['display_name']		: null;
		$this->board_name   	= (isset($data['board_name']))  	? $data['board_name']		: null;
		$this->class_name   	= (isset($data['class_name']))  	? $data['class_name']		: null;
		$this->email_id   		= (isset($data['email']))  		? $data['email']			: null;
		$this->school_name   	= (isset($data['school_name']))  	? $data['school_name']		: null;	
                $this->view_group_member_status   	= (isset($data['view_group_member_status']))  	? $data['view_group_member_status']		: null;	
    }
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}