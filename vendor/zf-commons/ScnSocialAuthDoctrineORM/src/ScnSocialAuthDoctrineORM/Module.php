<?php

namespace ScnSocialAuthDoctrineORM;

class Module
{
    public function onBootstrap($e)
    {
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/../../autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
    
    public function getServiceConfig() {

        return array(
            'invokables'   =>  array(
    	                           'common_service'=>'Common\Service\Cservice'
    	                           ),
            'aliases' => array(
                'scnauthdoctrine_em' => 'Doctrine\ORM\EntityManager',
            ),
            'factories' => array(
                'scnauth_module_options' => function ($sm) {
                    $config = $sm->get('Config');
                    return new Options\ModuleOptions();
                },
                'scnauth_mapper' => function($sm) {
                    $options = $sm->get('scnauth_module_options');
//                    echo '<pre>';print_r($options); echo '</pre>';die('macro Die');
                    $mapper = new Mapper\UserProvider(
                                    $sm->get('scnauthdoctrine_em'),
                                    $sm->get('scnauth_module_options')
                    );
//                    echo '<pre>';print_r($mapper); echo '</pre>';die('macro Die');
//                                        $entityClass = $options->getContainerEntityClass();      
//                    echo '<pre>';print_r($mapper); echo '</pre>';die('macro Die');
                                        return $mapper;
                }
            ),
        );
    }
}