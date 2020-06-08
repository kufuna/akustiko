<?php

class Page_Project_inner extends Page
{
    private $data = null;

    public function __construct($request)
    {
        parent::__construct($request);
    }

//    protected $paramBinding = array(
//        1 => array('name' => 'title', 'type' => 'string', 'default' => ''),
//        2 => array('name' => 'id', 'type' => 'number', 'default' => 0)
//    );

    public function loadData()
    {

//        $article = DB::get(array(
//            'table' => 'news',
//            'query' => array('active' => 1, 'id' => $this->id),
//            'single' => 'true'
//        ));
//
//        if (!$article) throw new Exception("No post found");
//
//        $this->seo = array(
//            'title' => $this->escape($article->title) . ' | ' . L('news_seo_title'),
//            'description' => $this->description($article->text),
//            'keywords' => $this->keywords($this->escape($article->title)),
//            'image' => $article->image_inner ? ROOT_URL . 'uploads/news/' . $article->image_inner : ROOT_URL . 'img/fb_logo_' . Lang::$lang . '.jpg',
//        );

        $this->data = (object)array(
//            'article' => $article,
        );

    }

    public function getTemplates()
    {
        $this->request->canonical = $this->getCanonical();
        $this->request->alternates = $this->getAlternates();
        $this->request->pageObject = $this;

        $this->request->data = $this->data;

        $pageHead = new PageHead();
        $pageScripts = new PageScripts();

        return array(
            $pageHead->init($this->request),
            PageHeader::init($this->request),
            (object)array("tpl" => "projects-inner", "data" => $this->data),
            PageFooter::init($this->request),
            $pageScripts->init($this->request),
            (object)array("tpl" => "foot", "data" => null)
        );
    }

//    public function getAlternates()
//    {
//
//        global $CFG;
//
//        $result = array();
//
//        foreach ($CFG['SITE_LANGS'] as $l) {
//
//            $article = DB::get(array(
//                'table' => 'news_' . $l,
//                'query' => array('active' => 1, 'id' => $this->id),
//                'multiLang' => false
//            ));
//
//            if (!$article) continue;
//
//            $result[$l] = ROOT_URL . $l . '/article/' . URL::escapeURL($article->title) . '/' . $article->id;
//        }
//
//        return (object)$result;
//
//    }

//    public function getLinksForSitemap($lang)
//    {
//        $news = DB::get(array(
//            'table' => 'news_' . $lang,
//            'query' => array('active' => 1),
//            'multiLang' => false
//        ));
//
//        $links = array();
//
//        foreach ($news as $article) {
//            $links[] = array(
//                'url' => ROOT_URL . $lang . '/article/' . URL::escapeURL($article->title) . '/' . $article->id . '/',
//                'changefreq' => 'weekly',
//                'priority' => '1.0'
//            );
//        }
//
//        return $links;
//    }

    public function getCanonical()
    {
//        return SITE_URL . 'article/' . URL::escapeURL($this->title) . '/' . $this->id . '/';
        return SITE_URL . 'project-inner/';
    }

    public $relatedPages = array(
        'news'
    );


}
