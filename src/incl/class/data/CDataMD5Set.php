<?php
include_once("CDataSetBase.php");
include_once("CDataMD5.php");

class CDataMD5Set extends CDataSetBase
{

  function __construct($envs)
  {
    CDataSetBase::__construct($envs);    

    // print("CDataMD5Set::__construct: envs:<Br />\n");
    // print_r($envs);
    // print("<br />\n");

        
    // print("CDataMD5Set::__construct: this->m_envs:<Br />\n");
    // print_r($this->m_envs);
    // print("<br />\n");
  }
  
  private function getCountByWhere($strWhere)
  {
    if($strWhere != "")
      $sql = "SELECT count(ID) FROM tblMD5 WHERE $strWhere";
     else
      $sql = "SELECT count(ID) FROM tblMD5";
      
     $qry = $this->getConnection()->prepare($sql);
     $qry->execute();
     
     $row   = $qry->fetch();
     $count = $row[0];
     
     return $count;
  }
  
  public function getSetSizeAll() { return $this->getCountByWhere(); }
  
  public function getSetSizeUncracked() { return $this->getCountByWhere("md5decoded = ''"); }
  
  public function getSetSizeCracked() { return $this->getCountByWhere("md5decoded != ''"); }
  
  public function getMD5ByUserID($userID)
  {
    $arr = array();
    $sql = "SELECT id FROM tblMD5 WHERE createdBy=:userID";
    $qry = $this->getConnection()->prepare($sql);
    
    $qry->bindValue("userID", $userID, PDO::PARAM_INT);
    $qry->execute();
    
    
    
    while($row = $qry->fetch())
    {
      $id   = $row[0];
      $pMD5 = new CDataMD5($id, false, $this->m_envs);
      
      $pMD5->setValue("createdBy", $userID, true);
      array_push($arr, $pMD5);
    }
    
    return $arr;
  }
}
?>