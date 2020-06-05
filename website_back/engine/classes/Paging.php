<?php
class Paging {
  private static $pageSize = 10;
  private static $currentPage = 1;
  private static $totalCount;
  private static $lastPage;
  private static $switchers = array();
  private static $canonical;
  private static $settings = null;

  public static function init($config) {
    if(!isset($config['totalCount'])) {
      throw new Exception("Total count not found for paging");
    }
    
    self::$totalCount = (int) $config['totalCount'];
    
    if(isset($config['currentPage'])) {
      self::$currentPage = (int) $config['currentPage'];
    }
    
    if(isset($config['pageSize'])) {
      self::$pageSize = (int) $config['pageSize'];
    }
    
    return self::getSettings();
  }
  
  private static function getSettings() {
    if(self::$settings) return self::$settings;
  
    self::$lastPage = max(1, ceil(self::$totalCount / self::$pageSize));
    self::$currentPage = max(self::$currentPage, 1);
    self::$currentPage = min(self::$lastPage, self::$currentPage);
    $skip = (self::$currentPage - 1) * self::$pageSize;
    
    self::$settings = (object) array(
      'skip' => $skip,
      'limit' => self::$pageSize,
      'currentPage' => self::$currentPage,
      'lastPage' => self::$lastPage
    );
    
    return self::$settings;
  }
  
  public static function getModel($canonical) {
    self::$canonical = $canonical;
    $start = max(1, self::$currentPage - 2);
    $end = min(self::$lastPage, self::$currentPage + 2);
    
    if(self::$lastPage > 1) {
      if(self::$currentPage > 3) {
        self::addSwitcher('<i class="fa fa-angle-double-left"></i>', 1, false, 'first');
      }
      
      if(self::$currentPage > 1) {
        self::addSwitcher('<i class="fa fa-angle-left"></i>', self::$currentPage - 1, false, 'prev');
      }
      
      for($x = $start; $x <= $end; $x++) {
        self::addSwitcher($x, $x, self::$currentPage == $x);
      }
      
      if(self::$currentPage < self::$lastPage) {
        self::addSwitcher('<i class="fa fa-angle-right"></i>', self::$currentPage + 1, false, 'next');
      }
      
      if(self::$currentPage < self::$lastPage - 2) {
        self::addSwitcher('<i class="fa fa-angle-double-right"></i>', self::$lastPage, false, 'last');
      }
    }
    
    $settings = self::getSettings();
    
    return (object) array_merge((array) $settings, array( 'switchers' => self::$switchers ));
  }
  
  private static function addSwitcher($text, $num, $active = false, $type = 'normal') {
    if(preg_match('#\/(\d+)\/$#', self::$canonical)) {
      $link = preg_replace('#\/(\d+)\/$#', '/'.$num.'/', self::$canonical);
    } else {
      $link = self::$canonical.'page/'.$num.'/';
    }
  
    self::$switchers[] = (object) array(
      'text' => $text,
      'link' => $link,
      'active' => $active,
      'type' => $type
    );
  }
}
