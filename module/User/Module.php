<?php
namespace User;


class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return array(
           
            'factories' => array(
                 'zfcuser_module_options' => function ($sm) {
                    $config = $sm->get('Config');
                    return new Options\ModuleOptions(isset($config['zfcuser']) ? $config['zfcuser'] : array());
                },

                'zfcuser_change_password_form' => function ($sm) {
                    $options = $sm->get('zfcuser_module_options');
                    $form = new Form\ChangePassword(null, $sm->get('zfcuser_module_options'));
                    $form->setInputFilter(new Form\ChangePasswordFilter($options));
                    return $form;
                },
            ),
        );
    }
    public function getViewHelperConfig() {
         return array(
           'factories' => array(
                    'SocialLogin' => function ($serviceManager) {
                    $serviceManager   = $serviceManager->getServiceLocator();
                    $containerService = $serviceManager->get('scnauth_mapper');
                    $auth = $serviceManager->get('zfcuser_auth_service');
                    $objauth = ($auth->hasIdentity())?$auth->getIdentity():'';
                    $social        = new \User\Helper\SocialLogin();
                    $social->setWebsiteService($containerService,$objauth);
                    $social->setAuthServices($auth);
                    return $social;
                },
                'profilepopupcontrol' => function ($serviceManager) {
                    $serviceManager   = $serviceManager->getServiceLocator();
                    $containerService = $serviceManager->get('Assessment\Model\UserOtherDetailsTable');
                    $getemstudent     = $serviceManager->get('Assessment\Model\TemsstudentsTable');
                    $userOtherDetailTable = $serviceManager->get('Assessment\Model\UserOtherDetailsTable');
                    $auth = $serviceManager->get('zfcuser_auth_service');
                    $tableUser        = $serviceManager->get('Assessment\Model\UserTable');
                    $trasactiontable =  $serviceManager->get('Package\Model\TusertransactionTable');
                    $profilecontrol        = new \User\Helper\ProfilePopupControl();
                    $profilecontrol->setWebsiteService($containerService,$tableUser,$getemstudent,$trasactiontable,$userOtherDetailTable);
                    $profilecontrol->setAuthServices($auth);
                    return $profilecontrol;
                }
            )
        );
        
   }   
    
}
