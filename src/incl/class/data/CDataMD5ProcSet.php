<?php
include_once("CDataSetBase.php");
include_once("CDataMD5Process.php");

class CDataMD5ProcSet extends CDataSetBase
{

  function __construct($envs)
  {
    CDataSetBase::__construct($envs);
  }
  
  public function getProcsByMD5ID($md5ID)
  {
    /*
    tblMD5Process
    - id        INT PRIMARY KEY AUTO_INCREMENT
    - md5ID     INT
    - status    ENUM('waiting', 'processing', 'completed')
    - strStart  VARCHAR(40)
    - strEnd    VARCHAR(40)
    - tsStart   BIGINT
    - tsEnd     BIGINT
    - tubeName  VARCHAR(32)
    - created   DATETIME
    - updated   DATETIME
    - createdBy INT
    - updatedBy INT
    */
    $arr = array();
    $sql = "SELECT id FROM tblMD5Process WHERE md5ID=:id ORDER BY created DESC";
    $qry = $this->getConnection()->prepare($sql);
    
    $qry->bindValue("id", $md5ID, PDO::PARAM_INT);
    $qry->execute();
    
    while($row = $qry->fetch())
    {
      $id     = $row[0];
      $pProc  = new CDataMD5Process($id, false, $this->m_envs);
      
      array_push($arr, $pProc);
    }
    
    return $arr;
  }
}
?>