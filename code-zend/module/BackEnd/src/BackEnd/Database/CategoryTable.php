<?php

namespace BackEnd\Database;

use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Where;
use Zend\Stdlib\ArrayUtils;
use Zend\Db\Sql\Expression;

//use BackEnd\Database\P;
//use BackEnd\Database\DistrictTable;
//use BackEnd\Database\;

class CategoryTable {

    const CATEGORY_TABLE = "category";
    const POST_TABLE = "post";
//    const POST_POST_IMAGE_TABLE="post_image";
//    const POST_TAX_HISTORY_TABLE="post_tax_history";
//    const POST_CONTACT_TABLE="post_contact";
//    const POST_FEUTURE_DETAILL_TABLE="post_feuture_detall";
//    const COMMENT_TABLE="comment";
//    const RATING_TABLE="rating";
//    

    /** @var  Sql $sql */
    protected $sql;
    protected $adapter;

    public function __construct($adapter) {
        $this->sql = new Sql($adapter);
        $this->adapter = $adapter;
    }

    public function getAll($type = '', $col = '') {
        
        $select = $this->sql->select();
        $select->columns(array('*'))->from(self::CATEGORY_TABLE);
//        if($type != '' && $sort != '')  $select->order(array("$type $sort"));
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        $resultSet = \Zend\Stdlib\ArrayUtils::iteratorToArray($result);
        $result->buffer();
        $result->next();
        return $resultSet;
    }

    public function getPostAndDelAll($id = '') {
        //table post
        $post = new PostTable($this->adapter);
        $selectpost = $post->getPostbyCategory($id);
        //table post_image
        $postimg = new PostImageTable($this->adapter);
        //tabel post_tax_history
        $posttax = new PostTaxHistoryTable($this->adapter);
        //table post_contact
        $postcontact = new PostContactTable($this->adapter);
        //table post_feature_detailt
        $postfeature = new PostFeatureDetailTable($this->adapter);
        //table comment table
        $postcomment = new PostCommentTable($this->adapter);
        //table rating table
        $postrating = new PostRatingTable($this->adapter);

        $data = array();
        foreach ($selectpost as $value) {
            $data[] = $value["id"];
        }
        //delete all post_image table by post_id
        $delpostimg = $postimg->DelPostbyPostID($data);
        //delete all post_tax_history table by post_id
        $delpostTax = $posttax->DelPostTaxbyPostID($data);
        //delete all post_contact table by post_id
        $delpostcontact = $postcontact->DelContactbyPostId($data);
        //delete all post_Feature table by post_id
        $delpostfeaturedetail = $postfeature->DelFeatureDetailbyPostId($data);
        //delete all post_comment table by post_id
        $delpostcomment = $postcomment->DelCommentbyPostId($data);
        //delete all post_rating table by post_id
        $delpostrating = $postrating->DelRatingbyPostId($data);

        if ($delpostTax == true && $delpostimg == true && $delpostcontact == true && $delpostfeaturedetail == true && $delpostcomment == true && $delpostrating == true) {
            $delpost = $post->DelPostbyCategoryId($id);
            //delete post table success
            if ($delpost == true) {
                //delete category by id
                $deleCate = $this->sql->delete();
                $deleCate->from(self::CATEGORY_TABLE)->where(array("id" => $id));
                $statementdel = $this->sql->prepareStatementForSqlObject($deleCate);
                try {
                    $result = $statementdel->execute();
                    return $result = TRUE;
     
                } catch (Exception $e) {
                    return $result = FALSE;
                }
            }
        } else
            return $result = FALSE;
    }
    public function saveData($id='') {
        
    }

}
