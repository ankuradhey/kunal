<?php

namespace Notification\Model;

class Treplyonquestion {

    public $reply_id;
    public $reply_on_question;
    public $user_id;
    public $reply_message;
    public $reply_date;
    public $reply_status;
    public $first_name;
    public $name;
    public $board_name;
    public $class_name;
    public $school_name;
    public $user_photo;
    public $file_names;
    //public $countLikes;
    public $userLike;
    public $subject_name;
    public $chapter_name;
    public $deleted_user;
    public $userid;
    public $email_id;
    public $address;
    public $mobile;
    public $up_down_id;
    public $pdf_file_name;
    public $file_name;
    
  public function exchangeArray($data) {
        $this->reply_id = (isset($data['reply_id'])) ? $data['reply_id'] : null;
        $this->reply_on_question = (isset($data['reply_on_question'])) ? $data['reply_on_question'] : null;
        $this->user_id = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->reply_message = (isset($data['reply_message'])) ? $data['reply_message'] : null;
        $this->reply_date = (isset($data['reply_date'])) ? $data['reply_date'] : null;
        $this->reply_status = (isset($data['reply_status'])) ? $data['reply_status'] : null;
        $this->first_name = (isset($data['first_name'])) ? $data['first_name'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->board_name = (isset($data['board_name'])) ? $data['board_name'] : null;
        $this->class_name = (isset($data['class_name'])) ? $data['class_name'] : null;
        $this->school_name = (isset($data['school_name'])) ? $data['school_name'] : null;
        $this->user_photo = (isset($data['user_photo'])) ? $data['user_photo'] : null;
        $this->file_names       = (isset($data['file_names']))      ? $data['file_names'] 	 : null;	
        $this->userLike = (isset($data['userLike'])) ? $data['userLike'] : null;
        $this->subject_name = (isset($data['subject_name'])) ? $data['subject_name'] : null;
        $this->chapter_name = (isset($data['chapter_name'])) ? $data['chapter_name'] : null;
        $this->deleted_user = (isset($data['deleted_user'])) ? $data['deleted_user'] : null;
        $this->userid = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->email_id = (isset($data['email_id'])) ? $data['email_id'] : null;
        $this->address = (isset($data['address'])) ? $data['address'] : null;
        $this->mobile = (isset($data['mobile'])) ? $data['mobile'] : null;
        $this->up_down_id = (isset($data['up_down_id'])) ? $data['up_down_id'] : null;
        $this->pdf_file_name = (isset($data['pdf_file_name'])) ? $data['pdf_file_name'] : null;
        $this->file_name    = (isset($data['file_name'])) 	      ? $data['file_name'] 	: null;
    }

    // Add the following method:
    public function getArrayCopy() {
        return get_object_vars($this);
    }

}