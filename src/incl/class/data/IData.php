<?php
interface IData
{
  public function isValid();
  public function getTblName();
  public function getConnection();
  public function getValue($key);
  public function setValue($key, $value, $bIsNewValue = false);
  public function updateValues();
  public function prefetchAllValues();
  public function insertValues();
  public function deleteRow();
  public function setLock($userID);
  public function isLocked();
  public function unlock();
}
?>