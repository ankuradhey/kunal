<?php
namespace Assessment\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql;
/**
 *
 * @author extramarks
 *     */
class MentorQuestionTable extends TableGateway
{
    // TODO - Insert your code here
    protected $tableGateway;
    /**
     *
     * @param string $table            
     *
     * @param Adapter $adapter            
     *
     * @param Feature\AbstractFeature|Feature\FeatureSet|Feature\AbstractFeature[] $features            
     *
     * @param ResultSetInterface $resultSetPrototype            
     *
     * @param Sql $sql            
     *
     * @throws Exception\InvalidArgumentException
     *
     */
    
    public function __construct(TableGateway $tableGateway){
    	$this->tableGateway=$tableGateway;
    }
    
	//public function insertdata-This function insert mentor questions
    public function insertdata($data)
    {
          $this->tableGateway->insert($data);
          $id = $this->tableGateway->lastInsertValue;
          return $id;
    }
    
     //public function getQuestionDetails-This function get mentor questions by questionId
    public function getQuestionDetails($questionId)
    { 
        $select = $this->tableGateway->getSql()->select();
                $select->where("mentor_question_id=$questionId") ;       
           $questionDetails = $this->tableGateway->selectWith($select);
           
        return $questionDetails;
    }
    
}


