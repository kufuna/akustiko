<?php
class SubscribePage extends Page {
  private $tableName = 'subscribers';

  public function getTemplates() {} // Implement abstract method
  
  public function setTableName($name) {
    $this->tableName = $name;
  }

  public function addSubscriber($data) {
    $userEmail = isset($data['email']) ? $data['email'] : '';
    
    if(!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
      throw new Exception(L("please_enter_valid_email"));
    }
    
    $pdo = DB_BASE::$pdo;
    
    try {
      $st = $pdo->prepare('INSERT INTO '.$this->tableName.' (`email`) VALUES (?)');
      $st->execute(array( $userEmail ));
    } catch(Exception $e) {
      if($e->getCode() == 23000) {
        throw new Exception(L('_already_subscribed_'));
      } else {
        throw new Exception(L('_subscribe_db_error_'));
      }
    }
  }
  
  public function removeSubscriber($data) {
    $userEmail = isset($data['email']) ? $data['email'] : '';
    
    if(!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
      throw new Exception(L("email_is_not_valid"));
    }
    
    $st = $pdo->prepare('DELETE FROM '.$this->tableName.' WHERE `email` = ?');
    $st->execute(array( $userEmail ));
  }
}
