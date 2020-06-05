<?php
abstract class Page {
  protected $request;
  protected $note;

  public $seo = array();

  public function __construct($request, $note = '') {
    $this->request = $request;
    $this->note = $note;

    /* if page is 404 set status code 404 */
    if(get_called_class() == 'Page_404' && function_exists('http_response_code')) {
        http_response_code(404);
    }

    if(isset($this->paramBinding)) {
      foreach($this->paramBinding as $key => $val) {
        if(is_array($val)) {
          $type = isset($val['type']) ? $val['type'] : 'number';
          $name = isset($val['name']) ? $val['name'] : '__defaultParam';
        } else {
          $type = 'number';
          $name = (string) $val;
        }

        $default = isset($val['default']) ? $val['default'] : null;

        switch($type) {
          case 'number':
            $this->$name = isset($request->params[$key]) ? (int) $request->params[$key] : $default;
          break;
          case 'string':
            $this->$name = isset($request->params[$key]) && $request->params[$key] ? (string) $request->params[$key] : $default;
          break;
        }
      }
    }
  }

  public function getTpls() {
    if(method_exists($this, 'loadData')) {
      try {
        $this->loadData();
      } catch(Exception $e) {
        if($e->getCode() == 401 || $e->getCode() == 302) return $this->redirect($e->getMessage());
        //$Page = new Page_404($this->request, $e->getMessage());
        $Page = $this->loadErrorMessage($this->request, $this->getCanonical());
        return $Page->getTpls();
      }
    }

    if(method_exists($this, 'getCanonical')) {
      $canonical = $this->getCanonical();

      $checkLevel = isset($this->checkLevel) ? $this->checkLevel : 4;
      if(!URL::equalsCurrentUrl($canonical, $checkLevel)) { // Second param is check level
        return $this->redirect($canonical);
      }
    }

    try {
      return $this->getTemplates();
    } catch(Exception $e) {
      $Page = new Page_404($this->request, $e->getMessage());
      return $Page->getTpls();
    }
  }
  
  public function loadErrorMessage($request, $canonical) {
    $Page = new Page_404($request, $canonical);
    $Page->request->pageObject = $this; 
    $Page->getTemplates();
    return $Page;
  }

  public function getAlternates() {
    global $CFG;
    $canonical = $this->getCanonical();
    $rootUrl = substr(ROOT_URL, 0, strlen(ROOT_URL) - 1); // Remove last slash

    $ms = array();
    $pattern = '';
    foreach($CFG['SITE_LANGS'] as $l) $pattern .= '/'.$l.'/|';
    preg_match('#'.$rootUrl.'('.$pattern.'/)(.*?)$#', $canonical, $ms);

    $result = array();
    if(isset($ms[2])) {
      foreach($CFG['SITE_LANGS'] as $l) $result[$l] = ROOT_URL.$l.'/'.$ms[2];
    } else {
      foreach($CFG['SITE_LANGS'] as $l) $result[$l] = ROOT_URL.$l.'/';
    }

    return (object) $result;
  }

  public function redirect($url) {
    return array(
      (object) array( "tpl" => "redirect", "data" => (object) array(  'url' => $url  ) )
    );
  }

  private function getModuleNameByLink($link) {
    if(preg_match('#^'.SITE_URL.'#', $link)) {
      $moduleName = str_replace(SITE_URL, '', $link);
      $moduleName = explode('/', $moduleName);
      $moduleName = $moduleName[0];
      return $moduleName;
    } else {
      return '';
    }
  }

  public function activateMenuItems(&$menuItems) {
    $activeExists = false;

    foreach($menuItems as &$item) {
      if(!$item->link) $item->link = 'javascript: void(0)';

      if(isset($this->relatedPages)) {
        $relatedModuleNames = $this->relatedPages;
        $moduleName = $this->getModuleNameByLink(URL::parseLink($item->link));
        $item->active = in_array($moduleName, $relatedModuleNames);
      } else if(isset($this->relatedLinks)) {
        foreach($this->relatedLinks as $relatedLink) {
          $item->active = $item->link == $relatedLink;
        }
      }

      if(!@$item->active) {
        $checkLevel = isset($this->urlCheckLevel) ? $this->urlCheckLevel : 4;
        $item->active = URL::equalsCurrentUrl(URL::parseLink($item->link), $checkLevel);
      }

      if(isset($item->children)) {
        $childrenActive = $this->activateMenuItems($item->children);
        $item->active = $item->active ? true : $childrenActive;
      }

      if(@$item->active) {
        $activeExists = true;
      }
    }

    return $activeExists;
  }

  public function getActiveMenuItem($list) {
    $path = array();

    function findActiveMenuItem($list) {
      foreach($list as $item) {
        if(@$item->active) return $item;

        if(@$item->children) {
          $activeItem = findActiveMenuItem($item->children);
          if($activeItem) return $activeItem;
        }
      }

      return false;
    }

    function getRootItem($item, $list) {
      while(@$item->parent) {
        $item = _getItemById($item->parent, $list);
      }

      return $item;
    }

    function _getItemById($id, $list) {
      foreach($list as $item) {
        if($item->id == $id) return $item;

        if(@$item->children) {
          $activeItem = _getItemById($id, $item->children);
          if($activeItem) return $activeItem;
        }
      }

      return null;
    }

    $activeItem = findActiveMenuItem($list);
    $root = getRootItem($activeItem, $list);

    return $root;
  }

  public function getLinksForSitemap($lang) {
    if(isset($this->disableForSitemap)) return array();

    $canonical = $this->getCanonical();
    $links = array();

    $links[] = array(
      'url' => str_replace(SITE_URL, ROOT_URL.$lang.'/', $canonical),
      'changefreq' => 'weekly',
      'priority' => '1.0'
    );

    return $links;
  }

  public abstract function getTemplates();

  protected function escape($str) {
    return str_replace('"', "'", strip_tags($str));
  }

  protected function description($str) {
    return mb_substr($this->escape($str), 0, 160);;
  }

  protected function keywords($str) {
    return str_replace(' ', ', ', $this->escape($str));
  }
}
