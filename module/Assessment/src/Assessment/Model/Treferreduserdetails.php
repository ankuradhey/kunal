<?php

namespace Assessment\Model;

/**
 *
 * @author extramarks
 *        
 */
class Treferreduserdetails {
    // TODO - Insert your code here

    /**
     */ 
    public $id;
    public $unique_reference_id;
    public $referred_by_user_id;
    public $referred_to_user_id;
    public $name;
    public $email;
    public $mobile;
    public $school_name;
    public $message;
    public $creation_date;
    public $updation_date;
  

    public function exchangeArray($data) {

        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->unique_reference_id = (isset($data['unique_reference_id'])) ? $data['unique_reference_id'] : null;
        $this->referred_by_user_id = (isset($data['referred_by_user_id'])) ? $data['referred_by_user_id'] : null;
        $this->referred_to_user_id = (isset($data['referred_to_user_id'])) ? $data['referred_to_user_id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->mobile = (isset($data['mobile'])) ? $data['mobile'] : null;
        $this->school_name = (isset($data['school_name'])) ? $data['school_name'] : null;
        $this->message = (isset($data['message'])) ? $data['message'] : null;
        $this->creation_date = (isset($data['creation_date'])) ? $data['creation_date'] : null;
        $this->updation_date = (isset($data['updation_date'])) ? $data['updation_date'] : null;
    }

    // Add the following method:
    public function getArrayCopy() {
        return get_object_vars($this);
    }

}

