<?php
namespace EmIdol\Model;

class EmIdolQuizSetQues
{	
	public $quiz_set_ques_id;
        public $set_id;
        public $question_id;
        
        public function exchangeArray($data)
        {
            $this->quiz_set_ques_id = (isset($data['quiz_set_ques_id'])) ? $data['quiz_set_ques_id'] : null;
            $this->set_id = (isset($data['set_id'])) ? $data['set_id'] : null;
            $this->question_id = (isset($data['question_id'])) ? $data['question_id'] : null;
        }
        
        public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}
