<?php 
namespace Notification\Factory\Model;
 
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use Notification\Model\TchaterResourceTable;
use Notification\Model\TchaterResource;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Album\Factory\Model\SlaveAdapter;
use Zend\Db\TableGateway\Feature;
 
class TChepterResourceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $db = $serviceLocator->get('Zend\Db\Adapter\Adapter');
 
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype->setHydrator(new ObjectProperty());
        $resultSetPrototype->setObjectPrototype(new TchaterResource());
 
        $tableGateway       = new TableGateway('resource_rack', $db, array(new Feature\MasterSlaveFeature($serviceLocator->get('SlaveAdapter')),new Feature\MasterSlaveFeature($serviceLocator->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($serviceLocator->get('SlaveAdapter3'))), $resultSetPrototype);
        $table              = new TchaterResourceTable($tableGateway);
 
        return $table;
    }
}