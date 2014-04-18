<?php
include_once("CDataLogbook.php");

class CDataLogbookSet
{
  private $m_dbConn;
  private $m_arrFilters;
  public  $m_page;
  private $m_length;

  function __construct($dbConn, $page, $length)
  {
    $this->m_dbConn = $dbConn;
    $this->m_length = $length;
    $this->m_page   = $page;
  }

  /*
    getData: Execute the actual query.
    * 
    *   output: array(int, CDataLogbook): logbook elements.
  */
  public function getData()
  {
    $sql    = "SELECT id FROM tblLogbook  ORDER BY created DESC LIMIT :start, :length";
    $qry    = $this->m_dbConn->prepare($sql);
    $length = $this->m_length;
    $start  = $this->m_page * length;

    $qry->prepare("start",  $start,   PDO::PARAM_INT);
    $qry->prepare("length", $length,  PDO::PARAM_INT);
    $qry->execute();

    while($row = $qry->fetch())
    {
      $id       = $row[0];
      $logbook  = new CDataLogbook($id, false, $this->m_dbConn);

      array_push($this->m_ArrLogbook, $logbook);
    }
  }
}
?>
