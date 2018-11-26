<?php
class errlog{
  public $log;
  // public $error;
  public function __construct($logfile){
    $this->log = $logfile;
    file_put_contents($logfile, "\n".date("D G:i:s")."> ", FILE_APPEND);
  }
  public function log($error){
    $logfile = $this->log;
    file_put_contents($logfile, $error."\n", FILE_APPEND);
  }//i changed some stuff
}
?>
