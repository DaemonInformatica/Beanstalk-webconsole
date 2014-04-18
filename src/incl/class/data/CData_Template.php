<?php
include_once("CData.php");

class CData_Template extends CData
{
  private $m_arrLines;

  function __construct($id, $bPrefetchAll, $envs)
  {
    $arrColNames = array("id", "created", "updated", "createdBy", "updatedBy");

    CData::__construct($id, $bPrefetchAll, "tbl", $envs, $arrColNames);

    $this->m_arrLines = array();
  }
}
?>