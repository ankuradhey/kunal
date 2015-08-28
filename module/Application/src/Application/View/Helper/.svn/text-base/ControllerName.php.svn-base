<?php
namespace Application\View\Helper;
use Zend\View\Helper\AbstractHelper;

class ControllerName extends AbstractHelper
{

 protected $routeMatch;
 public function __construct($routeMatch)
 {
   $this->routeMatch = $routeMatch;
 }
 public function __invoke()
  {
        if ($this->routeMatch) {
            $route      =  array();
            $route['controller'] = $this->routeMatch->getParam('controller', 'index');
            $route['action']     = $this->routeMatch->getParam('action', 'index'); 
            return $route;
        }
   }
}