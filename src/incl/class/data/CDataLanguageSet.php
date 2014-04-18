<?php
include_once("CDataLanguage.php");
include_once("CDataLanguageLine.php");

class CDataLanguageSet
{

  private $m_arrLanguages;
  private $m_bActiveOnly;
  private $m_envs;
  private $m_dbConn;

  function __construct($bActiveOnly, $envs)
  {
    $this->m_arrLanguages = array();
    $this->m_bActiveOnly  = $bActiveOnly;
    $this->m_envs         = $envs;
    $this->m_dbConn       = $envs['dbConn'];

    $this->loadLanguages();
  }

  /*
    getLanguageList: Get the raw array of all languages loaded by specified parameters in the constructor of this class.
  */
  public function getLanguageList() { return $this->m_arrLanguages; }

  /*
    loadLanguages: called on initialization: Load all elements specified by parameters.
  */
  private function loadLanguages()
  {
    $sql = "SELECT id FROM tblLanguage";
    if($this->m_bActiveOnly)
    {
      $sql .= " WHERE active=1;";
    }
     else
    {
      $sql .= ";";
    }

    $qry = $this->m_dbConn->prepare($sql);
    
    $qry->execute();
    
    while($row = $qry->fetch())
    {
      $id = $row[0];
      $language = new CDataLanguage($id, false, $this->m_envs);
      array_push($this->m_arrLanguages, $language);
    }
  }

  /*
    getLanguageByID: return a CDataLanguage object by its id.

      input:  (int)$id: id of the language object.

      output: (CDataLanguage)language object
  */
  public function getLanguageByID($id)
  {
    $langLen = count($this->m_arrLanguages);

    for($i = 0; $i < $langLen; $i++)
    {
      $currLang = $this->m_arrLanguages[$i];
      if($currLang->getValue("id") == $id)
        return $currLang;
    }

    return NULL;
  }

  /*
    getKeys: Get all keys in the language set.
  */
  public function getKeys()
  {

    /*
    tblLanguageLine
    - id          INT PRIMARY KEY AUTO_INCREMENT
    - languageID  INT
    - fieldname   TEXT
    - value       TEXT
    - active      INT
    - created     DATETIME
    - updated     DATETIME
    - createdBy   INT
    - updatedBy   INT
    */

    $sql      = "SELECT fieldname FROM tblLanguageLine GROUP BY fieldname ORDER BY fieldname ASC;";
    $qry      = $this->m_dbConn->prepare($sql);
    $arrKeys  = array();
    
    $qry->execute();

    while($row = $qry->fetch())
    {
      $fieldName = $row[0];
      
      array_push($arrKeys, $fieldName);
    }

    return $arrKeys;
  }

  public function getLinesByLanguage($languageID)
  {
    $arr = array();
    $sql = "SELECT id FROM tblLanguageLine WHERE languageID=:languageID";
    $qry = $this->m_envs['dbConn']->prepare($sql);

    $qry->bindValue("languageID", $languageID, PDO::PARAM_INT);
    $qry->execute();
    
    while($row = $qry->fetch())
    {
      $id             = $row[0];
      $pLanguageLine  = new CDataLanguageLine($id, false, $this->m_envs);
      
      array_push($arr, $pLanguageLine);
    }

    return $arr;
  }

}

?>
