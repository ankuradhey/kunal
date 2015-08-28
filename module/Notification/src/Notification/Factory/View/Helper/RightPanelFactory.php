<?php
namespace Container\Factory\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Container\View\Helper\RightPanelWidget;


class RightPanelFactory implements FactoryInterface 
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

        $viewHelper = new SubjectContainerWidget();
        $viewHelper->setAuthService($serviceManager);

        return $viewHelper;
    }
}
