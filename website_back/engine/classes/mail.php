<?php
class Mail {

  public static function sendMailSenderMessage($data) {

    $mail = new PHPMailer;

    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;

    $mail->From = '';
    $mail->FromName = '';
    $mail->addReplyTo('');

    foreach ($data['addressee'] as $email) {
        $mail->addAddress($email);
    }

    $mail->Subject = "";
    $mail->Body    = $data['text'];

    return $mail->send();
  }

  public static function send($data) {
    if(empty($data['from']) || !$data['to'] || empty($data['subject']) || empty($data['message']) || empty($data['fromName'])) throw new Exception(L("incorrect_parameters_for_mailsender"));

    $mail = new PHPMailer(true);
    //$mail->SMTPDebug = 1;

    $mail->isSMTP();
    $mail->Host = MAIL_HOST;
    $mail->SMTPAuth = true;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    if(defined('MAIL_SMTP_SECURE')) $mail->SMTPSecure = MAIL_SMTP_SECURE;
    if(defined('MAIL_PORT')) $mail->Port = MAIL_PORT;

    $mail->Username = MAIL_USER;
    $mail->Password = MAIL_PASS;

    $mail->From = $data['from'];
    $mail->FromName = $data['fromName'];
    $mail->addAddress($data['to']);
    $mail->addReplyTo($data['from']);

    $mail->Subject = $data['subject'];
    $mail->Body = $data['message'];
    $mail->AltBody = strip_tags(str_replace('<br>', "\n", $data['message']));

    if(isset($data['attachment'])) {
      if(is_array($data['attachment'])) {
        foreach($data['attachment'] as $value){
          $mail->addAttachment($value['path'], $value['name']);
        }
      } else {
        $mail->addAttachment($data['attachment'], $data['attachmentName']);
      }
    }

    $mail->CharSet = 'UTF-8';

    $status = $mail->send();

    if (!$status) throw new Exception(L("unable_to_send_email"));

    return $status;
  }

}
