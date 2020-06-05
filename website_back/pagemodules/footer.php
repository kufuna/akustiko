<?php
class PageFooter {
  public static function init($request) {
    /*$contact = DB::get(array(
      'table' => 'contact',
      'single' => true
    ));*/

    $data = array (
      //'alternates' => $request->alternates,
      //'contact' => $contact,
      //'module' => $request->module
    );

    return (object) array( "tpl" => "footer", "data" => (object) $data);
  }
}
