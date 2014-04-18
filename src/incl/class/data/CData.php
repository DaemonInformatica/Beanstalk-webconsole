<?php
include_once("IData.php");

abstract class CData implements IData
{
  protected $m_envs;
  private   $m_oldValues;
  private   $m_newValues;
  private   $m_tblName;
  private   $m_bValid;
  protected $m_arrColNames;
  private   $m_bLockable;
  private   $m_dbConn;

  function __construct($id, $bPrefetchAll, $tblName, $envs, $arrColNames)
  {
    if(is_numeric($id) == false)
      $id = 0;
    
    $this->m_bValid       = true;
    $this->m_arrColNames  = $arrColNames;
    $this->m_envs         = $envs;
    $this->m_tblName      = $tblName;
    
    
    $this->initConnection();
    $this->setValue("id", $id, true);

    if($bPrefetchAll)
      $this->prefetchAllValues();

    $this->m_bLockable = $this->checkLockable();
  }

  private function checkLockable()
  {
    // print("CData::checkLockable: checking lockable<br>\n");
    $lenColNames = count($this->m_arrColNames);

    for($i = 0; $i < $lenColNames; $i++)
    {
      $colName = $this->m_arrColNames[$i];
      // print("CData::checkLockable: checking colName: $colName<br>\n");
      if($colName == "lockID")
        return true;
    }

    return false;
  }

  private function initConnection()
  {
    // if there is no connection in envs:
    // print("CData::initConnection: m_envs dump:<br />\n");
    // print_r($this->m_envs);
    // print("<br />\n");
    
    if(isset($this->m_envs['dbConn']) == false)
    {

      // gather dsn data.
      $dbName = $this->m_envs['dbName'];
      $dbHost = $this->m_envs['dbHost'];
      $dbUser = $this->m_envs['dbUser'];
      $dbPass = $this->m_envs['dbPass'];
      $dsn    = 'mysql:dbname=$dbName;host=$dbHost';

      // create connection
      try
      {
        $dbConn = new PDO($dsn, $dbUser, $dbPass);
      }
      catch(PDOException $e)
      {
        // print("CData::initConnection: PDOEXception: setting m_bValid to false.");
        $this->m_bValid = false;
      }

      // if no connection could be created with the data:
      if($this->m_bValid == false)
      {
        // return false
        return false;
      }
       else//else
      {
        // store it in the object.
        $this->m_dbConn = $dbConn;
      }
    } // else
     else
    {
      // copy connection handle to m_dbConn.
      $this->m_dbConn = $this->m_envs['dbConn'];
    }

    // return true;
    return true;
  }

  public function isValid()       { return $this->m_bValid; }

  public function getTblName()    { return $this->m_tblName; }

  public function getConnection() { return $this->m_dbConn; }

  private function queryValue($key)
  {
    // if this rcord is not valid:
    if($this->m_bValid == false)
    {
      // print("CData::queryValue: Record is false. setting rewValues array to empty value and returning. <br />\n");
      // write empty value to m_newValues[$key]
      $this->m_newValues[$key] = "";

      // return.
      return true;
    }

    // create sql query.
    $tblName  = $this->m_tblName;
    $sql      = "SELECT $key FROM $tblName WHERE id=:id";
    $id       = $this->getValue("id");
    
    
    // execute PDO request.
    $dbConn = $this->getConnection();
    $qry    = $dbConn->prepare($sql);
    
    $qry->bindValue("id", $id, PDO::PARAM_INT);
    
    $bSuccess = $qry->execute();
    $row      = $qry->fetch(PDO::FETCH_NUM);
    $value    = $row[0];
    
    // print("CData::queryValue: sql: $sql | bSuccess: $bSuccess | row:<br />\n");
    // print_r($row);
    // print("<br /> value: $value<br />\n");
    
    // store value in both arrays
    $this->m_oldValues[$key] = $value;
    $this->m_newValues[$key] = $value;

    // print("CData::queryValue: m_newValues: <br />\n");
    // print_r($this->m_newValues);
    // print("<br />\n"); 

    // return.
    return true;
  }

  public function getValue($key)
  {
    // print("CData::getValue: key: $key<Br />\n");
    
    // if the value is available in the newValues array:
    if(isset($this->m_newValues[$key]) == true)
    {
      // print("CData::getValue: Value found in cache: ".$this->m_newValues[$key]."<Br />\n");
      // return this value.
      return $this->m_newValues[$key];
    }

    // print("CData::getValue: Querying value: <Br />\n");
    // query the value.
    $this->queryValue($key);
    
    // print("CData::getValue: Value in cache is now: ".$this->m_newValues[$key]."<Br />\n");
        
    // return the value.
    return $this->m_newValues[$key];
  }

  private function verifyKey($key)
  {
    // for each column in the table:
    foreach($this->m_arrColNames as $colName)
    {
      // if colName == key: return true;
      if($colName == $key)
        return true;
    }

    // return false.
    return false;
  }

  public function setValue($key, $value, $bIsNewValue = false)
  {
    // if given key is not part of the column set: return fail.
    if($this->verifyKey($key) == false)
      return false;

    // set the 'newValue' to the given value.
    $this->m_newValues[$key] = $value;

    // if bIsNewValue == true: set oldValue to the given value.
    if($bIsNewValue == true)
      $this->m_oldValues[$key] = $value;

    // return success.
    return true;
  }

  public function updateValues()
  {
    // if locked
    if($this->isLocked() == true)
    {
      // get updatedBy and lockID
      $updatedBy  = $this->m_newValues['updatedBy'];
      $lockID     = $this->getValue("lockID");

      // if updatedBy != locked: return fail.
      if($updatedBy != $lockID)
        return false;
    }

    // init sql statement
    $tblName  = $this->m_tblName;
    $id       = $this->getValue("id");
    $sql      = "UPDATE $tblName SET ";

    if(is_numeric($id) == false)
      $id = 0;

    // for each column for which there is a new value, and the new value is != old value:
    $bFirstValue = true;

    foreach($this->m_arrColNames as $colName)
    {
      // add to the sql statement.
      $newValue = isset($this->m_newValues[$colName]) ? $this->m_newValues[$colName] : NULL;
      $oldValue = isset($this->m_oldValues[$colName]) ? $this->m_oldValues[$colName] : NULL;
      
      // print("colName: $colName | newValue: $newValue | oldValue: $oldValue<br />\n");
      if($newValue == NULL)
        continue;

      if($newValue == $oldValue)
        continue;

      if($bFirstValue == true)
        $bFirstValue = false;
       else
        $sql .= ", ";

      $newValue = $this->m_dbConn->quote($newValue);
      $sql     .= "$colName=$newValue";
    }

    // add where clause.
    $sql .= " WHERE id=$id;";

    // print("CData::updateValues: sql: $sql<br />\n");
    
    // execute update statement.
    $bSuccess = $this->m_dbConn->exec($sql) > 0;

    // return success.
    return $bSuccess;
  }

  public function prefetchAllValues()
  {
    // setup a query database for all columns for given ID.
    $tblName  = $this->m_tblName;
    $id       = $this->getValue("id");
    $sql      = "SELECT * FROM $tblName WHERE id=:id;";

    // fetch associative array for result.
    $qry = $this->getConnection()->prepare($sql);

    $qry->bindValue("id", $id, PDO::PARAM_INT);
    $qry->execute();

    // for each column:
    $row = $qry->fetch(PDO::FETCH_ASSOC);

    if(count($row) == 0)
      return false;

    foreach($this->m_arrColNames as $colName)
    {
      // fetch and store in old and new array.
      $this->m_oldValues[$colName] = $row[$colName];
      $this->m_newValues[$colName] = $row[$colName];
    }

    // return success.
    return true;
  }

  public function insertValues()
  {
    // start insert statement.
    $tblName  = $this->m_tblName;
    $sql      = "INSERT INTO $tblName VALUES(";

    // for each column in this table, except for the id:
    $bFirstValue = true;

    foreach($this->m_arrColNames as $colName)
    {
      if($bFirstValue == true)
        $bFirstValue = false;
       else
        $sql .= ", ";

      // get value
      $value = isset($this->m_newValues[$colName]) ? $this->m_newValues[$colName] : "";

      // add to the sql statement.
      if(is_numeric($value) == true)
        $sql .= $value;
       else
        $sql .= "'$value'";
    }

    // end the statement
    $sql .= ");";
    
    print("CData::insertValues: sql: $sql<br />\n");
    
    // execute the SQL statement.
    $this->m_dbConn->exec($sql);

    // get ID of the last inserted row.
    $id = $this->m_dbConn->lastInsertId();

    // write ID to this object.
    $this->setValue("id", $id, true);

    // return success.
    return true;
  }

  public function deleteRow()
  {
    $tblName  = $this->m_tblName;
    $id       = $this->getValue("id");
    $id       = is_numeric($id) == true ? $id : 0;
    $sql      = "DELETE FROM $tblName WHERE id=$id;";

    $this->m_dbConn->exec($sql);

    return true;
  }

  public function setLock($userID)
  {
    // if the row is not lockable: return false;
    if($this->m_bLockable == false)
      return false;

    // if the row is already locked:
    $currLock = $this->getValue("lockID");

    if($lockID > 0)
    {
      // if the lock != userID: return false;
      if($lockID != $userID)
        return false;

      // return true.
      return true;
    }

    // set the lock.
    $tblName  = $this->m_tblName;
    $lockDate = date("Y-m-d H:i");
    $id       = $this->getValue("id");
    $sql      = "UPDATE $tblName SET lockID=:lockID, lockDate=:lockDate WHERE id=:id;";
    $qry      = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $qry->bindParam('lockID',   $lockID,    PDO::PARAM_INT);
    $qry->bindParam('lockDate', $lockDate,  PDO::PARAM_STR);
    $qry->bindParam('id',       $id,        PDO::PARAM_INT);

    $bSuccess = $qry->execute();

    // return true.
    return $bSuccess;
  }

  public function isLocked()
  {
    // fetch lockID and lockDate
    $lockID     = $this->getValue("lockID");
    $lockDate   = $this->getValue("lockDate");
    $lockTime   = strtotime($lockDate);
    $nowTime    = time();
    $lockMaxLen = $this->m_envs['lock_max_length'];
    $diffTime   = $nowTime - $lockTime;

    // return lockID > 0 && difference between now and lockDate < maxLockTime.
    return $lockID > 0 && $diffTime < $lockMaxLen;
  }

  public function unlock()
  {
    // update lockID to 0
    $id   = $this->getValue("id");
    $sql  = "UPDATE $tblName SET lockID=0 WHERE id=:id;";

    $qry->bindParam('id', $id, PDO::PARAM_INT);

    $bSuccess = $qry->exec();

    // return success.
    return $bSuccess;
  }
}
?>
