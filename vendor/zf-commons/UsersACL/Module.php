<?php
namespace UsersACL;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\Adapter\DbTable as DbAuthAdapter;
use Zend\Session\Container;
use UsersACL\Model\UserRole;
use UsersACL\Model\PermissionTable;
use UsersACL\Model\ResourceTable;
use UsersACL\Model\RolePermissionTable;
use Zend\Authentication\AuthenticationService;
use UsersACL\Model\Role;
use UsersACL\Utility\Acl;
use Zend\Console\Console;
class Module
{

    /**
     * Bootstrap function
     *
     * @param MvcEvent $e            
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        if(!Console::isConsole())
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
            $this,
            'boforeDispatch'
        ), 100);
    }

    /**
     * Before Dispatch Function
     *
     * @param MvcEvent $event            
     */
    function boforeDispatch(MvcEvent $event)
    {
        $sm = $event->getApplication()->getServiceManager();
        $config = $sm->get('Config');
        $list = @$config['whitelist'];
        $name = $sm->get('request')
            ->getUri()
            ->getPath();
        $controller = $event->getRouteMatch()->getParam('controller');
        $action = $event->getRouteMatch()->getParam('action');
        $session = new Container('admin_user_session');
        
        $controller = $event->getRouteMatch()->getParam('controller');
//        echo '<pre>';print_r ($controller);echo '</pre>';die('Vikash');
        $adminuserStr = strpos(strtolower($controller),"admin");
        //echo '<pre>';var_dump ($adminuserStr);echo '</pre>';die('Vikash');
        $action = $event->getRouteMatch()->getParam('action');
        if (! ((strpos($name, 'reset-password') || (strpos($name, 'logout')) || @in_array($name, $list))) && $session->offsetExists('userId') && $adminuserStr !== FALSE ) {
            
            // to bypass ajax requests
                $roleID[0] = array();
                $serviceManager = $event->getApplication()->getServiceManager();
                $roleTable = $serviceManager->get('RoleTable');
                $userRoleTable = $serviceManager->get('UserRoleTable');
                $roleID = $userRoleTable->getUserRoles('user_id = ' . $session->offsetGet('userId'), array(
                    'role_id'
                ));
                $status = false;
                if(count($roleID)) {
                    if($roleID[0]['role_id'] !=""){
                        $roleName = $roleTable->getUserRoles('rid = ' . $roleID[0]['role_id'], array(
                            'role_name'
                        ));

                        $userRole = $roleName[0]['role_name'];

                        $acl = $serviceManager->get('Acl');
                        $acl->initAcl();
    //                    $status = $acl->isAccessAllowed($userRole, $controller, $action);
                        $status = $acl->isAccessAllowed($userRole, 'admin', $action);
                    }
                }
                if (! $status) {

                    //header("Location: ".$event -> getRequest() -> getBaseUrl() . '/404'); die;
                    $permissionTable = $serviceManager->get('PermissionTable');
                    $permissionArray = $permissionTable->getPermissionDetails($action);
                    $permissionLabel = (!empty($permissionArray)) ? ' for '. $permissionArray[0]['permission_label'] : '';
                    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){ 
                        die('Permission denied'. $permissionLabel);
                    } else {
                        if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ){
                            die('Permission denied'. $permissionLabel);
                        }else{
                            echo "<script> alert ('Permission denied $permissionLabel'); </script>";

                            exit;
                        }
                    }
                    
                }
            
        }
    }

    /**
     * Function GetConfig
     * Get the Config details
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    /**
     * Function Get Service Config
     *
     * @return multitype:multitype:NULL |\Zend\Authentication\AuthenticationService|\UsersACL\Utility\Acl|\UsersACL\Model\Role|\UsersACL\Model\UserRole|\UsersACL\Model\PermissionTable|\UsersACL\Model\ResourceTable|\UsersACL\Model\RolePermissionTable
     */
    public function getServiceConfig()
    { 
        return array(
            'factories' => array(
                'AuthService' => function ($serviceManager)
                {
                    $adapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $dbAuthAdapter = new DbAuthAdapter($adapter, 'users', 'email', 'password');
                    $auth = new AuthenticationService();
                    $auth->setAdapter($dbAuthAdapter);
                    return $auth;
                },
                'Acl' => function ($serviceManager)
                {
                    return new Acl();
                },
                'RoleTable' => function ($serviceManager)
                {
                    return new Role($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'UserRoleTable' => function ($serviceManager)
                {
                    return new UserRole($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'PermissionTable' => function ($serviceManager)
                {
                    return new PermissionTable($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'ResourceTable' => function ($serviceManager)
                {
                    return new ResourceTable($serviceManager->get('Zend\Db\Adapter\Adapter'));
                },
                'RolePermissionTable' => function ($serviceManager)
                {
                    return new RolePermissionTable($serviceManager->get('Zend\Db\Adapter\Adapter'));
                }
            )
        );
    }
}