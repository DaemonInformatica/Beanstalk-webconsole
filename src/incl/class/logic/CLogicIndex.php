<?php
include_once("incl/class/data/CDataLanguageSet.php");
include_once("incl/class/data/CDataUser.php");

class CLogicIndex
{

  private $m_envs;  // Array with website environment variables.
  private $m_user;  // CData user object of the currently running session

  function __construct($envs)
  {
    $this->m_envs = $envs;

    // Check user session.
    if(!$this->loadUser())
      $this->m_user = NULL;

    // if($this->m_user != NULL)
      // print("CLogicIndex::__construct: user found!");
  }

  /*
    loadUser: Check to see if there's a session cookie containing an id and session.
      output: (boolean) true:   Session found and verified.
                        false:  No (valid) session found
  */
  private function loadUser()
  {
    // Get a session cookie and dissect it for id and session value.
    if(!isset($_COOKIE['session']))
      return false;

    $cki    = $_COOKIE['session'];
    $arrCki = explode(";", $cki);
    // print("CLogicIndex::loadUser: cki: $cki<br />\n");
    
    if(count($arrCki) < 2)
      return false;

    $id       = $arrCki[0];
    $session  = $arrCki[1];

    // validate id and session values.
    if($id == 0 || $id == "")
      return false;
    
    if($session == "")
      return false;
    
    // print("CLogicIndex::loadUser: preliminary checks completed<br />\n");
    
    // Get a user by ID from the database.
    $this->m_user = new CDataUser($id, false, $this->m_envs);

    // compare session in the cookie with the user object:
    $userSession = $this->m_user->getValue("session");
    
    // print("CLogicIndex::loadUser: database session: $userSession<br />\n");
    
    
    if($session != $userSession)
      return false;

    // User is valid and the session is active. return success.
    return true;
  }

  /*
    getUserName: If there's a user, return its name, otherwise return "".
      output: (String)User's name field.
  */
  public function getUserName()
  {
    if($this->m_user == NULL)
      return "";

    return $this->m_user->getValue("Name");
  }

  /*
    getUserAccess: get the user's access field.
      output: (CDataUserToAccess) reference to a user's access properties.
  */
  /*
  public function getUserAccess()
  {
    if($this->m_user == NULL)
      return NULL;

    return $this->m_user->m_access;
  }  
  */

  public function getUser() { return $this->m_user; }

  public function getLanguageSet()
  {
    $pLangSet = new CDataLanguageSet(false, $this->m_envs);
    $arrLang  = $pLangSet->getLanguageList();

    return $arrLang;
  }
  
  public function getMD5Count($state)
  {
    $count  = 0;
    $pSet   = new CdDataMD5Set($this->m_envs);
    
    switch($state)
    {
      case "total":
        break; // end of case "total":
        $count = $pSet->getSetSizeAll();
        
      case "to_crack":
        $count = $pSet->getSetSizeUncracked();
        break; // end of case "to_crack":
        
      case "cracked":
        $count = $pSet->getSetSizeCracked();
        break; // end of case "cracked":
    }
    
    return $count;
  }
}
?>
