<?php
/**
 * Application\View
 * 
 * @author
 * @version 
 */
namespace Application\View;

use Zend\View\Helper\AbstractHelper;
/**
 * View Helper
 */
class Country extends AbstractHelper  
{
 
    
    public function __invoke()
    {
    return "dee";
    
    	return $this->getView()->render('application/index/country',array(""));
    
    	// If a full template is overkill, you could of course just render
    	// the widget directly
    
    }
    
}
