<?php

 class Trash {

  public $user;
  public $itemid;
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
    $this->table = isset($data['table']) ? $data['table'] : " ";
    $contentData = $this->getdata($this->table,$this->itemid);
    $this->metadata = $contentData;
  }

  public function save(){
    $this->metadata = json_encode($this->metadata);
    $sql = "INSERT INTO cn_trash (`user`, `item_id`, `table`, `metadata`) VALUES (?, ?, ?, ?) ";

    $st = DB::$pdo->prepare($sql);
    $st->execute(array($this->user,$this->itemid,$this->table,$this->metadata));

  }

  public static function restoreTrashItem($id){
    $trashItem = DB::get(array(
      'table' => 'cn_trash',
      'query' => array( 'id' => $id ),
      'multiLang' => false,
      'single' => true
    ));
    $table = $trashItem->table;
    $metaData = json_decode($trashItem->metadata);
    $data = array();
    foreach($metaData as $key => $value ){
      if($key == 'id') continue;
      $data[$key] = $value;
    }
    DB::insert($data,$table);
    DB::DeleteTrashItem($id);

  }

}
