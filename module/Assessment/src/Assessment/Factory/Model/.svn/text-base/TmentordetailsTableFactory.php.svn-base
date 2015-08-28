<?php 
namespace Assessment\Factory\Model;
 
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway;
use Assessment\Model\TmentordetailsTable;
use Assessment\Model\Tmentordetails;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Common\Factory\Model\SlaveAdapter;
use Zend\Db\TableGateway\Feature;
 
class TmentordetailsTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $db = $serviceLocator->get('Zend\Db\Adapter\Adapter');
 
        $resultSetPrototype = new HydratingResultSet();
        $resultSetPrototype->setHydrator(new ObjectProperty());
        $resultSetPrototype->setObjectPrototype(new Tmentordetails());
 
        $tableGateway       = new TableGateway('t_mentor_details', $db, array(new Feature\MasterSlaveFeature($serviceLocator->get('SlaveAdapter')),new Feature\MasterSlaveFeature($serviceLocator->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($serviceLocator->get('SlaveAdapter3'))), $resultSetPrototype);
        $table              = new TmentordetailsTable($tableGateway);
 
        return $table;
    }
}