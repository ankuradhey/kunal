<?php
namespace EmIdol\Model;

class EmIdolQuizSet
{	
	public $set_id;
        public $userid;
        public $round;
        public $level;
        public $test_ans_auto_id;
        public $quiz_set_chapter_ques_id;
        public $ans_status;
        public $user_ans;
        public $user_ans_index;
        public $answer_order;
        public $question_id;
        public $ques_exp;
        public $question;
        public $answer;
        public $set_time;
        public $set_date_added;
        public $quiz_set_chapter_id;
        public $test_stu;
        public $knowledge_and_understanding;
        public $skill_and_application;
        public $rack_id;
        public $rack_name;
        public $user_time_taken;
        
        public function exchangeArray($data)
        {
            $this->set_id = (isset($data['set_id'])) ? $data['set_id'] : null;
            $this->userid = (isset($data['user_id'])) ? $data['user_id'] : null;
            $this->round = (isset($data['level_id'])) ? $data['round'] : null;
            $this->level = (isset($data['level'])) ? $data['level'] : null;
            $this->setstatus = (isset($data['set_status'])) ? $data['set_status'] : null;
            $this->test_ans_auto_id = (isset($data['test_ans_auto_id'])) ? $data['test_ans_auto_id'] : null;
            $this->quiz_set_chapter_ques_id = (isset($data['quiz_set_chapter_ques_id'])) ? $data['quiz_set_chapter_ques_id'] : null;
            $this->ans_status = (isset($data['ans_status'])) ? $data['ans_status'] : null;
            $this->user_ans = (isset($data['user_ans'])) ? $data['user_ans'] : null;
            $this->user_ans_index = (isset($data['user_ans_index'])) ? $data['user_ans_index'] : null;
            $this->answer_order = (isset($data['answer_order'])) ? $data['answer_order'] : null;
            $this->question_id = (isset($data['question_id'])) ? $data['question_id'] : null;
            $this->ques_exp = (isset($data['question_teacher_desc'])) ? $data['question_teacher_desc'] : null;
            $this->question = (isset($data['question'])) ? $data['question'] : null;
            $this->answer = (isset($data['answer'])) ? $data['answer'] : null;
            $this->set_time = (isset($data['set_time'])) ? $data['set_time'] : null;
            $this->set_date_added = (isset($data['set_date_added'])) ? $data['set_date_added'] : null;
            $this->quiz_set_chapter_id = (isset($data['quiz_set_chapter_id'])) ? $data['quiz_set_chapter_id'] : null;
            $this->test_stu = (isset($data['test_stu'])) ? $data['test_stu'] : null;
            $this->knowledge_and_understanding = (isset($data['knowledge_and_understanding'])) ? $data['knowledge_and_understanding'] : '0';
            $this->skill_and_application = (isset($data['skill_and_application'])) ? $data['skill_and_application'] : '0';
            $this->rack_id = (isset($data['rack_id'])) ? $data['rack_id'] : '0';
            $this->rack_name = (isset($data['name'])) ? $data['name'] : null;
            $this->user_time_taken = (isset($data['user_time_taken'])) ? $data['user_time_taken'] : null;
        }
        
        public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}