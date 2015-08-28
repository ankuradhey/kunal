<?php

namespace Assessment\Model;

/**
 *
 * @author extramarks
 *        
 */
class IpCountry {
    // TODO - Insert your code here

    /**
     */ public $id;
    public $ipFrom;
    public $ipTo;
    public $countrySHORT;
    public $countryLONG;
    public $state;
    public $city;
    



    public function exchangeArray($data) {

        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->ipFrom = (isset($data['ipFrom'])) ? $data['ipFrom'] : null;
        $this->ipTo = (isset($data['ipTo'])) ? $data['ipTo'] : null;
        $this->countrySHORT = (isset($data['countrySHORT'])) ? $data['countrySHORT'] : null;
        $this->countryLONG = (isset($data['countryLONG'])) ? $data['countryLONG'] : null;
        $this->state = (isset($data['state'])) ? $data['state'] : null;
        $this->city = (isset($data['city'])) ? $data['city'] : null;
        
    }

    // Add the following method:
    public function getArrayCopy() {
        return get_object_vars($this);
    }

}

