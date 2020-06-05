<?php
class Sitemap {
  public static function generate() {
    global $CFG;

    $sitemapFile = ROOT.DIR_FRONT.'/sitemap.xml';

    $baseSitemap = '<?xml version="1.0" encoding="UTF-8"?>'
                .'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    foreach($CFG['SITE_LANGS'] as $lang) {
      $baseSitemap .= '<sitemap>'
                      .'<loc>'.ROOT_URL.'sitemaps/sitemap_'.$lang.'.xml</loc>'
                   .'</sitemap>';

      self::generateSitemapByLang($lang);
    }

    $baseSitemap .= '</sitemapindex>';

    if($fh = @fopen($sitemapFile,"w")) {
      fwrite($fh, $baseSitemap);
      fclose($fh);
    } else {
      throw new Exception("Unable to create sitemap.xml");
    }

    Settings::set('sitemap_updated', date('d.m.Y (H:i)'), 'seo');

    if(Settings::getSetting('seo_enabled', 'seo')) {
      self::enableSEO();
    } else {
      self::disableSEO();
    }
  }

  public static function enableSEO() {
    $robotsText = 'User-agent: *
Disallow: /admin_resources
Disallow: /js
Sitemap: '.ROOT_URL.'sitemap.xml';

    if($fh = @fopen(ROOT.DIR_FRONT.'/robots.txt',"w")) {
      fwrite($fh, $robotsText);
      fclose($fh);
    } else {
      throw new Exception("Unable to create robots.txt file");
    }
  }

  public static function disableSEO() {
    $robotsText = 'User-agent: *
Disallow: /';

    if($fh = @fopen(ROOT.DIR_FRONT.'/robots.txt',"w")) {
      fwrite($fh, $robotsText);
      fclose($fh);
    } else {
      throw new Exception("Unable to create robots.txt file");
    }
  }

  private static function generateSitemapByLang($lang) {
    $sitemapFile = ROOT.DIR_FRONT.'/sitemaps/sitemap_'.$lang.'.xml';

    $baseSitemap = '<?xml version="1.0" encoding="UTF-8"?>'
                    .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    $pagesDir = ROOT.DIR_BACK.'/pages/';
    $pages = preg_grep('/^([^.])/', scandir($pagesDir));
    $childPages = array();
    foreach ($pages as $page) {
      if($page != '.' && $page != '..' && is_dir($pagesDir.$page)){
        $scanPage = preg_grep('/^([^.])/', scandir($pagesDir.$page.'/'));
        foreach ($scanPage as $simple) {
          if($simple != '.' && $simple != '..' ){
            $childPages[] = $simple;
          }
        }
      }
    }

    foreach($pages as $page) {
      if($page == '404') continue;

      if($page != '.' && $page != '..' && is_dir($pagesDir.$page)) {

        foreach ($childPages as $childPage) {
          $classFile = $pagesDir.$page.'/'.str_replace('-', '_', ucfirst($childPage));
          $childClass = substr($childPage,0,'-4');

          if(file_exists($classFile)) {
            include_once $classFile;
            $pageClassName = 'Page_'.str_replace('-', '_', ucfirst($childClass));
            $pageInstance = new $pageClassName((object) array( 'module' => 'home', 'action' => '', 'id' => '', 'extra' => '' ));

            if(method_exists($pageInstance, 'getLinksForSitemap')) {
              $links = $pageInstance->getLinksForSitemap($lang);

              foreach($links as $link) {
                $link = (object) $link;
                if(!isset($link->url)) continue;

                $baseSitemap .= '<url>'
                      .'<loc>'.$link->url.'</loc>'
                      .(isset($link->changefreq) ? '<changefreq>'.$link->changefreq.'</changefreq>' : '')
                      .(isset($link->priority) ? '<priority>'.$link->priority.'</priority>' : '')
                    .'</url>';
              }
            }
          }
        }
      }
    }

    $baseSitemap .= '</urlset>';

    if($fh = @fopen($sitemapFile,"w")) {
      fwrite($fh, $baseSitemap);
      fclose($fh);
    } else {
      throw new Exception('Unable to create sitemap_'.$lang.'.xml');
    }
  }
}
