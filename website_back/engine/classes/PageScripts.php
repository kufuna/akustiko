<?php
class PageScripts {
  public function init($request) {
    $data = array(
      //'jsfiles' => DEBUG ? @$request->jsFiles : array( 'js/min/'.strtolower($request->module).'.js' ),
      'jsfiles' => @$request->pageObject->jsFiles,
      'info' => isset($request->scriptsInfo) ? $request->scriptsInfo : null,
      'pageName' => strtolower($request->module)
    );

    return (object) array( "tpl" => "scripts", "data" => (object) $data );
  }
}
