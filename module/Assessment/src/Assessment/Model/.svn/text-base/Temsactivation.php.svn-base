<?php
namespace Assessment\Model;

class Temsactivation
{
	public $id;
	public $emp_code;
	public $school_code;
	public $activation_code;
	public $mac_address;
	public $days;
	public $ems_activation_start_date;
	public $ems_activation_end_date;
	public $status;
	public $alloted_to;
        public $creation_date;
        public $updation_date;
        public $name;
        public $email;
        public $phone;
        
        public $user_id;
        public $student_email;
        public $student_registered_date;
        public $school_name;
        public $area_name;
        public $registration_date;
        
	public function exchangeArray($data)
	{
            $this->id                           = (isset($data['id']))  ? $data['id'] 	: null; 
            $this->emp_code                     = (isset($data['emp_code']))  ? $data['emp_code']  : null; 
            $this->school_code                  = (isset($data['school_code']))  ? $data['school_code']  : null; 
            $this->activation_code              = (isset($data['activation_code']))  ? $data['activation_code']  : null; 
            $this->mac_address                  = (isset($data['mac_address']))	? $data['mac_address']	: null;	
            $this->days                         = (isset($data['days']))  ? $data['days']	: null;	
            $this->ems_activation_start_date    = (isset($data['ems_activation_start_date']))  	? $data['ems_activation_start_date']	: null;
            $this->ems_activation_end_date      = (isset($data['ems_activation_end_date']))  ? $data['ems_activation_end_date']	: null;
            $this->status                       = (isset($data['status']))  ? $data['status']	: null;
            $this->alloted_to                   = (isset($data['alloted_to']))  ? $data['alloted_to']	: null;
            $this->creation_date                = (isset($data['creation_date']))  ? $data['creation_date']	: null;
            $this->updation_date                = (isset($data['updation_date']))  ? $data['updation_date']	: null;
            $this->name                = (isset($data['name']))  ? $data['name']	: null;
            $this->email                = (isset($data['email']))  ? $data['email']	: null;
            $this->phone                = (isset($data['phone']))  ? $data['phone']	: null;
            
            $this->user_id                      = (isset($data['user_id']))  ? $data['user_id']	: null;
            $this->student_email                = (isset($data['student_email']))  ? $data['student_email'] : null;
            $this->student_registered_date      = (isset($data['student_registered_date']))  ? $data['student_registered_date']	: null;
            $this->school_name      = (isset($data['school_name']))  ? $data['school_name']	: null;
            $this->area_name      = (isset($data['area_name']))  ? $data['area_name']	: null;
            $this->registration_date      = (isset($data['registration_date']))  ? $data['registration_date']	: null;
      
	}
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}
