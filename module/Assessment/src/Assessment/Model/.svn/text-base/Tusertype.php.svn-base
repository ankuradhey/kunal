<?php
namespace Assessment\Model;

class Tusertype
{
	public $user_type_id;
	public $name;
	public $added_by;
	public $updated_by;	
	
	public function exchangeArray($data)
	{
		$this->user_type_id	  	= (isset($data['user_type_id']))  	? $data['user_type_id']    	: null;
        $this->name 	  		= (isset($data['name'])) 		    ? $data['name'] 			: null;        
        $this->added_by   		= (isset($data['added_by']))  		? $data['added_by']			: null;	
		$this->updated_by   	= (isset($data['updated_by']))  	? $data['updated_by']		: null;		        
    }
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}