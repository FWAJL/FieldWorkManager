<?php

namespace Applications\PMTool\Models\Dal;

if (!defined('__EXECUTION_ACCESS_RESTRICTION__'))
  exit('No direct script access allowed');

class DocumentDal extends \Library\DAL\Modules\DocumentDal {

  /**
   * Returns list of documents by document_category and category id
   *
   * @param string $document_category
   * @param integer $category_id
   * @return array of \Applications\PMTool\Models\Dao\Document
   */
  public function selectManyByCategoryAndId($document_category, $category_id) {
    $category_id .= '\_%';
    $sql = 'SELECT d.* FROM `document` d';
    $sql .= ' where d.`document_category` = :document_category and d.`document_value` LIKE :category_id;';
    $sth = $this->dao->prepare($sql);
    $sth->bindValue(':document_category',$document_category,\PDO::PARAM_STR);
    $sth->bindValue(':category_id',$category_id,\PDO::PARAM_STR);
    $sth->execute();
    $sth->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Document');

    $document_list = $sth->fetchAll();
    $sth->closeCursor();
    $list = $this->AddFilePathToObjectList($document_list);
    return $list;
  }

  public function selectOne($document) {
    if ($document->document_id() !== "") {//Check if the user is giving his username and that there is a value
      $tableName = \Applications\PMTool\Helpers\CommonHelper::GetShortClassName($document);
      $sql = 'SELECT * FROM `'.$tableName.'` where `document_id` = :document_id LIMIT 0, 1;';
    } else {
      return NULL;
    }

    $sth = $this->dao->prepare($sql);
    $sth->bindValue(':document_id',$document->document_id(),\PDO::PARAM_INT);
    $sth->execute();
    $sth->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Document');

    $document_out = $sth->fetch ();
    $sth->closeCursor();

    return $document_out;
  }

  public function GetFormsForTaskLocation($task_location_id) {
    $sql = 'select * from document where document_value LIKE :search_str';
    $dao = $this->dao->prepare($sql);
    $dao->bindValue(':search_str', $task_location_id . '_%', \PDO::PARAM_STR);
    $dao->execute();
    $dao->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Document');
    $search_res = $dao->fetchAll();
    $dao->closeCursor();
    return $search_res;
  }

}
