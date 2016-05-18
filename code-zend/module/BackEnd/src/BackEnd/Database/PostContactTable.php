<?php
namespace BackEnd\Database;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\AbstractTableGateway;


class PostContactTable{
    const POST_CONTACT_TABLE="post_contact";
    
    /** @var  Sql $sql */
    protected $sql;

    public function __construct($adapter) {
        $this->sql = new Sql($adapter);
    }
     public function getAll($type='',$col='') {
        $select = $this->sql->select();
        $select->columns(array('*'))->from(self::POST_TAX_HISTORY_TABLE);
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $resultSet = \Zend\Stdlib\ArrayUtils::iteratorToArray($result);
        $result->buffer();
        $result->next();
        return $resultSet;
     }
     public function getPostTaxbyPostID($data) {
//         var_dump($data);
         $select=$this->sql->select();
         $select->columns(array('*'))->from(self::POST_TAX_HISTORY_TABLE);
         $select->where(new \Zend\Db\Sql\Predicate\In("post_id", $data));
         $statement = $this->sql->prepareStatementForSqlObject($select);
//         var_dump($statement);
        $result = $statement->execute();
        $resultSet = \Zend\Stdlib\ArrayUtils::iteratorToArray($result);
        return $resultSet;
     }
      public function DelContactbyPostId($data) {
         $del=$this->sql->delete();
         $del->from(self::POST_CONTACT_TABLE);
         $del->where(new \Zend\Db\Sql\Predicate\In("post_id", $data));
         $statementdel = $this->sql->prepareStatementForSqlObject($del);
        $result = $statementdel->execute();
//        $resultSet = \Zend\Stdlib\ArrayUtils::iteratorToArray($result);
        return true;
     }
}

