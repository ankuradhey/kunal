<?php
namespace Assessment\Model;

class Tfeedbackscomments
{
    
        public $feed_comment_id;
        public $feedback_id;     
        public $student_id;
        public $comment_text;
        public $posted_date;
        public $status;
        
	public function exchangeArray($data)
	{
		$this->feedback_id           = (isset($data['feedback_id'])) ? $data['feedback_id'] : null;
                $this->feed_comment_id          = (isset($data['feed_comment_id'])) ? $data['feed_comment_id'] : null;
                $this->student_id        = (isset($data['student_id'])) ? $data['student_id'] : null;
		$this->comment_text         = (isset($data['comment_text'])) ? $data['comment_text'] : null;
                $this->	posted_date         = (isset($data['posted_date'])) ? $data['posted_date'] : null;
                $this->status         = (isset($data['status'])) ? $data['status'] : null;
        }
	// Add the following method:
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}