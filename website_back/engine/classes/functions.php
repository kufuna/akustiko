<?php

function render_date($date) {
  if(!preg_match('#^\d{4}-\d{2}-\d{2}$#', $date) || $date == '0000-00-00') {
    return '&nbsp;';
  }

  $date = explode('-', $date);
  
  $months = array(
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  );
  
  return $date[2].' '.L($months[((int) $date[1]) - 1]).' '.$date[0];
}

function render_date_time($date) {
  if(!preg_match('#^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$#', $date) || $date == '0000-00-00 00:00:00') {
    return '&nbsp;';
  }

  $firstPiece = explode(' ', $date);
  $date = explode('-', $firstPiece[0]);
  $time = explode(':', $firstPiece[1]);
  //$date = explode(':', $SecondPiece[0]);
  
  $months = array(
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  );
  
  return $date[2].' '.L($months[((int) $date[1]) - 1]).' '.$date[0].' | '.$time[0].':'.$time[1];
}

function current_month($date) {
  if(!preg_match('#^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$#', $date) || $date == '0000-00-00 00:00:00') {
    return '&nbsp;';
  }

  $firstPiece = explode(' ', $date);
  $date = explode('-', $firstPiece[0]);
  
  $months = array(
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  );
  
  return L($months[((int) $date[1]) - 1]);
}

function current_date($date) {
  $Pieces = explode(' ', $date);  
  return L($Pieces[0]).','.' '.$Pieces[1].' '.L($Pieces[2]);
}

function getMonths() {
  return array(
    1 => L('January'),
    2 => L('February'),
    3 => L('March'),
    4 => L('April'),
    5 => L('May'),
    6 => L('June'),
    7 => L('July'),
    8 => L('August'),
    9 => L('September'),
    10 => L('October'),
    11 => L('November'),
    12 => L('December')
  );
}
  
function getYears($lastNYears = 10) {
  $years = array();
  
  for($y = date('Y'); $y > date('Y') - $lastNYears; $y--) $years[] = $y;
  
  return $years;
}

function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE";  
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    };
    return array('browser'=>$bname,
                 'platform'=>$platform
    );
}

//debug data
function pre($var) {
    echo '<pre style="margin: 10px; border: 1px dashed #00ea21; padding: 10px; color:#00ea21; background: black; font-size: 14px;">';
    var_export($var);
    echo '</pre>';
}

/*
 *  ===== formValidate =====
 * field => form value
 * pattern => array('type'=>'string', 'required'=>true, 'min'=>1, 'max'=>30, 'trim'=>true)
 *
 */
function formValidate($field, $pattern, $method = 'post')
{
    //method processing
    $method == 'post' ? $field = @$_POST[$field] : $field = @$_GET['field'];

    switch ($pattern['type']) {
        //number
        case 'number':
            //check var if required
            if ($pattern['required'] && !@$field) return false;
            //check var type
            if (!is_numeric($field)) return false;
            //check var length
            if ($pattern['min'] > mb_strlen((string)$field) || $pattern['max'] < mb_strlen((string)$field)) return false;

            //success
            return $field;
        //email
        case 'email':
            if (!filter_var($field, FILTER_VALIDATE_EMAIL))
                return false;
            else
                return $field;
        //select
        case 'select':
            if(!@$field) return false;
            else return trim(stripslashes(htmlspecialchars($field)));
        //checkbox
        case 'checkbox':
            if(!@$field) return false;
            else return trim(stripslashes(htmlspecialchars($field)));
        //string
        default:
            //check var if required
            if ($pattern['required'] && !@$field) return false;
            //parse string
            $field = trim(stripslashes(htmlspecialchars($field)));
            //check var length
            if (@$pattern['min'] > mb_strlen((string)$field) || @$pattern['max'] < mb_strlen((string)$field)) return false;

            //success
            return $field;
    }
}

function dateToNow($timestamp){

    $now = time(); // or your date as well
    $your_date = strtotime($timestamp);
    $datediff = $now - $your_date;

    //echo floor($datediff/(60*60*24));

    switch ($datediff) {
        //Seconds
        case $datediff<60:
            return str_replace ( '{X}' , $datediff , L("{X} წამის წინ") ) ;
            break;
        case $datediff<60*60:
            return str_replace ( '{X}' , round($datediff/(60)) , L("{X} წუთის წინ") ) ;
            break;
        case $datediff<60*60*24:
            return str_replace ( '{X}' , round($datediff/(60*60)) , L("{X} საათის წინ") ) ;
            break;
        case $datediff<60*60*24*7:
            return str_replace ( '{X}' , round($datediff/(60*60*24)) , L("{X} დღის წინ") ) ;
            break;
        case $datediff<60*60*24*7*4:
            return str_replace ( '{X}' , round($datediff/(60*60*24*7)) , L("{X} კვირის წინ") ) ;
            break;
        case $datediff<60*60*24*7*4*12:
            return str_replace ( '{X}' , round($datediff/(60*60*24*7*4)) , L("{X} თვის წინ") ) ;
            break;
        case $datediff<60*60*24*7*4*12*12:
            return str_replace ( '{X}' , round($datediff/(60*60*24*7*4*12)) , L("{X} წლის წინ") ) ;
            break;
    }

    return $datediff;
}
