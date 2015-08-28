<?php
namespace Assessment\Model;

/**
 *
 * @author extramarks
 *        
 */
class MentorPaperQuestion
{
    // TODO - Insert your code here
    
    /**
     */
        public $mentorPaperQuestionId;
        public $mentorPaperId;
        public $questionId;
        public $mentorPaperQuestionMarks;
        public $mentorPaperQuestionsource;
        public $answer;
        public $paperuseransId;
        public $paperQuesMarks;
        public $paperQuesComment;
        public function exchangeArray($data)
        { 
             $this->mentorPaperQuestionId = (isset($data['mentor_paper_question_id']))?$data['mentor_paper_question_id']:null;  
             $this->mentorPaperId = (isset($data['mentor_paper_id']))?$data['mentor_paper_id']:null;
             $this->questionId = (isset($data['question_id']))?$data['question_id']:null;
             $this->mentorPaperQuestionMarks = (isset($data['mentor_paper_question_marks']))?$data['mentor_paper_question_marks']:null; 
             $this->mentorPaperQuestionsource = (isset($data['mentor_paper_question_source']))?$data['mentor_paper_question_source']:null; 
              $this->answer = (isset($data['mentor_paper_user_ans']))?$data['mentor_paper_user_ans']:null;
              $this->paperuseransId = (isset($data['mentor_paper_user_ans_id']))?$data['mentor_paper_user_ans_id']:null;
              $this->paperQuesMarks = (isset($data['mentor_paper_ques_marks']))?$data['mentor_paper_ques_marks']:null;
              $this->paperQuesComment = (isset($data['mentor_paper_question_comment']))?$data['mentor_paper_question_comment']:null;
	}
}

