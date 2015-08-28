<?php
namespace Assessment\Model;

/**
 *
 * @author extramarks
 *        
 */
class MentorAssignPaper
{
    // TODO - Insert your code here
    
    /**
     */
        public $paperAssignId;
        public $paperId;
        public   $paperAssignedBy;
        public  $paperEvaluateStatus ;
        public   $paperEvaluateDate ;
        public   $paperEvaluatedScore;
        public   $paperEvaluatedComment ;
        public   $paperAssignedTo ;
         public  $paperAttemptStatus ;
        public   $paperAttemptDate ;
         public  $paperName ;
         public  $paperMarks ;
        public   $paperSubject ;
         public  $paperClass ;
         public  $userFirstName;
          public $userLastName ;
         public  $userEmailId ;
         public $paperSource;
         public $paperInstruction;
         public $paperTime;
         public $mentor_name;
         public $student_name;
         public $paperClassId;
         public $paperSubjectId;
        public function exchangeArray($data)
        { 
//        echo '<pre>';print_r($data);echo '</pre>';die('Macro Die');
           $this->paperAssignId = (isset($data['mentor_paper_assign_id']))?$data['mentor_paper_assign_id']:null;
           $this->paperId = (isset($data['mentor_paper_id']))?$data['mentor_paper_id']:null;
           $this->paperAssignedBy = (isset($data['mentor_paper_assigned_by']))?$data['mentor_paper_assigned_by']:null;
           $this->paperEvaluateStatus = (isset($data['mentor_paper_evaluate_status']))?$data['mentor_paper_evaluate_status']:null;
           $this->paperEvaluateDate = (isset($data['mentor_paper_evaluate_date']))?$data['mentor_paper_evaluate_date']:null;
           $this->paperEvaluatedScore = (isset($data['mentor_paper_evaluated_score']))?$data['mentor_paper_evaluated_score']:null;
           $this->paperEvaluatedComment = (isset($data['mentor_paper_evaluated_comment']))?$data['mentor_paper_evaluated_comment']:null;
           $this->paperAssignedTo = (isset($data['mentor_paper_assigned_to']))?$data['mentor_paper_assigned_to']:null;
           $this->paperAttemptStatus = (isset($data['mentor_paper_attempt_status']))?$data['mentor_paper_attempt_status']:null;
           $this->paperAttemptDate = (isset($data['mentor_paper_attempt_date']))?$data['mentor_paper_attempt_date']:null;
           $this->paperName = (isset($data['mentor_paper_name']))?$data['mentor_paper_name']:null;
           $this->paperMarks = (isset($data['mentor_paper_marks']))?$data['mentor_paper_marks']:null;
           $this->paperSubject = (isset($data['paperSubject']))?$data['paperSubject']:null;
           $this->paperClass = (isset($data['paperClass']))?$data['paperClass']:null;
           $this->userFirstName = (isset($data['display_name']))?$data['display_name']:null;
           $this->userLastName = (isset($data['last_name']))?$data['last_name']:null;
           $this->userEmailId = (isset($data['email_id']))?$data['email_id']:null;
           $this->paperSource = (isset($data['mentor_paper_question_source']))?$data['mentor_paper_question_source']:null;
           $this->paperInstruction = (isset($data['mentor_paper_instruction']))?$data['mentor_paper_instruction']:null;
           $this->paperTime = (isset($data['mentor_paper_time']))?$data['mentor_paper_time']:null;
           $this->paperClassId = (isset($data['mentor_paper_class']))?$data['mentor_paper_class']:null;
           $this->mentor_name = (isset($data['mentor_name']))?$data['mentor_name']:null;
           $this->student_name = (isset($data['student_name']))?$data['student_name']:null;
           $this->paperSubjectId = (isset($data['mentor_paper_subject']))?$data['mentor_paper_subject']:null;
	}
}

