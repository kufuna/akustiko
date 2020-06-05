<?php

 class Logger {

  public $user;
  public $date;
  public $itemid;
  public $type;
  public $table;
  public $metadata;

  public function getData($table,$id){

    $sql = "SELECT * FROM $table WHERE id = $id";
    $st = DB::$pdo->query($sql);
    $data = $st->fetchObject();
    return $data;
  }

  public function __construct($data){
    $this->user = AdminUser::getCurrentUser()->id;
    $this->itemid = isset($data['item_id']) ? $data['item_id'] : 0;
    $this->type = isset($data['type']) ? $data['type'] : " ";
    $this->table = isset($data['table']) ? $data['table'] : " ";

    if ($this->type == 'content_edit'){
      $oldData = $this->getdata($this->table,$this->itemid);
      $this->metadata = array('old' => $oldData, 'new' => "");
    }else{
      $contentData = $this->getdata($this->table,$this->itemid);
      $this->metadata = array($contentData);
    }
  }

  public function save(){
    if ($this->type == 'content_edit'){
      $newData = $this->getData($this->table,$this->itemid);
      $this->metadata['new'] = $newData;
    }
    $this->metadata = json_encode($this->metadata);
    $sql = "INSERT INTO cn_action_logs (`user`, `item_id`, `type`, `table`, `metadata`) VALUES (?, ?, ?, ?, ?) ";

    $st = DB::$pdo->prepare($sql);
    $st->execute(array($this->user,$this->itemid,$this->type,$this->table,$this->metadata));

  }
}
