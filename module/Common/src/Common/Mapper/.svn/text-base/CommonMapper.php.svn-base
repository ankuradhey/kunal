<?php

namespace Common\Mapper;

use UseIndexWalker;
use Doctrine\ORM\Query; 
use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Common\Options\ModuleOptions;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Db\Sql\Predicate\IsNotNull;
use Common\Entity\Country;
use Common\Entity\State;
use Common\Entity\CityData;
use Common\Entity\OfflinePaymentTransaction;
use Common\Entity\IpCountryCode;
use Common\Entity\UserIpCapture;
use Common\Entity\Ipcountry;
use Common\Entity\IpNotFound;
use Zend\Escaper\Escaper;
use Lms\Entity\CustomBoardRack;
use Lms\Entity\ResourceRack;

/**
 *
 * @author extramarks
 *        
 */
class CommonMapper {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    protected $options;
    public $parameters;

    public function __construct(EntityManager $em, ModuleOptions $options) {
        $this->em = $em;
        $this->options = $options;
    }

    /**
     * @author:Aditi 
     * @created Date :09 Sep 2014
     * @param :NULL
     * @return Country Object array 
     * 
     * */
    public function getAllCountries($status = '') {
        $entity = $this->options->getCountryEntityClass();
        if ($status == '') {
            $query = $this->em->
                    createQuery("select a from $entity a where a.status!=0");
        } else {
            $query = $this->em->
                    createQuery("select a from $entity a where a.status=2");
        }


        return $query->getResult();
    }
    
    public function getCountarybystate($countryId) {
        $entity = $this->options->getStateEntityClass();
        $query = $this->em->createQuery("select a from $entity a where a.country_id='$countryId' ORDER BY a.stateName");
        return $query->getResult();
    }
    
    public function getCityByStateName($stateName) { 
        $entity = $this->options->getCityDataEntityClass();
        $query = $this->em->createQuery("select a.city from $entity a where a.state='$stateName' GROUP BY a.city");
        return $query->getResult();
    }

    public function getStateById($state_id)
    {
        
        $entity = $this->options->getStateEntityClass();
        $query = $this->em->
                    createQuery("select a from $entity a where a.stateId = '$state_id' ");
        
        return $query->getResult();
        // return $entity->findBy(array('state_id'=>$state_id));
    }
    
    public function getCountryById($country_id)
    {
        $entity = $this->options->getCountryEntityClass();
        $query = $this->em->
                    createQuery("select a from $entity a where a.countryId = '$country_id' ");
        
        return $query->getResult();
    }
    public function getCountryByPhoneCode($phone_code)
    {
        $entity = $this->options->getCountryEntityClass();
        $query = $this->em->
                    createQuery("select a from $entity a where a.phonecode = '$phone_code' ");
        
        return $query->getResult();
    }
    public function getCountryIdByCountryName($country_name)
    {
        $entity = $this->options->getCountryEntityClass();
        $query = $this->em->
                    createQuery("select a from $entity a where a.countryName = '$country_name' ");
        
        return $query->getResult();
    }
    
    public function getStateIdByStateName($state_name)
    {
        $entity = $this->options->getStateEntityClass();
        $query = $this->em->
                    createQuery("select a from $entity a where a.stateName = '$state_name' ");
        
        return $query->getResult();
    }
    
    public function getcustomboardPrimaryId($customboardId,$rackId)
    {
        $entity = $this->options->getcustomboardEntityClass();
        $query  = $this->em->
                    createQuery("select a from $entity a where a.customBoardId = '$customboardId' AND a.rackId='$rackId' ");
        $result = $query->getResult();
        $custom_rack_id = '';
        foreach($result as $customboards)
        {
            $custom_rack_id = $customboards->getCustomBoardRackId();
        }
        return $custom_rack_id;
    }
    
    
    
    public function getSchoolNameFromERP($school_name,$city,$limitRes = 5)
    {
        $cityCondition ="";
        if(isset($city)){
            $cityCondition = " AND (a.areaName = '$city' OR a.schoolAddress LIKE '%$city%') ";
        }
        $entity = $this->options->getSalesSchoolDetailsEntityClass();
        $query = $this->em->createQuery("select a.schoolId, a.schoolReportStatus, a.schoolName , a.areaName  from $entity a where a.schoolName LIKE '%$school_name%' $cityCondition ")
                    ->setMaxResults($limitRes);
                
        return $query->getResult();
    }
    
    public function getSchoolDetailsFromERPAPI($value,$type)
    {
        $entity = $this->options->getSalesSchoolDetailsEntityClass();
        if($type == 'name'){
            $query = $this->em->createQuery("select a.schoolCode , a.schoolName , a.areaName  from $entity a where a.schoolName LIKE '%$value%' ");
            
        }else if($type == 'code'){
            $query = $this->em->createQuery("select a.schoolId , a.schoolName , a.schoolReportStatus, a.schoolCode  from $entity a where a.schoolCode = '$value' ");
            
        }      
        return $query->getResult();
    }
    
    public function getSchoolIdFromERP($school_name,$city)
    {
        $school_name = str_replace("'", "''", $school_name);
        $cityCondition ="";
        if(isset($city)){
            $cityCondition = " AND (a.areaName = '$city' OR a.schoolAddress LIKE '%$city%') ";
        }
        $entity = $this->options->getSalesSchoolDetailsEntityClass();
        $query = $this->em->createQuery("select a.schoolId from $entity a where a.schoolName = '$school_name' ");
        $res = $query->getResult();
        $schoolId = NULL;
        if(count($res) >0){
           foreach ($res as $school) {
               $schoolId = $school['schoolId'];
           }
        }else{
            $school_nameArr = explode(" ,".$city,$school_name);
            $query = $this->em->createQuery("select a.schoolId from $entity a where a.schoolName = '$school_nameArr[0]' ");
            $res = $query->getResult();
            if(count($res) >0){
               foreach ($res as $school) {
                   $schoolId = $school['schoolId'];
               }
            }
        }
        return $schoolId;
    }
    public function getSchoolStatusFromId($schoolId)
    {
        $entity = $this->options->getSalesSchoolDetailsEntityClass();
        $query = $this->em->createQuery("select a.schoolReportStatus from $entity a where a.schoolId = '$schoolId' ");
        $res = $query->getResult();
        foreach ($res as $school) {
           $schoolStatus = $school['schoolReportStatus'];
        }
        if($schoolStatus == 4)
           return true;
        return false;
    }
    
    public function updateOfflinePaymentTransaction($id,$updater_id,$status)
    {
        $container = $this->em->getRepository($this->options->getOfflinePaymentTransactionEntityClass());
        $containerList = $container->findOneBy(array('id' => $id));
        $containerList->setPaymentCollectionStatus($status);
        $containerList->setPaymentUpdateUserId($updater_id); 
        $this->em->persist($containerList);
        $this->em->flush();
    }
    
    public function addOfflinePaymentTransaction($transaction_id,$loginId,$data) {
       // print_r($data);die;
        $er = $this->em->getRepository($this->options->getOfflinePaymentTransactionEntityClass());
        $obj = new OfflinePaymentTransaction();

        $obj->setUserTransactionId($transaction_id);
        if(isset($data['currency']))
            $obj->setCurrency($data['currency']);
        $obj->setPaymentMode($data['mode_of_payment']);
        $obj->setDdChequeNumber($data['dd_cheque_number']);
        $obj->setDdChequeDate($data['dd_cheque_receipt_date']);
        $obj->setBank($data['deposit_bank']);
        $obj->setDepositDate($data['date_of_deposit']);
        $obj->setAccountNumber($data['account_number']);
        $obj->setBankBranch($data['bank_branch']);
        $obj->setLoginId($loginId);
        $obj->setPaymentSource($data['payment_source']);
        if(isset($data['payment_collection_status'])){
            $obj->setPaymentCollectionStatus($data['payment_collection_status']);
        }else{
            $obj->setPaymentCollectionStatus('Pending');
        }
        $obj->setPaymentUpdateUserId(0);
        $obj->setOtherPaymentDetails($data['other_payment_desc']);

        $this->em->persist($obj);
        $this->em->flush();
    }
    
    public function ipcheckfunction($ipResultSet,$ip_address,$userId = NULL){
        
        if(count($ipResultSet)>0){
            $country_code = $ipResultSet['countrySHORT'];
            $state = $ipResultSet['state'];
            $city = $ipResultSet['city'];
            $country_code = ($country_code!='-')?$country_code:'IN';
            $this->addIpCountryData($country_code,$state,$city,$ip_address,'valid');
        }else{
            
            $checkSQE = strpos($_SERVER['SERVER_NAME'], '10.1.9.99');
            if($checkSQE !== false){
                $this->addIpCountryData('IN','UTTAR PRADESH','NOIDA', $ip_address,'invalid');
                $country_code =  'IN';
            }
            else{
                $extension = $this->tld($_SERVER['SERVER_NAME']);
                if($extension == '.com'){
                    $country_code =  'IN';
                }else if($extension == '.za'){
                    $country_code = 'ZA';
                }else if($extension == '.sg'){
                    $country_code = 'SG';
                }else{
                    $country_code =  'US';
                }
                $this->addIpCountryData($country_code,NULL,NULL,$ip_address,'invalid');
            }
            
        }
        //$country_code = trim(strip_tags(htmlspecialchars($this->getCountryFromIp($ip_address))));
        
        //set country code in session

        if(strlen($country_code) != '2' || $ip_address == NULL){
            $_SESSION['location'] = 'IN';
            $_SESSION['currencyType'] = 'INR';
        }
        else{
            $_SESSION['location'] = $country_code;
            
            if($_SESSION['location'] == 'ZA'){
                $_SESSION['currencyType'] = 'ZAR';
            }
            else if($_SESSION['location'] == 'SG'){
                $_SESSION['currencyType'] = 'SGD';
            }else if($_SESSION['location'] != 'IN'){
                $_SESSION['currencyType'] = 'USD';
            }else{
                $_SESSION['currencyType'] = 'INR';
            }
        }
        $_SESSION['starting_currencyType'] = $_SESSION['currencyType']; // for fallback
        
    }
    
    
    public function useripcapturefunction($ipResultSet,$ip_address){
          
        if(count($ipResultSet)>0 && ($ipResultSet['countrySHORT'] != '-' && $ipResultSet['state'] != '-' && $ipResultSet['city'] != '-')){
            //$resultRes = $containerList[0];
            $countryNameIP = $ipResultSet['countryLONG'];
            $stateNameIP = $ipResultSet['state']?$ipResultSet['state']:'0';
            $cityNameIP = $ipResultSet['city']?$ipResultSet['city']:'0';
            $result['country'] = $countryNameIP;
            $result['state'] = $stateNameIP;
            $result['city'] = $cityNameIP;
            // $this->addUserIpCapture($country,$state,$city,$userId);
        }else{
            $checkSQE = strpos($_SERVER['SERVER_NAME'], 'developer.extramarks.com');
            $checkSQE1 = strpos($_SERVER['SERVER_NAME'], 'nemrwebsite.extramarks.com');
            if($checkSQE !== false || $checkSQE1 !== false){
                $result['country'] = 'INDIA';
                $result['state'] = 'UTTAR PRADESH';
                $result['city'] = 'NOIDA';
                // $this->addUserIpCapture('INDIA','UTTAR PRADESH','NOIDA',$userId);
            }else{
                $extension = $this->tld($_SERVER['SERVER_NAME']);
                $state='0';$city='0';
                if($extension == '.com'){
                    $country =  'INDIA';
                }else if($extension == '.za'){
                    $country = 'SOUTH AFRICA';
                }else if($extension == '.sg'){
                    $country = 'SINGAPORE';
                }else{
                   // $country =  'UNITED STATES';
                    $country = 'INDIA';
                    $state   = 'UTTAR PRADESH';
                    $city    = 'NOIDA';
                }
                $result['country'] = $country;
                $result['state'] = $state;
                $result['city'] = $city;
                // $this->addUserIpCapture($country,$state,$city,$userId);
            }
        }
//        $result['country'] = 'INDIA';
//        $result['state'] = 'UTTAR PRADESH';
//        $result['city'] = 'NOIDA';
       // echo "<pre />"; print_r($result); exit;
        $_SESSION['user_session_ip_country'] = $result['country'];
        $_SESSION['user_session_ip_state'] = $result['state'];
        $_SESSION['user_session_ip_city'] = $result['city'];
        return $result;
    }
    
    public function useripcaptureeditprofile($ipResultSet){
        
        if(count($ipResultSet)>0 && ($ipResultSet['countryLONG'] != '-' && $ipResultSet['state'] != '-' && $ipResultSet['city'] != '-')){
            $countryNameIP = $ipResultSet['countryLONG'];
            $stateNameIP = $ipResultSet['state']?$ipResultSet['state']:'0';
            $cityNameIP = $ipResultSet['city']?$ipResultSet['city']:'0';
            $result['country'] = $countryNameIP;
            $result['state'] = $stateNameIP;
            $result['city'] = $cityNameIP;
            // $this->addUserIpCapture($country,$state,$city,$userId);
        }else{
            $checkSQE = strpos($_SERVER['SERVER_NAME'], '10.1.9.99');
            
            if($checkSQE !== false){
                $result['country'] = 'INDIA';
                $result['state'] = 'UTTAR PRADESH';
                $result['city'] = 'NOIDA';
                // $this->addUserIpCapture('INDIA','UTTAR PRADESH','NOIDA',$userId);
            }
            else{
                $extension = $this->tld($_SERVER['SERVER_NAME']);
                $state='0';$city='0';
                if($extension == '.com'){
                    $country =  'INDIA';
                }else if($extension == '.za'){
                    $country = 'SOUTH AFRICA';
                }else if($extension == '.sg'){
                    $country = 'SINGAPORE';
                }else{
                    $country =  'UNITED STATES';
                }
                $result['country'] = $country;
                $result['state'] = $state;
                $result['city'] = $city;
                // $this->addUserIpCapture($country,$state,$city,$userId);
            }
        }
//        $result['country'] = 'INDIA';
//        $result['state'] = 'UTTAR PRADESH';
//        $result['city'] = 'NOIDA';
        $_SESSION['user_session_ip_country_profile'] = $result['country'];
        $_SESSION['user_session_ip_state_profile'] = $result['state'];
        $_SESSION['user_session_ip_city_profile'] = $result['city'];
        return $result;
    }
    
    // To Be deleted
    public function useripcapturefunctionOLD($userId){
        $checkCapture = $this->checkUserIpCaptureExists($userId);
        
        if($checkCapture != 'not exists'){
            $result['country'] = $checkCapture->getIpBasedCountry();
            $result['state'] = $checkCapture->getIpBasedState();
            $result['city'] = $checkCapture->getIpBasedCity();
            return $result;
        }
        
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        
        $multiIPs = strpos($ip_address, ',');
        
        if($multiIPs === false){
            $ipAddressArr = explode('.',$ip_address);
            $ipNumber = ($ipAddressArr[0] * (256*256*256) ) + ($ipAddressArr[1] * (256*256) ) + ($ipAddressArr[2] * 256) + $ipAddressArr[3] ;
        }else{
            $ipNumber = $this->checkMultiIp($ip_address);
        }
        
        $entity = $this->options->getIpcountryEntityClass();
        $query = $this->em->createQueryBuilder()->select("r")
                ->from($entity, "r")
                ->where(" r.ipFrom <='" . $ipNumber . "' AND r.ipTo >='" . $ipNumber . "'");
        $containerList = $query->getQuery()->getResult();
        
        if(count($containerList)>0){
            $result = $containerList[0];
            $country = $result->getCountryName();
            $state = $result->getState()?$result->getState():'0';
            $city = $result->getCity()?$result->getCity():'0';
            $result['country'] = $country;
            $result['state'] = $state;
            $result['city'] = $city;
            $this->addUserIpCapture($country,$state,$city,$userId);
        }else{
            $checkSQE = strpos($_SERVER['SERVER_NAME'], '10.1.9.99');
            
            if($checkSQE !== false){
                $result['country'] = 'INDIA';
                $result['state'] = 'UTTAR PRADESH';
                $result['city'] = 'NOIDA';
                $this->addUserIpCapture('INDIA','UTTAR PRADESH','NOIDA',$userId);
            }
            else{
                $extension = $this->tld($_SERVER['SERVER_NAME']);
                $state='0';$city='0';
                if($extension == '.com'){
                    $country =  'INDIA';
                }else if($extension == '.za'){
                    $country = 'SOUTH AFRICA';
                }else if($extension == '.sg'){
                    $country = 'SINGAPORE';
                }else{
                    $country =  'UNITED STATES';
                }
                $result['country'] = $country;
                $result['state'] = $state;
                $result['city'] = $city;
                $this->addUserIpCapture($country,$state,$city,$userId);
            }
        }
        $result['country'] = 'INDIA';
        $result['state'] = 'UTTAR PRADESH';
        $result['city'] = 'NOIDA';
        return $result;
        return $result;
    }
    
    public function tld( $uri ) {
        $parts = explode('.', $uri);
        return (sizeof($parts) ? ('.' . end($parts)) : false);
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
    
    public function ipcheckfunctionDecData($ip_address) {
        $multiIPs = strpos($ip_address, ',');
        
        if($multiIPs === false){
            $ipAddressArr = explode('.',$ip_address);
            $ipNumber = ($ipAddressArr[0] * (256*256*256) ) + ($ipAddressArr[1] * (256*256) ) + ($ipAddressArr[2] * 256) + $ipAddressArr[3] ;
        }else{
            $ipNumber = $this->checkMultiIp($ip_address);
        }
        
        $entity = $this->options->getIpcountryEntityClass();
        $query = $this->em->createQueryBuilder()->select("r")
                ->from($entity, "r")
                ->where(" r.ipFrom <='" . $ipNumber . "' AND r.ipTo >='" . $ipNumber . "'");
        $containerList = $query->getQuery()->getResult();
        
        if(count($containerList)>0){
            $result = $containerList[0];
            $country_code = $result->getCountryCode();
            $country_code = ($country_code!='-')?$country_code:'IN';
        }else{
            $country_code ="IN";
//            $parts = explode('10.1.9.99', $_SERVER['SERVER_NAME']);
//            $checkSQE = (sizeof($parts) ? true : false);
//            if($checkSQE == true){
//                //$this->addIpCountryData('IN',$ip_address,'invalid');
//                $country_code = 'IN';
//            }
//        
//            $extension = $this->tld($_SERVER['SERVER_NAME']);
//            if($extension == '.com'){
//                $country_code =  'IN';
//            }else if($extension == '.za'){
//                $country_code = 'ZA';
//            }else if($extension == '.sg'){
//                $country_code = 'SG';
//            }else{
//                $country_code =  'US';
//            }
            //$this->addIpCountryData($country_code,$ip_address,'invalid');
        }
        return $country_code;
    }
    
    public function checkIpCountryExists($ip) {
        $er = $this->em->getRepository($this->options->getIpCountryCodeEntityClass());
        return $er->findOneBy(array('ip' => $ip), 
                              array('id' => 'DESC'));
    }
    
    
    public function addIpCountryData($code,$state,$city,$ip,$status) {
        $er = $this->em->getRepository($this->options->getIpCountryCodeEntityClass());
        $obj = new IpCountryCode();
        $obj->setIp($ip);
        $obj->setCountryCode($code);
        $obj->setState($state);
        $obj->setCity($city);
        $obj->setStatus($status);
        $this->em->persist($obj);
        $this->em->flush();
        return $obj;
    }
    
    public function checkUserIpCaptureExists($userId) {
        $container = $this->em->getRepository($this->options->getUserIpCaptureEntityClass());
        $containerList = $container->findOneBy(array('userId' => $userId));
        if(count($containerList) > 0){
            return $containerList;
        }
        return 'not exists';
    }
    
    
    public function addUserIpCapture($userId,$data) {
        $er = $this->em->getRepository($this->options->getUserIpCaptureEntityClass());
        $obj = new UserIpCapture();
        $obj->setUserId($userId);
        if(array_key_exists('countryNameChanged',$data) && array_key_exists('stateNameChanged',$data) && array_key_exists('cityNameChanged',$data)){
            $obj->setChangedLocationCountry($data['countryNameChanged']);
            $obj->setChangedLocationState($data['stateNameChanged']);
            $obj->setChangedLocationCity($data['cityNameChanged']);
            $obj->setChangeFlag('1');
        }else{
            $obj->setChangedLocationCountry('');
            $obj->setChangedLocationState('');
            $obj->setChangedLocationCity('');
            $obj->setChangeFlag('0');
        }
        
        $obj->setIpBasedCountry($data['countryNameIP']);
        $obj->setIpBasedState($data['stateNameIP']);
        $obj->setIpBasedCity($data['cityNameIP']);
        $this->em->persist($obj);
        $this->em->flush();
        return $obj;
    }
    
    
public function escaper($arr)
  {
      $postArray = array();
      while(list($var, $val) = each($arr)) {
        $escaper = new Escaper('utf-8');
        //$output  = $escaper->escapeHtmlAttr($val);
        $output  = $escaper->escapeHtml($val);
        $output  = $escaper->escapeJs($output);
        $output  = $escaper->escapeCss($output);
        $output  = $escaper->escapeUrl($output);
        $postArray[$var] = $output;  
      }
      return $postArray;  
  }
  
  public function escaperids($var)
  {
      $escaper = new Escaper('utf-8');
      $output  = $escaper->escapeJs($var);
      $output  = $escaper->escapeCss($output);
      $output  = $escaper->escapeUrl($output);
       
      return $output;  
  }
  
  public function escapMessage($val)
  {
        $escaper = new Escaper('utf-8');
        $output  = $escaper->escapeHtml($val);
        //$output  = $escaper->escapeHtml($output);
      
      return $output;  
  }

 public function validateImage($dataArray)
  {
        $valid_mime_types      = array("image/gif","image/png","image/jpeg","image/pjpeg",);
        $valid_file_extensions = array(".jpg", ".jpeg", ".gif", ".png");
        $file_extension        = strrchr($dataArray["image_file"]["name"], ".");
        $filename              = $dataArray["image_file"]["tmp_name"];
        $imagesize             = getimagesize($filename);

        if(in_array($file_extension, $valid_file_extensions) && in_array($dataArray["image_file"]["type"], $valid_mime_types)) 
         {
             $_returnArr = $dataArray;
        } else{
             $_returnArr['error'] = "Error: Only .jpg,.png,.gif,.pjpg images for upload";
        }
        
        if($imagesize == FALSE)
        {
            $_returnArr = "corrupt";
        }else{
            $_returnArr = $dataArray;
        }
        return $_returnArr;
   }
   
   public function is_correctfile($path)
    {
       $a = getimagesize($path);
       $image_type = $a[2];
       
       if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
       {
           return 'true';
       }
       return "false";
    } 
    
   public function is_correctotherfile($filename,$filetype) 
   {
        $allowed  = array("webm", "mp4","doc","txt","docx");
        $filename = $filename;
        $mimetype = array("video/mp4","video/webm","text/plain","application/doc","application/msword","application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(in_array($ext,$allowed)) {
          $msg ='true';
          }else{
            $msg ="false";
            return $msg; 
        }
       if(in_array($filetype,$mimetype))
       {
         $msg ='true';  
       }else{
          $msg = "false";
          return $msg; 
        }
     return $msg;   
   }
  public function newpackageordermailcontent($data) {
        $id = $data['id'];
        $boradname = $data['board_name'];
        $classname = $data['class_name'];
        $package_hidden_name = $data['package_hidden_name'];
        $valid_till = $data['valid_till'];
        $subject_string = trim($data['subject_string']);
        $subject_string = rtrim($subject_string,'<br />');
        $subject_string = rtrim($subject_string,'<br/>');
        $subject_string = rtrim($subject_string,'<br>');
        $subject_string = trim($subject_string);
        $subject_string = rtrim($subject_string,",");
        $packageRow =  '<tr align="center">
          <td width="58" valign="top"><p>'.$id.'</p></td>
          <td width="75" valign="top"><p>'.$boradname.'</p></td>
          <td width="56" valign="top"><p>'.$classname.'</p></td>
          <td width="103" valign="top"><p>'.$subject_string.'</p></td>
          <td width="233" valign="top"><p>'.$package_hidden_name.'</p></td>
          <td width="171" valign="top"><p>'.$valid_till.'</p></td>
        </tr>';
        return $packageRow;
  }
  
  public function newpackageordermailcontentcod($data) {
        $id = $data['id'];
        $boradname = $data['board_name'];
        $classname = $data['class_name'];
        $package_hidden_name = $data['package_hidden_name'];
        $valid_till = $data['valid_till'];
        $quantity = $data['quantity'];
        $amount = $data['total_amount'];
        $subject_string = trim($data['subject_string']);
        $subject_string = rtrim($subject_string,'<br />');
        $subject_string = rtrim($subject_string,'<br/>');
        $subject_string = rtrim($subject_string,'<br>');
        $subject_string = trim($subject_string);
        $subject_string = rtrim($subject_string,",");
        $packageRow =  '<tr align="center">
          <td width="58" valign="top"><p>'.$id.'</p></td>
          <td width="75" valign="top"><p>'.$boradname.'</p></td>
          <td width="56" valign="top"><p>'.$classname.'</p></td>
          <td width="103" valign="top"><p>'.$subject_string.'</p></td>
          <td width="233" valign="top"><p>'.$package_hidden_name.'</p></td>
          <td width="171" valign="top"><p>'.$valid_till.'</p></td>
          <td width="100" valign="top"><p>'.$quantity.'</p></td>
          <td width="100" valign="top"><p>'.$amount.'</p></td>
        </tr>';
        return $packageRow;
  }
  
    public function htmltemplateoftablet($data) {
        $tablet_html = $data['tablethtml'];
        $tablet_html = str_replace('{SITE_URL}', $data['site_url'], $tablet_html);
        $tablet_html = str_replace('{STUDENT_NAME}', ucwords($data['student_name']), $tablet_html);
        $tablet_html = str_replace('{PACKAGE_NAME}', $data['package_name'], $tablet_html);
        $tablet_html = str_replace('{CLASS_NAME}', $data['class_name'], $tablet_html);
        $tablet_html = str_replace('{PRICE}', $data['price'], $tablet_html);
        $tablet_html = str_replace('{ADDRESS}', $data['address'], $tablet_html);
        $tablet_html = str_replace('{DOWNLOAD_PDF}', $data['download_pdf'], $tablet_html);
        return $tablet_html;
    }
    
    public function htmltemplateoftabletcod($data) {
        $tablet_html = $data['tablethtml'];
        $tablet_html = str_replace('{SITE_URL}', $data['site_url'], $tablet_html);
        $tablet_html = str_replace('{STUDENT_NAME}', ucwords($data['student_name']), $tablet_html);
        $tablet_html = str_replace('{PACKAGE_NAME}', $data['package_name'], $tablet_html);
        $tablet_html = str_replace('{CLASS_NAME}', $data['class_name'], $tablet_html);
        $tablet_html = str_replace('{PRICE}', $data['price'], $tablet_html);
        $tablet_html = str_replace('{ADDRESS}', $data['address'], $tablet_html);
        $tablet_html = str_replace('{DOWNLOAD_PDF}', $data['download_pdf'], $tablet_html);
        $tablet_html = str_replace('{CURRENCY_TYPE}', $data['currency'], $tablet_html);
        return $tablet_html;
    }
  
    public function htmltemplateofsdcard($data) {
          $sdcardorder_html = $data['sdcardhtml'];
          $sdcardorder_html = str_replace('{SITE_URL}', $data['site_url'], $sdcardorder_html);
          $sdcardorder_html = str_replace('{STUDENT_NAME}', ucwords($data['student_name']), $sdcardorder_html);
          $sdcardorder_html = str_replace('{CLASS_NAME}', $data['class_name'], $sdcardorder_html);
          $sdcardorder_html = str_replace('{PRICE}', $data['price'], $sdcardorder_html);
          $sdcardorder_html = str_replace('{ADDRESS}', $data['address'], $sdcardorder_html);
          $sdcardorder_html = str_replace('{DOWNLOAD_PDF}', $data['download_pdf'], $sdcardorder_html);
          return $sdcardorder_html;
    }
    
    public function htmltemplateofsdcardcod($data) {
          $sdcardorder_html = $data['sdcardhtml'];
          $sdcardorder_html = str_replace('{SITE_URL}', $data['site_url'], $sdcardorder_html);
          $sdcardorder_html = str_replace('{STUDENT_NAME}', ucwords($data['student_name']), $sdcardorder_html);
          $sdcardorder_html = str_replace('{CLASS_NAME}', $data['class_name'], $sdcardorder_html);
          $sdcardorder_html = str_replace('{PRICE}', $data['price'], $sdcardorder_html);
          $sdcardorder_html = str_replace('{ADDRESS}', $data['address'], $sdcardorder_html);
          $sdcardorder_html = str_replace('{DOWNLOAD_PDF}', $data['download_pdf'], $sdcardorder_html);
          $sdcardorder_html = str_replace('{CURRENCY_TYPE}', $data['currency'], $sdcardorder_html);
          return $sdcardorder_html;
    }
  
    /*public function htmltemplateofsubject($data) {
          $packageconfrimation_html = $data['subjecthtml'];
          $packageconfrimation_html = str_replace('{SITE_URL}', $data['site_url'], $packageconfrimation_html);
          $packageconfrimation_html = str_replace('{STUDENT_NAME}', $data['student_name'], $packageconfrimation_html);
          $packageconfrimation_html = str_replace('{BOARD_NAME}', $data['board_name'], $packageconfrimation_html);
          $packageconfrimation_html = str_replace('{CLASS_NAME}',$data['class_name'], $packageconfrimation_html);
          $packageconfrimation_html = str_replace('{DATE}', $data['valid_till'], $packageconfrimation_html);
          $packageconfrimation_html = str_replace('{DOWNLOAD_PDF}', $data['download_pdf'], $packageconfrimation_html);
          return $packageconfrimation_html;
    }*/
    
    public function htmltemplateofwebsite($data) {
        $packageconfrimation_html = $data['website_html'];
        $packageconfrimation_html = str_replace('{SITE_URL}', $data['site_url'], $packageconfrimation_html);
        $packageconfrimation_html = str_replace('{STUDENT_NAME}', ucwords($data['student_name']), $packageconfrimation_html);
        $packageconfrimation_html = str_replace('{DOWNLOAD_PDF}', $data['download_pdf'], $packageconfrimation_html);
        $packageconfrimation_html = str_replace('{PACKAGE_DETAILS}', $data['package_details'], $packageconfrimation_html);
        return $packageconfrimation_html;
    }
    
    public function htmltemplateofwebsitecod($data) {
        $packageconfrimation_html = $data['website_html'];
        $packageconfrimation_html = str_replace('{SITE_URL}', $data['site_url'], $packageconfrimation_html);
        $packageconfrimation_html = str_replace('{STUDENT_NAME}', ucwords($data['student_name']), $packageconfrimation_html);
        $packageconfrimation_html = str_replace('{DOWNLOAD_PDF}', $data['download_pdf'], $packageconfrimation_html);
        $packageconfrimation_html = str_replace('{PACKAGE_DETAILS}', $data['package_details'], $packageconfrimation_html);
        $packageconfrimation_html = str_replace('{ADDRESS}', $data['address'], $packageconfrimation_html);
        $packageconfrimation_html = str_replace('{CURRENCY_TYPE}', $data['currency'], $packageconfrimation_html);
        $packageconfrimation_html = str_replace('{REQUIRED_AMOUNT}', $data['required_amount'], $packageconfrimation_html);
        return $packageconfrimation_html;
    }
    
    public function smssendprocess($data) {
        $to_mobile_number = $data['to_mobile_number'];
        $msg_txt = $data['msg_txt'];
        $user_id = $data['user_id'];
        $mobile_number = $data['mobile_number'];
        $sms_type = $data['sms_type'];
        $urltohit = "http://bulkpush.mytoday.com/BulkSms/SingleMsgApi?feedid=347144&username=9811816077&password=agmtj&To=".$to_mobile_number."&Text=".urlencode($msg_txt); 
        $datas = file_get_contents($urltohit);
        $data = array('user_id'=>$user_id,'mobile_number'=>$mobile_number,'sms_text'=>$msg_txt,'api_response'=>$datas,'sms_type'=>$sms_type);
        return $data;                
    }
    
    public function getContainerRackId($boardRackIds,$classId)
    {
        $strboard = '';
        foreach($boardRackIds as $key => $rackId) {
            if($key > 0){
                $strboard .= ",'".$rackId['rackId']."'";
            } else {
                $strboard .= "'".$rackId['rackId']."'";
            }
        }    
        $entity = $this->options->getresourcerackEntityClass();
        $query  = $this->em->
                    createQuery("select a from $entity a where a.rackContainerId IN($strboard) AND a.rackId='$classId' ");
       // echo $query->getSQL(); exit;
        $result = $query->getResult();
        $custom_rack_id = '';
        foreach($result as $customboards)
        {
            $rackContainerId = $customboards->getRackContainerId();
            return $rackContainerId;
            //echo $rackContainerId; exit;
            //echo '<pre>'; print_r($customboards); exit;
            //$custom_rack_id = $customboards->getCustomBoardRackId();
        }
        return 0;
        //return $custom_rack_id;
    }
  
}/*End Of Class*/

?>