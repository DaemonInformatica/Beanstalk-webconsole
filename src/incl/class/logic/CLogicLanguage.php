<?php
include_once("incl/class/data/CDataLanguage.php");
include_once("incl/class/data/CDataLanguageSet.php");

class CLogicLanguage
{
  private $m_envs;      // database resource
  private $m_language;  // CDataLanguage Object, instantiated with the id, passed through the constructor.

  function __construct($envs, $languageID)
  {
    $this->m_envs     = $envs;
    $this->m_language = new CDataLanguage($languageID, false, $envs);
  }

  public function getLanguageID() { return $this->m_language->getValue("id"); }

  /*
    call the translate function of a CDataLanguage object.
      input:  (string)key: name of the line.
      output: (string)translation: the value corresponding to the key, in the given language.
  */
  public function translate($key) { return $this->m_language->translate($key); }

  public function getLanguageSet()
  {
    $pLangSet = new CDataLanguageSet(true, $this->m_envs);
    $arrLang  = $pLangSet->getLanguageList();

    return $arrLang;
  }

  public function getLangNameByID($langID)
  {
    $pLang  = new CDataLanguage($langID, false, $this->m_envs);
    $name   = $pLang->getValue("name");

    return $name;
  }

  public function getLanguageAbbreviation()
  {
    if($this->m_language == NULL)
      return "";

    $abbr = $this->m_language->getValue("abbreviation");

    return $abbr;
  }
}
?>
