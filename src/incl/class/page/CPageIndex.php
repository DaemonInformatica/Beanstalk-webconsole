<?php
include_once("CPage.php");
include_once("incl/class/logic/CLogicIndex.php");

class CPageIndex extends CPage
{
  private $m_logicIndex;
  private $m_pLogicCat;

  function __construct($env)
  {
    CPage::__construct($env);

    $this->m_logicIndex = new CLogicIndex($this->m_envs);
  }

  // public function getUserAccess() { return $this->m_logicIndex->getUserAccess(); }
  
  public function showStats()
  {
    $code = "";
    
    // Show count of total md5's, to crack and cracked.
    $all        = $this->m_logicIndex->getMD5Count("total");
    $cracked    = $this->m_logicIndex->getMD5Count("cracked");
    $uncracked  = $this->m_logicIndex->getMD5Count("to_crack");
    
    $code .= "Stats: \n";
    $code .= "<table border=\"1\">\n";
    $code .= "  <tr><td>Total md5's:</td><td>$all</td></tr>\n";
    $code .= "  <tr><td>Cracked;:</td><td>$cracked</td></tr>\n";
    $code .= "  <tr><td>Uncracked:</td><td>$uncracked</td></tr>\n";
    $code .= "</table>\n";
    
    return $code; 
  }

  public function getUser()       { return $this->m_logicIndex->getUser(); }
}
?>
