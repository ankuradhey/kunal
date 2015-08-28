<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Notification\\Controller\\Index' => 'Notification\\Controller\\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'notification' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/notification',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Notification\\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:controller[/:action][/:param1][/:param2][/:param3]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(),
                        ),
                    ),
                ),
            ),
            'calendarschedule' => array(
                'type' => 'Zend\\Mvc\\Router\\Http\\Segment',
                'options' => array(
                    'route' => '/calendarschedule[/:id][/:userid]',
                    'constraints' => array(
                        'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                        'userid' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Notification\\Controller\\Index',
                        'action' => 'calendarschedule',
                    ),
                ),
            ),
            'subject-color' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/subject-color[/:id]',
                    'constraints' => array(
                        'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Notification\\Controller\\Index',
                        'action' => 'subjectcolor',
                    ),
                ),
            ),
            'add-lessons' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/add-lessons[/:id]',
                    'constraints' => array(
                        'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Notification\\Controller\\Index',
                        'action' => 'addLessons',
                    ),
                ),
            ),
            'scheduler-report' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/scheduler-report[/:id]',
                    'constraints' => array(
                        'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Notification\\Controller\\Index',
                        'action' => 'schedulerReport',
                    ),
                ),
            ),
            'schedulerchapterlist' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/schedulerchapterlist[/:subject_id][/:classId][/:userId][/:customboard]',
                    'defaults' => array(
                        'controller' => 'Notification\\Controller\\Index',
                        'action' => 'schedulerchapterlist',
                    ),
                ),
            ),
            'calender-operations' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/calender-operations[/:id]',
                    'constraints' => array(
                        'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Notification\\Controller\\Index',
                        'action' => 'calenderOperations',
                    ),
                ),
            ),
            'ajax-progress-notes' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/ajax-progress-notes[/:id]',
                    'constraints' => array(
                        'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Notification\\Controller\\Index',
                        'action' => 'ajaxProgressNotes',
                    ),
                ),
            ),
            'child-subjects' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/child-subjects[/:id]',
                    'constraints' => array(
                        'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Notification\\Controller\\Index',
                        'action' => 'childSubjects',
                    ),
                ),
            ),
            'notification.rest.notifications' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/v1.0/notifications[/:notifications_id]',
                    'defaults' => array(
                        'controller' => 'Notification\\V1\\Rest\\Notifications\\Controller',
                    ),
                ),
            ),
            'notification.rest.notificationupdate' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/v1.0/notificationupdate[/:notificationupdate_id]',
                    'defaults' => array(
                        'controller' => 'Notification\\V1\\Rest\\Notificationupdate\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Notification' => __DIR__ . '/../view',
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'notification.rest.notifications',
            1 => 'notification.rest.notificationupdate',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Notification\\V1\\Rest\\Notifications\\NotificationsResource' => 'Notification\\V1\\Rest\\Notifications\\NotificationsResourceFactory',
            'Notification\\V1\\Rest\\Notificationupdate\\NotificationupdateResource' => 'Notification\\V1\\Rest\\Notificationupdate\\NotificationupdateResourceFactory',
        ),
    ),
    'zf-rest' => array(
        'Notification\\V1\\Rest\\Notifications\\Controller' => array(
            'listener' => 'Notification\\V1\\Rest\\Notifications\\NotificationsResource',
            'route_name' => 'notification.rest.notifications',
            'route_identifier_name' => 'notifications_id',
            'collection_name' => 'notifications',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
                2 => 'PUT',
            ),
            'collection_query_whitelist' => array(
                0 => 'notificationId',
                1 => 'datefrom',
                2 => 'time',
                3 => 'userId',
                4 => 'apiKey',
            ),
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => 'Notification\\V1\\Rest\\Notifications\\NotificationsEntity',
            'collection_class' => 'Notification\\V1\\Rest\\Notifications\\NotificationsCollection',
            'service_name' => 'Notifications',
        ),
        'Notification\\V1\\Rest\\Notificationupdate\\Controller' => array(
            'listener' => 'Notification\\V1\\Rest\\Notificationupdate\\NotificationupdateResource',
            'route_name' => 'notification.rest.notificationupdate',
            'route_identifier_name' => 'notificationupdate_id',
            'collection_name' => 'notificationupdate',
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
            'entity_class' => 'Notification\\V1\\Rest\\Notificationupdate\\NotificationupdateEntity',
            'collection_class' => 'Notification\\V1\\Rest\\Notificationupdate\\NotificationupdateCollection',
            'service_name' => 'notificationupdate',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'Notification\\V1\\Rest\\Notifications\\Controller' => 'HalJson',
            'Notification\\V1\\Rest\\Notificationupdate\\Controller' => 'HalJson',
        ),
        'accept_whitelist' => array(
            'Notification\\V1\\Rest\\Notifications\\Controller' => array(
                0 => 'application/vnd.notification.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
            'Notification\\V1\\Rest\\Notificationupdate\\Controller' => array(
                0 => 'application/vnd.notification.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content_type_whitelist' => array(
            'Notification\\V1\\Rest\\Notifications\\Controller' => array(
                0 => 'application/vnd.notification.v1+json',
                1 => 'application/json',
            ),
            'Notification\\V1\\Rest\\Notificationupdate\\Controller' => array(
                0 => 'application/vnd.notification.v1+json',
                1 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'Notification\\V1\\Rest\\Notifications\\NotificationsEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'notification.rest.notifications',
                'route_identifier_name' => 'notifications_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'Notification\\V1\\Rest\\Notifications\\NotificationsCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'notification.rest.notifications',
                'route_identifier_name' => 'notifications_id',
                'is_collection' => true,
            ),
            'Notification\\V1\\Rest\\Notificationupdate\\NotificationupdateEntity' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'notification.rest.notificationupdate',
                'route_identifier_name' => 'notificationupdate_id',
                'hydrator' => 'Zend\\Stdlib\\Hydrator\\ArraySerializable',
            ),
            'Notification\\V1\\Rest\\Notificationupdate\\NotificationupdateCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'notification.rest.notificationupdate',
                'route_identifier_name' => 'notificationupdate_id',
                'is_collection' => true,
            ),
        ),
    ),
);
