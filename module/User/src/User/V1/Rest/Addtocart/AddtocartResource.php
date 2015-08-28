<?php
namespace User\V1\Rest\Addtocart;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

class AddtocartResource extends AbstractResourceListener
{


    protected $service;

    public function __construct($service){
        $this->service = $service;
    }



    
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        //print_r($data); die;
        $userId=$data->user_id;
        $cartInfo=$data->cartinfo;
        if(array_key_exists('cart',$data->cartinfo)){
            $oldCartArray=$data->cartinfo['cart'];
        }else{
            $returnArray['status']=0;
            $returnArray['message']="Invalid format of json. 'cart' array is not";
            $returnArray['content']="";
            return $returnArray;
        }


        if(array_key_exists('add',$data->cartinfo)){
            $newPackageArray=$data->cartinfo['add'];
        }else{
            $returnArray['status']=0;
            $returnArray['message']="Invalid format of json. 'add' index is not present";
            $returnArray['content']="";
            return $returnArray;
        }
        
        if(array_key_exists('remove',$data->cartinfo)){
            $delPackageArray=$data->cartinfo['remove'];
        }else{
            $returnArray['status']=0;
            $returnArray['message']="Invalid format of json. 'remove' index not present";
            $returnArray['content']="";
            return $returnArray;
        }
        
        

        //Check is this is valid json or not
        


        //Check if this user is valid or not
        $table = $this->service->get('Assessment\Model\UserTable');
        $user_details = $table->getuserdetailsById($userId);
        $emailAddress=@$user_details->current()->emailId;
        if($emailAddress!=''){
        /****************Removed Package into the cart Ends here**************/

        foreach($delPackageArray['package_id'] as $delpackageId){
            $tobeRemovedPckId[]=$delpackageId;
        }
        
        foreach($tobeRemovedPckId as $remPackgId){
            $key=$this->removePackageFromCart($oldCartArray,$remPackgId);
            //print_r($oldCartArray);
            unset($oldCartArray[$key]);
        }

        /****************Removed Package into the cart Ends here**************/





        /****************Add Package into the cart Start here**************/
        $updateCartArray=$oldCartArray;
        if(!empty($newPackageArray)){
            foreach($newPackageArray as $pckgData){
                //check If this packge is alredy added into cart
                if($this->isNotPresent($updateCartArray,$pckgData)){
                    array_push($updateCartArray,$pckgData);
                }
            }
        }
        /****************Add Package into the cart Ends here**************/
        $returnArray['status']=1;
        $returnArray['message']="sucess";
        $returnArray['content']=$updateCartArray;
        //print_r($updateCartArray); die;
        }else{
            $returnArray['status']=0;
            $returnArray['message']="Invalid User Id";
            $returnArray['content']="";
        }
        return $returnArray;
        //return new ApiProblem(405, 'The POST method has not been defined');
    }


    /*
     * Author: Pradeep Kumar
     * Description: To find out the package presnt into the cart not
     * Creted Date: 20 May 2015
     * @pass, package array
     * @return True if this package is not presnet into the cart
     * @return Flase, If this package is presnt into the cart 
     */
    private function isNotPresent($updateCartArray,$pckgData){
       $return=true;
       foreach($updateCartArray as $key => $product)
       {
          if ( $product['package_id'] == $pckgData['package_id']){
                $return=false;
          }
       }
       return $return;
    }







    /*
     *  Author: Pradeep Kumar
     *  Description: To remove package form cart
     *  Created Date: 21 May 2015
     *  @pass, package id array to be removed form the cart
     *  @return the new final cart array
     */
    private function removePackageFromCart($oldCart,$pckgId){
        $tempCartArray=$oldCart;
        $key=$this->getIndex($oldCart,'package_id',$pckgId);
        return $key;
    }



    private function getIndex($products, $field, $value)
    {
       foreach($products as $key => $product)
       {
          if ( $product[$field] == $value ){
                return $key;
             }
       }
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
