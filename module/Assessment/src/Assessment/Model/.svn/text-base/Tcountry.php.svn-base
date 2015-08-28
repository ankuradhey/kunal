<?php
namespace Assessment\Model;

class Tcountry
{
	public $country_id;
	public $country_name;
	public $iso;
	public $nicename;
	public $iso3;
	public $numcode;
	public $phonecode;
	public $status;
	
	public function exchangeArray($data)
	{
		$this->country_id = (isset($data['country_id'])) ? $data['country_id'] : null;
                $this->country_name = (isset($data['country_name'])) ? $data['country_name'] 			: null; 
		$this->iso = (isset($data['iso'])) ? $data['iso'] : null;	
                $this->nicename = (isset($data['nicename'])) ? $data['nicename'] : null;	
		$this->iso3 = (isset($data['iso3']))  	? $data['iso3']	: null;	
		$this->numcode = (isset($data['numcode'])) ? $data['numcode'] : null;
		$this->phonecode = (isset($data['phonecode'])) ? $data['phonecode'] : null;
		$this->status = (isset($data['status'])) ? $data['status'] : null;			
        }
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}