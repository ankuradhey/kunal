<?php

namespace Assessment\Model;

class Ticker
{
        
    /**
     */ public $ticker_id;
        public $ticker_name;     
        public $ticker_type;
        public $ticker_text;
        public $start_date;
        public $end_date;
        public $order;
        public $ticker_file;
        public $scroll_direction;
        public $status;
        public $created_by;
        public $modify_by;
        public $created_date;
        public $modify_date;
        public $ticker_redirect_url;
        
        public function exchangeArray($data)
        { 
                $this->ticker_id           = (isset($data['ticker_id'])) ? $data['ticker_id'] : null;
                $this->ticker_name          = (isset($data['ticker_name'])) ? $data['ticker_name'] : null;
                $this->ticker_type        = (isset($data['ticker_type'])) ? $data['ticker_type'] : null;
		$this->ticker_text         = (isset($data['ticker_text'])) ? $data['ticker_text'] : null;
                $this->start_date         = (isset($data['start_date'])) ? $data['start_date'] : null;
                $this->end_date         = (isset($data['end_date'])) ? $data['end_date'] : null;
                $this->order         = (isset($data['order'])) ? $data['order'] : null;
                $this->ticker_file         = (isset($data['ticker_file'])) ? $data['ticker_file'] : null;
                $this->status         = (isset($data['status'])) ? $data['status'] : null;                
                $this->created_by         = (isset($data['created_by'])) ? $data['created_by'] : null;
                $this->modify_by         = (isset($data['modify_by'])) ? $data['modify_by'] : null;
                $this->created_date         = (isset($data['created_date'])) ? $data['created_date'] : null;
                $this->modify_date         = (isset($data['modify_date'])) ? $data['modify_date'] : null;
                $this->scroll_direction = (isset($data['scroll_direction'])) ? $data['scroll_direction'] : null;
                $this->ticker_redirect_url = (isset($data['ticker_redirect_url']))? $data['ticker_redirect_url']:null;
        }
        
         public function getArrayCopy()
        {
         return get_object_vars($this);
        }
        
}
?>
