<?php
class P_DynamicTable extends Plugin {
  public $subscribes = array(
    'beforeAction' => 'getData',
    'action' => 'processAction',
    'beforeRender' => 'loadData'
  );

  public function __construct($config) {
    $this->rawCfg = isset($config['config']) ? $config['config'] : array();
    $this->rootCfg = isset($config['rootConfig']) ? $config['rootConfig'] : array();
    $this->mode = isset($config['mode']) ? $config['mode'] : '';
    $this->multiLang = isset($this->rootCfg->multiLang) ? $this->rootCfg->multiLang : true;

    $this->parseConfig($this->rawCfg);
  }

  public function getData() {
    if(isset($_POST['setactive'])) {
      return array(
        'action' => 'setactive',
        'active' => $_POST['setactive'],
        'id' => isset($_POST['id']) ? $_POST['id'] : 0
      );
    } else if($this->mode == 'remove' && isset($_POST['id'])) {
      return array(
        'action' => 'remove',
        'id' => $_POST['id']
      );
    } 
    if($this->mode == 'export') {
      return array(
        'action' => 'export'
      );
    }

    return '';
  }

  public function processAction($data) {
    $actionData = isset($data['P_DynamicTable']) ? $data['P_DynamicTable'] : array();
    $action = isset($actionData['action']) ? $actionData['action'] : null;
    $active = isset($actionData['active']) ? $actionData['active'] : null;
    $id = isset($actionData['id']) ? (int) $actionData['id'] : 0;

    $tableName = $this->multiLang ? $this->rootCfg->tableName.'_'.Lang::$lang : $this->rootCfg->tableName;

    if($action == 'setactive' && $active !== null && $id > 0) {
      if(isset($this->rootCfg->setActiveAllLang) && $this->rootCfg->setActiveAllLang){
        $dbTableName = $this->rootCfg->tableName;
        cn_db_update(array( 'active' => $active ), $dbTableName, $id,true);
      }else{
        cn_db_update(array( 'active' => $active ), $tableName, $id);
      }

      if (isset($this->cfg->updateTable) && $this->cfg->updateTable) {
        $updateTableName = $this->cfg->updateTable->table;
        $status = ($active ? 0 : 1);

        if (isset($this->cfg->updateTable->multiLang) && $this->cfg->updateTable->multiLang) {
          DB::updateTable(array('status' => $status), $updateTableName, $id, true);
        } else {
          DB::updateTable(array('status' => $status), $updateTableName, $id);
        }

      }

      echo json_encode(array( 'success' => true ));
    
    } else if($action == 'remove' && $id > 0) {
      $itemData = DB_BASE::get(array(
        'table' => $this->rootCfg->tableName,
        'multiLang' => $this->multiLang,
        'query' => array( 'id' => (int) $id )
      ));

      if($itemData) {
        PluginLoader::emitEvent('itemRemove', $itemData);
      }

      cn_db_remove($tableName, array( 'id' => (int) $id ));
      echo json_encode(array( 'deleted' => true ));
    } else if($action == 'export') {

      $data = DB_BASE::get(array(
        'table' => $this->rootCfg->tableName,
        'multiLang' => $this->multiLang,
        'order' => $this->cfg->order
      ));
      
      $mails = array();
      foreach($data as $item) {
        $row = array();
        foreach($this->cfg->export->fields as $field) {
          $row[ucfirst($field)] = isset($item->{$field}) ? $item->{$field} : '';
        }
        
        $mails[] = $row;
      }
      
      Excel::downloadExcel($mails, "subscribers.xls");
      exit;
    }
  }

  public function loadData() {
    if($this->mode != 'overview') return;

    $this->renderView();

    if($this->cfg->dataLoaderMethod) {
      $methodName = $this->cfg->dataLoaderMethod;
      $data = DB::$methodName();
    } else {
      $data = DB_BASE::get(array(
        'table' => $this->rootCfg->tableName,
        'multiLang' => $this->multiLang,
        'order' => $this->cfg->order
      ));
    }

    $result = '';

    foreach($data as $row) {
      $result .= '<tr>';
      foreach($this->cfg->cols as $col) {
        $value = isset($row->{$col->value}) ? $row->{$col->value} : '';

        if(isset($col->renderer) and method_exists($this, 'renderer_'.$col->renderer)) {
          $method = 'renderer_'.$col->renderer;
          $value = $this->$method($value, $row);
        }

        $result .= '<td>'.$value.'</td>';
      }
      $result .= '</tr>';
    }

    $this->append($result);
  }

  protected function renderView() {
    $cfg = $this->cfg;
    $renderedCols = $this->renderCols($cfg);
    ob_start();
    include __DIR__.'/tpl.php';
    $this->view = ob_get_contents();
    ob_end_clean();
  }

  protected function renderCols($cfg) {
    $cols = '';
    foreach($cfg->cols as $col) {
      $cols .= '<th'.($col->name == 'actions' ? ' class="dinamyc-actions-th"' : '').'>'.$col->name.'</th>';
    }

    return $cols;
  }

  protected function renderer_photo($val, $row) {
    if(!$val || $val == '[]') {
      $val = ROOT_URL.'uploads/no_photo.jpg';
    } else {
      /* in case of multi photo */
      $images = json_decode($val);
      if($images and isset($images[0])) $val = $images[0];

      $val = ROOT_URL.'uploads/'.$this->cfg->imageFolder.'/'.(is_string($val) ? $val : $val->src);
    }

    return '<div class="center">'
            .'<a href="'.$val.'" class="lightbox"><img src="'.$val.'" alt="" class="post-photo-in-table" /></a>'
          .'</div>';
  }

  protected function renderer_buildingPhoto($val, $row) {
    if(!$val || $val == '[]') {
      $val = ROOT_URL.'uploads/no_photo.jpg';
    } else {
      /* in case of multi photo */
      $image = json_decode($val);

      $val = ROOT_URL.'uploads/'.$this->cfg->imageFolder.'/'.$image->src;
    }

    return '<div class="center">'
            .'<a href="'.$val.'" class="lightbox"><img src="'.$val.'" alt="" class="post-photo-in-table" /></a>'
          .'</div>';
  }

  protected function renderer_toggleBtn($val, $row) {
    $val = (int) $val;

    return '<div class="grid9 on_off custom-control custom-switch" style="text-align: center; width: 50px; margin: 0 auto;">'
            .'<input type="checkbox" id="swither-'.$row->id.'" data-url="'.POSTMODULE_URL.'setactive/" class="custom-control-input dynamictable-row-activator"'.($val ? ' checked="checked"' : '').' data-itemid="'.$row->id.'" /><label class="custom-control-label" for="swither-'.$row->id.'"></label>'
          .'</div>';
  }

  protected function renderer_actionBtns($val, $row) {

    return '<div class="center dinamic-center">'
            .( $this->cfg->deleteBtn ? '<a href="javascript: void(0)" data-id="'.$row->id.'" data-url="'.POSTMODULE_URL.'remove/" class="btn btn-outline-secondary btn-sm btn-icon waves-effect waves-themed btn-action-def del-btn tablectrl_small tipS actBtns removeBtn" title="Remove"><i class="fal fa-times"></i></a>' : '')

            .( $this->cfg->editBtn ? '<a href="'.MODULE_URL.'edit/'.$row->id.'/" class="btn btn-outline-secondary btn-sm btn-icon waves-effect waves-themed btn-action-def list-edit-btn tablectrl_small edit-btn tipS L15" title="Edit"><i class="fal fa-pen"></i></a>' : '')
            

            .( $this->cfg->pdfBtn ? '<a href="'.SITE_URL.'pdf/'.$row->id.'/'.($this->cfg->pdfPath ? $this->cfg->pdfPath.'/' : '').'download" target="_blank" class="btn btn-outline-secondary btn-sm btn-icon waves-effect waves-themed btn-action-def list-edit-btn tablectrl_small edit-btn tipS L15" title="Edit"><i class="fal fa-file-pdf"></i></a>' : '')

          .'</div>';

  }

  protected function parseConfig($cfg) {
    $defaults = array(
      'title' => '',
      'cols' => array(
        array( 'name' => 'title', 'value' => 'title' )
      ),
      'addBtn' => (object) array( 'url' => 'add', 'text' => 'Add' ),
      'deleteBtn' => true,
      'editBtn' => true,
      'pdfBtn' => false,
      'pdfPath' => false,
      'imageFolder' => '',
      'dataLoaderMethod' => false,
      'export' => false,
      'columnFilter' => false,
      'order' => 'id DESC',
      'updateTable' => false
    );

    $this->mergeConfig($cfg, $defaults);
  }
}
