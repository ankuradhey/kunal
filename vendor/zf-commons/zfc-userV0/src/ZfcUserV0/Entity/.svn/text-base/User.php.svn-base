<?php

namespace ZfcUserV0\Entity;
use Common\Entity\Country as Country;
use Common\Entity\State as State;
use Lms\Entity\ResourceRack as ResourceRack;
class User implements UserInterface {

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $postalcode;

    /**
     * @var string
     */
    protected $otherCity;

    /**
     * @var string
     */
    protected $displayName;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $gender;

    /**
     * @var int
     */
    protected $userTypeId;

    /**
     * @var string
     */
    protected $phone;
    protected $mobile;

    /**
     * @var string
     */
    protected $country;
    protected $countryId;
    protected $stateId;
    protected $board;
    protected $class;
    protected $boardId;
    protected $classId;
    protected $userPhoto;
    protected $dob;
    protected $schoolName;
    protected $schoolId;
    protected $productType;
    protected $parentId;
    protected $age;
    protected $address;
    protected $ip;
    protected $subscribeMe;
    protected $newsletter;
    protected $validEmail;
    
    protected $createTime;
    protected $allowschedule;
    
    public function __construct() {
        date_default_timezone_set('Asia/Kolkata');
        $this->createTime = new \DateTime();
    }
    
    /**
     * Get id.
     * protected $country;
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param int $id
     * @return UserInterface
     */
    public function setId($id) {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set username.
     *
     * @param string $username
     * @return UserInterface
     */
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function getPostalCode() {
        return $this->postalcode;
    }

    /**
     * @param string $username
     * @return UserInterface
     */
    public function setPostalCode($postalcode) {
        $this->postalcode = $postalcode;
        return $this;
    }

    public function getCountry() {
        return $this->country;
    }

    /*
     * @return UserInterface
     */

    public function setCountry(Country $countryObj) {
        $this->country = $countryObj;
        return $this;
    }
    
    
    public function getCountryId() {
        return $this->countryId;
    }
//
    public function setCountryId($countryId) {
        $this->countryId = $countryId;
        return $this;
    }
    
    
    public function getStateId() {
        return $this->stateId;
    }
    
    public function setStateId($stateId) {
        $this->stateId = $stateId;
        return $this;
    }
    
    public function getOtherCity() {
        return $this->otherCity;
    }

    /**
     * Set username.
     *
     * @param string $username
     * @return UserInterface
     */
    public function setOtherCity($otherCity) {
        $this->otherCity = $otherCity;
        return $this;
    }

    /**
     * Get email.
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     * @return UserInterface
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * Get displayName.
     *
     * @return string
     */
    public function getDisplayName() {
        return $this->displayName;
    }

    /**
     * Set displayName.
     *
     * @param string $displayName
     * @return UserInterface
     */
    public function setDisplayName($displayName) {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set password.
     *
     * @param string $password
     * @return UserInterface
     */
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * Get state.
     *
     * @return int
     */
    public function getState() {
        return $this->state;
    }

    /**
     * Set state.
     *
     * @param int $state
     * @return UserInterface
     */
    public function setState($state) {
        $this->state = $state;
        return $this;
    }

    /**
     * Get gender.
     *
     * @return string
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Set gender.
     *
     * @param string $gender
     * @return UserInterface
     */
    public function setGender($gender) {
        $this->gender = $gender;
        return $this;
    }

    /**
     * Get userTypeId.
     *
     * @return int
     */
    public function getUserTypeId() {
        return $this->userTypeId;
    }

    /**
     * Set userTypeId.
     *
     * @param int $userTypeId
     * @return UserInterface
     */
    public function setUserTypeId($userTypeId) {
        $this->userTypeId = $userTypeId;
        return $this;
    }

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Set phone.
     *
     * @param string $phone
     * @return UserInterface
     */
    public function setPhone($phone) {
        $this->phone = $phone;
        return $this;
    }
    
    
    public function getMobile() {
        return $this->mobile;
    }

    /**
     * Set phone.
     *
     * @param string $phone
     * @return UserInterface
     */
    public function setMobile($mobile) {
        $this->mobile = $mobile;
        return $this;
    }
    
    
    public function getBoard() {
        return $this->board;
    }

    public function setBoard(ResourceRack $boardObj) {
        $this->board = $boardObj;
        return $this;
    }

    public function getClass() {
        return $this->class;
    }

    public function setClass(ResourceRack $classObj) {
        $this->class = $classObj;
        return $this;
    }
    
    public function getBoardId() {
        return $this->boardId;
    }
    
    public function setBoardId($boardId) {
        $this->boardId = $boardId;
        return $this;
    }
    
    public function getClassId() {
        return $this->classId;
    }
    public function setClassId($classId) {
        $this->classId = $classId;
        return $this;
    }

    public function getUserPhoto() {
        return $this->userPhoto;
    }

    public function setUserPhoto($userPhoto) {
        $this->userPhoto = $userPhoto;
        return $this;
    }
    
    public function getDob() {
        return $this->dob;
    }

    public function setDob($dob) {
        $this->dob = $dob;
        return $this;
    }
    public function getSchoolName() {
        return $this->schoolName;
    }

    public function setSchoolName($schoolName) {
        $this->schoolName = $schoolName;
        return $this;
    }
    public function getSchoolId() {
        return $this->schoolId;
    }

    public function setSchoolId($schoolId) {
        $this->schoolId = $schoolId;
        return $this;
    }
    public function getProductType() {
        return $this->productType;
    }

    public function setProductType($productType) {
        $this->productType = $productType;
        return $this;
    }
    public function getParentId() {
        return $this->parentId;
    }

    public function setParentId($parentId) {
        $this->parentId = $parentId;
        return $this;
    }
    public function getAge() {
        return $this->age;
    }

    public function setAge($age) {
        $this->age = $age;
        return $this;
    }
    
    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
        return $this;
    }
    public function getCreateTime() {
        return $this->createTime;
    }

    public function setCreateTime($createTime) {
        $this->createTime = $createTime;
        return $this;
    }
    public function getIp() {
        return $this->ip;
    }

    public function setIp($ip) {
        $this->ip = $ip;
        return $this;
    }
    public function getSubscribeMe() {
        return $this->subscribeMe;
    }

    public function setSubscribeMe($subscribeMe) {
        $this->subscribeMe = $subscribeMe;
        return $this;
    }
    public function getNewsletter() {
        return $this->newsletter;
    }

    public function setNewsletter($newsletter) {
        $this->newsletter = $newsletter;
        return $this;
    }
    public function getValidEmail() {
        return $this->validEmail;
    }

    public function setValidEmail($validEmail) {
        $this->validEmail = $validEmail;
        return $this;
    }
    public function getAllowschedule() {
        return $this->allowschedule;
    }

    public function setAllowschedule($allowschedule) {
        $this->allowschedule = $allowschedule;
        return $this;
    }

}
