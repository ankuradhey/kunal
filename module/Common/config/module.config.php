<?php

//namespace Common\Entity;
return array(
    'doctrine' => array(
        'driver' => array(
            'common_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Common/Entity'),
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Common\Entity' => 'common_entity'
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Common\Controller\Index' => 'Common\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'getstates' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/getstates',
                    'constraints' => array(
                        'action' =>
                        '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Common\Controller\Index',
                        'action' => 'getStateByCountry',
                    ),
                ),
            ),
            'common' => array(
                'type' => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route' => '/common',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Common\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Common' => __DIR__ . '/../view',
        ),
    ),
);
