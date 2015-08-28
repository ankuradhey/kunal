<?php
namespace Assessment\Model;

class Tmentordetails
{
        public $mentor_details_id;
	public $mentor_id;
	public $board_id;
	public $class_id;
	public $qualification;
	public $subject_id;
	public $experience;
	public $added_date;
	
	public function exchangeArray($data)
	{
            $this->mentor_details_id = (isset($data['mentor_details_id'])) ? $data['mentor_details_id'] : null;
            $this->mentor_id = (isset($data['mentor_id'])) ? $data['mentor_id'] : null;
            $this->board_id = (isset($data['board_id'])) ? $data['board_id'] : null;
            $this->class_id = (isset($data['class_id'])) ? $data['class_id'] : null;
            $this->subject_id = (isset($data['subject_id'])) ? $data['subject_id'] : null;
            $this->qualification = (isset($data['qualification'])) ? $data['qualification'] : null;
            $this->experience = (isset($data['experience'])) ? $data['experience'] : null;
            $this->added_date  = (isset($data['added_date'])) ? $data['added_date'] : null;
        }
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}