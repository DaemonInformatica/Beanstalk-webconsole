<?php
include("incl/const.php");
include_once("incl/class/page/CSecurePage.php");

$page   = new CSecurePage($env);
$access = $page->getUserAccess();

print($page->showHeader($access));

if(!$page->m_isSecure)
  $page->m_cid = 1000;

switch($page->m_cid)
{
  case 0:
    // point of entry

    break; // end of case 0

  case 1000:
    // Access denied!
    print($page->showAccessDenied());
    break; // end of case 1000
}
print($page->showFooter());
?>