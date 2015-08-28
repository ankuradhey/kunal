<?php

namespace Assessment\Model;

class MetaTags
{
        
    /**
     */ public $meta_id;
        public $page_url;     
        public $meta_tag;
        public $meta_keyword;
        public $meta_description;       
        public $status;
        public $created_by;
        public $modify_by;
        public $created_date;
        public $modify_date;
        
        public function exchangeArray($data)
        { 
                $this->meta_id           = (isset($data['meta_id'])) ? $data['meta_id'] : null;
                $this->page_url          = (isset($data['page_url'])) ? $data['page_url'] : null;
                $this->meta_tag        = (isset($data['meta_tag'])) ? $data['meta_tag'] : null;
		$this->meta_keyword         = (isset($data['meta_keyword'])) ? $data['meta_keyword'] : null;
                $this->meta_description         = (isset($data['meta_description'])) ? $data['meta_description'] : null;              
                $this->status         = (isset($data['status'])) ? $data['status'] : null;                
                $this->created_by         = (isset($data['created_by'])) ? $data['created_by'] : null;
                $this->modify_by         = (isset($data['modify_by'])) ? $data['modify_by'] : null;
                $this->created_date         = (isset($data['created_date'])) ? $data['created_date'] : null;
                $this->modify_date         = (isset($data['modify_date'])) ? $data['modify_date'] : null;
               
        }
        
         public function getArrayCopy()
        {
         return get_object_vars($this);
        }
        
}
?>
