<?php
include_once("CData.php");
include_once("CDataAccessElement.php");
include_once("CDataUserToAccess.php");

class CDataUser extends CData
{
  public  $m_access;

  function __construct($id, $bPrefetchAll, $envs)
  {
    $this->m_access = NULL;
    $arr            = array("id", "name", "username", "password", "email", "state", "session", "languageID", "userGroup", "IP", "lastLogin", "created", "updated", "createdBy", "updatedBy");

    if($id == 0)
      $bPrefetchAll = false;

    CData::__construct($id, $bPrefetchAll, "tblUser", $envs, $arr);
    
    // if($this->isValid() == true )
    //   $this->initUserAccess();    
  }

  /*
    initUserAccess: Initialize the collection of access elements for this user.
  */
  // private function initUserAccess()
  // {
  //   $this->m_access = new CDataUserToAccess($this->m_envs, $this->getValue("id"));
  // }

  /*
    hasAccessTo: See if the user has access to a specific element.
      input:  (String)$elementName: Name of the element to check on.
      ouput:  (boolean) true: User has access.
                        false: User does not have access.
  */
  public function hasAccessTo($elementName)
  {
    // if($this->m_access == NULL)
    //  initUserAccess();
    $userID   = $this->getValue("id");
    $pAccess  = new CDataUserToAccess(0, false, $this->m_envs);
    $pElem    = new CDataAccessElement(0, false, $this->m_envs);
    
    $pElem->getAccessByName($elementName);
    
    $accessID = $pElem->getValue("id");
    
    $bHasAccess = $pAccess->getIDByReferences($userID, $accessID);
    
    return $bHasAccess;
  }

  /*
    revokeAccessTo: revoke access to a specified element.
      input: (String)$elementName: name of the element the user should no longer have access to.
  */
  public function revokeAccessTo($elementName)
  {
    $pElem    = new CDataAccessElement(0, false, $this->m_envs);
    $pAccess  = new CDataUserToAccess(0, false, $this->m_envs);
    
    $pElem->getAccessByName($elementName);
    
    $accessID   = $pElem->getValue("id");
    $bHasAccess = $pAccess->getIDByReferences($userID, $accessID);
    
    if(bHasAccess == true)
      $pAccess->deleteRow();
  }

  public function addAccessTo($elementName, $createdBy)
  {
    $this->m_access->addAccessTo($elementName, $createdBy);
  }

  /*
    getIDByUsername: Get the id of a given user by a given username.
                      The id isn't returned as a value, but stored in the dataset.
                      It can then be read by getValue. Also 'prefetchAllValues' can then be called to get the details.
      input: (String) username: username of the Employee.
      output: (Boolean): true if user was found, false otherwise.

    This function is used during login, and maybe registration (to see if a username already exists?).
  */
  public function getIDByUsername($username)
  {
    $tblName  = $this->getTblName();
    $conn     = $this->getConnection();

    $this->setValue("id", 0, true);

    // $sqlID = "SELECT id FROM :tblName WHERE username=:username";
    $sqlID = "SELECT id FROM tblUser WHERE username=:username";
    $qryID = $this->getConnection()->prepare($sqlID);
    
    // print("CDataUser::getIDByUsername: username: $username<br />\n");
    
    // $qryID->bindValue("tblName",  $tblName,   PDO::PARAM_STR);
    $qryID->bindValue("username", $username,  PDO::PARAM_STR);
    
    $bSuccess = $qryID->execute();
    
    // print("CDataUser::getIDByUsername: bSuccess: $bSuccess<br />\n");
    
    if($rowID = $qryID->fetch())
    {
      $id = $rowID[0];
      
      $this->setValue("id",       $id,        true);
      $this->setValue("username", $username,  true);
      
      return true;
    }

    return false;
  }
}
?>
