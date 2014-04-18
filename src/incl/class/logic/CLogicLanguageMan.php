<?php
include_once("incl/class/data/CData.php");
include_once("incl/class/data/CDataLanguage.php");
include_once("incl/class/data/CDataLanguageLine.php");
include_once("incl/class/data/CDataLanguageSet.php");

class CLogicLanguageMan
{
  private $m_envs;
  private $m_dataLanguageSet;


  function __construct($envs)
  {
    $this->m_envs             = $envs;
    $this->m_dataLanguageSet  = new CDataLanguageSet(false, $envs);
  }

  /*
    getKeys: Get all defined language keys in the database.
      output(array(int, String)): array with keys.
  */
  public function getKeys() { return $this->m_dataLanguageSet->getKeys(); }

  /*
    getLanguageList: Get an array of language objects.
      output: (array(int, CDataLanguage)): array of languages defined in the database.
  */
  public function getLanguageList() { return $this->m_dataLanguageSet->getLanguageList(); }

  public function getTranslationByKey($languageID, $key)
  {
    $language = $this->m_dataLanguageSet->getLanguageID($languageID);

    return $language->translate($key);
  }

  public function updateTranslationByKey($languageID, $key, $value, $updatedBy)
  {
    $language = $this->m_dataLanguageSet->getLanguageByID($languageID);

    return $language->updateLine($key, $value, $updatedBy);
  }

  public function createNewLanguage($abbr, $name, $description, $copyFromID, $createdBy)
  {
    // create a new language object.
    $pLanguage  = new CDataLanguage(0, false, $this->m_envs);
    $created    = date("Y-m-d H:i");

    $pLanguage->setValue("name",          $name,              true);
    $pLanguage->setValue("description",   $description,       true);
    $pLanguage->setValue("abbreviation",  $abbr,              true);
    $pLanguage->setValue("active",        1,                  true);
    $pLanguage->setValue("created",       $created,           true);
    $pLanguage->setValue("createdBy",     $createdBy,         true);
    $pLanguage->setValue("updatedBy",     0,                  true);

    $pLanguage->insertValues();

    $newLangID = $pLanguage->getValue("id");

    // Fetch every single language line from the database with the languageID '$copyfromID'.
    $arrLangLines = $this->m_dataLanguageSet->getLinesByLanguage($copyFromID);
    $lenLangLines = count($arrLangLines);

    // for each element:
    for($i = 0; $i < $lenLangLines; $i++)
    {
      $pLangLine  = $arrLangLines[$i];
      $fieldName  = $pLangLine->getValue("fieldname");
      $value      = $pLangLine->getValue("value");
      $active     = $pLangLine->getValue("active");

      // create a new language line.
      $pNewLine = new CDataLanguageLine(0, false, $this->m_envs);

      // set the language ID to the new language.
      $pNewLine->setValue("languageID", $newLangID, true);

      // copy the values of the to copy line to the new object.
      $pNewLine->setValue("fieldname",  $fieldName, true);
      $pNewLine->setValue("value",      $value,     true);
      $pNewLine->setValue("active",     $active,    true);
      $pNewLine->setValue("created",    $created,   true);
      $pNewLine->setValue("createdBy",  $createdBy, true);
      $pNewLine->setValue("updatedBy",  0,          true);

      $pNewLine->insertValues();
    }
  }
}
?>
