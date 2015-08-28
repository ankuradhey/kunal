<?php
/**
 * EvaEngine
 *
 * @link      https://github.com/AlloVince/eva-engine
 * @copyright Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Eva_Api.php
 * @author    AlloVince
 */

namespace Application\View;

use Zend\View\Helper\AbstractHelper,
    Zend\ServiceManager\ServiceLocatorAwareInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    Zend\View\Exception;

/**
 * Call a Controller action
 * 
 * @category   Eva
 * @package    Eva_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2012 AlloVince (http://avnpc.com/)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Action extends \Zend\View\Helper\AbstractHelper implements ServiceLocatorAwareInterface
{


    /**
    * @var ServiceLocatorInterface
    */
    protected $serviceLocator;

    /**
    * Set the service locator.
    *
    * @param ServiceLocatorInterface $serviceLocator
    * @return AbstractHelper
    */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }


    public function __invoke($moduleName,$controllerName, $actionName, $params = array())
    {   $actionName=$actionName."Action";
    $str='\Controller\\';
      $controllerName=$moduleName.$str.$controllerName."Controller";
        $controllerLoader = $this->serviceLocator->getServiceLocator()->get('ControllerLoader');
        $controllerLoader->setInvokableClass($controllerName, $controllerName);
        $controller = $controllerLoader->get($controllerName);
        $data= $controller->$actionName($params);
       // print_r($data->countryList);
       $this->getView()->Country();
        echo $this->getView()->render('application/index/country',array("countryList"=>$data->countryList));
    }

    public function newinvoke($actionName,$moduleName=null,$controllerName=null,$params = array())
    { $actionView=$actionName;$controllerView=$controllerName;$moduleView=$moduleName;
    $actionName=$actionName."Action";$moduleName=ucfirst($moduleName);$controllerName=ucfirst($controllerName);
    $str='\Controller\\';
    $controllerName=$moduleName.$str.$controllerName."Controller";
    $controllerLoader = $this->serviceLocator->getServiceLocator()->get('ControllerLoader');
    $controllerLoader->setInvokableClass($controllerName, $controllerName);
    $controller = $controllerLoader->get($controllerName);
    
    $data= $controller->$actionName($params);
    
    if($controllerView !='' && $moduleView !=''){
    	return $this->getView()->render($moduleView.'/'.$controllerView.'/'.$actionView,$params);
    }else{
    	throw new Exception('Invalid Parameters');
    }
    }
    

}
