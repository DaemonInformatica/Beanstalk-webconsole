<?php
include_once("CDataAccessElement.php");

class CDataGroup
{
  private $m_groupID;
  private $m_dbConn;
  public  $m_elements;

  function __construct($groupID, $dbConn)
  {
    $this->m_groupID  = $groupID;
    $this->m_dbConn   = $dbConn;
    $m_elements       = array();
    
    $this->getElements();
  }

  /*
    getElements: Get all elements of this User group.
  */
  private function getElements()
  {
    $groupID      = $this->m_groupID;
    $sqlElements  = "SELECT id FROM tblGroupToAccess WHERE groupID=:groupID;";
    $qryElements  = $this->m_dbConn->prepare($sqlElements);
    
    $qryElements->bindValue("groupID", $groupID,  PDO::PARAM_INT);
    $qryElements->execute();
    
    while($rowElements = $qryElements->fetch())
    {
      $id 		  = $rowElements[0];
      $element 	= new CDataAccessElement($id, false, $this->m_dbConn);
      $key 		  = $element->getValue("name");
      
      array_push($this->m_elements[$key], $element);
    }
  }
}
?>
