<?php
namespace Notification\Model;

class Tnotes
{	
	public $note_id;
	public $user_id;
	public $container_id;
	public $chapter_id;
	public $note;
	public $added_date;
	public $updated_date;
	public $status;
	public $name;
	public $pdf_file_name;
        public $up_down_id;
        public $file_name;
        public $customBoardRackId;
	public function exchangeArray($data)
	{
            $this->note_id     	 = (isset($data['note_id']))          ? $data['note_id']     	: null;
            $this->user_id     	 = (isset($data['user_id']))          ? $data['user_id']     	: null;
            $this->container_id  = (isset($data['container_id']))     ? $data['container_id'] : null;
            $this->chapter_id    = (isset($data['chapter_id']))       ? $data['chapter_id'] : null;
            $this->note     	 = (isset($data['note']))     	      ? $data['note']     	: null;
            $this->added_date    = (isset($data['added_date']))       ? $data['added_date']   : null;
            $this->updated_date  = (isset($data['updated_date']))     ? $data['updated_date'] : null;
            $this->status   	 = (isset($data['status'])) 	      ? $data['status'] 	    : null;
            $this->name   	 = (isset($data['name']))             ? $data['name'] 	    : null;
            $this->notesCount    = (isset($data['notesCount']))       ? $data['notesCount'] 	: null;
            $this->chapter_name  = (isset($data['chapter_name']))     ? $data['chapter_name'] 	: null;
            $this->pdf_file_name = (isset($data['pdf_file_name']))    ? $data['pdf_file_name'] 	: null;
            $this->up_down_id    = (isset($data['up_down_id']))       ? $data['up_down_id'] 	: null;
            $this->file_name    = (isset($data['file_name'])) 	      ? $data['file_name'] 	: null;
            $this->customBoardRackId    = (isset($data['custom_board_rack_id'])) ? $data['custom_board_rack_id']: null;
	}
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

}