<?php

namespace ZfcUser\Model;

class Notificationtypemaster {

    public $notification_id;
    public $notification_text;
    public $userid;
    public $type_id;
    public $notification_url;
    public $notification_status;
    public $created_by;
    public $created_date;
    public $modified_by;
    public $modified_date;
    
    
    
    
    
    
  public  $notification_type_id;	
  public   $notification_type_name;		
  public   $notification_expiry_date;	
  public  $notification_expiry_detail;

    public function exchangeArray($data) {
        $this->notification_id = (isset($data['notification_id'])) ? $data['notification_id'] : null;
        $this->notification_text = (isset($data['notification_text'])) ? $data['notification_text'] : null;
        $this->userid = (isset($data['userid'])) ? $data['userid'] : null;
        $this->type_id = (isset($data['type_id'])) ? $data['type_id'] : null;
        $this->notification_url = (isset($data['notification_url'])) ? $data['notification_url'] : null;
        $this->notification_status = (isset($data['notification_status'])) ? $data['notification_status'] : null;
        $this->created_by = (isset($data['created_by'])) ? $data['created_by'] : null;
        $this->created_date = (isset($data['created_date'])) ? $data['created_date'] : null;
        $this->modified_by = (isset($data['modified_by'])) ? $data['modified_by'] : null;
        $this->modified_date = (isset($data['modified_date'])) ? $data['modified_date'] : null;
    }

    // Add the following method:
    public function getArrayCopy() {
        return get_object_vars($this);
    }

}