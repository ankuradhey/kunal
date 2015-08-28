<?php
namespace Assessment\Model;

class tabletupdatedappdetails
{
	public $id;
	public $app_id;
	public $app_name;
	public $updated_version;
        public $app_path;
        public $status;
        public $created_date;
        public $updated_date;
        
        
	public function exchangeArray($data)
	{
            $this->id               = (isset($data['id']))  ? $data['id'] 	: null; 
            $this->app_id           = (isset($data['app_id']))  ? $data['app_id']  : null; 
            $this->app_name         = (isset($data['app_name']))	? $data['app_name']	: null;	
            $this->updated_version  = (isset($data['updated_version']))  ? $data['updated_version']	: null;	
            $this->app_path         = (isset($data['app_path']))  ? $data['app_path']	: null;
            $this->status           = (isset($data['status']))  ? $data['status']	: null;
            $this->created_date    = (isset($data['created_date']))  ? $data['created_date']			: null;
            $this->updated_date    = (isset($data['updated_date']))  ? $data['updated_date']			: null;
      
	}
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}
