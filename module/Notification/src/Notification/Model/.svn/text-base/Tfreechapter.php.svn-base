<?php
namespace Notification\Model;

class Tfreechapter
{
	public $id;
	public $container_id;
	public $status;
        public $board_name;
        public $class_name;
        public $subject_name;
        public $chapter_name;
        
	public function exchangeArray($data)
	{
	$this->id	= (isset($data['id']))  		? $data['id']    		: null;
        $this->container_id 	  	= (isset($data['container_id'])) 		? $data['container_id'] 		: null;        
	$this->status 	  	= (isset($data['status'])) 		? $data['status']				: null;   
        $this->board_name 	  	= (isset($data['board_name'])) 		? $data['board_name']				: null;   
        $this->class_name 	  	= (isset($data['class_name'])) 		? $data['class_name']				: null;   
        $this->subject_name 	  	= (isset($data['subject_name'])) 		? $data['subject_name']				: null;   
        $this->chapter_name 	  	= (isset($data['chapter_name'])) 		? $data['chapter_name']				: null;   
        
	
    }
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}
