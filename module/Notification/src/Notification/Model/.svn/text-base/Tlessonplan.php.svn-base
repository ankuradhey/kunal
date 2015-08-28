<?php
namespace Notification\Model;

class Tlessonplan
{	
	public $plan_id;
	public $created_on;
	public $is_completed;
	public $is_reminded;
	public $is_started;
	public $plan_date;
	public $end_date;
	public $node_id;
	public $user_id;
	public $package_usage_id;
	public $chapter_name;
	public $subject_name;
	public $lesson_name;
	public $board_class_subject_id;
	public $board_class_subject_chapter_id;
	public $total;
	public $type;
        public $status;
        public $class_id;
        public $assign_user_id;
        public $assign_type;
        public $alt_class_id;
        
	public function exchangeArray($data)
	{
		$this->plan_id     	= (isset($data['plan_id']))     	? $data['plan_id']     		: null;
		$this->created_on     	= (isset($data['created_on']))     	? $data['created_on']   	: null;
                $this->is_completed   	= (isset($data['is_completed'])) 	? $data['is_completed'] 	: null;
		$this->is_reminded     	= (isset($data['is_reminded']))     ? $data['is_reminded']     	: null;
		$this->is_started     	= (isset($data['is_started']))     	? $data['is_started']   	: null;
                $this->plan_date   	= (isset($data['plan_date'])) 		? $data['plan_date'] 		: null;
		$this->end_date   	= (isset($data['end_date'])) 		? $data['end_date'] 		: null;
		$this->node_id   	= (isset($data['node_id'])) 		? $data['node_id'] 	   		: null;
		$this->user_id   	= (isset($data['user_id'])) 		? $data['user_id'] 	    	: null;
		$this->package_usage_id = (isset($data['package_usage_id'])) ? $data['package_usage_id']: null;
		$this->chapter_name 	= (isset($data['chapter_name'])) 	? $data['chapter_name']		: null;
		$this->subject_name 	= (isset($data['subject_name'])) 	? $data['subject_name']		: null;
		$this->lesson_name 	= (isset($data['lesson_name'])) 	? $data['lesson_name']		: null;
		
		$this->board_class_subject_id 		= (isset($data['board_class_subject_id'])) 	? $data['board_class_subject_id']		: null;
		$this->board_class_subject_chapter_id 		= (isset($data['board_class_subject_chapter_id'])) 	? $data['board_class_subject_chapter_id']		: null;
		$this->total 		= (isset($data['total'])) 	? $data['total']		: null;
		$this->type 		= (isset($data['type'])) 	? $data['type']		: null;
                $this->status 		= (isset($data['status'])) 	? $data['status']		: null;
                $this->class_id 	= (isset($data['class_id'])) 	? $data['class_id']		: null;
                $this->assign_user_id 	= (isset($data['assign_user_id'])) ? $data['assign_user_id']		: null;
                $this->assign_type 	= (isset($data['assign_type']))    ? $data['assign_type']		: null;
                $this->alt_class_id 	= (isset($data['alt_class_id']))   ? $data['alt_class_id']		: null;
	
        }
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

}