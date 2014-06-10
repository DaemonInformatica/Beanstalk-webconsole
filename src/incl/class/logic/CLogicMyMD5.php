<?php
include_once("CLogic.php");
include_once("incl/class/data/CDataMD5ProcSet.php");
include_once("incl/class/data/CDataMD5Set.php");


class CLogicMyMD5 extends CLogic
{
  function __construct($envs)
  {
    CLogic::__construct($envs);
  }

  public function getMD5SetByUserID($userID)
  {
    // print("CLogicMyMD5::getMD5SetByUserID: this->m_envs: <br />\n");
    // print_r($this->m_envs);
    // print("<br />\n");

    $pSet = new CDataMD5Set($this->m_envs);
    $arr  = $pSet->getMD5ByUserID($userID);

    return $arr;
  }

  public function calcSpentTime($md5ID)
  {
    $pSet = new CDataMD5ProcSet($this->m_envs);
    $arr  = $pSet->getProcsByMD5ID($md5ID);
    $sum  = 0;

    foreach($arr as $pProc)
    {
      $start  = $pProc->getValue("tsStart");
      $end    = $pProc->getValue("tsEnd");
      $end    = $end == 0 ? $start : $end;
      $diff   = $end - $start;
      $sum   += $diff;
    }

    return $sum;
  }


  public function addNewMD5($strMD5, $strStartAt, $userID)
  {
    // has user added this one before? update feedback and return false.
    $pMD5   = new CDataMD5(0, false, $this->m_envs);
    $bFound = $pMD5->loadIDByMD5AndUser($strMD5, $userID);

    if($bFound == true)
    {
      $this->addError("MD5 already in database.");
      return false;
    }

    // create new CDataMD5 and store it.
    /*
    tblMD5
    - id          INT PRIMARY KEY AUTO_INCREMENT
    - md5string   VARCHAR(32)
    - md5decoded  VARCHAR(40)
    - created     DATETIME
    - updated     DATETIME
    - createdBy   INT
    - updatedBy   INT
    */

    $pMD5     = new CDataMD5(0, false, $this->m_envs);
    $created  = date("Y-m-d H:m:i");

    $pMD5->setValue("md5string",  $strMD5,      true);
    $pMD5->setValue("start",      $strStartAt,  true);
    $pMD5->setValue("created",    $created,     true);
    $pMD5->setValue("createdBy",  $userID,      true);
    $pMD5->setValue("updatedBy",  0,            true);
    $pMD5->insertValues();

    // return success.
    return true;
  }


  public function getMD5Details($md5ID)
  {
    $pSet = new CDataMD5ProcSet($this->m_envs);
    $arr  = $pSet->getProcsByMD5ID($md5ID);

    return $arr;
  }


  public function getMD5ByID($md5ID)
  {
    $pMD5 = new CDataMD5($md5ID, false, $this->m_envs);

    return $pMD5;
  }
}
?>