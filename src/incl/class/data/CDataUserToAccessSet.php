<?php
include_once("CDataUserToAccess.php");

class CDataUserToAccessSet
{
  private $m_envs;

  function __construct($envs)
  {
    $this->m_envs = $envs;
  }

  public function getElementsByUser($userID)
  {
    $sql    = "SELECT id FROM tblUserToAccess WHERE userID=:userID;";
    $dbConn = $m_envs['dbConn'];
    $qry    = $dbConn->prepare($sql);
    $arr    = array();

    $qry->bindValue("userID", $userID, PDO::PARAM_INT);
    $qry->execute();

    $row = $qry->fetchAll();

    foreach($row as $rowElem)
    {
      // get ID
      $id = $rowElem[0];

      // create new userToAccess object.
      $pElem = new CDataUserToAccess($id, false, $this->m_envs);

      // add to array.
      array_push($arr, $pElem);
    }

    // return array of access elements.
    return $arr;
  }
}
?>
