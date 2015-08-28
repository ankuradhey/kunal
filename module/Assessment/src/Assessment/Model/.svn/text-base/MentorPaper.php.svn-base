<?php
namespace Assessment\Model;

/**
 *
 * @author extramarks
 *        
 */
class MentorPaper
{
    // TODO - Insert your code here
    
    /**
     */
        public $paperId;
        public $paperName;
        public $mentorId;
        public $paperMarks;
        public $className;
        public $subjectName;
        public $mentorPaperQuestionId;
        public $question;
        public $questionMarks;
        public $answer;
        public $paperuseransId;
        public $paperQuesMarks;
        public $mentorPaperClass;
        public $paperQuestionComment;
        public $paperUploadFileType;
        public $paperUploadFileName;
        public $customBoardRackId;
        public function exchangeArray($data)
        { 
//            echo '<pre>';print_r($data);echo '</pre>';die('Macro Die');
              $this->paperId = (isset($data['mentor_paper_id']))?$data['mentor_paper_id']:null;
              $this->paperName = (isset($data['mentor_paper_name']))?$data['mentor_paper_name']:null;
              $this->mentorId = (isset($data['mentor_paper_added_by']))?$data['mentor_paper_added_by']:null;
              $this->paperMarks = (isset($data['mentor_paper_marks']))?$data['mentor_paper_marks']:null;
              $this->className = (isset($data['class_name']))?$data['class_name']:null;
              $this->subjectName = (isset($data['subject_name']))?$data['subject_name']:null;
              $this->mentorPaperQuestionId = (isset($data['mentor_paper_question_id']))?$data['mentor_paper_question_id']:null;
              $this->questionId = (isset($data['mentor_question_id']))?$data['mentor_question_id']:isset($data['question_id'])?$data['question_id']:null;
              $this->question = (isset($data['mentor_question']))?$data['mentor_question']:isset($data['question'])? $data['question']: null;
              $this->questionMarks = (isset($data['mentor_question_marks']))?$data['mentor_question_marks']:null;
              $this->answer = (isset($data['mentor_paper_user_ans']))?$data['mentor_paper_user_ans']:null;
              $this->paperuseransId = (isset($data['mentor_paper_user_ans_id']))?$data['mentor_paper_user_ans_id']:null;
              $this->paperQuesMarks = (isset($data['mentor_paper_ques_marks']))?$data['mentor_paper_ques_marks']:null;
              $this->paperQuestionComment = (isset($data['mentor_paper_question_comment']))?$data['mentor_paper_question_comment']:null;
              $this->paperUploadFileType = (isset($data['paper_upload_file_type']))?$data['paper_upload_file_type']:null;
              $this->paperUploadFileName = (isset($data['paper_upload_file_name']))?$data['paper_upload_file_name']:null;
              $this->paperUploadFileMarks = (isset($data['paper_upload_file_marks']))?$data['paper_upload_file_marks']:null;
              $this->mentorPaperClass = (isset($data['mentor_paper_class']))?$data['mentor_paper_class']:null;
              $this->customBoardRackId = (isset($data['custom_board_rack_id']))?$data['custom_board_rack_id']:null;
              
	}
}

