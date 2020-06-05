<?php
class P_InnerSortableList extends Plugin {
  protected static $inc = 0;

  public $subscribes = array(
    'beforeAction' => 'getData',
    'action' => 'processAction',
    'beforeRender' => 'renderView'
  );

  public function __construct($config) {
    $this->rawCfg = isset($config['config']) ? $config['config'] : array();
    $this->rootCfg = isset($config['rootConfig']) ? $config['rootConfig'] : array();
    $this->mode = isset($config['mode']) ? $config['mode'] : '';
    $this->urlData = isset($config['urlData']) ? $config['urlData'] : array();

    $this->parseConfig($this->rawCfg);
    $this->multiLang = $this->cfg->multiLang;
  }

  public function getData($data) {
    if($this->mode == $this->cfg->removeMethod && isset($_POST['id'])) {
      return array(
        'action' => 'remove',
        'id' => $_POST['id']
      );
    } else if($this->mode == $this->cfg->reorderMethod && isset($_POST['data'])) {
      return array(
        'action' => 'reorder',
        'data' => stripslashes($_POST['data'])
      );
    }

    return null;
  }

  public function processAction($data) {
    $actionData = isset($data['P_InnerSortableList']) ? $data['P_InnerSortableList'] : array();
    $action = isset($actionData['action']) ? $actionData['action'] : null;
    $id = isset($actionData['id']) ? (int) $actionData['id'] : 0;

    $tableName = $this->multiLang ? $this->cfg->tableName.'_'.Lang::$lang : $this->cfg->tableName;

    if($action == 'remove' && $id > 0) {
      if($this->cfg->depth > 1) {
        DB::removeChilds($tableName, (int) $id, $this->cfg->depth - 1);
      }

      cn_db_remove($tableName, array( 'id' => (int) $id ));
      echo json_encode(array( 'deleted' => true ));
    } else if($action == 'reorder') {
      $data = isset($actionData['data']) ? json_decode($actionData['data']) : null;
      if($data) {
        $this->reorderItems($data);
      }
      echo json_encode(array( 'success' => true ));
    }
  }

  private function reorderItems($data) {
    $lang = Lang::$lang;
    $tableName = $this->multiLang ? $this->cfg->tableName.'_'.Lang::$lang : $this->cfg->tableName;

    cn_db_reorderItems($data, $tableName, 0, $this->cfg->depth);
  }

  public function setValue($data) {
    $result = '';
    $cfg = $this->cfg;

    $orderedData = cn_order_menu($data);

    $result = $this->printRecursive($orderedData, $cfg);

    $this->append($result);
  }

  private function printRecursive($items, $cfg) {
    $tpl = '';

    foreach($items as $item) {
      if(isset($item->children) && count($item->children)) {
        $childrenTplView = '<ol>';
        $childrenTplView .= printRecursive($item->children, $cfg);
        $childrenTplView .= '</ol>';
      } else {
        $childrenTplView = '';
      }

      ob_start();
      include __DIR__.'/item_tpl.php';
      $tpl .= ob_get_contents();
      ob_end_clean();
    }

    return $tpl;
  }

  public function renderView() {
    if($this->mode == $this->cfg->reorderMethod || $this->mode == $this->cfg->removeMethod) return;

    $cfg = $this->cfg;
    $cfg->urlData = $this->urlData;
    ob_start();
    include __DIR__.'/main_tpl.php';
    $this->view = ob_get_contents();
    ob_end_clean();

    $items = DB_BASE::get(array(
      'table' => $this->cfg->tableName,
      'multiLang' => $this->multiLang,
      'query' => array( $cfg->join_column => $this->urlData->id ),
      'order' => '`ord` ASC'
    ));

    $this->setValue($items);
  }

  protected function parseConfig($cfg) {
    $defaults = array(
      'uniq_id' => 'plugin_innersortablelist_'.(++self::$inc),
      'title' => '',
      'cols' => array(
        array( 'name' => 'title', 'value' => 'title' )
      ),
      'addBtn' => (object) array( 'url' => 'add', 'text' => 'Add' ),
      'saveBtn' => array( 'text' => 'Save' ),
      'imageFolder' => '',
      'displayImage' => false,
      'editMode' => 'edit',
      'join_column' => 'join_column_id',
      'depth' => 1,
      'reorderMethod' => 'reorder',
      'removeMethod' => 'remove',
      'tableName' => 'test',
      'multiLang' => true,
      'displayField' => 'name',
      'displayFields' => false
    );

    $this->mergeConfig($cfg, $defaults);
  }
}
