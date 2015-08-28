<?php
namespace Assessment\Model;

class Tuserlicensedetail
{
	public $id;
	public $user_id;
	public $license_id;
	public $tablet_id;
        public $project_name;
        public $start_date;
        public $expiration_date;
        public $buffer_days;
        public $activation_type;
        public $manufacturer;
        public $application_ver;
        public $operating_system;
        public $model_number;
        public $tablet_mapped;
        public $creation_date;
        public $updation_date;
        
        
	public function exchangeArray($data)
	{
	 $this->id 	  		= (isset($data['id'])) 		? $data['id'] 			: null; 
        $this->user_id 	  		= (isset($data['user_id'])) 		? $data['user_id'] 			: null; 
	$this->license_id                =  (isset($data['license_id']))	? $data['license_id']	: null;	
        $this->tablet_id   			= (isset($data['tablet_id']))  			? $data['tablet_id']			: null;	
	$this->project_name                 =  (isset($data['project_name']))  			? $data['project_name']			: null;
        $this->start_date                 =  (isset($data['start_date']))  			? $data['start_date']			: null;	
        $this->expiration_date                 =  (isset($data['expiration_date']))  ? $data['expiration_date']                         : null;
        
         $this->buffer_days                 =  (isset($data['buffer_days']))  ? $data['buffer_days']                                    : null;
          $this->activation_type                 =  (isset($data['activation_type']))  ? $data['activation_type']			: null;
           $this->manufacturer                 =  (isset($data['manufacturer']))  ? $data['manufacturer']			: null;
            $this->application_ver                 =  (isset($data['application_ver']))  ? $data['application_ver']			: null;
             $this->creation_date                 =  (isset($data['creation_date']))  ? $data['creation_date']			: null;
              $this->updation_date                 =  (isset($data['updation_date']))  ? $data['updation_date']			: null;
              $this->operating_system                 =  (isset($data['operating_system']))  ? $data['operating_system']			: null;
              $this->model_number                 =  (isset($data['model_number']))  ? $data['model_number']			: null;
              $this->tablet_mapped                 =  (isset($data['tablet_mapped']))  ? $data['tablet_mapped']			: null;
         
      
	}
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}
