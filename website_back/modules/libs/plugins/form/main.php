<?php
class P_Form {
  private $view;

  public function __construct($cfg) {
    $this->renderView($cfg);
  }

  public function getView() {
    return $this->view['top'].$this->view['middle'].$this->view['bottom'];
  }

  public function append($view) {
    $this->view['middle'] = $view;
  }

  private function renderView($cfg) {
    $this->view = array();

    $this->view['top'] = '<div style="width:98%; margin-left:10px">'
      .'<form method="post" action="" class="main" enctype="multipart/form-data">'
        .'<fieldset>'
          .'<div class="widget grid9" style="margin-left:auto; margin-right:auto; float:none;">'
            .'<div class="whead"><h6>Add new page</h6><div class="clear"></div></div>';

    $this->view['middle'] = '';

    $this->view['bottom'] = '</div></fieldset></form></div>';
  }
}
