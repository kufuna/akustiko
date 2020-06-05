<?php
function getSeparatedDate($date) {
  $d = explode('-', $date);
  return (object) array( 'year' => $d[0], 'month' => $d[1], 'day' => $d[2] );
}
