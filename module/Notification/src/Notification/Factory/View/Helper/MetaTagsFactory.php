<?php
namespace Notificationside\Factory\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Notification\View\Helper\MetaTagsWidget;


class MetaTagsFactory implements FactoryInterface 
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

        $viewHelper = new MetaTagsWidget();
        $viewHelper->setAuthService($serviceManager);

        return $viewHelper;
    }
}
