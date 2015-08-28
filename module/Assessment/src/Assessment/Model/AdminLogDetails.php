<?php

namespace Assessment\Model;

class AdminLogDetails
{
        
    /**
     */ public $id;
        public $login_id;
        public $key_name;     
        public $modified_table;     
        public $modified_primary_id;     
        public $value;
        public $created_date;
        
        public function exchangeArray($data)
        { 
                $this->id           = (isset($data['id'])) ? $data['id'] : null;
                $this->user_id      = (isset($data['login_id'])) ? $data['login_id'] : null;
                $this->key_name     = (isset($data['key_name'])) ? $data['key_name'] : null;
                $this->modified_table     = (isset($data['modified_table'])) ? $data['modified_table'] : null;
                $this->modified_primary_id     = (isset($data['modified_primary_id'])) ? $data['modified_primary_id'] : null;
		$this->value        = (isset($data['value'])) ? $data['value'] : null;
		$this->created_date = (isset($data['created_date'])) ? $data['created_date'] : null;
        }
        
         public function getArrayCopy()
        {
         return get_object_vars($this);
        }
        
}
?>
