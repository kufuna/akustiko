<?php
class Page_404 extends Page {
  private $data = null;

  public function __construct($request, $note = '') {
    parent::__construct($request, $note);
  }

  public function loadData() {
    $msg = $this->request->action;

    $this->data = (object) array(
      'debugMsg' => $msg,
      'msg' => L('page_not_found')
    );
  }

  public function getTemplates() {
    $this->request->canonical = $this->getCanonical();
    $this->request->alternates = $this->getAlternates();
    $this->request->pageObject = $this;

    $this->request->data = $this->data;
    $this->data->canonical = $this->request->canonical;

    $pageHead = new PageHead();
    $pageScripts = new PageScripts();

    return array(
      $pageHead->init($this->request),
      PageHeader::init($this->request),
      (object) array( "tpl" => "404", "data" => $this->data ),
      PageFooter::init($this->request),
      $pageScripts->init($this->request),
      (object) array( "tpl" => "foot", "data" => null )
    );
  }

  public function getCanonical() {
    if($this->note) {
      $msg = urlencode($this->note);
    } else if($this->request->action) {
      $msg = urlencode($this->request->action);
    } else {
      $msg = '';
    }

    return SITE_URL.'404/'.$msg.'/';
  }
}
