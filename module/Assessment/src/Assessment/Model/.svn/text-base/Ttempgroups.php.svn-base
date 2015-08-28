<?php

namespace Assessment\Model;

class Ttempgroups {

    public $id;
    public $email_id;
    public $requested_by;
    public $created_by;
    public $created_date;
    public $modified_by;
    public $modified_date;
    public $status;
    public $first_name;
    public $request_type;

    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->email_id = (isset($data['email_id'])) ? $data['email_id'] : null;
        $this->requested_by = (isset($data['requested_by'])) ? $data['requested_by'] : null;
        $this->created_by = (isset($data['created_by'])) ? $data['created_by'] : null;
        $this->created_date = (isset($data['created_date'])) ? $data['created_date'] : null;
        $this->modified_by = (isset($data['modified_by'])) ? $data['modified_by'] : null;
        $this->modified_date = (isset($data['modified_date'])) ? $data['modified_date'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->first_name = (isset($data['first_name'])) ? $data['first_name']   : null;
        $this->request_type  = (isset($data['request_type'])) ? $data['request_type']   : null;
  
     }

    // Add the following method:
    public function getArrayCopy() {
        return get_object_vars($this);
    }

}