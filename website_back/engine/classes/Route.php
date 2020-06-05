<?php
class Route {
  private static $routes = array();

  public static function setRoute($url, $file) {
    if($url && $file) {
      $params = explode('/', $url);
      $root = &self::$routes;

      $paramNames = array();

      foreach($params as $param) {
        if(!$param) continue;

        if(preg_match('#\{.*\}#', $param)) {
          $key = '%param%';
        } else {
          $key = $param;
        }

        if(empty($root[$key])) {
          $root[$key] = array();
        }

        $root = &$root[$key];

        if($key == '%param%') {
          $paramNames[] = str_replace(array( '{', '}' ), '', $param);
        }
      }

      $root['%class_name%'] = $file;
      $root['%param_names%'] = $paramNames;
    }
  }

  private static function getRoute($url) {
    $params = explode('/', $url);
    $root = &self::$routes;

    $paramsToPass = array();
    $paramValues = array();

    foreach($params as $param) {
      if(isset($root[$param])) { // This is fixed value
        $root = &$root[$param];
      } else if(isset($root['%param%'])) { // This is param
        $root = &$root['%param%'];
        $paramValues[] = $param;
      }
    }

    if(isset($root['%param_names%'])) { // This is final
      $i = 0;
      foreach($root['%param_names%'] as $paramName) {
        $paramsToPass[$paramName] = $paramValues[$i];
        $i++;
      }
    }

    if(isset($root['%class_name%'])) { // This is final
      return array( 'class' => $root['%class_name%'], 'params' => $paramsToPass );
    }

    return false;
  }

  public static function getInstance($request) {
    $url = trim(implode('/', $request->params), '/');

    $route = self::getRoute($url);

    if($route && $route['class']) {
      $className = self::getClassName($route['class']);
      $pageFile = __DIR__.'/../../pages/'.$route['class'].'.php';

      if(file_exists($pageFile)) {
        self::modifyRequest($request, $route['class']);
      }
    } else {
      $className = self::getClassName($request->module);
      $pageFile = __DIR__.'/../../pages/'.$request->module.'/'.$className.'.php';
    }

    if(file_exists($pageFile)) {
      include_once $pageFile;
      $className = 'Page_'.$className;
      $instance = new $className($request);
      if($route && $route['params']) {
        foreach($route['params'] as $k => $v) {
          $instance->$k = strip_tags($v);
        }
      }
      return $instance;
    } else {
      /* If requested module doesn't exist render module 404 */
      return new Page_404($request);
    }
  }

  private static function modifyRequest($request, $url) {
    $request->module = str_replace('/', '-', strtolower($url));
  }

  private static function getClassName($name) {
    $name = explode('/', $name);
    $name = end($name);
    return str_replace('-', '_', ucfirst($name));
  }
}
