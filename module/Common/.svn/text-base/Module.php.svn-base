<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Common for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Common;
use Common\Helper\CountryList;
use Common\Helper\StateList;
use Common\Helper\Fileversion;

use Doctrine\Common\Annotations;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface {

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e) {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
//        public function onBootstrap(Event $e)
//    {
//        AnnotationRegistry::registerFile('Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');
    
    }

    public function getServiceConfig() {

        return array(
'invokables'   =>  array(
    	                           'common_service'=>'Common\Service\Cservice'
    	                           ),
            'aliases' => array(
                'comdoctrine_em' => 'Doctrine\ORM\EntityManager',
            ),
            'factories' => array(
                'com_module_options' => function ($sm) {
                    $config = $sm->get('Config');
                    return new Options\ModuleOptions();
                },
                'com_mapper' => function($sm) {
                    $options = $sm->get('com_module_options');
//                    echo '<pre>';print_r($options); echo '</pre>';die('macro Die');
                    $mapper = new Mapper\CommonMapper(
                                    $sm->get('comdoctrine_em'),
                                    $sm->get('com_module_options')
                    );
//                    echo '<pre>';print_r($mapper); echo '</pre>';die('macro Die');
//                                        $entityClass = $options->getContainerEntityClass();      
//                    echo '<pre>';print_r($mapper); echo '</pre>';die('macro Die');
                                        return $mapper;
                }
            ),
        );
    }
    
    public function getViewHelperConfig()
    {

      return array(
            'factories' => array(
               
          'countryList' => function ($serviceManager) {
                            $serviceManager   = $serviceManager->getServiceLocator();
                            $containerService = $serviceManager->get('common_service');
                            $countryList      = new CountryList();
                            $countryList->setContainerService($containerService);
                            return $countryList;  
                    },
      
            'stateList'=>function($serviceManager){      
                 $serviceManager   = $serviceManager->getServiceLocator();
                 $containerService = $serviceManager->get('common_service');
                 $stateList        = new StateList();
                 $stateList->setContainerService($containerService);
                 return $stateList;

            },
            'fileversion'=>function($serviceManager){      
                 $serviceManager   = $serviceManager->getServiceLocator();
                 $containerService = $serviceManager->get('common_service');
                 $config           = $serviceManager->get('Config');
                 $stateList        = new Fileversion();
                 $stateList->setConfigservice(@$config['file_version']);
                 $stateList->setContainerService($containerService);
                 return $stateList;

            },
            'IpCountry'=>function($serviceManager){      
                 $serviceManager   = $serviceManager->getServiceLocator();
                 $containerService = $serviceManager->get("com_mapper");
                 $tableIpCountry   = $serviceManager->get('Assessment\Model\IpCountryTable');
                 $stateList        = new Helper\IpCountry();
                 $stateList->setIpservice($tableIpCountry);
                 $stateList->setContainerService($containerService);
                 return $stateList;

            },
            'cdn' => function($sm){ 
                  $request   = $sm->getServiceLocator()->get('Request');
                  $serviceLocator = $sm->getServiceLocator();
                  return new \Common\View\Helper\CdnHelper($request, $serviceLocator);
            }
        ));
    }

}
