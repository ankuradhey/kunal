<?php
namespace Notification\Model;

class Tquestion
{	
	public $question_id;
	public $user_id;
	public $board_id;
	public $class_id;
	public $subject_id;
	public $chapter_id;
	public $question;
	public $added_date;	
	public $c_status;
	public $first_name;
	public $name;
	public $board_name;
	public $class_name;
	public $school_name;
	public $user_photo;
	//public $countLikes;
	public $userLike;
	public $countReplys;
	public $subject_name;
	public $chapter_name;
	public $email_id;
	public $mobile;
	public $address;
        public $deleted_user;
        public $group_owner_id;
        public $question_asked_to;
        public $pdf_file_name;
        public $up_down_id;
        public $file_name;
        
	public function exchangeArray($data)
	{
		$this->question_id      = (isset($data['question_id']))     ? $data['question_id']  : null;
		$this->user_id     	    = (isset($data['user_id']))         ? $data['user_id']     	: null;
                $this->board_id   	    = (isset($data['board_id'])) 	    ? $data['board_id']     : null;
		$this->class_id   	    = (isset($data['class_id'])) 		? $data['class_id']     : null;
		$this->subject_id     	= (isset($data['subject_id']))     	? $data['subject_id']   : null;
		$this->chapter_id   	= (isset($data['chapter_id'])) 	    ? $data['chapter_id']   : null;
		$this->question   	    = (isset($data['question'])) 	    ? $data['question']      : null;
		$this->added_date     	= (isset($data['added_date']))     	? $data['added_date']   : null;      
		$this->c_status   		= (isset($data['c_status'])) 		? $data['c_status'] 	: null;
		$this->first_name   	= (isset($data['first_name'])) 		? $data['first_name'] 	: null;
		$this->name   		    = (isset($data['name'])) 		    ? $data['name'] 	    : null;
		$this->board_name   	= (isset($data['board_name'])) 		? $data['board_name'] 	: null;
                $this->boardname   	= (isset($data['boardname'])) 		? $data['boardname'] 	: null;
		 $this->class_name   	= (isset($data['class_name'])) 		? $data['class_name'] 	: null;
                  $this->classname   	= (isset($data['classname'])) 		? $data['classname'] 	: null;
		$this->school_name   	= (isset($data['school_name'])) 	? $data['school_name'] 	: null;
		$this->user_photo   	= (isset($data['user_photo'])) 	    ? $data['user_photo'] 	: null;
		//$this->countLikes       = (isset($data['countLikes']))      ? $data['countLikes'] 	: null;	
		$this->userLike         = (isset($data['userLike']))        ? $data['userLike'] 	: null;
		$this->countReplys     = (isset($data['countReplys']))     ? $data['countReplys'] 	: null;
		$this->subject_name     = (isset($data['subject_name']))     ? $data['subject_name'] 	: null;
		$this->chapter_name     = (isset($data['chapter_name']))     ? $data['chapter_name'] 	: null;
		$this->email_id     	= (isset($data['email']))     		? $data['email'] 	: null;
		$this->mobile     		= (isset($data['mobile']))     		? $data['mobile'] 	: null;
		$this->address    		 = (isset($data['address']))    	 ? $data['address'] 	: null;
                $this->deleted_user    		 = (isset($data['deleted_user']))    	 ? $data['deleted_user'] 	: null;
                $this->group_owner_id    		 = (isset($data['group_owner_id']))    	 ? $data['group_owner_id'] 	: null;
                $this->question_asked_to   = (isset($data['question_asked_to']))    	 ? $data['question_asked_to'] 	: null;
                
                $this->pdf_file_name   = (isset($data['pdf_file_name']))    	 ? $data['pdf_file_name'] 	: null;
                $this->up_down_id   = (isset($data['up_down_id']))    	 ? $data['up_down_id'] 	: null;
                $this->file_name    = (isset($data['file_name'])) 	      ? $data['file_name'] 	: null;
        
	}
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

}