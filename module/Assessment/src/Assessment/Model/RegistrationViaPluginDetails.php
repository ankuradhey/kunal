<?php
namespace Assessment\Model;

class RegistrationViaPluginDetails
{
	public $id;
	public $user_id;
	public $source;
	public $referer;
	public $current_page;
        public $creation_date;
        public $updation_date;
    
        
	public function exchangeArray($data)
	{
            $this->id                           = (isset($data['id']))  ? $data['id'] 	: null; 
            $this->user_id                      = (isset($data['user_id']))  ? $data['user_id']  : null; 
            $this->source                       = (isset($data['source']))	? $data['source']	: null;	
            $this->referer                      = (isset($data['referer']))	? $data['referer']	: null;	
            $this->current_page                 = (isset($data['current_page']))	? $data['current_page']	: null;	
            $this->creation_date                = (isset($data['creation_date']))  ? $data['creation_date']			: null;
            $this->updation_date                = (isset($data['updation_date']))  ? $data['updation_date']			: null;

	}
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}
