<?php
class P_MultiSelectJoin extends Plugin {
  protected static $inc = 0;
  private $formData = array();

  public $subscribes = array(
    'beforeAction' => 'getData',
    'beforeRender' => 'renderView',
    'setValue' => 'setValue',
    'afterAction' => 'commit'
  );

  private $modules = array();

  public function __construct($config) {
    $this->rawCfg = isset($config['config']) ? $config['config'] : array();
    $this->rootCfg = isset($config['rootConfig']) ? $config['rootConfig'] : array();
    $this->mode = isset($config['mode']) ? $config['mode'] : '';
    $this->multiLang = isset($this->rootCfg->multiLang) ? $this->rootCfg->multiLang : true;
    $this->urlData = isset($config['urlData']) ? $config['urlData'] : array();

    $this->parseConfig($this->rawCfg);
  }

  public function commit($insertId) {
    global $CFG;

    $cfg = $this->cfg;
    $value = isset($this->formData[$cfg->field_name]) ? $this->formData[$cfg->field_name] : '';

    if($cfg->required and !$value) {
      throw new Exception($cfg->label." is empty");
    }

    $value = $this->escape($value);
    $value = $value ? $value : array();

    if(!$cfg->langSync && $this->cfg->joinTableMultilang) {
      $removeTable = $cfg->joinTable.'_'.Lang::$lang;
    } else {
      $removeTable = $cfg->joinTable;
    }

    if(!$cfg->langSync && $this->mode == 'edit' && $this->cfg->joinTableMultilang) {
      $insertTable = $cfg->joinTable.'_'.Lang::$lang;
      $tmpMultiLang = false;
    } else {
      $insertTable = $cfg->joinTable;
      $tmpMultiLang = $this->cfg->joinTableMultilang;
    }

    if($cfg->langSync) {
      foreach($CFG['SITE_LANGS'] as $lang) {
        $removeTable = $cfg->joinTable.'_'.$lang;
        cn_db_remove($removeTable, array( $this->cfg->left_col => $insertId ));
      }
    } else {
      cn_db_remove($removeTable, array( $this->cfg->left_col => $insertId ));
    }

    foreach($value as $id) {
      try {
        cn_db_insert(array( $this->cfg->left_col => $insertId, $this->cfg->right_col => $id ), $insertTable, $tmpMultiLang);
      } catch(Exception $e) {
        continue; // Dublicate entry
      }
    }

    if($cfg->countsCol) {
      $pdo = DB::$pdo;
      $table1 = $cfg->tableName.($this->multiLang ? '_'.Lang::$lang : '');
      $table2 = $cfg->joinTable.($this->cfg->joinTableMultilang ? '_'.Lang::$lang : '');
      $st = $pdo->query("SELECT a.id, count(b.id) as `count` FROM `$table1` a
                          LEFT JOIN `$table2` b ON b.{$this->cfg->right_col} = a.id
                          GROUP BY a.id");

      $data = [];

      while($row = $st->fetchObject()) {
        cn_db_update(array( $cfg->countsCol => $row->count ), $cfg->tableName, $row->id, true);
      }
    }
  }

  public function getData($data) {
    $this->formData = $data;
    return null;
  }

  public function setValue($data) {
    $rows = DB_BASE::get(array(
      'table' => $this->cfg->joinTable,
      'multiLang' => $this->cfg->joinTableMultilang,
      'query' => array( $this->cfg->left_col => $data->id )
    ));

    $rightIds = array();
    foreach($rows as $row) {
      $rightIds[] = $row->{$this->cfg->right_col};
    }

    $this->cfg->value = $rightIds;
  }

  private function renderOptions() {
    $cfg = $this->cfg;
    $cfg->value = $cfg->value ? $cfg->value : array();

    $tableName = $cfg->tableName;
    $multiLang = $cfg->multiLang;

    if($cfg->dataLoaderMethod) {
      $methodName = $cfg->dataLoaderMethod;
      $this->modules = DB::$methodName();
    } else {
      $this->modules = DB_BASE::get(array(
        'table' => $tableName,
        'multiLang' => $multiLang
      ));
    }

    if($cfg->defaultValue) {
      array_unshift($this->modules, $cfg->defaultValue);
    }

    $result = '';

    foreach($this->modules as $mod) {
      if(!isset($mod->{$cfg->optionValue})) continue;

      $selected = in_array($mod->{$cfg->optionValue}, $cfg->value) ? ' selected="selected"' : '';
      $result .= '<option'.$selected.' value="'.$mod->{$cfg->optionValue}.'">'.$mod->{$cfg->optionName}.'</option>';
    }

    return $result;
  }

  public function renderView() {
    $cfg = $this->cfg;
    $options = $this->renderOptions();
    ob_start();
    include __DIR__.'/tpl.php';
    $this->view = ob_get_contents();
    ob_end_clean();
  }

  protected function parseConfig($cfg) {
    $uniqInc = ++self::$inc;

    $defaults = array(
      'label' => 'Select box',
      'placeholder' => '',
      'value' => '',
      'field_name' => 'plugin_multiselectjoin_'.$uniqInc,
      'id' => 'plugin_multiselectjoin_id_'.$uniqInc,
      'required' => false,
      'escape' => array(),
      'errorText' => 'Selectbox is empty',
      'columnName' => 'category',
      'width' => '350',
      'optionName' => 'name',
      'optionValue' => 'value',
      'search' => false,
      'dataLoaderMethod' => false,
      'tableName' => 'test',
      'multiLang' => true,
      'defaultValue' => false,
      'joinTable' => 'multiselect_join_table',
      'left_col' => 'left',
      'right_col' => 'right',
      'joinTableMultilang' => true,
      'langSync' => false,
      'countsCol' => false
    );

    $this->mergeConfig($cfg, $defaults);
  }
}
