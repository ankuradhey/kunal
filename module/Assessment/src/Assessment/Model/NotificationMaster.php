<?php

namespace Assessment\Model;

class NotificationMaster
{
        
    /**
     */ public $notification_type_id;
        public $notification_type_name;     
        public $notification_expiry_hour;
        public $notification_expiry_detail;
        public $status;
        public $created_by;
        public $created_date;
        
        public function exchangeArray($data)
        { 
                $this->notification_type_id           = (isset($data['notification_type_id'])) ? $data['notification_type_id'] : null;
                $this->notification_type_name          = (isset($data['notification_type_name'])) ? $data['notification_type_name'] : null;
                $this->notification_expiry_hour        = (isset($data['notification_expiry_hour'])) ? $data['notification_expiry_hour'] : null;
		$this->notification_expiry_detail         = (isset($data['notification_expiry_detail'])) ? $data['notification_expiry_detail'] : null;
                $this->meta_description         = (isset($data['meta_description'])) ? $data['meta_description'] : null;              
                $this->status         = (isset($data['status'])) ? $data['status'] : null;                
                $this->created_by         = (isset($data['created_by'])) ? $data['created_by'] : null;
                $this->created_date         = (isset($data['created_date'])) ? $data['created_date'] : null;
               
        }
        
         public function getArrayCopy()
        {
         return get_object_vars($this);
        }
        
}
?>
