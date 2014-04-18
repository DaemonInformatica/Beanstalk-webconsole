<?php
include_once("CData.php");

class CDataWorkerStatus extends CData
{

  function __construct($id, $bPrefetchAll, $envs)
  {
    if($id == 0)
      $bPrefetchAll = false;
      
    $arr = array("id", "IP", "hostname", "lastReported", "created", "updated", "createdBy", "updatedBy");
    CData::__construct($id, $bPrefetchAll, "tblWorkerStatus", $envs, $arr);
  }
}
?>