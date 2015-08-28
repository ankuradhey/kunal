<?php
namespace Assessment\Model;

class Temsstudents
{
	public $id;
	public $user_id;
	public $activation_code;
	public $user_package_id;
	public $source;
	public $registration_date;
        public $creation_date;
        public $updation_date;
        
        public $student_email;
        public $school_name;
        public $school_code;
        public $area_name;
        
	public function exchangeArray($data)
	{
            $this->id                           = (isset($data['id']))  ? $data['id'] 	: null; 
            $this->user_id                      = (isset($data['user_id']))  ? $data['user_id']  : null; 
            $this->activation_code              = (isset($data['activation_code']))	? $data['activation_code']	: null;	
            $this->user_package_id              = (isset($data['user_package_id']))	? $data['user_package_id']	: null;	
            $this->source                       = (isset($data['source']))	? $data['source']	: null;	
            $this->registration_date            = (isset($data['registration_date']))	? $data['registration_date']	: null;	
            $this->creation_date                = (isset($data['creation_date']))  ? $data['creation_date']			: null;
            $this->updation_date                = (isset($data['updation_date']))  ? $data['updation_date']			: null;
      
            $this->student_email                = (isset($data['student_email']))  ? $data['student_email']			: null;
            $this->school_name                = (isset($data['school_name']))  ? $data['school_name']			: null;
            $this->school_code                = (isset($data['school_code']))  ? $data['school_code']			: null;
            $this->area_name                = (isset($data['area_name']))  ? $data['area_name']			: null;
      
	}
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}
