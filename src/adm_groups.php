<?php
include("incl/const.php");
include_once("incl/class/page/CPageGroupMan.php");

// $arrAccElem = array("page1");
$page   = new CPageGroupMan($env);
$access = $page->getUserAccess();
$pUser  = $page->getUser();

print($page->showHeader($pUser));


if(!$page->m_isSecure)
  $page->m_cid = 1000;

$actionResult = true;

if(isset($_GET['aid']) && $page->m_cid != 1000)
{
  $aid = $_GET['aid'];
  switch($aid)
  {
    case 10:
      // remove an access element from the given group.
      $actionResult = $page->removeElementFromGroup();

      break; // end of case 10

    case 20:
      // add an access element to the given group.
      $actionResult = $page->addElementToGroup();

      break; // end of case 20
  }
}

?>
<?php

switch($page->m_cid)
{
  case 0:
    // point of entry
    print($page->createTop());
    print("<div class=\"rightcontent\">\n");
    print("  <div class=\"rightcontentin\">\n");
    // Show a list of groups.
    print("<a href=\"adm_groups.php?cid=40\">Manage access elements.</a><br /><br />");

    print($page->createNewGroup());
    print("<br><br>\n");
    print($page->createGroupsList());
    print("  </div><!--end of rightcontentin-->\n");
    print("</div><!--end of rightcontent-->\n");
    break; // end of case 0

  case 10:
  	print($page->createTop());
    print("<div class=\"rightcontent\">\n");
    print("  <div class=\"rightcontentin\">\n");
    // store new group:
    if($page->storeNewGroup())
    {
      print("New Group stored successfully. Click <a href=\"adm_groups.php\">here</a> to continue.<br>\n");
    }
    else
    {
      $errors = $page->getErrors();
      print("New group".$errors."<br>");
    }
	print("  </div><!--end of rightcontentin-->\n");
    print("</div><!--end of rightcontent-->\n");
    break; // end of case 10

  case 20:
  	print($page->createTop());
    print("<div class=\"rightcontent\">\n");
    print("  <div class=\"rightcontentin\">\n");
    // edit a given group
    if($actionResult == false)
      print($page->getLogicErrors());

    print($page->createEditGroup());
    print("<br />");
    print($page->createAddRemoveElements());
    print("  </div><!--end of rightcontentin-->\n");
    print("</div><!--end of rightcontent-->\n");
	break; // end of case 20

  case 21:
  	print($page->createTop());
    print("<div class=\"rightcontent\">\n");
    print("  <div class=\"rightcontentin\">\n");
    // store an edited group
    if($page->storeEditGroup())
    {
      print("Group updated successfully. Click <a href=\"adm_groups.php\">here</a> to continue.<br>\n");
    }
    else
    {
      $errors = $page->getErrors();
      print("Update group:<br><br>".$errors."<br> Click <a href=\"adm_groups.php\">here</a> to continue.<br>");
    }
	print("  </div><!--end of rightcontentin-->\n");
    print("</div><!--end of rightcontent-->\n");
    break; // end of case 21.
	
  case 30:
    print($page->createTop());
    print("<div class=\"rightcontent\">\n");
    print("  <div class=\"rightcontentin\">\n");
    
    // remove a given group
    $page->deleteGroup();
    print("Group deleted successfully. Click <a href=\"adm_groups.php\">here</a> to continue.<br>\n");
    print("  </div><!--end of rightcontentin-->\n");
    print("</div><!--end of rightcontent-->\n");
    break; // end of case 30

  case 40:
  	print($page->createTop());
    print("<div class=\"rightcontent\">\n");
    print("  <div class=\"rightcontentin\">\n");
    // Show interface to create new element.
    print($page->createNewAccessElement());

    // Show list of elements to edit.
    print($page->createElementList());
    print("  </div><!--end of rightcontentin-->\n");
    print("</div><!--end of rightcontent-->\n");
    break; // end of case 40

  case 41:
  	print($page->createTop());
    print("<div class=\"rightcontent\">\n");
    print("  <div class=\"rightcontentin\">\n");
    // Store new Element.
    if($page->storeNewElement())
    {
      print("Element stored successfully. Click <a href=\"adm_groups.php?cid=40\">here</a> to continue.<br>\n");
    }
    else
    {
      $errors = $page->getLogicErrors();
      print("Error(s) storing access element:<br><br>".$errors."<br> Click <a href=\"adm_groups.php?cid=40\">here</a> to continue.<br>");
    }
	print("  </div><!--end of rightcontentin-->\n");
    print("</div><!--end of rightcontent-->\n");
    break; // end of case 41.

  case 50:
  	print($page->createTop());
    print("<div class=\"rightcontent\">\n");
    print("  <div class=\"rightcontentin\">\n");
    // Edit element.
    print($page->createEditAccessElement());
	print("  </div><!--end of rightcontentin-->\n");
    print("</div><!--end of rightcontent-->\n");
    break; // end of case 50.

  case 51:
  	print($page->createTop());
    print("<div class=\"rightcontent\">\n");
    print("  <div class=\"rightcontentin\">\n");
    // Store edit element.
    if($page->storeEditElement())
    {
      print("Element updated successfully. Click <a href=\"adm_groups.php?cid=40\">here</a> to continue.<br>\n");
    }
    else
    {
      $errors = $page->getLogicErrors();
      print("Error(s) updating access element:<br><br>".$errors."<br> Click <a href=\"adm_groups.php?cid=40\">here</a> to continue.<br>");
    }
	print("  </div><!--end of rightcontentin-->\n");
    print("</div><!--end of rightcontent-->\n");
    break; // end of case 51.

  case 1000:
  	print($page->createTop());
    print("<div class=\"rightcontent\">\n");
    print("  <div class=\"rightcontentin\">\n");
    // Access denied!
    $page->showAccessDenied();
	print("  </div><!--end of rightcontentin-->\n");
    print("</div><!--end of rightcontent-->\n");
    break; // end of case 1000
}
print($page->showFooter());
?>
