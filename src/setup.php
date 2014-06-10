<?php
include("incl/const.php");
include("incl/class/page/CPage.php");

$page   = new CPage($env);

$sql    = array();
$entry  = array();

// array_push($sql, "");
array_push($sql, "CREATE TABLE tblUser(id INT PRIMARY KEY AUTO_INCREMENT, name TEXT, username TEXT, password TEXT, email TEXT, state ENUM('registered', 'active', 'blocked'), session TEXT, languageID INT, userGroup TEXT, IP TEXT, lastLogin DATETIME, created DATETIME, updated DATETIME, createdBy INT, updatedBy INT);");
array_push($sql, "CREATE TABLE tblUserGroup(id INT PRIMARY KEY AUTO_INCREMENT, name TEXT, created DATETIME, updated DATETIME, createdBy INT, updatedBy INT);");
array_push($sql, "CREATE TABLE tblAccessElements(id INT PRIMARY KEY AUTO_INCREMENT, name TEXT, description TEXT, active INT, created DATETIME, updated DATETIME, createdBy INT, updatedBy INT);");
array_push($sql, "CREATE TABLE tblGroupToAccess(id INT PRIMARY KEY AUTO_INCREMENT, groupID INT, accessID INT, created DATETIME, updated DATETIME, createdBy INT, updatedBy INT);");
array_push($sql, "CREATE TABLE tblUserToAccess(id INT PRIMARY KEY AUTO_INCREMENT, userID INT, accessID INT, created DATETIME, updated DATETIME, createdBy INT, updatedBy INT);");
array_push($sql, "CREATE TABLE tblLogbook(id INT PRIMARY KEY AUTO_INCREMENT, IP TEXT, description TEXT, type INT, website TEXT, created DATETIME, updated DATETIME, createdBy INT, updatedBy INT);");
array_push($sql, "CREATE TABLE tblLanguage(id INT PRIMARY KEY AUTO_INCREMENT, name TEXT, description TEXT, active INT, abbreviation TEXT, created DATETIME, updated DATETIME, createdBy INT, updatedBy INT);");
array_push($sql, "CREATE TABLE tblLanguageLine(id INT PRIMARY KEY AUTO_INCREMENT, languageID INT, fieldname TEXT, value TEXT, active INT, created DATETIME, updated DATETIME, createdBy INT, updatedBy INT);");
array_push($sql, "CREATE TABLE tblMD5(id INT PRIMARY KEY AUTO_INCREMENT, md5string VARCHAR(32), start VARCHAR(40), md5decoded VARCHAR(40), created DATETIME, updated DATETIME, createdBy INT, updatedBy INT);");
array_push($sql, "CREATE TABLE tblMD5Process(id INT PRIMARY KEY AUTO_INCREMENT, md5ID INT, status ENUM('waiting', 'processing', 'completed'), strStart VARCHAR(40), strEnd VARCHAR(40), tsStart BIGINT, tsEnd BIGINT, tubeName VARCHAR(32), created DATETIME, updated DATETIME, createdBy INT, updatedBy INT);");
array_push($sql, "CREATE TABLE tblWorkerStatus(id INT PRIMARY KEY AUTO_INCREMENT, IP TEXT, hostname TEXT, lastReported DATETIME, created DATETIME, updated DATETIME, createdBy INT, updatedBy INT);");


// array_push($entry, "");
/********************************/
// Initial entries for this database.

/******* tblUser: ***************/

$name       = "System Admin";
$username   = "admin";
$salt       = $page->getMD5Salt();
$passwd     = md5($salt."mypassword");
$email      = "admin@email.com";
$state      = "active";
$languageID = 1;
$userGroup  = "administrator";
$created    = date("Y-m-d H:i");
$createdBy  = 1;
/*
tblUser
- id          INT PRIMAIRY KEY AUTO_INCREMENT
- name        TEXT
- username    TEXT
- password    TEXT
- email       TEXT
- state       ENUM('registered', 'active', 'blocked')
- session     TEXT
- languageID  INT
- userGroup   TEXT
- IP          TEXT
- lastLogin   DATETIME
- created     DATETIME
- updated     DATETIME
- createdBy   INT
- updatedBy   INT
*/

array_push($entry, "INSERT INTO tblUser VALUES(0, '$name', '$username', '$passwd', '$email', '$state', '', $languageID, '$userGroup', '', '', '$created', '', $createdBy, 0);");



/******* tblAccessElements: ***************/
/*
tblAccessElements
- id          INT PRIMARY KEY AUTO_INCREMENT
- name        TEXT
- description TEXT
- active      INT
- created     DATETIME
- updated     DATETIME
- createdBy   INT
- updatedBy   INT*/

$name         = "adm-groupman";
$description  = "Group administration";

array_push($entry, "INSERT INTO tblAccessElements VALUES(0, '$name', '$description', 1, '$created', '', $createdBy, 0);");

$name         = "adm-userman";
$description  = "Group administration";

array_push($entry, "INSERT INTO tblAccessElements VALUES(0, '$name', '$description', 1, '$created', '', $createdBy, 0);");

$name         = "cms-edit-lang";
$description  = "Language administration";

array_push($entry, "INSERT INTO tblAccessElements VALUES(0, '$name', '$description', 1, '$created', '', $createdBy, 0);");


$name         = "page-mymd5";
$description  = "Show My MD5 page.";

array_push($entry, "INSERT INTO tblAccessElements VALUES(0, '$name', '$description', 1, '$created', '', $createdBy, 0);");

/******* tblUserToAccess: ***************/
/*
tblUserToAccess
- id        INT PRIMARY KEY AUTO_INCREMENT
- userID    INT
- accessID  INT
- created   DATETIME
- updated   DATETIME
- createdBy INT
- updatedBy INT
*/

$userID   = 1;
$accessID = 1;

array_push($entry, "INSERT INTO tblUserToAccess VALUES(0, $userID, $accessID, '$created', '', $createdBy, 0);");

$accessID = 2;

array_push($entry, "INSERT INTO tblUserToAccess VALUES(0, $userID, $accessID, '$created', '', $createdBy, 0);");

$accessID = 3;

array_push($entry, "INSERT INTO tblUserToAccess VALUES(0, $userID, $accessID, '$created', '', $createdBy, 0);");

$accessID = 4;

array_push($entry, "INSERT INTO tblUserToAccess VALUES(0, $userID, $accessID, '$created', '', $createdBy, 0);");

print($page->showHeaderNoMenu());

switch($page->m_cid)
{
  case 0:
    ?>
    <form name="install" action="setup.php?cid=1" method="post">
      Make database: <input type="submit" name="submit" value="Make">
    </form>
    <?php
    break; // end of case 0
  case 1:
    print("Building tables: <br>");

    for($i = 0; $i < count($sql); $i++)
    {
      print("Building table: $sql[$i]... ");

      $qry = $env['dbConn']->prepare($sql[$i]);

      $qry->execute();
      print("done.<br>");
    }

    print("Done building tables.<br> executing entries:<br>");

    for($i = 0; $i < count($entry); $i++)
    {
      print("Executing entry: $entry[$i]... ");
      // mysql_query($entry[$i], $env['dbConn']) or die("Error in entry $entry[$i]<br>");
      $qry = $env['dbConn']->prepare($entry[$i]);

      $qry->execute();
      print("done.<br>");
    }

    print("<br><br>Database installation complete. Have a nice day.");
    break; // end of case 1
}

print($page->showFooter());
?>
