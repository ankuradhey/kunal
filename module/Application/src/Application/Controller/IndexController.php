<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Countrydetails;
use Application\Entity\User;
use Doctrine\ORM\EntityManager;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;

class IndexController extends AbstractActionController
{
     protected $service;
  
    public function getService() {
        if (!$this->service) {
            $this->service = $this->getServiceLocator()->get('lms_container_service');
        }
        return $this->service;
    }
    
    /**
     * @var EntityManager
     */
    protected $entityManager;
    
    /**
     * Sets the EntityManager
     *
     * @param EntityManager $em
     * @access protected
     * @return PostController
     */
    protected function setEntityManager(EntityManager $em)
    {
    	$this->entityManager = $em;
    	return $this;
    }
    
    /**
     * Returns the EntityManager
     *
     * Fetches the EntityManager from ServiceLocator if it has not been initiated
     * and then returns it
     *
     * @access protected
     * @return EntityManager
     */
    protected function getEntityManager()
    {
    	if (null === $this->entityManager) {
    		$this->setEntityManager($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
    	}
    	return $this->entityManager;
    }
   public function indexAction()
    {}
    
    public function headerforzf1Action(){
        $result = new ViewModel();
        $result->setTerminal(true);
        return $result;
    }
    /**
     * @author Ashutosh(23/2/15 4:08 PM)
     */
    public function footerforzf1Action(){
        $result = new ViewModel();
        $result->setTerminal(true);
        return $result;
    }
    public function countryAction(){  
        $repository = $this->getServiceLocator()->get("countrydetails");
        $countryList=$repository->listOf();
 
      
         return $this->layout()->setVariable("countryList",$countryList);
    }
	 public function schoolsolutionAction() {
        return new ViewModel();
    }
	public function smartlearnclassAction() {
        return new ViewModel();
    }

    public function classroomtabAction() {
        return new ViewModel();
    }

    public function testcenterAction() {
        return new ViewModel();
    }

    public function ccereportAction() {
        return new ViewModel();
    }

    public function schoolmangementAction() {
        return new ViewModel();
    }
public function getleafnodeAction()
    {
        $classId = $this->params()->fromRoute('classId');
        $rackId = $this->getService()->getLeafContainer($classId);
        echo Json::encode($rackId);
        exit;
    }
}
