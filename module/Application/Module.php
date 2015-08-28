<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Application\Model\Countrydetails;
use Application\View\Helper\NavLoginWidget;
use Application\View\Helper\UserTrackerWidget;
use Application\View\Helper\ConfigValue;
use Application\View\Helper;
use Zend\ServiceManager\ServiceLocatorInterface;
class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
       $e->getApplication()->getServiceManager()->get('viewhelpermanager')->setFactory('controllerName', function($sm) use ($e) {
        $viewHelper = new View\Helper\ControllerName($e->getRouteMatch());
        return $viewHelper;
        });
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
 public function getServiceConfig(){
    return array(
            'factories' => array(
    		'dbAdapter' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    		    return $dbAdapter;
    		},
    		'countrydetails' =>  function($sm) {
    		    $table = new Countrydetails();
                    return $table;
    		},
    
    				 
     
    	   ));
  }
    
  public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                   'NavLoginWidget' => function ($sm) {
                    $sm = $sm->getServiceLocator();
                    $tableGateway = $sm->get('Notification\Model\NotificationTable');
                    $viewHelper = new NavLoginWidget();
                    $viewHelper->setTableGateway($tableGateway);
                    return $viewHelper;
                },
                 'UserTrackerWidget' => function ($sm) {
                    $sm = $sm->getServiceLocator();
                    $auth = $sm->get('zfcuser_auth_service');
                    $objauth = ($auth->hasIdentity())?$auth->getIdentity()->getId():'';
                    $tableGateway = $sm->get('Assessment\Model\UserTrackerTable');
                    $viewHelper = new UserTrackerWidget();
                    $viewHelper->setAuthServices($auth);
                    $viewHelper->setTableGateway($tableGateway,$objauth);
                    return $viewHelper;
                },
             'configvalue' => function($serviceManager) {
                $helper = new ConfigValue($serviceManager);
                $helper->setversion($serviceManager);
                return $helper;
            },        
	/*	'Params' => function (ServiceLocatorInterface $helpers)
                {
                    $services = $helpers->getServiceLocator();
                    $app = $services->get('Application');
                    return new Helper\Params($app->getRequest(), $app->getMvcEvent());
                }  */
            ),
        );
    }    
    
    
}
