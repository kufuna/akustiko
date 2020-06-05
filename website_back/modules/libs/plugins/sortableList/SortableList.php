<?php
class P_SortableList extends Plugin {
  public $subscribes = array(
    'beforeAction' => 'getData',
    'action' => 'processAction',
    'beforeRender' => 'renderView'
  );

  public function __construct($config) {
    $this->rawCfg = isset($config['config']) ? $config['config'] : array();
    $this->rootCfg = isset($config['rootConfig']) ? $config['rootConfig'] : array();
    $this->mode = isset($config['mode']) ? $config['mode'] : '';
    $this->multiLang = isset($this->rootCfg->multiLang) ? $this->rootCfg->multiLang : true;

    $this->parseConfig($this->rawCfg);
  }

  public function getData($data) {
    if($this->mode == 'remove' && isset($_POST['id'])) {
      return array(
        'action' => 'remove',
        'id' => $_POST['id']
      );
    } else if($this->mode == 'reorder' && isset($_POST['data'])) {
      return array(
        'action' => 'reorder',
        'data' => stripslashes($_POST['data'])
      );
    }

    return '';
  }

  public function processAction($data) {
    $actionData = isset($data['P_SortableList']) ? $data['P_SortableList'] : array();
    $action = isset($actionData['action']) ? $actionData['action'] : null;
    $id = isset($actionData['id']) ? (int) $actionData['id'] : 0;

    $tableName = $this->multiLang ? $this->rootCfg->tableName.'_'.Lang::$lang : $this->rootCfg->tableName;

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
    $tableName = $this->multiLang ? $this->rootCfg->tableName.'_'.Lang::$lang : $this->rootCfg->tableName;

    cn_db_reorderItems($data, $tableName, 0, $this->cfg->depth);
  }

  public function setValue($data) {
    $result = '';
    $cfg = $this->cfg;

    $orderedData = cn_order_menu($data);

    function printRecursive($items, $cfg) {
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

    $result = printRecursive($orderedData, $cfg);

    $this->append($result);
  }

  public function renderView() {
    if($this->mode != 'overview') return;

    $cfg = $this->cfg;
    ob_start();
    include __DIR__.'/main_tpl.php';
    $this->view = ob_get_contents();
    ob_end_clean();

    if($this->cfg->dataLoaderMethod) {
      $methodName = $this->cfg->dataLoaderMethod;
      $items = DB::$methodName();
    } else {
      $items = DB_BASE::get(array(
        'table' => $this->rootCfg->tableName,
        'multiLang' => $this->multiLang,
        'order' => '`ord` ASC'
      ));
    }

    $this->setValue($items);
  }

  protected function parseConfig($cfg) {
    $defaults = array(
      'title' => '',
      'cols' => array(
        array( 'name' => 'title', 'value' => 'title' )
      ),
      'addBtn' => array( 'url' => 'add', 'text' => 'Add' ),
      'saveBtn' => array( 'text' => 'Save' ),
      'imageFolder' => '',
      'depth' => 1,
      'reorderMethod' => 'reorderMenu',
      'displayField' => 'name',
      'displayFields' => false,
      'dataLoaderMethod' => false
    );

    $this->mergeConfig($cfg, $defaults);
  }
}
