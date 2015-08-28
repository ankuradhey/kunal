<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'User\\Controller\\Index' => 'User\\Controller\\IndexController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'user' => __DIR__ . '/../view',
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
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'myprofile' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/myprofile',
                            'defaults' => array(
                                'controller' => 'User\\Controller\\Index',
                                'action' => 'myprofile',
                            ),
                        ),
                    ),
                    'student-profile' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/student-profile[/:id]',
                            'defaults' => array(
                                'controller' => 'User\\Controller\\Index',
                                'action' => 'student-profile',
                            ),
                        ),
                    ),
                    'changeprofile' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/changeprofile',
                            'defaults' => array(
                                'controller' => 'User\\Controller\\Index',
                                'action' => 'changeprofile',
                            ),
                        ),
                    ),
                    'changepassword' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/change-password',
                            'defaults' => array(
                                'controller' => 'User\\Controller\\Index',
                                'action' => 'changepassword',
                            ),
                        ),
                    ),
                    'changepasswordchild' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/changepasswordchild[/:userId]',
                            'defaults' => array(
                                'controller' => 'User\\Controller\\Index',
                                'action' => 'changepasswordchild',
                            ),
                        ),
                    ),
                    'unsubscription' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/unsubscription[/:userId]',
                            'defaults' => array(
                                'controller' => 'User\\Controller\\Index',
                                'action' => 'unsubscription',
                            ),
                        ),
                    ),
                    'user-dashboard' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/user-dashboard[/:id][/:welcome]',
                            'defaults' => array(
                                'controller' => 'User\\Controller\\Index',
                                'action' => 'userdashboard',
                            ),
                        ),
                    ),
                ),
            ),
            'my-groups' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/my-groups[/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'my-groups',
                    ),
                ),
            ),
            'my-subscriptions' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/my-subscriptions[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'my-subscriptions',
                    ),
                ),
            ),
            'hit-mobile-url' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/mobile_url[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'mobile-url',
                    ),
                ),
            ),
            'ajaxsubscriptions' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/ajaxsubscriptions[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'ajaxsubscriptions',
                    ),
                ),
            ),
            'ajaxgetcarddetails' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/ajaxgetcarddetails[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'ajaxgetcarddetails',
                    ),
                ),
            ),
            'validateextensioncode' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/validateextensioncode',
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'validateextensioncode',
                    ),
                ),
            ),
            'mymentor' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/mymentor',
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'mymentor',
                    ),
                ),
            ),
            'my-parent' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/my-parent/[/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'myparent',
                    ),
                ),
            ),
            'my-students' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/my-students[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'myStudents',
                    ),
                ),
            ),
            'mypaper' => array(
                'type' => 'Zend\\Mvc\\Router\\Http\\Segment',
                'options' => array(
                    'route' => '/mypaper',
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'mypaper',
                    ),
                ),
            ),
            'myChild' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/myChild/[/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'mychild',
                    ),
                ),
            ),
            'my-child' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/myChild/[/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'mychild',
                    ),
                ),
            ),
            'userprofile' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/userprofile',
                    'constraints' => array(
                        'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'userprofile',
                    ),
                ),
            ),
            'all-questions' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/all-questions[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'allQuestions',
                    ),
                ),
            ),
            'mentorpopup' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/mentorpopup',
                    'constraints' => array(
                        'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'mentorpopup',
                    ),
                ),
            ),
            'mentorrequest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/changechildstatus',
                    'constraints' => array(
                        'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'changechildstatus',
                    ),
                ),
            ),
            'changechildstatus' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/changechildstatus',
                    'constraints' => array(
                        'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'changechildstatus',
                    ),
                ),
            ),
            'updatepassword' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/updatepassword',
                    'constraints' => array(
                        'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'updatepassword',
                    ),
                ),
            ),
            'updatepackageprofile' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/updatepackageprofile',
                    'constraints' => array(
                        'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'User\\Controller\\Index',
                        'action' => 'updatepackageprofile',
                    ),
                ),
            ),
            'user.rest.tabletregistration' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/v1.1/tabletregistration[/:tabletregistration_id]',
                    'defaults' => array(
                        'controller' => 'User\\V1\\Rest\\Tabletregistration\\Controller',
                    ),
                ),
            ),
            'user.rest.forgotpassword' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/v1.1/forgotpassword[/:forgotpassword_id]',
                    'defaults' => array(
                        'controller' => 'User\\V1\\Rest\\Forgotpassword\\Controller',
                    ),
                ),
            ),
            'user.rest.usersubscriptions' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/v1.1/usersubscriptions[/:usersubscriptions_id][/]',
                    'defaults' => array(
                        'controller' => 'User\\V1\\Rest\\Usersubscriptions\\Controller',
                    ),
                ),
            ),
            'user.rest.logout' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/v1.1/logout[/:logout_id]',
                    'defaults' => array(
                        'controller' => 'User\\V1\\Rest\\Logout\\Controller',
                    ),
                ),
            ),
            'user.rest.profileupdate' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/v1.1/profileupdate[/:profileupdate_id]',
                    'defaults' => array(
                        'controller' => 'User\\V1\\Rest\\Profileupdate\\Controller',
                    ),
                ),
            ),
            'user.rest.subscribe' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/v1.1/subscribe[/:subscribe_id]',
                    'defaults' => array(
                        'controller' => 'User\\V1\\Rest\\Subscribe\\Controller',
                    ),
                ),
            ),
            'user.rest.addtocart' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/v1.1/addtocart[/:addtocart_id]',
                    'defaults' => array(
                        'controller' => 'User\\V1\\Rest\\Addtocart\\Controller',
                    ),
                ),
            ),
            'user.rest.loginapi' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/v1.1/loginapi[/:loginapi_id]',
                    'defaults' => array(
                        'controller' => 'User\\V1\\Rest\\Loginapi\\Controller',
                    ),
                ),
            ),
            'user.rest.profilepicupdate' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/v1.1/profilepicupdate[/:profilepicupdate_id][/]',
                    'defaults' => array(
                        'controller' => 'User\\V1\\Rest\\Profilepicupdate\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'user.rest.tabletregistration',
            1 => 'user.rest.forgotpassword',
            2 => 'user.rest.usersubscriptions',
            3 => 'user.rest.logout',
            4 => 'user.rest.profileupdate',
            5 => 'user.rest.subscribe',
            6 => 'user.rest.addtocart',
            7 => 'user.rest.loginapi',
            8 => 'user.rest.profilepicupdate',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'User\\V1\\Rest\\Tabletregistration\\TabletregistrationResource' => 'User\\V1\\Rest\\Tabletregistration\\TabletregistrationResourceFactory',
            'User\\V1\\Rest\\Forgotpassword\\ForgotpasswordResource' => 'User\\V1\\Rest\\Forgotpassword\\ForgotpasswordResourceFactory',
            'User\\V1\\Rest\\Usersubscriptions\\UsersubscriptionsResource' => 'User\\V1\\Rest\\Usersubscriptions\\UsersubscriptionsResourceFactory',
            'User\\V1\\Rest\\Logout\\LogoutResource' => 'User\\V1\\Rest\\Logout\\LogoutResourceFactory',
            'User\\V1\\Rest\\Profileupdate\\ProfileupdateResource' => 'User\\V1\\Rest\\Profileupdate\\ProfileupdateResourceFactory',
            'User\\V1\\Rest\\Subscribe\\SubscribeResource' => 'User\\V1\\Rest\\Subscribe\\SubscribeResourceFactory',
            'User\\V1\\Rest\\Addtocart\\AddtocartResource' => 'User\\V1\\Rest\\Addtocart\\AddtocartResourceFactory',
            'User\\V1\\Rest\\Loginapi\\LoginapiResource' => 'User\\V1\\Rest\\Loginapi\\LoginapiResourceFactory',
            'User\\V1\\Rest\\Profilepicupdate\\ProfilepicupdateResource' => 'User\\V1\\Rest\\Profilepicupdate\\ProfilepicupdateResourceFactory',
        ),
    ),
    'zf-rest' => array(
        'User\\V1\\Rest\\Tabletregistration\\Controller' => array(
            'listener' => 'User\\V1\\Rest\\Tabletregistration\\TabletregistrationResource',
            'route_name' => 'user.rest.tabletregistration',
            'route_identifier_name' => 'tabletregistration_id',
            'collection_name' => 'tabletregistration',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
                4 => 'POST',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
                2 => 'PUT',
                3 => 'PATCH',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'User\\V1\\Rest\\Tabletregistration\\TabletregistrationEntity',
            'collection_class' => 'User\\V1\\Rest\\Tabletregistration\\TabletregistrationCollection',
            'service_name' => 'tabletregistration',
        ),
        'User\\V1\\Rest\\Forgotpassword\\Controller' => array(
            'listener' => 'User\\V1\\Rest\\Forgotpassword\\ForgotpasswordResource',
            'route_name' => 'user.rest.forgotpassword',
            'route_identifier_name' => 'forgotpassword_id',
            'collection_name' => 'forgotpassword',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'User\\V1\\Rest\\Forgotpassword\\ForgotpasswordEntity',
            'collection_class' => 'User\\V1\\Rest\\Forgotpassword\\ForgotpasswordCollection',
            'service_name' => 'forgotpassword',
        ),
        'User\\V1\\Rest\\Usersubscriptions\\Controller' => array(
            'listener' => 'User\\V1\\Rest\\Usersubscriptions\\UsersubscriptionsResource',
            'route_name' => 'user.rest.usersubscriptions',
            'route_identifier_name' => 'usersubscriptions_id',
            'collection_name' => 'usersubscriptions',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(
                0 => 'user_id',
                1 => 'active',
                2 => 'start',
                3 => 'offset',
                4 => 'apikey',
                5 => 'salt',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'User\\V1\\Rest\\Usersubscriptions\\UsersubscriptionsEntity',
            'collection_class' => 'User\\V1\\Rest\\Usersubscriptions\\UsersubscriptionsCollection',
            'service_name' => 'usersubscriptions',
        ),
        'User\\V1\\Rest\\Logout\\Controller' => array(
            'listener' => 'User\\V1\\Rest\\Logout\\LogoutResource',
            'route_name' => 'user.rest.logout',
            'route_identifier_name' => 'logout_id',
            'collection_name' => 'logout',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
                4 => 'POST',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'User\\V1\\Rest\\Logout\\LogoutEntity',
            'collection_class' => 'User\\V1\\Rest\\Logout\\LogoutCollection',
            'service_name' => 'logout',
        ),
        'User\\V1\\Rest\\Profileupdate\\Controller' => array(
            'listener' => 'User\\V1\\Rest\\Profileupdate\\ProfileupdateResource',
            'route_name' => 'user.rest.profileupdate',
            'route_identifier_name' => 'profileupdate_id',
            'collection_name' => 'profileupdate',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'User\\V1\\Rest\\Profileupdate\\ProfileupdateEntity',
            'collection_class' => 'User\\V1\\Rest\\Profileupdate\\ProfileupdateCollection',
            'service_name' => 'profileupdate',
        ),
        'User\\V1\\Rest\\Subscribe\\Controller' => array(
            'listener' => 'User\\V1\\Rest\\Subscribe\\SubscribeResource',
            'route_name' => 'user.rest.subscribe',
            'route_identifier_name' => 'subscribe_id',
            'collection_name' => 'subscribe',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(
                0 => 'user_id',
                1 => 'board_id',
                2 => 'class_id',
                3 => 'start',
                4 => 'offset',
                5 => 'apikey',
                6 => 'checksum',
                7 => 'salt',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'User\\V1\\Rest\\Subscribe\\SubscribeEntity',
            'collection_class' => 'User\\V1\\Rest\\Subscribe\\SubscribeCollection',
            'service_name' => 'subscribe',
        ),
        'User\\V1\\Rest\\Addtocart\\Controller' => array(
            'listener' => 'User\\V1\\Rest\\Addtocart\\AddtocartResource',
            'route_name' => 'user.rest.addtocart',
            'route_identifier_name' => 'addtocart_id',
            'collection_name' => 'addtocart',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'User\\V1\\Rest\\Addtocart\\AddtocartEntity',
            'collection_class' => 'User\\V1\\Rest\\Addtocart\\AddtocartCollection',
            'service_name' => 'addtocart',
        ),
        'User\\V1\\Rest\\Loginapi\\Controller' => array(
            'listener' => 'User\\V1\\Rest\\Loginapi\\LoginapiResource',
            'route_name' => 'user.rest.loginapi',
            'route_identifier_name' => 'loginapi_id',
            'collection_name' => 'loginapi',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
                4 => 'POST',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
                2 => 'PUT',
                3 => 'PATCH',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'User\\V1\\Rest\\Loginapi\\LoginapiEntity',
            'collection_class' => 'User\\V1\\Rest\\Loginapi\\LoginapiCollection',
            'service_name' => 'loginapi',
        ),
        'User\\V1\\Rest\\Profilepicupdate\\Controller' => array(
            'listener' => 'User\\V1\\Rest\\Profilepicupdate\\ProfilepicupdateResource',
            'route_name' => 'user.rest.profilepicupdate',
            'route_identifier_name' => 'profilepicupdate_id',
            'collection_name' => 'profilepicupdate',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
                4 => 'POST',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'User\\V1\\Rest\\Profilepicupdate\\ProfilepicupdateEntity',
            'collection_class' => 'User\\V1\\Rest\\Profilepicupdate\\ProfilepicupdateCollection',
            'service_name' => 'profilepicupdate',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'User\\V1\\Rest\\Tabletregistration\\Controller' => 'Json',
            'User\\V1\\Rest\\Forgotpassword\\Controller' => 'Json',
            'User\\V1\\Rest\\Usersubscriptions\\Controller' => 'Json',
            'User\\V1\\Rest\\Logout\\Controller' => 'HalJson',
            'User\\V1\\Rest\\Profileupdate\\Controller' => 'HalJson',
            'User\\V1\\Rest\\Subscribe\\Controller' => 'Json',
            'User\\V1\\Rest\\Addtocart\\Controller' => 'Json',
            'User\\V1\\Rest\\Loginapi\\Controller' => 'HalJson',
            'User\\V1\\Rest\\Profilepicupdate\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'User\\V1\\Rest\\Tabletregistration\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/json',
                2 => 'application/hal+json',
                3 => 'text/html',
            ),
            'User\\V1\\Rest\\Forgotpassword\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'User\\V1\\Rest\\Usersubscriptions\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'User\\V1\\Rest\\Logout\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'User\\V1\\Rest\\Profileupdate\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'User\\V1\\Rest\\Subscribe\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'User\\V1\\Rest\\Addtocart\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'User\\V1\\Rest\\Loginapi\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'User\\V1\\Rest\\Profilepicupdate\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'User\\V1\\Rest\\Tabletregistration\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/json',
                2 => 'text/html',
            ),
            'User\\V1\\Rest\\Forgotpassword\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/json',
            ),
            'User\\V1\\Rest\\Usersubscriptions\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/json',
            ),
            'User\\V1\\Rest\\Logout\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/json',
            ),
            'User\\V1\\Rest\\Profileupdate\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/json',
            ),
            'User\\V1\\Rest\\Subscribe\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/json',
            ),
            'User\\V1\\Rest\\Addtocart\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/json',
            ),
            'User\\V1\\Rest\\Loginapi\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/json',
            ),
            'User\\V1\\Rest\\Profilepicupdate\\Controller' => array(
                0 => 'application/vnd.user.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'User\\V1\\Rest\\Tabletregistration\\TabletregistrationEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.tabletregistration',
                'route_identifier_name' => 'tabletregistration_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'User\\V1\\Rest\\Tabletregistration\\TabletregistrationCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.tabletregistration',
                'route_identifier_name' => 'tabletregistration_id',
                'is_collection' => true,
            ),
            'User\\V1\\Rest\\Forgotpassword\\ForgotpasswordEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.forgotpassword',
                'route_identifier_name' => 'forgotpassword_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'User\\V1\\Rest\\Forgotpassword\\ForgotpasswordCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.forgotpassword',
                'route_identifier_name' => 'forgotpassword_id',
                'is_collection' => true,
            ),
            'User\\V1\\Rest\\Usersubscriptions\\UsersubscriptionsEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.usersubscriptions',
                'route_identifier_name' => 'usersubscriptions_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'User\\V1\\Rest\\Usersubscriptions\\UsersubscriptionsCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.usersubscriptions',
                'route_identifier_name' => 'usersubscriptions_id',
                'is_collection' => true,
            ),
            'User\\V1\\Rest\\Logout\\LogoutEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.logout',
                'route_identifier_name' => 'logout_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'User\\V1\\Rest\\Logout\\LogoutCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.logout',
                'route_identifier_name' => 'logout_id',
                'is_collection' => true,
            ),
            'User\\V1\\Rest\\Profileupdate\\ProfileupdateEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.profileupdate',
                'route_identifier_name' => 'profileupdate_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'User\\V1\\Rest\\Profileupdate\\ProfileupdateCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.profileupdate',
                'route_identifier_name' => 'profileupdate_id',
                'is_collection' => true,
            ),
            'User\\V1\\Rest\\Subscribe\\SubscribeEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.subscribe',
                'route_identifier_name' => 'subscribe_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'User\\V1\\Rest\\Subscribe\\SubscribeCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.subscribe',
                'route_identifier_name' => 'subscribe_id',
                'is_collection' => true,
            ),
            'User\\V1\\Rest\\Addtocart\\AddtocartEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.addtocart',
                'route_identifier_name' => 'addtocart_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'User\\V1\\Rest\\Addtocart\\AddtocartCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.addtocart',
                'route_identifier_name' => 'addtocart_id',
                'is_collection' => true,
            ),
            'User\\V1\\Rest\\Loginapi\\LoginapiEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.loginapi',
                'route_identifier_name' => 'loginapi_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'User\\V1\\Rest\\Loginapi\\LoginapiCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.loginapi',
                'route_identifier_name' => 'loginapi_id',
                'is_collection' => true,
            ),
            'User\\V1\\Rest\\Profilepicupdate\\ProfilepicupdateEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.profilepicupdate',
                'route_identifier_name' => 'profilepicupdate_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'User\\V1\\Rest\\Profilepicupdate\\ProfilepicupdateCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'user.rest.profilepicupdate',
                'route_identifier_name' => 'profilepicupdate_id',
                'is_collection' => true,
            ),
        ),
    ),
);
