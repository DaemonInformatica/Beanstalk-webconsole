<?php
include_once("CSecurePage.php");
include_once("CLeftMenu.php");
include_once("incl/class/logic/CLogicLogin.php");

class CPageAdmin extends CSecurePage
{
  private $m_pLogicLogin;

  function __construct($env, $accessReq = array())
  {
    CSecurePage::__construct($env, $accessReq);

    $this->m_pLogicLogin = new CLogicLogin($env);
  }

  public function createHeader($pAccess, $meta = "", $bodyclass = "")
  {
    $bLoggedIn = true;

    if($this->m_cid == 1000)
      $bLoggedIn = false;

    $pAdminMenu = new CLeftMenu($pAccess);

    $code = "";

    $code .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
    $code .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
    $code .= "  <head>\n";
    $code .= "    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
    $code .= "    <title>Content Management System</title>\n";
    $code .= "    <link href=\"css/admin.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
    $code .=      $meta;
    $code .= "  </head>\n";

    if($bodyclass == "")
      $code .= "  <body>\n";
     else
      $code .= "  <body class=\"$bodyclass\">\n";

    $code .= "    <div id=\"top\">\n";
    $code .= "      <div id=\"logo\">\n";
    $code .= "        <a href=\"admin.php\"><img src=\"pics/admin/logo.png\" alt=\"logo\" /></a>\n";
    $code .= "        </div><!--end of logo-->\n";
    $code .= "    </div><!--end of top-->\n";
    $code .= "    <div id=\"content\">\n";
    $code .= "      <div id=\"left\">\n";

    if($bLoggedIn == false)
      $code .= "        <img src=\"pics/admin/cmspack.jpg\" alt=\"cmspackage\" />\n";
     else
      $code .= $pAdminMenu->showLeftMenu($pAccess);
    $code .= "      </div><!--end of left-->\n";
    $code .= "      <div id=\"right\">\n";


    return $code;
  }

  public function createFooter()
  {
    $code = "";

    $code .= "      </div><!--end of right-->\n";
    $code .= "    </div><!--end of content-->\n";
    $code .= "    <div id=\"footer\">\n";
    $code .= "      This CMS is powered by <a href=\"http://www.loeq.nl\" target=\"_blank\">loeqmedia</a>. Copyright 2012.\n";
    $code .= "    </div><!--end of footer-->\n";
    $code .= "  </body>\n";
    $code .= "</html>\n";

    return $code;
  }

  public function createLogin()
  {
    $code = "";

    $code .= "<form name=\"login\" action=\"admin.php?cid=1\" method=\"post\">\n";
    $code .= "<div class=\"righttop\"><h1>Inloggen</h1></div><!--end of righttop-->\n";
    $code .= "<div class=\"rightcontenthome\">\n";
    $code .= "  <div class=\"rightcontentin\">\n";
    $code .= "    <div class=\"steps\">\n";
    $code .= "      <div class=\"stap\">Stap 1: Vul hier uw inlognaam in.</div><!--end of stap-->\n";
    $code .= "      <input name=\"name\" type=\"text\" class=\"txtfield\" />\n";
    $code .= "    </div><!--end of steps-->\n";
    $code .= "    <div class=\"steps\">\n";
    $code .= "      <div class=\"stap\">Stap 2: Vul hier uw wachtwoord in.</div><!--end of stap-->\n";
    $code .= "      <input name=\"pass\" type=\"password\" class=\"txtfield\" />\n";
    $code .= "    </div><!--end of steps-->\n";
    $code .= "    <input name=\"submit\" type=\"submit\" value=\"Klik hier om in te loggen\" class=\"submit\" />\n";
    $code .= "  </div><!--end of rightcontentin-->\n";
    $code .= "</div><!--end of rightcontent-->\n";
    $code .= "</form>\n";

    return $code;
  }

  public function createYUIMeta()
  {
    $code = "";

    $code .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"http://yui.yahooapis.com/2.9.0/build/resize/assets/skins/sam/resize.css\" />\n";
    $code .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"http://yui.yahooapis.com/2.9.0/build/fonts/fonts-min.css\" />\n";
    $code .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"http://yui.yahooapis.com/2.9.0/build/imagecropper/assets/skins/sam/imagecropper.css\" />\n";

    $code .= "<script type=\"text/javascript\" src=\"http://yui.yahooapis.com/2.9.0/build/yahoo-dom-event/yahoo-dom-event.js\"></script>\n";
    $code .= "<script type=\"text/javascript\" src=\"http://yui.yahooapis.com/2.9.0/build/element/element-min.js\"></script>\n";
    $code .= "<script type=\"text/javascript\" src=\"http://yui.yahooapis.com/2.9.0/build/dragdrop/dragdrop-min.js\"></script>\n";
    $code .= "<script type=\"text/javascript\" src=\"http://yui.yahooapis.com/2.9.0/build/resize/resize-min.js\"></script>\n";
    $code .= "<script type=\"text/javascript\" src=\"http://yui.yahooapis.com/2.9.0/build/imagecropper/imagecropper-min.js\"></script>\n";

    return $code;
  }

  public function login()
  {
    $name = $_POST['name'];
    $pass = $_POST['pass'];

    $pUser = $this->m_pLogicLogin->authenticate($name, $pass);

    return $pUser != NULL;
  }
}
?>
