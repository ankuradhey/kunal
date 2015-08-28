<?php
namespace Notificationside\Factory\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Notification\View\Helper\NotificationWidget;


class NotificationsideFactory implements FactoryInterface 
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $pluginManager)
    {
        /* @var $pluginManager HelperPluginManager */
        $serviceManager = $pluginManager->getServiceLocator();

       /* @var $authService AuthenticationService */
        $authService = $serviceManager->get('zfcuser_auth_service');

        $viewHelper = new NotificationWidget();
        $viewHelper->setAuthService($serviceManager);

        return $viewHelper;
    }
}
