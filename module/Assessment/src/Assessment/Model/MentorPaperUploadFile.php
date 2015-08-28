<?php
namespace Assessment\Model;

/**
 *
 * @author extramarks
 *        
 */
class MentorPaperUploadFile
{
    // TODO - Insert your code here
    
    /**
     */
        public $id;
        public $name;
        public $questionid;
        public $question;
        public $question_marks;
        public function exchangeArray($data)
        {
                $this->id = (isset($data['board_class_subject_chapter_id'])) ? $data['board_class_subject_chapter_id'] : null;
                $this->name = (isset($data['chapter_name'])) ? $data['chapter_name'] : null;
                $this->questionid = (isset($data['question_id']))?$data['question_id']:null;
                $this->question = (isset($data['question']))?$data['question']:null;
                $this->question_marks = (isset($data['meta_marks']))?$data['meta_marks']:null;
        }
}

