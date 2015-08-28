<?php
namespace Assessment\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql;
/**
 *
 * @author extramarks
 *     */
class MentorPaperUserAnswerTable extends TableGateway
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
    
	//public function insertdata-This function inserted user given answer to mentor created questions
    public function insertdata($data)
    {
          $this->tableGateway->insert($data);
          $id = $this->tableGateway->lastInsertValue;
          return $id;
    }
    
   //public function updateData-This function update user answers
    public function updateData($id, $data)
    {
         
       if($id != null)
       {
           // update the existing record
           
           $updateArray = array('mentor_paper_user_ans_id' => $id);
           $this->tableGateway->update($data, $updateArray); 
       }
    }
    
    
}


