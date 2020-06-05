<?php
if ((!file_exists('config.php') || !file_get_contents('config.php')) && isset($_POST['submit'])) {
  
$phpCode = "<?php\n";

foreach ($_POST as $key => $value) {
  if ($key != 'submit' 
        && $key != 'languages' 
        && $key != 'captcha_public_key' 
        && $key != 'captcha_private_key' 
        && $key != 'mail_host' 
        && $key != 'mail_user' 
        && $key != 'mail_pass' 
        && $key != 'contact_email') {

    $phpCode .= "define('".strtoupper($key)."', '$value');\n";
  }
}

$phpCode .= "\n\$dynamicConfig = json_decode(file_get_contents(ROOT.DIR_BACK.'/config.json'));\n";
$phpCode .= 
'if($dynamicConfig) {
  $dynamicConfig = $dynamicConfig->general;

  foreach($dynamicConfig as $key => $val) {
    define($key, $val);
  }
}';

$phpCode .= 
"\n\nini_set('display_errors', DEBUG);
error_reporting((DEBUG ? E_ALL : 0));
define('ACCESS_LOG_PATH', '../logs/book.log');
date_default_timezone_set('Asia/Tbilisi');
mb_internal_encoding('utf8');";

$phpCode .=
"\n\n\$CFG = (array(
  'SITE_LANGS' => 
    array (\n";

foreach ($_POST['languages'] as $key => $value) {
$phpCode .= "      $key => '".substr($value, 0, 2)."',\n";
}

$phpCode .= "    ),";

$phpCode .= 
"
  'LANG_NAMES' => 
    (array(
";

foreach ($_POST['languages'] as $key => $value) {
$phpCode .= "      '".substr($value, 0, 2)."' => '".substr($value, 7)."',\n";
}

$phpCode .= "    )),";

$phpCode .= 
"
  'LANG_SHORT_NAMES' => 
    (array(
";

foreach ($_POST['languages'] as $key => $value) {
$phpCode .= "      '".substr($value, 0, 2)."' => '".substr($value, 3, 3)."',\n";
}

$phpCode .= "    )),";

$phpCode .= 
"
   'stats' =>
  (array(
  )),
));
";

$phpCode .= "\n\$SITE_LANGS = array (";

foreach ($_POST['languages'] as $key => $value) {
$phpCode .= "$key => '".substr($value, 0, 2)."', ";
}

$phpCode .= ");";

$jsonCode = '{"general":{"MAIL_HOST":"'.$_POST['mail_host'].'","MAIL_USER":"'.$_POST['mail_user'].'","MAIL_PASS":"'.(($_POST['mail_pass']) ? $_POST['mail_pass'] : '1992natia').'","CONTACT_EMAIL":"'.$_POST['contact_email'].'","CAPTCHA_PUBLIC_KEY":"'.$_POST['captcha_public_key'].'","CAPTCHA_PRIVATE_KEY":"'.$_POST['captcha_private_key'].'","GOOGLE_MAPS_API_KEY":"'.$_POST['maps_api_key'].'","ADMIN_PATH":"connect","DEBUG":1,"MINIFY_FILES":0},"seo":{"sitemap_updated":"","seo_enabled":false,"google-verification-code":"","google-analytics-id":""}}';

/***************************************************************/

file_put_contents($_POST['root'].$_POST['dir_front'].'/config.php', $phpCode);
file_put_contents($_POST['root'].$_POST['dir_back'].'/config.json', $jsonCode);

header("location:".$_POST['root_url']);

}

?>
<html>
<head>
  <style>
    body{
      width: 800px;
      margin: 0 auto;
      font: 14px Tahoma;
    }
    table{
      width: 100%;
    }
    td{
      width: 50%;
      padding: 10px;
    }
    td:first-child{
      text-align: right;
    }
    input{
      padding: 10px;
      border: solid 1px #ccc;
    }
    legend{
      font-size: 24px;
    }
    fieldset{
      border: solid 1px #ccc;
      margin: 30px;
    }
    button{
      padding: 10px;
      cursor: pointer;
    }
    #advanced{
      display: none;
    }
    #show-advanced{
      padding: 0px 35px 10px;
      text-align: right;
    }
    #show-advanced a{
      color: #000;
    }
    select{
      width: 250px;
      padding: 10px;
    }
  </style>
  <title></title>
</head>
<body>
<form action="" method="POST">
  <fieldset>
    <legend>DATABASE</legend>
    <table>
      <tr>
        <td>HOST</td>
        <td><input type="text" name="db_host" value="localhost"></td>
      </tr>
      <tr>
        <td>USER</td>
        <td><input type="text" name="db_user"></td>
      </tr>
      <tr>
        <td>PASS</td>
        <td><input type="password" name="db_pass"></td>
      </tr>
      <tr>
        <td>NAME</td>
        <td><input type="text" name="db_name"></td>
      </tr>
    </table>
  </fieldset>

  <div id="show-advanced"><a id="show" href="#">Advanced</a></div>

  <div id="advanced">
    <fieldset>
      <legend>DIR PATH</legend>
      <table>
        <tr>
          <td>FRONT</td>
          <td><input type="text" name="dir_front" value="public_html"></td>
        </tr>
        <tr>
          <td>BACK</td>
          <td><input type="text" name="dir_back" value="website_back"></td>
        </tr>
        <tr>
          <td>ROOT</td>
          <td><input type="text" name="root" value="../"></td>
        </tr>
      </table>
    </fieldset>

    <fieldset>
      <legend>SITE</legend>
      <table>
        <tr>
          <td>ROOT URL</td>
          <td><input type="text" name="root_url" placeholder="http://example.com/"></td>
        </tr>
        <tr>
          <td>DOMAIN</td>
          <td><input type="text" name="domain" placeholder="example.com"></td>
        </tr>
      </table>
    </fieldset>

    <fieldset>
      <legend>LANGUAGES</legend>
      <table>
        <tr>
          <td>AVAILABLE</td>
          <td>
            <select multiple name="languages[]">
              <option value="ka/GEO/Georgian" selected>Georgian</option>
              <option value="en/ENG/English">English</option>
              <option value="ru/RUS/Russian">Russian</option>
              <option value="de/DEU/German">German</option>
              <option value="az/AZE/Azerbaijani">Azerbaijani</option> 
            </select>
          </td>
        </tr>
        <tr>
          <td>DEFAULT</td>
          <td>
            <select name="default_lang">
              <option value="ka" selected>Georgian</option>
              <option value="en">English</option>
              <option value="ru">Russian</option>
              <option value="de">German</option>
              <option value="az">Azerbaijani</option> 
            </select>
          </td>
        </tr>
      </table>
    </fieldset>

    <fieldset>
      <legend>MAIL SMTP</legend>
      <table>
        <tr>
          <td>HOST</td>
          <td><input type="text" name="mail_host" value="mail.connect.ge"></td>
        </tr>
        <tr>
          <td>USER</td>
          <td><input type="text" name="mail_user" value="natia@connect.ge"></td>
        </tr>
        <tr>
          <td>PASS</td>
          <td><input type="text" name="mail_pass" placeholder="leave it empty ..."></td>
        </tr>
        <tr>
          <td>CONTACT EMAIL</td>
          <td><input type="text" name="contact_email" value="natia@connect.ge"></td>
        </tr>
      </table>
    </fieldset>

    <fieldset>
      <legend>CAPTCHA</legend>
      <table>
        <tr>
          <td>PUBLIC KEY</td>
          <td><input type="text" name="captcha_public_key" value="6LeWtAkTAAAAABiwv6iP0NVDoLJeTZCnfexY41L9"></td>
        </tr>
        <tr>
          <td>PRIVATE KEY</td>
          <td><input type="text" name="captcha_private_key" value="6LeWtAkTAAAAAKUmglUQ-Qstv7XFvLNDgJ7CwHCB"></td>
        </tr>
      </table>
    </fieldset>

    <fieldset>
      <legend>GOOGLE MAPS API KEY</legend>
      <table>
        <tr>
          <td>API KEY</td>
          <td><input type="text" name="maps_api_key" value="AIzaSyBOYB-uk489gOpMKTxw-2j-uxrPanFOSwc"></td>
        </tr>
      </table>
    </fieldset>

    <fieldset>
      <legend>SESSION NAMESPACES</legend>
      <table>
        <tr>
          <td>ADMIN</td>
          <td><input type="text" name="admin_session_namespace" value="_CN_ADMIN_USER"></td>
        </tr>
        <tr>
          <td>USER</td>
          <td><input type="text" name="user_session_namespace" value="_CN_USER"></td>
        </tr>
      </table>
    </fieldset>
  </div>

  <table>
      <tr>
        <td></td>
        <td><button type="submit" name="submit">SAVE</button></td>
      </tr>    
  </table>
</form>

<script>
  var showLink = document.getElementById("show"),
      showAdvancedDiv = document.getElementById("show-advanced"),
      advancedForm = document.getElementById("advanced");

  showLink.addEventListener("click", function(event){
      showAdvancedDiv.remove();
      advancedForm.style.display = 'block';

      event.preventDefault();
  });
</script>
</body>
</html>