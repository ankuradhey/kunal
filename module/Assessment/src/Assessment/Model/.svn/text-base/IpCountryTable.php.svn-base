<?php
namespace Assessment\Model;

use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;
use Zend\Paginator\Paginator;
/**
 *
 * @author extramarks
 *     */
class IpCountryTable 
{
    // TODO - Insert your code here

    /**
     *
     * @param string $table            
     *
     * @param Adapter $adapter            
     *
     * @param Feature\AbstractFeature|Feature\FeatureSet|Feature\AbstractFeature[] $features            
     *
     * @param ResultSetInterface $resultSetPrototype            
     *
     * @param Sql $sql            
     *
     * @throws Exception\InvalidArgumentException
     *
     */
    
  protected $tableGateway;
  protected $select;
  public function __construct(TableGateway $tableGateway)
  {
      $this->tableGateway = $tableGateway;
      $this->select = new Select();
  }
	
public function checkMultiIp($ipAddress){
        $ipAddressMultiArr = explode(',',$ipAddress);
        $counter = count($ipAddressMultiArr);
        $i=0;
        while($i<$counter){
            $ipAddressArr = explode('.',$ipAddressMultiArr[$i]);
            $ipNumber = ($ipAddressArr[0] * (256*256*256) ) + ($ipAddressArr[1] * (256*256) ) + ($ipAddressArr[2] * 256) + $ipAddressArr[3] ;
            if($ipNumber >= 167772160 && $ipNumber <= 184549375){
                $i++;
            }else{
                return $ipNumber;
            }
        }
        
    }
    
    
  public function ipRange($ip_address) {
      $multiIPs = strpos($ip_address, ',');
        
        if($multiIPs === false){
            $ipAddressArr = explode('.',$ip_address);
            $ipNumber = ($ipAddressArr[0] * (256*256*256) ) + ($ipAddressArr[1] * (256*256) ) + ($ipAddressArr[2] * 256) + $ipAddressArr[3] ;
        }else{
            $ipNumber = $this->checkMultiIp($ip_address);
        }
        
        $sql = "SELECT countrySHORT,countryLONG,state,city FROM ipcountry FORCE INDEX (idx_ipc_ip) WHERE ipFROM <='" . $ipNumber . "' AND `ipTO` >= '" . $ipNumber . "' ";
        $stmt = $this->tableGateway->getAdapter()->createStatement($sql);
        $stmt->prepare();      
        
        $resultSet = $stmt->execute(); 
        
        $resultSet->buffer();
        foreach($resultSet as $key => $val){
            return $val;
        }
        
  }
  
  public function ipRangeReference($ip_reference) {
      $checkValidIp = strpos($ip_reference, '.');
      if($checkValidIp === false){
          $ipNumber = $ip_reference;
      }else{
          $ip_address = $ip_reference;
          $multiIPs = strpos($ip_address, ',');
          if($multiIPs === false){
              $ipAddressArr = explode('.',$ip_address);
              $ipNumber = ($ipAddressArr[0] * (256*256*256) ) + ($ipAddressArr[1] * (256*256) ) + ($ipAddressArr[2] * 256) + $ipAddressArr[3] ;
          }else{
              $ipNumber = $this->checkMultiIp($ip_address);
          }
      }
      
      $sql = "SELECT countrySHORT,countryLONG,state,city FROM ipcountry FORCE INDEX (idx_ipc_ip) WHERE ipFROM <='" . $ipNumber . "' AND `ipTO` >= '" . $ipNumber . "' ";
      $stmt = $this->tableGateway->getAdapter()->createStatement($sql);
      $stmt->prepare();      
        
      $resultSet = $stmt->execute(); 
        
      $resultSet->buffer();
      foreach($resultSet as $key => $val){
          return $val;
      }
        
  }
//    
/** End Of Class **/        
}


