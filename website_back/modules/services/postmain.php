<?php
if($data->action == 'remove') {
  $_POSTMODE = 'remove';
  include __DIR__.'/main.php';
} else if($data->action == 'reorder') {
  $_POSTMODE = 'reorder';
  include __DIR__.'/main.php';
}