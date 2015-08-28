<?php
namespace Notification;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Assessment\Model\User;
use Assessment\Model\UserTable;
use Notification\Model\Notification;
use Notification\Model\NotificationTable;
use Notification\Model\Notificationtypemaster;
use Notification\Model\NotificationtypemasterTable;
use Notification\Model\Tlessonplan;
use Notification\Model\TlessonplanTable;
use Notification\Model\Tfreechapter;
use Notification\Model\TfreechapterTable;
use Notification\Model\Tnotes;
use Notification\Model\TNotesTable;
use Notification\Model\Tquestion;
use Notification\Model\TquestionTable;
use Notification\Model\Treplyonquestion;
use Notification\Model\TreplyonquestionTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Common\Factory\Model\SlaveAdapter;
use Zend\Db\TableGateway\Feature;
use Notification\View\Helper\RightPanelWidget;
use Notification\View\Helper\NotificationWidget;
use Notification\View\Helper\ToggleNotificationWidget;
use Notification\View\Helper\SubjectSchedulerWidget;
use Notification\View\Helper\TickerWidget;
use Notification\View\Helper\MetaTagsWidget;
use Notification\View\Helper\MaskWidget;


class Module {

    public function onBootstrap(MvcEvent $e) {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

public function getServiceConfig() {
        return array(
            'factories' => array(
                'Notification\Model\TTchaterResourceTable'=>'Notification\Factory\Model\TChepterResourceFactory',
                 'Notification\Model\NotificationTable' =>  function($sm) {
                   $tableGateway = $sm->get('NotificationTableGateway');
                   $table = new NotificationTable($tableGateway,$sm);
                   return $table;
                },
                   'NotificationTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Notification());
                    return new TableGateway('notification', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                    
                   'ZfcUser\Model\NotificationtypemasterTable' =>  function($sm) {
                   $tableGateway = $sm->get('NotificationtypemasterTableGateway');
                   $table = new NotificationTable($tableGateway);
                   return $table;
                },
                    'NotificationtypemasterTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Notificationtypemaster());
                    return new TableGateway('notification', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                 
                        
                  'Notification\Model\TlessonplanTable' =>  function($sm) {
                   $tableGateway = $sm->get('TlessonplanTableGateway');
                   $table = new TlessonplanTable($tableGateway);
                   return $table;
                },
		  'TlessonplanTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tlessonplan());
                    return new TableGateway('t_lesson_plan', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                 
               'Notification\Model\TfreechapterTable' =>  function($sm) {
                   $tableGateway = $sm->get('TfreechapterTableGateway');
                   $table = new TfreechapterTable($tableGateway);
                   return $table;
                },
		   'TfreechapterTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tfreechapter());
                    return new TableGateway('free_chapter_list', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                 
               'Notification\Model\TNotesTable' =>  function($sm) {
                   $tableGateway = $sm->get('TNotesTableGateway');
                   $table = new TNotesTable($tableGateway);
                   return $table;
                },
		   'TNotesTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tnotes());
                    return new TableGateway('t_notes', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
               'Notification\Model\TquestionTable' =>  function($sm) {
                   $tableGateway = $sm->get('TquestionTableGateway');
                   $table = new TquestionTable($tableGateway);
                   return $table;
                },
		    'TquestionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tquestion());
                    return new TableGateway('t_question', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                }, 
               'Notification\Model\TreplyonquestionTable' =>  function($sm) {
                   $tableGateway = $sm->get('TreplyonquestionTableGateway');
                   $table = new TreplyonquestionTable($tableGateway);
                   return $table;
                },
		'TreplyonquestionTableGateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Treplyonquestion());
                return new TableGateway('t_reply_on_question', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },         
                        
               'zfcuser_auth_service' => function ($sm) {
                    return new \Zend\Authentication\AuthenticationService(
                        $sm->get('ZfcUser\Authentication\Storage\Db'),
                        $sm->get('ZfcUser\Authentication\Adapter\AdapterChain')
                    );
                },

                'ZfcUser\Authentication\Adapter\AdapterChain' => 'ZfcUser\Authentication\Adapter\AdapterChainServiceFactory',
          
                        
            ),
        );
    }
    
   public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
               'RightPanelWidget' => function ($sm) {
                    $sm              = $sm->getServiceLocator();                  
                    $auth            = $sm->get('zfcuser_auth_service');
                    $getLessons      = $sm->get('Notification\Model\TlessonplanTable');
                    $tableUser       = $sm->get('Assessment\Model\UserTable');
                    $mentorstudent   = $sm->get('Assessment\Model\TstudentandmentorTable');
                    $viewHelper      = new RightPanelWidget();
                    $viewHelper->setTableGateway($tableUser,$getLessons,$mentorstudent);
                    $viewHelper->setAuthServices($auth);
                    $viewHelper->setServiceLocator($sm);
                    return $viewHelper;
                },
                   'NotificationWidget' => function ($sm) {
                    $sm = $sm->getServiceLocator();
                    $auth = $sm->get('zfcuser_auth_service');
                    $objauth = ($auth->hasIdentity())?$auth->getIdentity():'';
                    $tableGateway = $sm->get('Notification\Model\NotificationTable');
                    $viewHelper = new NotificationWidget();
                    $viewHelper->setAuthServices($auth);
                    $viewHelper->setTableGateway($tableGateway,$objauth);
                    return $viewHelper;
                },
               
                'ToggleNotificationWidget' => function ($sm) {
                      $sm = $sm->getServiceLocator();
                      $auth = $sm->get('zfcuser_auth_service');
                      $objauth = ($auth->hasIdentity())?$auth->getIdentity():'';
                      $tableGateway = $sm->get('Notification\Model\NotificationTable');
                      $viewHelper = new ToggleNotificationWidget();
                      $viewHelper->setAuthServices($auth);
                      $viewHelper->setTableGateway($tableGateway,$objauth);
                      return $viewHelper;
                  }, 
                          
                 'SubjecSchedulerWidget' => function ($sm) {
                    $sm         = $sm->getServiceLocator();
                    $auth       = $sm->get('zfcuser_auth_service');
                    $lmSservice = $sm->get('lms_container_service');
                    $userPackagetableGateway = $sm->get('Package\Model\TuserpackageTable');
                    $viewHelper = new SubjectSchedulerWidget();                    
                    $viewHelper->setTableGateway('',$userPackagetableGateway);
                    $viewHelper->setLmsServices($lmSservice);
                    $viewHelper->setAuthServices($auth);
                    return $viewHelper;
                },
                    'TickerWidget' => function ($sm) {
                    $sm = $sm->getServiceLocator();
                    $auth = $sm->get('zfcuser_auth_service');
                    $objauth = ($auth->hasIdentity())?$auth->getIdentity():'';
                    $tableGateway = $sm->get('Assessment\Model\TickerTable');
                    $viewHelper = new TickerWidget();
                    $viewHelper->setAuthServices($auth);
                    $viewHelper->setTableGateway($tableGateway,$objauth);
                    return $viewHelper;
                },   
                        'MetaTagsWidget' => function ($sm) {
                    $sm = $sm->getServiceLocator();
                    $auth = $sm->get('zfcuser_auth_service');
                    $objauth = ($auth->hasIdentity())?$auth->getIdentity():'';
                    $tableGateway = $sm->get('Assessment\Model\MetaTagsTable');
                    $viewHelper = new MetaTagsWidget();
                    $viewHelper->setAuthServices($auth);
                    $viewHelper->setTableGateway($tableGateway,$objauth);
                    return $viewHelper;
                },
                       'MaskWidget' => function ($sm) {
                    $sm = $sm->getServiceLocator();
                    $auth = $sm->get('zfcuser_auth_service');
                    $objauth = ($auth->hasIdentity())?$auth->getIdentity():'';
                    $tableGateway = $sm->get('Assessment\Model\TickerTable');
                    $viewHelper = new MaskWidget();
                    $viewHelper->setAuthServices($auth);
                    $viewHelper->setTableGateway($tableGateway,$objauth);
                    return $viewHelper;
                },    
                        
            ),
        );
    } 
    
   
}
