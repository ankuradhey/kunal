<?php

namespace Assessment\Model;

class UserOtherDetails
{
        
    /**
     */ public $id;
        public $user_id;
        public $key_name;     
        public $value;
        public $created_date;
        
        public function exchangeArray($data)
        { 
                $this->id           = (isset($data['id'])) ? $data['id'] : null;
                $this->user_id      = (isset($data['user_id'])) ? $data['user_id'] : null;
                $this->key_name     = (isset($data['key_name'])) ? $data['key_name'] : null;
		$this->value        = (isset($data['value'])) ? $data['value'] : null;
		$this->created_date = (isset($data['created_date'])) ? $data['created_date'] : null;
        }
        
         public function getArrayCopy()
        {
         return get_object_vars($this);
        }
        
}
?>
