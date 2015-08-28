<?php
namespace User\V1\Rest\Subscribe;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

class SubscribeResource extends AbstractResourceListener
{
    protected $service;

    /*
     * Product Name
     * @This is name which is manage by the admin
     */
    public $productName='subscribe';


    public function __construct($service){
        $this->service = $service;
    }




    /*
     * Author: Pradeep Kumar
     * Description: This is API Service that provide the validtaion of the API
     * with the product name, if the product name is not valid then API will be not work
     * 
     */
    public function getApiService() {
        $this->apiService = $this->service->get('api_service');
        return $this->apiService;
    }





    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        return new ApiProblem(405, 'The POST method has not been defined');
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }


     private function stripsqlinjection($string){
        $strstriptags=  strip_tags($string);
        $string=str_replace("/", "",str_replace("'", "",$strstriptags));
        return $string;
    }


    
    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     * @Referance: Module/Package/Src/Controller/IndexController.php :: "getpackagedetailsAction()"
     */
    public function fetchAll($params = array())
    {

          //Validate Api Key and Salt
        $apiKey=$params->get('apikey');
        $apiSalt=$params->get('salt');
        //Call Service to validate the api key with salt and product 
        $result=$this->getApiService()->isValidApiSalt($apiKey,$apiSalt,$this->productName,$params);
        if(!empty($result)){
            return $result;
        }




        $userId=$params->get('user_id');
        if($params->get('board_id')!=''){
            $board_id=$params->get('board_id');
        }else{
            $board_id="";
        }
        if($params->get('class_id')!=''){
            $class_id=$params->get('class_id');
        }else{
            $class_id="";
        }
        $_SESSION['currencyType']='';
        //unset($_SESSION['currencyType']);
        $finalArray=array();
        if($userId!=''){
            $table = $this->service->get('Assessment\Model\UserTable');
            $user_details = $table->getuserdetailsById($userId);
            if($board_id==""){
                $currentboardid=$user_details->current()->boardId;
            }else{
                $currentboardid=$board_id;
            }


            if($class_id==''){
                $currentclassid=$user_details->current()->classId;
            }else{
                $currentclassid=$class_id;
            }

            
            $className=$user_details->current()->className;
            $board_name=$user_details->current()->boardName;
            $pcat=$package_category='1';
            $type=1;
            if ($type == '2') {
                $packagetype = 'sdcard';
            } elseif ($type == '3') {
                $packagetype = 'tablet';
            } else {
                $packagetype = 'study';
            }

            $packagedetailsObj = $this->service->get('package_service')->getPackagesListForCustomers($this->stripsqlinjection($currentboardid), $this->stripsqlinjection($currentclassid), $this->stripsqlinjection($packagetype), $this->stripsqlinjection($pcat));
            //print_r($packagedetailsObj); die;
            foreach ($packagedetailsObj as $packagedetails){
                //echo "<pre>";
                //print_r(get_class_methods($packagedetails));
                            $PackageId=$packagedetails->getPackageId();
                            $valid_date = $packagedetails->getValidDate();
                            if ($valid_date->format('Y') != '-0001')
                                $valid_date = date('Y-m-d', $valid_date->getTimeStamp());
                            else
                                $valid_date = '';
                            $flag='off';
                            $pricemark='on';
                            if($_SESSION['currencyType'] === "USD"){
                                if($packagedetails->getIsUsdSaved()==='yes' && $packagedetails->getCurrency()->getIsOffer()==='yes'){
                                   $offertext= $packagedetails->getCurrency()->getOfferQuote();
                                   $original_price= $packagedetails->getCurrency()->getOriginalPrice();
                                   $price= $packagedetails->getCurrency()->getPrice();
                                   $original_priceConverted= round($original_price,2);
                                   $priceConverted= round($price,2);
                                   $flag='on';
                                }else if($packagedetails->getIsUsdSaved()==='yes' && ($packagedetails->getCurrency()->getIsOffer()==='no' || $packagedetails->getCurrency()->getIsOffer()=='')){
                                   $price= $packagedetails->getCurrency()->getPrice();
                                   $priceConverted= round($price,2);
                                }elseif(($packagedetails->getIsUsdSaved()==='no' || $packagedetails->getIsUsdSaved()=='') && $packagedetails->getisOffer() === 'yes'){
                                   $offertext=$packagedetails->getofferQuote();
                                   $original_price= $packagedetails->getOriginalPrice()/$this->conversionRate;
                                   $price= $packagedetails->getPrice()/$this->conversionRate;
                                   $original_priceConverted= round($original_price,2);
                                   $priceConverted= round($price,2);
                                   $flag='on';
                                }else{
                                   $price= $packagedetails->getPrice()/$this->conversionRate;
                                   $priceConverted= round($price,2);
                                }
                                    $currency_symbol = '$';
                                    $currency_css_class = 'dollar-sign';
                                    $pricemark='off';
                            }elseif($_SESSION['currencyType'] === "SGD"){
                                if($packagedetails->getIsSgdSaved()==='yes' && $packagedetails->getCurrency()->getIsOffer()==='yes'){
                                   $offertext= $packagedetails->getCurrency()->getOfferQuote();
                                   $original_price= $packagedetails->getCurrency()->getOriginalPrice();
                                   $price= $packagedetails->getCurrency()->getPrice();
                                   $original_priceConverted= round($original_price,2);
                                   $priceConverted= round($price,2);
                                   $flag='on';
                                }else if($packagedetails->getIsSgdSaved()==='yes' && ($packagedetails->getCurrency()->getIsOffer()==='no' || $packagedetails->getCurrency()->getIsOffer()=='')){
                                   $price= $packagedetails->getCurrency()->getPrice();
                                   $priceConverted= round($price,2);
                                }elseif(($packagedetails->getIsSgdSaved()==='no' || $packagedetails->getIsSgdSaved()=='') && $packagedetails->getisOffer() === 'yes'){
                                   $offertext=$packagedetails->getofferQuote();
                                   $original_price= $packagedetails->getOriginalPrice()/$this->conversionRate;
                                   $price= $packagedetails->getPrice()/$this->conversionRate;
                                   $original_priceConverted= round($original_price,2);
                                   $priceConverted= round($price,2);
                                   $flag='on';
                                }else{
                                   $price= $packagedetails->getPrice()/$this->conversionRate;
                                   $priceConverted= round($price,2);
                                }
                                    $currency_symbol = 'S$';
                                    $currency_css_class = 'dollar-sign';
                                    $pricemark='off';
                            }else{
                                if($packagedetails->getisOffer() === 'yes'){
                                    $offertext=$packagedetails->getofferQuote();
                                    $original_price = $packagedetails->getOriginalPrice();
                                    $price = $packagedetails->getPrice();
                                    $original_priceConverted= round($original_price,0);
                                    $priceConverted= round($price,0);
                                    $flag='on';
                                }else{
                                    $price = round($packagedetails->getPrice(),0);
                                    $priceConverted= round($price,0);
                                }
                                    $currency_symbol = 'Rs.';
                                    $currency_css_class = 'WebRupee';
                            }


                            //final Array for Json
                            $packageArray['PackageTitle']=$packagedetails->getPackageName();
                            
                            $packageArray['CurrencySymbol']=$currency_symbol;
                            $packageArray['Original_price']=round(@$original_priceConverted,2);
                            $packageArray['Offer_price']=round($priceConverted,2);
                            $packageArray['save_price']=round(@$original_priceConverted - $priceConverted,2);
                            $packageArray['info']=$this->getPackageInformation($PackageId,$userId);
                            $packageArray['content']=$this->getSubjectAndChapterList($currentclassid);
                            $finalArray[]=$packageArray;
                            $packageArray='';
            }


            //echo "<pre />"; print_r($finalArray);die;
            //die;
            //$email_id =$user_details->current()->emailId;
           
            $returnArray['status']=1;
            $returnArray['message']="Success";
            $returnArray['content']=$finalArray;
            return $returnArray;
        }else{
            $returnArray['status']=0;
            $returnArray['message']="User Id is missing into the parameters.";
            return $returnArray;
        }
        return new ApiProblem(405, 'The GET method has not been defined for collections');
    }




    /*
     * Author: Pradeep Kumar
     * Description:: Get Package Information on the basis of package Id
     * @parameter: packageId
     * @return Array
     * @Created Date: 14 MaY 2015 12:44 PM
     * @Referance: Module/Package/Src/Controller/IndexController.php :: "getpackagedetailsAction()"
     * Line No:: 329
     */
    private function getPackageInformation($package_id,$user_id){
        $packageId=$package_id;
        if($packageId!=''){
            $packageid = $packageId;
            $_SESSION['viewed_package_id']=$packageid;
            $packageclasstable = $this->service->get('Package\Model\TpackageclassesTable');


            $comMapperObj  = $this->service->get("com_mapper");
            $countryData   = $comMapperObj->getAllCountries();

            $currentPackageObj = $this->service->get('package_service')->getPackageById($packageid);
  
            $packagesyllabitable = $this->service->get('Package\Model\TpackagesyllabusTable');

            $cursubject_id = $packagesyllabitable->getsubjectids($packageid);
            $subject_ids = array_values(iterator_to_array($cursubject_id));
            $countsubjectid = count($subject_ids) ? count(explode(',', $subject_ids[0]->syllabus_id)) : 0;
            $subjectdetails = array();
            $currentPackage="";
            foreach($currentPackageObj as $key=>$val){
               $currentPackage=$val;
            }

            $subjectdetails = $this->service->get('lms_container_service')->getChildList($currentPackage->getClassId());
            $nextclassname='';


            if (isset($_SESSION['pkg_cat']) && $_SESSION['pkg_cat'] == 'cp') {
                $nextclassList = $packageclasstable->getnextClass($packageid);
                $classList = $this->service->get('lms_container_service')->getContainerList($nextclassList->current()->class_id);
                foreach ($classList as $class) {

                    $nextclassname = $class->getRackName()->getName(); //die;
                }
            }


            $conversionRate = '';
            if($_SESSION['currencyType'] != 'INR' && $_SESSION['currencyType']!=''){
                $lmsContainerSerObj = $this->service->get('lms_container_service');
                $conversionRate = $lmsContainerSerObj->getCurrenctConversionResult($_SESSION['currencyType']);
            }
            $childcount = '0';


            $finalArray=array(
                        'current_package' => $currentPackage,
                        'conversionRate' => $conversionRate,
                        'valid_date' => $currentPackage->getValidDate(),
                        'display_validity_date' => $currentPackage->getDisplayValidityDate(),
                        'countsubjectid' => $countsubjectid,
                        'allow_subject_count'=>$currentPackage->getAllowedSubjectCount(),
                        'nextclassname'=>$nextclassname,
                        'childcount'=>$childcount,
                );


        return $finalArray;
        }else{
            return array();
        }
    }


    /*
     * Auhtor:: Pradeep Kumar
     * Description: Get All the Child of the Parent
     * @parameters: Paass Parent Subject Id
     * @return :: Array of the Chapter List
     */
    private function getChildList($parentId){
        $serObj=$this->service->get('lms_container_service');
        $chapterNameDetails=$serObj->getChildList($parentId);
        foreach ($chapterNameDetails as $chapter) {
                $hierarchy = $chapter->getRackType()->getTypeName();
                if ($hierarchy == 'sub subject') {
                    $chapterinfo = $chapter->getChild();
                    foreach ($chapterinfo as $chp) {
                          $chapterList[]=html_entity_decode($chp->getRackName()->getName());
                    }

                }else{
                    $chapterList[]=html_entity_decode($chapter->getRackName()->getName());
                }
        }

        return $chapterList;
    }



    /*
     * Author:: Pradeep Kumar
     * Description:: Get All Subject and Chapter List
     * Created: 14 May 2015 6:12 PM
     * @parameter: parentId
     * @return: subject with Chapter Name
     */
    private function getSubjectAndChapterList($parentId){
        $subjectdetails = $this->service->get('lms_container_service')->getChildList($parentId);
         if (isset($subjectdetails)) {
                 foreach ($subjectdetails as $key => $subject) {
                      $finalArray[]=array(
                          'subject'=>html_entity_decode($subject->getRackName()->getName()),
                              'chapter'=>$this->getChildList($subject->getRackId())
                  );
                 }
              }
           return $finalArray;
        
    }






    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
