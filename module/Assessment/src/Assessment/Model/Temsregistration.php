<?php
namespace Assessment\Model;

class Temsregistration
{
	public $id;
	public $name;
	public $email;
	public $phone;
        public $emp_code;
        public $status;
        public $creation_date;
        public $updation_date;
        
        
	public function exchangeArray($data)
	{
            $this->id 	  	 = (isset($data['id']))  ? $data['id'] 	: null; 
            $this->name 	 = (isset($data['name']))  ? $data['name']  : null; 
            $this->email         = (isset($data['email']))	? $data['email']	: null;	
            $this->phone   	 = (isset($data['phone']))  ? $data['phone']	: null;	
            $this->emp_code      = (isset($data['emp_code']))  ? $data['emp_code']	: null;
            $this->status        = (isset($data['status']))  ? $data['status']	: null;
            $this->creation_date = (isset($data['creation_date']))  ? $data['creation_date']			: null;
            $this->updation_date = (isset($data['updation_date']))  ? $data['updation_date']			: null;
      
	}
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}
