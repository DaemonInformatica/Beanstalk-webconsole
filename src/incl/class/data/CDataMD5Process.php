<?php
include_once("CData.php");

class CDataMD5Process extends CData
{

  function __construct($id, $bPrefetchAll, $envs)
  {
    if($id == 0)
      $bPrefetchAll = false;

    $arr = array("id", "md5ID ", "status", "strStart", "strEnd", "tsStart ", "tsEnd ", "tubeName", "created ", "updated ", "createdBy ", "updatedBy ");
    CData::__construct($id, $bPrefetchAll, "tblMD5Process", $envs, $arr);
  }
}
?>