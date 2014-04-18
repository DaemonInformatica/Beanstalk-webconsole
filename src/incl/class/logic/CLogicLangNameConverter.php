<?php
include_once("CLogic.php");
include_once("incl/class/data/CDataLanguageSet.php");

class CLogicLangNameConverter extends CLogic
{
  function __construct($envs)
  {
    CLogic::__construct($envs);
  }

  public function getLangIDByAbbr($abbr)
  {
    $id     = 0;
    $pLang  = new CDataLanguage(0, false, $this->m_envs);
    $bFound = $pLang->loadIDByAbbr($abbr);

    if($bFound == true)
      $id = $pLang->getValue("id");

    // return ID
    return $id;
  }
}
?>
