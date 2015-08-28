<?php

namespace Assessment\Model;

class Tparentandchild {

    public $id;
    public $parent_id;
    public $child_id;
    public $requested_by;
    public $status;
    public $userid;
    public $first_name;
    public $mobile;
    public $email_id;
    public $name;
    public $subject_name;
    public $board_name;
    public $class_name;
    public $board_id;
    public $board_class_id;
    public $subject_id;
    public $board_class_parent_subject_id;
    public $parent_subject_name;
    public $board_class_subject_id;

    public function exchangeArray($data) {
//        echo '<pre>';print_r($data);echo '</pre>';die('Macro Die');
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->parent_id = (isset($data['parent_id'])) ? $data['parent_id'] : null;
        $this->child_id = (isset($data['child_id'])) ? $data['child_id'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->userid = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->requested_by = (isset($data['requested_by'])) ? $data['requested_by'] : null;
        $this->first_name = (isset($data['display_name'])) ? $data['display_name'] : null;
        $this->username = (isset($data['username'])) ? $data['username'] : null;
        $this->mobile = (isset($data['mobile'])) ? $data['mobile'] : null;
        $this->email_id = (isset($data['email'])) ? $data['email'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->subject_name = (isset($data['subject_name'])) ? $data['subject_name'] : null;
        $this->board_name = (isset($data['board_name'])) ? $data['board_name'] : null;
        $this->class_name = (isset($data['class_name'])) ? $data['class_name'] : null;
        $this->board_id = (isset($data['board_id'])) ? $data['board_id'] : null;
        $this->class_id = (isset($data['class_id'])) ? $data['class_id'] : null;
        $this->board_class_parent_subject_id = (isset($data['board_class_parent_subject_id'])) ? $data['board_class_parent_subject_id'] : null;
        $this->board_class_subject_id = (isset($data['board_class_subject_id'])) ? $data['board_class_subject_id'] : null;
        $this->parent_subject_name = (isset($data['parent_subject_name'])) ? $data['parent_subject_name'] : null;
    }

    // Add the following method:
    public function getArrayCopy() {
        return get_object_vars($this);
    }

}