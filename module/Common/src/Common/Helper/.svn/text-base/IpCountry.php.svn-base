<?php

/**
 * Lms\Helper
 * 
 * @author
 * @version 
 */
namespace Common\Helper;
use Zend\View\Helper\AbstractHelper;

/**
 * View Helper
 */

class IpCountry extends AbstractHelper {

    
    protected $containerService = null;
    public $configdata = null;
    public $status;   

   
    public function __invoke($systemIp) 
    {
     $ipResultSet     = $this->Iptabledata->ipRange($systemIp);
     $ipCaptureDetails = $this->containerService->useripcapturefunction($ipResultSet,$systemIp);
     return $ipCaptureDetails['country'];
    }     
    
/**
  * @param Container $containerService
  */
  public function getContainerService() {
        return $this->containerService;
  }
  /**
    * @param Container $containerService
   */
  public function setContainerService($containerService) {
        $this->containerService = $containerService;
    }
    
   /**
     * @param Container $containerService
     */
    public function setIpservice($Iptabledata) {
        $this->Iptabledata = $Iptabledata;
    }
}