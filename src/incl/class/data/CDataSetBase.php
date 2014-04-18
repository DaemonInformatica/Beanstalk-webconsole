<?php

abstract class CDataSetBase
{
  protected $m_envs;
  
  function __construct($envs)
  {
    $this->m_envs = $envs;
  }
  
  public function getConnection() { return $this->m_envs['dbConn']; }  
}
?>
