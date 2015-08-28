<?php

namespace Assessment\Model;

class UserTracker
{
        
    /**
     */ public $id;
        public $user_id;
        public $session;     
        public $page_url;
        public $page_group;
        public $created_date;
        
        public function exchangeArray($data)
        { 
                $this->id           = (isset($data['id'])) ? $data['id'] : null;
                $this->user_id      = (isset($data['user_id'])) ? $data['user_id'] : null;
                $this->session      = (isset($data['session'])) ? $data['session'] : null;
		$this->page_url     = (isset($data['page_url'])) ? $data['page_url'] : null;
		$this->page_group   = (isset($data['page_group'])) ? $data['page_group'] : null;
                $this->created_date = (isset($data['created_date'])) ? $data['created_date'] : null;
        }
        
         public function getArrayCopy()
        {
         return get_object_vars($this);
        }
        
}
?>
