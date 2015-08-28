<?php

namespace ZfcUserV0\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;

class Base extends ProvidesEventsForm
{
    public function __construct()
    {
        parent::__construct();
        
        
//        $radio = new Element\Radio('gender');
//     $radio->setLabel('What is your gender ?');
//     $radio->setValueOptions(array(
//             array(
//                     '0' => 'Female',
//                     '1' => 'Male',
//             )
//     ));
//     $this->add($radio);
     
     
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'user_type_id',
            'options' => array(
                'disable_inarray_validator' => true,
                'label' => 'I am',
                'value_options' => array(
                    'student' => array(
                        'label' => 'Student',
                        'label_attributes' => array('class'=>'css-label' , 'for' => 'usertype_1'),
                        'value' => '1',
                        'attributes' => array(
                            'id' => 'usertype_1',
                        ),
                    ),
                    'parent' => array(
                        'label' => 'Parent',
                        'label_attributes' => array('class'=>'css-label' , 'for' => 'usertype_2'),
                        'value' => '2',
                        'attributes' => array(
                            'id' => 'usertype_2',
                        ),
                    ),
                    'teacher' => array(
                        'label' => 'Teacher',
                        'label_attributes' => array('class'=>'css-label' , 'for' => 'usertype_3'),
                        'value' => '3',
                        'attributes' => array(
                            'id' => 'usertype_3',
                        ),
                    ),
                ),
                
            ),
            'attributes' => array(
//                'id' => 'usertype',
                'class' => 'css-checkbox usertype',
            ),
        ));
        
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'age_bar',
            'options' => array(
                'disable_inarray_validator' => true,
                'label' => 'I certify that I am 13 years of age or older:',
                'value_options' => array(
                    'yes' => array(
                        'label' => 'Yes',
                        'label_attributes' => array('class'=>'css-label' , 'for' => 'agebar_1'),
                        'value' => '0',
                        'attributes' => array(
                            'id' => 'agebar_1',
                        ),
                    ),
                    'no' => array(
                        'label' => 'No',
                        'label_attributes' => array('class'=>'css-label' , 'for' => 'agebar_2'),
                        'value' => '1',
                        'attributes' => array(
                            'id' => 'agebar_2',
                        ),
                    ),
                    
                ),
                
            ),
            'attributes' => array(
                'id' => 'age_bar',
                'class' => 'css-checkbox',
            ),
        ));
        
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'gender',
            'options' => array(
                'disable_inarray_validator' => true,
                'label' => 'Gender',
                'value_options' => array(
                    'male' => array(
                        'label' => 'Male',
                        'label_attributes' => array('class'=>'css-label' , 'for' => 'gender_1'),
                        'value' => 'Male',
                        'attributes' => array(
                            'id' => 'gender_1',
                        ),
                    ),
                    'female' => array(
                        'label' => 'Female',
                        'label_attributes' => array('class'=>'css-label' , 'for' => 'gender_2'),
                        'value' => 'Female',
                        'attributes' => array(
                            'id' => 'gender_2',
                        ),
                    ),
                    
                ),
            ),
            'attributes' => array(
                // 'id' => 'sex',
                'class' => 'css-checkbox',
            ),
        ));
        
        $this->add(array(
            'name' => 'display_name',
            'options' => array(
                'label' => 'Name',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Please enter your name',
                'id' => 'display_name'
            ),
        ));
        
         $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => 'Email',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Please enter your email',
                'id' => 'email'
            ),
        ));
        
         $this->add(array(
            'name' => 'mobile',
            'options' => array(
                'label' => 'Mobile',
            ),
            'attributes' => array(
                'type' => 'text',
                'id' => 'mobile',
                //'maxlength' => 10,
                'class' => 'form-control',
                'placeholder' => 'Please enter mobile no.',
                'style' => " border:none;width:200px; vertical-align: top;"
            ),
        ));
         
        $this->add(array(
            'name' => 'school_name',
            'options' => array(
                'label' => 'School Name',
            ),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Please enter your school name',
                'id' => 'school_name',
                'onkeyup' => 'callFunctionSchoolName()',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'school_email',
            'options' => array(
                'label' => 'School Name',
            ),
            'attributes' => array(
                'type' => 'hidden',
                'id' => 'school_email'
            ),
        ));
        
//        $this->add(array(
//            'name' => 'username',
//            'options' => array(
//                'label' => 'Username',
//            ),
//            'attributes' => array(
//                'type' => 'text'
//            ),
//        ));

       
//        $this->add(array(
//            'name' => 'display_name',
//            'options' => array(
//                'label' => 'Display Name',
//            ),
//            'attributes' => array(
//                'type' => 'text'
//            ),
//        ));
//
//        $this->add(array(
//            'name' => 'password',
//            'options' => array(
//                'label' => 'Password',
//            ),
//            'attributes' => array(
//                'type' => 'password'
//            ),
//        ));
//
//        $this->add(array(
//            'name' => 'passwordVerify',
//            'options' => array(
//                'label' => 'Password Verify',
//            ),
//            'attributes' => array(
//                'type' => 'password'
//            ),
//        ));
        
        
        
        if ($this->getRegistrationOptions()->getUseRegistrationFormCaptcha()) {
            $this->add(array(
                'name' => 'captcha',
                'type' => 'Zend\Form\Element\Captcha',
                'options' => array(
                    'label' => 'Please type the following text',
                    'captcha' => $this->getRegistrationOptions()->getFormCaptchaOptions(),
                ),
            ));
        }

        $submitElement = new Element\Button('submit');
        $submitElement
            ->setLabel('Submit')
            ->setAttributes(array(
                'type'  => 'submit',
                'class' => "createAccount_btn"
            ));

        $this->add($submitElement, array(
            'priority' => -100,
        ));

        $this->add(array(
            'name' => 'userId',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));

        // @TODO: Fix this... getValidator() is a protected method.
        //$csrf = new Element\Csrf('csrf');
        //$csrf->getValidator()->setTimeout($this->getRegistrationOptions()->getUserFormTimeout());
        //$this->add($csrf);
    }
}
