<?php
function cn_order_menu($list) {
  $orderedData = array();

  foreach($list as $item) {
    if(!@$item->parent) {
      if(!_cn_getOrderedItem($item->id, $orderedData)) $orderedData[] = $item;
    } else {
      _cn_insertItem($item, $list, $orderedData);
    }
  }

  _cn_sortRecursive($orderedData);

  return $orderedData;
}

function _cn_getOrderedItem($itemId, $orderedData) {
  foreach($orderedData as $item) {
    if($item->id == $itemId) return $item;

    if(@$item->children) {
      $checkChildren = _cn_getOrderedItem($itemId, $item->children);
      if($checkChildren) return $checkChildren;
    }
  }

  return false;
}

function _cn_getItemById($itemId, $list) {
  foreach($list as $item) {
    if($item->id == $itemId) return $item;
  }
  return null;
}

function _cn_getRoot($itemId, &$path, $list) {
  do {
    $item = _cn_getItemById($itemId, $list);
    array_unshift($path, $item);
    $itemId = $item->parent;
  } while($item->parent != 0);
  return $item;
}

function _cn_insertItem($itemToInsert, $list, &$orderedData) {
  $path = array();
  $root = _cn_getRoot($itemToInsert->parent, $path, $list);

  $parent = &$orderedData;
  foreach($path as $item) {
    $inserted = _cn_getOrderedItem($item->id, $orderedData);
    if(!$inserted) {
      $item->children = array();
      $parent[] = $item;
      $parent = &$item->children;
    } else {
      $parent = &$inserted->children;
    }

  }

  if(!_cn_getOrderedItem($itemToInsert->id, $orderedData)) $parent[] = $itemToInsert;
}

function _cn_cmp_ord($a, $b) {
  return $a->ord > $b->ord;
}

function _cn_sortRecursive(&$list) {
  usort($list, '_cn_cmp_ord');

  foreach($list as $item) {
    if(@$item->children) _cn_sortRecursive($item->children);
  }
}