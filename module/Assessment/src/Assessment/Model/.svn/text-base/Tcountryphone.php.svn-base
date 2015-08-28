<?php
namespace Assessment\Model;

class Tcountryphone
{
	public $id;
	public $country_id;
	public $lower_phone_digit_limit;
        public $upper_phone_digit_limit;
	public $created_date;
	public $modified_date;
	public $Status;
	
	public function exchangeArray($data)
	{
		$this->id = (isset($data['id'])) ? $data['id'] : null;
                $this->country_id = (isset($data['country_id'])) ? $data['country_id'] : null; 
		$this->lower_phone_digit_limit = (isset($data['lower_phone_digit_limit'])) ? $data['lower_phone_digit_limit'] : null;
                $this->upper_phone_digit_limit = (isset($data['upper_phone_digit_limit'])) ? $data['upper_phone_digit_limit'] : null;
                $this->created_date = (isset($data['created_date'])) ? $data['created_date'] : null;	
		$this->modified_date = (isset($data['modified_date']))  	? $data['modified_date']	: null;	
		$this->Status = (isset($data['Status'])) ? $data['Status'] : null;			
        }
	
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}
