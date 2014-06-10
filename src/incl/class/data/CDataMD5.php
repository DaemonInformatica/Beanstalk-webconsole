<?php
include_once("CData.php");

class CDataMD5 extends CData
{

  function __construct($id, $bPrefetchAll, $envs)
  {
    if($id == 0)
      $bPrefetchAll = false;

    // print("CDataMD5::__construct: envs: <Br />\n");
    // print_r($envs);
    // print("<br />\n");

    $arr = array("id", "md5string", "start", "md5decoded", "created", "updated", "createdBy", "updatedBy");
    CData::__construct($id, $bPrefetchAll, "tblMD5", $envs, $arr);
  }

  public function loadIDByMD5AndUser($strMD5, $userID)
  {
    $sql = "SELECT id FROM tblMD5 WHERE md5string=:md5 AND createdBy=:userID";
    $qry = $this->getConnection()->prepare($sql);

    $qry->bindValue("md5",    $strMD5, PDO::PARAM_STR);
    $qry->bindValue("userID", $userID, PDO::PARAM_INT);
    $qry->execute();

    if($row = $qry->fetch())
    {
      $id = $row[0];
      $this->setValue("id",         $id,      true);
      $this->setValue("userID",     $userID,  true);
      $this->setValue("md5string",  $strMD5,  true);

      return true;
    }

    return false;
  }
}
?>