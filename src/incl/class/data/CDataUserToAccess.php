<?php
include_once("CData.php");


class CDataUserToAccess extends CData
{
  function __construct($id, $bPrefetchAll, $envs)
  {
    if($id == 0)
      $bPrefetchAll = false;

    $arr = array("id", "userID", "accessID", "created", "updated", "createdBy", "updatedBy");

    CData::__construct($id, $bPrefetchAll, "tblUserToAccess", $envs, $arr);
  }

  public function getIDByReferences($userID, $accessID)
  {
    $sql = "SELECT id FROM tblUserToAccess WHERE userID=:userID AND accessID=:accessID;";
    $qry = $this->getConnection()->prepare($sql);

    $qry->bindValue("userID", $userID,      PDO::PARAM_INT);
    $qry->bindValue("accessID", $accessID,  PDO::PARAM_INT);
    $qry->execute();
    
    if($row = $qry->fetch())
    {
      $id = $row[0];

      $this->setValue("id",       $id,        true);
      $this->setValue("userID",   $userID,    true);
      $this->setValue("accessID", $accessID,  true);

      return true;
    }

    return false;
  }
}
?>
