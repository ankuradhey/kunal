<?php
namespace Assessment\Model;

/**
 *
 * @author extramarks
 *        
 */
class MentorQuestion
{
    // TODO - Insert your code here
    
    /**
     */
        //public $mentorQuestionId;
        public $mentorQuestionId;
        public $mentorQuestion;
        public $mentorQuestionMarks;
        
        public function exchangeArray($data)
        { 
            $this->mentorQuestion = (isset($data['mentor_question']))?$data['mentor_question']:null; 
            $this->mentorQuestionMarks = (isset($data['mentor_question_marks']))?$data['mentor_question_marks']:null; 
	}
}

