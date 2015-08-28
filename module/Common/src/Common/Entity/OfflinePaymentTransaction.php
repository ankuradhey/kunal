<?php

namespace Common\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="offline_payment_transaction")
 * @ORM\Entity
 */
class OfflinePaymentTransaction {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var string
     *
    * @ORM\Column(name="currency", type="string", length=10)
     
     */
    private $currency;
    
    /**
     * @var integer
     *
    * @ORM\Column(name="user_transaction_id", type="integer", length=11)
     
     */
    private $userTransactionId;
    
    
    /**
     * @var string
     *
    * @ORM\Column(name="payment_mode", type="string", length=50)
     
     */
    private $paymentMode;
    
    /**
     * @var string
     *
    * @ORM\Column(name="dd_cheque_number", type="string", length=100)
     
     */
    private $ddChequeNumber;
    
    /**
     * @var string
     *
    * @ORM\Column(name="dd_cheque_date", type="string")
     
     */
    private $ddChequeDate;
    
    /**
     * @var string
     *
    * @ORM\Column(name="bank", type="string", length=100)
     
     */
    private $bank;
    
    /**
     * @var string
     *
    * @ORM\Column(name="deposit_date", type="string")
     
     */
    
    private $depositDate;
    
    /**
     * @var string
     *
    * @ORM\Column(name="account_number", type="string")
     
     */
    
    private $accountNumber;
    
    
    /**
     * @var string
     *
    * @ORM\Column(name="bank_branch", type="string")
     
     */
    
    private $bankBranch;
    /**
     * @var integer
     *
    * @ORM\Column(name="login_id", type="integer")
     
     */
    
    private $loginId;
    
    /**
     * @var String
     *
    * @ORM\Column(name="payment_source", type="string")
     
     */
    
    private $paymentSource;
    /**
     * @var String
     *
    * @ORM\Column(name="payment_collection_status", type="string")
     
     */
    
    private $paymentCollectionStatus;
    
    /**
     * @var String
     *
    * @ORM\Column(name="payment_update_user_id", type="integer")
     
     */
    
    private $paymentUpdateUserId;
    
    /**
     * @var text
     *
     * @ORM\Column(name="other_payment_details", type="text")
     */
    private $otherPaymentDetails;
    
//    public function __construct() {
//        date_default_timezone_set('Europe/Istanbul');
//        $this->depositDate = new \DateTime();
//        $this->ddChequeDate = new \DateTime();
//    }
    public function setId($id) {
            $this->id = $id;
    }
    public function getId() {
            return $this->id;
    }
    
    public function getCurrency() {
           return  $this->currency;
    }
    public function setCurrency($currency) {
            $this->currency = $currency;
    }
    
    public function getUserTransactionId() {
           return  $this->userTransactionId;
    }
    public function setUserTransactionId($userTransactionId) {
            $this->userTransactionId = $userTransactionId;
    }
    
    public function setPaymentMode($paymentMode) {
            $this->paymentMode = $paymentMode;
    }
    public function getPaymentMode() {
           return  $this->paymentMode;
    }
    public function setDdChequeNumber($ddChequeNumber) {
            $this->ddChequeNumber = $ddChequeNumber;
    }
    public function getDdChequeNumber() {
           return  $this->ddChequeNumber;
    }
    public function setDdChequeDate($ddChequeDate) {
            $this->ddChequeDate= $ddChequeDate;
    }
    public function getDdChequeDate() {
           return  $this->ddChequeDate;
    }
    public function setBank($bank) {
            $this->bank = $bank;
    }
    public function getBank() {
           return  $this->bank;
    }
    
    public function setDepositDate($depositDate) {
            $this->depositDate = $depositDate;
    }
    public function getDepositDate() {
           return  $this->depositDate;
    }
    public function setAccountNumber($accountNumber) {
            $this->accountNumber = $accountNumber;
    }
    public function getAccountNumber() {
           return  $this->accountNumber;
    }
    public function setBankBranch($bankBranch) {
            $this->bankBranch = $bankBranch;
    }
    public function getBankBranch() {
           return  $this->bankBranch;
    }
    public function setLoginId($loginId) {
            $this->loginId = $loginId;
    }
    public function getLoginId() {
           return  $this->loginId;
    }
    public function setPaymentSource($paymentSource) {
            $this->paymentSource = $paymentSource;
    }
    public function getPaymentSource() {
           return  $this->paymentSource;
    }
    public function setPaymentCollectionStatus($paymentCollectionStatus) {
            $this->paymentCollectionStatus = $paymentCollectionStatus;
    }
    public function getPaymentCollectionStatus() {
           return  $this->paymentCollectionStatus;
    }
    public function setPaymentUpdateUserId($paymentUpdateUserId) {
            $this->paymentUpdateUserId = $paymentUpdateUserId;
    }
    public function getPaymentUpdateUserId() {
           return  $this->paymentUpdateUserId;
    }
    
    public function setOtherPaymentDetails($otherPaymentDetails) {
            $this->otherPaymentDetails = $otherPaymentDetails;
    }
    public function getOtherPaymentDetails() {
           return  $this->otherPaymentDetails;
    }

}
