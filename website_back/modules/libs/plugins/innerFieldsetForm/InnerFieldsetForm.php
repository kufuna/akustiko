<?php
class P_InnerFieldsetForm extends Plugin {

  public $subscribes = array(
    'action' => 'processAction',
    'beforeRender' => 'processRender'
  );

  public function __construct($config) {
    $this->rawCfg = isset($config['config']) ? $config['config'] : array();
    $this->rootCfg = isset($config['rootConfig']) ? $config['rootConfig'] : array();
    $this->mode = isset($config['mode']) ? $config['mode'] : '';
    $this->urlData = isset($config['urlData']) ? $config['urlData'] : array();
    $this->parseConfig($this->rawCfg);
    $editingObjectId = isset($config['urlData']->id) ? $config['urlData']->id : null;
    $this->cfg->back_url = MODULE_URL.'edit/'.$editingObjectId.'/';
    $this->multiLang = $this->cfg->multiLang;
  }

  private function generateUniqueLink($data, $inc = 0) {
    $target = $this->cfg->link_generator->target;
    $source = $this->cfg->link_generator->source;

    $res = URL::escapeURL($data[$source]).($inc ? '-'.$inc : '');
    $res = preg_replace('#\-+#', '-', $res);

    $query = array(
      'table' => $this->cfg->tableName,
      'query' => array( $target => $res ),
      'single' => true,
      'multiLang' => $this->multiLang
    );

    if($this->mode == 'edit') {
      $query['not_query'] = array( 'id' => $this->urlData->id );
    }

    $check = DB_BASE::get($query);

    if($check) {
      return $this->generateUniqueLink($data, ++$inc);
    } else {
      return $res;
    }
  }

  public function processAction($data) {
    foreach($data as $k => $v) {
      if($v === null) unset($data[$k]);
    }

    if($this->mode == $this->cfg->action) {
      if($this->cfg->link_generator) {
        $target = $this->cfg->link_generator->target;
        $data[$target] = $this->generateUniqueLink($data);
      }

      $data[$this->cfg->join_column] = $this->urlData->id;
      $insertId = cn_db_insert($data, $this->cfg->tableName, $this->multiLang, true);

      foreach($this->children as $child) {
        if(isset($child->subscribes['afterAction'])) {
          $method = $child->subscribes['afterAction'];
          $child->$method($insertId);
        }
      }
    } else if($this->mode == $this->cfg->edit_action) {
      $tableName = $this->multiLang ? $this->cfg->tableName.'_'.Lang::$lang : $this->cfg->tableName;
      if($this->cfg->link_generator) {
        $target = $this->cfg->link_generator->target;
        $data[$target] = $this->generateUniqueLink($data);
      }

      cn_db_update($data, $tableName, $this->urlData->id);
      $insertId = $this->urlData->id;

      foreach($this->children as $child) {
        if(isset($child->subscribes['afterAction'])) {
          $method = $child->subscribes['afterAction'];
          $child->$method($insertId);
        }
      }
    }
  }

  public function processRender() {
    if($this->mode == $this->cfg->edit_action) {
      $editId = isset($this->urlData->id) ? $this->urlData->id : 0;

      $editItem = DB_BASE::get(array(
        'table' => $this->cfg->tableName,
        'query' => array( 'id' => $editId ),
        'multiLang' => $this->multiLang
      ));

      $this->cfg->back_url = MODULE_URL.'edit/'.$editItem->{$this->cfg->join_column}.'/';
      $this->cfg->action = $this->cfg->edit_action;

      PluginLoader::emitEvent('setValue', $editItem);

      $this->cfg->submitbtnText = 'Update';
      $this->cfg->allLangChange = isset($this->rootCfg->multiLang) && $this->rootCfg->multiLang == false ? false : true;
      $this->cfg->title = $this->cfg->edit_title ?: 'Edit news';
    }

    $this->renderView();
  }

  protected function renderView() {
    $cfg = $this->cfg;
    $cfg->urlData = $this->urlData;
    ob_start();
    include __DIR__.'/tpl.php';
    $this->view = ob_get_contents();
    ob_end_clean();
  }

  protected function parseConfig($cfg) {
    $defaults = array(
      'title' => '',
      'edit_title' => '',
      'action' => 'add',
      'edit_action' => 'edit',
      'enctype' => 'multipart/form-data',
      'method' => 'post',
      'submitbtnText' => 'add',
      'link_generator' => false,
      'tableName' => 'test',
      'multiLang' => false,
      'join_column' => 'join_column_id',
      'readOnly' => false
    );

    $this->mergeConfig($cfg, $defaults);
  }
}
