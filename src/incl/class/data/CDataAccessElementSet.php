<?php
include_once("CDataAccessElement.php");
include_once("CDataSetBase.php");

class CDataAccessElementSet extends CDataSetBase
{
  // private $m_envs;

  function __construct($envs)
  {
    CDataSetBase::__construct($envs);
    $this->m_envs = $envs;
  }

  /*
    getElements: Get all access elements. Link to group to access and Filter on groupID, if groupID is given.

      input: (int)$groupID: optional: if given (and != 0) only the access elements for this group is given.

      output: (array(int, CDataAccessElement))$arr: result of the query.
  */
  public function getElements($groupID = 0)
  {
    $arr      = array();
    $groupID  = addslashes($groupID);

    if($groupID == 0)
    {
      $sql = "SELECT id FROM tblAccessElements ORDER BY name ASC;";
      $qry = $this->getConnection()->prepare($sql);

    }
     else
    {
      $sql = "SELECT e.id FROM tblAccessElements e, tblGroupToAccess a WHERE e.id=a.accessID AND a.groupID=:groupID ORDER BY name ASC;";
      $qry = $this->getConnection()->prepare($sql);

      $qry->bindValue("groupID", $groupID, PDO::PARAM_INT);
    }

    // $qry = mysql_query($sql, $this->m_envs['dbConn']);
    $qry->execute();

    $rows = $qry->fetchAll();

    foreach($rows as $row)
    {
      $id       = $row[0];
      $pAccess  = new CDataAccessElement($id, false, $this->m_envs);

      array_push($arr, $pAccess);
    }

    return $arr;
  }
}
?>
