<?php
class PageHead extends HeadModule {
  protected function getTitle($request) {
    if(isset($request->pageObject->seo['title'])) return $request->pageObject->seo['title'];

    if(isset($request->data) and $request->data == '404') return L('page_not_found');
  
    switch($request->module) {
      case 'home':
        return L('website_seo_title');
      default:
        return L('website_seo_title');
    }
  }
  
  protected function getDescription($request) {
    if(isset($request->pageObject->seo['description'])) return $request->pageObject->seo['description'];

    if(isset($request->data) and $request->data == '404') return L('page_not_found');
    
    switch($request->module) {
      case 'home':
        return L('website_seo_description');
      default:
        return L('website_seo_description');
    }
  }
  
  protected function getKeywords($request) {
    if(isset($request->pageObject->seo['keywords'])) return $request->pageObject->seo['keywords'];

    if(isset($request->data) and $request->data == '404') return L('page_not_found');
    
    switch($request->module) {
      case 'home':
        return L('website_seo_keywords');
      default:
        return L('website_seo_keywords');
    }
  }
  
  protected function getImage($request) {
    if(isset($request->pageObject->seo['image'])) return $request->pageObject->seo['image'];
    
    if(isset($request->data) and $request->data == '404') return ROOT_URL.'img/fb_logo.jpg';
    
    switch($request->module) {
      default:
        return ROOT_URL.'img/fb_logo.jpg';
    }
  }
}
