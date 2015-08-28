<?php

namespace Assessment\Model;

class Inviteexternalemail {

    public $id;
    public $invitation_from_id;
    public $invited_email;
    public $invitation_for;
    public $board_id;
    public $class_id;
    public $subject_id;
    public $status;
    public $invite_created_date;
    public $invite_modified_date;
    
    public function exchangeArray($data) {
        $this->id                       = (isset($data['id'])) ? $data['id'] : null;
        $this->invitation_from_id       = (isset($data['invitation_from_id'])) ? $data['invitation_from_id'] : null;
        $this->invited_email            = (isset($data['invited_email'])) ? $data['invited_email'] : null;
        $this->invitation_for           = (isset($data['invitation_for'])) ? $data['invitation_for'] : null;
        $this->board_id                 = (isset($data['board_id'])) ? $data['board_id'] : null;
        $this->class_id                 = (isset($data['class_id'])) ? $data['class_id'] : null;
        $this->subject_id               = (isset($data['subject_id'])) ? $data['subject_id'] : null;
        $this->status                   = (isset($data['status'])) ? $data['status'] : null;
        $this->invite_created_date      = (isset($data['invite_created_date'])) ? $data['invite_created_date'] : null;
        $this->invite_modified_date     = (isset($data['invite_modified_date'])) ? $data['invite_modified_date'] : null;
        
    }
    
    // Add the following method:
    public function getArrayCopy() {
        return get_object_vars($this);
    }

}