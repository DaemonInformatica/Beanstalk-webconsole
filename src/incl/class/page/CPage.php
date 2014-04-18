<?php
include_once("CLeftMenu.php");
include_once("incl/class/logic/CLogicLanguage.php");
include_once("incl/class/logic/CLogicLangNameConverter.php");

class CPage
{
  public    $m_cid;       // cid, commonly used in a webpage ro display case's in a switch.
  protected $m_envs;      // Key / value array with environment variables for the website.
  protected $m_language;  // CLogicLanguage object
  private   $m_arrErrors;

  function __construct($envs, $bLoadLanguage = true)
  {
    $this->m_envs       = $envs;
    $this->m_arrErrors  = array();

    if(isset($_GET['cid']))
      $this->m_cid = $_GET['cid'];
     else
      $this->m_cid = 0;


    if($bLoadLanguage == true)
    {
      // First try and see if the user selected a new language.
      if(!$this->resolveLanguageSel())
      {
        // If not, select a language from a different source.
        $this->loadLanguage();
      }
    }
  }

  private function handleSubmitButton($submitName)
  {
    $strName = "handle_".substr($submitName, 7);
    $bExists = method_exists($this, $strName);

    // print("CPage::handleSubmitButton: calling $strName<br />\n");
    
    if($bExists == true)
      return call_user_func(array($this, $strName));
    
    return true;
  }
  
  private function evalPostValue($post)
  {
    $bSuccess = true;
    
    // is the value an array? call evalPostValue($post)
    if(is_string($post) == true && strpos($post, "submit_") === 0)
    {
      $bSuccess &= $this->handleSubmitButton($post);
    }
    elseif(is_array($post) == true)
    {      
      foreach($post as $key => $value)
      {
        if(is_array($value) == true)
        {
          $this->evalPostValue($value);
        }
        // else: does the value start with 'submit_'? 
        elseif(is_string($value) == true)
        {
          // process as a handled method.
          if(strpos($key, "submit_") === 0)
            $bSuccess &= $this->handleSubmitButton($key);
        }
      } 
    }
    
    return $bSuccess;
  }
  
  private function evalSubmit()
  {
    $bSuccess = true;
    
    // for each post key, call evalPost($key);
    foreach($_POST as $key => $value)
      $bSuccess &= $this->evalPostValue($key);
       
    return $bSuccess;
  }
  
  public function initPage()
  {
    // process any submit signals. 
    return $this->evalSubmit();
  }
  
  public function getTitle() { return $this->m_envs['title']; }

  public function getMD5Salt() { return $this->m_envs['md5salt']; }

  public function getLanguageID() { return $this->m_language->getLanguageID(); }

  public function messageOfTheDay($username = "") 
  { 
    return "Master $username, I am here to serve you!"; 
  }

  public function getLanguageAbbreviation() { return $this->m_language->getLanguageAbbreviation(); }

  public function translate($key)
  {
    // print("CPage::translate: ". $this->m_language->translate($key)."<br>\n" );
    return $this->m_language->translate($key);
  }

  /*
    showHeader: Show the framework for the page, and include a menu structure somewhere.
      input:  $pUser:     reference to a CDataUser object.
              $meta:      A set of tags that go into the 'head' of the page.
              $script:    Any (java) script elements that go into the head of a page.
  */
  public function showHeader($pUser, $meta = "", $script = "", $strCrumb = "")
  {
    $code     = "";
    $baseAddr = $this->m_envs['address_base'];
    $title    = "<title>".$this->getTitle()."</title>";

    if(!empty($this->m_envs['background']))
      $background = "background=\"".$baseAddr.$this->m_envs['background']."\"";
     else
      $background = "";

    if(empty($this->m_envs['bgcolor']))
      $this->m_envs['bgcolor'] = "#ffffff";

    $bgcolor    = $this->m_envs['bgcolor'];

    if(empty($this->m_envs['banner']))
      $banner = "Banner";
     else
      $banner = "<img src=\"".$baseAddr.$this->m_envs['banner']."\">";

    $menu         = new CLeftMenu($pUser, 0);

    // $code .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
    $code .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
    $code .= "  <head>\n";
    $code .= "    $title\n";
    $code .= "    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
    $code .= "    $meta\n";
    $code .= "    $script\n";
    $code .= "  </head>\n";
    $code .= "  <body>\n";
    $code .= "  <table border=\"1\" width=\"100%\" height=\"100%\">\n";
    $code .= "    <tr height=\"15%\">\n";
    $code .= "      <td>$banner</td>\n";
    $code .= "    </tr>\n";
    $code .= "    <tr>\n";
    $code .= "      <td>\n";
    $code .= "        <table border=\"1\" width=\"100%\" height=\"100%\">\n";
    $code .= "        <tr>\n";
    $code .= "          <td width=\"10%\" valign=\"top\">\n";
    $code .=              $menu->showLeftMenu();
    $code .= "          </td>\n";
    $code .= "          <td valign=\"top\">\n";

    return $code;
  }

  /*
    showHeaderNoMenu: Show header without a menu.
  */
  public function showHeaderNoMenu()
  {
    $title    = "<title>".$this->m_envs['title']."</title>";
    $code     = "";
    $baseAddr = $this->m_envs['address_base'];

    if(!empty($this->m_envs['background']))
      $background = "background=\"".$baseAddr.$this->m_envs['background']."\"";
     else
      $background = "";

    if(empty($this->m_envs['bgcolor']))
      $this->m_envs['bgcolor'] = "#ffffff";

    $bgcolor    = $this->m_envs['bgcolor'];

    if(empty($this->m_envs['banner']))
      $banner = "Banner";
     else
      $banner = "<img src=\"".$baseAddr.$this->m_envs['banner']."\">";


    $code .= "<html>\n";
    $code .= "  <head>\n";
    $code .= "    $title\n";
    $code .= "  </head>\n";
    $code .= "  <body $background $bgcolor>\n";

    return $code;
  }

  public function showFooterNoMenu()
  {
    $code  = "";

    $code .= "  </body>\n";
    $code .= "</html>\n";

    return $code;
  }
  public function showFooter()

  {
    $code  = "";
    $code .= "            </td>\n";
    $code .= "          </tr>\n";
    $code .= "        </td>\n";
    $code .= "      </tr>\n";
    $code .= "    </table>\n";
    $code .= "  </body>\n";
    $code .= "</html>\n";

    return $code;

  }

  /*
    showAccessDenied: Message that is shown when a user has no access to a given page.
  */
  public function showAccessDenied()
  {
    return "<h2>Access Denied!</h2>";
  }

  private function resolveLanguageSel()
  {
    if(isset($_GET['lang']))
    {
      $languageID = addslashes($_GET['lang']);

      if(is_numeric($languageID) == false)
      {
        $pConv      = new CLogicLangNameConverter($this->m_envs);
        $languageID = $pConv->getLangIDByAbbr($languageID);
      }

      if($languageID != 0)
      {
        setcookie("lang", $languageID, 0, "/");

        $this->m_language = new CLogicLanguage($this->m_envs, $languageID);

        return true;
      }
    }

    return false;
  }

  /*
    loadLanguage: Figure out which language to load, and instantiate 'CLogicLanguage' accordingly.
  */
  private function loadLanguage()
  {
    // On an unmoderated page, load the standard language:
    if(isset($_COOKIE['lang']))
      $languageID = $_COOKIE['lang'];
     else
      $languageID = $this->m_envs['default_language_id'];

    $this->m_language = new CLogicLanguage($this->m_envs, $languageID);
  }

  protected function addError($error)
  {
    array_push($this->m_arrErrors, $error);
  }

  public function getErrors()
  {
    return $this->m_arrErrors;
  }

  public function getLangNameByID($langID)
  {
    return $this->m_language->getLangNameByID($langID);
  }
}
?>
