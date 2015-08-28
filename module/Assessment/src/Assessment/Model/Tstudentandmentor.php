<?php

namespace Assessment\Model;

class Tstudentandmentor {

    public $id;
    public $student_id;
    public $mentor_id;
    public $status;
    public $display_name;
    public $mobile;
    public $email_id;
    public $name;
    public $subject_name;
    public $board_name;
    public $class_name;
    public $board_id;
    public $class_id;
    public $subject_id;
    public $user_type;
    public $stud_mentor_id;
    public $class_ids;
    public $sub_id;
    public $board_ids;
    public $joining_date;
    public $user_id;
    public $custom_board_rack_id;

    public function exchangeArray($data) {
        $this->id               = (isset($data['id'])) ? $data['id'] : null;
        $this->student_id       = (isset($data['student_id'])) ? $data['student_id'] : null;
        $this->mentor_id        = (isset($data['mentor_id'])) ? $data['mentor_id'] : null;
        $this->status           = (isset($data['status'])) ? $data['status'] : null;
        $this->request_initiator= (isset($data['request_initiator'])) ? $data['request_initiator'] : null;
        $this->name             = (isset($data['display_name'])) ? $data['display_name'] : null;
        $this->mobile           = (isset($data['phone'])) ? $data['phone'] : null;
        $this->email_id         = (isset($data['email'])) ? $data['email'] : null;
        $this->subject_name     = (isset($data['subject_name'])) ? $data['subject_name'] : null;
        $this->board_name       = (isset($data['board_name'])) ? $data['board_name'] : null;
        $this->class_name       = (isset($data['class_name'])) ? $data['class_name'] : null;
        $this->board_id         = (isset($data['board_id'])) ? $data['board_id'] : null;
        $this->class_id         = (isset($data['class_id'])) ? $data['class_id'] : null;
        $this->subject_id       = (isset($data['subject_id'])) ? $data['subject_id'] : null;
        $this->user_type        = (isset($data['user_type'])) ? $data['user_type'] : null;
        $this->stud_mentor_id   = (isset($data['stud_mentor_id'])) ? $data['stud_mentor_id'] : null;
        $this->class_ids        = (isset($data['class_ids'])) ? $data['class_ids'] : null;
        $this->sub_id           = (isset($data['sub_id'])) ? $data['sub_id'] : null;
        $this->board_ids        = (isset($data['board_ids'])) ? $data['board_ids'] : null;
        $this->joining_date     = (isset($data['joining_date'])) ? $data['joining_date'] : null;
        $this->user_id          = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->custom_board_rack_id = (isset($data['custom_board_rack_id'])) ? $data['custom_board_rack_id'] : null;
    }
    
    // Add the following method:
    public function getArrayCopy() {
        return get_object_vars($this);
    }

}