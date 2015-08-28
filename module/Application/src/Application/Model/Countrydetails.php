<?php
namespace Application\Model;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
class Countrydetails implements ServiceManagerAwareInterface
{
    protected $entityManager;
    public $serviceManager;
    
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
    		$this->setEntityManager($this->getServiceManager()->get('Doctrine\ORM\EntityManager'));
    	}
    	return $this->entityManager;
    }
    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
    	return $this->serviceManager;
    }
    
    /**
     * Set service manager instance
     *
     * @param ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
    	$this->serviceManager = $serviceManager;
    	return $this;
    }
    
    function listOf(){
       $repository = $this->getEntityManager()->getRepository('Application\Entity\Country');
     return  $repository->findAll();
    }
}

?>