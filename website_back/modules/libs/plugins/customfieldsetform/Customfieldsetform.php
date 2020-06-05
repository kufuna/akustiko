<?php
class P_Customfieldsetform extends Plugin {

  public $subscribes = array(
    'action' => 'processAction',
    'beforeRender' => 'processRender'
  );


  public function __construct($config) {

    $this->rawCfg = isset($config['config']) ? $config['config'] : array();
    $this->rootCfg = isset($config['rootConfig']) ? $config['rootConfig'] : array();
    $this->mode = isset($config['mode']) ? $config['mode'] : '';
    $this->multiLang = isset($this->rootCfg->multiLang) ? $this->rootCfg->multiLang : true;
    $this->urlData = isset($config['urlData']) ? $config['urlData'] : array();
    $this->editModeOnly = isset($this->rootCfg->editModeOnly) ? $this->rootCfg->editModeOnly : false;
    $this->parseConfig($this->rawCfg);
    $this->cfg->allLangChange = false;
  }

  private function generateUniqueLink($data, $inc = 0) {
    $target = $this->cfg->link_generator->target;
    $source = $this->cfg->link_generator->source;

    $res = URL::escapeURL($data[$source]).($inc ? '-'.$inc : '');
    $res = preg_replace('#\-+#', '-', $res);

    $query = array(
      'table' => $this->rootCfg->tableName,
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

    if($this->mode == 'add') {
      if($this->cfg->link_generator) {
        $target = $this->cfg->link_generator->target;
        $data[$target] = $this->generateUniqueLink($data);
      }
      $insertId = cn_db_insert($data, $this->rootCfg->tableName, $this->multiLang, true);

      foreach($this->children as $child) {
        if(isset($child->subscribes['afterAction'])) {
          $method = $child->subscribes['afterAction'];
          $child->$method($insertId);
        }
      }
    } else if($this->mode == 'edit') {
      $tableName = $this->multiLang ? $this->rootCfg->tableName.'_'.Lang::$lang : $this->rootCfg->tableName;
      @$copyTableName = $this->rootCfg->copyTableName;
      if($this->cfg->link_generator) {
        $target = $this->cfg->link_generator->target;
        $data[$target] = $this->generateUniqueLink($data);
      }

      if($this->editModeOnly) {

        $editItem = DB_BASE::get(array(
          'table' => $this->rootCfg->tableName,
          'single' => true,
          'multiLang' => $this->multiLang
        ));

        if(isset($_POST['fieldsetform-update-all-lang']) && $_POST['fieldsetform-update-all-lang'] == "checked"){
          DB::updateInAllLang($this->rootCfg->tableName,$data,$editItem->id);
        };

        cn_db_update($data, $tableName, $editItem->id);
        $insertId = $editItem->id;
      } else {

        if(isset($_POST['fieldsetform-update-all-lang']) && $_POST['fieldsetform-update-all-lang'] == "checked"){
          DB::updateInAllLang($this->rootCfg->tableName,$data,$this->urlData->id);
        };

        $updateData = ['described' => 'yes'];

        cn_db_update($updateData, 'products_list', $this->urlData->id);

        cn_db_insert($data, $copyTableName, $allLang = true);

        $insertId = $this->urlData->id;
      }

      foreach($this->children as $child) {
        if(isset($child->subscribes['afterAction'])) {
          $method = $child->subscribes['afterAction'];
          $child->$method($insertId);
        }
      }
    }
  }

  public function processRender() {
    if($this->mode == 'edit') {
      if($this->editModeOnly) {

        $editItem = DB_BASE::get(array(
          'table' => $this->rootCfg->tableName,
          'single' => true,
          'multiLang' => $this->multiLang
        ));

        $this->cfg->action = MODULE_URL;
      } else {
        $editId = isset($this->urlData->id) ? $this->urlData->id : 0;

        $editItem = DB_BASE::get(array(
          'table' => $this->rootCfg->tableName,
          'query' => array( 'id' => $editId ),
          'multiLang' => $this->multiLang
        ));

        $this->cfg->action = MODULE_URL.'edit/'.$editId;
      }

      PluginLoader::emitEvent('setValue', $editItem);

      $this->cfg->submitbtnText = 'Update';
      $this->cfg->allLangChange = isset($this->rootCfg->multiLang) && $this->rootCfg->multiLang == false ? false : true;
      $this->cfg->title = $this->cfg->edit_title ?: 'Edit news';
    }

    $this->renderView();
  }

  protected function renderView() {
    $cfg = $this->cfg;
    ob_start();
    include __DIR__.'/tpl.php';
    $this->view = ob_get_contents();
    ob_end_clean();
  }

  protected function parseConfig($cfg) {
    $defaults = array(
      'title' => '',
      'edit_title' => '',
      'update_excel' => false,
      'action' => MODULE_URL.'add/',
      'enctype' => 'multipart/form-data',
      'method' => 'post',
      'submitbtnText' => 'add',
      'link_generator' => false,
      'readOnly' => false
    );

    $this->mergeConfig($cfg, $defaults);
  }
}
