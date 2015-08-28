<?php

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'zfcuserv0' => __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'zfcuser' => 'ZfcUserV0\Controller\UserController',
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'zfcuser_zend_db_adapter' => 'Zend\Db\Adapter\Adapter',
        ),
    ),
    'router' => array(
        'routes' => array(
            'zfcuser' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'zfcuser',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'loginapi' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/loginapi',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'loginapi',
                            ),
                        ),
                    ),
                    'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'login',
                            ),
                        ),
                    ),
                    'validemail' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/validemail',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'validemail',
                            ),
                        ),
                    ),
                    'checkemailexists' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/checkemailexists',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'checkemailexists',
                            ),
                        ),
                    ),
                    'checkcountryphonedegits' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/checkcountryphonedegits',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action' => 'checkcountryphonedegits',
                            ),
                        ),
                    ),
                    'getchildcount' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/getchildcount',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'getchildcount',
                            ),
                        ),
                    ),
                    'validemailsubmission' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/validemailsubmission',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'validemailsubmission',
                            ),
                        ),
                    ),
                    'updateboardclass' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/updateboardclass',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'updateboardclass',
                            ),
                        ),
                    ),
                    'forgotpassword' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/forgotpassword',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'forgotpassword',
                            ),
                        ),
                    ),
                    'checkemail' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/checkemail',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'checkemail',
                            ),
                        ),
                    ),
                    'sentforgotrequest' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/sentforgotrequest',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'sentforgotrequest',
                            ),
                        ),
                    ),
                    'resetforgetpass' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/resetforgetpass',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'resetforgetpass',
                            ),
                        ),
                    ),
                    
                    'childpasswordcheck' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/childpasswordcheck',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'childpasswordcheck',
                            ),
                        ),
                    ),
                    
                    'forgetpassvalidate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/forgetpassvalidate',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'forgetpassvalidate',
                            ),
                        ),
                    ),
                    
                    'registerchild' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/registerchild',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'registerchild',
                            ),
                        ),
                    ),
                    'invitelearner' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/invitelearner',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'invitelearner',
                            ),
                        ),
                    ),
                    'linkchild' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/linkchild',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'linkchild',
                            ),
                        ),
                    ),
                    'socialdatacapture' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/socialdatacapture',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'socialdatacapture',
                            ),
                        ),
                    ),
                  
                    'authenticate' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/authenticate',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'authenticate',
                            ),
                        ),
                    ),
                    'fromstudyboard' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/fromstudyboard',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'fromstudyboard',
                            ),
                        ),
                    ),
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logout',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'logout',
                            ),
                        ),
                    ),
                    'logoutspecial' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/logoutspecial',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'logoutspecial',
                            ),
                        ),
                    ),
                    'registermaskedemail' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/registermaskedemail',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'registermaskedemail',
                            ),
                        ),
                    ),
                   
                    'checkuserlogstatus' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/checkuserlogstatus',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'checkuserlogstatus',
                            ),
                        ),
                    ),
                    'register' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/register[/:redirect]',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'register',
                            ),
                        ),
                    ),
                    'registersocial' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/registersocial[/:redirect]',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'registersocial',
                            ),
                        ),
                    ),
                    'notifyparent' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/notifyparent',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'notifyparent',
                            ),
                        ),
                    ),
                    'notifymentor' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/notifymentor',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'notifymentor',
                            ),
                        ),
                    ),  
                    'changeuserrole' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/changeuserrole',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'changeuserrole',
                            ),
                        ),
                    ),
                    
                    'changeemail' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/change-email',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action' => 'changeemail',
                            ),
                        ),
                    ),
                    'previoussession' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/previoussession',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action' => 'previoussession',
                            ),
                        ),
                    ),

                    /*'user-dashboard' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/user-dashboard[/:id][/:welcome]',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action' => 'userdashboard',
                            ),
                        ),
                    ),*/


                     'tablet-register' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/tablet-register/[/:id]',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'tabletregister',
                            ),
                        ),
                    ),
                     'user-confrim' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/user-confirm/[/:id]',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'confirmpassword',
                            ),
                        ),
                    ),                    
                    'go-online' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/go-online/[/:id]',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'goOnline',
                            ),
                        ),
                    ),
                    'ask-password' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/ask-password',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'ask-password',
                            ),
                        ),
                    ),
                   
                'getstates' => array(
                       'type' => 'Segment',
                       'options' => array(
                           'route' => '/getstates[/:id]',
                           'defaults' => array(
                               'controller' => 'zfcuser',
                               'action' => 'getstates',
                           ),
                       ),
                   ),
                'openregisterpopup' => array(
                       'type' => 'Segment',
                       'options' => array(
                           'route' => '/openregisterpopup',
                           'defaults' => array(
                               'controller' => 'zfcuser',
                               'action' => 'openregisterpopup',
                           ),
                       ),
                   ),
                'openregisterpopupexternal' => array(
                       'type' => 'Segment',
                       'options' => array(
                           'route' => '/openregisterpopupexternal',
                           'defaults' => array(
                               'controller' => 'zfcuser',
                               'action' => 'openregisterpopupexternal',
                           ),
                       ),
                   ),
                'getschools' => array(
                       'type' => 'Segment',
                       'options' => array(
                           'route' => '/getschools[/:id]',
                           'defaults' => array(
                               'controller' => 'zfcuser',
                               'action' => 'getschools',
                           ),
                       ),
                   ),
                    'updatetabletmapped' => array(
                       'type' => 'Literal',
                       'options' => array(
                           'route' => '/updatetabletmapped',
                           'defaults' => array(
                               'controller' => 'zfcuser',
                               'action' => 'updatetabletmapped',
                           ),
                       ),
                   ),
                    
                    
                ),
            ),
            
        ),
    ),
);
