<?php
include_once("CSecurePage.php");
include_once("incl/class/logic/CLogicMyMD5.php");


class CPageMyMD5 extends CSecurePage
{
  private $m_pLogicMyMD5;
  
  function __construct($envs)
  {
    $arrAccessReq = array("page-mymd5");
    CSecurePage::__construct($envs, $arrAccessReq);
    
    $this->m_pLogicMyMD5 = new CLogicMyMD5($envs);
  }
  
  
  public function createAddNewMD5String()
  {
    $code  = "";
    $code .= "<form name=\"form_new_md5\" action=\"mymd5.php\" method=\"post\">\n";
    $code .= "  New MD5: <input type=\"text\" name=\"txt_new_md5\" />\n";
    $code .= "  <input type=\"submit\" name=\"submit_new_md5\" value=\"Add\" />\n";
    $code .= "</form>\n";
    
    return $code;
  }
  
  public function createMD5Overview()
  {
    $code = "";
    
    /* create a list of all md5's added. For each md5: 
      - current status. 
      - time spent on cracking it. 
      - date added. 
      - options
    */ 
    
    // get md5's by user. 
    $userID = $this->getUserID();
    $arrMD5 = $this->m_pLogicMyMD5->getMD5SetByUserID($userID);
    
    // show overview. 
    $code .= "<table border=\"1\">\n";
    $code .= "  <tr><td>ID:</td><td>MD5:</td><td>Decoded</td><td>status:</td><td>time spent:</td><td>created:</td><td>options:</td></tr>\n";
    
    foreach($arrMD5 as $pMD5)
    {
      $id       = $pMD5->getValue("id");
      $md5      = $pMD5->getValue("md5string");
      $decoded  = $pMD5->getValue("md5decoded");      
      $spent    = $this->m_pLogicMyMD5->calcSpentTime($id);
      $created  = $pMD5->getValue("created");
      $status   = $decoded == "" ? "decoding" : "decoded";
      $decoded  = $decoded == "" ? "&nbsp;"   : $decoded;
      $code    .= "<tr>\n";
      $code    .= "  <td>$id</td>\n";
      $code    .= "  <td>$md5</td>\n";
      $code    .= "  <td>$decoded</td>\n";
      $code    .= "  <td>$status</td>\n";
      $code    .= "  <td>$spent</td>\n";
      $code    .= "  <td>$created</td>\n";
      $code    .= "  <td>\n";
      $code    .= "<a href=\"mymd5.php?cid=10&mid=$id\">Details</a></td>\n";
      $code    .= "</tr>\n";
    }
    
    $code .= "</table>\n";
    
    return $code;
  }
  
  
  public function createMD5Details()
  {
    $code       = "";
    $md5ID      = $_GET['mid'];    
    $pMD5       = $this->m_pLogicMyMD5->getMD5ByID($md5ID);
    $arrDetails = $this->m_pLogicMyMD5->getMD5Details($md5ID);
    $md5        = $pMD5->getValue("md5string");
    $decoded    = $pMD5->getValue("md5decoded");      
    $created    = $pMD5->getValue("created");    
    $spent      = $this->m_pLogicMyMD5->calcSpentTime($id);    
    
    $code .= "<table border=\"1\">\n";
    $code .= "  <tr><td>md5:</td><td>$md5</td></tr>\n";
    $code .= "  <tr><td>decoded:</td><td>$decoded &nbsp;</td></tr>\n";
    $code .= "  <tr><td>created:</td><td>$created</td></tr>\n";
    $code .= "  <tr><td>spent:</td><td>$spent seconds</td></tr>\n";
    $code .= "</table>\n";
    $code .= "<br />\n";
    
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
    
    $code .= "<table border=\"1\">\n";
    $code .= "  <tr><td>id:</td><td>status:</td><td>strStart:</td><td>strEnd:</td><td>tubename:</td><td>created:</td></tr>\n";
    
    foreach($arrDetails as $pDetail)
    {
      $id       = $pDetail->getValue("id");
      $status   = $pDetail->getValue("status");
      $strStart = $pDetail->getValue("strStart");
      $strEnd   = $pDetail->getValue("strEnd");
      $tubename = $pDetail->getValue("tubeName");
      $created  = $pDetail->getValue("created");
      
      $code .= "<tr>\n";
      $code .= "  <td>$id</td>\n";
      $code .= "  <td>$status</td>\n";
      $code .= "  <td>$strStart</td>\n";
      $code .= "  <td>$strEnd</td>\n";
      $code .= "  <td>$tubename</td>\n";
      $code .= "  <td>$created</td>\n";
      $code .= "</tr>\n";
    }
    
    $code .= "</table>\n";
    
    return $code;
  }
  
  protected function handle_new_md5()
  {
    $strMD5 = strtoupper($_POST['txt_new_md5']);
    $userID = $this->getUserID();
     
    return $this->m_pLogicMyMD5->addNewMD5($strMD5, $userID);
  }
}
?>