<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
'doctrine' => array(
  'driver' => array(
    'application_entities' => array(
      'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
      'cache' => 'array',
      'paths' => array(__DIR__.'/../src/Application/Entity')
    ),

    'orm_default' => array(
      'drivers' => array(
        'Application\Entity' => 'application_entities'
      )
))),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Cron' => 'Application\Controller\CronController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:controller[/:action]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
			
            'school-solutions' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/school-solutions[/:action]',
                    'constraints' => array(
                        'action' =>
'[a-zA-Z][a-zA-Z0-9_-]*',                         
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'schoolsolution',
                    ),
                ),
            ),
			'smart-learn-class' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/smart-learn-classroom[/:action]',
                    'constraints' => array(
                        'action' =>
'[a-zA-Z][a-zA-Z0-9_-]*',                         
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'smartlearnclass',
                    ),
                ),
            ),
            
            
            'headerforzf1' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/headerforzf1[/:action]',
                    'constraints' => array(
                        'action' =>
'[a-zA-Z][a-zA-Z0-9_-]*',                         
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'headerforzf1',
                    ),
                ),
            ),
            /**
             * Footer for zf1
             */
            'footerforzf1' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/footerforzf1[/:action]',
                    'constraints' => array(
                        'action' =>'[a-zA-Z][a-zA-Z0-9_-]*',                         
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'footerforzf1',
                    ),
                ),
            ),
//            'loggedinzf1' => array(
//                'type' => 'segment',
//                'options' => array(
//                    'route'    => '/loggedinzf1[/:action]',
//                    'constraints' => array(
//                        'action' =>
//'[a-zA-Z][a-zA-Z0-9_-]*',                         
//                    ),
//                    'defaults' => array(
//                        'controller' => 'Application\Controller\Index',
//                        'action'     => 'loggedinzf1',
//                    ),
//                ),
//            ),
            
            'classroomtab' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/classroomtab[/:action]',
                    'constraints' => array(
                        'action' =>
'[a-zA-Z][a-zA-Z0-9_-]*',                         
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'classroomtab',
                    ),
                ),
            ),
            
            'testcenter' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/testcenter[/:action]',
                    'constraints' => array(
                        'action' =>
'[a-zA-Z][a-zA-Z0-9_-]*',                         
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'testcenter',
                    ),
                ),
            ),
            
            'ccereport' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/ccereport[/:action]',
                    'constraints' => array(
                        'action' =>
'[a-zA-Z][a-zA-Z0-9_-]*',                         
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'ccereport',
                    ),
                ),
            ),
            
            'schoolmangement' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/school-mangement-system[/:action]',
                    'constraints' => array(
                        'action' =>
'[a-zA-Z][a-zA-Z0-9_-]*',                         
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'schoolmangement',
                    ),
                ),
            ),
             'getleafnode' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/getleafnode[/:classId]',
                    'constraints' => array(
                        'action' =>
'[a-zA-Z][a-zA-Z0-9_-]*',                         
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'getleafnode',
                    ),
                ),
            ),
            'run-cron-email' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/run-cron-email[/:id]',
                    'constraints' => array(
                         'id' =>
'[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',                        
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Cron',
                        'action'     => 'runCronEmail',
                    ),
                ),
            ),
            
            'notification-cron' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/notification-cron',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Cron',
                        'action' => 'notificationcron',
                    ),
                ),
            ),
            
            'auto-mail' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/auto-mail',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Cron',
                        'action' => 'automail',
                    ),
                ),
            ),
            
            'falconideapi' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/falconideapi[/:TRANSID][/:RESPONSE][/:EVENT][/:RCPTID][/:EMAIL][/:TIMESTAMP]',
                    'constraints' => array(
                         'EVENT' =>
                          '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',                     
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Cron',
                        'action'     => 'falconideapi',
                    ),
                ),
            ),
            
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    
    'view_manager' => array(
        
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/layout_admin'     => __DIR__ . '/../view/layout/layout_admin.phtml',
             'layout/layout_dealer'     => __DIR__ . '/../view/layout/layout_dealer.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
                // Console routes go here
                'container-list-console'=>array(
                    'type'    => 'simple',       // <- simple route is created by default, we can skip that
                    'options' => array(
                        'route'    => 'cron',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Cron',
                            'action'     => 'runCronEmail'
                        )
                    )
                )
            )
        )
    ),

    'view_helpers' => array(
'invokables' => array(
	'Country' => 'Application\View\Country',
	'action' => 'Application\View\Action',
		),
		 
	)
);



