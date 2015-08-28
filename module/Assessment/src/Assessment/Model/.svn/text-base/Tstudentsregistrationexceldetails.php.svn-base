<?php
namespace Assessment\Model;

class Tstudentsregistrationexceldetails
{
	public $id;
	public $filename;
	public $uploaded_by;
	public $uploaded_for;
	public $total_records;
	public $correct_uploads;
	public $error_count;
        public $creation_date;
        public $updation_date;
        public $user_id;
        public $uploader_email;
        
        
	public function exchangeArray($data)
	{
            $this->id                           = (isset($data['id']))  ? $data['id'] 	: null; 
            $this->filename                     = (isset($data['filename']))  ? $data['filename'] 	: null; 
            $this->uploaded_by                  = (isset($data['uploaded_by']))  ? $data['uploaded_by']  : null; 
            $this->uploaded_for                  = (isset($data['uploaded_for']))  ? $data['uploaded_for']  : null; 
            $this->total_records                = (isset($data['total_records']))	? $data['total_records']	: null;	
            $this->correct_uploads              = (isset($data['correct_uploads']))	? $data['correct_uploads']	: null;	
            $this->error_count                  = (isset($data['error_count']))	? $data['error_count']	: null;	
            $this->creation_date               = (isset($data['creation_date']))  ? $data['creation_date']			: null;
            $this->updation_date                 = (isset($data['updation_date']))  ? $data['updation_date']			: null;
            $this->user_id                      = (isset($data['user_id']))  ? $data['user_id']			: null;
            $this->uploader_email               = (isset($data['uploader_email']))  ? $data['uploader_email']			: null;
      
	}
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}
