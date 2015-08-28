<?php
namespace Assessment\Model;

class Tuserpackage {

    public $user_package_id;
    public $valid_till;
    public $package_category;
    public $valid_from;
    public $recent_usage_id;
    public $pakage_id;
    public $user_id;
    public $transaction_id;
    public $order_id;
    public $price;
    public $currency_type;
    public $package_name;
    public $purchase_date;
    public $syllabus_id;
    public $subject_name;
    public $parent_subject_name;
    public $subject_language;
    public $parent_subject_language;
    public $board_class_subject_id;
    public $discount_amount;
    public $board_class_parent_subject_id;
    public $display_name;
    public $purchaser_id;
    public $purchaser;
    public $package_id;
    public $package_type;
    public $package_image;
    public $subject_added;
    public $code_assign_id;
    public $status;
    public $pkg_payment_type;
    public $actual_package_id;
    public $transaction_product_type;
    public $is_switched;
    public $package_syllabi_id; 
    public $first_name; 
    public $package_price; 
    public $days; 
    public $display_validity_date; 

    public function exchangeArray($data) {
//            echo '<pre>';print_r($data);echo '</pre>';die('Macro Die');
        $this->user_package_id = (isset($data['user_package_id'])) ? $data['user_package_id'] : null;
        $this->discount_amount = (isset($data['discount_amount'])) ? $data['discount_amount'] : null;
        $this->valid_till = (isset($data['valid_till'])) ? $data['valid_till'] : null;
        $this->valid_from = (isset($data['valid_from'])) ? $data['valid_from'] : null;
        $this->recent_usage_id = (isset($data['recent_usage_id'])) ? $data['recent_usage_id'] : null;
        $this->pakage_id = (isset($data['pakage_id'])) ? $data['pakage_id'] : null;
        $this->user_id = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->transaction_id = (isset($data['transaction_id'])) ? $data['transaction_id'] : null;
        $this->order_id = (isset($data['order_id'])) ? $data['order_id'] : null;
        $this->price = (isset($data['price'])) ? $data['price'] : null;
        $this->package_name = (isset($data['package_name'])) ? $data['package_name'] : null;
        $this->purchase_date = (isset($data['purchase_date'])) ? $data['purchase_date'] : null;
        $this->syllabus_id = (isset($data['syllabus_id'])) ? $data['syllabus_id'] : null;
        $this->board = (isset($data['board'])) ? $data['board'] : null;
        $this->subject_language = (isset($data['subject_language'])) ? $data['subject_language'] : null;
        $this->class = (isset($data['class'])) ? $data['class'] : null;
        $this->subject_name = (isset($data['subject_name'])) ? $data['subject_name'] : null;
        $this->board_class_subject_id = (isset($data['board_class_subject_id'])) ? $data['board_class_subject_id'] : null;
        $this->board_class_parent_subject_id = (isset($data['board_class_parent_subject_id'])) ? $data['board_class_parent_subject_id'] : null;
        $this->parent_subject_language = (isset($data['parent_subject_language'])) ? $data['parent_subject_language'] : null;
        $this->parent_subject_name = (isset($data['parent_subject_name'])) ? $data['parent_subject_name'] : null;
        $this->currency_type = (isset($data['currency_type'])) ? $data['currency_type'] : null;
        $this->board_id = (isset($data['board_id'])) ? $data['board_id'] : null;
        $this->class_id = (isset($data['class_id'])) ? $data['class_id'] : null;
        $this->display_name = (isset($data['display_name'])) ? $data['display_name'] : null;
        $this->purchaser_id = (isset($data['purchaser_id'])) ? $data['purchaser_id'] : null;
        $this->purchaser = (isset($data['purchaser'])) ? $data['purchaser'] : null;
        $this->package_id = (isset($data['package_id'])) ? $data['package_id'] : null;
        $this->package_type = (isset($data['package_type'])) ? $data['package_type'] : null;
        $this->package_image = (isset($data['package_image'])) ? $data['package_image'] : null;
        $this->subject_added = (isset($data['subject_added'])) ? $data['subject_added'] : null;
        $this->code_assign_id = (isset($data['code_assign_id'])) ? $data['code_assign_id'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        $this->pkg_payment_type = (isset($data['pkg_payment_type'])) ? $data['pkg_payment_type'] : null;
        $this->actual_package_id = (isset($data['actual_package_id'])) ? $data['actual_package_id'] : null;
        $this->package_category = (isset($data['package_category'])) ? $data['package_category'] : null;
        $this->transaction_product_type = (isset($data['transaction_product_type'])) ? $data['transaction_product_type'] : null;
        $this->is_switched = (isset($data['is_switched'])) ? $data['is_switched'] : null;
        $this->package_syllabi_id = (isset($data['package_syllabi_id'])) ? $data['package_syllabi_id'] : null;
        $this->first_name = (isset($data['first_name'])) ? $data['first_name'] : null;
        $this->package_price = (isset($data['package_price'])) ? $data['package_price'] : null;
        $this->days = (isset($data['days'])) ? $data['days'] : null;
        $this->display_validity_date = (isset($data['display_validity_date'])) ? $data['display_validity_date'] : null;
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

}
