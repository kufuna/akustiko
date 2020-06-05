<?php
if($data->action == 'remove') {
  $_POSTMODE = 'remove';
  include __DIR__.'/main.php';
} else if($data->action == 'setactive') {
  $_POSTMODE = 'toggleActive';
  include __DIR__.'/main.php';
}
else if($data->action == 'removeall') {
  $_POSTMODE = 'removeall';
  include __DIR__.'/main.php';
}