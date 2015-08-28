<?php

namespace ZfcUserV0\Form;

use ZfcBase\InputFilter\ProvidesEventsInputFilter;
use ZfcUser\Module as ZfcUser;
use ZfcUser\Options\RegistrationOptionsInterface;

class RegisterFilter extends ProvidesEventsInputFilter
{
    protected $emailValidator;
    protected $usernameValidator;

    /**
     * @var RegistrationOptionsInterface
     */
    protected $options;

    public function __construct($emailValidator, $usernameValidator, RegistrationOptionsInterface $options)
    {
        $this->setOptions($options);
        $this->emailValidator = $emailValidator;
        $this->usernameValidator = $usernameValidator;

//        if ($this->getOptions()->getEnableUsername()) {
//            $this->add(array(
//                'name'       => 'username',
//                'required'   => true,
//                'validators' => array(
//                    array(
//                        'name'    => 'StringLength',
//                        'options' => array(
//                            'min' => 3,
//                            'max' => 255,
//                        ),
//                    ),
//                    $this->usernameValidator,
//                ),
//            ));
//        }
        
//        $this->add(array(
//            'name'       => 'display_name',
//            'required'   => true,
//            'validators' => array(
//                array(
//                    'name'    => 'StringLength',
//                    'options' => array(
//                        'min' => 3,
//                        'max' => 255,
//                    ),
//                ),
//                $this->usernameValidator,
//            ),
//        ));
        
        
        $this->add(array(
            'name'       => 'user_type_id',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                             'isEmpty' => 'Please select your role',
                         )
                    ),
                ),
                array(
                    'name' => 'Callback',
                    'options' => array(
                        'messages' => array(
                                \Zend\Validator\Callback::INVALID_VALUE => 'Please verify your age below',
                        ),
                        'callback' => function($value, $context = array()) {
                            if($value == '1' && !isset($context['age_bar']))
                                return false;
                            return true;
                        },
                    ),
                ), 
            ),
        ));
        
        
        $this->add(array(
            'name'       => 'gender',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                             'isEmpty' => 'Please select gender',
                         ),
                    ),
                    'break_chain_on_failure' => true,
                ),
            ),
        ));
        
        $this->add(array(
            'name'       => 'age_bar',
            'required' => false,
            'validators' => array(),
        ));
        
        $this->add(array(
            'name'       => 'email',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                             'isEmpty' => 'Please enter your email address'
                         )
                    ),
                    'break_chain_on_failure' => true
                ),
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'messages' => array(
                              \Zend\Validator\EmailAddress::INVALID_FORMAT => 'Please enter valid email address'
                         )
                    ),
                    'break_chain_on_failure' => true
                ),
                $this->emailValidator
            ),
        ));
        
//        $this->add(array(
//            'name'       => 'mobile',
//            'required'   => false,
//            'validators' => array(
////                array(
////                    'name'    => 'NotEmpty',
////                    'options' => array(
////                        'messages' => array(
////                             'isEmpty' => 'Please enter your mobile number'
////                         )
////                    ),
////                    'break_chain_on_failure' => true
////                ),
//                array(
//                    'name'    => 'Int',
//                    'options' => array(
//                        'min' => 10,
//                        'max' => 10,
//                        'messages' => array(
//                             'notInt' => ' Please enter a valid mobile number'
//                         )
//                    ),
//                    'break_chain_on_failure' => true
//                ),
//                array(
//                    'name'    => 'StringLength',
//                    'options' => array(
//                        'min' => 10,
//                        'max' => 10,
//                        'messages' => array(
//                             'stringLengthTooShort' => 'Mobile Number should be of 10 integers',
//                             'stringLengthTooLong' => 'Mobile Number should be of 10 integers'
//                            )
//                         ),
//                    'break_chain_on_failure' => true
//                ),
//                
//            ),
//        ));
        
        $this->add(array(
            'name'       => 'display_name',
            'required'   => true,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                             'isEmpty' => 'Please enter your name'
                         )
                         ),
                    'break_chain_on_failure' => true
                    ),
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 120,
                        'messages' => array(
                             'stringLengthTooShort' => 'Name should be 3 to 120 characters',
                             'stringLengthTooLong' => 'Name should be 3 to 120 characters'
                         )
                     ),
                    'break_chain_on_failure' => true
                    ),
                array(
                    'name'    => 'Regex',
                    'options' => array(
                        'pattern' => "/^[a-zA-Z][a-zA-Z ]{2,120}$/",
                        'messages' => array(
                            'regexNotMatch' => 'Name should only have characters',
                         ),
                     ),
                    'break_chain_on_failure' => true
                    ),
                ),
        ));
        
        $this->add(array(
            'name'       => 'school_name',
            'required'   => true,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name'    => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                             'isEmpty' => 'Please enter school name'
                         )
                         ),
                    'break_chain_on_failure' => true
                    ),
                
                ),
        ));
      
//        $this->add(array(
//            'name'       => 'password',
//            'required'   => true,
//            'filters'    => array(array('name' => 'StringTrim')),
//            'validators' => array(
//                array(
//                    'name'    => 'StringLength',
//                    'options' => array(
//                        'min' => 6,
//                    ),
//                ),
//            ),
//        ));

//        $this->add(array(
//            'name'       => 'passwordVerify',
//            'required'   => true,
//            'filters'    => array(array('name' => 'StringTrim')),
//            'validators' => array(
//                array(
//                    'name'    => 'StringLength',
//                    'options' => array(
//                        'min' => 6,
//                    ),
//                ),
//                array(
//                    'name'    => 'Identical',
//                    'options' => array(
//                        'token' => 'password',
//                    ),
//                ),
//            ),
//        ));

        $this->getEventManager()->trigger('init', $this);
    }

    public function getEmailValidator()
    {
        return $this->emailValidator;
    }

    public function setEmailValidator($emailValidator)
    {
        $this->emailValidator = $emailValidator;
        return $this;
    }

    public function getUsernameValidator()
    {
        return $this->usernameValidator;
    }

    public function setUsernameValidator($usernameValidator)
    {
        $this->usernameValidator = $usernameValidator;
        return $this;
    }

    /**
     * set options
     *
     * @param RegistrationOptionsInterface $options
     */
    public function setOptions(RegistrationOptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * get options
     *
     * @return RegistrationOptionsInterface
     */
    public function getOptions()
    {
        return $this->options;
    }
}
