<?php
namespace Assessment\Model;

class Tuploadsdownloads
{
    public $up_down_id;
    public $student_id;
    public $mentor_id;
    public $subject_id;
    public $chapter_id;
    public $pdf_file_name;
    public $type_downloaded;
    public $download_for;
    public $posted_date;
    public $status;
    public $chepter_name;
    public $subject_name;
    public $class_name;
    public $class_id;
    public $download_relation_id;
    public $file_name;

    public function exchangeArray($data)
    {
   // echo '<pre>';print_r($data);echo '</pre>';die('Macro');    
        $this->up_down_id = (isset($data['up_down_id'])) ? $data['up_down_id'] : null;
        $this->student_id = (isset($data['student_id'])) ? $data['student_id'] : null;
        $this->mentor_id = (isset($data['mentor_id'])) ? $data['mentor_id'] : null;
        $this->subject_id = (isset($data['subject_id'])) ? $data['subject_id'] : null;
        $this->chapter_id = (isset($data['chapter_id'])) ? $data['chapter_id'] : null;
        $this->pdf_file_name = (isset($data['pdf_file_name'])) ? $data['pdf_file_name'] : null;
        $this->type_downloaded = (isset($data['type_downloaded'])) ? $data['type_downloaded'] : null;
        $this->posted_date = (isset($data['posted_date'])) ? $data['posted_date'] : null;
        $this->download_for = (isset($data['download_for'])) ? $data['download_for'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->chepter_name = (isset($data['chepter_name'])) ? $data['chepter_name'] : null;
        $this->subject_name = (isset($data['subject_name'])) ? $data['subject_name'] : null;
        $this->class_name = (isset($data['class_name'])) ? $data['class_name'] : null;
        $this->class_id = (isset($data['class_id'])) ? $data['class_id'] : null;
        $this->download_relation_id = (isset($data['download_relation_id'])) ? $data['download_relation_id'] : null;
        $this->file_name = (isset($data['file_name'])) ? $data['file_name'] : null;
    }

	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}