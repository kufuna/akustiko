<?php
class P_Form extends Plugin {
  private $view;

  public function __construct($cfg) {
    
  }

  public function getData($data) {
    return isset($data['text']) ? $data['text'] : '';
  }

  private function renderView($cfg) {
    $this->view = ''
    .'<div style="width:98%; margin-left:10px">'
      .'<form method="post" action="" class="main" enctype="multipart/form-data">'
        .'<fieldset>'
          .'<div class="widget grid9" style="margin-left:auto; margin-right:auto; float:none;">'
            .'<div class="whead"><h6>Add new page</h6><div class="clear"></div></div>'
            .'{InnerPlugin}'
          .'</div>'
        .'</fieldset>'
      .'</form>'
    .'</div>';
  }
}
