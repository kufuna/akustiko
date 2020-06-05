<?php
class PluginLoader {
  private static $rootConfig;
  private static $mode;
  private static $plugins;
  private static $PluginsToRender = array();
  private static $urlData;
  private static $processData = array();
  private static $modeData;
  private static $outputMode;
  private static $multiLang;
  private static $dataFromPlugins = array();

  public static $error;
  public static $success;

  public static function init($rootConfig, $mode, $urlData, $outputMode = 'HTML') {
    self::$rootConfig = $rootConfig; // Everything from Config.json
    self::$plugins = $rootConfig->plugins;

    self::$outputMode = $outputMode;

    self::$mode = $mode; // Defined by main.php

    // if table has lang support, like news_ka, news_en
    self::$multiLang = isset($rootConfig->multiLang) ? $rootConfig->multiLang : true;

    //if($mode == 'setactive') return self::setActive(); // Returns JSON

    self::$urlData = $urlData; // Variables from URL

    self::load(self::$plugins); // Just create plugin objects

    try {
      //self::$modeData = self::getDataByMode();
      //self::processActionData();

      if($_SERVER['REQUEST_METHOD'] == 'POST') {
        self::$dataFromPlugins = self::callToSubscribers('beforeAction', $_POST); //self::getDataFromPlugins();
        self::callToSubscribers('action', self::$dataFromPlugins);
      }

      self::callToSubscribers('beforeRender');
    } catch(Exception $e) {
      self::$error = $e->getMessage();
    }

    /*try {
      self::setPluginData();
    } catch(Exception $e) {
      self::$error = $e->getMessage();
    }*/

    self::renderNotes();

    self::renderPlugins(self::$PluginsToRender);
  }

  public static function emitEvent($eventName, $data = array()) {
    self::callToSubscribers($eventName, $data);
  }

  private static function callToSubscribers($eventName, $data = array(), $plugins = false) {
    $plugins = $plugins ?: self::$PluginsToRender;
    $result = array();

    foreach($plugins as $plugin) {
      if(isset($plugin->subscribes) && isset($plugin->subscribes[$eventName])) {
        $pluginCfg = $plugin->getRawConfig();
        $methodName = $plugin->subscribes[$eventName];
        if(method_exists($plugin, $methodName)) {
          $columnName = isset($pluginCfg->columnName) ? $pluginCfg->columnName : get_class($plugin);
          $result[$columnName] = $plugin->$methodName($data);
        }
      }

      $children = $plugin->getChildren();
      if(count($children) > 0) {
        $childrenResult = self::callToSubscribers($eventName, $data, $children);
        $result = array_merge($result, $childrenResult);
      }
    }

    return $result;
  }

  private static function renderPlugins($plugins) {
    if(self::$outputMode == 'HTML') {
      foreach($plugins as $plugin) {
        $children = $plugin->getChildren();
        if(count($children) > 0) {
          self::renderChildrenPlugins($plugin, $children);
        }

        echo $plugin->getView();
      }
    } else if(count($plugins) && self::$mode != 'remove') {
      foreach($plugins as $plugin) {
        $children = $plugin->getChildren();
        if(count($children) > 0) {
          self::renderChildrenPlugins($plugin, $children);
        }

        echo $plugin->getView();
      }
    }
  }

  private static function renderChildrenPlugins($plugin, $children) {
    foreach($children as $child) {
      $plugin->append($child->getView());
    }
  }

  private static function renderNotes() {
    if(self::$outputMode == 'JSON') {
      if(PluginLoader::$error) {
        echo json_encode(array( 'error' => PluginLoader::$error ));
      } else if(PluginLoader::$success) {
        echo json_encode(array( 'success' => true, 'note' => PluginLoader::$success ));
      }
    } else {
      if(PluginLoader::$error) {
        echo '<div class="nNote nFailure"><p>'.self::$error.'</p></div>';
      } else if(PluginLoader::$success) {
        echo '<div class="nNote nSuccess"><p>'.self::$success.'</p></div>';
      }
    }
  }

  private static function load($plugins, $parentPlugin = false) {
    foreach($plugins as $plugin) {
      /* Detect plugin mode and exclude if necessary */
      $pluginMode = isset($plugin->mode) ? explode(',', $plugin->mode) : array();
      if(self::$mode && !in_array(self::$mode, $pluginMode)) {
        continue; // skip this plugin
      }

      include_once __DIR__.'/'.$plugin->name.'/'.ucfirst($plugin->name).'.php';

      $className = 'P_'.ucfirst($plugin->name);
      $_CurrentPlugin = new $className(array(
        'config' => $plugin,
        'rootConfig' => self::$rootConfig,
        'parentConfig' => $parentPlugin ? $parentPlugin->getRawConfig() : null,
        'mode' => self::$mode,
        'urlData' => self::$urlData
      ));

      if(isset($_CurrentPlugin->requires)) {
        foreach($_CurrentPlugin->requires as $inc) {
          include_once __DIR__.'/../functions/'.$inc;
        }
      }

      if(isset($plugin->plugins)) {
        // Load children recursive
        self::load($plugin->plugins, $_CurrentPlugin);
      }

      //$_CurrentPlugin->setRawConfig($plugin);

        if($parentPlugin) {
          $parentPlugin->addChild($_CurrentPlugin);
        } else {
          self::$PluginsToRender[] = $_CurrentPlugin;
        }
    }
  }
}

include_once __DIR__.'/../plugins/Plugin.php';
include_once __DIR__.'/../functions/builder/parse_config.php';
include_once __DIR__.'/../functions/db/db_insert.php';
include_once __DIR__.'/../functions/db/db_update.php';
include_once __DIR__.'/../functions/db/db_remove.php';
include_once __DIR__.'/../functions/db/db_set_active.php';
include_once __DIR__.'/../functions/db/db_reorder.php';
include_once __DIR__.'/../functions/db/db_update_building.php';
include_once __DIR__.'/../functions/image/convert_to_jpg.php';
include_once __DIR__.'/../functions/image/create_thumbs.php';
include_once __DIR__.'/../functions/image/crop_image.php';
include_once __DIR__.'/../functions/image/EasyThumbnail.php';
include_once __DIR__.'/../functions/upload/upload_file.php';
include_once __DIR__.'/../functions/upload/get_files.php';
include_once __DIR__.'/../functions/db/image_used.php';
include_once __DIR__.'/../functions/db/db_truncate.php';
