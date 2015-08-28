<?php

namespace Notification\Model;

class Notification {

    public $notification_id;
    public $notification_name;
    public $notification_text;
    public $userid;
    public $type_id;
    public $notification_url;
    public $notification_status;
    public $created_by;
    public $created_date;
    public $modified_by;
    public $modified_date;
    public $notification_type_id;
    public $notification_type_name;
    public $notification_expiry_date;
    public $notification_expiry_detail;
    public $notification_uuid;
    public $notification_mail;
    public $notify_appear_type;
    public $notification_based_on;
    public $email;
    public $display_name;
    public $mobile;
    public $notification_activity_type;
    public $notification_start_date;
    public $notification_end_date;
    public $seen;
    public $notification_occurrence_type;
    public $notification_occurrence_no;
    public $notification_recurrence_on;

    public function exchangeArray($data) {
        $this->notification_id = (isset($data['notification_id'])) ? $data['notification_id'] : null;
        $this->notification_name = (isset($data['notification_name'])) ? $data['notification_name'] : null;
        $this->notification_text = (isset($data['notification_text'])) ? $data['notification_text'] : null;
        $this->userid = (isset($data['userid'])) ? $data['userid'] : null;
        $this->type_id = (isset($data['type_id'])) ? $data['type_id'] : null;
        $this->notification_url = (isset($data['notification_url'])) ? $data['notification_url'] : null;
        $this->notification_status = (isset($data['notification_status'])) ? $data['notification_status'] : null;
        $this->created_by = (isset($data['created_by'])) ? $data['created_by'] : null;
        $this->created_date = (isset($data['created_date'])) ? $data['created_date'] : null;
        $this->modified_by = (isset($data['modified_by'])) ? $data['modified_by'] : null;
        $this->modified_date = (isset($data['modified_date'])) ? $data['modified_date'] : null;
        $this->notification_uuid=(isset($data['notification_uuid']))? $data['notification_uuid']:null;
        $this->notification_mail=(isset($data['notification_mail']))? $data['notification_mail']:null;
        $this->notify_appear_type=(isset($data['notify_appear_type']))? $data['notify_appear_type']:null;
        $this->email=(isset($data['email']))? $data['email']:null;
        $this->display_name=(isset($data['display_name']))? $data['display_name']:null;
        $this->mobile=(isset($data['mobile']))? $data['mobile']:null;
        $this->notification_based_on=(isset($data['notification_based_on']))? $data['notification_based_on']:null;
        $this->notification_start_date=(isset($data['notification_start_date']))? $data['notification_start_date']:null;
        $this->notification_end_date=(isset($data['notification_end_date']))? $data['notification_end_date']:null;
        $this->notification_activity_type=(isset($data['notification_activity_type']))? $data['notification_activity_type']:null;
        $this->seen=(isset($data['seen']))? $data['seen']:null;
        $this->notification_occurrence_type=(isset($data['notification_occurrence_type']))? $data['notification_occurrence_type']:null;
        $this->notification_occurrence_no=(isset($data['notification_occurrence_no']))? $data['notification_occurrence_no']:null;
        $this->notification_recurrence_on=(isset($data['notification_recurrence_on']))? $data['notification_recurrence_on']:null;
    }

    // Add the following method:
    public function getArrayCopy() {
        return get_object_vars($this);
    }

}