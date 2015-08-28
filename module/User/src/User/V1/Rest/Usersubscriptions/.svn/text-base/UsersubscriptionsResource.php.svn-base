<?php
namespace User\V1\Rest\Usersubscriptions;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

class UsersubscriptionsResource extends AbstractResourceListener
{



    protected $service;



    /*
     * Product Name
     * @This is name which is manage by the admin
     */
    public $productName='usersubscriptions';




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

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
        //$start=0;
        //$offset=0;
        $packagesArray=array();
        $userId=$params->get('user_id');
        $start=$params->get('start');

        //Validate Api Key and Salt
        $apiKey=$params->get('apikey');
        $apiSalt=$params->get('salt');
        //Call Service to validate the api key with salt and product
        $result=$this->getApiService()->isValidApiSalt($apiKey,$apiSalt,$this->productName);
        if(!empty($result)){
        return $result;
        }




        $offset=$params->get('offset');
        if(!is_numeric($userId)){
            $returnArray['status']=0;
            $returnArray['message']='Invalid User Id, User Id should be integer';
            return $returnArray;
        }else{
             $userTable = $this->service->get('Assessment\Model\UserTable');
             $userDetails = $userTable->UserMinDetailByID($userId);
             if(empty($userDetails)){
                $returnArray['status']=0;
                $returnArray['message']="User Doesn't exists, please provide valid user id.";
                return $returnArray;

             }
        }

        if(!is_numeric($start)){
            $returnArray['status']=0;
            $returnArray['message']='Invalid Start value, start value should be integer';
            return $returnArray;
        }

        if(!is_numeric($offset)){
            $returnArray['status']=0;
            $returnArray['message']='Invalid Offset value, Offset should be integer';
            return $returnArray;
        }

        if($offset>100){
            $returnArray['status']=0;
            $returnArray['message']='too much data requested, please set offset value less then 100';
            return $returnArray;
        }

        /*  If active=1,then active records
         *  If acticv=2, then all records
         *  If acticv=0, then inactive
         */
        $active=$params->get('active');
        if($active==1){
             $all_active_inactive='active';
        }elseif($active==2){
            $all_active_inactive='all_active_inactive';
        }else{
            $all_active_inactive='all';
        }

        

        $table = $this->service->get('Assessment\Model\TuserpackageTable');
        $packageclasstable = $this->service->get('Package\Model\TpackageclassesTable');
        $lmsService = $this->service->get('lms_container_service');
        // This function get the all subscribed packages of loged user.
        $packagess = $table->getPackages($userId,'one',(int)$offset, $all_active_inactive,(int)$start);
        $pDate = date('Y-m-d H:i:s');
        $i=0;
	$packagesArray=array();
        foreach ($packagess as $key => $packages) {
            $packagesArray[]=array(
            	'user_package_id'=>$packages->user_package_id,
            	'valid_till'=>$packages->valid_till,
            	'valid_from'=>$packages->valid_from,
                'package_id'=>$packages->package_id,
                'package_category'=>$packages->package_category,
                'transaction_id'=>$packages->transaction_id,
                'order_id'=>$packages->order_id,
                'price'=>$packages->price,
                'currency_type'=>$packages->currency_type,
                'package_name'=>$packages->package_name,
                'purchase_date'=>$packages->purchase_date,
                'syllabus_id' =>$packages->syllabus_id,
                'subject_name' =>$packages->subject_name,
                'parent_subject_name'=>$packages->parent_subject_name,
                'subject_language'=>$packages->subject_language,
                'parent_subject_language' =>$packages->parent_subject_language,
                'board_class_subject_id' =>$packages->board_class_subject_id,
                'discount_amount' =>$packages->discount_amount,
                'board_class_parent_subject_id' =>$packages->board_class_parent_subject_id,
                'first_name' =>$packages->first_name,
                'email' =>$packages->email,
                'purchaser_id' =>$packages->purchaser_id,
                'purchaser' =>$packages->purchaser,
                'package_id' =>$packages->package_id,
                'package_type' =>$packages->package_type,
                'package_syllabi_id' =>$packages->package_syllabi_id,
                'actual_package_id' =>$packages->actual_package_id,
                'days' =>$packages->days,
                'unit_price' =>$packages->unit_price,
                'tax' =>$packages->tax,
                'package_image' =>$packages->package_image,
                'allowed_subject_count' =>$packages->allowed_subject_count,
                'display_validity_date' =>$packages->display_validity_date,
                'subject_added' =>$packages->subject_added,
                'is_switched' =>$packages->is_switched,
                'pkg_payment_type' =>$packages->pkg_payment_type,
                'paid_amount' =>$packages->paid_amount,
                'status' =>$packages->status,
                'transaction_status' =>$packages->transaction_status,
                'display_name' =>$packages->display_name,
                'package_price' =>$packages->package_price,
                'invoice_number' =>$packages->invoice_number,
                'payment_mode_offline' =>$packages->payment_mode_offline,
                'transaction_product_type' =>$packages->transaction_product_type,
                'coupon_id' =>$packages->coupon_id,
                'transaction_discount' =>$packages->transaction_discount,
                'board' => $packages->board,
                'class' =>$packages->class,
                'board_id' => $packages->board_id,
                'class_id' => $packages->class_id,
        );
        $sm = $this->service;
        $packagetableGateway = $sm->get('Package\Model\TpackageTable');
        $packagedetails = $packagetableGateway->getPackageById($packages->actual_package_id,$packages->package_syllabi_id);

        $subjectNameDetails='';
	$finalSubContent=array();
        foreach ($packagedetails as $package) {
                $subjectIds = '';
                $explode = explode(',', $package->class_id);
                foreach ($explode as $val) {
                    $subjectIds .= "" . $val . "" . ",";
                }
                $subject_id_array = explode(',', trim($subjectIds, ','));
                $subjectNameDetails = $lmsService->getContainerList($subject_id_array);

        }
        foreach($subjectNameDetails as $subject){
            $chapterNameDetails = $lmsService->getChildList($subject->getRackId());
            $j=0;
            foreach ($chapterNameDetails as $chapter) {
               $hierarchy = $chapter->getRackType()->getTypeName();
               //Get Sub subject Or Chapter List from Subject Id
               $finalChapterNames=$this->getChapterList($chapter->getRackId());
               
               // $finalChapterNames = '';
                $finalSubContent[]=array(
                    'subject'=>html_entity_decode($chapter->getRackName()->getName()),
                    'chapters'=>$finalChapterNames
                );
                

            $j++;
        }
        }
        $finalChapterNames=array();
	$subjectNameDetails=array();
         $packagesArray[$i]=array_merge($packagesArray[$i],array('content'=>$finalSubContent));
	 $finalSubContent='';
         $i++;
        }
        $returnArray['status']=1;
        $returnArray['message']='success';
        $returnArray['page']=array('start'=>$start,'offset'=>$offset);
        $returnArray['content']=$packagesArray;
        return $returnArray;
        return new ApiProblem(405, 'The GET method has not been defined for collections');
    }




    /*
     * Author:: Pradeep Kumar
     * Description: To find Out all the chapter of the subject that subscribe by the user
     * @pass. subject Object
     */
    public function getChapterList($subjectID){
        $lmsService = $this->service->get('lms_container_service');
        $subjectListArray = $lmsService->getChildList($subjectID);
        $finalChapterNames=array();
        foreach ($subjectListArray as $chapterName) {
            $finalChapterNames[] = html_entity_decode($chapterName->getRackName()->getName());
        }
        return $finalChapterNames;
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
