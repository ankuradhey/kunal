<?php
namespace Assessment\Model;

class Terpusermapped
{
	public $id;
	public $user_id;
	public $source;
	public $uniquekey;
	public $type;
	public $status;
        public $created_date;
        public $updated_date;
        
	public function exchangeArray($data)
	{
            $this->id                           = (isset($data['id']))  ? $data['id'] 	: null; 
            $this->user_id                      = (isset($data['user_id']))  ? $data['user_id']  : null; 
            $this->source                       = (isset($data['source']))  ? $data['source']  : null; 
            $this->uniquekey                    = (isset($data['uniquekey']))  ? $data['uniquekey']  : null; 
            $this->type                         = (isset($data['type']))  ? $data['type']  : null; 
            $this->status                       = (isset($data['status']))	? $data['status']	: null;	
            $this->created_date                 = (isset($data['created_date']))  ? $data['created_date']			: null;
            $this->updated_date                 = (isset($data['updated_date']))  ? $data['updated_date']			: null;
      
	}
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}
