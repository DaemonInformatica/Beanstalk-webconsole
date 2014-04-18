<?php
include_once("CData.php");

class CDataLanguageLine extends CData
{

  function __construct($id, $bPrefetchAll, $envs)
  {

    $arr = array("id", "languageID", "fieldname", "value", "active", "created", "updated", "createdBy", "updatedBy");
    CData::__construct($id, $bPrefetchAll, "tblLanguageLine", $envs, $arr);
  }

  /*
    getTranslationByKey: Get a translation directly by its languageID and key.

      input:  (int)$languageID: id of the language.
              (String)$key:     name of the element.
  */
  public function getTranslationByKey($languageID, $key)
  {
    $sql = "SELECT id FROM tblLanguageLine WHERE languageID=:languageID AND fieldname=:key";
    $qry = $this->getConnection()->prepare($sql);
    
    $qry->bindValue("key",        $key,         PDO::PARAM_STR);
    $qry->bindValue("languageID", $languageID,  PDO::PARAM_INT);
    $qry->execute();
    
    if($row = $qry->fetch())
    {
      $id = $row[0];
      
      $this->setValue("id",         $id,          true);
      $this->setValue("languageID", $languageID,  true);
      $this->setValue("fieldName",  $key,         true);
      
      return true;
    }

    return false;
  }
}
?>
