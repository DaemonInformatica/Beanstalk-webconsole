<?php
include("incl/const.php");
include("incl/class/page/CPageIndex.php");

$page       = new CPageIndex($env);

$page->initPage();

$pUser      = $page->getUser();
$username   = $pUser != NULL ? $pUser->getValue("name") : "";

print($page->showHeader($pUser));
print($page->messageOfTheDay($username));
print($page->showFooter());
?>
