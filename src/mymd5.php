<?php
include("incl/const.php");
include_once("incl/class/page/CPageMyMD5.php");

$page   = new CPageMyMD5($env);
$access = $page->getUserAccess();

// print("_POST:<br />");
// print_r($_POST);
// print("<Br />\n");
print($page->showHeader($access));

if(!$page->m_isSecure)
  $page->m_cid = 1000;
 else
  $page->initPage();
  

switch($page->m_cid)
{
  case 0:
    // point of entry
    print($page->createAddNewMD5String());
    print($page->createMD5Overview());
    
    break; // end of case 0

  case 10:
    // details. 
    print($page->createMD5Details());
    
    break;
    
  case 1000:
    // Access denied!
    print($page->showAccessDenied());
    
    break; // end of case 1000
}

print($page->showFooter());
?>