<?php
class Stats {
  private static $shortMonths = array('/Jan/', '/Feb/', '/Mar/', '/Apr/', '/May/', '/Jun/', '/Jul/', '/Aug/', '/Sep/', '/Oct/', '/Nov/', '/Dec/' );
  private static $longMonths = array(' January ', ' February ', ' March ', ' April ', ' May ', ' June ', ' July ', ' August ', ' September ', ' October ', ' November ', ' December ' );
  private static $logFile = null;
  private static $stats = null;

  public static function parse($logFile) {
    self::$logFile = $logFile;
    
    for($i = -1; $i > -30; $i--) {
      $day = date('Y-m-d', strtotime($i.' day'));
      $dayStat = self::getStat($day);
      
      if($dayStat) {
        break;
      } else {
        self::updateStats($day);
      }
    }
  }
  
  private static function loadStats() {
    if(self::$stats) return self::$stats;
    
    if(!file_exists(self::$logFile)) {
      throw new Exception("Log file not found");
    }
  
    $logs = file_get_contents(self::$logFile);
    $logers = explode("\n", $logs);
    $stats = array();
    foreach($logers as $log){
      if(preg_match('/([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})/', $log, $ip)) {
        preg_match("#(\d{2}\/\w{3}\/\d{4})#", $log ,$date);
        $date = str_replace(self::$shortMonths, self::$longMonths, $date[1]);
        $date = date('Y-m-d', strtotime($date));
        $stats[$date][] = $ip[1];
      }
    }

    foreach($stats as &$ip){
       $ip = count(array_unique($ip));
    }
    
    self::$stats = $stats;
    
    return self::$stats;
  }
  
  private static function updateStats($date) {
    $stats = self::loadStats();

    if(isset($stats[$date])) {
      DB_Base::insertVisitStat($date, $stats[$date]);
    }
  }
  
  public static function getStats() {
    return DB_Base::get(array(
      'table' => 'cn_visit_stats',
      'multiLang' => false,
      'order' => '`date` ASC'
    ));
  }
  
  public static function getStat($date) {
    return DB_Base::get(array(
      'table' => 'cn_visit_stats',
      'query' => array( 'date' => $date ),
      'multiLang' => false,
      'single' => true
    ));
  }
}
