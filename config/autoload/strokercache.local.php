<?php

return array(
    'strokercache' => array(
        'strategies' => array(
            'enabled' => array(
                'StrokerCache\Strategy\RouteName' => array(
                    'routes' => array(
                        'sitemapnew/boards',
                        'sitemapnew/classes',
                        'sitemapnew/subjects',
                        'sitemapnew/class',
                        'sitemapnew/class/subjects',
                        'sitemapnew/class/subjects/chapters',
                        'sitemapnew/class/subjects/chapters/services',
                        'sitemapnew/services',
                        'sitemapnew/services/boards',
                        'sitemapnew/services/boards/classes',
                        'sitemapnew/services/boards/classes/subjects',
                        'sitemapnew/services/boards/classes/subjects/chapters',
                    ),
                ),
//                'StrokerCache\Strategy\ControllerName' => array(
//                    'controllers' => array(
//                    ),
//                ),
//                'StrokerCache\Strategy\Url' => array(
//                    'regexpes' => array(
//                        '^(/lms/index/containerlist/(\d)*)$'
//                    )
//                )
            ),
        ),
        'storage_adapter' => array(
            'name' => 'Zend\Cache\Storage\Adapter\Filesystem',
            'options' => array(
                'cache_dir' => 'data/cache',
            ),
        ),
    )
);
