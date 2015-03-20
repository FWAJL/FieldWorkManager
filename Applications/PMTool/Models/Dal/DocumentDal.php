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
    $sql = 'SELECT d.* FROM `document` d';
    $sql .= ' where d.`document_category` = \'' . $document_category . '\' and d.`document_value` LIKE \''.$category_id.'\\_%\';';
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Document');

    $document_list = $query->fetchAll();
    $query->closeCursor();
    $list = $this->AddFilePathToObjectList($document_list);
    return $list;
  }

  public function selectOne($document) {
    if ($document->document_id() !== "") {//Check if the user is giving his username and that there is a value
      $tableName = \Applications\PMTool\Helpers\CommonHelper::GetShortClassName($document);
      $sql = 'SELECT * FROM `'.$tableName.'` where `document_id` = \'' . $document->document_id() . '\' LIMIT 0, 1;';
    } else {
      return NULL;
    }
    $query = $this->dao->query($sql);
    $query->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Applications\PMTool\Models\Dao\Document');

    $document_out = $query->fetch ();
    $query->closeCursor();

    return $document_out;
  }

}
