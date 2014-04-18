<?php

$envName = "development";
// $envName = "live";
// $envName = "staging";

// $SYSTEM_CONST['development'][''] = "";
/**************** Development environment: *****************************/
$SYSTEM_CONST['development']['dbHost']  = "localhost";
$SYSTEM_CONST['development']['dbUser']  = "root";
$SYSTEM_CONST['development']['dbPass']  = "";
$SYSTEM_CONST['development']['dbName']  = "dbMD5Decode";
$SYSTEM_CONST['development']['md5salt'] = "fhpsdfs9df8hs9df8h7sd9fh79dsf8h";

$SYSTEM_CONST['development']['title']         = "beanstalk based MD5 decoder service";
$SYSTEM_CONST['development']['banner']        = "";
$SYSTEM_CONST['development']['background']    = "";
$SYSTEM_CONST['development']['bgcolor']       = "#ffffff";
$SYSTEM_CONST['development']['address_base']  = "http://localhost/md5decoder_service/src/";

$SYSTEM_CONST['development']['default_language_id']   = "1";
$SYSTEM_CONST['development']['register_state']        = "1";

$SYSTEM_CONST['development']['lock_max_length'] = 24 * 3600; // Maximum time a user can lock a record (in seconds).

/********************* Live environment: *******************************/
$SYSTEM_CONST['live']['dbHost']   = "localhost";
$SYSTEM_CONST['live']['dbUser']   = "";
$SYSTEM_CONST['live']['dbPass']   = "";
$SYSTEM_CONST['live']['dbName']   = "";
$SYSTEM_CONST['live']['md5salt']  = "fhpsdfs9df8hs9df8h7sd9fh79dsf8h";

$SYSTEM_CONST['live']['title']        = "Development template - Live.";
$SYSTEM_CONST['live']['banner']       = "";
$SYSTEM_CONST['live']['background']   = "";
$SYSTEM_CONST['live']['bgcolor']      = "#ffffff";
$SYSTEM_CONST['live']['address_base'] = "http://www.template.nl/";

$SYSTEM_CONST['live']['default_language_id']  = "1";
$SYSTEM_CONST['live']['register_state']       = "1";

$SYSTEM_CONST['live']['lock_max_length'] = 24 * 3600; // Maximum time a user can lock a record (in seconds).

/********************* Staging environment: *******************************/
$SYSTEM_CONST['staging']['dbHost']   = "localhost";
$SYSTEM_CONST['staging']['dbUser']   = "";
$SYSTEM_CONST['staging']['dbPass']   = "";
$SYSTEM_CONST['staging']['dbName']   = "";
$SYSTEM_CONST['staging']['md5salt']  = "fhpsdfs9df8hs9df8h7sd9fh79dsf8h";

$SYSTEM_CONST['staging']['title']        = "Development template - Staging";
$SYSTEM_CONST['staging']['banner']       = "";
$SYSTEM_CONST['staging']['background']   = "";
$SYSTEM_CONST['staging']['bgcolor']      = "#ffffff";
$SYSTEM_CONST['staging']['address_base'] = "http://www.template.nl/staging/";

$SYSTEM_CONST['staging']['default_language_id']  = "1";
$SYSTEM_CONST['staging']['register_state']       = "1";

$SYSTEM_CONST['staging']['lock_max_length'] = 24 * 3600; // Maximum time a user can lock a record (in seconds).

$env = $SYSTEM_CONST[$envName];

// $env['dbConn']  = mysql_connect($env['dbHost'], $env['dbUser'], $env['dbPass']) or die("cannot connect");
// mysql_select_db($env['dbName'], $env['dbConn']) or die("cannot select database");


$dsn = "mysql:dbname=".$env['dbName'].";host=".$env['dbHost']."";

try
{
  $dbConn         = new PDO($dsn, $env['dbUser'], $env['dbPass']);
  $env['dbConn']  = $dbConn;
}
catch(PDOException $e)
{
  die("cannot connect to database.");
}
?>
