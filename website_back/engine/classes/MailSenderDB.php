<?php
class MailSenderDB extends DB {
  public static function getMailsToSendByActive() {        
    $sql = "SELECT * FROM mails_to_send WHERE sent = 0 LIMIT 1";
    return self::$pdo->query($sql)->fetchObject();
  }

  public static function getMailsList($id, $limit) {
    $id = (int) $id;
    $data = array(  );
    $sql = "SELECT * FROM mails_list WHERE id NOT IN (SELECT email_id FROM mails_log WHERE template_id = $id) LIMIT $limit";
    $st = self::$pdo->query($sql);
    while($row = $st->fetchObject()) { $data[] = $row; }
    return $data;
  }

  public static function updateMailsToSend($id){
    $id = (int) $id;
    $sql = "UPDATE mails_to_send SET sent = 1 WHERE id = $id";
    $st = self::$pdo->query($sql);
  }

  public static function updateMailsLog($sql){
    $sql = "INSERT INTO mails_log (email_id, template_id, sent) VALUES ".$sql;
    $st = self::$pdo->query($sql);
  }
}
