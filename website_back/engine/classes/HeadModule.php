<?php
abstract class HeadModule {
  public function init($request) {
    $data = array(
      'title' => $this->getTitle($request).' | '.L('website_slug'),
      'description' => $this->getDescription($request),
      'keywords' => $this->getKeywords($request),
      'image' => $this->getImage($request),
      'canonical' => $request->canonical,
      'alternates' => $request->alternates,
      'css' => @$request->cssFiles,
      'pageName' => strtolower($request->module)
    );

    return (object) array( "tpl" => "head", "data" => (object) $data );
  }

  protected function escape($str) {
    return str_replace('"', "'", strip_tags($str));
  }

  protected abstract function getTitle($request);
  protected abstract function getDescription($request);
  protected abstract function getKeywords($request);
  protected abstract function getImage($request);
}
