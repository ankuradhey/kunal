<?php
namespace Assessment\Model;

/**
 *
 * @author extramarks
 *        
 */
class Question
{
    // TODO - Insert your code here
    
    /**
     */
        public $boardClassSubjectId;
        public $name;
        public $questionid;
        public $question;
        public $question_marks;
        public function exchangeArray($data)
        {
//            echo '<pre>';print_r($data);echo '</pre>';die('Macro Die');
                $this->boardClassSubjectId = (isset($data['container_id'])) ? $data['container_id'] : null;
                $this->name = (isset($data['chapter_name'])) ? $data['chapter_name'] : null;
                $this->questionid = (isset($data['question_id']))?$data['question_id']:null;
                $this->question = (isset($data['question']))?$data['question']:null;
                $this->question_marks = (isset($data['meta_marks']))?$data['meta_marks']:null;
        }
}

