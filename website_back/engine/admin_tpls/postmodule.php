<?php
header('Content-Type: application/json');
if(isset($data->modFile)) {
  include_once $data->modFile;
}
