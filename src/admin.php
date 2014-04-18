<?php
include("incl/const.php");
include("incl/class/page/CPageAdmin.php");

$page   = new CPageAdmin($env);
$access = $page->getUserAccess();


if(!isset($_POST['submit']) && !$page->m_isSecure)
  $page->m_cid = 1000;

if($page->m_cid == 1)
  $bSuccess = $page->login();

print($page->createHeader($access));

// print("admin.php: cid: $cid<br />\n");
switch($page->m_cid)
{
  case 1:
    // process login.


    if($bSuccess == true)
    {
      print("
    <div class=\"righttop\"><h1>Login Succesvol</h1></div><!--end of righttop-->
    <div class=\"rightcontenthome\">
    <div class=\"rightcontentin\">
    
	<div class=\"steps\">
  		Klik <a href=\"admin.php\">hier</a> om verder te gaan.   
    </div><!--end of steps-->
   
    </div><!--end of rightcontentin-->
    </div><!--end of rightcontent-->
	");
    }
    else
    {
      print("
	  <div class=\"righttop\"><h1>Login Mislukt</h1></div><!--end of righttop-->
    <div class=\"rightcontenthome\">
    <div class=\"rightcontentin\">
    
	<div class=\"steps\">
  		Ongeldige naam / wachtwoord combinatie. <br />Klik <a href=\"admin.php\">hier</a> om verder te gaan.   
    </div><!--end of steps-->
   
    </div><!--end of rightcontentin-->
    </div><!--end of rightcontent-->
	  ");
    }

    break; // end of case 1
  case 1000:
    // Login screen.
    print($page->createLogin());
    break;
}

print($page->createFooter());
?>