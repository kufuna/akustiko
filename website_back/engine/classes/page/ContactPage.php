<?php
class ContactPage extends Page {
  private $fields = array(
    array( 'key' => 'email', 'label' => 'Email', 'required' => true ),
    array( 'key' => 'name', 'label' => 'Name', 'required' => true ),
    array( 'key' => 'phone', 'label' => 'Phone' ),
    array( 'key' => 'text', 'label' => 'Comment', 'required' => true )
  );

  private $subject = 'Contact Message';
  private $disableCaptcha = false;

  public function setSubject($subject) {
    $this->subject = (string) $subject;
  }

  private $contactEmails = array( CONTACT_EMAIL );

  public function setContactEmails($emails) {
    if(is_array($emails)) {
      $this->contactEmails = $emails;
    }
  }

  public function disableCaptcha() {
    $this->disableCaptcha = true;
  }

  public function getTemplates() {} // Implement abstract method

  public function setFields($fields) {
    $this->validateFields($fields); // Throws exception in case of misconfigured values
    $this->fields = $fields;
  }

  public function sendMail($data) {
    $text = '';
    $userEmail = isset($data['email']) ? $data['email'] : '';

    if(!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
      throw new Exception(L("please_enter_valid_email"));
    }

    $attachments = array();

    foreach($this->fields as $field) {
      $field = (object) $field;
      $key = $field->key;
      $label = $field->label;

      if(isset($field->type) && $field->type == 'attachment') {
        $ext = explode('.', $_FILES[$key]['name']);
        $ext = end($ext);
        $attachments[] = array( 'file' => $field->upload_dir, 'name' => $label.'.'.$ext );
      } else {
        $value = isset($data[$key]) ? $data[$key] : '';
        if(isset($field->required) && $field->required && !$data[$key]) {
          throw new Exception(L("please_fill_required_fields"));
        }

        $text .= $label.': '.$value.'<br><br>';
      }
    }

    if(!$this->disableCaptcha) $this->validateCaptcha($data); // Throws exception in case of wrong captcha

    $mail = new PHPMailer();

    $mail->isSMTP();

    $mail->SMTPOptions = array(
      'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
      )
    );
    
    //$mail->SMTPDebug  = 2;

    $mail->Host = MAIL_HOST;
    $mail->SMTPAuth = true;
    if(defined('MAIL_SMTP_SECURE')) $mail->SMTPSecure = MAIL_SMTP_SECURE;
    if(defined('MAIL_PORT')) $mail->Port = MAIL_PORT;

    $mail->Username = MAIL_USER;
    $mail->Password = MAIL_PASS;
    $mail->CharSet  = 'UTF-8';

    $mail->From = $userEmail;
    $mail->FromName = isset($data['name']) ? $data['name'] : 'Contact Form';

    foreach($this->contactEmails as $email) {
      $mail->addAddress($email);
    }

    $mail->addReplyTo($userEmail);

    foreach($attachments as $attachment) {
      $mail->AddAttachment($attachment['file'], $attachment['name']);
    }

    $mail->Subject = $_SERVER['HTTP_HOST'].' - '.$this->subject;
    $mail->Body    = $text;
    $mail->AltBody = strip_tags(str_replace('<br>', '\n', $text));

    $status = $mail->send();

    if(!$status) {
      throw new Exception(L("unable_to_send_email"));
    }
  }

  public function validateCaptcha($data) {
    if(!isset($data['g-recaptcha-response']) || !$data['g-recaptcha-response']) {
      throw new Exception(L("please_enter_security_code"));
    }

    if(ini_get('allow_url_fopen')){

      $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?'
                    .'secret='.CAPTCHA_PRIVATE_KEY
                    .'&response='.$data['g-recaptcha-response']
                    .'&remoteip='.$_SERVER['REMOTE_ADDR']);
    } else {
      $captchaUrl = 'https://www.google.com/recaptcha/api/siteverify?' .'secret='.CAPTCHA_PRIVATE_KEY .'&response='.$data['g-recaptcha-response'] .'&remoteip='.$_SERVER['REMOTE_ADDR']; 
      $curl = curl_init(); 
      curl_setopt($curl, CURLOPT_URL, $captchaUrl); 
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
      curl_setopt($curl, CURLOPT_TIMEOUT, 120); 
      $response = curl_exec($curl); 
      curl_close($curl); 
    }

    $response = json_decode($response);

    if(!$response || !isset($response->success) || !$response->success) {
      throw new Exception(L("security_code_is_not_valid"));
    }
  }

  private function validateFields($fields) {
    $emailField = false;

    if(!is_array($fields)) {
      throw new Exception("Contact page fields must be an array");
    }

    foreach($fields as $field) {
      if(!is_array($field)) {
        throw new Exception("Each of Contact page field must be an array");
      }

      $field = (object) $field;

      if(!isset($field->key) || !$field->key) {
        throw new Exception("Contact page field must contain key");
      }

      if(!isset($field->label) || !$field->label) {
        throw new Exception("Contact page field must contain label");
      }

      if($field->key == 'email') {
        $emailField = true;
      }
    }

    if(!$emailField) {
      throw new Exception("Contat page fields must include email field (key => email)");
    }

    return true;
  }
}
