<?php
namespace User\V1\Rest\Profilepicupdate;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

class ProfilepicupdateResource extends AbstractResourceListener
{
   /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    protected $service,$commonObj,$lmsService,$apiService;
    public $productName='profilepicupdate';

    public function __construct($service){
        $this->service = $service;
    }

    public function getCommanServer(){
        $this->commonObj=$this->service->get("com_mapper");
    }
    
    public function getLmsService() {
        if (!$this->service) {
            $this->service = $this->service->get('lms_container_service');
        }
        return $this->service;
    }
     
    
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
        //echo "<pre>";
        $returnArray =array();
        $board="";
        $classnames="";
        if(!empty($data)){
          //Validate the API Key and checksum here
          if(!empty($data->api_details)){
            $apikey=$data->api_details['apikey'];
            //Validate Api Key and Salt
            $apiKey=$apikey;
            $apiSalt="";
            $params['user_id']=$data->user_details['user_id'];
            $params['apikey']=$apiKey;
            //Call Service to validate the api key with salt and product 
            $result=$this->getApiService()->isValidApiSalt($apiKey,$apiSalt,$this->productName,$params);
            if(!empty($result)){
                return $result;
            }
            
            
            
          }  
          if($data->action=='updateprofilepic'){
                    $user_id=$data->user_details['user_id'];
                    // $userDisplayName=$data->user_details['user_display_name'];
                    // $board=$data->user_details['board_id'];
                    // $classnames=$data->user_details['class_id'];
                    
                    
                    $table = $this->service->get('Assessment\Model\UserTable');
                    $user_details  = $table->getuserdetails($user_id);
                    $user_info     = $user_details->current();
                    
                    
                    //Upload Image
                    $data=base64_decode($data->user_details['upload_img']);
                    $im = imagecreatefromstring($data);
                    $imgName=$user_id.".jpg";
                    //$path='/uploads/'.$imgName;
                    $event = $this->getEvent();
                    $requestURL = $event->getRequest();
                    $baseUrl=$requestURL->getBaseUrl();
                    $uri=$requestURL->getUri();
                    //echo $siteUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $baseUrl); die;
                    $path=$_SERVER['DOCUMENT_ROOT'].$baseUrl.'/uploads/'.$imgName;
                    $unlinkpath=$_SERVER['DOCUMENT_ROOT'].$baseUrl.'/uploads/profileimages/'.$imgName;
                    if(file_exists($unlinkpath)){
                        unlink($unlinkpath);
                    }
                    imagejpeg($im,$path); 
                    $imageName=$imgName;
                    $uploadedImage=false;
                    if(!empty($data)){
                        $fileUploaded = $this->ftpFileUploaded($path,"/uploads/profileimages/".$imageName);
                        if($fileUploaded=='success'){ 
                                $userId=$user_id;
                                $updateData['user_photo']      = strip_tags($imgName);
                                $res=$table->updateUserAddress($updateData,$userId);
                                //print_r($res); die;
                                if($res){
                                    $uploadedImage=true;
                                }else{
                                    $uploadedImage=true;
                                }
                            unlink($path);
                        }
                    }
                    if($uploadedImage){
                            $user_id="";
                            $siteUrl = sprintf('%s://%s%s', $uri->getScheme(), $uri->getHost(), $baseUrl); 
                            $returnArray['status']=1;
                            $returnArray['message']="User profile Picture is uplaoded succesfully";    
                            $returnArray['url']=$siteUrl.'/uploads/profileimages/'.$imgName;    
                            return $returnArray;
                            
                    }else{
                            $user_id="";
                            $returnArray['status']=0;
                            $returnArray['message']="Failed! User's profle pic is not uplaoded succesfully";    
                            return $returnArray;
                    }
          }else{
                    $user_id="";
                    $returnArray['status']=0;
                    $returnArray['message']="Failed! User Id is missing, Please provide user Id ";
                    return $returnArray;

          }
        }else{
            return new ApiProblem(405, 'The POST method has not been defined');
        }
    }
     
   public function ftpFileUploaded($sourcePath, $targetPath)
  {
     //echo $sourcePath;echo '<pre>';echo $targetPath;die;
     $config     = $this->service->get('config');
     $ftpDetails = $config['ftp_config'];
     $conn_id = ftp_connect($ftpDetails['FTP_SERVER']);       
     $login_result = ftp_login($conn_id, $ftpDetails['FTP_USERNAME'], $ftpDetails['FTP_PASSWORD']); // ftp login     
        if($login_result){
            //echo "+++++".$sourcePath; die;
            //echo $targetPath;die;
            $upload = ftp_put($conn_id, $targetPath, $sourcePath, FTP_BINARY);  // upload the file
            //echo $sourcePath; die;
             if (!$upload) {  // check upload status
                $fileStatus = 'error';
            }else {
                $fileStatus = 'success';
            }
        }else{
            $fileStatus = 'error';
        }       
        ftp_close($conn_id); // close the FTP stream     
        return $fileStatus;
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
        return new ApiProblem(405, 'The GET method has not been defined for collections');
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
