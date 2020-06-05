<?php
class Admin_PageHead {
  public static function getModel($request) {
    $request->data = isset($request->data) ? $request->data : (object) array(  );
  
    $headData = (object) array( 
      'title' => self::getTitleByModule($request)
     );
  
    return (object) array( "tpl" => "head", "data" => $headData );
  }
  
  private static function getTitleByModule($request) {
    $statics = array( 
      'home' => 'Admin Dashboard'
     );
    
    if(isset($statics[$request->module])) {
      $title = $statics[$request->module];
    } else {
      $title = 'Admin';
    }
    
    return $title;
  }
}
